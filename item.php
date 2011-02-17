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

class CWebItem extends BaseModel
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

        $locales = Locale::newInstance()->listAllEnabled() ;
        //$this->_exportVariableToView('categories', $categories) ;
        $this->_exportVariableToView('locales', $locales) ;
        //$this->_exportVariableToView('latestItems', $latestItems) ;

        switch( $this->action ) {
            case 'post': // add
                if(!osc_users_enabled()) {
                    osc_add_flash_message(__('Users are not enabled')) ;
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
                $this->_exportVariableToView('user', $user) ;

                osc_run_hook('post_item');
                $this->doView('item-post.php');
                break;
                
            case 'post_item': // add_post
                if(!osc_users_enabled()) {
                    osc_add_flash_message(__('Users are not enabled'));
                    osc_redirectTo(osc_base_url());
                }

                // check the required fields

                $mItems = new ItemActions(false);
                $success = $mItems->add();

                if($success) {
                    $PcontactName   = Params::getParam('contactName');
                    $PcontactEmail  = Params::getParam('contactEmail');
                    $itemId = Params::getParam('itemId');
                    //echo $PcontactEmail."<br>".$PcontactName."<br>$itemId<br>";

                    if( Session::newInstance()->_get('userId') == '' ){ // if(!isset($_SESSION['userId'])) {

                        $mPages = new Page() ;
                        $aPage = $mPages->findByInternalName('email_new_item_non_register_user') ;
                        $locale = osc_get_user_locale() ;

                        $content = array();
                        if(isset($aPage['locale'][$locale]['s_title'])) {
                            $content = $aPage['locale'][$locale];
                        } else {
                            $content = current($aPage['locale']);
                        }

                        
                        $item =  $this->itemManager->findByPrimaryKey($itemId);
                        
                        $item_url = osc_item_url($item) ;
                        $urlEdit =  array(
                                        'file'   => 'user'
                                        ,'action' => 'item_edit'
                                        ,'id'     => $itemId
                                        ,'userId' => null
                                        ,'secret' => $item['s_secret']
                                    ) ;

                        $edit_link = osc_base_url(true). "?page=item&action=editItem&id=$itemId&secret=".$item['s_secret'];

                        $urlDelete =    array(
                                            'file'   => 'user'
                                            ,'action' => 'item_delete'
                                            ,'id'     => $itemId
                                            ,'userId' => null
                                            ,'secret' => $item['s_secret']
                                        ) ;
                        $delete_link = osc_base_url(true) . "?page=item&action=item_delete&id=$itemId&secret=".$item['s_secret'] ;

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
                    $category = Category::newInstance()->findByPrimaryKey($catId);
                    $this->redirectTo(osc_search_category_url($category));
                } else {
                    $this->redirectTo( osc_item_post_url() );
                }
            break;
            case 'editItem':
                //item edit
//                $secret = Params::getParam('secret');
//                $id     = Params::getParam('id');
//                $item   = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s'", $secret, $id);
//                    if (count($item) == 1) {
                        // secret es correcto
//                        if( Params::getParam('secret') != '' && Params::getParam('id') ){
                
                        $id = Params::getParam('id') ;
                        $item = Item::newInstance()->findByPrimaryKey($id);

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
                        $this->_exportVariableToView('user', $user) ;
                        $this->_exportVariableToView('item', $item) ;
//                        }
//                    }
                $this->doView('item-edit.php');

            break;
            case 'item_edit_post':
                $userId = Session::newInstance()->_get('userId');

                $mItems = new ItemActions(false);
                $success = $mItems->edit($userId);

                $id = Params::getParam('id');

                if($success){
                    osc_run_hook('item_edit_post');
                    osc_add_flash_message(__('Great! We\'ve just update your item.'));
                    $this->redirectTo( osc_base_url(true) . "?page=item&id=$id" ) ;
                } else {
                    $id = Params::getParam('id');
                    $this->redirectTo( osc_base_url() . "?page=item&action=editItem&id=$id" );
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
                        $item_validated = $this->itemManager->listWhere("i.s_secret = '%s' AND i.e_status = '%s' AND i.pk_i_id = '%s'", $secret, 'INACTIVE', $id);
                        if (!is_array($item_validated))
                            return false;

                        if (count($item_validated) == 1) {
                            $this->itemManager->update(
                                    array('e_status' => 'ACTIVE'),
                                    array('s_secret' => $secret)
                            );
                            osc_run_hook('activate_item', $this->itemManager->findByPrimaryKey($id));
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
            case 'item_delete':
                $id = intval( Params::getParam('id') ) ;
                $secret = Params::getParam('secret') ;


                osc_add_flash_message(__('Your item has been deleted.')) ;
                if(!osc_is_web_user_logged_in ()) {
                    Item::newInstance()->delete(array('pk_i_id' => $id, 's_secret' => $secret));
                    osc_add_flash_message(__('You could register and access every time to your items.'));
                    osc_redirectTo(osc_register_account_url());//'user.php?action=register');
                } else {
                    Item::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_user_id' => $userId, 's_secret' => $secret));
                    osc_redirectTo(osc_user_list_items_url());//'user.php?action=items');
                }
                osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
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

                //$aResources = ItemResource::newInstance()->getAllResources( Params::getParam('id') ) ;
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
                //$this->_exportVariableToView('resources', $aResources) ;
                osc_run_hook('show_item', $item);
                $this->doView('item.php');
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
