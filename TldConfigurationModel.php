<?php
namespace WHMCS\Module\Registrar\Ispapi;

class TldConfigurationModel extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The table associated with the tld configurations model.
     *
     * @var string
     */
    protected $table = 'ispapi_tbltldconfigurations';
    public $timestamps = false;
    protected $fillable = ['tld', 'tldclass', 'periods', 'repository', 'authRequired', 'idprotection'];

    public static function hasTable()
    {
        return \WHMCS\Database\Capsule::schema()->hasTable('ispapi_tbltldconfigurations');
    }

    public static function createTableIfNotExists()
    {
        if (!self::hasTable()) {
            \WHMCS\Database\Capsule::schema()->create('ispapi_tbltldconfigurations', function ($table) {
                /** @var \Illuminate\Database\Schema\Blueprint $table */
                $table->string('tld');
                $table->index('tld');
                $table->unique('tld');
                $table->string('tldclass');
                $table->index('tldclass');
                $table->unique('tldclass');
                $table->json('periods')->nullable();
                $table->string('repository');
                $table->boolean('authRequired');
                $table->boolean('idprotection');
            });
        }
    }

    public static function insertFromAPI($tlds, $tldclassmap, $apiresponse)
    {
        $inserts = [];
        if (isset($apiresponse["PROPERTY"])) {
            $apiresponse = $apiresponse["PROPERTY"];
        }
        foreach ($apiresponse["REGISTRYTRANSFERREQUIREAUTHCODE"] as $idx => &$needsAuth) {
            $inserts[] = [
                "tld" => $tlds[$idx],
                "tldclass" => $tldclassmap[$tlds[$idx]],
                "periods" => json_encode([
                    "registration" => self::formatPeriods($apiresponse["ZONEREGISTRATIONPERIODS"][$idx]),
                    "transfer" => self::formatPeriods($apiresponse["ZONETRANSFERPERIODS"][$idx]),
                    "renewal" => self::formatPeriods($apiresponse["ZONERENEWALPERIODS"][$idx]),
                    "redemptiondays" => $apiresponse["ZONEDELETIONRESTORABLEPERIOD"][$idx] === "" ? null : (int)$apiresponse["ZONEDELETIONRESTORABLEPERIOD"][$idx],
                    "gracedays" => null // unsupported
                ]),
                "repository" => $apiresponse["REPOSITORY"][$idx],
                "authRequired" => ($apiresponse["REGISTRYTRANSFERREQUIREAUTHCODE"][$idx] === "YES"),
                "idprotection" => in_array("WHOISTRUSTEE", explode(" ", $apiresponse["X-PROXY"][$idx]))
            ];
        }
        foreach (array_chunk($inserts, 1000) as $t) {
            self::insert($t);
        }
    }

    public static function getConfigurations($tlds)
    {
        $cfgs = self::where("repository", "<>", "")
                ->whereRaw("tld REGEXP '^(" . implode("|", $tlds) . ")$'")->get();
        $results = [];
        foreach ($cfgs as $cfg) {
            $results[$cfg->tld] = $cfg;
        }
        return $results;
    }

    public static function formatPeriods($periodStr)
    {
        $periods = array_values(preg_grep("/^(0Y|1M)?$/", explode(",", $periodStr), PREG_GREP_INVERT));
        if (!empty($periods)) {
            return array_map('intval', $periods);// convert strings to ints
        }
        return [];
    }

    public static function getNonExistingTLDs($tldclasses)
    {
        $results = self::select("tld")
                    ->whereRaw("tldclass REGEXP '^(" . implode("|", $tldclasses) . ")$'")
                    ->get();
        if (empty($results)) {
            return array_keys($tlds);
        }
        $found = [];
        foreach ($results as $r) {
            $found[] = $r->tld;
        }
        return array_diff(array_keys($tldclasses), $found);
    }

    public function setPeriodsAttribute($value)
    {
        $this->attributes['periods'] = json_encode($value);
    }

    public function getPeriodsAttribute($value)
    {
        return json_decode($value, true);
    }
}
