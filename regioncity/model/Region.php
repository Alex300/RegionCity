<?php
defined('COT_CODE') or die('Wrong URL.');

/**
 * Model class for the Region
 *
 * @package Region City
 * @subpackage Region
 *
 * @author Kalnov Alexey    <kalnovalexey@yandex.ru>
 * @copyright © Portal30 Studio http://portal30.ru
 *
 * @method static regioncity_model_Region getById($pk);
 * @method static regioncity_model_Region fetchOne($conditions = array(), $order = '');
 * @method static regioncity_model_Region[] find($conditions = array(), $limit = 0, $offset = 0, $order = '');
 *
 * @property int $id;
 * @property string $country
 * @property string $title
 */
class regioncity_model_Region extends Som_Model_ActiveRecord
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

        $q = "SELECT id, title FROM ".static::$_db->quoteIdentifier(static::$_tbname)."
            WHERE `country`=? ORDER BY `title` ASC";
        $sql = static::$_db->query($q, array($country));

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
            'title' =>
                array(
                    'type' => 'varchar',
                    'length' => 255,
                    'nullable' => false,
                ),
        );
    }

}

// Class initialization for some static variables
regioncity_model_Region::__init();
