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
            if ($result["success"]) {
                $result["insertid"] = $pdo->lastInsertId();//before commit!
            } else {
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
            $r = self::SQLCall("SELECT currency FROM tblclients WHERE id=:id", array(":id" => $ca->getUserID()));
            if ($r["success"]) {
                $currency = $r["result"]["currency"];
            }
        }
                
        //no currency neither provided as request parameter nor by session (not logged in)
        if (empty($currency)) {
            $r = self::SQLCall("SELECT id FROM tblcurrencies WHERE `default`=1");
            if ($r["success"]) {
                $currency = $r["result"]["id"];
            }
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
        mail("kschwarz@hexonet.net", "dbg", print_r($result, true));
        if ($r["result"] == "success") {
            return Helper::getClientsDetailsByEmail($contact["EMAIL"][0]);
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
     * @param WHMCS\Domains\Domain $domain domain object
     * @param array $apidata StatusDomain PROPERTY data from API
     * @param string $gateway payment gateway
     * @param array $clientdetails client details e.g. id, currency
     * @param string $renewprice recurring amount
     *
     * @return bool domain creation result
     */
    public static function createDomain($domain, $apidata, $gateway, $clientdetails, $renewprice, $domainprice, $premiumpricing = array())
    {
        $is_premium = (int) preg_match("/^PREMIUM_/", $apidata["SUBCLASS"][0]);
        
        $info = [
            ":userid" => $clientdetails["id"],
            ":orderid" => 0,
            ":type" => "register",
            ":registrationdate" => $apidata["CREATEDDATE"][0],
            ":domain" => strtolower($domain->getDomain()),
            ":firstpaymentamount" => $domainprice,//normal price
            ":recurringamount" => $renewprice,//normal price
            ":registrationperiod" => 1,
            ":status" => "Active",
            ":paymentmethod" => $gateway,
            ":expirydate" => $apidata["PAIDUNTILDATE"][0],
            ":nextduedate" => $apidata["PAIDUNTILDATE"][0],
            ":nextinvoicedate" => $apidata["PAIDUNTILDATE"][0],
            ":dnsmanagement" => 0,
            ":emailforwarding" => 0,
            ":idprotection" => (int) !empty($apidata["X-ACCEPT-WHOISTRUSTEE-TAC"][0]),
            ":donotrenew" => 0,
            ":is_premium" => $is_premium,
            ":registrar" => "ispapi",
            ":subscriptionid" => ""
        ];
        $r = Helper::SQLCall("INSERT INTO tbldomains ({{KEYS}}) VALUES ({{VALUES}})", $info, "execute");

        if ($r["success"]) {
            if ($is_premium) {
                $addcurrency = false;
                if (array_key_exists("transfer", $premiumpricing)) {//register is not available for registered domains
                    $extraDetails = \WHMCS\Domain\Extra::firstOrNew([
                        "domain_id" => $r["insertid"],
                        "name" => "registrarCostPrice"
                    ]);
                    $extraDetails->value = $premiumpricing["transfer"];//register is not available for registered domains
                    $extraDetails->save();
                    $addcurrency = true;
                }
                if (array_key_exists("renew", $premiumpricing)) {
                    $extraDetails = \WHMCS\Domain\Extra::firstOrNew([
                        "domain_id" => $r["insertid"],
                        "name" => "registrarRenewalCostPrice"
                    ]);
                    $extraDetails->value = $premiumpricing["renew"];
                    $extraDetails->save();
                    $addcurrency = true;
                }
                if ($addcurrency) {
                    $currency = \WHMCS\Billing\Currency::where("code", $premiumpricing["CurrencyCode"])->first();
                    $extraDetails = \WHMCS\Domain\Extra::firstOrNew([
                        "domain_id" => $r["insertid"],
                        "name" => "registrarCurrency"
                    ]);
                    $extraDetails->value = $currency->id;
                    $extraDetails->save();
                }
            }
            //--- care about extension flags (we could handle this in DomainSync instead)
            $addflds = new ISPAPI\AdditionalFields();
            $addflds->setDomain($domain->getDomain())->setFieldValuesFromAPI($apidata)->saveToDatabase($r["insertid"]);
        }
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

        $domainObj = new \WHMCS\Domains\Domain($domain);
        $registrarModule = new \WHMCS\Module\Registrar();
        if (!$registrarModule->load($registrar)) {
            throw new \WHMCS\Exception("No Registrar Configured");
        }
        $registrarCfg = $registrarModule->call("config", getregistrarconfigoptions($registrar));

        if (!preg_match('/\.(.*)$/i', $domain)) {
            return array(
                "success" => false,
                "msgid" => 'domainnameinvaliderror'
            );
        }
        if (Helper::checkDomainExists($domain)) {
            return array(
                "success" => false,
                "msgid" => 'alreadyexistingerror'
            );
        }
        
        $r = Helper::APICall($registrar, array(
            "COMMAND" => "StatusDomain",
            "DOMAIN"  => $domain
        ));
        if (!($r["CODE"] == 200)) {
            return array(
                "success" => false,
                "msgid" => null,
                "msg" => $r["DESCRIPTION"]
            );
        }
        $registrant = $r["PROPERTY"]["OWNERCONTACT"][0];
        if (!$registrant) {
            return array(
                "success" => false,
                "msgid" => "registrantmissingerror"
            );
        }
        if (!isset($contacts[$registrant])) {
            $r2 = Helper::APICall($registrar, array(
                "COMMAND" => "StatusContact",
                "CONTACT"  => $registrant
            ));
            if (!($r2["CODE"] == 200)) {
                return array(
                    "success" => false,
                    "msgid" => "registrantfetcherror"
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
                "success" => false,
                "msgid" => "registrantcreateerrornophone"
            );
        }
        $client = Helper::getClientsDetailsByEmail($contact["EMAIL"][0]);
        if (!$client) {
            $client = Helper::addClient($contact, $currency, $password);
            if (!$client) {
                return array(
                    "success" => false,
                    "msgid" => "registrantcreateerror"
                );
            }
        }

        // get tld-specific price and addon configuration details
        $domainprices = localAPI('GetTLDPricing', array(
            'currencyid' => $client["currency"]
        ));
        if ($domainprices["result"] != "success") {
            return array(
                "success" => false,
                "msgid" => "tldrenewalpriceerror"
            );
        }
        $tld = $domainObj->getTLD();
        if (!isset($domainprices["pricing"][$tld]['renew']['1'])) {
            return array(
                "success" => false,
                "msgid" => "tldrenewalpriceerror"
            );
        }
        $prices = $domainprices["pricing"][$tld];
        $renewprice = $prices['renew']['1'];
        $domainprice = $prices['register']['1'];

        //--- consider add-on prices when configured in WHMCS and active on domain level
        if ($prices['addons']["idprotect"] && !empty($r["PROPERTY"]["X-ACCEPT-WHOISTRUSTEE-TAC"][0])) {
            $addonsPricing = \WHMCS\Database\Capsule::table("tblpricing")
                ->where("type", "domainaddons")
                ->where("currency", $client["currency"])
                ->where("relid", 0)->first(array("ssetupfee"));
            $domainprice += $addonsPricing->ssetupfee; // * $regperiod here: 1
            $renewprice += $addonsPricing->ssetupfee; // * $regperiod here: 1
        }
        //--- consider taxes
        if (\WHMCS\Config\Setting::getValue("TaxEnabled") && \WHMCS\Config\Setting::getValue("TaxInclusiveDeduct")) {
            $excltaxrate = 1;
            $taxdata = getTaxRate(1, $client["state"], $client["country"]);
            $taxrate = $taxdata["rate"] / 100;
            $taxdata = getTaxRate(2, $client["state"], $client["country"]);
            $taxrate2 = $taxdata["rate"] / 100;
            if (\WHMCS\Config\Setting::getValue("TaxType") == "Inclusive" && (!$taxrate && !$taxrate2 || $client["taxexempt"])) {
                $systemFirstTaxRate = \WHMCS\Database\Capsule::table("tbltax")->value("taxrate");
                if ($systemFirstTaxRate) {
                    $excltaxrate = 1 + $systemFirstTaxRate / 100;
                }
            }
            $domainprice = round($domainprice / $excltaxrate, 2);
            $renewprice = round($renewprice / $excltaxrate, 2);
        }
        
        // get premium price
        $premiumpricing = array();
        if (preg_match("/^PREMIUM_/", $r["PROPERTY"]["SUBCLASS"][0])) {
            if (!(bool) (int) \WHMCS\Config\Setting::getValue("PremiumDomains")) {
                return array(
                    "success" => false,
                    "msgid" => "premiumdomainsinactive" // TODO
                );
            }
            $premiumpricing = $registrarModule->call("GetPremiumPrice", [
                "domain" => $domainObj,
                "sld" => $domainObj->getSecondLevel(),
                "tld" => $domainObj->getDotTopLevel(),
                "type" => array("renew")
            ]);
            if (!isset($premiumpricing['renew'])) {
                return array(
                    "success" => false,
                    "msgid" => "tldrenewalpriceerror"
                );
            }
        }

        $result = Helper::createDomain($domainObj, $r["PROPERTY"], $gateway, $client, $renewprice, $domainprice, $premiumpricing);
        if (!$result) {
            return array(
                "success" => false,
                "msgid" => "domaincreateerror"
            );
        }
        return array(
            "success" => true,
            "msgid" => "ok"
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
