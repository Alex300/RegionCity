<?php
defined('COT_CODE') or die('Wrong URL.');
require_once "{$cfg['plugins_dir']}/regioncity/model/RecModelAbstract.php";
/**
 * Model class for the Region
 *
 * @package Region City
 * @subpackage Region
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 *
 * @property int $region_id;
 * @property string $region_country
 * @property string $region_title
 *
 * @method static Region getById(int $pk)
 * @method static Region[] getList(int $limit = 0, int $offset = 0, string $order = '')
 * @method static Region[] find(mixed $conditions, int $limit = 0, int $offset = 0, string $order = '')
 *
 */
class Region extends RecModelAbstract{

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
        global $db_rec_region;

        self::$_table_name = $db_rec_region;
        self::$_primary_key = 'region_id';
        parent::__init();

    }

    /**
     * Retireve a key => val list of Regions from the database.
     * @param string $country
     * @return array
     */
    public static function getKeyValPairsByCountry($country = '') {
        global $db, $db_rec_region;

        $key = $country = trim($country);
        if ($key == '') $key = '_all_';

        static $_stCache = array();
        if (isset($_stCache[$key])){
            return $_stCache[$key];
        }

        $q = "SELECT region_id, region_title FROM `$db_rec_region`
            WHERE `region_country`=? ORDER BY `region_title` ASC";
        $sql = $db->query($q, array($country));

        $_stCache[$key] = $sql->fetchAll(PDO::FETCH_KEY_PAIR);

        return $_stCache[$key];
    }

}

// Class initialization for some static variables
Region::__init();
