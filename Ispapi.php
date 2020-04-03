<?php

namespace WHMCS\Module\Registrar\Ispapi;

use \HEXONET\APIClient;

if (defined("WHMCS")) {
    if (!function_exists("getregistrarconfigoptions")) {
        require_once implode(DIRECTORY_SEPARATOR, [ROOTDIR, "includes", "registrarfunctions.php"]);
    }
}

// ---------------------------------------------------------------
// PHP-SDK
// ---------------------------------------------------------------
$path = implode(DIRECTORY_SEPARATOR, [__DIR__, "sdk", "src", ""]);
include_once($path . "SocketConfig.php");
include_once($path . "APIClient.php");
include_once($path . "Column.php");
include_once($path . "Logger.php");
include_once($path . "Record.php");
include_once($path . "ResponseTemplate.php");
include_once($path . "Response.php");
include_once($path . "ResponseParser.php");
include_once($path . "ResponseTemplateManager.php");
// ---------------------------------------------------------------

class Ispapi
{
    public static $tldclassmap = [
        'ACIM' => '.ac.im',
        'ACMU' => '.ac.mu',
        'ACNZ' => '.ac.nz',
        'AAAPRO' => '.aaa.pro',
        'ACAPRO' => '.aca.pro',
        'ACCTPRO' => '.acct.pro',
        'ADULTHT' => '.adult.ht',
        'AEORG' => '.ae.org',
        'ARCOM' => '.ar.com',
        'ARCPRO' => '.arc.pro',
        'ARTHT' => '.art.ht',
        'ARTSNF' => '.arts.nf',
        'ASSOHT' => '.asso.ht',
        'AVOCATPRO' => '.avocat.pro',
        'BARPRO' => '.bar.pro',
        'BIZKI' => '.biz.ki',
        'BIZPL' => '.biz.pl',
        'BIZPR' => '.biz.pr',
        'BRCOM' => '.br.com',
        'CLUBTW' => '.club.tw',
        'COAE' => '.co.ae',
        'COAG' => '.co.ag',
        'COAM' => '.co.am',
        'COAT' => '.co.at',
        'COBZ' => '.co.bz',
        'COCM' => '.co.cm',
        'COCOM' => '.co.com',
        'COGG' => '.co.gg',
        'COGL' => '.co.gl',
        'COGY' => '.co.gy',
        'COIM' => '.co.im',
        'COIN' => '.co.in',
        'COJE' => '.co.je',
        'COJP' => '.co.jp',
        'COKR' => '.co.kr',
        'COLC' => '.co.lc',
        'COMAF' => '.com.af',
        'COMBR' => '.com.br',
        'COMAG' => '.com.ag',
        'COMAI' => '.com.ai',
        'COMAM' => '.com.am',
        'COMAR' => '.com.ar',
        'COMAU' => '.com.au',
        'COMBZ' => '.com.bz',
        'COMCM' => '.com.cm',
        'COMCN' => '.com.cn',
        'COMCO' => '.com.co',
        'COMDE' => '.com.de',
        'COMEC' => '.com.ec',
        'COMES' => '.com.es',
        'COMG' => '.co.mg',
        'COMU' => '.co.mu',
        'COMGL' => '.com.gl',
        'COMGR' => '.com.gr',
        'COMGY' => '.com.gy',
        'COMHK' => '.com.hk',
        'COMHN' => '.com.hn',
        'COMHT' => '.com.ht',
        'COMIM' => '.com.im',
        'COMKI' => '.com.ki',
        'COMLC' => '.com.lc',
        'COMLV' => '.com.lv',
        'COMMG' => '.com.mg',
        'COMMS' => '.com.ms',
        'COMMT' => '.com.mt',
        'COMMU' => '.com.mu',
        'COMMX' => '.com.mx',
        'COMMY' => '.com.my',
        'COMNF' => '.com.nf',
        'COMPE' => '.com.pe',
        'COMPH' => '.com.ph',
        'COMPL' => '.com.pl',
        'COMPR' => '.com.pr',
        'COMPT' => '.com.pt',
        'COMRE' => '.com.re',
        'COMRO' => '.com.ro',
        'COMRU' => '.com.ru',
        'COMUA' => '.com.ua',
        'COMS' => '.co.ms',
        'COMSB' => '.com.sb',
        'COMSC' => '.com.sc',
        'COMSE' => '.com.se',
        'COMSG' => '.com.sg',
        'COMSO' => '.com.so',
        'COMTC' => '.com.tc',
        'COMTW' => '.com.tw',
        'COMVC' => '.com.vc',
        'COMVE' => '.com.ve',
        'CONL' => '.co.nl',
        'CONZ' => '.co.nz',
        'COUK' => '.co.uk',
        'COVE' => '.co.ve',
        'COZA' => '.co.za',
        'CNCOM' => '.cn.com',
        'CPAPRO' => '.cpa.pro',
        'DDSPRO' => '.dds.pro',
        'DECOM' => '.de.com',
        'DENPRO' => '.den.pro',
        'DNTPRO' => '.dnt.pro',
        'EBIZTW' => '.ebiz.tw',
        'EBIZTW' => '.ebiz.tw',
        'EDUCO' => '.edu.co',
        'ENGPRO' => '.eng.pro',
        'EPPUA' => '.epp.ua',
        'EUCOM' => '.eu.com',
        'FINEC' => '.fin.ec',
        'FIRMHT' => '.firm.ht',
        'FIRMIN' => '.firm.in',
        'FIRMNF' => '.firm.nf',
        'GAMETW' => '.game.tw',
        'GBCOM' => '.gb.com',
        'GBNET' => '.gb.net',
        'GENIN' => '.gen.in',
        'GENNZ' => '.gen.nz',
        'GOVCO' => '.gov.co',
        'GRCOM' => '.gr.com',
        'HUCOM' => '.hu.com',
        'HUNET' => '.hu.net',
        'IDAU' => '.id.au',
        'IDVTW' => '.idv.tw',
        'INDIN' => '.ind.in',
        'INFOEC' => '.info.ec',
        'INFOHT' => '.info.ht',
        'INFOKI' => '.info.ki',
        'INFONF' => '.info.nf',
        'INFOPL' => '.info.pl',
        'INFOPR' => '.info.pr',
        'INFOVE' => '.info.ve',
        'INFOVN' => '.info.vn',
        'INGPRO' => '.ing.pro',
        'INNET' => '.in.net',
        'JPNET' => '.jp.net',
        'JPNCOM' => '.jpn.com',
        'JURPRO' => '.jur.pro',
        'KIWINZ' => '.kiwi.nz',
        'KRCOM' => '.kr.com',
        'LAWPRO' => '.law.pro',
        'LTDCOIM' => '.ltd.co.im',
        'LTDIM' => '.ltd.im',
        'LTDUK' => '.ltd.uk',
        'MAORINZ' => '.maori.nz',
        'MEDEC' => '.med.ec',
        'MEDPRO' => '.med.pro',
        'MEUK' => '.me.uk',
        'MEXCOM' => '.mex.com',
        'MILCO' => '.mil.co',
        'MOBIKI' => '.mobi.ki',
        'NAMEPR' => '.name.pr',
        'NAMESLD' => '.name',
        'NETAE' => '.net.ae',
        'NETAF' => '.net.af',
        'NETAG' => '.net.ag',
        'NETAI' => '.net.ai',
        'NETAM' => '.net.am',
        'NETAU' => '.net.au',
        'NETBR' => '.net.br',
        'NETBZ' => '.net.bz',
        'NETCO' => '.net.co',
        'NETCM' => '.net.cm',
        'NETCN' => '.net.cn',
        'NETEC' => '.net.ec',
        'NETGG' => '.net.gg',
        'NETGL' => '.net.gl',
        'NETGY' => '.net.gy',
        'NETHN' => '.net.hn',
        'NETHT' => '.net.ht',
        'NETIM' => '.net.im',
        'NETIN' => '.net.in',
        'NETJE' => '.net.je',
        'NETKI' => '.net.ki',
        'NETLC' => '.net.lc',
        'NETLV' => '.net.lv',
        'NETMG' => '.net.mg',
        'NETMU' => '.net.mu',
        'NETMY' => '.net.my',
        'NETMX' => '.net.mx',
        'NETNF' => '.net.nf',
        'NETNZ' => '.net.nz',
        'NETPE' => '.net.pe',
        'NETPH' => '.net.ph',
        'NETPL' => '.net.pl',
        'NETPR' => '.net.pr',
        'NETRU' => '.net.ru',
        'NETSB' => '.net.sb',
        'NETSC' => '.net.sc',
        'NETSO' => '.net.so',
        'NETTC' => '.net.tc',
        'NETVC' => '.net.vc',
        'NETVE' => '.net.ve',
        'NETZA' => '.net.za',
        'NOCOM' => '.no.com',
        'NOMAG' => '.nom.ag',
        'NOMCO' => '.nom.co',
        'NOMES' => '.nom.es',
        'NOMPE' => '.nom.pe',
        'NOMRO' => '.nom.ro',
        'OFFAI' => '.off.ai',
        'ORAT' => '.or.at',
        'ORJP' => '.or.jp',
        'ORMU' => '.or.mu',
        'ORGAE' => '.org.ae',
        'ORGAF' => '.org.af',
        'ORGAG' => '.org.ag',
        'ORGAI' => '.org.ai',
        'ORGAM' => '.org.am',
        'ORGAU' => '.org.au',
        'ORGBZ' => '.org.bz',
        'ORGCN' => '.org.cn',
        'ORGCO' => '.org.co',
        'ORGES' => '.org.es',
        'ORGGG' => '.org.gg',
        'ORGGL' => '.org.gl',
        'ORGGR' => '.org.gr',
        'ORGHT' => '.org.ht',
        'ORGHN' => '.org.hn',
        'ORGIM' => '.org.im',
        'ORGIN' => '.org.in',
        'ORGJE' => '.org.je',
        'ORGKI' => '.org.ki',
        'ORGLC' => '.org.lc',
        'ORGLV' => '.org.lv',
        'ORGMG' => '.org.mg',
        'ORGMS' => '.org.ms',
        'ORGMU' => '.org.mu',
        'ORGMX' => '.org.mx',
        'ORGMY' => '.org.my',
        'ORGNZ' => '.org.nz',
        'ORGPE' => '.org.pe',
        'ORGPH' => '.org.ph',
        'ORGPL' => '.org.pl',
        'ORGPR' => '.org.pr',
        'ORGPT' => '.org.pt',
        'ORGRO' => '.org.ro',
        'ORGRU' => '.org.ru',
        'ORGSB' => '.org.sb',
        'ORGSC' => '.org.sc',
        'ORGSO' => '.org.so',
        'ORGTC' => '.org.tc',
        'ORGTW' => '.org.tw',
        'ORGUA' => '.org.ua',
        'ORGUK' => '.org.uk',
        'ORGVC' => '.org.vc',
        'ORGVE' => '.org.ve',
        'ORGWS' => '.org.ws',
        'ORGZA' => '.org.za',
        'OTHERNF' => '.other.nf',
        'PERNF' => '.per.nf',
        'PERSOHT' => '.perso.ht',
        'PHONEKI' => '.phone.ki',
        'PLCCOIM' => '.plc.co.im',
        'PLCUK' => '.plc.uk',
        'POLHT' => '.pol.ht',
        'PPRU' => '.pp.ru',
        'PROEC' => '.pro.ec',
        'PROHT' => '.pro.ht',
        'PROPR' => '.pro.pr',
        'PROTC' => '.pro.tc',
        'QCCOM' => '.qc.com',
        'RADIOAM' => '.radio.am',
        'RADIOFM' => '.radio.fm',
        'RECHTPRO' => '.recht.pro',
        'RECNF' => '.rec.nf',
        'RELHT' => '.rel.ht',
        'RUCOM' => '.ru.com',
        'SACOM' => '.sa.com',
        'SCHOOLNZ' => '.school.nz',
        'SECOM' => '.se.com',
        'SENET' => '.se.net',
        'SHOPHT' => '.shop.ht',
        'STBPRO' => '.stb.pro',
        'STORENF' => '.store.nf',
        'TELKI' => '.tel.ki',
        'TMFR' => '.tm.fr',
        'TMSE' => '.tm.se',
        'UKCOM' => '.uk.com',
        'UKNET' => '.uk.net',
        'USCOM' => '.us.com',
        'USORG' => '.us.org',
        'UYCOM' => '.uy.com',
        'WAWPL' => '.waw.pl',
        'WEBNF' => '.web.nf',
        'WEBVE' => '.web.ve',
        'WEBZA' => '.web.za',
        'ZACOM' => '.za.com'
    ];

    //NOTE: deactivated as not yet officially supported by WHMCS
    //this could get replaced by using CONVERTIDN command
    /*public static $idntldclassmap = [
        "XN--3DS443G" => ".在线",
        "XN--5TZM5G" => ".网站",
        "XN--6FRZ82G" => ".移动",
        "XN--80ASEHDB" => ".онлайн",
        "XN--80ASWG" => ".сайт",
        "XN--9DBQ2A" => ".קום",
        "XN--C1AVG" => ".орг",
        "XN--CZRS0T" => ".商店",
        "XN--FIQ228C5HS" => ".中文网",
        "XN--FJQ720A" => ".娱乐",
        "XN--H2BRJ9C" => ".भारत",
        "XN--I1B6B1A6A2E" => ".संगठन",
        "XN--J6W193G" => ".香港",
        "XN--MGBAAM7A8H" => ".امارات",
        "XN--MGBAB2BD" => ".بازار",
        "XN--MK1BU44C" => ".닷컴",
        "XN--NQV7F" => ".机构",
        "XN--Q9JYB4C" => ".みんな",
        "XN--QXA6A" => ".ευ",
        "XN--RHQV96G" => ".世界",
        "XN--T60B56A" => ".닷넷",
        "XN--TCKWE" => ".コム",
        "XN--UNUP4Y" => ".游戏",
        "XN--VHQUV" => ".企业",
        "XN--Y9A3AQ" => ".հայ",
        "XN--NGBC5AZD" => ".شبكة",
        "XN--WGBL6A" => ".قطر"
    ];*/

    public static $ttl = 3600; // 1h

    /**
     * Make an API request using the provided command and return response in Hash Format
     * @param array $command API command to request
     * @param array $params common module parameters (optional)
     * @return array
     */
    public static function call($command, $params = null)
    {
        if (!$params) {
            $params = \getregistrarconfigoptions('ispapi');
        }
        $cl = new \HEXONET\APIClient();
        if ($params["TestMode"] == 1 || $params["TestMode"] == "on") {
            $cl->useOTESystem();
        }

        $modules = [];
        foreach (self::getModuleVersions($params) as $key => $val) {
            $modules[] = "$key/$val";
        }

        $cl->setCredentials($params["Username"], html_entity_decode($params["Password"], ENT_QUOTES))
            ->setReferer($GLOBALS["CONFIG"]["SystemURL"])
            ->setUserAgent("WHMCS", $GLOBALS["CONFIG"]["Version"], $modules)
            ->enableDebugMode() // activate logging
            ->setCustomLogger(new Logger(["module" => "ispapi"]));
        if (strlen($params["ProxyServer"])) {
            $cl->setProxy($params["ProxyServer"]);
        }
        return ($cl->request($command))->getHash();
    }

    /**
     * get new data for environment update
     * @param array $params common module parameters
     * @return array
     */
    public static function getStatisticsData($params)
    {
        return ([
            "whmcs" => $params["whmcsVersion"],
            "updated_date" =>  (new \DateTime("now", new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')." (UTC)",
            "phpversion" => phpversion(),
            "os" => php_uname("s")
        ] + self::getModuleVersions($params));
    }

    /**
     * Get the version of the registrar module or "N/A" if not found
     * @param string $registrar the registrar identifier (ispapi/hexonet)
     * @return string
     */
    public static function getRegistrarModuleVersion($registrar)
    {
        if (!function_exists($registrar . "_getConfigArray")) {
            include_once(implode(DIRECTORY_SEPARATOR, [ROOTDIR, "modules", "registrars", $registrar, $registrar . ".php"]));
        }
        $const = strtoupper($registrar . "_MODULE_VERSION");
        if (defined($const)) {
            return constant($const);
        }
        // fallback for ispapi module < v3.0.0
        $cfgs = call_user_func($registrar . "_getConfigArray");
        if (isset($cfg["FriendlyName"]) && preg_match("/v([0-9]+\.[0-9]+\.[0-9]+)$/", $cfg["FriendlyName"], $m)) {
            return $m[1];
        }
        return "N/A";
    }

    /**
     * get current module versioning list
     * @return array
     */
    public static function getModuleVersions()
    {
        static $values = null;
        if (!is_null($values)) {
            return $values;
        }

        $values = [
            "ispapi" => self::getRegistrarModuleVersion("ispapi")
        ];

        // get addon module versions
        global $CONFIG;
        $activemodules = array_filter(explode(",", $CONFIG["ActiveAddonModules"]));
        $addon = new \WHMCS\Module\Addon();
        foreach ($addon->getList() as $module) {
            if (in_array($module, $activemodules) && preg_match("/^ispapi/i", $module) && !preg_match("/\_addon$/i", $module)) {
                $d = \WHMCS\Module\Addon\Setting::module($module)->pluck("value", "setting");
                $values[$module] = $d["version"];
            }
        }

        // get server module versions
        $server = new \WHMCS\Module\Server();
        foreach ($server->getList() as $module) {
            if (preg_match("/^ispapi/i", $module)) {
                $server->load($module);
                $v = $server->getMetaDataValue("MODULEVersion");
                $values[$module] = empty($v) ? "old" : $v;
            }
        }

        // get widget module versions
        $widget = new \WHMCS\Module\Widget();
        foreach ($widget->getList() as $module) {
            if (preg_match("/^ispapi/i", $module)) {
                $widget->load($module);
                $tmp = explode("_", $module);
                $widgetClass = "\\WHMCS\Module\Widget\\" . ucfirst($tmp[0]) . ucfirst($tmp[1]) . "Widget";
                $mname=$tmp[0]."widget".$tmp[1];
                if (class_exists($widgetClass) && defined("$widgetClass::VERSION")) {
                    $values[$mname] = $widgetClass::VERSION;
                } else {
                    $values[$mname] = "n/a";
                }
            }
        }

        return $values;
    }

    public static function loadPrices($params)
    {
        //load exchange rates
        static $rates = null;

        UserRelationModel::createTableIfNotExists();
        TldConfigurationModel::createTableIfNotExists();
        TldPriceModel::createTableIfNotExists();
        //unset($_SESSION["ispapidatattl"]);
        if (!isset($_SESSION["ispapidatattl"]) || (mktime() >  $_SESSION["ispapidatattl"])) {
            $_SESSION["ispapidatattl"] = mktime() + self::$ttl;
            UserRelationModel::truncate();
            TldConfigurationModel::truncate();
            TldPriceModel::truncate();
        }
        
        if (is_null($rates)) {
            $r = self::call([
                "COMMAND" => "QueryExchangeRates"
            ], $params);
            if ($r["CODE"]!="200") {
                throw new \Exception(
                    "Could not load currency exchange rates. Ensure to whitelist command `QueryEchangeRates` " .
                    "in case you use a restrictive role user.<br/>(" .$r["CODE"] . " " . $r["DESCRIPTION"] . ")"
                );
            }
            $rates = ["EUR" => 1];
            $r = $r["PROPERTY"];
            foreach ($r["CURRENCYTO"] as $idx => $to) {
                $rates[$to] = (float)$r["RATE"][$idx];
            }
        }
        TldPriceModel::setExchangeRates($rates);

        //load user relations into db
        if (!UserRelationModel::first()) {
            $r = self::call(["COMMAND" => "StatusUser"], $params);
            if ($r["CODE"] != "200") {
                return false;
            }
            UserRelationModel::insertFromAPI($r);
          
            //parse relation prices into basic json format (standard domain prices only for now)
            //and insert them into db
            TldPriceModel::insertFromRelations(
                UserRelationModel::getDomainPriceRelations(),
                $r["PROPERTY"]["ACCOUNTCURRENCY"][0]
            );
        }
        return true;
    }

    public static function getTLDs($params)
    {
        if (!self::loadPrices($params)) {
            return [
               'error' => 'Could not get user status from registrar API.'
            ];
        }
        $tlds = [];
        $relations = UserRelationModel::getTLDList();
        
        foreach ($relations as $relation) {
            //if (preg_match("/^XN--/", $relation->relation_subclass)) {
            //    if (isset(self::$idntldclassmap[$relation->relation_subclass])) {
            //        $tlds[self::$idntldclassmap[$relation->relation_subclass]] = $relation->relation_subclass;
            //    }
            //} else {
            $tlds[self::getTLDByClass($relation->relation_subclass)] = $relation->relation_subclass;
            //}
        }

        return $tlds;
    }

    public static function getTLDPrices($tldclasses, $cfgs)
    {
        if (!TldPriceModel::first()) {
            return [
               'error' => 'Load prices first from registrar API.'
            ];
        }

        // return price list for all requested tlds
        return TldPriceModel::getTLDPrices($tldclasses, $cfgs);
    }

    public static function getTLDByClass($tldclass)
    {
        $tld = self::$tldclassmap[$tldclass];
        if (empty($tld)) {
            return "." . strtolower($tldclass);
        }
        return $tld;
    }

    public static function getTLDConfigurations($tldclassmap, $params)
    {
        $tlds = array_keys($tldclassmap);
        // filter out TLDs we have already a configuration for
        $nflist = TldConfigurationModel::getNonExistingTLDs($tldclassmap);
        //ensure not running in API param limit
        if (!empty($nflist)) {
            $tldgrps = array_chunk($nflist, 200);
            foreach ($tldgrps as $tldgrp) {
                $cmd = ["COMMAND" => "QueryDomainOptions"];
                foreach ($tldgrp as $idx => $tld) {
                    $cmd["DOMAIN"][] = "example" . $tld;
                }
                $r = self::call($cmd, $params);
                if ($r["CODE"] != "200") {
                    return [
                        "error" => (
                            "Could not load TLD configuration data. Try again later.<br/>(" .
                            $r["CODE"] . " " . $r["DESCRIPTION"] . ")"
                        )
                    ];
                }
                TldConfigurationModel::insertFromAPI($tldgrp, $tldclassmap, $r);
            }
        }
        return TldConfigurationModel::getConfigurations($tlds);
    }

    /**
    * Check if providing Admin-C in Trade is necessary
    * @param string $tld last segment of the tld
    * @return bool
    */
    public static function needsAdminContactInTrade($tld)
    {
        //see https://wiki.hexonet.net/wiki/IT "Ownerchange"
        //see https://wiki.hexonet.net/wiki/ES "Ownerchange"
        //if the new registrant is an individual then the admin contact is required and has to match the new registrant contact
        return in_array($tld, ["it", "es"]);
    }
}
