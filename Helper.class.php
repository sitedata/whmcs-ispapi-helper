<?php
namespace ISPAPINEW;

use WHMCS\Database\Capsule;
use PDO;

//use WHMCS_ClientArea;//no idea why we should keep it

if (defined("ROOTDIR")) {
    require_once(implode(DIRECTORY_SEPARATOR, array(ROOTDIR,"includes","registrarfunctions.php")));
}

/**
 * PHP Helper Class
 *
 * @copyright  2018 HEXONET GmbH
 */
class Helper
{
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
     * Set $debug = true in the function to have DEBUG output in the JSON string
     *
     * @param string $sql The SQL query
     * @param array $params The parameters of the query DEFAULT = NULL
     * @param $fetchmode The fetching mode of the query (fetch, fetchall, execute) - DEFAULT = fetch

     * @return array response where boolean property "success" tells you if the query was successful or not
     * and property "result" only exists in case of success and covers the expected response format.
     * In case of execute failed (or thrown error), check property "errormsg" for the error details.
     */
    public static function SQLCall($sql, $params = null, $fetchmode = "fetch")
    {
        $debug = false;
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
        } catch (Exception $e) {
            logModuleCall(
                'provisioningmodule',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );
            $pdo->rollBack();
            $result["errormsg"] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Return list of available Payment Gateways
     *
     * @return array list of payment gateways
     */
    public static function getPaymentGateways()
    {
        $gateways = array();
        $r = Helper::SQLCall("SELECT `gateway`, `value` FROM tblpaymentgateways WHERE setting=:setting and `order`", array(
            ":setting" => "name"
        ), "fetchall");
        if ($r["success"]) {
            foreach ($r["result"] as $row) {
                $gateways[$row["gateway"]] = $row["value"];
            }
        }
        return $gateways;
    }

    /**
     * Return list of available Currencies
     *
     * @return array list of currencies
     */
    public static function getCurrencies()
    {
        $currencies = array();
        $r = Helper::SQLCall("SELECT `code`, `id` FROM tblcurrencies", array(), "fetchall");
        if ($r["success"]) {
            foreach ($r["result"] as $row) {
                $currencies[$row["id"]] = $row["code"];
            }
        }
        return $currencies;
    }

    /**
     * Get client id by given email address
     *
     * @return string|boolean the client id or false if not found
     */
    public static function getClientIdByEmail($email)
    {
        $r = Helper::SQLCall("SELECT `id` FROM tblclients WHERE email=:email LIMIT 1", array(
            ":email" => $email
        ), "fetch");
        if ($r["success"]) {
            return $r["result"]["id"];
        }
        return false;
    }

    /**
     * Get currency by given client id
     *
     * @return string|false client's currency or false if not found
     */
    public static function getCurrencyByClientId($clientid)
    {
        $r = Helper::SQLCall("SELECT `currency` FROM tblclients WHERE id=:id", array(
            ":id" => $clientid
        ), "fetch");
        if ($r["success"]) {
            return $r["result"]["currency"];
        }
        return false;
    }

    /**
     * get domain prices by currency id
     *
     * @return array list of domain prices
     */
    public static function getDomainPrices($currencyid)
    {
        $r = Helper::SQLCall("SELECT tdp.extension, tp.type, msetupfee year1, qsetupfee year2, ssetupfee year3, asetupfee year4, bsetupfee year5, monthly year6, quarterly year7, semiannually year8, annually year9, biennially year10 FROM tbldomainpricing tdp, tblpricing tp WHERE tp.relid=tdp.id AND tp.currency=:currency", array(
            ":currency" => $currencyid
        ), "fetchall");
        if ($r["success"]) {
            foreach ($r["result"] as $key => &$row) {
                for ($i=1; $i<=10; $i++) {
                    // TODO: think about this idea
                    // move this to WHERE clause in SQL statement: one of year1-10 != 0
                    // leave this filter work to the DB itself
                    if ($row['year'.$i] != 0) {
                        $domainprices[$row['extension']][$row['type']][$i] = $row['year'.$i];
                    }
                }
            }
        }
        return $domainprices;
    }

    /**
     * Create a new client by given API contact data and return the client id.
     *
     * @param array $contact StatusContact PROPERTY data from API
     * @param string $currency currency
     *
     * @return string|bool client id or false in error case
     */
    public static function createClient($contact, $currency, $password)
    {
        $info = array(
            ":firstname" => $contact["FIRSTNAME"][0],
            ":lastname" => $contact["LASTNAME"][0],
            ":companyname" => $contact["ORGANIZATION"][0],
            ":email" => $contact["EMAIL"][0],
            ":address1" => $contact["STREET"][0],
            ":address2" => $contact["STREET"][1],
            ":city" => $contact["CITY"][0],
            ":state" => $contact["STATE"][0],
            ":postcode" => $contact["ZIP"][0],
            ":country" => strtoupper($contact["COUNTRY"][0]),
            ":phonenumber" => $contact["PHONE"][0],
            ":password" => $password,
            ":currency" => $currency,
            ":language" => "English",
            ":credit" => "0.00",
            ":lastlogin" => "0000-00-00 00:00:00",
            ":phonenumber" => preg_replace('/^\+/', '', $info["phonenumber"]) || "NONE",
            ":postcode" => preg_replace('/[^0-9a-zA-Z ]/', '', $info["postcode"] || "N/A")
        );
        $r = Helper::SQLCall("INSERT INTO tblclients (datecreated, {{KEYS}}) VALUES (now(), {{VALUES}})", $info, "execute");
        if ($r["success"]) {
            return Helper::getClientIdByEmail($contact["EMAIL"][0]);
        }
        return false;
    }

    /**
     * Create a domain by given data
     *
     * @param string $domain domain name
     * @param array $apidata StatusDomain PROPERTY data from API
     * @param string $gateway payment gateway
     * @param string $client client id
     * @param string $recurringamount recurring amount
     *
     * @return bool domain creation result
     */
    public static function createDomain($domain, $apidata, $gateway, $client, $recurringamount)
    {
        $info = array(
            ":userid" => $client,
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
        if (!preg_match('/(\..*)$/i', $domain, $m)) {
            return array(
                success => false,
                msgid => 'domainnameinvaliderror'
            );
        }
        $tld = strtolower($m[1]);
        $r = Helper::SQLCall("SELECT `id` FROM tbldomains WHERE domain=:domain AND status IN ('Pending', 'Pending Transfer', 'Active') AND registrar='ispapi' LIMIT 1", array(
            ":domain" => $domain
        ), "fetch");
        if ($r["success"] && $r["result"]) {
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
        $clientid = Helper::getClientIdByEmail($contact["EMAIL"][0]);
        if (!$clientid) {
            $clientid = Helper::createClient($contact, $currency, $password);
            if (!$clientid) {
                return array(
                    success => false,
                    msgid => "registrantcreateerror"
                );
            }
        }
        $domainprices = Helper::getDomainPrices(Helper::getCurrencyByClientId($clientid));
        if (!isset($domainprices[$tld]['domainrenew'][1])) {
            return array(
                success => false,
                msgid => "tldrenewalpriceerror"
            );
        }
        $result = Helper::createDomain($domain, $r["PROPERTY"], $gateway, $clientid, $domainprices[$tld]['domainrenew'][1]);
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
