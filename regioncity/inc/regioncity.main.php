<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Main Controller class for the Region City plugin
 *
 * @package Region City
 * @author Alex - Studio Portal30
 * @copyright Portal30 2013 http://portal30.ru
 */
class MainController{

    /**
     * Main (index) Action.
     * Объявления пользователя
     */
    public function indexAction(){
        global $t, $L, $cfg, $usr, $sys, $out, $db_users, $db;
        cot_die_message(404, TRUE);
        return "qwerty";

    }

    /**
     * Получить список регионов для страны
     */
    public function axjGetRegionsAction(){
        global $cfg, $L;

        $country = cot_import('country', 'R', 'TXT', 3);

        $regions = array();
        if ($country != '0'){
            $regions = regioncity_model_Region::getKeyValPairsByCountry($country);
        }

        $ret = array(
            'regions' => array(0 => $L['select_region']) + $regions,
            'disabled' => (empty($country) || count($regions) == 0) ? 1 : 0,
        );

        echo json_encode($ret);
        exit;
    }

    /**
     * Получить список городов для региона
     */
    public function axjGetCitiesAction(){
        global $cfg, $L;

        $region = cot_import('region', 'R', 'INT');

        $cities = array();
        if ($region){
            $cities = regioncity_model_City::getKeyValPairsByRegion($region);
        }

        $ret = array(
            'cities' => array(0 => $L['select_city']) + $cities,
            'disabled' => (!$region || count($cities) == 0) ? 1 : 0,
        );

        echo json_encode($ret);
        exit;
    }

    /**
     * Получить список городов по первой букве
     */
    public function ajxSuggestCityAction(){
        $v = cot_import('q', 'G', 'TXT');
        $page = cot_import('page', 'G', 'INT');
        if(!$page) $page = 1;
        $limit = cot_import('page_limit', 'G', 'INT');

        $list = regioncity_model_City::find(array(array('city_title', '*'.$v.'*')), 10, ($page - 1) * $limit);
        $mOut = array();
        $mOut['total'] = regioncity_model_City::count(array(array('city_title', '*'.$v.'*')));
        if (!empty($list))
            foreach ($list as $MCity) $mOut['data'][] = array(
                'id' => $MCity->city_id,
                'text' => $MCity->city_title
            );
        else {
            $mOut['data'] = array();
        }

        echo json_encode($mOut);
        exit();
    }

    /**
     * Временно. Убрать. Конвертер
     */
    public function convertAction(){
        global $db, $db_users;
        die('Wrong Url');
        $users = $db->query("SELECT user_id,user_name, user_country,user_city_name, user_region_name, user_city,
                  user_region
                FROM $db_users WHERE (user_city=0 OR user_region=0) AND (user_city_name!='' OR user_region_name!='')");
        $users = $users->fetchAll();

        cot_sendheaders();

        foreach($users as $user){
            echo "Обработка: {$user['user_id']}: {$user['user_name']}<br />\n";
            $data = array();
            $region = false;
            $city = false;
            $country = false;
            $regionFromCity = false;
            // Получаем город
            if ($user['user_city'] == 0){
                $cond = array();
                if ($user['user_city_name'] != ''){
                    $cond[] = array('city_title', $user['user_city_name']);
                    if ($user['user_country'] != '' && $user['user_country'] != '00') $cond[] = array('city_country', $user['user_country']);
                    if ($user['user_region'] != 0) $cond[] = array('city_region', $user['user_region']);
                }
                if (count($cond) > 0){
                    $tmp = City::find($cond);
                    if ($tmp){
                        $city = (int)$tmp[0]->city_id;
                        $region = (int)$tmp[0]->city_region;
                        if ($user['user_country'] == '' || $user['user_country'] == '00'){
                            $country = $tmp[0]->city_country;
                        }
                    }
                }
            }elseif($user['user_region'] == 0){
                // Получить регион
                $tmp = City::getById($user['user_city']);
                if($tmp){
                    $region = $tmp->city_region;
                    $regionFromCity = true;
                }
            }

            // Получаем регион
            if ($user['user_region'] == 0 && !$regionFromCity){
                $cond = array();
                if ($user['user_region_name'] != ''){
                    $cond[] = array('region_title', $user['user_region_name']);
                    if ($user['user_country'] != '' && $user['user_country'] != '00') $cond[] = array('region_country', $user['user_country']);
                }
                if (count($cond) > 0){
                    $tmp = Region::find($cond);
                    if ($tmp){
                        $region = (int)$tmp[0]->region_id;
                        if ($user['user_country'] == '' || $user['user_country'] == '00'){
                            $country = $tmp[0]->region_country;
                        }
                    }
                }
            }

            if ($country) $data['user_country'] = $country;
            if ($region) $data['user_region'] = $region;
            if ($city) $data['user_city'] = $city;
            if (count($data) > 0){
                echo " - {$data['user_region']}, {$data['user_city']}<br /><br />\n";
                $db->update($db_users, $data, "user_id={$user['user_id']}");
            }
            var_dump($user);
            var_dump($data);
        }

        exit;
    }
}
