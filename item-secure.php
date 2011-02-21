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
            default:
            
        }
    }

    //hopefully generic...
    function doView($file) {
        osc_current_web_theme_url($file) ;
    }
}

?>
