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

        switch( $this->action ){
            case 'post': // add
                if( osc_reg_user_post() ) {     // 1 => solo los registrados pueden añadir items
                                                // 0 => todos pueden añadir items
                    osc_add_flash_message(__('Only allow registered users to post items')) ;
                    $this->redirectTo(osc_base_url(true));
                }

                $categories = Category::newInstance()->toTree();
                $countries = Country::newInstance()->listAll();
                $currencies = Currency::newInstance()->listAll();
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

                $this->_exportVariableToView('categories', $categories) ;
                $this->_exportVariableToView('currencies', $currencies) ;
                $this->_exportVariableToView('countries',$countries ) ;
                $this->_exportVariableToView('regions', $regions) ;
                $this->_exportVariableToView('cities', $cities) ;

                osc_run_hook('post_item');

                $this->doView('item-post.php');
                break;
                
            case 'post_item': // add_post
                if( osc_reg_user_post() ) {                
                    osc_add_flash_message(__('Only allow registered users to post items')) ;
                    $this->redirectTo(osc_base_url(true));
                }
                // POST ITEM ( ADD ITEM ) 
                $mItems = new ItemActions(false);
                $success = $mItems->add();

                if($success) {
                    $PcontactName   = Params::getParam('contactName');
                    $PcontactEmail  = Params::getParam('contactEmail');
                    $itemId         = Params::getParam('itemId');

                    if( Session::newInstance()->_get('userId') == '' ){
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
                        // before page = user , action = item_edit
                        $edit_link = osc_base_url(true). "?page=item&action=editItem&id=$itemId&secret=".$item['s_secret'];
                        // before page = user , action = item_delete
                        $delete_link = osc_base_url(true) . "?page=item&action=item_delete&id=$itemId&secret=".$item['s_secret'] ;

                        $words   = array();
                        $words[] = array('{ITEM_ID}', '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_TITLE}',
                                         '{ITEM_URL}', '{WEB_TITLE}', '{EDIT_LINK}', '{DELETE_LINK}');
                        $words[] = array($itemId, $PcontactName, $PcontactEmail, osc_base_url(), $item['s_title'],
                                         $item_url, osc_page_title(), $edit_link, $delete_link) ;
                        $title   = osc_mailBeauty($content['s_title'], $words) ;
                        $body    = osc_mailBeauty($content['s_text'], $words) ;

                        $emailParams =  array(
                                            'subject' => $title
                                            ,'to' => $PcontactEmail
                                            ,'to_name' => $PcontactName
                                            ,'body' => $body
                                            ,'alt_body' => $body
                                        );

                        osc_sendMail($emailParams);
                    }

                    osc_run_hook('posted_item', $item);
                    $category = Category::newInstance()->findByPrimaryKey($catId);
                    $this->redirectTo(osc_search_category_url($category));
                } else {
                    $this->redirectTo( osc_item_post_url() );
                }
            break;
            case 'item_edit':
                if( osc_reg_user_post() ) {
                    osc_add_flash_message(__('Only allow registered users to post items') ) ;
                    $this->redirectTo(osc_base_url(true));
                }
                // not logged user
                // only can edit item if have a secret and idItem
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL", $secret, $id);
                if (count($item) == 1) {
                    $item = Item::newInstance()->findByPrimaryKey($id);

                    if(!osc_users_enabled()) {
                        osc_add_flash_message(__('Users are not enable') ) ;
                        $this->redirectTo(osc_base_url(true));
                    }

                    if( Session::newInstance()->_get('userId') != '' ){
                        $userId = Session::newInstance()->_get('userId');
                    }else{
                        $userId = null;
                    }

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
                    $this->_exportVariableToView('item', $item) ;

                    $this->doView('item-edit.php');
                }else{
                    // add a flash message [ITEM NO EXISTE]
                    $this->redirectTo(osc_base_url(true));
                }
            break;
            case 'item_edit_post':
                // recoger el secret y el 
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL", $secret, $id);
                if (count($item) == 1) {
                    
                    $mItems = new ItemActions(false);
                    $success = $mItems->edit();

                    if($success){
                        osc_run_hook('item_edit_post');
                        osc_add_flash_message(__('Great! We\'ve just update your item.')) ;
                        $this->redirectTo( osc_base_url(true) . "?page=item&id=$id" ) ;
                    } else {
                        $this->redirectTo( osc_base_url(true) . "?page=item&action=editItem&id=$id" ) ;
                    }
                }

            break;
            case 'activate':
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL ", $secret, $id);
                if (count($item) == 1) {
                    $item_validated = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL AND i.e_status = '%s'", $secret, $id, 'INACTIVE');
                    if ( !is_array($item_validated) ) {
                        // aqui hay que poner flashmessage ??
                        $this->redirectTo( osc_base_url(true) );
                    }
                    if( count($item_validated) == 1 ) {
                        // ACTIVETE ITEM
                        $mItems = new ItemActions(false) ;
                        $success = $mItems->activate( Params::getParam('secret'), Params::getParam('id') );

                        if( $success ){
                            osc_add_flash_message( __('Item validated') ) ;
                            $this->redirectTo( osc_item_url($item[0]) );
                        }else{
                            osc_add_flash_message( __('Item could not be validated') ) ;
                            $this->redirectTo( osc_base_url(true) );
                        }
                    }else {
                        osc_add_flash_message( __('The item was validated before') );
                        $this->redirectTo( osc_item_url($item[0]) );
                    }
                }else{
                    osc_add_flash_message( __('The item was validated before') );
                    $this->redirectTo( osc_base_url(true) );
                }
            break;
            case 'item_delete':
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL ", $secret, $id);

                if (count($item) == 1) {
                    $mItems = new ItemActions(false);
                    $success = $mItems->delete( $secret, $id);
                    osc_add_flash_message( __('Your item has been deleted.') ) ;
                    $this->redirectTo( osc_register_account_url() ) ;
                }else{
                    osc_add_flash_message( __('The item you are trying to delete has not been deleted.') ) ;
                    $this->redirectTo( osc_base_url(true) ) ;
                }
//                osc_add_flash_message(__('Your item has been deleted.')) ;
//                if(!osc_is_web_user_logged_in ()) {
//                    Item::newInstance()->delete(array('pk_i_id' => $id, 's_secret' => $secret));
//                    osc_add_flash_message(__('You could register and access every time to your items.'));
//                    osc_redirectTo(osc_register_account_url());//'user.php?action=register');
//                } else {
//                    Item::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_user_id' => $userId, 's_secret' => $secret));
//                    osc_redirectTo(osc_user_list_items_url());//'user.php?action=items');
//                }
//                osc_redirectTo(osc_createUserItemsURL());//'user.php?action=items');
            break;
            case 'mark':
                $mItem = new ItemActions(false);
                $mItem->mark();

                $item = Params::getParam('item');
                
                osc_add_flash_message(__('Thanks! That helps us.'));
                $this->redirectTo( osc_item_url($item) );

            break;
            case 'send_friend':
                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );

                $this->_exportVariableToView('item', $item) ;

                $this->doView('item-send-friend.php');
            break;
            case 'send_friend_post':
                $mItem = new ItemActions(false);
                $mItem->send_friend();

                $item_url = Params::getParam('item_url');
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

                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') ) ;

                $category = Category::newInstance()->findByPrimaryKey($item['fk_i_category_id']);

                if($category['i_expiration_days'] > 0) {
                    $item_date = strtotime($item['dt_pub_date'])+($category['i_expiration_days']*(24*3600)) ;
                    $date = time();
                    if($item_date < $date) {
                        // The item is expired, we can not contact the seller
                        osc_add_flash_message(__('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
                        $this->redirectTo(osc_item_url($item));
                    }
                }

                $mItem = new ItemActions(false);
                $mItem->contact();

                osc_add_flash_message(__('We\'ve just sent an e-mail to the seller.')) ;
                $this->redirectTo( osc_item_url($item) );
                
                break;
            case 'add_comment':
//                $authorName     = Params::getParam('authorName') ;
//                $authorEmail    = Params::getParam('authorEmail') ;
//                $body           = Params::getParam('body') ;
//                $title          = Params::getParam('title') ;
//                $itemId         = Params::getParam('id') ;
//
//                $item = $this->itemManager->findByPrimaryKey($itemId) ;
//
//                $itemURL = osc_item_url($item) ;
//
//                if (osc_moderate_comments()) {
//                    $status = 'INACTIVE' ;
//                } else {
//                    $status = 'ACTIVE' ;
//                }
//                if (osc_akismet_key()) {
//                    require_once LIB_PATH . 'Akismet.class.php' ;
//                    $akismet = new Akismet(osc_base_url(), osc_akismet_key()) ;
//                    $akismet->setCommentAuthor($authorName) ;
//                    $akismet->setCommentAuthorEmail($authorEmail) ;
//                    $akismet->setCommentContent($body) ;
//                    $akismet->setPermalink($itemURL) ;
//
//                    $status = $akismet->isCommentSpam() ? 'SPAM' : $status ;
//                }
//
//
//                $mComments = new Comment() ;
//                $aComment  = array(
//                                'dt_pub_date'    => DB_FUNC_NOW
//                                ,'fk_i_item_id'   => $itemId
//                                ,'s_author_name'  => $authorName
//                                ,'s_author_email' => $authorEmail
//                                ,'s_title'        => $title
//                                ,'s_body'         => $body
//                                ,'e_status'       => $status
//                            );
//
//                if( $mComments->insert($aComment) ){
//
//                    $notify = osc_notify_new_comment() ;
//                    $admin_email = osc_contact_email() ;
//                    $prefLocale = osc_language;
//
//                    //Notify admin
//                    if ($notify) {
//                        $mPages = new Page() ;
//                        $aPage = $mPages->findByInternalName('email_new_comment_admin') ;
//                        $locale = osc_get_user_locale() ;
//
//                        $content = array();
//                        if(isset($aPage['locale'][$locale]['s_title'])) {
//                            $content = $aPage['locale'][$locale];
//                        } else {
//                            $content = current($aPage['locale']);
//                        }
//
//                        $words   = array();
//                        $words[] = array('{COMMENT_AUTHOR}', '{COMMENT_EMAIL}', '{COMMENT_TITLE}',
//                                         '{COMMENT_TEXT}', '{ITEM_NAME}', '{ITEM_ID}', '{ITEM_URL}');
//                        $words[] = array($authorName, $authorEmail, $title, $body, $item['s_title'], $itemId, $itemURL);
//                        $title_email = osc_mailBeauty($content['s_title'], $words);
//                        $body_email = osc_mailBeauty($content['s_text'], $words);
//
//                        $from = osc_contact_email() ;
//                        $from_name = osc_page_title ;
//                        if (osc_notify_contact_item()) {
//                            $add_bbc = osc_contact_email() ;
//                        }
//
//                        $emailParams = array(
//                                        'from'      => $admin_email
//                                        ,'from_name' => __('Admin mail system')
//                                        ,'subject'   => $title_email
//                                        ,'to'        => $admin_email
//                                        ,'to_name'   => __('Admin mail system')
//                                        ,'body'      => $body_email
//                                        ,'alt_body'  => $body_email
//                                        );
//                        osc_sendMail($emailParams) ;
//                    }
//                    osc_run_hook('add_comment', $item);
//                }else{
//                    osc_add_flash_message(__('We are very sorry but could not save your comment. Try again later.')) ;
//                }

                $this->redirectTo( Params::getParam('itemURL') );
                break;

            case('dashboard'):      //dashboard...

            break;
            
            

            
            default:
                if( Params::getParam('id') == ''){
                    $this->redirectTo(osc_base_url());
                }

                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                // if item doesn't exist redirect to base url
                if( !$item['fk_i_item_id'] ){
                    osc_add_flash_message( __('This item doesn\'t exist.') );
                    $this->redirectTo( osc_base_url(true) );
                }else{
                    
                    if ($item['e_status'] != 'ACTIVE') {
                        if( Session::newInstance()->_get('userId') != '' && Session::newInstance()->_get('userId') == $item['fk_i_user_id'] ) {
                            osc_add_flash_message(__('This item is NOT validated. You should validate it in order to show this item
                                to the rest of the users. You could do that in your profile menu.') );
                        } else {
                            osc_add_flash_message( __('This item is NOT validated.') );  // el item no esta activado,  tienes el enlace de activacion en el correo
                            $this->redirectTo( osc_base_url(true) );
                        }
                    }
                    $mStats = new ItemStats();
                    $mStats->increase('i_num_views', $item['pk_i_id']);

                    //$aResources = ItemResource::newInstance()->getAllResources( Params::getParam('id') ) ;
                    //$aComments = ItemComment::newInstance()->findByItemID( Params::getParam('id') );

                    foreach($item['locale'] as $k => $v) {
                        $item['locale'][$k]['s_title'] = osc_apply_filter('item_title',$v['s_title']);
                        $item['locale'][$k]['s_description'] = osc_apply_filter('item_description',$v['s_description']);
                    }

                    /*$user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                    $actual_locale = osc_get_user_locale() ;
                    if(isset($author['locale'][$actual_locale]['s_info'])) {
                        $author['s_info'] = $author['locale'][$actual_locale]['s_info'];
                    } else {
                        $author['s_info'] = '';
                    }*/
                    //$this->_exportVariableToView('user', $user) ;
                    $this->_exportVariableToView('items', array($item)) ;
                    //$this->_exportVariableToView('comments', $aComments) ;
                    //$this->_exportVariableToView('resources', $aResources) ;
                    //$this->_exportVariableToView('section',$item['s_title']) ;
                    //$this->_exportVariableToView('category', $item['fk_i_category_id']) ;
                    //$this->_exportVariableToView('location', 'item' ) ; //  ??

                    osc_run_hook('show_item', $item) ;

                    $this->doView('item.php') ;
                }
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
