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

$rec_uCouFlds = explode(',',  cot::$cfg['plugin']['regioncity']['user_country']);
$rec_uRegFlds = explode(',',  cot::$cfg['plugin']['regioncity']['user_region']);
$rec_uCityFlds = explode(',', cot::$cfg['plugin']['regioncity']['user_city']);

foreach ($rec_uCouFlds as $key => $val){
    $rec_uCouFlds[$key] = trim($rec_uCouFlds[$key]);
    if ($val != 'country' && !isset($cot_extrafields[cot::$db->users][$val])) unset($rec_uCouFlds[$key]);
}

foreach ($rec_uRegFlds as $key => $val){
    $rec_uRegFlds[$key] = trim($rec_uRegFlds[$key]);
    if (!isset($cot_extrafields[cot::$db->users][$val])) unset($rec_uRegFlds[$key]);
}

foreach ($rec_uCityFlds as $key => $val){
    $rec_uCityFlds[$key] = trim($rec_uCityFlds[$key]);
    if (!isset($cot_extrafields[cot::$db->users][$val])) unset($rec_uCityFlds[$key]);
}

$uPrefix = 'USERS_PROFILE_';
if ($m == 'edit')       $uPrefix = 'USERS_EDIT_';
if ($m == 'register')   $uPrefix = 'USERS_REGISTER_';

foreach ($rec_uCouFlds as $key => $val) {
    $countryFldName = 'ruser'.$val;
    if($val == 'country') {
        if ($m == 'register') {
            $countryFldName = 'rcountry';
        }
    }

    var_dump_("user_{$rec_uCouFlds[$key]}", "user_{$rec_uRegFlds[$key]}", "user_{$rec_uCityFlds[$key]}");
    var_dump_($urr["user_{$rec_uCouFlds[$key]}"], $urr["user_{$rec_uRegFlds[$key]}"], $urr["user_{$rec_uCityFlds[$key]}"]);

    $t->assign(rec_select_location(
            array($countryFldName, $uPrefix . strtoupper($rec_uCouFlds[$key])),
            array("ruser{$rec_uRegFlds[$key]}", $uPrefix . strtoupper($rec_uRegFlds[$key])),
            array("ruser{$rec_uCityFlds[$key]}", $uPrefix . strtoupper($rec_uCityFlds[$key])),
            $urr["user_{$rec_uCouFlds[$key]}"], $urr["user_{$rec_uRegFlds[$key]}"], $urr["user_{$rec_uCityFlds[$key]}"])
    );
}