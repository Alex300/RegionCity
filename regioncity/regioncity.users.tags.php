<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.tags
Tags=users.tpl:{USERS_TOP_FILTERS_COUNTRY},{USERS_TOP_FILTERS_REGION},{USERS_TOP_FILTERS_CITY}
[END_COT_EXT]
==================== */
/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');


$t->assign(rec_select_location(
    array('recf_country', 'USERS_TOP_FILTERS_COUNTRY'),
    array('recf_region',  'USERS_TOP_FILTERS_REGION'),
    array('recf_city',    'USERS_TOP_FILTERS_CITY'),
    $recf_country, $recf_region, $recf_city)
);
