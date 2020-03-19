<?php
namespace ISPAPI;

class UserRelationModel extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The table associated with the relation model.
     *
     * @var string
     */
    protected $table = 'ispapi_tblrelations';
    public $timestamps = false;
    protected $fillable = ['type', 'value'];
    protected $myttl = 3600;

    public static function hasTable() {
        return \WHMCS\Database\Capsule::schema()->isTableExising('ispapi_tblrelations');
    }

    public static function createTable() {
        \WHMCS\Database\Capsule::schema()->create('ispapi_tblrelations', function ($table) {
            /** @var \Illuminate\Database\Schema\Blueprint $table */
            $table->string('type');
            $table->string('value');
            $table->unique('type');
            $table->index('type');
        });
        self::create(['type' => '_RELATION_TTL', 'value' => mktime() + self::$myttl]);
    }

    public static function truncate(){
        parent::truncate();
        self::create(['type' => '_RELATION_TTL', 'value' => mktime() + self::$myttl]);
    }
}