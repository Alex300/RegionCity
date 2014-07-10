<?php
defined('COT_CODE') or die('Wrong URL.');

/**
 * Model class for the City
 *
 * @package Region City
 * @subpackage City
 * @author Alex - Studio Portal30
 * @copyright Portal30 http://portal30.ru
 *
 * @method static regioncity_model_City getById($pk);
 * @method static regioncity_model_City fetchOne($conditions = array(), $order = '');
 * @method static regioncity_model_City[] find($conditions = array(), $limit = 0, $offset = 0, $order = '');
 *
 * @property int $city_id;
 * @property string $city_country
 * @property string $city_region
 * @property string $city_title
 *
 */
class regioncity_model_City extends Som_Model_Abstract{


    /** @var Som_Model_Mapper_Abstract $db */
    protected static $_db = null;
    protected static $_columns = null;
    protected static $_tbname = '';
    protected static $_primary_key = 'city_id';

    public $owner = array();

    /**
     * Static constructor
     */
    public static function __init($db = 'db'){
        global $db_rec_city;

        static::$_tbname = $db_rec_city;
        parent::__init($db);
    }

    /**
     * Retireve a key => val list of Regions from the database.
     * @param int $region
     * @return array
     */
    public static function getKeyValPairsByRegion($region = 0) {
        $key = $region = (int)$region;
        if($region == 0) return false;

        static $_stCache = array();
        if (isset($_stCache[$key])){
            return $_stCache[$key];
        }

        $q = "SELECT city_id, city_title FROM ".static::$_db->quoteIdentifier(static::$_tbname)."
            WHERE `city_region`=? ORDER BY `city_title` ASC";
        $sql = static::$_db->query($q, array($region));

        $_stCache[$key] = $sql->fetchAll(PDO::FETCH_KEY_PAIR);

        return $_stCache[$key];
    }

    public static function fieldList()
    {
        return array(
            'city_id' =>
                array(
                    'name' => 'city_id',
                    'type' => 'int',
                    'primary' => true,
                ),
            'city_country' =>
                array(
                    'name' => 'city_id',
                    'type' => 'varchar',
                    'length' => 3,
                    'nullable' => false,
                ),
            'city_region' =>
                array(
                    'name' => 'city_region',
                    'type' => 'int',
                    'nullable' => false,
                ),
            'city_title' =>
                array(
                    'name' => 'city_title',
                    'type' => 'varchar',
                    'length' => 255,
                    'nullable' => false,
                ),
        );
    }

}

// Class initialization for some static variables
regioncity_model_City::__init();
