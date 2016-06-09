<?php
defined('COT_CODE') or die('Wrong URL.');

if(empty($GLOBALS['db_city'])) {
    cot::$db->registerTable('city');
}

/**
 * Model class for the City
 *
 * @package Region City
 * @subpackage City
 *
 * @author Kalnov Alexey <kalnovalexey@yandex.ru>
 * @copyright © Portal30 Studio http://portal30.ru
 *
 * @method static regioncity_model_City getById($pk);
 * @method static regioncity_model_City fetchOne($conditions = array(), $order = '');
 * @method static regioncity_model_City[] find($conditions = array(), $limit = 0, $offset = 0, $order = '');
 *
 * @property int $id;
 * @property string $country
 * @property regioncity_model_Region $region
 * @property string $title
 * @property int $sort          Поле для сортировки
 */
class regioncity_model_City extends Som_Model_ActiveRecord
{

    /** @var Som_Model_Mapper_Abstract $db */
    protected static $_db = null;
    protected static $_tbname = '';
    protected static $_primary_key = 'id';

    /**
     * Static constructor
     * @param string $db Data base connection config name
     */
    public static function __init($db = 'db'){
        static::$_tbname = cot::$db->city;
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
                    'description' => cot::$L['Country'],
                    'length' => 3,
                    'nullable' => false,
                ),
            'region' =>
                array(
                    'type' => 'link',
                    'description' => cot::$L['rec_region'],
                    'nullable' => false,
                    'link' =>
                        array(
                            'model' => 'regioncity_model_Region',
                            'relation' => Som::TO_ONE,
                            'label' => 'title',
                        ),
                ),
            'title' =>
                array(
                    'type' => 'varchar',
                    'description' => cot::$L['rec_title'],
                    'nullable' => false,
                ),
            'sort' => array (
                'type' => 'int',
                'default' => 0,
                'description' => cot::$L['rec_sort'],
            ),
        );
    }

}

// Class initialization for some static variables
regioncity_model_City::__init();
