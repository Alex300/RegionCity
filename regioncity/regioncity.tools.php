<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */
/**
 * Region City plugin for Cotonti
 *
 * @package Region City
 * @author esclkm, Yusupov, Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'regioncity', 'RWA');
cot_block($usr['isadmin']);

if (!$cot_countries) include_once cot_langfile('countries', 'core');

require_once cot_incfile('forms');
require_once cot_incfile('regioncity', 'plug');
require_once cot_langfile('regioncity', 'plug');

// Стандартный Роутер
// Only if the file exists...
if (!$n) $n = 'country';

if (file_exists(cot_incfile('regioncity', 'plug', 'admin.'.$n))) {
    require_once cot_incfile('regioncity', 'plug', 'admin.'.$n);
    /* Create the controller */
    $_class = ucfirst($n).'Controller';
    $controller = new $_class();

    // TODO кеширование
    /* Perform the Request task */
    $shop_action = $a.'Action';
    if (!$a && method_exists($controller, 'indexAction')){
        $content = $controller->indexAction();
    }elseif (method_exists($controller, $shop_action)){
        $content = $controller->$shop_action();
    }else{
        // Error page
        cot_die_message(404);
        exit;
    }
    // todo дописать как вывод для плагинов
    if (isset($content)){
        $plugin_body .= $content;
    }

}else{
    // Error page
    cot_die_message(404);
    exit;
}

