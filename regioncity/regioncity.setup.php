<?php
/* ====================
[BEGIN_COT_EXT]
Code=regioncity
Name=Region City
Description=Редактор/Селектор стран, регионов, городов
Version=1.0.4
Date=29 September 2015
Author=Yusupov, esclkm, Alex
Copyright=2013-2015 Portal30 http://portal30.ru
Notes=
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
countriesfilter=01:string:::Show only countries
user_country=05:string::country:User country fields
user_region=10:string:::User region fields
user_city=15:string:::User city fields
page_country=20:string:::Page country fields
page_region=25:string:::Page region fields
page_city=30:string:::Page city fields
[END_COT_EXT_CONFIG]
==================== */

/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author Yusupov, esclkm, Alex - Studio Portal30
 * @copyright Portal30 2013-2015 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');
