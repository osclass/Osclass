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

class CWebItem extends WebSecBaseModel
{
    private $itemManager;

    function __construct() {
        parent::__construct() ;
        $this->itemManager = Item::newInstance();
        $this->add_css('style.css');
        $this->add_css('jquery-ui.css');
        $this->add_global_js('jquery.js');
    }

    //Business Layer...
    function doModel() {
        //calling the view...
        //$this->_exportVariableToView('categories', $categories) ;
        //$this->_exportVariableToView('locales', $locales) ;
        //$this->_exportVariableToView('latestItems', $latestItems) ;
        switch( $this->action ) {
            case 'post': // add
                if(!osc_users_enabled()) {
                    osc_add_flash_message(__('Users are not enable')) ;
                    $this->redirectTo(osc_base_url(true));
                }

                if( Session::newInstance()->_get('userId') != '' ){
                    $userId = Session::newInstance()->_get('userId');
                }else{
                    $userId = null;
                }

                $user = ($userId!=null)?User::newInstance()->findByPrimaryKey($userId):null;
                $categories = Category::newInstance()->toTree();
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

                $currencies = Currency::newInstance()->listAll();

                $this->_exportVariableToView('categories', $categories) ;
                $this->_exportVariableToView('currencies', $currencies) ;
                $this->_exportVariableToView('countries',$countries ) ;
                $this->_exportVariableToView('regions', $regions) ;
                $this->_exportVariableToView('cities', $cities) ;

                osc_run_hook('post_item');
                $this->doView('item-post.php');
                break;
                
            case 'post_item': // add_post
                if(!osc_users_enabled()) {
                    osc_add_flash_message(__('Users are not enable'));
                    osc_redirectTo(osc_base_url());
                }

                $mItems = new ItemActions();
                $success = $mItems->add(Item);

                // variables
                $active     = 'INACTIVE';

                $is_admin   = FALSE;

                $showEmail  = 0;
                if(Params::getParam('showEmail') != ''){    // comprobación de integer
                    $showEmail = (int) Params::getParam('showEmail');
                }

                $catId      = '';
                if( Params::getParam('catId') != '' ) {
                    $catId = Params::getParam('catId');
                }

                $userId     = '';
                if( Session::newInstance()->_get('userId') != '' ) {
                    $userId = Session::newInstance()->_get('userId');
                }

                $currency   = '';
                if( Params::getParam('currency') != '' ) {
                    $currency = Params::getParam('currency');
                }

                $price      = '';
                if( Params::getParam('price') != '' ) {
                    $price = Params::getParam('price');
                }

                $countryId = '';
                if( Params::getParam('countryId') != '' ) {
                    $countryId = Params::getParam('countryId');
                }

                $mUser = new User();
                $data = $mUser->findByPrimaryKey( (int)$userId );
                $contactName   = $data['s_name'];
                $contactEmail  = $data['s_email'];

                // falta testealo
                if (osc_recaptcha_private_key()) {
                    require_once LIB_PATH . 'recaptchalib.php';
                    if ( Params::getFiles("recaptcha_challenge_field") != '') {
                        $resp = recaptcha_check_answer (
                            osc_recaptcha_private_key()
                            ,$_SERVER["REMOTE_ADDR"]
                            ,Params::getParam("recaptcha_challenge_field")
                            ,Params::getParam("recaptcha_response_field")
                        );
                        if (!$resp->is_valid) {
                            die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")") ;
                        }
                    }
                }
                
                // crear el array con los datos a pasar
                $aItem = array(
                    'showEmail'     => $showEmail,
                    'is_admin'      => FALSE,
                    'active'        => $active,
                    'userId'        => $userId,
                    'price'         => $price,
                    'catId'         => $catId,
                    'currency'      => $currency,
                    'contactName'   => $contactName,
                    'contactEmail'  => $contactEmail,
                    'countryId'     => $countryId,
                    'region'        => Params::getParam('region'),
                    'regionId'      => Params::getParam('regionId'),
                    'cityId'        => Params::getParam('cityId'),
                    'cityArea'      => Params::getParam('cityArea'),
                    'address'       => Params::getParam('address'),
                    'photos'        => Params::getFiles('photos'),
                    'title'         => Params::getParam('title'),
                    'description'   => Params::getParam('description')
                );

//                echo "<pre>";print_r($aItem);echo "</pre>";

                

                if($success) {
                    // ESTO VA EN EL item.php QUE EXTIENDE SIN SEGURIDAD ?
//                    if( Session::newInstance()->_get('userId') == '' ){ // if(!isset($_SESSION['userId'])) {
//
//                        $mPages = new Page() ;
//                        $aPage = $mPages->findByInternalName('email_new_item_non_register_user') ;
//                        $locale = osc_get_user_locale() ;
//
//                        $content = array();
//                        if(isset($aPage['locale'][$locale]['s_title'])) {
//                            $content = $aPage['locale'][$locale];
//                        } else {
//                            $content = current($aPage['locale']);
//                        }
//
//                        $item_url = osc_create_item_url($item) ;
//                        $urlEdit =  array(
//                                        'file'   => 'user'
//                                        ,'action' => 'item_edit'
//                                        ,'id'     => $itemId
//                                        ,'userId' => null
//                                        ,'secret' => $item['s_secret']
//                                    ) ;
//                        $edit_link = osc_create_url($urlEdit);
//
//                        $urlDelete =    array(
//                                            'file'   => 'user'
//                                            ,'action' => 'item_delete'
//                                            ,'id'     => $itemId
//                                            ,'userId' => null
//                                            ,'secret' => $item['s_secret']
//                                        ) ;
//                        $delete_link = osc_create_url($urlDelete) ;
//
//                        $words   = array();
//                        $words[] = array('{ITEM_ID}', '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_TITLE}',
//                                         '{ITEM_URL}', '{WEB_TITLE}', '{EDIT_LINK}', '{DELETE_LINK}');
//                        $words[] = array($itemId, $PcontactName, $PcontactEmail, osc_base_url(), $item['s_title'],
//                                         $item_url, osc_page_title(), $edit_link, $delete_link) ;
//                        $title = osc_mailBeauty($content['s_title'], $words) ;
//                        $body = osc_mailBeauty($content['s_text'], $words) ;
//
//                        $emailParams =  array(
//                                            'subject' => $title
//                                            ,'to' => $PcontactEmail
//                                            ,'to_name' => $PcontactName
//                                            ,'body' => $body
//                                            ,'alt_body' => $body
//                                        );
//                        osc_sendMail($params);
//                    }

                    osc_run_hook('posted_item', $item);
                    $category = Category::newInstance()->findByPrimaryKey($catId);
                    $this->redirectTo(osc_search_category_url($category));
                } else {
                    $this->redirectTo( osc_item_post_url() );
                }
            break;
            case 'mark':
                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') ) ;
                $column = null;
                switch (Params::getParam('as')) {
                    case 'spam':
                        $column = 'i_num_spam';
                        break;
                    case 'badcat':
                        $column = 'i_num_bad_classified';
                        break;
                    case 'offensive':
                        $column = 'i_num_offensive';
                        break;
                    case 'repeated':
                        $column = 'i_num_repeated';
                        break;
                    case 'expired':
                        $column = 'i_num_expired';
                        break;
                }

                $dao_itemStats = new ItemStats() ;
                $dao_itemStats->increase($column, Params::getParam('id')) ;
                unset($dao_itemStats) ;
                setcookie("mark_" . $item['pk_i_id'], "1", time() + 86400);
                osc_add_flash_message(__('Thanks! That helps us.'));
                $this->redirectTo( osc_item_url($item) );
            break;
            case 'send_friend':
                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
//                global $osc_request;
//                $osc_request['section'] = __('Send to a friend');
//                $osc_request['category'] = $item['fk_i_category_id'];
//                $osc_request['item'] = $item;
//                $osc_request['location'] = 'item_send_friend';
                // pasar los parametros a la vista...
                $this->_exportVariableToView('item', $item) ;
                
                $this->doView('item-send-friend.php');
            break;
            case 'send_friend_post':
                $mPages = new Page();
                $aPage = $mPages->findByInternalName('email_send_friend');
                $locale = osc_get_user_locale();

                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                $item_url = osc_item_url($item);

                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }

                $words   = array() ;
                $words[] = array(
                                    '{FRIEND_NAME}'
                                    ,'{USER_NAME}'
                                    ,'{USER_EMAIL}'
                                    ,'{FRIEND_EMAIL}'
                                    ,'{WEB_URL}'
                                    ,'{ITEM_NAME}'
                                    ,'{COMMENT}'
                                    ,'{ITEM_URL}'
                                    ,'{WEB_TITLE}'
                            ) ;
                $words[] = array(
                                    Params::getParam('friendName')
                                    ,Params::getParam('yourName')
                                    ,Params::getParam('yourEmail')
                                    ,Params::getParam('friendEmail')
                                    ,osc_base_url()
                                    ,Params::getParam('s_title')
                                    ,Params::getParam('message')
                                    ,$item_url
                                    ,osc_page_title()
                            ) ;
                $title = osc_mailBeauty($content['s_title'], $words) ;
                $body  = osc_mailBeauty($content['s_text'], $words) ;


                $from = osc_contact_email();
                if( Params::getParam('yourEmail') != '' ){
                    $from = Params::getParam('yourEmail');
                }
                
                $from_name = Params::getParam('yourName');

                if (osc_notify_contact_friends()) {
                    $add_bbc = osc_contact_email() ;
                }

                $params = array(
                            'add_bcc'   => $add_bbc
                            ,'from'      => $from
                            ,'from_name' => $from_name
                            ,'subject'   => $title
                            ,'to'        => Params::getParam('friendEmail')
                            ,'to_name'   => Params::getParam('friendName')
                            ,'body'      => $body
                            ,'alt_body'  => $body
                          ) ;

                if(osc_sendMail($params)) {
                    osc_add_flash_message(__('We just send your message to ') . Params::getParam('friendName') . ".") ;
                } else {
                    osc_add_flash_message(__('We are very sorry but we could not deliver your message to your friend. Try again later.')) ;
                }
                $this->redirectTo($item_url);
            break;
            case 'contact':
                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') ) ;
                $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']) ;
                if($category['i_expiration_days'] > 0) {
                    $item_date = strtotime($item['dt_pub_date'])+($category['i_expiration_days']*(24*3600)) ;
                    $date = time() ;
                    if($item_date < $date) {
                        // The item is expired, we can not contact the seller
                        osc_add_flash_message(__('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
                        $this->redirectTo(osc_create_item_url($item));
                    }
                }

                $this->_exportVariableToView('item', $item) ;

                $this->doView('item-contact.php');
                break;

            case 'contact_post':
                $path = null;
                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') ) ;

                $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);

                if($category['i_expiration_days'] > 0) {
                    $item_date = strtotime($item['dt_pub_date'])+($category['i_expiration_days']*(24*3600)) ;
                    $date = time();
                    if($item_date < $date) {
                        // The item is expired, we can not contact the seller
                        osc_add_flash_message(__('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
                        $this->redirectTo(osc_create_item_url($item));
                    }
                }

                $mPages = new Page();
                $aPage = $mPages->findByInternalName('email_item_inquiry');
                $locale = osc_get_user_locale() ;

                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }

                $words   = array();
                $words[] = array('{CONTACT_NAME}', '{USER_NAME}', '{USER_EMAIL}', '{USER_PHONE}',
                                 '{WEB_URL}', '{ITEM_NAME}','{ITEM_URL}', '{COMMENT}');
                
                $words[] = array($item['s_contact_name'], Params::getParam('yourName'), Params::getParam('yourEmail'),
                                 Params::getParam('phoneNumber'), osc_base_url(), $item['s_title'], osc_item_url($item), Params::getParam('message'));
                $title = osc_mailBeauty($content['s_title'], $words);
                $body = osc_mailBeauty($content['s_text'], $words);

                $from = osc_contact_email() ;
                $from_name = osc_page_title() ;
                if (osc_notify_contact_item()) {
                    $add_bbc = osc_contact_email() ;
                }

                $emailParams = array (
                                    'add_bcc'   => $add_bbc
                                    ,'from'      => $from
                                    ,'from_name' => $from_name
                                    ,'subject'   => $title
                                    ,'to'        => $item['s_contact_email']
                                    ,'to_name'   => $item['s_contact_name']
                                    ,'body'      => $body
                                    ,'alt_body'  => $body
                                    ,'reply_to'  => Params::getParam('yourEmail')
                                ) ;

                
                if(osc_item_attachment()) {
                    $attachment = Params::getFiles('attachment');
                    $resourceName = $attachment['name'] ;
                    $tmpName = $attachment['tmp_name'] ;
                    $resourceType = $attachment['type'] ;

                    $path = osc_base_path() . 'oc-content/uploads/' . time() . '_' . $resourceName ;

                    if(!is_writable(osc_base_path() . 'oc-content/uploads/')) {
                        osc_add_flash_message(__('There has been some erro sending the message')) ;
                        $this->redirectTo( osc_base_url() );
                    }

                    if(!move_uploaded_file($tmpName, $path)){
                        unset($path) ;
                    }
                }

                if(isset($path)) {
                    $emailParams['attachment'] = $path ;
                }

                osc_sendMail($emailParams);
                   
                @unlink($path) ;
                osc_add_flash_message(__('We\'ve just sent an e-mail to the seller.')) ;
                $this->redirectTo( osc_create_item_url($item) );
                
                break;
            case 'add_comment':
                $authorName     = Params::getParam('authorName') ;
                $authorEmail    = Params::getParam('authorEmail') ;
                $body           = Params::getParam('body') ;
                $title          = Params::getParam('title') ;
                $itemId         = Params::getParam('id') ;

                $item = $this->itemManager->findByPrimaryKey($itemId) ;

                $itemURL = osc_item_url($item) ;

                if (osc_moderate_comments()) {
                    $status = 'INACTIVE' ;
                } else {
                    $status = 'ACTIVE' ;
                }
                if (osc_akismet_key()) {
                    require_once LIB_PATH . 'Akismet.class.php' ;
                    $akismet = new Akismet(osc_base_url(), osc_akismet_key()) ;
                    $akismet->setCommentAuthor($authorName) ;
                    $akismet->setCommentAuthorEmail($authorEmail) ;
                    $akismet->setCommentContent($body) ;
                    $akismet->setPermalink($itemURL) ;

                    $status = $akismet->isCommentSpam() ? 'SPAM' : $status ;
                }

               
                $mComments = new Comment() ;
                $aComment  = array(
                                'dt_pub_date'    => DB_FUNC_NOW
                                ,'fk_i_item_id'   => $itemId
                                ,'s_author_name'  => $authorName
                                ,'s_author_email' => $authorEmail
                                ,'s_title'        => $title
                                ,'s_body'         => $body
                                ,'e_status'       => $status
                            );

                if( $mComments->insert($aComment) ){

                    $notify = osc_notify_new_comment() ;
                    $admin_email = osc_contact_email() ;
                    $prefLocale = osc_language;

                    //Notify admin
                    if ($notify) {
                        $mPages = new Page() ;
                        $aPage = $mPages->findByInternalName('email_new_comment_admin') ;
                        $locale = osc_get_user_locale() ;

                        $content = array();
                        if(isset($aPage['locale'][$locale]['s_title'])) {
                            $content = $aPage['locale'][$locale];
                        } else {
                            $content = current($aPage['locale']);
                        }

                        $words   = array();
                        $words[] = array('{COMMENT_AUTHOR}', '{COMMENT_EMAIL}', '{COMMENT_TITLE}',
                                         '{COMMENT_TEXT}', '{ITEM_NAME}', '{ITEM_ID}', '{ITEM_URL}');
                        $words[] = array($authorName, $authorEmail, $title, $body, $item['s_title'], $itemId, $itemURL);
                        $title_email = osc_mailBeauty($content['s_title'], $words);
                        $body_email = osc_mailBeauty($content['s_text'], $words);

                        $from = osc_contact_email() ;
                        $from_name = osc_page_title ;
                        if (osc_notify_contact_item()) {
                            $add_bbc = osc_contact_email() ;
                        }

                        $emailParams = array(
                                        'from'      => $admin_email
                                        ,'from_name' => __('Admin mail system')
                                        ,'subject'   => $title_email
                                        ,'to'        => $admin_email
                                        ,'to_name'   => __('Admin mail system')
                                        ,'body'      => $body_email
                                        ,'alt_body'  => $body_email
                                        );
                        osc_sendMail($emailParams) ;
                    }
                    osc_run_hook('add_comment', $item);
                }else{
                    osc_add_flash_message(__('We are very sorry but could not save your comment. Try again later.')) ;
                }
                $this->redirectTo($itemURL);
                break;

            case('dashboard'):      //dashboard...

            break;
            case 'activate':
                if( Params::getParam('secret') != '' && Params::getParam('id') ){

                    $secret = Params::getParam('secret');
                    $id     = Params::getParam('id');
                    $item   = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s'", $secret, $id);
                    if (count($item) == 1) {
                        $item_validated = $manager->listWhere("i.s_secret = '%s' AND i.e_status = '%s' AND i.pk_i_id = '%s'", $secret, 'INACTIVE', $id);
                        if (!is_array($item_validated))
                            return false;

                        if (count($item_validated) == 1) {
                            $manager->update(
                                    array('e_status' => 'ACTIVE'),
                                    array('s_secret' => $secret)
                            );
                            osc_run_hook('activate_item', $manager->findByPrimaryKey($id));
                            CategoryStats::newInstance()->increaseNumItems($item[0]['fk_i_category_id']);
                            osc_add_flash_message('Item validated');
                            $this->redirectTo( osc_item_url($item[0]) );
                        } else {
                            osc_add_flash_message('The item was validated before');
                            $this->redirectTo( osc_item_url($item[0]) );
                        }
                    }
                }
            break;
            default:
                if( Params::getParam('id') == ''){
                    $this->redirectTo(osc_base_url());
                }

                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                
                $this->_exportVariableToView('item', $item) ;
                $this->_exportVariableToView('section',$item['s_title']) ;  // ??
                $this->_exportVariableToView('category', $item['fk_i_category_id']) ;   // ??
                $this->_exportVariableToView('location', 'item' ) ; //  ??

                if ($item['e_status'] != 'ACTIVE') {
                    if( Session::newInstance()->_get('userId') != '' && Session::newInstance()->_get('userId') == $item['fk_i_user_id'] ) {
                        osc_add_flash_message('This item is NOT validated. You should validate it in order to show this item
                            to the rest of the users. You could do that in your profile menu.');
                    } else {
                        $this->redirectTo( osc_base_url(true) );
                    }
                }
                $mStats = new ItemStats();
                $mStats->increase('i_num_views', $item['pk_i_id']);

                $resources = $this->itemManager->findResourcesByID( Params::getParam('id') );
                $comments = ItemComment::newInstance()->findByItemID( Params::getParam('id') );

                foreach($item['locale'] as $k => $v) {
                    $item['locale'][$k]['s_title'] = osc_apply_filter('item_title',$v['s_title']);
                    $item['locale'][$k]['s_description'] = osc_apply_filter('item_description',$v['s_description']);
                }

                $author = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                $actual_locale = osc_get_user_locale() ;
                if(isset($author['locale'][$actual_locale]['s_info'])) {
                    $author['s_info'] = $author['locale'][$actual_locale]['s_info'];
                } else {
                    $author['s_info'] = '';
                }

                $this->_exportVariableToView('author', $author) ;
                $this->_exportVariableToView('item', $item) ;
                $this->_exportVariableToView('comments', $comments) ;
                $this->_exportVariableToView('resources', $resources) ;
                osc_run_hook('show_item', $item);
                $this->doView('item.php');
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}



/*
require_once 'oc-load.php';

$manager = Item::newInstance() ;
$theme = osc_theme() ;
$locales = Locale::newInstance()->listAllEnabled() ;

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

switch ($action) {
    case 'mark':
        $item = $manager->findByPrimaryKey($_GET['id']) ;

        $column = null;
        switch ($_GET['as']) {
            case 'spam':
                $column = 'i_num_spam';
                break;
            case 'badcat':
                $column = 'i_num_bad_classified';
                break;
            case 'offensive':
                $column = 'i_num_offensive';
                break;
            case 'repeated':
                $column = 'i_num_repeated';
                break;
            case 'expired':
                $column = 'i_num_expired';
                break;
        }
        
        $dao_itemStats = new ItemStats() ;
        $dao_itemStats->increase($column, $_GET['id']) ;
        unset($dao_itemStats) ;
        setcookie("mark_" . $item['pk_i_id'], "1", time() + 86400);
        osc_add_flash_message(__('Thanks! That helps us.'));
        osc_redirectTo(osc_create_item_url($item));
        break;

    case 'send_friend':
        $item = $manager->findByPrimaryKey($_GET['id']);

        global $osc_request;
        $osc_request['section'] = __('Send to a friend');
        $osc_request['category'] = $item['fk_i_category_id'];
        $osc_request['item'] = $item;
        $osc_request['location'] = 'item_send_friend';

        osc_renderHeader();
        osc_renderView('item-send-friend.php');
        osc_renderFooter();
        break;

    case 'send_friend_post':
        $mPages = new Page();
        $aPage = $mPages->findByInternalName('email_send_friend');
        $locale = osc_get_user_locale();

        $item = $manager->findByPrimaryKey($_POST['id']);
        $item_url = osc_create_item_url($item);

        $content = array();
        if(isset($aPage['locale'][$locale]['s_title'])) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }
        
        $words   = array() ;
        $words[] = array(
                            '{FRIEND_NAME}'
                            ,'{USER_NAME}'
                            ,'{USER_EMAIL}'
                            ,'{FRIEND_EMAIL}'
                            ,'{WEB_URL}'
                            ,'{ITEM_NAME}'
                            ,'{COMMENT}'
                            ,'{ITEM_URL}'
                            ,'{WEB_TITLE}'
                    ) ;
        $words[] = array(
                            $_POST['friendName']
                            ,$_POST['yourName']
                            ,$_POST['yourEmail']
                            ,$_POST['friendEmail']
                            ,osc_base_url()
                            ,$item['s_title']
                            ,$_POST['message']
                            ,$item_url
                            ,osc_page_title()
                    ) ;
        $title = osc_mailBeauty($content['s_title'], $words) ;
        $body  = osc_mailBeauty($content['s_text'], $words) ;

        $from = ( isset($_POST['yourEmail']) ) ? $_POST['yourEmail'] : osc_contact_email() ;
        $from_name = $_POST['yourName'];

        if (osc_notify_contact_friends()) {
            $add_bbc = osc_contact_email() ;
        }

        $params = array(
                    'add_bcc'   => $add_bbc
                    ,'from'      => $from
                    ,'from_name' => $from_name
                    ,'subject'   => $title
                    ,'to'        => $_POST['friendEmail']
                    ,'to_name'   => $_POST['friendName']
                    ,'body'      => $body
                    ,'alt_body'  => $body
                  ) ;

        if(osc_sendMail($params)) {
            osc_add_flash_message(__('We just send your message to ') . $_POST['friendName'] . ".") ;
        } else {
            osc_add_flash_message(__('We are very sorry but we could not deliver your message to your friend. Try again later.')) ;
        }

        osc_redirectTo($item_url) ;
        break;

    case 'contact':
        $item = $manager->findByPrimaryKey($_REQUEST['id']) ;
        $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']) ;
        if($category['i_expiration_days'] > 0) {
            $item_date = strtotime($item['dt_pub_date'])+($category['i_expiration_days']*(24*3600)) ;
            $date = time() ;
            if($item_date < $date) {
                // The item is expired, we can not contact the seller
                osc_add_flash_message(__('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
                osc_redirectTo(osc_create_item_url($item)) ;
            }
        }

        osc_renderHeader();
        osc_renderView('item-contact.php');
        osc_renderFooter();
        break;

    case 'contact_post':
        $path = '';
        $item = $manager->findByPrimaryKey($_REQUEST['id']) ;

        $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);

        if($category['i_expiration_days'] > 0) {
            $item_date = strtotime($item['dt_pub_date'])+($category['i_expiration_days']*(24*3600)) ;
            $date = time();
            if($item_date < $date) {
                // The item is expired, we can not contact the seller
                osc_add_flash_message(__('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
                osc_redirectTo(osc_create_item_url($item)) ;
            }
        }

        $mPages = new Page();
        $aPage = $mPages->findByInternalName('email_item_inquiry');
        $locale = osc_get_user_locale() ;

        $content = array();
        if(isset($aPage['locale'][$locale]['s_title'])) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }

        $words   = array();
        $words[] = array('{CONTACT_NAME}', '{USER_NAME}', '{USER_EMAIL}', '{USER_PHONE}',
                         '{WEB_URL}', '{ITEM_NAME}','{ITEM_URL}', '{COMMENT}');
        $words[] = array($item['s_contact_name'], $_POST['yourName'], $_POST['yourEmail'],
                         $_POST['phoneNumber'], osc_base_url(), $item['s_title'], osc_create_item_url($item), $_POST['message']);
        $title = osc_mailBeauty($content['s_title'], $words);
        $body = osc_mailBeauty($content['s_text'], $words);

        $from = osc_contact_email() ;
        $from_name = osc_page_title() ;
        if (osc_notify_contact_item()) {
            $add_bbc = osc_contact_email() ;
        }

        $emailParams = array (
                            'add_bcc'   => $add_bbc
                            ,'from'      => $from
                            ,'from_name' => $from_name
                            ,'subject'   => $title
                            ,'to'        => $item['s_contact_email']
                            ,'to_name'   => $item['s_contact_name']
                            ,'body'      => $body
                            ,'alt_body'  => $body
                            ,'reply_to'  => $_POST['yourEmail']
                        ) ;

        if(osc_item_attachment()) {
            $resourceName = $_FILES['attachment']['name'] ;
            $tmpName = $_FILES['attachment']['tmp_name'] ;
            $resourceType = $_FILES['attachment']['type'] ;
            $path = ABS_PATH . 'oc-content/uploads/' . time() . '_' . $resourceName ;

            if(!is_writable(ABS_PATH . 'oc-content/uploads/')) {
                osc_add_flash_message(__('There has been some erro sending the message')) ;
                osc_redirectToReferer(osc_base_url()) ;
            }

            if(!move_uploaded_file($tmpName, $path)){
                unset($path) ;
            }
        }

        if(isset($path)) {
            $emailParams['attachment'] = $path ;
        }

        osc_sendMail($emailParams) ;
        @unlink($path) ;
        osc_add_flash_message(__('We\'ve just sent an e-mail to the seller.')) ;
        osc_redirectTo(osc_create_item_url($item)) ;
        break;

    case 'add_comment':
        $authorName = $_POST['authorName'] ;
        $authorEmail = $_POST['authorEmail'] ;
        $body = $_POST['body'] ;
        $title = $_POST['title'] ;
        $itemId = $_POST['id'] ;

        $item = $manager->findByPrimaryKey($itemId) ;

        $itemURL = osc_create_item_url($item) ;

        if (osc_moderate_comments()) {
            $status = 'INACTIVE' ;
        } else {
            $status = 'ACTIVE' ;
        }
        if (osc_akismet_key()) {
            require_once LIB_PATH . 'Akismet.class.php' ;
            $akismet = new Akismet(osc_base_url(), osc_akismet_key()) ;
            $akismet->setCommentAuthor($authorName) ;
            $akismet->setCommentAuthorEmail($authorEmail) ;
            $akismet->setCommentContent($body) ;
            $akismet->setPermalink($itemURL) ;

            $status = $akismet->isCommentSpam() ? 'SPAM' : $status ;
        }

        try {
            $mComments = new Comment() ;
            $aComment  = array(
                            'dt_pub_date'    => DB_FUNC_NOW
                            ,'fk_i_item_id'   => $itemId
                            ,'s_author_name'  => $authorName
                            ,'s_author_email' => $authorEmail
                            ,'s_title'        => $title
                            ,'s_body'         => $body
                            ,'e_status'       => $status
                        );
            $mComments->insert($aComment) ;

            $notify = osc_notify_new_comment() ;
            $admin_email = osc_contact_email() ;
            $prefLocale = osc_language;

            //Notify admin
            if ($notify) {
                $mPages = new Page() ;
                $aPage = $mPages->findByInternalName('email_new_comment_admin') ;
                $locale = osc_get_user_locale() ;

                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }

                $words   = array();
                $words[] = array('{COMMENT_AUTHOR}', '{COMMENT_EMAIL}', '{COMMENT_TITLE}',
                                 '{COMMENT_TEXT}', '{ITEM_NAME}', '{ITEM_ID}', '{ITEM_URL}');
                $words[] = array($authorName, $authorEmail, $title, $body, $item['s_title'], $itemId, $itemURL);
                $title_email = osc_mailBeauty($content['s_title'], $words);
                $body_email = osc_mailBeauty($content['s_text'], $words);

                $from = osc_contact_email() ;
                $from_name = osc_page_title ;
                if (osc_notify_contact_item()) {
                    $add_bbc = osc_contact_email() ;
                }

                $emailParams = array(
                                'from'      => $admin_email
                                ,'from_name' => __('Admin mail system')
                                ,'subject'   => $title_email
                                ,'to'        => $admin_email
                                ,'to_name'   => __('Admin mail system')
                                ,'body'      => $body_email
                                ,'alt_body'  => $body_email
                                );
                osc_sendMail($emailParams) ;
            }
            osc_run_hook('add_comment', $item);
        } catch (Exception $e) {
            osc_add_flash_message(__('We are very sorry but could not save your comment. Try again later.')) ;
        }

        osc_redirectTo($itemURL) ;
        break;

    case 'post':
        if(!osc_users_enabled()) {
            osc_add_flash_message(__('Users are not enable')) ;
            osc_redirectTo(osc_base_url()) ;
        }

        $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null ;

        if (osc_reg_user_post()) {
            if ($userId == null) {
                // NOT OK
                osc_add_flash_message(__('You need to log-in in order to post a new item.')) ;
                osc_redirectTo(osc_login_url()) ;
                break;
            }
        }

        $user = ($userId!=null)?User::newInstance()->findByPrimaryKey($userId):null;
        $categories = Category::newInstance()->toTree();
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
        
        $currencies = Currency::newInstance()->listAll();
        osc_run_hook('post_item');
        osc_renderHeader(
                array(
                    'pageTitle' => __('Publish your item') . ' - ' . osc_page_title()
                    ,'noindex' => 'true'
                )
        );
        osc_renderView('item-post.php');
        osc_renderFooter();
        break;

    case 'post_item':
        if(!osc_users_enabled()) {
            osc_add_flash_message(__('Users are not enable'));
            osc_redirectTo(osc_base_url());
        }
        
        require_once LIB_PATH . 'osclass/items.php';

        if($success) {
            if(!isset($_SESSION['userId'])) {
                $mPages = new Page() ;
                $aPage = $mPages->findByInternalName('email_new_item_non_register_user') ;
                $locale = osc_get_user_locale() ;

                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }

                $item_url = osc_create_item_url($item) ;
                $urlEdit =  array(
                                'file'   => 'user'
                                ,'action' => 'item_edit'
                                ,'id'     => $itemId
                                ,'userId' => null
                                ,'secret' => $item['s_secret']
                            ) ;
                $edit_link = osc_create_url($urlEdit);

                $urlDelete =    array(
                                    'file'   => 'user'
                                    ,'action' => 'item_delete'
                                    ,'id'     => $itemId
                                    ,'userId' => null
                                    ,'secret' => $item['s_secret']
                                ) ;
                $delete_link = osc_create_url($urlDelete) ;

                $words   = array();
                $words[] = array('{ITEM_ID}', '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_TITLE}',
                                 '{ITEM_URL}', '{WEB_TITLE}', '{EDIT_LINK}', '{DELETE_LINK}');
                $words[] = array($itemId, $PcontactName, $PcontactEmail, osc_base_url(), $item['s_title'],
                                 $item_url, osc_page_title(), $edit_link, $delete_link) ;
                $title = osc_mailBeauty($content['s_title'], $words) ;
                $body = osc_mailBeauty($content['s_text'], $words) ;

                $emailParams =  array(
                                    'subject' => $title
                                    ,'to' => $PcontactEmail
                                    ,'to_name' => $PcontactName
                                    ,'body' => $body
                                    ,'alt_body' => $body
                                );
                osc_sendMail($params);
            }

            osc_run_hook('posted_item', $item);
            $category = Category::newInstance()->findByPrimaryKey($PcatId);
            osc_redirectTo(osc_search_category_url($category));
        } else {
            osc_redirectTo(osc_item_post_url());
        }
        break;

    case 'activate':
        if (isset($_GET['secret']) && isset($_GET['id'])) {
            $secret = $_GET['secret'];
            $id = $_GET['id'];
            $item = $manager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s'", $secret, $id);
            if (count($item) == 1) {
                $item_validated = $manager->listWhere("i.s_secret = '%s' AND i.e_status = '%s' AND i.pk_i_id = '%s'", $secret, 'INACTIVE', $id);
                if (!is_array($item_validated))
                    return false;

                if (count($item_validated) == 1) {
                    $manager->update(
                            array('e_status' => 'ACTIVE'),
                            array('s_secret' => $secret)
                    );
                    osc_run_hook('activate_item', $manager->findByPrimaryKey($id));
                    CategoryStats::newInstance()->increaseNumItems($item[0]['fk_i_category_id']);
                    osc_add_flash_message('Item validated');
                    osc_redirectTo(osc_create_item_url($item[0]));
                } else {
                    osc_add_flash_message('The item was validated before');
                    osc_redirectTo(osc_create_item_url($item[0]));
                }
            }
        }
        break;

    case 'update_cat_stats':
        $conn = getConnection() ;
        $date = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
        $sql = sprintf("SELECT COUNT(pk_i_id) as total, fk_i_category_id as category FROM `%st_item` WHERE `dt_pub_date` > '%s' GROUP BY fk_i_category_id", DB_TABLE_PREFIX, $date);
        $items = $conn->osc_dbFetchResults($sql);

        foreach ($items as $stats) {
            $category = $total = 0;
            foreach ($stats as $k => $v) {
                if ($k == "category") {
                    $category = $v;
                }
                if ($k == "total") {
                    $total = $v;
                }
            }
            CategoryStats::newInstance()->update(
                    array(
                        'i_num_items' => $total
                    ), array('fk_i_category_id' => $category)
            );
        }
        break;

    default:
        if ( !isset($_GET['id']) ) {
            osc_redirectTo(osc_base_url());
        }

        $item = $manager->findByPrimaryKey($_GET['id']);

        global $osc_request;
        $osc_request['section'] = $item['s_title'];
        $osc_request['category'] = $item['fk_i_category_id'];
        $osc_request['item'] = $item;
        $osc_request['location'] = 'item';
        
        if ($item['e_status'] == 'ACTIVE') {
            $mStats = new ItemStats();
            $mStats->increase('i_num_views', $item['pk_i_id']);
            
            $resources = $manager->findResourcesByID($_GET['id']);
            $comments = ItemComment::newInstance()->findByItemID($_GET['id']);

            foreach($item['locale'] as $k => $v) {
                $item['locale'][$k]['s_title'] = osc_apply_filter('item_title',$v['s_title']);
                $item['locale'][$k]['s_description'] = osc_apply_filter('item_description',$v['s_description']);
            }

            $mUser = new User();
            $user_prefs = User::newInstance()->preferences($item['fk_i_user_id']);
            $aUser = $mUser->findByPrimaryKey($item['fk_i_user_id']);
            $actual_locale = osc_get_user_locale() ;
            if(isset($aUser['locale'][$actual_locale]['s_info'])) {
                $aUser['s_info'] = $aUser['locale'][$actual_locale]['s_info'];
            } else {
                $aUser['s_info'] = '';
            }

            $headerConf = array('pageTitle' => $item['s_title'] . ' - ' . osc_page_title()) ;
            osc_run_hook('show_item', $item);
            osc_renderHeader($headerConf);
            osc_renderView('item.php');
            osc_renderFooter();
        } else {
            if (isset($_SESSION['userId']) && $item['fk_i_user_id'] == $_SESSION['userId']) {
                $resources = $manager->findResourcesByID($_GET['id']);
                $comments = ItemComment::newInstance()->findByItemID($_GET['id']);

                $headerConf = array('pageTitle' => $item['s_title'] . ' - '.osc_page_title()) ;
                osc_add_flash_message('This item is NOT validated. You should validate it in order to show this item
                    to the rest of the users. You could do that in your profile menu.');
                osc_renderHeader($headerConf);
                osc_renderView('item.php');
                osc_renderFooter();
            } else {

                osc_redirectTo('index.php');
            }
        }
}*/

?>
