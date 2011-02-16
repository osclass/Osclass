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

class CAdminTest extends AdminSecBaseModel
{
    //specific for this class
    private $testManager ;
    
    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->testManager = Test::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {

            case 'add':     //adding items and item descriptions
                            $this->testManager->loadUserInfo( Session::newInstance()->_get('adminEmail'), Session::newInstance()->_get('adminName') ) ;
                            $this->testManager->loadItemInfo( Session::newInstance()->_get('adminEmail') ) ;
                            osc_add_flash_message(__('Population of the database done properly')) ;
                            $this->doView("test/index.php");
            break;
            case 'del':     //adding items and item descriptions
                            osc_add_flash_message(__('Population of the database reverted')) ;
                            $this->doView("test/index.php");
            break;
            default:        //Page with two buttons... "populate DB with fake data" and "revert"
                            $this->doView("test/index.php");
            break;
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>
