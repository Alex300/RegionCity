<?php
defined('COT_CODE') or die('Wrong URL.');

/**
 * Model class for the City
 *
 * @package Region City
 * @subpackage City
 *
 * @author Kalnov Alexey    <kalnovalexey@yandex.ru>
 * @copyright © Portal30 Studio http://portal30.ru
 *
 * @method static regioncity_model_City getById($pk);
 * @method static regioncity_model_City fetchOne($conditions = array(), $order = '');
 * @method static regioncity_model_City[] find($conditions = array(), $limit = 0, $offset = 0, $order = '');
 *
 * @property int $id;
 * @property string $country
 * @property string $region
 * @property string $title
 * @property int $sort          Поле для сортировки
 */
class regioncity_model_City extends Som_Model_ActiveRecord
{


    /** @var Som_Model_Mapper_Abstract $db */
    protected static $_db = null;
    protected static $_tbname = '';
    protected static $_primary_key = 'id';

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

        $q = "SELECT id, title FROM ".static::$_db->quoteIdentifier(static::$_tbname)."
            WHERE `region`=? ORDER BY `sort` DESC, `title` ASC";
        $sql = static::$_db->query($q, array($region));

        $_stCache[$key] = $sql->fetchAll(PDO::FETCH_KEY_PAIR);

        return $_stCache[$key];
    }

    public static function fieldList()
    {
        return array(
            'id' =>
                array(
                    'type' => 'int',
                    'primary' => true,
                ),
            'country' =>
                array(
                    'type' => 'varchar',
                    'length' => 3,
                    'nullable' => false,
                ),
            'region' =>
                array(
                    'type' => 'int',
                    'nullable' => false,
                ),
            'title' =>
                array(
                    'type' => 'varchar',
                    'length' => 255,
                    'nullable' => false,
                ),
            'sort' => array (
                'type' => 'int',
                'default' => 0,
                'description' => 'Порядок для сортировки',
            ),
        );
    }

}

// Class initialization for some static variables
regioncity_model_City::__init();
