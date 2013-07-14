<?php
defined('COT_CODE') or die('Wrong URL.');
require_once "{$cfg['plugins_dir']}/regioncity/model/RecModelAbstract.php";
/**
 * Model class for the City
 *
 * @package Region City
 * @subpackage City
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 *
 * @property int $city_id;
 * @property string $city_country
 * @property string $city_region
 * @property string $city_title
 *
 * @method static City getById(int $pk)
 * @method static City[] getList(int $limit = 0, int $offset = 0, string $order = '')
 * @method static City[] find(mixed $conditions, int $limit = 0, int $offset = 0, string $order = '')
 *
 */
class City extends RecModelAbstract{

    /**
     * @var string
     */
    public static $_table_name = '';

    /**
     * @var string
     */
    public static $_primary_key = '';

    /**
     * Column definitions
     * @var array
     */
    public static $_columns = array();

    public $owner = array();

    /**
     * Static constructor
     */
    public static function __init(){
        global $db_rec_city;

        self::$_table_name = $db_rec_city;
        self::$_primary_key = 'city_id';
        parent::__init();

    }

    /**
     * Retireve a key => val list of Regions from the database.
     * @param int $region
     * @return array
     */
    public static function getKeyValPairsByRegion($region = 0) {
        global $db, $db_rec_city;

        $key = $region = (int)$region;
        if($region == 0) return false;

        static $_stCache = array();
        if (isset($_stCache[$key])){
            return $_stCache[$key];
        }

        $q = "SELECT city_id, city_title FROM `{$db_rec_city}`
            WHERE `city_region`=? ORDER BY `city_title` ASC";
        $sql = $db->query($q, array($region));

        $_stCache[$key] = $sql->fetchAll(PDO::FETCH_KEY_PAIR);

        return $_stCache[$key];
    }

}

// Class initialization for some static variables
City::__init();
