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

require_once 'oc-load.php' ;

if(!osc_users_enabled()) {
    osc_add_flash_message(__('Users are not enable')) ;
    osc_redirectTo(osc_base_url()) ;
}

$manager = User::newInstance();
$theme = osc_theme() ;

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
switch ($action) {
    case 'register':
        if(osc_user_registration_enabled()) {
            $headerConf = array(
                'pageTitle' => __('Create your account')
                ,'javaScripts' => array('/oc-includes/js/FormValidator.js')
            );
            osc_renderHeader() ;
            osc_renderView('user-register.php') ;
            osc_renderFooter() ;
            break;
        } else {
            osc_add_flash_message(__('User registration is not available.'));
            osc_redirectTo(osc_indexURL()) ;
        }
    case 'register_post':
        if(osc_user_registration_enabled()) {
            if (osc_recaptcha_private_key()) {
                require_once LIB_PATH . 'recaptchalib.php' ;
                $resp = recaptcha_check_answer (
                                osc_recaptcha_private_key()
                                ,$_SERVER["REMOTE_ADDR"]
                                ,$_POST["recaptcha_challenge_field"]
                                ,$_POST["recaptcha_response_field"]) ;
                if (!$resp->is_valid) {
                    die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")") ;
                } else {
                    unset($_POST["recaptcha_challenge_field"]) ;
                    unset($_POST["recaptcha_response_field"]) ;
                }
            }

            require_once LIB_PATH . 'osclass/users.php' ;

            switch($success) {
            
                case 0:
                    osc_redirectTo(osc_createRegisterURL()) ;
                    break ;
                    
                case 1:
                    osc_run_hook('register_user', $manager->findByPrimaryKey($userId)) ;
                    osc_add_flash_message(__('Your account has been created. An activation email has been sent to your email address.')) ;
                    osc_redirectTo(osc_createLoginURL()) ;
                    break ;
                    
                case 2:
                    osc_run_hook('register_user', $manager->findByPrimaryKey($userId)) ;
                    osc_add_flash_message(__('Your account has been created. You\'re ready to go.')) ;
                    osc_redirectTo(osc_createLoginURL()) ;
                    break;
                    
                case 3:
                    osc_add_flash_message(__('Sorry, but that email is already in use. Did you forget your password?')) ;
                    osc_redirectTo(osc_createRegisterURL()) ;
                    break;
                    
                case 4:
                    osc_add_flash_message(__('The user could not be registered, sorry.')) ;
                    osc_redirectTo(osc_createRegisterURL()) ;
                    break;
                    
                default:
                    osc_redirectTo(osc_createRegisterURL()) ;
                    break;
            }

            osc_redirectTo(osc_createRegisterURL()) ;
        } else {
            osc_add_flash_message(__('User registration is not available.')) ;
            osc_redirectTo(osc_indexURL()) ;
        }
        break;
    case 'send-validation':
        unset($_POST['action']);

        if(isset($_REQUEST['userid'])) {
            try {
                $userId = $_REQUEST['userid'];
                $user = $manager->findByPrimaryKey($userId);

                $mPages = new Page();
                $aPage = $mPages->findByInternalName('email_user_validation');

                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }
                
                if (!is_null($content)) {
                    $validationLink = sprintf('%suser.php?action=validate&id=%d&code=%s', osc_base_url(),
                                              $user['pk_i_id'], $user['s_secret']);
                    $words   = array();
                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{VALIDATION_LINK}');
                    $words[] = array($user['s_name'], $user['s_email'], osc_base_url(), $validationLink);
                    $title = osc_mailBeauty($content['s_title'], $words);
                    $body = osc_mailBeauty($content['s_text'], $words);

                    $emailParams = array('subject'  => $title
                                         ,'to'       => $user['s_email']
                                         ,'to_name'  => $user['s_name']
                                         ,'body'     => $body
                                         ,'alt_body' => $body);
                    osc_sendMail($params) ;
                }

                osc_add_flash_message(__('We resend you the validation email. If you don\'t recive it after a few minutes, please check your SPAM folder.')) ;
            } catch (Exception $e) {
                osc_add_flash_message(__('The email couldn\'t be sent, sorry.')) ;
            }
        }
        osc_redirectTo('index.php') ;
        break;
    case 'validate':
        $id = intval(osc_paramGet('id', 0)) ;
        $code = osc_paramGet('code', '') ;
        $user = $manager->findByIdSecret($id, $code) ;

        if ($user) {
            if (!$user['b_enabled']) {
                $mUser = new User();
                $mUser->update(array('b_enabled' => '1'),
                               ,array('pk_i_id'   => $id
                                     ,'s_secret'  => $code));
                $mPages = new Page();
                $aPage = $mPages->findByInternalName('email_user_registration');
                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }
                
                if (!is_null($content)) {
                    $words   = array();
                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}');
                    $words[] = array($user['s_name'], $user['s_email'], osc_page_title());
                    $title = osc_mailBeauty($content['s_title'], $words);
                    $body = osc_mailBeauty($content['s_text'], $words);

                    $emailParams = array(
                        'subject'  => $title
                        ,'to'       => $user['s_email']
                        ,'to_name'  => $user['s_name']
                        ,'body'     => $body
                        ,'alt_body' => $body
                    );
                    osc_sendMail($emailParams) ;
                }
                osc_runHok('validate_user', $user) ;
                osc_add_flash_message(__('Your account is correctly validated. Thanks.')) ;
            } else {
                osc_add_flash_message(__('Your account has been activated before.')) ;
            }
        } else {
            osc_add_flash_message(__('The link is not longer valid, sorry.')) ;
        }
        osc_redirectTo('index.php') ;
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
            if( isset($user['fk_i_region_id']) && $user['fk_i_region_id'] != '' ) {
                $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$user['fk_i_region_id']) ;
            } else if( count($regions) > 0 ) {
                $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
            }

            osc_renderHeader(array('pageTitle' => __('Create your account')));
            nav_user_menu();
            osc_renderView('user-profile.php');
            osc_renderFooter();
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());//'user.php?action=login');
        }
        break;
    case 'profile_post':
        $userId = $_SESSION['userId'];

        require_once LIB_PATH . 'osclass/users.php';
            
        if($success==0) {
            osc_add_flash_message(__('This should never happened.'));
        } else if($success==1) {
            osc_add_flash_message(__('Passwords don\'t match.'));
        } else {
            osc_add_flash_message(__('Your profile has been updated correctly'));
        }

        osc_redirectTo(osc_createProfileURL());//$_SERVER['HTTP_REFERER']);
        break;
    case 'items':
        $items = Item::newInstance()->findByUserID($_SESSION['userId']);
        osc_renderHeader(array('pageTitle' => __('Your Items')));
        nav_user_menu();
        osc_renderView('user-items.php');
        osc_renderFooter();
        break;

    case 'public':
        if(isset($_REQUEST['user']) && $_REQUEST['user']!='') {
            $user = User::newInstance()->findByPrimaryKey($_REQUEST['user']);
            $items = Item::newInstance()->findByUserIDEnabled($user['pk_i_id']);
            osc_renderHeader(array('pageTitle' => __('Items')));
            osc_renderView('user-public-dashboard.php');
            osc_renderFooter();
        } else {
            osc_redirectTo(osc_indexURL());
        }
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
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }

        break;

    case 'account':
        if(isset($_SESSION['userId'])) {
            $user = $manager->findByPrimaryKey($_SESSION['userId']);
            $items = Item::newInstance()->findByUserID($_SESSION['userId'], 3);

            osc_renderHeader(array('pageTitle' => __('Manage your account')));
            nav_user_menu();
            osc_renderView('user-account.php');
            osc_run_hook('user_account', $user);
            osc_renderFooter();
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }
        break;

    case 'contact_post':
        $user = $manager->findByPrimaryKey($_SESSION['userId']);
        $yourName = $user['s_name'];
        $yourEmail = $user['s_email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $params = array('from'      => $yourEmail
                        ,'from_name' => $yourName
                        ,'subject'   => __('Contact form') . ': ' . $subject
                        ,'to'        => osc_contact_email()
                        ,'to_name'   => __('Administrator')
                        ,'body'      => $message
                        ,'alt_body'  => $message) ;
        osc_sendMail($params) ;

        osc_add_flash_message(__('Your message has been sent and will be answered soon, thank you.')) ;
        osc_createUserAccountURL() ;
        break;
    case 'deleteItem':
    case 'item_delete':
        $id = intval(osc_paramGet('id', 0));
        $secret = osc_paramGet('secret', '');
        $userId = intval(osc_paramSession('userId', 0));
        osc_add_flash_message(__('Your item has been deleted.'));
        if($userId==0) {
            Item::newInstance()->delete(array('pk_i_id' => $id, 's_secret' => $secret));
            osc_add_flash_message(__('You could register and access every time to your items.'));
            osc_redirectTo(osc_createRegisterURL());//'user.php?action=register');
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
            osc_add_flash_message(__('You could register and access every time to your items.'));
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
        $user = $manager->findByCredentials($_POST['s_email'], $_POST['password']);
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
            osc_run_hook('user_login');
        } else if ($user && $user['b_enabled'] == '0') {
            osc_add_flash_message(__('You have not validated your account yet.<br/> Should we resend you the validation email?').'<br/><a href="user.php?action=send-validation&userid='.$user['pk_i_id'].'">'.__('Yes, resend me the validation email.').'</a>');
            osc_redirectToReferer(osc_createLoginURL());
        } else {
            osc_add_flash_message(__('Wrong email or password.'));
            osc_redirectToReferer(osc_createLoginURL());
        }

        osc_redirectTo(osc_createUserAccountURL());
        break;
        
    case 'logout':
        osc_run_hook('logout_user', $_COOKIE['oc_userId']);
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
            osc_add_flash_message(__('Unsubscribed correctly.'));
        } else {
            osc_add_flash_message(__('Ops! There was a problem trying to unsubscribe you. Please contact the administrator.'));
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
                        array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR'])
                        ,array('pk_i_id' => $user['pk_i_id'])
                    );

                    $password_link = sprintf('%suser.php?action=forgot_change&id=%d&code=%s', 
                                             osc_base_url(), $user['pk_i_id'], $code) ;
                    
                    $mPages = new Page();
                    $aPage = $mPages->findByInternalName('email_user_forgot_password') ;

                    $content = array() ;
                    if(isset($aPage['locale'][$locale]['s_title'])) {
                        $content = $aPage['locale'][$locale] ;
                    } else {
                        $content = current($aPage['locale']) ;
                    }

                    if (!is_null($content)) {
                        $words   = array();
                        $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_TITLE}', '{IP_ADDRESS}',
                                         '{PASSWORD_LINK}', '{DATE_TIME}');
                        $words[] = array($user['s_name'], $user['s_email'], osc_page_title(),
                                         $_SERVER['REMOTE_ADDR'], $password_link, $date2);
                        $title = osc_mailBeauty($content['s_title'], $words);
                        $body = osc_mailBeauty($content['s_text'], $words);

                        $emailParams = array('subject'  => $title,
                                             'to'       => $user['s_email'],
                                             'to_name'  => $user['s_name'],
                                             'body'     => $body,
                                             'alt_body' => $body);
                        osc_sendMail($emailParams);
                    }
                }
            }
            osc_add_flash_message(__('Check your email inbox in a few moments. A message with instructions on how to recover your password should arrive.'));
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
                    osc_add_flash_message(__('Sorry, the link is not valid.'));
                    osc_redirectTo('index.php');
                }
            } else {
                osc_add_flash_message(__('Sorry, the link is not valid.'));
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
                        osc_add_flash_message(__('The password has been changed.'));
                        osc_redirectTo(osc_createLoginURL());
                    } else {
                        osc_add_flash_message(__('Sorry, the link is not valid.'));
                        osc_redirectTo('index.php');
                    }
                } else {
                    osc_add_flash_message(__('Error: Passwords don\'t match.'));
                    osc_redirectTo('user.php?action=forgot_change&id='.$_REQUEST['id'].'&code='.$_REQUEST['code']);
                }
            } else {
                osc_add_flash_message(__('Sorry, the link is not valid.'));
                osc_redirectTo('index.php');
            }
        break;

    case 'options':
        if(isset($_SESSION['userId'])) {
            $user_prefs = $manager->preferences($_SESSION['userId']);

            osc_renderHeader(array('pageTitle' => __('Retrieve your password')));
            nav_user_menu();
            osc_run_hook('user_options', (isset($_REQUEST['option']))?$_REQUEST['option']:'');
            osc_renderFooter();
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }
        break;


    case 'options_post':
        if(isset($_SESSION['userId'])) {

            osc_run_hook('user_options_post', (isset($_REQUEST['option']))?$_REQUEST['option']:'');
            osc_redirectTo(osc_createUserAccountURL());
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        };
        break;

    case 'delete_user':
        if(isset($_SESSION['userId'])) {
            try {
                $manager->deleteUser($_SESSION['userId']);
                osc_add_flash_message(__('Success. The user has been deleted.'));
                unset($_SESSION['userId']);
                setcookie('oc_userId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                setcookie('oc_userSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                unset($_COOKIE['oc_userId']);
                unset($_COOKIE['oc_userSecret']);
            } catch (Exception $e) {
                osc_add_flash_message(__('Error. The user can not be deleted. Please try again in a few moments, if the problem persists contact the administrator.'));
                osc_redirectTo(osc_createUserAccountURL());
            }
            
            osc_redirectTo(osc_createRegisterURL());
        } else {
            osc_add_flash_message(__('You need to login first.'));
        };
        osc_redirectTo(osc_createLoginURL());
        break;

    case 'change_email':
        if(isset($_SESSION['userId'])) {
            $user = $manager->findByPrimaryKey($_SESSION['userId']);

            osc_renderHeader(array('pageTitle' => __('Retrieve your password')));
            nav_user_menu();
            ?>
                <div id="home_header"><div><?php _e('Change your E-mail'); ?></div></div>
                <form action="<?php echo osc_create_url('user') ; ?>" method="post">
                <input type="hidden" name="action" value="change_email_post" />
                <div>
	                <div id="change_email_form" >
		                <p>
		                <label for="name"><?php _e('Your current e-mail'); ?></label><br />
                        <?php echo $user['s_email']; ?>
		                </p>

		                <p>
		                <label for="phoneLand"><?php _e('New E-mail'); ?></label><br />
                        <?php UserForm::email_text($user); ?>
		                </p>
		                
		                <p>
                        <?php _e('You are going to change your email address. You will received a confirmation on your new email address.');?>
		                </p>
		                
		                <p>
			                <button type="submit"><?php _e('Change e-mail'); ?></button>
		                </p>
                        <div style="clear:both;"></div>
	                </div>
                </div>
                </form>    
            
            
            <?php
            osc_renderFooter();
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }
        break;
        
    case 'change_password':
        if(isset($_SESSION['userId'])) {
            $user_prefs = $manager->preferences($_SESSION['userId']);

            osc_renderHeader(array('pageTitle' => __('Retrieve your password')));
            nav_user_menu();
            ?>
            <div id="home_header"><div><?php _e('Change your password'); ?></div></div>
            <form action="<?php echo osc_create_url('user') ; ?>" method="post">
            <input type="hidden" name="action" value="change_password_post" />
            <div>
	            <div id="change_password_form" >
		            <p>
		            <label for="name"><?php _e('Old password'); ?></label><br />
                    <?php UserForm::old_password_text(); ?><br />
		            </p>

		            <p>
		            <label for="password"><?php _e('Password'); ?></label><br />
                    <?php UserForm::password_text(); ?><br />
		            </p>
		
		            <p>
		            <label for="password2"><?php _e('Retype the password'); ?></label><br />
                    <?php UserForm::check_password_text(); ?>
		            </p>
		            
		            <p>
			            <button type="submit"><?php _e('Change password'); ?></button>
		            </p>
                    <div style="clear:both;"></div>
	            </div>
            </div>
            </form>    
        <?php 
            osc_renderFooter();
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }
        break;

    case 'change_email_post':
        if(isset($_SESSION['userId'])) {
            if(osc_user_validation_enabled()) {
                    $pref = $manager->updatePreference($_SESSION['userId'], 'new_email', $_REQUEST['s_email']) ;
                    $user = $manager->findByPrimaryKey($_SESSION['userId']) ;
                    $code = osc_genRandomPassword(50) ;
                    $date = date('Y-m-d H:i:s') ;
                    $manager->update(
                        array('s_pass_code' => $code, 's_pass_date' => $date, 's_pass_ip' => $_SERVER['REMOTE_ADDR'])
                        ,array('pk_i_id' => $_SESSION['userId'])
                    );

                $content = Page::newInstance()->findByInternalName('email_new_email') ;
                if (!is_null($content)) {
                    $validationLink = sprintf('%suser.php?action=change_email_confirm&id=%d&oe=%s&ne=%s&code=%s', osc_base_url(), $_SESSION['userId'], $user['s_email'], $_REQUEST['s_email'], $code);
                    $words = array();
                    $words[] = array('{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{WEB_TITLE}', '{VALIDATION_LINK}') ;
                    $words[] = array($user['s_name'], $_REQUEST['s_email'], osc_base_url(), osc_page_title(), $validationLink) ;
                    $title = osc_mailBeauty($content['s_title'], $words) ;
                    $body = osc_mailBeauty($content['s_text'], $words) ;
				
                    $params = array(
                        'subject' => $title
                        ,'to' => $_REQUEST['s_email']
                        ,'to_name' => $user['s_name']
                        ,'body' => $body
                        ,'alt_body' => $body
                    );
                    osc_sendMail($params);
                }
                osc_add_flash_message(__('We have send you an email, you need to confirm it.')) ;
                osc_redirectTo(osc_createUserAccountURL()) ;
            } else {
                $manager->update(
                    array('s_email' => $_REQUEST['s_email'], 's_username' => $_REQUEST['s_email'])
                    ,array('pk_i_id' => $_SESSION['userId'])
                );
                osc_add_flash_message(__('We change your email. Please login with your new e-mail.')) ;
                unset($_SESSION['userId']);
                setcookie('oc_userId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']) ;
                setcookie('oc_userSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']) ;
                unset($_COOKIE['oc_userId']) ;
                unset($_COOKIE['oc_userSecret']) ;
                osc_redirectTo(osc_createLoginURL()) ;
            }
            $pref = $manager->updatePreference($_SESSION['userId'], 'new_email', $_REQUEST['s_email']) ;
            osc_add_flash_message(__('We have send you an email, you need to confirm it.')) ;
            osc_redirectTo(osc_createUserAccountURL()) ;
        } else {
            osc_add_flash_message(__('You need to login first.')) ;
            osc_redirectTo(osc_createLoginURL()) ;
        }   
        break;

    case 'change_email_confirm':
            if(isset($_REQUEST['id']) && isset($_REQUEST['code']) && $_REQUEST['id']!='' && $_REQUEST['code']!='' && isset($_REQUEST['ne']) && isset($_REQUEST['oe']) && $_REQUEST['ne']!='' && $_REQUEST['oe']!='') {
                $user = $manager->findByPrimaryKey($_REQUEST['id']);
                $prefs = $manager->findPreferenceByUserAndName($_REQUEST['id'], 'new_email');
                if($user!=null || $user['s_email']==$_REQUEST['oe'] || $user['s_pass_code']==$_REQUEST['code'] || $prefs['new_email']==$_REQUEST['ne']) {
                    $manager->update(
                        array('s_email' => $prefs['new_email'], 's_username' => $prefs['new_email']),
                        array('pk_i_id' => $user['pk_i_id'])
                    );
                    $manager->deletePreference($user['pk_i_id'], 'new_email');
                    unset($_SESSION['userId']);
                    setcookie('oc_userId', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                    setcookie('oc_userSecret', null, time() - 3600, '/', $_SERVER['SERVER_NAME']);
                    unset($_COOKIE['oc_userId']);
                    unset($_COOKIE['oc_userSecret']);
                    osc_add_flash_message(__('E-mail change correctly, please login with your new email.'));
                    osc_redirectTo(osc_createLoginURL());
                } else {
                    osc_add_flash_message(__('Sorry, the link is not valid.'));
                    osc_redirectTo('index.php');
                }
            } else {
                osc_add_flash_message(__('Sorry, the link is not valid.'));
                osc_redirectTo('index.php');
            }
        break;

    case 'change_password_post':
        if(isset($_SESSION['userId'])) {
            $user = $manager->findByPrimaryKey($_SESSION['userId']);
            if($user['s_password']!=sha1($_REQUEST['old_password'])) {
                osc_add_flash_message(__('Old password doesn\'t match.'));
                osc_redirectTo(osc_create_url(array('file' => 'user', 'action' => 'change_password'))) ;
            } else if($_REQUEST['profile_password']=='') {
                osc_add_flash_message(__('Passwords can not be empty.'));
                osc_redirectTo(osc_create_url(array('file' => 'user', 'action' => 'change_password')));
            } else if($_REQUEST['profile_password']!=$_REQUEST['profile_password2']) {
                osc_add_flash_message(__('Passwords don\'t match.'));
                osc_redirectTo(osc_create_url(array('file' => 'user', 'action' => 'change_password')));
            }
            $manager->update(
                        array('s_password' => sha1($_REQUEST['profile_password'])),
                        array('pk_i_id' => $_SESSION['userId'])
                );
            osc_add_flash_message(__('Password has been changed.'));
            osc_redirectTo(osc_createUserAccountURL());
        } else {
            osc_add_flash_message(__('You need to login first.'));
            osc_redirectTo(osc_createLoginURL());
        }   

        break;

    default : 
        osc_redirectTo(osc_base_url());
        break;
    
}

?>
