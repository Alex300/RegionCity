<?php
/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author Yusupov, Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');

$db_rec_region = (isset($db_rec_region)) ? $db_rec_region : $db_x . 'region';
$db_rec_city = (isset($db_rec_city)) ? $db_rec_city : $db_x . 'city';

require_once cot_incfile('forms');

// Автозагрузка
require_once 'lib/Loader.php';
Loader::register();

/**
 * Виджет выбора региона/города
 *
 * @param string|array $counName
 * @param string|array $regName
 * @param string|array $cityName
 * @param string $country
 * @param int $region
 * @param int $city
 * @param bool $sendNames - Передавать в запрос названия Страны, региона, города
 * @param bool $fallback  - Передавать в запрос значение '0', если выбор региона/города - disabled?
 *
 * @return array
 */
function rec_select_location($counName = 'country', $regName = 'region', $cityName = 'city',
                    $country = '', $region = 0, $city = 0, $sendNames = true, $fallback = true){

    global $cfg, $L, $R;

    static $elmCnt = 0;

    if (!is_array($counName)) $counName = array($counName);
    if (empty($counName[1])) $counName[1] = strtoupper($counName[0]);

    if (!is_array($regName)) $regName = array($regName);
    if (empty($regName[1])) $regName[1] = strtoupper($regName[0]);

    if (!is_array($cityName)) $cityName = array($cityName);
    if (empty($cityName[1])) $cityName[1] = strtoupper($cityName[0]);

    $countriesfilter = array();
    $attr = array(
        'id' => "rec_country_{$elmCnt}",
        'class' => "rec_country form-control"
    );
    if (!empty($cfg['plugin']['regioncity']['countriesfilter']) && $cfg['plugin']['regioncity']['countriesfilter'] != 'all') {
        $countriesfilter = str_replace(' ', '', $cfg['plugin']['regioncity']['countriesfilter']);
        $countriesfilter = explode(',', $countriesfilter);
        if(count($countriesfilter) == 1) $attr['disabled'] = 'disabled';
        $country = (count($countriesfilter) == 1) ? $countriesfilter[0] : $country;
    }

    $countries = cot_getcountries($countriesfilter);
    $countries = array(0 => $L['select_country']) + $countries;
    $country_selectbox = cot_selectbox($country, $counName[0], array_keys($countries), array_values($countries),
        false, $attr);
    $country_selectbox .= (count($countriesfilter) == 1) ? cot_inputbox('hidden', $counName[0], $country) : '';

    $region = ($country == '' || count($countries) < 2) ? 0 : $region;
    $regions = (!empty($country)) ? regioncity_model_Region::getKeyValPairsByCountry($country) : array();
    $regions = array(0 => $L['select_region']) + $regions;
    $attr = array(
        'id' => "rec_region_{$elmCnt}",
        'class' => "rec_region form-control"
    );
    if(empty($country) || count($regions) < 2) $attr['disabled'] = 'disabled';
    $region_selectbox = '';
    // 1-ый хидден, чтобы отправить 0 если сам selectbox станет disable. без вывода ошибок
    if($fallback) $region_selectbox = '<input type="hidden" name="'.$regName[0].'" value="0" />';
    $region_selectbox .= cot_selectbox($region, $regName[0], array_keys($regions), array_values($regions),
        false, $attr);
    $val = ($region > 0) ? $regions[$region] : '';
    if($sendNames) $region_selectbox .= cot_inputbox('hidden', $regName[0].'_name', $val, array('id' => "rec_region_{$elmCnt}_name"));

    $city = ($region == 0 || count($regions) < 2) ? 0 : $city;
    $cities = (!empty($region)) ? regioncity_model_City::getKeyValPairsByRegion($region) : array();
    $cities = array(0 => $L['select_city']) + $cities;
    $attr = array(
        'id' => "rec_city_{$elmCnt}",
        'class' => "rec_city form-control"
    );
    if(empty($region) || count($cities) < 2) $attr['disabled'] = 'disabled';
    $city_selectbox = '';
    // 1-ый хидден, чтобы отправить 0 если сам selectbox станет disable
    if($fallback) $city_selectbox .= '<input type="hidden" name="'.$cityName[0].'" value="0" />';
    $city_selectbox .= cot_selectbox($city, $cityName[0], array_keys($cities), array_values($cities),
        false, $attr);
    $val = ($city > 0) ? $cities[$city] : '';
    if($sendNames) $city_selectbox .= cot_inputbox('hidden', $cityName[0].'_name' ,$val, array('id' => "rec_city_{$elmCnt}_name"));

    $result = array(
        $counName[1] => $country_selectbox,
        $regName[1] => $region_selectbox,
        $cityName[1] => $city_selectbox,
    );

    $elmCnt++;

    if(!defined('REGION_CITY_JS')){
        cot_rc_link_footer("{$cfg['plugins_dir']}/regioncity/js/regioncity.js");
        define('REGION_CITY_JS', 1);
    }

    return $result;
}

/**
 * Renders a Select2 city dropdown
 *
 * Select2 must be installed on your site
 * @see http://ivaynberg.github.io/select2/
 *
 * @param string $name Dropdown name
 * @param int array|int $chosen Seleced value (or values array for mutli-select)
 * @param bool $add_empty Allow empty choice
 * @param mixed $attrs Additional attributes as an associative array or a string
 * @param string $custom_rc Custom resource string name
 * @return string
 */
function rec_select2_city($name, $chosen = 0, $add_empty = true, $attrs = array(), $custom_rc = '' ){

    //$input_attrs = cot_rc_attr_string($attrs);
    $chosen = cot_import_buffered($name, $chosen);
//    $multi = is_array($chosen) && (mb_strpos($input_attrs, 'multiple') !== false);

    $params = '';
    if(empty($attrs['placeholder'])){
        if (!empty($attrs['multiple'])){
            $attrs['placeholder'] = 'Кликните для выбора';
        }else{
            $attrs['placeholder'] = 'Пожалуйста выберите';
        }
    }

    if($add_empty){
        $attrs['allowClear'] = 'true';
    }

    foreach ($attrs as $k => $v) {
        if (is_string($v) || is_bool($v)) {
            if ($k == 'multiple') {
                $v = 'true';
            }
            $params .= '"' . $k . '":"' . $v . '",';
        }
    }

    if (!empty($attrs['class'])) {
        if (mb_strpos($attrs['class'], 'form-control') === false)
            $attrs['class'] .= ' form-control';
    } else {
        $attrs['class'] = 'form-control';
    }

    $chosenName = '';
    if (is_array($chosen) && (null !== current($chosen)) && current($chosen) instanceof Som_Model_Abstract) {
        // реализуй меня ))
//            foreach ($choosen as $Model) {
//
//            }
//            $values = $valuesToInput;
    } else {
        if (is_int($chosen) || ctype_digit($chosen)){
            $chosen = regioncity_model_City::getById($chosen);
        }

        if ($chosen instanceof regioncity_model_City) {
            $vrValues[] = array(
                'id'   => $chosen->city_id,
                'text' => $chosen->city_title,
                $chosenName = $chosen->city_title
            );
        }
    }

    if(empty($attrs['id'])) $attrs['id'] = str_replace(array('[]','][','[',']',' '), array('','_','_','','_'), $name);

    $attrs['data-params'] =
        '{
             "params":{
                '.$params.'
                "minimumInputLength": 2,
                "quietMillis": 100
             },
             "initSelection":' . (isset($options['multiple']) ? json_encode($vrValues) :
                                     (empty($vrValues) ? 'null' : json_encode($vrValues[0]))) . '
        }';


    if(!defined('REGION_CITY_JS')){
        cot_rc_link_footer(cot::$cfg['plugins_dir'].'/regioncity/js/regioncity.js');
        define('REGION_CITY_JS', 1);
    }

    cot_rc_embed_footer("
        $('#{$attrs['id']}').select2City();
    ");

    return cot_inputbox('hidden', $name, '', $attrs).cot_inputbox('hidden', $name.'_name' ,$chosenName,
        array('id' => "{$attrs['id']}_name"));
}