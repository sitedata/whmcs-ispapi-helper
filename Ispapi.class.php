<?php

namespace ISPAPI;

include(implode(DIRECTORY_SEPARATOR, [__DIR__, "UserRelationModel.class.php"]));
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
        'UKCOM' => '.uk.com',
        'UKNET' => '.uk.net',
        'USCOM' => '.us.com',
        'USORG' => '.us.org',
        'UYCOM' => '.uy.com',
        'WAWPL' => '.waw.pl',
        'WEBNF' => '.web.nf',
        'WEBVE' => '.web.ve',
        'ZACOM' => '.za.com'
    ];

    //this could get replaced by using CONVERTIDN command
    public static $idntldclassmap = [
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
    ];

    //NOTE: filter out IDN TLDs as not yet officially supported by WHMCS (first part of regex)
    public static $tldclassfilter = "(XN--[^_]+|MANDATE--SWISS|DOMAINALERT|TESTDNSERVICESCOZA|TLDBOX|NAME|NAMEEMAIL|DPMLPUB|DPMLZONE|[^_]+(REGIONAL|IDN(TLD(ASCII)?)?|(CHARS|NUMBERS)[0-9]*))";

    public static $config = null;

    public static $ttl = 3600; // 1h

    public static function init($config)
    {
        self::$config = $config;
        if (!UserRelationModel::hasTable()) {
            $ttl = UserRelationModel::createTable();
        }
        if (!isset($_SESSION["ispapidatattl"]) || (mktime() >  $_SESSION["ispapidatattl"])) {
            $_SESSION["ispapidatattl"] = mktime() + self::$ttl;
            unset(
                $_SESSION["ispapiaccountcurrency"],
                $_SESSION["ispapitldprices"],
                $_SESSION["ispapitldconfiguration"]
            );
            UserRelationModel::truncate();
        }
    }

    public static function loadPrices()
    {
        //load user relations into session
        if (!UserRelationModel::first()) {
            $r = ispapi_call(["COMMAND" => "StatusUser"], self::$config);
            if ($r["CODE"] != "200") {
                return false;
            }
            //$regexp = "/^PRICE_CLASS_DOMAIN_" . self::$tldclassfilter . "_/";
            //$list = preg_grep($regexp, $r["PROPERTY"]["RELATIONTYPE"], PREG_GREP_INVERT);
            $inserts = [];
            //foreach ($list as $idx => &$t) {
            foreach ($r["PROPERTY"]["RELATIONTYPE"] as $idx => &$t) {
                $inserts[] = ["type" => $t, "value" => $r["PROPERTY"]["RELATIONVALUE"][$idx]];
            }
            UserRelationModel::insert($inserts);
            $_SESSION["ispapiaccountcurrency"] = $r["PROPERTY"]["ACCOUNTCURRENCY"][0];
        }
        return true;
    }

    public static function getTLDs()
    {
        if (!self::loadPrices()) {
            return [
               'error' => 'Could not get user status from registrar API.'
            ];
        }
        $tlds = [];
        $relations = UserRelationModel::selectRaw("*, REPLACE(REPLACE(type, 'PRICE_CLASS_DOMAIN_', ''), '_CURRENCY', '') AS tldclass")
                        ->whereRaw("type regexp '^PRICE_CLASS_DOMAIN_[^_]+_CURRENCY$'")
                        ->whereRaw("type not regexp '^PRICE_CLASS_DOMAIN_" . self::$tldclassfilter . "_'")
                        ->get();
        
        foreach ($relations as $relation) {
            //if (preg_match("/^XN--/", $tldclass)) {
            //    if (isset(self::$idntldclassmap[$tldclass])) {
            //        $tlds[self::$idntldclassmap[$tldclass]] = $tldclass;
            //    }
            //} else {
            if (isset(self::$tldclassmap[$relation->tldclass])) {
                $tlds[self::$tldclassmap[$relation->tldclass]] = $relation->tldclass;
            } else {
                $tlds["." . strtolower($relation->tldclass)] = $relation->tldclass;
            }
            //}
        }
        throw new \Exception(nl2br(print_r($tlds, true)));
        return $tlds;
    }

    public static function initTLDPrices($tlds)
    {
        if (!isset($_SESSION["ispapirelations"])) {
            return [
               'error' => 'Load prices first from registrar API.'
            ];
        }

        // built list of tlds we have to calculate prices for
        if (!isset($_SESSION["ispapitldprices"])) {
            $_SESSION["ispapitldprices"] = [];
            $nflist = array_keys($tlds);
        } else {
            // filter out TLDs we have already prices calculated for
            $nflist = array_diff(array_keys($tlds), array_keys($_SESSION["ispapitldprices"]));
        }

        // calculate prices for outstanding TLDs
        $allrelationkeys = array_keys($_SESSION["ispapirelations"]);
        foreach ($nflist as $tld) {
            $rbase = "PRICE_CLASS_DOMAIN_" . $tlds[$tld] . "_";
            
            $tmp = [
                "currency" => isset($_SESSION["ispapirelations"][$rbase . "CURRENCY"]) ?
                    $_SESSION["ispapirelations"][$rbase . "CURRENCY"] :
                    $_SESSION["ispapiaccountcurrency"]
            ];
            $pkeys = [
                "TRANSFER" => "transfer",
                "RESTORE" => "redemption",
                "SETUP" => "registration",
                "ANNUAL" => "renewal"
            ];
            foreach ($pkeys as $type => $pkey) {
                if (!isset($tmp[$pkey])) {
                    $tmp[$pkey] = [];
                }
                $rtypes = preg_grep("/^" . $rbase . $type . "([0-9]+)?$/", $allrelationkeys);
                foreach ($rtypes as $tkey) {
                    if (preg_match("/_" . $type . "([0-9]+)$/", $tkey, $m)) {
                        if ($m[1]) { //no 0Y
                            $tmp[$pkey][(int)$m[1]] = (float)$_SESSION["ispapirelations"][$tkey];
                        }
                    } else {
                        $tmp[$pkey]["default"] = (float)$_SESSION["ispapirelations"][$tkey];
                    }
                }
            }
            $_SESSION["ispapitldprices"][$tld] = $tmp;
        }

        // return price list for all requested tlds
        $results = [];
        foreach ($tlds as $tld => $cl) {
            $results[$tld] = $_SESSION["ispapitldprices"][$tld];
        }
        return $results;
    }

    public static function getTLDPrices($tld, $minPeriod)
    {
        static $defaultcurrency = null;
        $apicurrency = $_SESSION["ispapitldprices"][$tld]["currency"];
        $prices = [
            "registration" => self::getRegistrationPrice($tld, $minPeriod),
            "renewal" => self::getRenewalPrice($tld, $minPeriod),
            "transfer" => self::getTransferPrice($tld, $minPeriod),
            "redemption" => self::getRedemptionPrice($tld, $minPeriod),
        ];
        // check if currency exists, otherwise we have to convert to default currency
        $currency = \WHMCS\Database\Capsule::table("tblcurrencies")->where("code", $apicurrency)->first();
        if (is_null($currency)) {
            if (is_null($defaultcurrency)) {
                $defaultcurrency = (get_query_vals("tblcurrencies", "code", ["`default`" => "1"]))["code"];
            }
            foreach ($prices as $key => $val) {
                $prices[$key] = self::convertCurrency($val, $apicurrency, $defaultcurrency);
            }
            $prices["currency"] = $defaultcurrency;
        } else {
            $prices["currency"] = $apicurrency;
        }
        return $prices;
    }

    public static function convertCurrency($val, $currFrom, $currTo)
    {
        static $rates = null;
        if (is_null($rates)) {
            $r = ispapi_call([
                "COMMAND" => "QueryExchangeRates"
            ], self::$config);
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
        return ($val / $rates[$currFrom]) * $rates[$currTo];
    }

    public static function getTLDPriceForType($type, $tld, $period)
    {
        $tmp = $_SESSION["ispapitldprices"][$tld];
        if ($type === "annual") {
            if (isset($tmp[$type][$period])) {
                return $tmp[$type][$period];
            }
            if (isset($tmp[$type]["default"])) {
                return ($period * $tmp[$type]["default"]);
            }
        } elseif ($type === "redemption") {
            if (isset($tmp[$type]["default"])) {
                return $tmp[$type]["default"];
            }
        } elseif ($type === "registration") {
            if (isset($tmp[$type][$period]) && isset($tmp["renewal"][$period])) {
                return ($tmp[$type][$period] + $tmp["renewal"][$period]);
            }
            if (isset($tmp[$type]["default"]) && isset($tmp["renewal"]["default"])) {
                return ($tmp[$type]["default"] + ($period * $tmp["renewal"]["default"]));
            }
        } else {
            if (isset($tmp[$type][$period])) {
                return $tmp[$type][$period];
            }
            if (isset($tmp[$type]["default"])) {
                return ($period * $tmp[$type]["default"]);
            }
        }
        return null;
    }

    public static function getRegistrationPrice($tld, $period)
    {
        return self::getTLDPriceForType("registration", $tld, $period);
    }

    public static function getRenewalPrice($tld, $period)
    {
        return self::getTLDPriceForType("renewal", $tld, $period);
    }

    public static function getTransferPrice($tld, $period)
    {
        return self::getTLDPriceForType("transfer", $tld, $period);
    }

    public static function getRedemptionPrice($tld, $period)
    {
        return self::getTLDPriceForType("redemption", $tld, $period);
    }

    public static function getTLDConfigurations($tlds)
    {
        if (!isset($_SESSION["ispapitldconfiguration"])) {
            $_SESSION["ispapitldconfiguration"] = [];
            $nflist = $tlds;
        } else {
            // filter out TLDs we have already a configuration for
            $nflist = array_diff($tlds, array_keys($_SESSION["ispapitldconfiguration"]));
        }
        //ensure not running in API param limit
        if (!empty($nflist)) {
            $tldgrps = array_chunk(array_keys($nflist), 200);
            foreach ($tldgrps as $tlds) {
                $cmd = ["COMMAND" => "QueryDomainOptions"];
                foreach ($tlds as $idx => $tld) {
                    $cmd["DOMAIN"][] = "example" . $tld;
                }
                $r = ispapi_call($cmd, self::$config);
                if ($r["CODE"] != "200") {
                    continue;
                }
                $r = $r["PROPERTY"];
                $periodkeys = preg_grep("/^ZONE(.+)PERIODS$/", array_keys($r));
                foreach ($tlds as $idx => $tld) {
                    $cfg = [
                        "periods" => [],
                        "transferNeedsAuth" => (int)($r["REGISTRYTRANSFERREQUIREAUTHCODE"][$idx] == "YES"),
                        "idprotection" => (!empty($r["X-PROXY"][$idx])),
                    ];
                    foreach ($periodkeys as $key) {
                        preg_match("/^ZONE(.+)PERIODS$/", $key, $m);
                        //filter empty value and 0Y, 1M period out
                        $val = array_values(preg_grep("/^(0Y|1M)?$/", explode(",", $r[$key][$idx]), true));
                        if (!empty($val)) {
                            $cfg["periods"][strtolower($m[1])] = $val;
                        }
                    }
                    $_SESSION["ispapitldconfiguration"][$tld] = $cfg;
                }
            }
        }
        $results = [];
        foreach ($tlds as $tld) {
            $results[$tld] = $_SESSION["ispapitldconfiguration"][$tld];
        }
        return $results;
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
