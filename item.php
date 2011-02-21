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
    private $user;
    private $userId;

    function __construct() {
        parent::__construct() ;
        $this->itemManager = Item::newInstance();
        
        // here allways userId == ''
        if( Session::newInstance()->_get('userId') != '' ){
            $this->userId = Session::newInstance()->_get('userId');
            $this->user = User::newInstance()->findByPrimaryKey($this->userId);
        }else{
            $this->userId = null;
            $this->user = null;
        }        
    }

    //Business Layer...
    function doModel() {
        //calling the view...

        $locales = Locale::newInstance()->listAllEnabled() ;
        //$this->_exportVariableToView('categories', $categories) ;
        $this->_exportVariableToView('locales', $locales) ;
        //$this->_exportVariableToView('latestItems', $latestItems) ;

        switch( $this->action ){
            case 'item_add': // post
                if( !osc_users_enabled () ){
                    osc_add_flash_message(__('Users not enable')) ;
                    $this->redirectTo(osc_base_url(true));
                }
                if( osc_reg_user_post() && $this->user==null) {
                    // CHANGEME: This text
                    osc_add_flash_message(__('Only registered users are allowed to post items')) ;
                    $this->redirectTo(osc_base_url(true));
                }

                $categories = Category::newInstance()->toTree();
                $currencies = Currency::newInstance()->listAll();

                $countries = Country::newInstance()->listAll();
                $regions = array(); 
                if( isset($this->user['fk_c_country_code']) && $this->user['fk_c_country_code']!='' ) {
                    $regions = Region::newInstance()->getByCountry($this->user['fk_c_country_code']);
                } else if( count($countries) > 0 ) {
                    $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
                }
                $cities = array();
                if( isset($this->user['fk_i_region_id']) && $this->user['fk_i_region_id']!='' ) {
                    $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$this->user['fk_i_region_id']) ;
                } else if( count($regions) > 0 ) {
                    $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
                }

                $this->_exportVariableToView('categories', $categories) ;
                $this->_exportVariableToView('currencies', $currencies) ;
                $this->_exportVariableToView('countries',$countries ) ;
                $this->_exportVariableToView('regions', $regions) ;
                $this->_exportVariableToView('cities', $cities) ;
                $this->_exportVariableToView('user', $this->user) ;

                osc_run_hook('post_item');
                $this->doView('item-post.php');
                break;
                
            case 'item_add_post': //post_item
                if( !osc_users_enabled () ){
                    osc_add_flash_message(__('Users not allowed')) ;
                    $this->redirectTo(osc_base_url(true));
                }
                if( osc_reg_user_post() && $this->user==null) {                
                    osc_add_flash_message(__('Only registered users are allowed to post items')) ;
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
                        $edit_link = osc_base_url(true). "?page=item&action=item_edit&id=$itemId&secret=".$item['s_secret'];
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
                
                /*if( osc_reg_user_post() && $this->user==null) {
                    osc_add_flash_message(__('Only allow registered users to post items') ) ;
                    $this->redirectTo(osc_base_url(true));
                }*/
                // not logged user
                // only can edit item if have a secret and idItem
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
                if (count($item) == 1) {
                    $item = Item::newInstance()->findByPrimaryKey($id);

                    $categories = Category::newInstance()->toTree();
                    $countries = Country::newInstance()->listAll();
                    $regions = array();
                    if( isset($this->user['fk_c_country_code']) && $this->user['fk_c_country_code']!='' ) {
                        $regions = Region::newInstance()->getByCountry($this->user['fk_c_country_code']);
                    } else if( count($countries) > 0 ) {
                        $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
                    }
                    $cities = array();
                    if( isset($this->user['fk_i_region_id']) && $this->user['fk_i_region_id']!='' ) {
                        $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$this->user['fk_i_region_id']) ;
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
                    $this->_exportVariableToView('user', $this->user) ;

                    $this->doView('item-edit.php');
                }else{
                    // add a flash message [ITEM NO EXISTE]
                    //$this->redirectTo(osc_base_url(true));
                    osc_add_flash_message(__('Sorry, we don\'t have any items with that ID')) ;
                    if($this->user!=null) {
                        $this->redirectTo(osc_user_list_items_url());
                    } else {
                        $this->redirectTo( osc_base_url() ) ;
                    }
                }
            break;
            case 'item_edit_post':
               
                // recoger el secret y el 
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
                if (count($item) == 1) {
                    
                    $mItems = new ItemActions(false);
                    $success = $mItems->edit();

                    if($success){
                        osc_run_hook('item_edit_post');
                        osc_add_flash_message(__('Great! We\'ve just updated your item')) ;
                        $this->redirectTo( osc_base_url(true) . "?page=item&id=$id" ) ;
                    } else {
                        $this->redirectTo( osc_item_edit_url($secret) ) ;
                    }
                }
            break;
            case 'activate':
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
                if ($item[0]['e_status']=='INACTIVE') {
                    // ACTIVETE ITEM
                    $mItems = new ItemActions(false) ;
                    $success = $mItems->activate( $item[0]['pk_i_id'], $item[0]['s_secret'] );

                    if( $success ){
                        osc_add_flash_message( __('The item has been validated') ) ;
                    }else{
                        osc_add_flash_message( __('The item can\'t be validated') ) ;
                    }
                }else{
                    osc_add_flash_message( __('The item has already been validated') );
                }
                $this->redirectTo( osc_item_url($item[0]) );
            break;
            case 'item_delete':
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);

                if (count($item) == 1) {
                    $mItems = new ItemActions(false);
                    $success = $mItems->delete($item['s_secret'], $item['pk_i_id']);
                    osc_add_flash_message( __('Your item has been deleted') ) ;
                    if($this->user!=null) {
                        $this->redirectTo(osc_user_list_items_url());
                    } else {
                        $this->redirectTo( osc_base_url() ) ;
                    }
                }else{
                    osc_add_flash_message( __('The item you are trying to delete couldn\'t be deleted') ) ;
                    $this->redirectTo( osc_base_url() ) ;
                }
            break;
            case 'mark':
                $mItem = new ItemActions(false) ;

                $id = Params::getParam('id') ;
                $as = Params::getParam('as') ;
                
                $mItem->mark($id, $as) ;

                osc_add_flash_message( __('Thanks! That\'s very helpful') ) ;
                $this->redirectTo( osc_item_url($id) );

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
                        osc_add_flash_message(__('We\'re sorry, but the item has expired. You can\'t contact the seller')) ;
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
                        osc_add_flash_message(__('We\'re sorry, but the item has expired. You can\'t contact the seller')) ;
                        $this->redirectTo(osc_item_url($item));
                    }
                }

                $mItem = new ItemActions(false);
                $mItem->contact();

                osc_add_flash_message(__('We\'ve just sent an e-mail to the seller')) ;
                $this->redirectTo( osc_item_url($item) );
                
                break;
            case 'add_comment':
                $mItem = new ItemActions(false);
                $mItem->add_comment();

                $this->redirectTo( Params::getParam('itemURL') );
                break;
            default:
                if( Params::getParam('id') == ''){
                    $this->redirectTo(osc_base_url());
                }

                $item = $this->itemManager->findByPrimaryKey( Params::getParam('id') );
                // if item doesn't exist redirect to base url
                if( !$item['fk_i_item_id'] ){
                    osc_add_flash_message( __('This item doesn\'t exist') );
                    $this->redirectTo( osc_base_url(true) );
                }else{
                    
                    if ($item['e_status'] != 'ACTIVE') {
                        if( $this->userId == $item['fk_i_user_id'] ) {
                            osc_add_flash_message(__('The item hasn\'t been validated. Please validate it in order to
                                show it to the rest of users') );
                        } else {
                            osc_add_flash_message( __('This item hasn\'t been validated') );
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

                    $this->_exportVariableToView('items', array($item)) ;

                    osc_run_hook('show_item', $item) ;

                    $this->doView('item.php') ;
                }
            break;
            
            case('dashboard'):      //dashboard...

            break;
        }
    }

    //hopefully generic...
    function doView($file) {
        osc_current_web_theme_path($file) ;
    }
}

?>
