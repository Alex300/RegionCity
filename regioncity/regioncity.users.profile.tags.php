<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.profile.tags, users.edit.tags, users.register.tags
[END_COT_EXT]
==================== */
/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('regioncity', 'plug');
require_once cot_langfile('regioncity', 'plug');

$rec_uRegFlds = explode(',',  cot::$cfg['plugin']['regioncity']['user_region']);
$rec_uCityFlds = explode(',', cot::$cfg['plugin']['regioncity']['user_city']);

foreach ($rec_uRegFlds as $key => $val){
    $rec_uRegFlds[$key] = trim($rec_uRegFlds[$key]);
    if (!isset($cot_extrafields[cot::$db->users][$val])) unset($rec_uRegFlds[$key]);
}

foreach ($rec_uCityFlds as $key => $val){
    $rec_uCityFlds[$key] = trim($rec_uCityFlds[$key]);
    if (!isset($cot_extrafields[cot::$db->users][$val])) unset($rec_uCityFlds[$key]);
}

$uPrefix = 'USERS_PROFILE_';
$countryFldName = 'rusercountry';
if ($m == 'edit') $uPrefix = 'USERS_EDIT_';
if ($m == 'register'){
    $countryFldName = 'rcountry';
    $uPrefix = 'USERS_REGISTER_';
}

$t->assign(rec_select_location(
            array($countryFldName, $uPrefix.'COUNTRY'),
            array("ruser{$rec_uRegFlds[0]}", $uPrefix.strtoupper($rec_uRegFlds[0])),
            array("ruser{$rec_uCityFlds[0]}", $uPrefix.strtoupper($rec_uCityFlds[0])),
            $urr['user_country'], $urr["user_{$rec_uRegFlds[0]}"], $urr["user_{$rec_uCityFlds[0]}"])
);
