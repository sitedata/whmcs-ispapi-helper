<?php
namespace ISPAPI;

class TldConfigurationModel extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The table associated with the tld configurations model.
     *
     * @var string
     */
    protected $table = 'ispapi_tbltldconfigurations';
    public $timestamps = false;
    protected $fillable = ['tld', 'tldclass', 'periods_registration', 'periods_transfer', 'periods_renewal', 'repository', 'authRequired', 'idprotection'];

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
                $table->string('periods_registration');
                $table->string('periods_transfer');
                $table->string('periods_renewal');
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
                "periods_registration" => $apiresponse["ZONEREGISTRATIONPERIODS"][$idx],
                "periods_transfer" => $apiresponse["ZONETRANSFERPERIODS"][$idx],
                "periods_renewal" => $apiresponse["ZONERENEWALPERIODS"][$idx],
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
            $cfg->periods_registration = self::formatPeriods($cfg->periods_registration);
            $cfg->periods_renewal = self::formatPeriods($cfg->periods_renewal);
            $cfg->periods_transfer = self::formatPeriods($cfg->periods_transfer);
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



    public function getMinRegistrationPeriod()
    {
        return self::formatPeriods($this->periods_registration)[0];
    }

    public function getMaxRegistrationPeriod()
    {
        $tmp = self::formatPeriods($this->periods_registration);
        return $tmp[count($tmp) - 1];
    }

    public function getYearsStep()
    {
        $periods = self::formatPeriods($this->periods_registration);
        $pmin = $periods[0];
        $pnext = $pmin + 1;
        $idxmax = count($periods) - 1;
        if ($idxmax > 0) {
            $pnext = $periods[1];
        }
        return $pnext - $pmin;
    }
}
