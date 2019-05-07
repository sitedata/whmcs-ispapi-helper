<?php
namespace ISPAPI;

use WHMCS\Database\Capsule;
use WHMCS_ClientArea;
use PDO;

if (defined("ROOTDIR")) {
    require_once(implode(DIRECTORY_SEPARATOR, array(ROOTDIR,"includes","registrarfunctions.php")));
}

/**
 * PHP Helper Class
 *
 * @copyright  2018 HEXONET GmbH, MIT License
 */
class Helper
{
    public static $currencies = null;
    public static $paymentmethods = null;

    /*
     * Helper to send API command to the given registrar. Returns the response
     *
     * @param string $registrar The registrar
     * @param string $command The API command to send
     *
     * @return array The response from the API
     */
    public static function APICall($registrar, $command)
    {
        $registrarconfigoptions = getregistrarconfigoptions($registrar);
        $registrar_config = call_user_func($registrar."_config", $registrarconfigoptions);
        return call_user_func($registrar."_call", $command, $registrar_config);
    }

    /*
     * Helper to send API Response to the given registrar. Returns the parsed response
     *
     * @param string $registrar The registrar
     * @param string $response The API response to send
     *
     * @return array The parsed response from the API
     */
    public static function parseResponse($registrar, $response)
    {
        return call_user_func($registrar."_parse_response", $response);
    }

    /*
     * Helper to send SQL call to the Database with Capsule
     *
     *
     * @param string $sql The SQL query
     * @param array $params The parameters of the query DEFAULT = NULL
     * @param $fetchmode The fetching mode of the query (fetch, fetchall, execute) - DEFAULT = fetch
     * @param $debug Set to true to have the SQL Query in addition returned in case of an error

     * @return array response where boolean property "success" tells you if the query was successful or not
     * and property "result" only exists in case of success and covers the expected response format.
     * In case of execute failed (or thrown error), check property "errormsg" for the error details.
     */
    public static function SQLCall($sql, $params = null, $fetchmode = "fetch", $debug = false)
    {
        $result = array(
            "success" => false
        );

        // replace NULL values with empty string
        // check if this is still necessary after we switched to PHP-SDK
        $params = array_map(function ($v) {
            return (is_null($v)) ? "" : $v;
        }, $params);

        // for INSERTs apply a way to dynamically generate list of fields
        // and their values
        if (preg_match("/^INSERT /i", $sql)) {
            $keys = array_keys($params);
            $fkeys = implode(", ", preg_replace("/:/", " ", $keys));
            $fvals = implode(", ", $keys);
            $sql = preg_replace("/\{\{KEYS\}\}/", $fkeys, $sql, 1);
            $sql = preg_replace("/\{\{VALUES\}\}/", $fvals, $sql, 1);
        }

        // now execute SQL statement and return result in requested format
        $pdo = Capsule::connection()->getPdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare($sql);
            $result["success"] = $stmt->execute($params);
            switch ($fetchmode) {
                case "execute":
                    // we won't have a result property as not expected
                    break;
                case "fetchall":
                    $result["result"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    break;
                default:
                    $result["result"] = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
            }
            if (!$result["success"]) { // execute failed
                // return the reason: http://php.net/manual/de/pdostatement.errorinfo.php
                $result["errormsg"] = implode(", ", $stmt->errorInfo());
            }
            $pdo->commit();
        } catch (\Exception $e) {
            logModuleCall(
                'provisioningmodule',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );
            $pdo->rollBack();
            $result["errormsg"] = $e->getMessage();
            if ($debug) {
                $result["sqlquery"] = $sql;
            }
        }
        return $result;
    }

    /**
     * Return list of available Payment Gateways
     *
     * @return array list of payment gateways
     */
    public static function getPaymentMethods()
    {
        if (!self::$paymentmethods) {
            self::$paymentmethods = array();
            $r = localAPI("GetPaymentMethods", array());
            if ($r["result"]) {
                foreach ($r["paymentmethods"]["paymentmethod"] as $pm) {
                    self::$paymentmethods[$pm["module"]] = $pm["displayname"];
                }
            }
        }
        return self::$paymentmethods;
    }


    /**
     * load configured currencies
     * @return array assoc array, list of currencies where currency code is array key
     */
    public static function getCurrencies()
    {
        if (!self::$currencies) {
            self::$currencies = array();
            $results = localAPI('GetCurrencies', array());
            if ($results["result"]=="success") {
                foreach ($results["currencies"]["currency"] as $idx => $d) {
                    self::$currencies[$d["code"]] = $d;
                }
            }
        }
        return self::$currencies;
    }

    /**
     * get currency id of a currency identified by given currency code
     * @param string $currency currency code
     * @return null|int currency id or null if currency is not configured
     */
    private function getCurrencyId($currency)
    {
        $cs = self::getCurrencies();
        return (!isset($cs[$currency]) ? null : $cs[$currency]["id"]);
    }

    /*
     * Returns the customer selected currency id.
     *
     * @return string Currency ID of the user
     */
    public static function getCustomerCurrency()
    {
        //first take the currency from the URL or from the session; do not move this line!
        $currency = isset($_REQUEST["currency"]) ? $_REQUEST["currency"] : $_SESSION["currency"];

        //if customer logged in, set the configured currency.
        $ca = new WHMCS_ClientArea();
        if ($ca->isLoggedIn()) {
            $user = self::SQLCall("SELECT currency FROM tblclients WHERE id=:id", array(":id" => $ca->getUserID()));
            return $user["currency"];
        }
                
        //no currency neither provided as request parameter nor by session (not logged in)
        if (empty($currency)) {
            $default = self::SQLCall("SELECT id FROM tblcurrencies WHERE `default`=1");
            return $default["id"];
        }
        return $currency;
    }


    /**
     * Get client details by given email address
     *
     * @return array|boolean the client id or false if not found
     */
    public static function getClientsDetailsByEmail($email)
    {
        $r = localAPI('GetClientsDetails', array('email' => $email));
        if ($r["result"]=="success") {
            $details = array();
            return $r["client"];
        }
        return false;
    }

    /**
     * Create a new client by given API contact data and return the client id.
     *
     * @param array $contact StatusContact PROPERTY data from API
     * @param string $currency currency id
     *
     * @return string|bool client id or false in error case
     */
    public static function addClient($contact, $currencyid, $password)
    {
        $phone = preg_replace('/[^0-9 ]/', '', $contact["PHONE"][0]);//only numbers and spaces allowed
        $zip = preg_replace('/[^0-9a-zA-Z ]/', '', $contact["ZIP"][0]);
        $request = array(
            "firstname" => $contact["FIRSTNAME"][0],
            "lastname" => $contact["LASTNAME"][0],
            "email" => $contact["EMAIL"][0],
            "address1" => $contact["STREET"][0],
            "city" => $contact["CITY"][0],
            "state" => $contact["STATE"][0],
            "postcode" => $zip ? $zip : "N/A",
            "country" => strtoupper($contact["COUNTRY"][0]),
            "phonenumber" => $phone,
            "password2" => $password,
            "currency" => $currencyid,
            "language" => "english"
        );
        if (!empty($contact["ORGANIZATION"][0])) {
            $request["companyname"] = $contact["ORGANIZATION"][0];
        }
        if (!empty($contact["STREET"][1])) {
            $request["address2"] = $contact["STREET"][1];
        }
        $result = localAPI('AddClient', $request);
        if ($r["result"] == "success") {
            return Helper::getClientsDetails($contact["EMAIL"][0]);
        }
        return false;
    }

    /**
     * Check if a domain already exists in WHMCS database
     * @param string $domain domain name
     * @return boolean check result
     */
    public static function checkDomainExists($domain)
    {
        $r = localAPI('GetClientsDomains', array(
            'domain' => $domain,
            'limitnum' => 1
        ));
        if ($r["result"] == "success") {
            return $r["totalresults"] > 0;
        }
        return false;
    }

    /**
     * Create a domain by given data
     *
     * @param string $domain domain name
     * @param array $apidata StatusDomain PROPERTY data from API
     * @param string $gateway payment gateway
     * @param string $clientid client id
     * @param string $recurringamount recurring amount
     *
     * @return bool domain creation result
     */
    public static function createDomain($domain, $apidata, $gateway, $clientid, $recurringamount)
    {
        $info = array(
            ":userid" => $clientid,
            ":orderid" => 0,
            ":type" => "Register",
            ":registrationdate" => $apidata["CREATEDDATE"][0],
            ":domain" => strtolower($domain),
            ":firstpaymentamount" => $recurringamount,
            ":recurringamount" => $recurringamount,
            ":paymentmethod" => $gateway,
            ":registrar" => "ispapi",
            ":registrationperiod" => 1,
            ":expirydate" => $apidata["PAIDUNTILDATE"][0],
            ":subscriptionid" => "",
            ":status" => "Active",
            ":nextduedate" => $apidata["PAIDUNTILDATE"][0],
            ":nextinvoicedate" => $apidata["PAIDUNTILDATE"][0],
            ":dnsmanagement" => "on",
            ":emailforwarding" => "on"
        );
        $r = Helper::SQLCall("INSERT INTO tbldomains ({{KEYS}}) VALUES ({{VALUES}})", $info, "execute");
        return $r["success"];
    }

    /**
     * import an existing domain from HEXONET API.
     *
     * @param string $domain domain name
     * @param string $registrar registrar id
     * @param string $gateway payment gateway
     * @param string $currency currency
     * @param string $password the default password we set for newly created customers
     * @param array  $contacts contact data container
     *
     * @return array where property "success" (boolean) identifies the import result and property "msgid" the translation/language key
     */
    public static function importDomain($domain, $registrar, $gateway, $currency, $password, &$contacts)
    {
        if (!preg_match('/\.(.*)$/i', $domain, $m)) {
            return array(
                success => false,
                msgid => 'domainnameinvaliderror'
            );
        }
        $tld = strtolower($m[1]);
        if (Helper::checkDomainExists($domain)) {
            return array(
                success => false,
                msgid => 'alreadyexistingerror'
            );
        }
        $r = Helper::APICall($registrar, array(
            "COMMAND" => "StatusDomain",
            "DOMAIN"  => $domain
        ));
        if (!($r["CODE"] == 200)) {
            return array(
                success => false,
                msgid => null,
                msg => $r["DESCRIPTION"]
            );
        }
        $registrant = $r["PROPERTY"]["OWNERCONTACT"][0];
        if (!$registrant) {
            return array(
                success => false,
                msgid => "registrantmissingerror"
            );
        }
        if (!isset($contacts[$registrant])) {
            $r2 = Helper::APICall($registrar, array(
                "COMMAND" => "StatusContact",
                "CONTACT"  => $registrant
            ));
            if (!($r2["CODE"] == 200)) {
                return array(
                    success => false,
                    msgid => "registrantfetcherror"
                );
            }
            $contacts[$registrant] = $r2["PROPERTY"];
        }
        $contact = $contacts[$registrant];
        if ((!$contact["EMAIL"][0]) || (preg_match('/null$/i', $contact["EMAIL"][0]))) {
            $contact["EMAIL"][0] = "info@".$domain;
        }
        if (empty($contact["PHONE"][0])) {
            return array(
                success => false,
                msgid => "registrantcreateerrornophone"
            );
        }
        $client = Helper::getClientsDetailsByEmail($contact["EMAIL"][0]);
        if (!$client) {
            $client = Helper::addClient($contact, $currency, $password);
            if (!$client) {
                return array(
                    success => false,
                    msgid => "registrantcreateerror"
                );
            }
        }
        $domainprices = localAPI('GetTLDPricing', array(
            'currencyid' => $client["currency"]
        ));
        if (!$domainprices["result"] == "success") {
            return array(
                success => false,
                msgid => "tldrenewalpriceerror"
            );
        }
        if (!isset($domainprices["pricing"][$tld]['renew']['1'])) {
            return array(
                success => false,
                msgid => "tldrenewalpriceerror"
            );
        }
        $result = Helper::createDomain($domain, $r["PROPERTY"], $gateway, $client["id"], $domainprices["pricing"][$tld]['renew']['1']);
        if (!$result) {
            return array(
                success => false,
                msgid => "domaincreateerror"
            );
        }
        return array(
            success => true,
            msgid => "ok"
        );
    }

    public static $stringCharset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public static function generateRandomString($length = 10)
    {
        $characters = Helper::$stringCharset;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
