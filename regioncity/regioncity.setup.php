<?php
/* ====================
[BEGIN_COT_EXT]
Code=regioncity
Name=Region City
Description=Selector / Editor countries, regions, cities
Version=1.0.7
Date=Sep 2, 2021
Author=Yusupov, esclkm, Kalnov Alexey    <kalnovalexey@yandex.ru>
Copyright=2013-2021 Lily Software https://lily-software.com (ex. Portal30 Studio)
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
 * @author Yusupov, esclkm, Kalnov Alexey    <kalnovalexey@yandex.ru>
 * @copyright 2013-2021 Lily Software https://lily-software.com (ex. Portal30 Studio)
 */
defined('COT_CODE') or die('Wrong URL.');
