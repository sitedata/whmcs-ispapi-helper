<?php
namespace ISPAPI;

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
            $sql = preg_replace("/##KEYS##/", $fkeys, $sql, 1);
            $sql = preg_replace("/##VALUES##/", $fvals, $sql, 1);
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
}
