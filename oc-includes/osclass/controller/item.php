<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CWebItem extends BaseModel
    {
        private $itemManager;
        private $user;
        private $userId;

        function __construct()
        {
            parent::__construct();
            $this->itemManager = Item::newInstance();

            // here allways userId == ''
            if( osc_is_web_user_logged_in() ) {
                $this->userId = osc_logged_user_id();
                $this->user = User::newInstance()->findByPrimaryKey($this->userId);
            } else {
                $this->userId = null;
                $this->user = null;
            }
            osc_run_hook( 'init_item' );
        }

        //Business Layer...
        function doModel()
        {
            //calling the view...

            $locales = OSCLocale::newInstance()->listAllEnabled();
            $this->_exportVariableToView('locales', $locales);

            switch( $this->action ) {
                case 'item_add': // post
                    if( osc_reg_user_post() && $this->user == null ) {
                        osc_add_flash_warning_message( _m('Only registered users are allowed to post listings') );
                        Session::newInstance()->_setReferer(osc_item_post_url());
                        $this->redirectTo(osc_user_login_url());
                    }

                    $countries = Country::newInstance()->listAll();
                    $regions = array();
                    if( isset($this->user['fk_c_country_code']) && $this->user['fk_c_country_code']!='' ) {
                        $regions = Region::newInstance()->findByCountry($this->user['fk_c_country_code']);
                    } else if( count($countries) > 0 ) {
                        $regions = Region::newInstance()->findByCountry($countries[0]['pk_c_code']);
                    }
                    $cities = array();
                    if( isset($this->user['fk_i_region_id']) && $this->user['fk_i_region_id']!='' ) {
                        $cities = City::newInstance()->findByRegion($this->user['fk_i_region_id']);
                    } else if( count($regions) > 0 ) {
                        $cities = City::newInstance()->findByRegion($regions[0]['pk_i_id']);
                    }

                    $this->_exportVariableToView('countries',$countries );
                    $this->_exportVariableToView('regions', $regions);
                    $this->_exportVariableToView('cities', $cities);

                    $form = count(Session::newInstance()->_getForm());
                    $keepForm = count(Session::newInstance()->_getKeepForm());
                    if($form==0 || $form==$keepForm) {
                        Session::newInstance()->_dropKeepForm();
                    }

                    if( Session::newInstance()->_getForm('countryId') != "" ) {
                        $countryId  = Session::newInstance()->_getForm('countryId');
                        $regions    = Region::newInstance()->findByCountry($countryId);
                        $this->_exportVariableToView('regions', $regions);
                        if(Session::newInstance()->_getForm('regionId') != "" ) {
                            $regionId  = Session::newInstance()->_getForm('regionId');
                            $cities = City::newInstance()->findByRegion($regionId );
                            $this->_exportVariableToView('cities', $cities );
                        }
                    }

                    $this->_exportVariableToView('user', $this->user);

                    osc_run_hook('post_item');

                    $this->doView('item-post.php');
                break;
                case 'item_add_post':
                    // SAVE form data before CSRF CHECK
                    $mItems = new ItemActions(false);
                    $mItems->prepareData(true);
                    foreach( $mItems->data as $key => $value ) {
                        Session::newInstance()->_setForm($key,$value);
                    }

                    $meta = Params::getParam('meta');
                    if(is_array($meta)) {
                        foreach( $meta as $key => $value ) {
                            Session::newInstance()->_setForm('meta_'.$key, $value);
                            Session::newInstance()->_keepForm('meta_'.$key);
                        }
                    }

                    osc_csrf_check();

                    if( osc_reg_user_post() && $this->user == null ) {
                        osc_add_flash_warning_message( _m('Only registered users are allowed to post listings') );
                        $this->redirectTo( osc_base_url(true) );
                    }

                    if(osc_recaptcha_items_enabled() && osc_recaptcha_private_key() != '') {
                        if(!osc_check_recaptcha()) {
                            osc_add_flash_error_message( _m('The Recaptcha code is wrong') );
                            $this->redirectTo( osc_item_post_url() );
                            return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                        }
                    }

                    if(!osc_is_web_user_logged_in()) {
                        $user = User::newInstance()->findByEmail($mItems->data['contactEmail']);
                        // The user exists but it's not logged
                        if(isset($user['pk_i_id'])) {
                            foreach( $mItems->data as $key => $value ) {
                                Session::newInstance()->_keepForm($key);
                            }
                            osc_add_flash_error_message( _m('A user with that email address already exists, if it is you, please log in'));
                            $this->redirectTo(osc_user_login_url());
                        }
                    }

                    $banned = osc_is_banned($mItems->data['contactEmail']);
                    if($banned==1) {
                        osc_add_flash_error_message( _m('Your current email is not allowed'));
                        $this->redirectTo( osc_item_post_url() );
                    } else if($banned==2) {
                        osc_add_flash_error_message( _m('Your current IP is not allowed'));
                        $this->redirectTo( osc_item_post_url() );
                    }

                    // POST ITEM ( ADD ITEM )
                    $success = $mItems->add();

                    if($success!=1 && $success!=2) {
                        osc_add_flash_error_message( $success);
                        $this->redirectTo( osc_item_post_url() );
                    } else {
                        if(is_array($meta)) {
                            foreach( $meta as $key => $value ) {
                                Session::newInstance()->_dropKeepForm('meta_'.$key);
                            }
                        }
                        Session::newInstance()->_clearVariables();
                        if($success==1) {
                            osc_add_flash_ok_message( _m('Check your inbox to validate your listing') );
                        } else {
                            osc_add_flash_ok_message( _m('Your listing has been published') );
                        }

                        $itemId         = Params::getParam('itemId');

                        $category = Category::newInstance()->findByPrimaryKey(Params::getParam('catId'));
                        View::newInstance()->_exportVariableToView('category', $category);
                        $this->redirectTo(osc_search_category_url());
                    }
                break;
                case 'item_edit':   // edit item
                                    $secret = Params::getParam('secret');
                                    $id     = Params::getParam('id');
                                    $item   = $this->itemManager->listWhere("i.pk_i_id = %d AND ((i.s_secret = %s AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = %d))", (int)($id), $secret, (int)($this->userId));
                                    if (count($item) == 1) {
                                        $item     = Item::newInstance()->findByPrimaryKey($id);

                                        $form     = count(Session::newInstance()->_getForm());
                                        $keepForm = count(Session::newInstance()->_getKeepForm());
                                        if($form == 0 || $form == $keepForm) {
                                            Session::newInstance()->_dropKeepForm();
                                        }

                                        $this->_exportVariableToView('item', $item);

                                        osc_run_hook("before_item_edit", $item);
                                        $this->doView('item-edit.php');
                                    } else {
                                        // add a flash message [ITEM NO EXISTE]
                                        osc_add_flash_error_message( _m("Sorry, we don't have any listings with that ID") );
                                        if($this->user != null) {
                                            $this->redirectTo( osc_user_list_items_url() );
                                        } else {
                                            $this->redirectTo( osc_base_url() );
                                        }
                                    }
                break;
                case 'item_edit_post':
                    // SAVE form data before CSRF CHECK
                    $mItems = new ItemActions(false);
                    // prepare data for ADD ITEM
                    $mItems->prepareData(false);
                    // set all parameters into session
                    foreach( $mItems->data as $key => $value ) {
                        Session::newInstance()->_setForm($key,$value);
                    }

                    $meta = Params::getParam('meta');
                    if(is_array($meta)) {
                        foreach( $meta as $key => $value ) {
                            Session::newInstance()->_setForm('meta_'.$key, $value);
                            Session::newInstance()->_keepForm('meta_'.$key);
                        }
                    }

                    osc_csrf_check();

                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');
                    $item   = $this->itemManager->listWhere("i.pk_i_id = %d AND ((i.s_secret = %s AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = %d))", (int)($id), $secret, (int)($this->userId));

                    if (count($item) == 1) {
                        $this->_exportVariableToView('item', $item[0]);

                        if(osc_recaptcha_items_enabled() && osc_recaptcha_private_key() != '') {
                            if( !osc_check_recaptcha() ) {
                                osc_add_flash_error_message( _m('The Recaptcha code is wrong') );
                                $this->redirectTo( osc_item_edit_url($secret, $id) );
                                return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                            }
                        }

                        $success = $mItems->edit();

                        if($success==1) {
                            if(is_array($meta)) {
                                foreach( $meta as $key => $value ) {
                                    Session::newInstance()->_dropKeepForm('meta_'.$key);
                                }
                            }
                            Session::newInstance()->_clearVariables();
                            osc_add_flash_ok_message( _m("Great! We've just updated your listing") );
                            View::newInstance()->_exportVariableToView("item", Item::newInstance()->findByPrimaryKey($id));
                            $this->redirectTo( osc_item_url() );
                        } else {
                            osc_add_flash_error_message( $success);
                            $this->redirectTo( osc_item_edit_url($secret, $id) );
                        }
                    }
                break;
                case 'activate':
                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');
                    $item   = $this->itemManager->listWhere("i.pk_i_id = %d AND ((i.s_secret = %s) OR (i.fk_i_user_id = %d))", (int)($id), $secret, (int)($this->userId));

                    // item doesn't exist
                    if( count($item) == 0 ) {
                        $this->do404();
                        return;
                    }

                    View::newInstance()->_exportVariableToView('item', $item[0]);
                    if( $item[0]['b_active'] == 0 ) {
                        // ACTIVETE ITEM
                        $mItems = new ItemActions(false);
                        $success = $mItems->activate( $item[0]['pk_i_id'], $item[0]['s_secret'] );

                        if( $success ) {
                            osc_add_flash_ok_message( _m('The listing has been validated') );
                        }else{
                            osc_add_flash_error_message( _m("The listing can't be validated") );
                        }
                    } else {
                        osc_add_flash_warning_message( _m('The listing has already been validated') );
                    }

                    $this->redirectTo( osc_item_url( ) );
                break;
                case 'item_delete':
                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');
                    $item   = $this->itemManager->listWhere("i.pk_i_id = %d AND ((i.s_secret = %s) OR (i.fk_i_user_id = %d))", (int)($id), $secret, (int)($this->userId));
                    if (count($item) == 1) {
                        $mItems = new ItemActions(false);
                        $success = $mItems->delete($item[0]['s_secret'], $item[0]['pk_i_id']);
                        if($success) {
                            osc_add_flash_ok_message( _m('Your listing has been deleted') );
                        } else {
                            osc_add_flash_error_message( _m("The listing you are trying to delete couldn't be deleted") );
                        }
                        if($this->user!=null) {
                            $this->redirectTo(osc_user_list_items_url());
                        } else {
                            $this->redirectTo( osc_base_url() );
                        }
                    }else{
                        osc_add_flash_error_message( _m("The listing you are trying to delete couldn't be deleted") );
                        $this->redirectTo( osc_base_url() );
                    }
                break;
                case 'deleteResources': // Delete images via AJAX
                    $id     = Params::getParam('id');
                    $item   = Params::getParam('item');
                    $code   = Params::getParam('code');
                    $secret = Params::getParam('secret');

                    if( Session::newInstance()->_get('userId') != '' ){
                        $userId = Session::newInstance()->_get('userId');
                        $user = User::newInstance()->findByPrimaryKey($userId);
                    }else{
                        $userId = null;
                        $user = null;
                    }

                    if ( !( is_numeric($id) && is_numeric($item) && preg_match('/^([a-z0-9]+)$/i', $code) ) ) {
                        osc_add_flash_error_message(_m("The selected photo couldn't be deleted, the url doesn't exist"));
                        $this->redirectTo(osc_item_edit_url($secret, $item));
                    }

                    $aItem = Item::newInstance()->findByPrimaryKey($item);
                    if(count($aItem) == 0) {
                        osc_add_flash_error_message(_m("The listing doesn't exist"));
                        $this->redirectTo(osc_item_edit_url($secret, $item));
                    }

                    if(!osc_is_admin_user_logged_in()) {
                        if($userId != null && $userId != $aItem['fk_i_user_id']) {
                            osc_add_flash_error_message(_m("The listing doesn't belong to you"));
                            $this->redirectTo(osc_item_edit_url($secret, $item));
                        }

                        if($userId == null && $aItem['fk_i_user_id']==null && $secret != $aItem['s_secret']) {
                            osc_add_flash_error_message(_m("The listing doesn't belong to you"));
                            $this->redirectTo(osc_item_edit_url($secret, $item));
                        }
                    }

                    $result = ItemResource::newInstance()->existResource($id, $code);

                    if ($result > 0) {
                        $resource = ItemResource::newInstance()->findByPrimaryKey($id);

                        if($resource['fk_i_item_id']==$item) {
                            osc_deleteResource($id, false);
                            Log::newInstance()->insertLog('item', 'deleteResource', $id, $id, 'user', osc_logged_user_id());
                            ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $item, 's_name' => $code) );
                            osc_add_flash_ok_message(_m('The selected photo has been successfully deleted'));
                        } else {
                            osc_add_flash_error_message(_m("The selected photo does not belong to you"));
                        }
                    } else {
                        osc_add_flash_error_message(_m("The selected photo couldn't be deleted"));
                    }

                    $this->redirectTo(osc_item_edit_url($secret, $item));
                    break;
                case 'mark':
                    $id = Params::getParam('id');
                    $as = Params::getParam('as');

                    $item = Item::newInstance()->findByPrimaryKey($id);
                    View::newInstance()->_exportVariableToView('item', $item);

                    require_once(osc_lib_path() . 'osclass/user-agents.php');
                    foreach($user_agents as $ua) {
                        if(preg_match('|'.$ua.'|', Params::getServerParam('HTTP_USER_AGENT'))) {
                            // mark item if it's not a bot
                            $mItem = new ItemActions(false);
                            $mItem->mark($id, $as);
                            break;
                        }
                    }

                    osc_add_flash_ok_message( _m("Thanks! That's very helpful") );
                    $this->redirectTo( osc_item_url( ) );
                break;
                case 'send_friend':
                    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );

                    $this->_exportVariableToView('item', $item);

                    $this->doView('item-send-friend.php');
                break;
                case 'send_friend_post':
                    osc_csrf_check();
                    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                    $this->_exportVariableToView('item', $item);

                    Session::newInstance()->_setForm("yourEmail",   Params::getParam('yourEmail'));
                    Session::newInstance()->_setForm("yourName",    Params::getParam('yourName'));
                    Session::newInstance()->_setForm("friendName", Params::getParam('friendName'));
                    Session::newInstance()->_setForm("friendEmail", Params::getParam('friendEmail'));
                    Session::newInstance()->_setForm("message_body",Params::getParam('message'));

                    if ((osc_recaptcha_private_key() != '')) {
                        if(!osc_check_recaptcha()) {
                            osc_add_flash_error_message( _m('The Recaptcha code is wrong') );
                            $this->redirectTo(osc_item_send_friend_url() );
                            return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                        }
                    }

                    osc_run_hook('pre_item_send_friend_post', $item);

                    $mItem = new ItemActions(false);
                    $success = $mItem->send_friend();

                    osc_run_hook('post_item_send_friend_post', $item);

                    if($success) {
                        Session::newInstance()->_clearVariables();
                        $this->redirectTo( osc_item_url() );
                    } else {
                        $this->redirectTo(osc_item_send_friend_url() );
                    }
                break;
                case 'contact':
                    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                    if( empty($item) ){
                        osc_add_flash_error_message( _m("This listing doesn't exist") );
                        $this->redirectTo( osc_base_url(true) );
                    } else {
                        $this->_exportVariableToView('item', $item);

                        if( osc_item_is_expired () ) {
                            osc_add_flash_error_message( _m("We're sorry, but the listing has expired. You can't contact the seller") );
                            $this->redirectTo( osc_item_url() );
                        }

                        if( osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ){
                            $this->doView('item-contact.php');
                        } else {
                            osc_add_flash_warning_message( _m("You can't contact the seller, only registered users can").'. <br />'.sprintf( _m("<a href=\"%s\">Click here to sign-in</a>"), osc_user_login_url() ) );
                            $this->redirectTo( osc_item_url() );
                        }
                    }
                break;
                case 'contact_post':
                    osc_csrf_check();
                    if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ){
                        osc_add_flash_warning_message( _m("You can't contact the seller, only registered users can") );
                        $this->redirectTo( osc_base_url(true) );
                    }

                    $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                    $this->_exportVariableToView('item', $item);
                    if ((osc_recaptcha_private_key() != '')) {
                        if(!osc_check_recaptcha()) {
                            osc_add_flash_error_message( _m('The Recaptcha code is wrong') );
                            Session::newInstance()->_setForm("yourEmail",   Params::getParam('yourEmail'));
                            Session::newInstance()->_setForm("yourName",    Params::getParam('yourName'));
                            Session::newInstance()->_setForm("phoneNumber", Params::getParam('phoneNumber'));
                            Session::newInstance()->_setForm("message_body",Params::getParam('message'));
                            $this->redirectTo( osc_item_url( ) );
                            return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                        }
                    }

                    $banned = osc_is_banned(Params::getParam('yourEmail'));
                    if($banned==1) {
                        osc_add_flash_error_message( _m('Your current email is not allowed'));
                        $this->redirectTo(osc_item_url());
                    } else if($banned==2) {
                        osc_add_flash_error_message( _m('Your current IP is not allowed'));
                        $this->redirectTo(osc_item_url());
                    }

                    if( osc_isExpired($item['dt_expiration']) ) {
                        osc_add_flash_error_message( _m("We're sorry, but the listing has expired. You can't contact the seller") );
                        $this->redirectTo(osc_item_url());
                    }

                    osc_run_hook('pre_item_contact_post', $item);

                    $mItem  = new ItemActions(false);
                    $result = $mItem->contact();

                    osc_run_hook('post_item_contact_post', $item);
                    if(is_string($result)){
                        osc_add_flash_error_message( $result );
                    } else {
                        osc_add_flash_ok_message( _m("We've just sent an e-mail to the seller") );
                    }

                    $this->redirectTo( osc_item_url( ) );
                    break;
                case 'add_comment':
                    osc_csrf_check();

                    $itemId    = Params::getParam('id');
                    $item      = Item::newInstance()->findByPrimaryKey($itemId);

                    osc_run_hook('pre_item_add_comment_post', $item);

                    $mItem     = new ItemActions(false);
                    $status    = $mItem->add_comment();

                    switch ($status) {
                        case -1: $msg = _m('Sorry, we could not save your comment. Try again later');
                                 osc_add_flash_error_message($msg);
                            break;
                        case 1:  $msg = _m('Your comment is awaiting moderation');
                                 osc_add_flash_info_message($msg);
                            break;
                        case 2:  $msg = _m('Your comment has been approved');
                                 osc_add_flash_ok_message($msg);
                            break;
                        case 3:  $msg = _m('Please fill the required field (email)');
                                 osc_add_flash_warning_message($msg);
                            break;
                        case 4:  $msg = _m('Please type a comment');
                                 osc_add_flash_warning_message($msg);
                            break;
                        case 5:  $msg = _m('Your comment has been marked as spam');
                            osc_add_flash_error_message($msg);
                            break;
                        case 6:  $msg = _m('You need to be logged to comment');
                            osc_add_flash_error_message($msg);
                            break;
                        case 7:  $msg = _m('Sorry, comments are disabled');
                            osc_add_flash_error_message($msg);
                            break;
                    }

                    // View::newInstance()->_exportVariableToView('item', Item::newInstance()->findByPrimaryKey(Params::getParam('id')));
                    $this->redirectTo( osc_item_url() );
                    break;
                case 'delete_comment':
                    osc_csrf_check();

                    $commentId = Params::getParam('comment');
                    $itemId    = Params::getParam('id');
                    $item      = Item::newInstance()->findByPrimaryKey($itemId);

                    osc_run_hook('pre_item_delete_comment_post', $item, $commentId);

                    $mItem = new ItemActions(false);
                    $status = $mItem->add_comment(); // @TOFIX @FIXME $status never used + ?? need to add_comment() before deleting it??

                    if( count($item) == 0 ) {
                        osc_add_flash_error_message( _m("This listing doesn't exist") );
                        $this->redirectTo( osc_base_url(true) );
                    }

                    View::newInstance()->_exportVariableToView('item', $item);

                    if($this->userId == null) {
                        osc_add_flash_error_message(_m('You must be logged in to delete a comment') );
                        $this->redirectTo( osc_item_url() );
                    }

                    $commentManager = ItemComment::newInstance();
                    $aComment = $commentManager->findByPrimaryKey($commentId);

                    if( count($aComment) == 0 ) {
                        osc_add_flash_error_message( _m("The comment doesn't exist") );
                        $this->redirectTo( osc_item_url() );
                    }

                    if( $aComment['b_active'] != 1 ) {
                        osc_add_flash_error_message( _m('The comment is not active, you cannot delete it') );
                        $this->redirectTo( osc_item_url() );
                    }

                    if($aComment['fk_i_user_id'] != $this->userId) {
                        osc_add_flash_error_message( _m('The comment was not added by you, you cannot delete it') );
                        $this->redirectTo( osc_item_url() );
                    }

                     $commentManager->deleteByPrimaryKey($commentId);
                     osc_add_flash_ok_message( _m('The comment has been deleted' ) );
                     $this->redirectTo( osc_item_url() );
                break;
                default:
                    // if there isn't ID, show an error 404
                    if( Params::getParam('id') == '') {
                        $this->do404();
                        return;
                    }

                    if( Params::getParam('lang') != '' ) {
                        Session::newInstance()->_set('userLocale', Params::getParam('lang'));
                    }

                    $item = osc_apply_filter('pre_show_item', $this->itemManager->findByPrimaryKey( Params::getParam('id') ));
                    // if item doesn't exist show an error 410
                    if( count($item) == 0 ) {
                        $this->do410();
                        return;
                    }

                    if ($item['b_active'] != 1) {
                        if( ($this->userId == $item['fk_i_user_id']) && ($this->userId != '') || osc_is_admin_user_logged_in()) {
                            osc_add_flash_warning_message( _m("The listing hasn't been validated. Please validate it in order to make it public") );
                        } else {
                            $this->do400();
                            return;
                        }
                    } else if ($item['b_enabled'] == 0) {
                        if( osc_is_admin_user_logged_in() ) {
                            osc_add_flash_warning_message( _m("The listing hasn't been enabled. Please enable it in order to make it public") );
                        } else if(osc_is_web_user_logged_in() && osc_logged_user_id()==$item['fk_i_user_id']) {
                            osc_add_flash_warning_message( _m("The listing has been blocked or is awaiting moderation from the admin") );
                        } else {
                            $this->do400();
                            return;
                        }
                    }

                    if(!osc_is_admin_user_logged_in() && !($item['fk_i_user_id']!='' && $item['fk_i_user_id']==osc_logged_user_id())) {
                        require_once(osc_lib_path() . 'osclass/user-agents.php');
                        foreach($user_agents as $ua) {
                            if(preg_match('|'.$ua.'|', Params::getServerParam('HTTP_USER_AGENT'))) {
                                $mStats = new ItemStats();
                                $mStats->increase('i_num_views', $item['pk_i_id']);
                                break;
                            }
                        }
                    }

                    foreach($item['locale'] as $k => $v) {
                        $item['locale'][$k]['s_title'] = osc_apply_filter('item_title',$v['s_title']);
                        $item['locale'][$k]['s_description'] = nl2br(osc_apply_filter('item_description',$v['s_description']));
                    }

                    if( $item['fk_i_user_id'] != '' ) {
                        $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                        $this->_exportVariableToView('user', $user);
                    }

                    $this->_exportVariableToView('item', $item);

                    osc_run_hook('show_item', $item);

                    // redirect to the correct url just in case it has changed
                    $itemURI = str_replace(osc_base_url(), '', osc_item_url());
                    $URI = preg_replace('|^' . REL_WEB_URL . '|', '', Params::getServerParam('REQUEST_URI', false, false));
                    // do not clean QUERY_STRING if permalink is not enabled
                    if( osc_rewrite_enabled () ) {
                        $URI = str_replace('?' . Params::getServerParam('QUERY_STRING', false, false), '', $URI);
                    } else {
                        $params_keep = array('page', 'id');
                        $params      = array();
                        foreach( Params::getParamsAsArray('get') as $k => $v ) {
                            if( in_array($k, $params_keep) ) {
                                $params[] = "$k=$v";
                            }
                        }
                        $URI = 'index.php?' . implode('&', $params);
                    }

                    // redirect to the correct url
                    if($itemURI!=$URI) {
                        $this->redirectTo(osc_base_url().$itemURI, 301);
                    }

                    $this->doView('item.php');
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

    /* file end: ./item.php */
?>
