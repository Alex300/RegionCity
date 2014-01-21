<?php
/* ====================
[BEGIN_COT_EXT]
Code=regioncity
Name=Region City
Description=Редактор/Селектор стран, регионов, городов
Version=1.0.0
Date=15 Febreury 2013
Author=Yusupov, esclkm, Alex
Copyright=2013 Portal30 http://portal30.ru
Notes=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
countriesfilter=01:string:::Show only countries
user_region=05:string:::User region fields
user_city=10:string:::User city fields
page_region=15:string:::Page region fields
page_city=20:string:::Page city fields
[END_COT_EXT_CONFIG]
==================== */

/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author Yusupov, esclkm, Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');
