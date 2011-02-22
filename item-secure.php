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

class CWebSecItem extends WebSecBaseModel
{
    private $itemManager;

    function __construct() {
        parent::__construct() ;
        $this->itemManager = Item::newInstance();
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
                // TODO
                // hacer igual que item.php solo hace falta pasar el item
                // los demas parametros se obtienen en las vistas gracias a los helpers.
                if(!osc_users_enabled()) {
                    osc_add_flash_message( _m('Users are not enable')) ;
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
                    osc_add_flash_message( _m('Users are not enable'));
                    osc_redirectTo(osc_base_url());
                }
                
                $mItems = new ItemActions(false);
                $success = $mItems->add();

                if($success) {
                    osc_run_hook('posted_item', $item);
                    $category = Category::newInstance()->findByPrimaryKey($catId);
                    $this->redirectTo(osc_search_category_url($category));
                } else {
                    $this->redirectTo( osc_item_post_url() );
                }
            break;
            // TODO case edit_post
            case 'item_edit':
                
            break;
            // TODO case item_edit_post
            case 'item_edit_post':
                
            break;
            case 'activate':
                // TODO
                // solo puede activar un item si el item es del usuario que lo quiere activar y
                // el secret concuerda.
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);
                if ($item[0]['e_status']=='INACTIVE') {
                    // ACTIVETE ITEM
                    $mItems = new ItemActions(false) ;
                    $success = $mItems->activate( $item[0]['pk_i_id'], $item[0]['s_secret'] );

                    if( $success ){
                        osc_add_flash_message( _m('Item validated') ) ;
                    }else{
                        osc_add_flash_message( _m('Item could not be validated') ) ;
                    }
                }else{
                    osc_add_flash_message( _m('The item was validated before') );
                }
                $this->redirectTo( osc_item_url($item[0]) );
            break;
            case 'item_delete':
                // TODO
                // solo puede borrar un item si le pertenece a el usuario que lo quiere borrar
                // y si el secret es el correcto.
                $secret = Params::getParam('secret');
                $id     = Params::getParam('id');
                $item   = $this->itemManager->listWhere("i.pk_i_id = '%s' AND ((i.s_secret = '%s' AND i.fk_i_user_id IS NULL) OR (i.fk_i_user_id = '%d'))", $id, $secret, $this->userId);

                if (count($item) == 1) {
                    $mItems = new ItemActions(false);
                    $success = $mItems->deleteByPrimaryKey($id);
                    osc_add_flash_message( _m('Your item has been deleted.') ) ;
                    if($this->user!=null) {
                        $this->redirectTo(osc_user_list_items_url());
                    } else {
                        $this->redirectTo( osc_base_url() ) ;
                    }
                }else{
                    osc_add_flash_message( _m('The item you are trying to delete has not been deleted.') ) ;
                    $this->redirectTo( osc_base_url() ) ;
                }
            break;
            case 'mark':
                $mItem = new ItemActions(false) ;

                $id = Params::getParam('id') ;
                $as = Params::getParam('as') ;

                $mItem->mark($id, $as) ;

                osc_add_flash_message( _m('Thanks! That helps us') ) ;
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
                        osc_add_flash_message( _m('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
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
                        osc_add_flash_message( _m('We\'re sorry, but the item is expired. You can not contact the seller.')) ;
                        $this->redirectTo(osc_item_url($item));
                    }
                }

                $mItem = new ItemActions(false);
                $mItem->contact();

                osc_add_flash_message( _m('We\'ve just sent an e-mail to the seller.')) ;
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
                    osc_add_flash_message( _m('This item doesn\'t exist.') );
                    $this->redirectTo( osc_base_url(true) );
                }else{

                    if ($item['e_status'] != 'ACTIVE') {
                        if( $this->userId == $item['fk_i_user_id'] ) {
                            osc_add_flash_message( _m('This item is NOT validated. You should validate it in order to show this item
                                to the rest of the users. You could do that in your profile menu.') );
                        } else {
                            osc_add_flash_message( _m('This item is NOT validated.') );  // el item no esta activado,  tienes el enlace de activacion en el correo
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

                    /*$this->user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                    $actual_locale = osc_get_user_locale() ;
                    if(isset($author['locale'][$actual_locale]['s_info'])) {
                        $author['s_info'] = $author['locale'][$actual_locale]['s_info'];
                    } else {
                        $author['s_info'] = '';
                    }*/
                    //$this->_exportVariableToView('user', $this->user) ;
                    $this->_exportVariableToView('items', array($item)) ;
                    //$this->_exportVariableToView('comments', $aComments) ;
                    //$this->_exportVariableToView('resources', $aResources) ;
                    //$this->_exportVariableToView('section',$item['s_title']) ;
                    //$this->_exportVariableToView('category', $item['fk_i_category_id']) ;
                    //$this->_exportVariableToView('location', 'item' ) ; //  ??

                    osc_run_hook('show_item', $item) ;

                    $this->doView('item.php') ;
            break;    }
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
