<?php
/* ====================
[BEGIN_COT_EXT]
Code=regioncity
Name=Region City
Description=Selector / Editor countries, regions, cities
Version=1.0.6
Date=2016-06-10
Author=Yusupov, esclkm, Kalnov Alexey
Copyright=2013-2016 Portal30 Studio http://portal30.ru
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
 * @author Yusupov, esclkm, Kalnov Alexey - Studio Portal30
 * @copyright 2013-2016 Portal30 Studio http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');
