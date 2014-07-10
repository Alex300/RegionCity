<?php
defined('COT_CODE') or die('Wrong URL.');

/**
 * Model class for the Region
 *
 * @package Region City
 * @subpackage Region
 * @author Alex - Studio Portal30
 * @copyright Portal30 http://portal30.ru
 *
 * @method static regioncity_model_Region getById($pk);
 * @method static regioncity_model_Region fetchOne($conditions = array(), $order = '');
 * @method static regioncity_model_Region[] find($conditions = array(), $limit = 0, $offset = 0, $order = '');
 *
 * @property int $region_id;
 * @property string $region_country
 * @property string $region_title
 */
class regioncity_model_Region extends Som_Model_Abstract{

    /** @var Som_Model_Mapper_Abstract $db */
    protected static $_db = null;
    protected static $_columns = null;
    protected static $_tbname = '';
    protected static $_primary_key = 'region_id';

    public $owner = array();

    /**
     * Static constructor
     */
    public static function __init($db = 'db'){
        global $db_rec_region;

        static::$_tbname = $db_rec_region;
        parent::__init($db);
    }

    /**
     * Retireve a key => val list of Regions from the database.
     * @param string $country
     * @return array
     */
    public static function getKeyValPairsByCountry($country = '') {
        $key = $country = trim($country);
        if ($key == '') $key = '_all_';

        static $_stCache = array();
        if (isset($_stCache[$key])){
            return $_stCache[$key];
        }

        $q = "SELECT region_id, region_title FROM ".static::$_db->quoteIdentifier(static::$_tbname)."
            WHERE `region_country`=? ORDER BY `region_title` ASC";
        $sql = static::$_db->query($q, array($country));

        $_stCache[$key] = $sql->fetchAll(PDO::FETCH_KEY_PAIR);

        return $_stCache[$key];
    }

    public static function fieldList()
    {
        return array(
            'region_id' =>
                array(
                    'name' => 'region_id',
                    'type' => 'int',
                    'primary' => true,
                ),
            'region_country' =>
                array(
                    'name' => 'region_country',
                    'type' => 'varchar',
                    'length' => 3,
                    'nullable' => false,
                ),
            'region_title' =>
                array(
                    'name' => 'region_title',
                    'type' => 'varchar',
                    'length' => 255,
                    'nullable' => false,
                ),
        );
    }

}

// Class initialization for some static variables
regioncity_model_Region::__init();
