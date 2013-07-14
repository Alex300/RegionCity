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
require_once "{$cfg['plugins_dir']}/regioncity/model/Region.php";
require_once "{$cfg['plugins_dir']}/regioncity/model/City.php";

function rec_select_location($counName = 'country', $regName = 'region', $cityName = 'city',
                    $country = '', $region = 0, $city = 0){

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
        'class' => "rec_country"
    );
    if (!empty($cfg['plugin']['regioncity']['countriesfilter']) && $cfg['plugin']['locationselector']['countriesfilter'] != 'all') {
        $countriesfilter = str_replace(' ', '', $cfg['plugin']['locationselector']['countriesfilter']);
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
    $regions = (!empty($country)) ? Region::getKeyValPairsByCountry($country) : array();
    $regions = array(0 => $L['select_region']) + $regions;
    $attr = array(
        'id' => "rec_region_{$elmCnt}",
        'class' => "rec_region"
    );
    if(empty($country) || count($regions) < 2) $attr['disabled'] = 'disabled';
    // 1-ый хидден, чтобы отправить 0 если сам selectbox станет disable
    $region_selectbox = cot_inputbox('hidden', $regName[0], 0);
    $region_selectbox .= cot_selectbox($region, $regName[0], array_keys($regions), array_values($regions),
        false, $attr);
    $val = ($region > 0) ? $regions[$region] : '';
    $region_selectbox .= cot_inputbox('hidden', $regName[0].'_name', $val, array('id' => "rec_region_{$elmCnt}_name"));

    $city = ($region == 0 || count($regions) < 2) ? 0 : $city;
    $cities = (!empty($region)) ? City::getKeyValPairsByRegion($region) : array();
    $cities = array(0 => $L['select_city']) + $cities;
    $attr = array(
        'id' => "rec_city_{$elmCnt}",
        'class' => "rec_city"
    );
    if(empty($region) || count($cities) < 2) $attr['disabled'] = 'disabled';
    // 1-ый хидден, чтобы отправить 0 если сам selectbox станет disable
    $city_selectbox = cot_inputbox('hidden', $cityName[0], 0);
    $city_selectbox .= cot_selectbox($city, $cityName[0], array_keys($cities), array_values($cities),
        false, $attr);
    $val = ($city > 0) ? $cities[$city] : '';
    $city_selectbox .= cot_inputbox('hidden', $cityName[0].'_name' ,$val, array('id' => "rec_city_{$elmCnt}_name"));

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
