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

    require_once '../oc-load.php' ;

    switch( Params::getParam('page') )
    {
        case('items'):      require_once(osc_admin_base_path() . 'items.php') ;
                            $do = new CAdminItems() ;
                            $do->doModel() ;
        break;
        case('comments'):   require_once(osc_admin_base_path() . 'comments.php') ;
                            $do = new CAdminItemComments() ;
                            $do->doModel() ;
        break;
        case ('login'):     require_once(osc_admin_base_path() . 'login.php') ;
                            $do = new CAdminLogin() ;
                            $do->doModel() ;
        break;
        case('categories'): require_once(osc_admin_base_path() . 'categories.php') ;
                            $do = new CAdminCategories() ;
                            $do->doModel() ;
        break;
        case('emails'): require_once(osc_admin_base_path() . 'emails.php') ;
                            $do = new CAdminEmails() ;
                            $do->doModel() ;
        break;
        case('pages'): require_once(osc_admin_base_path() . 'pages.php') ;
                            $do = new CAdminPages() ;
                            $do->doModel() ;
        break;
        case('ajax'):       require_once(osc_admin_base_path() . 'ajax.php') ;
                            $do = new CAdminAjax() ;
                            $do->doModel() ;
        break;
        default:            //login of oc-admin
                            require_once(osc_admin_base_path() . 'main.php') ;
                            $do = new CAdminMain() ;
                            $do->doModel() ;
    }

?>
