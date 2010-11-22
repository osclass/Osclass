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
            require_once 'recaptchalib.php';
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
                $user = $manager->findByPrimaryKey($userId);

                $content = Page::newInstance()->findByInternalName('email_user_validation');
                if (!is_null($content)) {
                    $validationLink = sprintf('%s/user.php?action=validate&id=%d&code=%s', ABS_WEB_URL, $user['pk_i_id'], $code);
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
                osc_addFlashMessage(__('Sorry, but that username is already in use.'));
                osc_redirectTo(osc_createRegisterURL());//'user.php?action=register');
            }
        } catch (DatabaseException $e) {
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
                $validationLink = sprintf('%s/user.php?action=validate&id=%d&code=%s', ABS_WEB_URL, $user['pk_i_id'], $user['s_secret']);
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
        } catch (DatabaseException $e) {
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
            osc_renderHeader(array('pageTitle' => __('Create your account')));
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
                osc_redirectTo($_SERVER['HTTP_REFERER']);
            } else {
                $_POST['s_password'] = sha1($_POST['profile_password']);
                unset($_POST['profile_password']);
                unset($_POST['profile_password2']);
            }
        }
           unset($_POST['profile_username']);
        $manager->update($_POST, array('pk_i_id' => $_SESSION['userId']));

        osc_addFlashMessage(__('Your profile has been updated correctly'));
        osc_redirectTo($_SERVER['HTTP_REFERER']);
        break;
    case 'items':
        $items = Item::newInstance()->findByUserID($_SESSION['userId']);
        osc_renderHeader(array('pageTitle' => __('Create your account')));
        osc_renderView('user-items.php');
        osc_renderFooter();
        break;
    case 'deleteItem':
    case 'item_delete':
        $id = intval(osc_paramGet('id', 0));
        $secret = osc_paramGet('secret', '');
        $userId = intval(osc_paramSession('userId', 0));
        //require_once 'osclass/model/Item.php';
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
        require_once LIB_PATH.'/osclass/items.php';

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
            unlink(APP_PATH."/".$item['s_path']);
            unlink(APP_PATH."/".str_replace("_thumbnail", "", $item['s_path']));
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
        //require_once 'osclass/security.php';
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
        } else if ($user && $user['b_enabled'] == '0') {
            osc_addFlashMessage(__('You have not validated your account yet.<br/> Should we resend you the validation email?').'<br/><a href="user.php?action=send-validation&userid='.$user['pk_i_id'].'">'.__('Yes, resend me the validation email.').'</a>');
            osc_redirectToReferer('user.php');
        } else {
            osc_addFlashMessage(__('Wrong username or password.'));
            osc_redirectToReferer('user.php');
        }

        osc_redirectTo('index.php');
        break;
    case 'logout':
        unset($_SESSION['userId']);
        setcookie('oc_userId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
        setcookie('oc_userSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['oc_userId']);
        unset($_COOKIE['oc_userSecret']);
        osc_redirectTo('index.php');
        break;
}

?>
