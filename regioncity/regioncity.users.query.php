<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.query
[END_COT_EXT]
==================== */
/**
 * Region City plugin for Cotonti
 *  Фильтры списка пользователей по городам
 * @package Region City
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('regioncity', 'plug');
require_once cot_langfile('regioncity', 'plug');

$rec_uRegFlds = explode(',', $cfg['plugin']['regioncity']['user_region']);
$rec_uCityFlds = explode(',', $cfg['plugin']['regioncity']['user_city']);

foreach ($rec_uRegFlds as $key => $val){
    $rec_uRegFlds[$key] = trim($rec_uRegFlds[$key]);
    if (!isset($cot_extrafields['cot_users'][$val])) unset($rec_uRegFlds[$key]);
}

foreach ($rec_uCityFlds as $key => $val){
    $rec_uCityFlds[$key] = trim($rec_uCityFlds[$key]);
    if (!isset($cot_extrafields['cot_users'][$val])) unset($rec_uCityFlds[$key]);
}

$recf_country = cot_import('recf_country', 'G', 'ALP', 2);
if(empty($recf_country)) $recf_country = cot_import('recf_country', 'P', 'ALP', 2);

$recf_region = cot_import('recf_region', 'G', 'INT');
if(empty($recf_region)) $recf_region = cot_import('recf_region', 'P', 'INT');

$recf_city = cot_import('recf_city', 'G', 'INT');
if(empty($recf_city)) $recf_city = cot_import('recf_city', 'P', 'INT');

if(!empty($recf_country)){
    $where['country'] = "user_country='".$db->prep($recf_country)."'";
    $users_url_path['recf_country'] = $recf_country;
}
if(!empty($recf_region)){
    $where['recf_region'] = "user_{$rec_uRegFlds[0]}=".$recf_region;
    $users_url_path['recf_region'] = $recf_region;
}
if(!empty($recf_city)){
    $where['recf_city'] = "user_{$rec_uCityFlds[0]}=".$recf_city;
    $users_url_path['recf_city'] = $recf_city;
}
