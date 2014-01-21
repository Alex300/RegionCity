<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */
// Плагин от esclm увеличивает использование памяти скриптом на 5 mb т.к. грузит в память всегда все города !!!
/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author esclkm, Yusupov, Alex
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('forms');
require_once  cot_incfile('regioncity', 'plug');
require_once cot_langfile('regioncity', 'plug');

global $db_rc_region, $db_rc_city, $db_x, $cot_countries;

if (!$cot_countries) include_once cot_langfile('countries', 'core');

function cot_getcountries($countriesfilter = array())
{
	global $cot_countries;

	$countries = array();
	foreach ($cot_countries as $code => $name)
	{
		if ((count($countriesfilter) > 0 && in_array($code, $countriesfilter)) || count($countriesfilter) == 0)
		{
			$countries[$code] = $name;
		}
	}
	asort($countries);
	return $countries;
}

function cot_getregions($country)
{
	global $cot_lf_regions, $cot_lf_locations;
	$regions = array();
	$cot_lf_locations[$country] = (is_array($cot_lf_locations[$country])) ? $cot_lf_locations[$country] : array();
	foreach ($cot_lf_locations[$country] as $i => $reg)
	{
		$regions[$i] = $cot_lf_regions[$i];
	}
	asort($regions);
	return $regions;
}

function cot_getcities($region)
{
	global $cot_lf_locations;

	$cities = array();
	foreach ($cot_lf_locations as $lcountry => $regs)
	{
		if (array_key_exists($region, $regs))
		{
			$country = $lcountry;
			break;
		}
	}
	
	foreach ($cot_lf_locations[$country][$region] as $id => $name)
	{
		$cities[$id] = $name;
	}
	asort($cities);
	return $cities;
}

function cot_getcountry($country)
{
	global $cot_countries;
	return $cot_countries[$country];
}

function cot_getregion($region)
{
	global $cot_lf_regions;
	return $cot_lf_regions[$region];
}

function cot_getcity($city)
{
	global $cot_lf_cities;
	return $cot_lf_cities[$city];
}

function cot_getlocation($country = '', $region = 0, $city = 0)
{
	global $cot_countries, $cot_lf_regions, $cot_lf_cities;
	
	$location['country'] = '';
	$location['region'] = '';
	$location['city'] = '';	
	if(!empty($country))
	{
		$location['country'] = $cot_countries[$country];
	}
	if(!empty($country) && (int)$region > 0)
	{
		$location['region'] = $cot_lf_regions[$region];
	}
	if(!empty($country) && (int)$region > 0 && (int)$city > 0)
	{
		$location['city'] = $cot_lf_cities[$city];	
	}
	return $location;
}

