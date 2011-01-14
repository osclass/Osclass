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

require_once 'oc-load.php';

$preferences = Preference::newInstance()->toArray();
$manager = User::newInstance();
$theme = $preferences['theme'];

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
switch ($action) {
    case 'register':
        $headerConf = array(
            'pageTitle' => __('Create your account'),
            'javaScripts' => array('/oc-includes/js/FormValidator.js')
        );
        osc_renderHeader();
        osc_renderView('user-register.php');
        osc_renderFooter();
        break;
    case 'register_post':
        unset($_POST['action']);

        if (isset($preferences['recaptchaPrivKey'])) {
            require_once LIB_PATH . 'recaptchalib.php';
            $resp = recaptcha_check_answer($preferences['recaptchaPrivKey'],
                            $_SERVER["REMOTE_ADDR"],
                            $_POST["recaptcha_challenge_field"],
                            $_POST["recaptcha_response_field"]);
            if (!$resp->is_valid) {
                die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")");
            } else {
                unset($_POST["recaptcha_challenge_field"]);
                unset($_POST["recaptcha_response_field"]);
            }
        }

        $validations = array(
            's_username' => array(
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => array('regexp' => '') // User Registration complete RegExp support with _, - and .
            )
        );

        if( !preg_match('/^[a-zA-Z0-9_\.\-]+$/i',$_POST['s_username']) ) {
            osc_addFlashMessage(__('Sorry, but the username can only contain alphanumeric characters.'));
            osc_redirectTo(osc_createRegisterURL());//'user.php?action=register');
        }

        $input['s_name'] = $_POST['s_name'];
        $input['s_username'] = $_POST['s_username'];
        $input['s_email'] = $_POST['s_email'];
        $input['s_password'] = sha1($_POST['s_password']);
        $input['dt_reg_date'] = DB_FUNC_NOW;
        
        $code = osc_genRandomPassword();
        $input['s_secret'] = $code;
        try {
            $username_taken = $manager->findByUsername($input['s_username']);
            if($username_taken==null) {
                $manager->insert($input);
                $userId = $manager->getConnection()->get_last_id();
                osc_runHook('user_register_completed');
                if(isset($preferences['enabled_user_validation']) && $preferences['enabled_user_validation']) {
                    $user = $manager->findByPrimaryKey($userId);

                    $content = Page::newInstance()->findByInternalName('email_user_validation');
                    if (!is_null($content)) {
                        $validationLink = sprintf('%suser.php?action=validate&id=%d&code=%s', ABS_WEB_URL, $user['pk_i_id'], $code);
                        $words = array();
                        $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{VALIDATION_LINK}');
                        $words[] = array($user['s_name'], $user['s_email'], ABS_WEB_URL, $validationLink);
                        $title = osc_mailBeauty($content['s_title'], $words);
                        $body = osc_mailBeauty($content['s_text'], $words);
				
                        $params = array(
                            'subject' => $title,
                            'to' => $_POST['s_email'],
                            'to_name' => $_POST['s_name'],
                            'body' => $body,
                            'alt_body' => $body
                        );
                        osc_sendMail($params);
                    }

                    osc_addFlashMessage(__('Your account has been created. An activation email has been sent to your email address.'));
                } else {
                    User::newInstance()->update(
                        array('b_enabled' => '1'),
                        array('pk_i_id' => $userId)
                    );
                    osc_addFlashMessage(__('Your account has been created. You\'re ready to go.'));
                }
            } else {
                osc_addFlashMessage(__('Sorry, but that username is already in use.'));
                osc_redirectTo(osc_createRegisterURL());//'user.php?action=register');
            }
        } catch (Exception $e) {
            osc_addFlashMessage(__('The user could not be registered, sorry.'));
        }
        osc_redirectTo('index.php');
        break;
    case 'send-validation':
        unset($_POST['action']);

		if(isset($_REQUEST['userid'])) {
        try {
            $userId = $_REQUEST['userid'];
            $user = $manager->findByPrimaryKey($userId);

            $content = Page::newInstance()->findByInternalName('email_user_validation');
            if (!is_null($content)) {
                $validationLink = sprintf('%suser.php?action=validate&id=%d&code=%s', ABS_WEB_URL, $user['pk_i_id'], $user['s_secret']);
				$words = array();
                $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{VALIDATION_LINK}');
                $words[] = array($user['s_name'], $user['s_email'], ABS_WEB_URL, $validationLink);
                $title = osc_mailBeauty($content['s_title'], $words);
                $body = osc_mailBeauty($content['s_text'], $words);

                $params = array(
                    'subject' => $title,
                    'to' => $user['s_email'],
                    'to_name' => $user['s_name'],
                    'body' => $body,
                    'alt_body' => $body
                );
                osc_sendMail($params);
            }

            osc_addFlashMessage(__('We resend you the validation email. If you don\'t recive it after a few minutes, please check your SPAM folder.'));
        } catch (Exception $e) {
            osc_addFlashMessage(__('The email couldn\'t be sent, sorry.'));
        }
		}
        osc_redirectTo('index.php');
        break;
    case 'validate':
        $id = intval(osc_paramGet('id', 0));
        $code = osc_paramGet('code', '');
        $user = $manager->findByIdSecret($id, $code);

        if ($user) {
            if (!$user['b_enabled']) {
                User::newInstance()->update(
                        array('b_enabled' => '1'),
                        array('pk_i_id' => $id, 's_secret' => $code)
                );

                $content = Page::newInstance()->findByInternalName('email_user_registration');
                if (!is_null($content)) {
					$words = array();
                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}');
                    $words[] = array($user['s_name'], $user['s_email'], $preferences['pageTitle']);
                    $title = osc_mailBeauty($content['s_title'], $words);
                    $body = osc_mailBeauty($content['s_text'], $words);

                    $params = array(
                        'subject' => $title,
                        'to' => $user['s_email'],
                        'to_name' => $user['s_name'],
                        'body' => $body,
                        'alt_body' => $body
                    );
                    osc_sendMail($params);
                }
                osc_addFlashMessage(__('Your account is correctly validated. Thanks.'));
            } else {
                osc_addFlashMessage(__('Your account has been activated before.'));
            }
        } else {
            osc_addFlashMessage(__('The link is not longer valid, sorry.'));
        }
        osc_redirectTo('index.php');
        break;
    case 'profile':
        if(isset($_SESSION['userId'])) {
            $user = $manager->findByPrimaryKey($_SESSION['userId']);
            $countries = Country::newInstance()->listAll();
            $regions = array();
            if( isset($user['fk_c_country_code']) && $user['fk_c_country_code']!='' ) {
                $regions = Region::newInstance()->getByCountry($user['fk_c_country_code']);
            } else if( count($countries) > 0 ) {
                $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
            }
            $cities = array();
            if( isset($user['fk_i_region_id']) && $user['fk_i_region_id']!='' ) {
                $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$user['fk_i_region_id']) ;
            } else if( count($regions) > 0 ) {
                $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
            }

            osc_renderHeader(array('pageTitle' => __('Create your account')));
            nav_user_menu();
            osc_renderView('user-profile.php');
            osc_renderFooter();
        } else {
            osc_addFlashMessage(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());//'user.php?action=login');
        }
        break;
    case 'profile_post':

        unset($_POST['action']);
        if (empty($_POST['profile_password']))  {
            unset($_POST['profile_password']);
        } else {
            if($_POST['profile_password']!=$_POST['profile_password2']) {
                osc_addFlashMessage(__('Passwords don\'t match.'));
                osc_redirectTo(osc_createProfileURL());//$_SERVER['HTTP_REFERER']);
            } else {
                $_POST['s_password'] = sha1($_POST['profile_password']);
                unset($_POST['profile_password']);
                unset($_POST['profile_password2']);
            }
        }

           //unset($_POST['profile_username']);
        //$manager->update($_POST, array('pk_i_id' => $_SESSION['userId']));


            // insert location (copied from osclass/items.php)
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
                's_username' => $_POST['s_username'],
                's_password' => $_POST['s_password'],
                's_email' => $_POST['s_email'],
                's_website' => $_POST['s_website'],
                's_info' => $_POST['s_info'],
                's_phone_land' => $_POST[''],
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
        $manager->update($data, array('pk_i_id' => $_SESSION['userId']));
        osc_addFlashMessage(__('Your profile has been updated correctly'));
        osc_redirectTo(osc_createProfileURL());//$_SERVER['HTTP_REFERER']);
        break;
    case 'items':
        $items = Item::newInstance()->findByUserID($_SESSION['userId']);
        osc_renderHeader(array('pageTitle' => __('Create your account')));
        nav_user_menu();
        osc_renderView('user-items.php');
        osc_renderFooter();
        break;

    case 'alerts':

        if(isset($_SESSION['userId'])) {

            $alerts = Alerts::newInstance()->getAlertsFromUser($_SESSION['userId']);
            foreach($alerts as $k => $a) {
                $search = osc_unserialize(base64_decode($a['s_search']));
                $search->limit(0,3);
                $alerts[$k]['items'] = $search->search();
            }
            osc_renderHeader(array('pageTitle' => __('Manage your alerts')));
            nav_user_menu();
            osc_renderView('user-alerts.php');
            osc_renderFooter();

        } else {
            osc_addFlashMessage(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }

        break;

    case 'account':

        if(isset($_SESSION['userId'])) {
            $user = $manager->findByPrimaryKey($_SESSION['userId']);
            osc_renderHeader(array('pageTitle' => __('Manage your account')));
            nav_user_menu();
            osc_renderView('user-account.php');
            osc_renderFooter();
        } else {
            osc_addFlashMessage(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }
        break;


    case 'deleteItem':
    case 'item_delete':
        $id = intval(osc_paramGet('id', 0));
        $secret = osc_paramGet('secret', '');
        $userId = intval(osc_paramSession('userId', 0));
        osc_addFlashMessage(__('Your item has been deleted.'));
        if($userId==0) {
            Item::newInstance()->delete(array('pk_i_id' => $id, 's_secret' => $secret));
            osc_addFlashMessage(__('You could register and access every time to your items.'));
            die;osc_redirectTo(osc_createRegisterURL());//'user.php?action=register');
        } else {
            Item::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_user_id' => $userId, 's_secret' => $secret));
            osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
        }
        osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
        break;
    case 'item_edit':
    case 'editItem':
        $id = osc_paramGet('id', -1);
        $secret = osc_paramGet('secret', '');
        $userId = intval(osc_paramSession('userId', 0));
        $locales = Locale::newInstance()->listAllEnabled();


        
        $currencies = Currency::newInstance()->listAll();

        $item = Item::newInstance()->findByPrimaryKey($id);

        $categories = Category::newInstance()->toTree();
        $countries = Country::newInstance()->listAll();
        $regions = array();
        if( count($countries) > 0 ) {
            $regions = Region::newInstance()->getByCountry($item['fk_c_country_code']);
        }
        $cities = array();
        if( count($regions) > 0 ) {
            $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$item['fk_i_region_id']) ;
        }

        if ($secret==$item['s_secret'] && ($userId==0 || $userId==$item['fk_i_user_id'])) {

            $resources = Item::newInstance()->findResourcesByID($id);
            
            $headerConf = array('pageTitle' => $item['s_title']);
            osc_renderHeader($headerConf);
            osc_renderView('item-edit.php');
            osc_renderFooter();
        } else {
            osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
        }
        break;


    case 'item_edit_post':
    case 'editItemPost':

        // The magic code
        require_once LIB_PATH . 'osclass/items.php';

        $userId = intval(osc_paramSession('userId', 0));
        if($userId==0) {
            osc_addFlashMessage(__('You could register and access every time to your items.'));
            osc_redirectTo(osc_createRegisterURL());//'user.php?action=register');
        } else {
            osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
        }
        break;

    case 'deleteResource':
        $id = osc_paramGet('id', -1);
        $name = osc_paramGet('name', '');   
        $fkid = osc_paramGet('fkid', -1);

        $item = ItemResource::newInstance()->findByConditions(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name));
        if(isset($item['s_path'])) {
            unlink(ABS_PATH.$item['s_path']);
            unlink(ABS_PATH.str_replace("_thumbnail", "", $item['s_path']));
        }
        ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name));
        osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
        break;

    case 'login':
        $headerConf = array(
            'pageTitle' => __('Access to your account'),
            'javaScripts' => array('/oc-includes/js/FormValidator.js')
        );
        osc_renderHeader($headerConf);
        osc_renderView('user-login.php');
        osc_renderFooter();
        break;
    case 'login_post':
        define('COOKIE_LIFE', 86400);
        $user = $manager->findByCredentials($_POST['userName'], $_POST['password']);
        if ($user && $user['b_enabled'] == '1') {
            if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 1) {
                $life = time() + COOKIE_LIFE;
                $userSecret = osc_genRandomPassword();
                User::newInstance()->update(
                        array('s_secret' => $userSecret),
                        array('pk_i_id' => $user['pk_i_id'])
                );
                setcookie('oc_userId', $user['pk_i_id'], $life, '/', $_SERVER['SERVER_NAME']);
                setcookie('oc_userSecret', $userSecret, $life, '/', $_SERVER['SERVER_NAME']);
            } else {
                setcookie('oc_userId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                setcookie('oc_userSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
            }

            $_SESSION['userId'] = $user['pk_i_id'];
            osc_runHook('user_login');
        } else if ($user && $user['b_enabled'] == '0') {
            osc_addFlashMessage(__('You have not validated your account yet.<br/> Should we resend you the validation email?').'<br/><a href="user.php?action=send-validation&userid='.$user['pk_i_id'].'">'.__('Yes, resend me the validation email.').'</a>');
            osc_redirectToReferer(osc_createLoginURL());
        } else {
            osc_addFlashMessage(__('Wrong username or password.'));
            osc_redirectToReferer(osc_createLoginURL());
        }

        osc_redirectTo(osc_createUserAccountURL());
        break;
    case 'logout':
        unset($_SESSION['userId']);
        setcookie('oc_userId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
        setcookie('oc_userSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['oc_userId']);
        unset($_COOKIE['oc_userSecret']);
        osc_redirectTo(osc_createLoginURL());
        break;

    case 'unsub_alert':
        if(isset($_REQUEST['email']) && isset($_REQUEST['alert']) && $_REQUEST['email']!='' && $_REQUEST['alert']!='') {
            Alerts::newInstance()->delete(array('s_email' => $_REQUEST['email'], 's_search' => $_REQUEST['alert']));
            osc_addFlashMessage(__('Unsubscribed correctly.'));
        } else {
            osc_addFlashMessage(__('Ops! There was a problem trying to unsubscribe you. Please contact the administrator.'));
        }
        osc_redirectTo('index.php');
        break;

    case 'forgot':
            osc_renderHeader(array('pageTitle' => __('Retrieve your password')));
            osc_renderView('user-forgot.php');
            osc_renderFooter();
        break;

    case 'forgot_post':
            if(isset($_REQUEST['s_email']) && $_REQUEST['s_email']!='') {
                $user = $manager->findByEmail($_REQUEST['s_email']);
                if($user!=null) {
                    $code = osc_genRandomPassword(50);
                    $date = date('Y-m-d H:i:s');
                    $date2 = date('Y-m-d H:i:').'00';
                    $manager->update(
                        array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR']),
                        array('pk_i_id' => $user['pk_i_id'])
                    );

                    $password_link = sprintf('%suser.php?action=forgot_change&id=%d&code=%s', ABS_WEB_URL, $user['pk_i_id'], $code);

                    $content = Page::newInstance()->findByInternalName('email_user_forgot_password');
                    if (!is_null($content)) {
					    $words = array();
                        $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}', '{IP_ADDRESS}', '{PASSWORD_LINK}', '{DATE_TIME}');
                        $words[] = array($user['s_name'], $user['s_email'], $preferences['pageTitle'], $_SERVER['REMOTE_ADDR'], $password_link, $date2);
                        $title = osc_mailBeauty($content['s_title'], $words);
                        $body = osc_mailBeauty($content['s_text'], $words);

                        $params = array(
                            'subject' => $title,
                            'to' => $user['s_email'],
                            'to_name' => $user['s_name'],
                            'body' => $body,
                            'alt_body' => $body
                        );
                        osc_sendMail($params);
                    }


                }
            }
            osc_addFlashMessage(__('Check your email inbox in a few moments. A message with instructions on how to recover your password should arrive.'));
            osc_redirectToReferer('index.php');
        break;

    case 'forgot_change':
            if(isset($_REQUEST['id']) && isset($_REQUEST['code']) && $_REQUEST['id']!='' && $_REQUEST['code']!='') {
                $user = $manager->findByIdPasswordSecret($_REQUEST['id'], $_REQUEST['code']);
                if($user!=null) {
                    osc_renderHeader(array('pageTitle' => __('Retrieve your password')));
                    osc_renderView('user-forgot-change.php');
                    osc_renderFooter();
                } else {
                    osc_addFlashMessage(__('Sorry, the link is not valid.'));
                    osc_redirectTo('index.php');
                }
            } else {
                osc_addFlashMessage(__('Sorry, the link is not valid.'));
                osc_redirectTo('index.php');
            }
        break;

    case 'forgot_change_post':
            if(isset($_REQUEST['id']) && isset($_REQUEST['code']) && $_REQUEST['id']!='' && $_REQUEST['code']!='') {
                if(isset($_REQUEST['profile_password']) && isset($_REQUEST['profile_password2']) && $_REQUEST['profile_password']!='' && $_REQUEST['profile_password']==$_REQUEST['profile_password2']) { 
                    $user = $manager->findByIdPasswordSecret($_REQUEST['id'], $_REQUEST['code']);
                    if($user!=null) {
                        $manager->update(
                            array('s_pass_code' => osc_genRandomPassword(50), 's_pass_date' => date('Y-m-d H:i:s', 0), 's_pass_ip' => $_SERVER['REMOTE_ADDR'], 's_password' => $_REQUEST['profile_password'] ),
                            array('pk_i_id' => $user['pk_i_id'])
                        );
                        osc_addFlashMessage(__('The password has been changed.'));
                        osc_redirectTo(osc_createLoginURL());
                    } else {
                        osc_addFlashMessage(__('Sorry, the link is not valid.'));
                        osc_redirectTo('index.php');
                    }
                } else {
                    osc_addFlashMessage(__('Error: Passwords don\'t match.'));
                    osc_redirectTo('user.php?action=forgot_change&id='.$_REQUEST['id'].'&code='.$_REQUEST['code']);
                }
            } else {
                osc_addFlashMessage(__('Sorry, the link is not valid.'));
                osc_redirectTo('index.php');
            }
        break;

    case 'options':

        if(isset($_SESSION['userId'])) {
            $user_prefs = $manager->preferences($_SESSION['userId']);

            osc_renderHeader(array('pageTitle' => __('Retrieve your password')));
            nav_user_menu();
            osc_runHook('user_options', (isset($_REQUEST['option']))?$_REQUEST['option']:'');
            osc_renderFooter();
        } else {
            osc_addFlashMessage(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());//'user.php?action=login');
        }
        break;


    case 'options_post':

        if(isset($_SESSION['userId'])) {

            $manager->updatePreferences(array( 'show_phone' => $_POST['show_phone']), $_SESSION['userId']);

            osc_addFlashMessage(__('Options saved.'));
            osc_runHook('user_options_post');
            osc_redirectTo(osc_createUserOptionsURL());
        } else {
            osc_addFlashMessage(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());//'user.php?action=login');
        };
        break;


    default : 
        osc_redirectTo('index.php');
        break;
    
}

?>
