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
    protected $fillable = ['relation_type', 'relation_value', 'relation_category',  'relation_subcategory', 'relation_subclass', 'relation_period'];
    
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
                $table->tinyInteger('relation_period')->nullable();
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
                "relation_category" => self::parseRelationCategory($t),
                "relation_subclass" => self::parseRelationSubclass($t),
                "relation_period" => self::parsePeriod($t)
            ];
            $insert["relation_subcategory"] = self::parseRelationSubcategory($t, $insert["relation_category"]);
            $inserts[] = $insert;
        }
        foreach (array_chunk($inserts, 1000) as $t) {
            self::insert($t);
        }
    }

    private static function parseRelationCategory($type)
    {
        if (preg_match("/^PRICE_CLASS_" . self::$categoryRegexp . "_/", $type, $m)) {
            return str_replace("_", "", $m[1]) . "_PRICE";
        }
        if (preg_match("/^" . self::$categoryRegexp . "_/", $type, $m)) {
            return str_replace("_", "", $m[1]) . "_CONFIG";
        }
        return "";
    }

    private static function parseRelationSubcategory($type, $category)
    {
        if ($category === "DOMAIN_PRICE") {
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_DPML(PUB|ZONE)_/", $type)) {
                return "DPML";
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_(EOI|EARLYACCESS[0-9]+|GOLIVE|SUNRISE|LANDRUSH)_/", $type)) {
                return "PREREG";
            }
            if (preg_match("/([^_]+_(PROMO|SCALE))[0-9]*$/", $type, $m)) {
                return preg_replace("/[0-9]+/", "", $m[1]); //SETUP<p>_PROMO, SETUP_SCALE<p>
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_[^_]+_(ANNUAL|[^_]+ACKORDER|CURRENCY|RESTORE|SETUP|TRADE|TRANSFER)([0-9]*|_.+)$/", $type, $m)) {
                return $m[1];
            }
        }
        if ($category === "DOMAINPREMIUM_PRICE") {
            if (preg_match("/([^_]+_(PROMO|SCALE))[0-9]*$/", $type, $m)) {
                return preg_replace("/[0-9]+/", "", $m[1]); //SETUP<p>_PROMO, SETUP_SCALE<p>
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_PREMIUM_.+_(ANNUAL|[^_]+ACKORDER|CURRENCY|RESTORE|SETUP|TRADE|TRANSFER)([0-9]*|_.+)$/", $type, $m)) {
                return $m[1];
            }
            if (preg_match("/^PRICE_CLASS_DOMAIN_PREMIUM_.+_(EOI|EARLYACCESS[0-9]+|GOLIVE|SUNRISE|LANDRUSH)_/", $type)) {
                return "PREREG";
            }
        }
        //To Be Extended
        return "";
    }

    private static function parseRelationSubclass($t)
    {
        if (preg_match("/^PRICE_CLASS_([^_]+_)?DOMAIN_([^_]+)_/", $t, $m)) {
            return $m[2];
        }
        return "";
    }

    private static function parsePeriod($relationType)
    {
        if (preg_match("/_[^_0-9]+([0-9]+)(_PROMO)?$/", $relationType, $m)) {
            return (int)$m[1];
        }
        return null;
    }

    public static function getTLDList()
    {
        return self::where("relation_category", "DOMAIN_PRICE")
                    ->whereRaw("relation_subcategory REGEXP '^(TRANSFER|SETUP|CURRENCY|ANNUAL|RESTORE)[0-9]*$'")
                    ->whereRaw("relation_subclass NOT REGEXP '^" . self::$tldclassfilter . "$'")
                    ->get();
    }

    public static function getDomainPriceRelations()
    {
        return self::where("relation_category", "DOMAIN_PRICE")
            ->whereRaw("relation_subcategory REGEXP '^(TRANSFER|SETUP|CURRENCY|ANNUAL|RESTORE)[0-9]*$'")
            ->whereRaw("relation_subclass NOT REGEXP '^" . self::$tldclassfilter . "$'")
            ->get();
    }

    public function getPeriod()
    {
        return is_null($this->relation_period) ? "default" : $this->relation_period;
    }
}
