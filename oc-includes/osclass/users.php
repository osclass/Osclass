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

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$manager = new User();

switch ($action) {
    case 'register_post':
    case 'create_post':
        $success = 0;
        $input['s_name'] = $_POST['s_name'];
        if(!isset($_POST['s_email']) || $_POST['s_email']=='') {
        
        } else {
            $input['s_email'] = $_POST['s_email'];
            $input['s_password'] = sha1($_POST['s_password']);
            $input['dt_reg_date'] = DB_FUNC_NOW;
            
            // This line will be not needed when username is not required anymore
            $input['s_username'] = $input['s_email'];
            
            // Location code from oc-includes/osclass/items.php
            $country = Country::newInstance()->findByCode($_REQUEST['countryId']);
            if(count($country) > 0) {
                $countryId = $country['pk_c_code'];
                $countryName = $country['s_name'];
            } else {
                $countryId = null;
                $countryName = null;
            }

            if( isset($_REQUEST['regionId']) ) {
                if( intval($_REQUEST['regionId']) ) {
                    $region = Region::newInstance()->findByPrimaryKey($_REQUEST['regionId']);
                    if( count($region) > 0 ) {
                        $regionId = $region['pk_i_id'];
                        $regionName = $region['s_name'];
                    }
                }
            } else {
                $regionId = null;
                $regionName = $_REQUEST['region'];
            }

            if( isset($_REQUEST['cityId']) ) {
                if( intval($_REQUEST['cityId']) ) {
                    $city = City::newInstance()->findByPrimaryKey($_REQUEST['cityId']);
                    if( count($city) > 0 ) {
                        $cityId = $city['pk_i_id'];
                        $cityName = $city['s_name'];
                    }
                }
            } else {
                $cityId = null;
                $cityName = $_REQUEST['city'];
            }

            if( empty($_REQUEST['cityArea']) ) {
                $_POST['cityArea'] = null;
            }

            if( empty($_REQUEST['address']) ) {
                $_POST['address'] = null;
            }

            $input['fk_c_country_code'] = $countryId;
            $input['s_country'] = $countryName;
            $input['fk_i_region_id'] = $regionId;
            $input['s_region'] = $regionName;
            $input['fk_i_city_id'] = $cityId;
            $input['s_city'] = $cityName;
            $input['s_city_area'] = $_POST['cityArea'];
            $input['s_address'] = $_POST['address'];

            $code = osc_genRandomPassword();
            $input['s_secret'] = $code;
            try {
                $email_taken = $manager->findByEmail($input['s_email']);
                if($email_taken==null) {
                    $manager->insert($input);
                    $userId = $manager->getConnection()->get_last_id();

                    $data = array();
                    foreach ($_REQUEST as $k => $v) {
                        if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                            $data[$m[1]][$m[2]] = $v;
                        }
                    }
                    foreach ($data as $k => $_data) {
                        $manager->updateDescription($userId, $k, $_data['s_info']);
                    }
                    osc_runHook('user_register_completed');
                    if(isset($preferences['enabled_user_validation']) && $preferences['enabled_user_validation']) {
                        $user = $manager->findByPrimaryKey($userId);

                        $mPages = new Page();
                        $locale = osc_getActualLocale();

                        $aPage = $mPages->findByInternalName('email_user_validation');

                        $content = array();
                        if(isset($aPage['locale'][$locale]['s_title'])) {
                            $content = $aPage['locale'][$locale];
                        } else {
                            $content = current($aPage['locale']);
                        }

                        if (!is_null($content)) {
                            $validationLink = sprintf('%suser.php?action=validate&id=%d&code=%s', ABS_WEB_URL,
                                                      $user['pk_i_id'], $code);
                            $words   = array();
                            $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{VALIDATION_LINK}');
                            $words[] = array($user['s_name'], $user['s_email'], ABS_WEB_URL, $validationLink);
                            $title = osc_mailBeauty($content['s_title'], $words);
                            $body = osc_mailBeauty($content['s_text'], $words);
				
                            $emailParams = array('subject'  => $title,
                                                 'to'       => $_POST['s_email'],
                                                 'to_name'  => $_POST['s_name'],
                                                 'body'     => $body,
                                                 'alt_body' => $body);
                            osc_sendMail($emailParams);
                        }

                        $success = 1;
                    } else {
                        User::newInstance()->update(
                            array('b_enabled' => '1'),
                            array('pk_i_id' => $userId)
                        );
                        $success = 2;
                    }
                } else {
                    $success = 3;
                }
            } catch (Exception $e) {
                $success = 4;
            }
        }
        break;

    case 'edit_post':
    case 'profile_post':
        $sucess = 0;
        $s_password = '';
        if($_POST['profile_password']!=$_POST['profile_password2']) {
            $success = 1;
        } else {
            if($_POST['profile_password']!='') {
                $s_password = sha1($_POST['profile_password']);
                unset($_POST['profile_password']);
                unset($_POST['profile_password2']);
            }
        }

        // Location code from oc-includes/osclass/items.php
        $country = Country::newInstance()->findByCode($_REQUEST['countryId']);
        if(count($country) > 0) {
            $countryId = $country['pk_c_code'];
            $countryName = $country['s_name'];
        } else {
            $countryId = null;
            $countryName = null;
        }

        if( isset($_REQUEST['regionId']) ) {
            if( intval($_REQUEST['regionId']) ) {
                $region = Region::newInstance()->findByPrimaryKey($_REQUEST['regionId']);
                if( count($region) > 0 ) {
                    $regionId = $region['pk_i_id'];
                    $regionName = $region['s_name'];
                }
            }
        } else {
            $regionId = null;
            $regionName = $_REQUEST['region'];
        }

        if( isset($_REQUEST['cityId']) ) {
            if( intval($_REQUEST['cityId']) ) {
                $city = City::newInstance()->findByPrimaryKey($_REQUEST['cityId']);
                if( count($city) > 0 ) {
                    $cityId = $city['pk_i_id'];
                    $cityName = $city['s_name'];
                }
            }
        } else {
            $cityId = null;
            $cityName = $_REQUEST['city'];
        }

        if( empty($_REQUEST['cityArea']) )
            $_POST['cityArea'] = null;

        if( empty($_REQUEST['address']) )
            $_POST['address'] = null;

        $data = array(
            's_name' => $_POST['s_name'],
            's_website' => $_POST['s_website'],
            's_phone_land' => $_POST['s_phone_land'],
            's_phone_mobile' => $_POST['s_phone_mobile'],
            'fk_c_country_code' => $countryId,
            's_country' => $countryName,
            'fk_i_region_id' => $regionId,
            's_region' => $regionName,
            'fk_i_city_id' => $cityId,
            's_city' => $cityName,
            's_city_area' => $_POST['cityArea'],
            's_address' => $_POST['address']
        );
        $manager->update($data, array('pk_i_id' => $userId));

        $data = array();
        foreach ($_REQUEST as $k => $v) {
            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                $data[$m[1]][$m[2]] = $v;
            }
        }
        foreach ($data as $k => $_data) {
            $manager->updateDescription($userId, $k, $_data['s_info']);
        }

        if($s_password!='') {
            $manager->update(array('s_password' => $s_password), array('pk_i_id' => $userId));
        }
        $success = 2;
        break;

    default:
        break;
}

?>
