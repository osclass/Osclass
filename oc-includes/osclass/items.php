<?php

/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

switch ($action) {


    case 'item_edit_post':
    case 'editItemPost':

        import_request_variables('p', 'P');
        
        $country = Country::newInstance()->findByCode($_POST['countryId']);
        if (count($country) > 0) {
            $countryId = $country['pk_c_code'];
            $countryName = $country['s_name'];
        } else {
            $countryId = null;
            $countryName = null;
        }

        if (isset($_POST['regionId'])) {
            if (intval($_POST['regionId'])) {
                $region = Region::newInstance()->findByPrimaryKey($_POST['regionId']);
                if (count($region) > 0) {
                    $regionId = $region['pk_i_id'];
                    $regionName = $region['s_name'];
                }
            }
        } else {
            $regionId = null;
            $regionName = $_POST['region'];
        }

        if (isset($_POST['cityId'])) {
            if (intval($_POST['cityId'])) {
                $city = City::newInstance()->findByPrimaryKey($_POST['cityId']);
                if (count($city) > 0) {
                    $cityId = $city['pk_i_id'];
                    $cityName = $city['s_name'];
                }
            }
        } else {
            $cityId = null;
            $cityName = $_POST['city'];
        }

        $location = array(
            'fk_c_country_code' => $countryId,
            's_country' => $countryName,
            'fk_i_region_id' => $regionId,
            's_region' => $regionName,
            'fk_i_city_id' => $cityId,
            's_city' => $cityName,
            's_city_area' => $_POST['cityArea'],
            's_address' => $_POST['address']
        );

        $locationManager = ItemLocation::newInstance();
        $locationManager->update($location, array('fk_i_item_id' => $Pid));

        // If the Google Maps plugin is well configured, we can try to geodecode the address
        if (isset($preferences['google_maps_key']) && !empty($preferences['google_maps_key'])) {
            $key = $preferences['google_maps_key'];
            $address = sprintf('%s, %s %s', $_POST['address'], $regionName, $cityName);
            $temp = file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false&key=%s', urlencode($address), $key));
            $temp = json_decode($temp);
            if (isset($temp->Placemark) && count($temp->Placemark[0]) > 0) {
                $coord = $temp->Placemark[0]->Point->coordinates;
                $locationManager->update(
                        array(
                            'd_coord_lat' => $coord[1],
                            'd_coord_long' => $coord[0]
                        ),
                        array('fk_i_item_id' => $Pid)
                );
            }
        }


        Item::newInstance()->update(array(
            'dt_pub_date' => DB_FUNC_NOW,
            'fk_i_category_id' => $PcatId,
            'f_price' => $Pprice,
            'fk_c_currency_code' => $Pcurrency
                ), array('pk_i_id' => $Pid, 's_secret' => $Psecret));
        
        $data = array();
        foreach ($_REQUEST as $k => $v) {
            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                $data[$m[1]][$m[2]] = $v;
            }
        }
        foreach ($data as $k => $_data) {
            Item::newInstance()->updateLocaleForce($Pid, $k, $_data['s_title'], $_data['s_description']);
        }


        $dao_itemResource = new ItemResource() ;
        foreach ($_FILES['photos']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $resourceName = $_FILES['photos']['name'][$key];
                $tmpName = $_FILES['photos']['tmp_name'][$key];
                $resourceType = $_FILES['photos']['type'][$key];

                $dao_itemResource->insert(array(
                    'fk_i_item_id' => $Pid,
                    's_name' => $resourceName,
                    's_content_type' => $resourceType
                ));
                $resourceId = $dao_itemResource->getConnection()->get_last_id() ;

                $thumbnailPath = APP_PATH . '/oc-content/uploads/' . $resourceId . '_thumbnail.png';
                ImageResizer::fromFile($tmpName)->resizeToMax(100)->saveToFile($thumbnailPath);

                $path = APP_PATH . '/oc-content/uploads/' . $resourceId.'.png';
                move_uploaded_file($tmpName, $path);

                $s_path = 'oc-content/uploads/' . $resourceId . '_thumbnail.png';
                $dao_itemResource->update(array(
                    's_path' => $s_path
                        ), array('pk_i_id' => $resourceId, 'fk_i_item_id' => $Pid));
            }
        }
        unset($dao_itemResource) ;


        $_POST['pk_i_id'] = $_POST['id'];
        osc_runHook('item_edit_post');

        osc_addFlashMessage(__('Great! We\'ve just update your item.'));
        //osc_redirectTo('user.php?action=items');
        break;

    case 'post_item':

        $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

        if (isset($preferences['reg_user_post'])) {
            if ($preferences['reg_user_post']) {
                if ($userId == null) {
                    osc_addFlashMessage(__('You new to log-in in order to post a new item.'));
                    osc_redirectTo('user.php?action=login');
                    break;
                }
            }
        }
        import_request_variables('p', 'P');
        
        if (isset($preferences['recaptchaPrivKey'])) {
            require_once 'recaptchalib.php';
            if (!empty($_POST["recaptcha_challenge_field"])) {
                $resp = recaptcha_check_answer(
                    $preferences['recaptchaPrivKey'],
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["recaptcha_challenge_field"],
                    $_POST["recaptcha_response_field"]
                );
                if (!$resp->is_valid) {
                    die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")");
                }
            }
        }

        // first of all, insert the item

        $code = osc_genRandomPassword();

        $active = (isset($preferences['enabled_item_validation']) && $preferences['enabled_item_validation']) ? 'INACTIVE' : 'ACTIVE' ;
        $show_email = isset($_POST['showEmail']) ? $_POST['showEmail'] : '0';

        if ($userId != null) {
            $data = User::newInstance()->findByPrimaryKey($userId);
            $PcontactName = $data['s_name'];
            $PcontactEmail = $data['s_email'];
        }
        
        $dao_item = new Item() ;
        $dao_item->insert(array(
            'fk_i_user_id' => $userId,
            'dt_pub_date' => DB_FUNC_NOW,
            'fk_i_category_id' => $PcatId,
            'f_price' => $Pprice,
            'fk_c_currency_code' => $Pcurrency,
            's_contact_name' => $PcontactName,
            's_contact_email' => $PcontactEmail,
            's_secret' => $code,
            'e_status' => $active,
            'b_show_email' => $show_email
        ));
        $itemId = $dao_item->getConnection()->get_last_id() ;
        
        // prepare locales
        $data = array();
        foreach ($_REQUEST as $k => $v) {
            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                $data[$m[1]][$m[2]] = $v;
            }
        }

        // insert locales
        foreach ($data as $k => $_data) {
            $dao_item->insertLocale($itemId, $k, $_data['s_title'], $_data['s_description'], $_data['s_title'] . " " . $_data['s_description']);
        }
        unset($dao_item) ;

        // insert location
        $country = Country::newInstance()->findByCode($_POST['countryId']);
        if(count($country) > 0) {
            $countryId = $country['pk_c_code'];
            $countryName = $country['s_name'];
        } else {
            $countryId = null;
            $countryName = null;
        }

        if( isset($_POST['regionId']) ) {
            if( intval($_POST['regionId']) ) {
                $region = Region::newInstance()->findByPrimaryKey($_POST['regionId']);
                if( count($region) > 0 ) {
                    $regionId = $region['pk_i_id'];
                    $regionName = $region['s_name'];
                }
            }
        } else {
            $regionId = null;
            $regionName = $_POST['region'];
        }

        if( isset($_POST['cityId']) ) {
            if( intval($_POST['cityId']) ) {
                $city = City::newInstance()->findByPrimaryKey($_POST['cityId']);
                if( count($city) > 0 ) {
                    $cityId = $city['pk_i_id'];
                    $cityName = $city['s_name'];
                }
            }
        } else {
            $cityId = null;
            $cityName = $_POST['city'];
        }

        if( empty($_POST['cityArea']) )
            $_POST['cityArea'] = null;

        if( empty($_POST['address']) )
            $_POST['address'] = null;

        $location = array(
            'fk_i_item_id' => $itemId,
            'fk_c_country_code' => $countryId,
            's_country' => $countryName,
            'fk_i_region_id' => $regionId,
            's_region' => $regionName,
            'fk_i_city_id' => $cityId,
            's_city' => $cityName,
            's_city_area' => $_POST['cityArea'],
            's_address' => $_POST['address']
        );

        $locationManager = ItemLocation::newInstance();
        $locationManager->insert($location);

        // If the Google Maps plugin is well configured, we can try to geodecode the address
        if (isset($preferences['google_maps_key']) && !empty($preferences['google_maps_key'])) {
            $key = $preferences['google_maps_key'];
            $address = sprintf('%s, %s %s', $_POST['address'], $regionName, $cityName);
            $temp = file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false&key=%s', urlencode($address), $key));
            $temp = json_decode($temp);
            if (isset($temp->Placemark) && count($temp->Placemark[0]) > 0) {
                $coord = $temp->Placemark[0]->Point->coordinates;
                $locationManager->update(
                        array(
                            'd_coord_lat' => $coord[1],
                            'd_coord_long' => $coord[0]
                        ),
                        array('fk_i_item_id' => $itemId)
                );
            }
        }

        if (isset($preferences['enabled_item_validation']) && !$preferences['enabled_item_validation']) {
            CategoryStats::newInstance()->increaseNumItems($PcatId);
        }
        
        $dao_itemResource = new ItemResource() ;
        foreach ($_FILES['photos']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $resourceName = $_FILES['photos']['name'][$key];
                $tmpName = $_FILES['photos']['tmp_name'][$key];
                $resourceType = $_FILES['photos']['type'][$key];

                $dao_itemResource->insert(array(
                    'fk_i_item_id' => $itemId,
                    's_name' => $resourceName,
                    's_content_type' => $resourceType
                ));
                $resourceId = $dao_itemResource->getConnection()->get_last_id();

                $thumbnailPath = APP_PATH . '/oc-content/uploads/' . $resourceId . '_thumbnail.png';
                ImageResizer::fromFile($tmpName)->resizeToMax(100)->saveToFile($thumbnailPath);

                $path = APP_PATH . '/oc-content/uploads/' . $resourceId.'.png';
                move_uploaded_file($tmpName, $path);

                $s_path = 'oc-content/uploads/' . $resourceId . '_thumbnail.png';
                $dao_itemResource->update(array(
                    's_path' => $s_path
                        ), array('pk_i_id' => $resourceId, 'fk_i_item_id' => $itemId));
            }
        }
        unset($dao_itemResource) ;

        if (!isset($_REQUEST['catId'])) {
            $_REQUEST['catId'] = "";
        }

        osc_runHook('item_form_post', $_REQUEST['catId'], array('id' => $itemId));

        $item = $manager->findByPrimaryKey($itemId);

        // send an e-mail to the admin with the data of the new item
        alert_new_item($preferences, $item);
        mail_validation($preferences, $item);

        osc_runHook('after_item_post');
        // This should be called via HTTP so the user will not notice the lag
        //osc_runAlertOnCategory($_REQUEST['catId']);

        if (isset($preferences['enabled_item_validation']) && $preferences['enabled_item_validation']) {
            osc_addFlashMessage(__('Great! You\'ll receive an e-mail to activate your item.'));
        } else {
            osc_addFlashMessage(__('Great! We\'ve just published your item.'));
        }



    break;

    default:
        break;
}

?>
