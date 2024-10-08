<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Admin City Controller class for the Region City plugin
 *
 * @package Region City
 * @subpackage City
 * 
 * @author Kalnov Alexey <kalnovalexey@yandex.ru>
 * @copyright © Lily Software https://lily-software.com
 */
class CityController
{
    /**
     * Main (index) Action.
     */
    public function indexAction(){
        global $adminPath, $adminTitle, $adminSubtitle, $cot_countries, $L, $cfg;

        $country = cot_import('country', 'G', 'TXT');

        $adminPath[] = array(cot_url('admin', 'm=other&p=regioncity'), $L['rec_countries'] );

        $rid = cot_import('rid', 'G', 'INT');

        $region = regioncity_model_Region::getById($rid);
        if(!$region){
            cot_error('Region not found');
        }

        $adminPath[] = array(cot_url('admin', "m=other&p=regioncity&n=region&country={$region->country}"), $cot_countries[$region->country] );
        $adminPath[] = $region->title;

        $country = $region->country;

        $adminTitle = $adminSubtitle  = $region->title . ' (' . $cot_countries[$country] . ')';

        $cond = array(array('region', $rid));

        list($pn, $d, $d_url) = cot_import_pagenav('d', Cot::$cfg['maxrowsperpage']);

        $totalitems = regioncity_model_City::count($cond);
        $pagenav = cot_pagenav('admin', "m=other&p=regioncity&n=city&rid=" . $rid, $d, $totalitems, Cot::$cfg['maxrowsperpage']);

        $cities = regioncity_model_City::find($cond, Cot::$cfg['maxrowsperpage'], $d, array(array('sort', 'DESC'), array('title', 'ASC')));
        $regionsArr = regioncity_model_Region::getKeyValPairsByCountry($region->country);


        $t = new XTemplate(cot_tplfile('regioncity.admin.city', 'plug', true));
        $cnt = 0;
        if($cities){
            foreach($cities as $item){
                $cnt++;
                $t->assign(array(
                    "CITY_ROW_NAME" => cot_inputbox('text', 'rname[' . $item->id . ']', $item->title),
                    "CITY_ROW_REGION" => cot_selectbox($item->region, "rregion[{$item->id}]", array_keys($regionsArr),
                        array_values($regionsArr), false),
                    "CITY_ROW_DEL_URL" => cot_confirm_url(cot_url('admin', 'm=other&p=regioncity&n=city&a=del&cid=' . $item->id), 'regioncity'),
                    "CITY_ROW_SORT" => cot_inputbox('text', 'rsort[' . $item->id . ']', $item->sort),
                    "CITY_ROW_NUM" => $cnt,
                    "CITY_ROW_ODDEVEN" => cot_build_oddeven($cnt)
                ));

                $t->parse("MAIN.ROWS");
            }
        }
        if ($cnt == 0) $t->parse("MAIN.NOROWS");

        $t->assign(array(
            "ADD_FORM_NAME" => cot_textarea('rname', '', 10, 60),
            "ADD_FORM_ACTION_URL" => cot_url('admin', "m=other&p=regioncity&n=city&rid={$rid}&a=add", '', true),
        ));
        $t->parse("MAIN.ADDFORM");

        $t->assign(array(
            "EDIT_FORM_ACTION_URL" => cot_url('admin', "m=other&p=regioncity&n=city&rid={$rid}&a=edit&d={$d_url}"),
            "PAGENAV_PAGES" => $pagenav['main'],
            "PAGENAV_PREV"  => $pagenav['prev'],
            "PAGENAV_NEXT"  => $pagenav['next'],
            'TOTALITEMS'    => $totalitems,
            'ON_PAGE'       => $cnt,
            "COUNTRY_NAME"  => $cot_countries[$country],
            "REGION_NAME"   => $region->title
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
        global $cfg, $db, $cache, $db_rec_city;

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        $rid = cot_import('rid', 'G', 'INT');

        $rnames = cot_import('rname', 'P', 'TXT');
        $rnames = str_replace("\r\n", "\n", $rnames);
        $rnames = explode("\n", $rnames);

        if (count($rnames) > 0){

            $region = regioncity_model_Region::getById($rid);
            if(!$region){
                cot_error('Region not found');
                return false;
            }
            foreach ($rnames as $rname){
                if (!empty($rname)){
                    $rinput = array();
                    $rinput['title'] = trim(cot_import($rname, 'D', 'TXT'));
                    $rinput['region'] = (int)$rid;
                    $rinput['country'] = $region->country;
                    $db->insert($db_rec_city, $rinput);
                    cot_message("City Added: '{$rinput['title']}'");
                }
            }

            $cache && $cache->clear();
            cot_redirect(cot_url('admin', "m=other&p=regioncity&n=city&rid={$rid}&d={$d_url}", '', true));
            exit;
        }

    }


    public function delAction(){
        global $cache, $cfg;

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        $cid = cot_import('cid', 'G', 'INT');
        $city = regioncity_model_City::getById($cid);

        $rid = $city->region;
        $title = $city->title;

        $city->delete();

        $cache && $cache->clear();

        cot_message("Deleted city: '{$title}'");

        cot_redirect(cot_url('admin', "m=other&p=regioncity&n=city&rid={$rid}&d={$d_url}", '', true));
        exit;
    }



    public function editAction(){
        global $cfg, $db, $cache, $db_rec_city;

        list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

        $rid = cot_import('rid', 'G', 'INT');

        $rnames = cot_import('rname', 'P', 'ARR');
        $rregions = cot_import('rregion', 'P', 'ARR');
        $rsorts = cot_import('rsort', 'P', 'ARR');

        $cnt = 0;
        foreach ($rnames as $cid => $rname){

            $rinput = array();
            $rinput['title'] = cot_import($rname, 'D', 'TXT');
            $rinput['region'] = cot_import($rregions[$cid], 'D', 'INT');
            $rinput['sort'] = cot_import($rsorts[$cid], 'D', 'INT');
            if(!empty($rinput['title'])){
                $cnt += $db->update($db_rec_city, $rinput, "id=".(int)$cid);
            }else{

            }
        }
        if($cnt > 0) cot_message(cot::$L['Saved']);

        $cache && $cache->clear();
        cot_redirect(cot_url('admin', "m=other&p=regioncity&n=city&rid={$rid}&d={$d_url}", '', true));
        exit;

    }
}
