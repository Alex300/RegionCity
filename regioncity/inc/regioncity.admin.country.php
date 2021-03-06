<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Admin Country Controller class for the Region City plugin
 *
 * @package Region City
 * @subpackage Country
 *
 * @author Kalnov Alexey    <kalnovalexey@yandex.ru>
 * @copyright © Portal30 Studio http://portal30.ru
 */
class CountryController{

    /**
     * Main (index) Action.
     */
    public function indexAction(){
        global $adminpath, $admintitle, $adminsubtitle, $cot_countries, $L;

        $adminpath[] = $L['rec_countries'];
        $admintitle = $adminsubtitle  = $L['rec_countries'];

        $t = new XTemplate(cot_tplfile('regioncity.admin.country', 'plug', true));
        $cnt = 0;
        foreach ($cot_countries as $code => $name){
            $cnt++;

            $flag = (!file_exists('images/flags/'.$code.'.png')) ? '00' : $code;
            $t->assign(array(
                "COUNTRY_ROW_CODE" => $code,
                "COUNTRY_ROW_NAME" => $name,
                "COUNTRY_ROW_URL" => cot_url('admin', 'm=other&p=regioncity&n=region&country=' . $code),
                "COUNTRY_ROW_FLAG" => cot_rc('icon_flag', array('code' => $flag, 'alt' => '')),
                "COUNTRY_ROW_NUM" => $cnt,
                "COUNTRY_ROW_ODDEVEN" => cot_build_oddeven($cnt)
            ));

            $t->parse("MAIN.ROWS");
        }
        if($cnt == 0) {
            $t->parse("MAIN.NOROWS");
        }
        $t->parse("MAIN");

        return $t->text("MAIN");
    }
}
