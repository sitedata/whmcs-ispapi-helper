<?php

namespace ISPAPI;

include(implode(DIRECTORY_SEPARATOR, [__DIR__, "UserRelationModel.class.php"]));
include(implode(DIRECTORY_SEPARATOR, [__DIR__, "TldConfigurationModel.class.php"]));
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

    public static $config = null;

    public static $ttl = 3600; // 1h

    public static function init($config)
    {
        self::$config = $config;
        UserRelationModel::createTableIfNotExists();
        TldConfigurationModel::createTableIfNotExists();
        if (!isset($_SESSION["ispapidatattl"]) || (mktime() >  $_SESSION["ispapidatattl"])) {
            $_SESSION["ispapidatattl"] = mktime() + self::$ttl;
            unset(
                $_SESSION["ispapiaccountcurrency"],
                $_SESSION["ispapitldprices"]
            );
            UserRelationModel::truncate();
            TldConfigurationModel::truncate();
        }
    }

    public static function loadPrices()
    {
        //load user relations into db
        if (!UserRelationModel::first()) {
            $r = ispapi_call(["COMMAND" => "StatusUser"], self::$config);
            if ($r["CODE"] != "200") {
                return false;
            }
            UserRelationModel::insertFromAPI($r);
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

    public static function getTLDPrices($tlds, $cfgs)
    {
        if (!UserRelationModel::first()) {
            return [
               'error' => 'Load prices first from registrar API.'
            ];
        }

        $tlds = array_keys($tlds);
        $tldsinv = array_flip(self::$tldclassmap);

        // built list of tlds we have to calculate prices for
        if (!isset($_SESSION["ispapitldprices"])) {
            $_SESSION["ispapitldprices"] = [];
            $nflist = $tlds;
        } else {
            // filter out TLDs we have already prices calculated for
            $nflist = array_diff($tlds, array_keys($_SESSION["ispapitldprices"]));
        }

        // calculate prices for outstanding TLDs
        $nfclasses = [];
        foreach ($nflist as $tld) {
            $nfclasses[] = self::getTLDClass($tld);
        }
        $relations = UserRelationModel::getDomainPriceRelations($nfclasses);
        foreach ($relations as $relation) {
            // $m [
            //   1 => TYPE
            //   2 => period (optional)
            // ]
            if (!preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_(TRANSFER|SETUP|ANNUAL|RESTORE|CURRENCY)[0-9]*$/", $relation->relation_type, $m)) {
                continue;
            }
            $tld = self::getTLDByClass($relation->relation_subclass);
            if (!isset($_SESSION["ispapitldprices"][$tld])) {
                $_SESSION["ispapitldprices"][$tld] = [
                    "CURRENCY" => $_SESSION["ispapiaccountcurrency"]
                ];
            }
            $tmp = $_SESSION["ispapitldprices"][$tld];
            if ($m[1]=="CURRENCY") {
                $tmp[$m[1]] = $relation->relation_value;
                $_SESSION["ispapitldprices"][$tld] = $tmp;
                continue;
            }
            if (!isset($tmp[$m[1]])) {
                $tmp[$m[1]] = [];
            }
            $tmp[$m[1]][$relation->getPeriod()] = (float)$relation->relation_value;
            $_SESSION["ispapitldprices"][$tld] = $tmp;
        }

        // return price list for all requested tlds
        $results = [];
        foreach ($tlds as $tld) {
            if (isset($cfgs[$tld])) {
                $results[$tld] = self::calcTLDPrices(
                    $_SESSION["ispapitldprices"][$tld],
                    $cfgs[$tld]["periods_registration"][0]
                );
            }
        }
        return $results;
    }

    public static function getTLDByClass($tldclass)
    {
        $tld = self::$tldclassmap[$tldclass];
        if (empty($tld)) {
            return "." . strtolower($tldclass);
        }
        return $tld;
    }

    public static function getTLDClass($tld)
    {
        return strtoupper(preg_replace("/\./", "", $tld));
    }

    public static function calcTLDPrices($rawPrice, $minPeriod)
    {
        static $defaultcurrency = null;
        $apicurrency = $rawPrice["CURRENCY"];
        // default prices
        $prices = [
            "registration" => self::getRegistrationPrice($rawPrice, $minPeriod),
            "renewal" => self::getRenewalPrice($rawPrice, $minPeriod),
            "transfer" => self::getTransferPrice($rawPrice, $minPeriod),
            "redemption" => self::getRedemptionPrice($rawPrice, $minPeriod)
        ];
        // check if API currency exists in WHMCS
        if (\WHMCS\Database\Capsule::table("tblcurrencies")->where("code", $apicurrency)->first()) {
            return array_merge($prices, [ "currency" => $apicurrency ]);
        }
        // load WHMCS default currency
        if (!$defaultcurrency) {
            $defaultcurrency = (get_query_vals("tblcurrencies", "code", ["`default`" => "1"]))["code"];
        }
        // convert prices to default currency
        foreach ($prices as $key => $val) {
            $prices[$key] = self::convertCurrency($val, $apicurrency, $defaultcurrency);
        }
        return array_merge($prices, [ "currency" => $defaultcurrency ]);
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

    public static function getRegistrationPrice($rawPrice, $period)
    {
        if (isset($rawPrice["SETUP"][$period]) && isset($rawPrice["ANNUAL"][$period])) {
            return ($rawPrice["SETUP"][$period] + $rawPrice["ANNUAL"][$period]);
        }
        if (isset($rawPrice["SETUP"]["default"]) && isset($rawPrice["ANNUAL"]["default"])) {
            return ($rawPrice["SETUP"]["default"] + ($period * $rawPrice["ANNUAL"]["default"]));
        }
        return null;
    }

    public static function getRenewalPrice($rawPrice, $period)
    {
        if (isset($rawPrice["ANNUAL"][$period])) {
            return $rawPrice["ANNUAL"][$period];
        }
        if (isset($rawPrice["ANNUAL"]["default"])) {
            return ($period * $rawPrice["ANNUAL"]["default"]);
        }
        return null;
    }

    public static function getTransferPrice($rawPrice, $period)
    {
        if (isset($rawPrice["TRANSFER"][$period])) {
            return $rawPrice["TRANSFER"][$period];
        }
        if (isset($rawPrice["TRANSFER"]["default"])) {
            return ($period * $rawPrice["TRANSFER"]["default"]);
        }
        return null;
    }

    public static function getRedemptionPrice($rawPrice, $period)
    {
        if (isset($rawPrice["RESTORE"]["default"])) {
            return $rawPrice["RESTORE"]["default"];
        }
        return null;
    }

    public static function getTLDConfigurations($tldclassmap)
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
                $r = ispapi_call($cmd, self::$config);
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
