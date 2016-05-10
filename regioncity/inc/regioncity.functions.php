<?php
/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 *
 * @author Yusupov, Kalnov Alexey - Studio Portal30
 * @copyright Portal30 Studio http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');

$db_rec_region = (isset($db_rec_region)) ? $db_rec_region : $db_x . 'region';
$db_rec_city = (isset($db_rec_city)) ? $db_rec_city : $db_x . 'city';

require_once cot_incfile('forms');

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
        Resources::linkFileFooter("{$cfg['plugins_dir']}/regioncity/js/regioncity.js");
        define('REGION_CITY_JS', 1);
    }

    return $result;
}


/**
 * Renders a Select2 city dropdown
 *
 * Select2 must be installed on your site
 * @see https://select2.github.io
 *
 * @param string $name Dropdown name
 * @param array|int $chosen Seleced value (or values array for mutli-select)
 * @param bool $add_empty Allow empty choice
 * @param mixed $attrs Additional attributes as an associative array or a string
 * @param string $custom_rc Custom resource string name
 * @return string
 */
function rec_select2_city($name, $chosen = 0, $add_empty = true, $attrs = array(), $custom_rc = '' ){

    //$input_attrs = cot_rc_attr_string($attrs);
    $chosen = cot_import_buffered($name, $chosen);
    $multi = is_array($chosen) && isset($attrs['multiple']) && !in_array($attrs['multiple'], array(false, 'false', 0, '0'));


    $params = '';
    if(empty($attrs['placeholder'])){
        if (!empty($attrs['multiple'])){
            $attrs['placeholder'] = cot::$L['rec_click_to_select'];
        }else{
            $attrs['placeholder'] = cot::$L['rec_please_select'];
        }
    }

    if($add_empty){
        $params = '"allowClear": "true",';
    }

    foreach ($attrs as $k => $v) {
        if(in_array($k, array('id', 'class'))) continue;
        if (is_string($v) || is_bool($v)) {
            if ($k == 'multiple') {
                $v = 'true';
            }
            $params .= '"' . $k . '":"' . $v . '",';
        }
    }

    if($multi) {
        $attrs['multiple'] = 'multiple';
    }

    if (!empty($attrs['class'])) {
        if (mb_strpos($attrs['class'], 'form-control') === false)
            $attrs['class'] .= ' form-control';
    } else {
        $attrs['class'] = 'form-control';
    }

    $chosenName = '';
    $chosenVal = null;
    $vrValues = array();
    if (is_array($chosen) && (null !== current($chosen))) {
        $chosenNames = array();
        $chosenVal = array();
        $ids = array();

        foreach ($chosen as $city) {
            if ($city instanceof regioncity_model_City) {
                $vrValues[$city->id] = $city->title;
                $chosenNames[] = $city->title;
                $chosenVal[] = $city->id;

            } elseif (is_int($city) || ctype_digit($city)) {
                $ids[] = $city;
            }
        }

        if(!empty($ids)) {
            $tmp = regioncity_model_City::find(array(array('id', $ids)), 0, 0, array(array('sort', 'desc'), array('title', 'asc')));
            if($tmp) {
                foreach ($tmp as $city) {
                    $vrValues[$city->id] = $city->title;
                    $chosenNames[] = $city->title;
                    $chosenVal[] = $city->id;
                }
            }
        }

        $chosenName = implode(',', $chosenNames);

    } else {
        if (is_int($chosen) || ctype_digit($chosen)){
            $chosen = regioncity_model_City::getById($chosen);
        }

        if ($chosen instanceof regioncity_model_City) {
            $vrValues[$chosen->id] = $chosen->title;
            $chosenName = $chosen->title;
            $chosenVal = $chosen->id;
        }
    }

    if(empty($attrs['id'])) $attrs['id'] = str_replace(array('[]','][','[',']',' '), array('','_','_','','_'), $name);

    $attrs['data-params'] =
        '{
             "params":{
                '.$params.'
                "minimumInputLength": 2,
                "quietMillis": 100
             }
        }';

    if(!defined('REGION_CITY_JS')){
        Resources::linkFileFooter(cot::$cfg['plugins_dir'].'/regioncity/js/regioncity.js');
        define('REGION_CITY_JS', 1);
    }
    Resources::embedFooter("$('#{$attrs['id']}').select2City();");

    $hidden = '';
    if(mb_strpos($name, '[') === false) $hidden = cot_inputbox('hidden', $name.'_name' ,$chosenName, array('id' => "{$attrs['id']}_name"));

    return cot_selectbox($chosenVal, $name, array_keys($vrValues), array_values($vrValues), false, $attrs).$hidden;
}
