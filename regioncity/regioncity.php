<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
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

require_once cot_incfile('regioncity', 'plug');
require_once cot_langfile('regioncity');

// Роутер
// Only if the file exists...
if (!$m) $m = 'main';

if (file_exists(cot_incfile('regioncity', 'plug', $m))) {
    require_once cot_incfile('regioncity', 'plug', $m);
    /* Create the controller */
    $_class = ucfirst($m).'Controller';
    $controller = new $_class();

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

}else{
    // Error page
    cot_die_message(404);
    exit;
}
