<?php
namespace ISPAPI;

class UserRelationModel extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The table associated with the user relation model.
     *
     * @var string
     */
    protected $table = 'ispapi_tblrelations';
    public $timestamps = false;
    protected $fillable = ['relation_type', 'relation_value', 'relation_category',  'relation_subcategory', 'relation_subclass'];
    
    //NOTE: filter out IDN TLDs as not yet officially supported by WHMCS (first part of regex)
    public static $tldclassfilter = "(XN--[^_]+|MANDATE--SWISS|TESTDNSERVICESCOZA|TLDBOX|NAME|NAMEEMAIL|DPMLPUB|DPMLZONE|[^_]+(REGIONAL|IDN(TLD(ASCII)?)?|(CHARS|NUMBERS)[0-9]*))";
    public static $categoryRegexp = "(DOMAIN_PREMIUM|PAYMENT|SERVICE_USAGE|DNSZONE|SSLCERT|VSERVER|DSERVER|DOMAIN|TRUSTEE_DOMAIN|MANAGED_DOMAIN|INVOICE|TMCHMARK|DOMAINALERT|PREMIUMDNS|MOBILE|WEBSITE|PROXY_WHOISPROXY)";

    public static function hasTable()
    {
        return \WHMCS\Database\Capsule::schema()->hasTable('ispapi_tblrelations');
    }

    public static function createTableIfNotExists()
    {
        if (!self::hasTable()) {
            \WHMCS\Database\Capsule::schema()->create('ispapi_tblrelations', function ($table) {
                /** @var \Illuminate\Database\Schema\Blueprint $table */
                $table->string('relation_subclass');
                $table->index('relation_subclass');
                $table->string('relation_value');
                $table->string('relation_category');
                $table->index('relation_category');
                $table->string('relation_subcategory');
                $table->index('relation_subcategory');
                $table->string('relation_type');
                $table->unique('relation_type');
                $table->index('relation_type');
            });
        }
    }

    public static function insertFromAPI($apiresponse)
    {
        $inserts = [];
        if (isset($apiresponse["PROPERTY"])) {
            $apiresponse = $apiresponse["PROPERTY"];
        }
        foreach ($apiresponse["RELATIONTYPE"] as $idx => &$t) {
            $insert = [
                "relation_type" => $t,
                "relation_value" => $apiresponse["RELATIONVALUE"][$idx],
                "relation_category" => self::getRelationCategory($t),
                "relation_subclass" => self::getSubclassByRelation($t)
            ];
            $insert["relation_subcategory"] = self::getRelationSubcategory($t, $insert["relation_category"]);
            $inserts[] = $insert;
        }
        foreach (array_chunk($inserts, 1000) as $t) {
            self::insert($t);
        }
    }

    public static function getRelationCategory($type)
    {
        if (preg_match("/^PRICE_CLASS_" . self::$categoryRegexp . "_/", $type, $m)) {
            return str_replace("_", "", $m[1]) . "_PRICE";
        }
        if (preg_match("/^" . self::$categoryRegexp . "_/", $type, $m)) {
            return str_replace("_", "", $m[1]) . "_CONFIG";
        }
        return "";
    }

    public static function getRelationSubcategory($type, $category)
    {
        if ($category === "DOMAIN_PRICE") {
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_BACKORDER_/", $type)) {
                return "BACKORDER";
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_LITEBACKORDER_/", $type)) {
                return "LITEBACKORDER";
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_DPML(PUB|ZONE)_/", $type)) {
                return "DPML";
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_(EOI|EARLYACCESS[0-9]+|GOLIVE|SUNRISE|LANDRUSH)_/", $type)) {
                return "PREREG";
            }
        }
        if ($category === "DOMAINPREMIUM_PRICE") {
            if (preg_match("/^PRICE_CLASS_DOMAIN_PREMIUM_.+_BACKORDER_/", $type)) {
                return "BACKORDER";
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_PREMIUM_.+_LITEBACKORDER_/", $type)) {
                return "LITEBACKORDER";
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_PREMIUM_.+_(EOI|EARLYACCESS[0-9]+|GOLIVE|SUNRISE|LANDRUSH)_/", $type)) {
                return "PREREG";
            }
        }
        //To Be Extended
        return "";
    }

    public static function getSubclassByRelation($t)
    {
        if (preg_match("/^PRICE_CLASS_([^_]+_)?DOMAIN_([^_]+)_/", $t, $m)) {
            return $m[2];
        }
        return "";
    }

    public static function getTLDList()
    {
        return self::where("relation_category", "DOMAIN_PRICE")
                    ->where("relation_subcategory", "")
                    ->whereRaw("relation_type LIKE '%CURRENCY'")
                    ->whereRaw("relation_subclass NOT REGEXP '^" . self::$tldclassfilter . "$'")
                    ->get();
    }

    public static function getDomainPriceRelations($tldclasses)
    {
        return self::where("relation_category", "DOMAIN_PRICE")
            ->where("relation_subcategory", "")
            ->whereRaw("relation_subclass NOT REGEXP '^" . self::$tldclassfilter . "$'")
            ->whereRaw("relation_type REGEXP '_(TRANSFER|RESTORE|SETUP|ANNUAL|CURRENCY)[0-9]*$'")->get();
    }

    public function getPeriod()
    {
        if (preg_match("/([0-9]+)$/", $this->relation_type, $m)) {
            return (int)$m[1];
        }
        return "default";
    }
}
