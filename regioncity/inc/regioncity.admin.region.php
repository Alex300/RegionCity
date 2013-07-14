<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Admin Region Controller class for the Region City plugin
 *
 * @package Region City
 * @subpackage Region
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
class RegionController{

    /**
     * Main (index) Action.
     */
    public function indexAction(){
        global $adminpath, $cot_countries, $L, $cfg;

        $country = cot_import('country', 'G', 'TXT');

        $adminpath[] = array(cot_url('admin', 'm=other&p=regioncity'), $L['rec_countries'] );
        $adminpath[] = $cot_countries[$country];

        $cond = array(array('region_country', $country));

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        $totalitems = Region::count($cond);
        $pagenav = cot_pagenav('admin', "m=other&p=regioncity&n=region&country=" . $country, $d, $totalitems, $cfg['maxrowsperpage']);

        $regions = Region::find($cond, $cfg['maxrowsperpage'], $d, 'region_title ASC');

        $t = new XTemplate(cot_tplfile('regioncity.region', 'plug'));
        $cnt = 0;
        if($regions){
            foreach($regions as $item){
                $cnt++;

                $t->assign(array(
                    "REGION_ROW_NAME" => cot_inputbox('text', 'rname[' . $item->region_id . ']', $item->region_title),
                    "REGION_ROW_URL" => cot_url('admin', 'm=other&p=regioncity&n=city&rid=' . $item->region_id),
                    "REGION_ROW_DEL_URL" => cot_confirm_url(cot_url('admin', 'm=other&p=regioncity&n=region&country=' . $country . '&a=del&rid=' . $item->region_id), 'regioncity'),
                    "REGION_ROW_NUM" => $cnt,
                    "REGION_ROW_ODDEVEN" => cot_build_oddeven($cnt)
                ));

                $t->parse("MAIN.ROWS");
            }
        }
        if ($cnt == 0) $t->parse("MAIN.NOROWS");

        $t->assign(array(
            "ADD_FORM_ACTION_URL" => cot_url('admin', 'm=other&p=regioncity&n=region&country=' . $country . '&a=add&d='.$d_url),
            "ADD_FORM_NAME" => cot_inputbox('text', 'rname', ''),
        ));
        $t->parse("MAIN.ADDFORM");

        $t->assign(array(
            "EDIT_FORM_ACTION_URL" => cot_url('admin', 'm=other&p=regioncity&n=region&country=' . $country . '&a=edit&d=' . $d_url),
            "PAGENAV_PAGES" => $pagenav['main'],
            "PAGENAV_PREV" => $pagenav['prev'],
            "PAGENAV_NEXT" => $pagenav['next'],
            "COUNTRY_NAME" => $cot_countries[$country],
        ));

        // Error and message handling
        cot_display_messages($t);

        $t->parse("MAIN");
        return $t->text("MAIN");
    }

    /**
     *
     */
    public function addAction(){
        global $cfg, $cache;

        $country = cot_import('country', 'G', 'TXT');
        $title = cot_import('rname', 'P', 'TXT');

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        if(empty($country)){
            cot_error('Country empty');
        }
        if(empty($title)){
            cot_error('Region title empty');
        }

        if(!cot_error_found()){
            $region = new Region();
            $region->region_title = $title;
            $region->region_country = $country;
            $region->save();

            $cache && $cache->clear();

            cot_message('Saved');
        }

        cot_redirect(cot_url('admin', "m=other&p=regioncity&n=region&country={$country}&d={$d_url}", '', true));
    }


    public function delAction(){
        global $db, $db_rec_region, $db_rec_city, $cache, $cfg;

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        $country = cot_import('country', 'G', 'TXT');
        $rid = cot_import('rid', 'G', 'INT');

        $region = Region::getById($rid);
        if(empty($country)) $country = $region->region_country;

        $db->delete($db_rec_region, "region_id=" . (int)$rid);
        $db->delete($db_rec_city, "city_region=" . (int)$rid);

        $cache && $cache->clear();

        cot_message("Deleted '{$region->region_title}'");

        cot_redirect(cot_url('admin', 'm=other&p=regioncity&n=region&country=' . $country . '&d=' . $d_url, '', true));
        exit;
    }



    public function editAction(){
        global $cfg, $db, $db_rec_region, $cache, $db_rec_city;

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        $country = cot_import('country', 'G', 'TXT');
        $rnames = cot_import('rname', 'P', 'ARR');
        $cnt = 0;
        foreach ($rnames as $rid => $rname){
            $rinput = array();
            $rinput['region_title'] = cot_import($rname, 'D', 'TXT');
            if (!empty($rinput['region_title'])){
                $cnt += $db->update($db_rec_region, $rinput, "region_id=" . (int)$rid);

            }
            else
            {
//                $db->delete($db_rec_region, "region_id=" . (int)$rid);
//                $db->delete($db_rec_city, "city_region=" . (int)$rid);
            }
        }
        if($cnt > 0) cot_message("Updated ");
        $cache && $cache->clear();
        cot_redirect(cot_url('admin', 'm=other&p=regioncity&n=region&country=' . $country . '&d=' . $d_url, '', true));
        exit;

    }
}
