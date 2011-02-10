<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2010 OSCLASS
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null ;
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
        if (osc_google_maps_key()) {
            $key = osc_google_maps_key() ;
            $address = sprintf('%s, %s %s', $_POST['address'], $regionName, $cityName);
            $temp = osc_file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false&key=%s', urlencode($address), $key));
            $temp = json_decode($temp);
            if (isset($temp->Placemark) && count($temp->Placemark[0]) > 0) {
                $coord = $temp->Placemark[0]->Point->coordinates;
                $locationManager->update (
                        array(
                            'd_coord_lat' => $coord[1]
                            ,'d_coord_long' => $coord[0]
                        )
                        ,array('fk_i_item_id' => $Pid)
                );
            }
        }

        // Update category numbers
        $old_item = Item::newInstance()->findByPrimaryKey($Pid) ;
        if($old_item['fk_i_category_id'] != $PcatId) {
            CategoryStats::newInstance()->increaseNumItems($PcatId) ;
            CategoryStats::newInstance()->decreaseNumItems($old_item['fk_i_category_id']) ;
        }
        unset($old_item) ;
        

        Item::newInstance()->update (
                                array(
                                    'dt_pub_date' => DB_FUNC_NOW
                                    ,'fk_i_category_id' => $PcatId
                                    ,'f_price' => $Pprice
                                    ,'fk_c_currency_code' => $Pcurrency
                                )
                                ,array(
                                    'pk_i_id' => $Pid
                                    ,'s_secret' => $Psecret
                            )
        ) ;
        
        $data = array() ;
        foreach ($_REQUEST as $k => $v) {
            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                $data[$m[1]][$m[2]] = $v ;
            }
        }
        foreach ($data as $k => $_data) {
            Item::newInstance()->updateLocaleForce($Pid, $k, $_data['s_title'], $_data['s_description']) ;
        }

        $filePhotos = Params::getFiles('photos') ;
        if($filePhotos != '')
        {
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

                    // Create thumbnail
                    $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
                    $size = explode('x', osc_thumbnail_dimensions()) ;
                    ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                    // Create normal size
                    $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '.png' ;
                    $size = explode('x', osc_normal_dimensions()) ;
                    ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath);

                    if(osc_keep_original_image()) {
                        $path = ABS_PATH . 'oc-content/uploads/' . $resourceId.'_original.png' ;
                        move_uploaded_file($tmpName, $path) ;
                    }

                    $s_path = 'oc-content/uploads/' . $resourceId . '_thumbnail.png';
                    $dao_itemResource->update(
                                            array('s_path' => $s_path)
                                            ,array('pk_i_id' => $resourceId, 'fk_i_item_id' => $Pid)
                    ) ;
                }
            }
            unset($dao_itemResource) ;
        }

        $_POST['pk_i_id'] = $_POST['id'];
        osc_run_hook('item_edit_post');

        osc_add_flash_message(__('Great! We\'ve just update your item.'));
        break;

    case 'post_item':
        $success = true;
        $is_admin = false;
        $active = 'INACTIVE';

        if(isset($_SESSION['adminId'])) {
            $is_admin = true;
        }

        import_request_variables('p', 'P');

        if(isset($admin) && $admin==TRUE) {
            if(isset($_REQUEST['userId']) && $_REQUEST['userId']!='') {
                $userId = $_REQUEST['userId'];
            } else {
                $userId = $_SESSION['adminId'];
            }
        } else {
            $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

            if (osc_reg_user_post()) {
                if ($userId == null) {
                    osc_add_flash_message(__('You new to log-in in order to post a new item.')) ;
                    osc_redirectTo(osc_login_url()) ;
                    break ;
                }
            }

            if (osc_recaptcha_private_key()) {
                require_once LIB_PATH . 'recaptchalib.php';
                if (!empty($_POST["recaptcha_challenge_field"])) {
                    $resp = recaptcha_check_answer (
                        osc_recaptcha_private_key()
                        ,$_SERVER["REMOTE_ADDR"]
                        ,$_POST["recaptcha_challenge_field"]
                        ,$_POST["recaptcha_response_field"]
                    );
                    if (!$resp->is_valid) {
                        die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")") ;
                    }
                }
            }
        }

        // first of all, insert the item
        $code = osc_genRandomPassword();

        $has_to_validate = false ;
        if(osc_enabled_item_validation()) {
            $has_to_validate = true ;
        }

        if($is_admin || !$has_to_validate) {
            $active = 'ACTIVE';
        }
       
        $show_email = isset($_POST['showEmail']) ? $_POST['showEmail'] : '0';

        if ($userId != null) {
            if(isset($_POST['userId']) && (int) $_POST['userId'] > 0) {
                $mUser = new User();
                $data = $mUser->findByPrimaryKey((int)$_POST['userId']);
                $userId = $_POST['userId'];
            } else if(isset($admin) && $admin==TRUE) {
                if(isset($_REQUEST['contactName']) && $_REQUEST['contactName']!='' && isset($_REQUEST['contactEmail']) && $_REQUEST['contactEmail']!='') {
                    $data['s_name'] = $_REQUEST['contactName'];
                    $data['s_email'] = $_REQUEST['contactEmail'];
                } else {
                    $data = Admin::newInstance()->findByPrimaryKey($userId);
                    $userId = null;
                }
            } else {
                $data = User::newInstance()->findByPrimaryKey($userId);
            }
            $PcontactName = $data['s_name'];
            $PcontactEmail = $data['s_email'];
        }

        $Pprice = Params::getParam('price');
        if($Pprice == '') $Pprice = null;

        if(!isset($PcontactName) || !isset($PcontactEmail) || $PcontactName==null || $PcontactEmail==null || $PcontactName=='' || $PcontactEmail=='') {
            osc_add_flash_message(__('You need to input your name and email to be able to publish a new item.'));
            $success = false;
        } else {
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

            if( empty($_POST['cityArea']) ) {
                $_POST['cityArea'] = null;
            }

            if( empty($_POST['address']) ) {
                $_POST['address'] = null;
            }

            $location = array('fk_i_item_id'      => $itemId,
                              'fk_c_country_code' => $countryId,
                              's_country'         => $countryName,
                              'fk_i_region_id'    => $regionId,
                              's_region'          => $regionName,
                              'fk_i_city_id'      => $cityId,
                              's_city'            => $cityName,
                              's_city_area'       => $_POST['cityArea'],
                              's_address'         => $_POST['address']);
            $locationManager = ItemLocation::newInstance();
            $locationManager->insert($location);

            // If the Google Maps plugin is well configured, we can try to geodecode the address
            if (osc_google_maps_key()) {
                $key = osc_google_maps_key() ;
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

            if ($is_admin || !$has_to_validate) {
                CategoryStats::newInstance()->increaseNumItems($PcatId);
            }
            
            $filePhotos = Params::getFiles('photos');
            if($filePhotos != '')
            {
                $dao_itemResource = new ItemResource() ;

                foreach ($filePhotos['error'] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {
                        $resourceName = $filePhotos['name'][$key];
                        $tmpName = $filePhotos['tmp_name'][$key];
                        $resourceType = $filePhotos['type'][$key];

                        $dao_itemResource->insert(array(
                            'fk_i_item_id' => $itemId,
                            's_name' => $resourceName,
                            's_content_type' => $resourceType
                        ));
                        $resourceId = $dao_itemResource->getConnection()->get_last_id();

                        // Create thumbnail
                        $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
                        $size = explode('x', osc_thumbnail_dimensions()) ;
                        ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                        // Create normal size
                        $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '.png' ;
                        $size = explode('x', osc_normal_dimensions()) ;
                        ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                        if(osc_keep_original_image()) {
                            $path = ABS_PATH . 'oc-content/uploads/' . $resourceId.'_original.png' ;
                            move_uploaded_file($tmpName, $path) ;
                        }

                        $s_path = 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
                        $dao_itemResource->update(
                                                array('s_path' => $s_path)
                                                ,array(
                                                    'pk_i_id'      => $resourceId
                                                    ,'fk_i_item_id' => $itemId
                                                )
                                           ) ;
                    }
                }
                unset($dao_itemResource) ;
            }
            
            if (!isset($_REQUEST['catId'])) {
                $_REQUEST['catId'] = "" ;
            }

            osc_run_hook('item_form_post', $_REQUEST['catId'], array('id' => $itemId));

            $item = $manager->findByPrimaryKey($itemId);

            $mPages = new Page();
            $locale = osc_get_user_locale() ;
            
            // send an e-mail to the admin with the data of the new item
            if(!$is_admin) {
                if (osc_enabled_item_validation()) {
                    $aPage = $mPages->findByInternalName('email_item_validation') ;

                    $content = array();
                    if(isset($aPage['locale'][$locale]['s_title'])) {
                        $content = $aPage['locale'][$locale];
                    } else {
                        $content = current($aPage['locale']);
                    }

                    $item_url = osc_create_item_url($item);

                    $all = '';

                    if (isset($item['locale'])) {
                        foreach ($item['locale'] as $locale => $data) {
                            $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'");
                            $all .= '<br/>';
                            if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
                                $all .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
                            } else {
                                $all .= __('Language') . ': ' . $locale . '<br/>';
                            }
                            $all .= __('Title') . ': ' . $data['s_title'] . '<br/>';
                            $all .= __('Description') . ': ' . $data['s_description'] . '<br/>';
                            $all .= '<br/>';
                        }
                    } else {
                        $all .= __('Title') . ': ' . $item['s_title'] . '<br/>';
                        $all .= __('Description') . ': ' . $item['s_description'] . '<br/>';
                    }

                    $words   = array();
                    $words[] = array('{ITEM_DESCRIPTION_ALL_LANGUAGES}', '{ITEM_DESCRIPTION}', '{ITEM_COUNTRY}',
                                     '{ITEM_PRICE}', '{ITEM_REGION}', '{ITEM_CITY}', '{ITEM_ID}', '{USER_NAME}',
                                     '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}', '{WEB_TITLE}',
                                     '{VALIDATION_LINK}');
                    $words[] = array($all, $item['s_description'], $item['s_country'], $item['f_price'], 
                                     $item['s_region'], $item['s_city'], $item['pk_i_id'], $item['s_contact_name'],
                                     $item['s_contact_email'], osc_base_url(), $item['s_title'], $item_url,
                                     osc_page_title(), '<a href="' . osc_base_url() .
                                     'item.php?action=activate&id=' . $item['pk_i_id'] . '&secret=' .
                                     $item['s_secret'] . '" >' . osc_base_url() . 'item.php?action=activate&id=' .
                                     $item['pk_i_id'] . '&secret=' . $item['s_secret'] . '</a>' );
                    $title = osc_mailBeauty($content['s_title'], $words);
                    $body = osc_mailBeauty($content['s_text'], $words);

                    $emailParams =  array (
                                        'subject'  => $title
                                        ,'to'       => $PcontactEmail
                                        ,'to_name'  => $PcontactName
                                        ,'body'     => $body
                                        ,'alt_body' => $body
                                    );
                    osc_sendMail($emailParams) ;
                }

                if (osc_notify_new_item()) {
                    $aPage = $mPages->findByInternalName('email_admin_new_item') ;

                    $content = array();
                    if(isset($aPage['locale'][$locale]['s_title'])) {
                        $content = $aPage['locale'][$locale] ;
                    } else {
                        $content = current($aPage['locale']) ;
                    }

                    $item_url = osc_create_item_url($item) ;

                    $all = '' ;

                    if (isset($item['locale'])) {
                        foreach ($item['locale'] as $locale => $data) {
                            $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'") ;
                            $all .= '<br/>';
                            if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
                                $all .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
                            } else {
                                $all .= __('Language') . ': ' . $locale . '<br/>';
                            }
                            $all .= __('Title') . ': ' . $data['s_title'] . '<br/>';
                            $all .= __('Description') . ': ' . $data['s_description'] . '<br/>';
                            $all .= '<br/>';
                        }
                    } else {
                        $all .= __('Title') . ': ' . $item['s_title'] . '<br/>';
                        $all .= __('Description') . ': ' . $item['s_description'] . '<br/>';
                    }


                    $words   = array();
                    $words[] = array('{EDIT_LINK}', '{ITEM_DESCRIPTION_ALL_LANGUAGES}', '{ITEM_DESCRIPTION}',
                                     '{ITEM_COUNTRY}', '{ITEM_PRICE}', '{ITEM_REGION}', '{ITEM_CITY}', '{ITEM_ID}',
                                     '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}',
                                     '{WEB_TITLE}', '{VALIDATION_LINK}');
                    $words[] = array('<a href="' . osc_base_url() . 'oc-admin/items.php?action=editItem&id=' .
                                     $item['pk_i_id'] . '" >' . osc_base_url() . 'oc-admin/items.php?action=editItem&id=' .
                                     $item['pk_i_id'] . '</a>', $all, $item['s_description'], $item['s_country'],
                                     $item['f_price'], $item['s_region'], $item['s_city'], $item['pk_i_id'],
                                     $item['s_contact_name'], $item['s_contact_email'], osc_base_url(), $item['s_title'],
                                     $item_url, osc_page_title(), '<a href="' .
                                     osc_base_url() . 'item.php?action=activate&id=' . $item['pk_i_id'] .
                                     '&secret=' . $item['s_secret'] . '" >' . osc_base_url() .
                                     'item.php?action=activate&id=' . $item['pk_i_id'] . '&secret=' .
                                     $item['s_secret'] . '</a>' );
                    $title = osc_mailBeauty($content['s_title'], $words);
                    $body = osc_mailBeauty($content['s_text'], $words);

                    $emailParams = array(
                                        'subject'  => $title
                                        ,'to'       => osc_contact_email()
                                        ,'to_name'  => 'admin'
                                        ,'body'     => $body
                                        ,'alt_body' => $body
                    ) ;
                    osc_sendMail($emailParams) ;
                }

            }
            
            osc_run_hook('after_item_post') ;

            if($is_admin) {
                osc_add_flash_message(__('A new item has been added')) ;
            } else {
                if(osc_enabled_item_validation()) {
                    osc_add_flash_message(__('Great! You\'ll receive an e-mail to activate your item.')) ;
                } else {
                    osc_add_flash_message(__('Great! We\'ve just published your item.')) ;
                }
            }
        }

    break;
    default:
    break;
}

?>
