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

class CAdminItems extends AdminSecBaseModel
{
    //specific for this class
    private $localeManager ;

    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->localeManager = Locale::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        switch ($this->action)
        {
            case 'add':                 $this->doView('languages/add.php') ;
            break;
            case 'add_post':            $path = TRANSLATIONS_PATH . pathinfo($_FILES['package']['name'], PATHINFO_FILENAME);
                                        if(osc_packageExtract($_FILES['package']['tmp_name'], $path)) {
                                            osc_add_flash_message(__('The language has been installed correctly.'));
                                        } else {
                                            osc_add_flash_message(__('There was a problem adding the language. Please, try again. If the problem persist, contact the developer of the package or install it manually via FTP/SSH.'));
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=languages' ) ;
            break;
            case 'edit':                $this->localeManager = Locale::newInstance()->findByPrimaryKey($_GET['id']) ;
                                        $this->doView('languages/frm.php') ;
            break;
            case 'edit_post':           $code = $_POST['pk_c_code'] ;
                                        unset($_POST['pk_c_code']) ;

                                        if (!isset($_POST['b_enabled']))
                                            $_POST['b_enabled'] = DB_CONST_FALSE ;
                                        if (!isset($_POST['b_enabled_bo']))
                                            $_POST['b_enabled_bo'] = DB_CONST_FALSE ;

                                        $this->localeManager->update($_POST, array('pk_c_code' => $code)) ;
                                        $this->redirectTo(osc_admin_base_url(true).'?page=languages') ;
            break;
            case 'enable':              
            case 'enable_bo':           $default_lang = osc_language() ;
                                        $id = Params::getParam('id') ;
                                        $enabled = Params::getParam('enabled') ;
                                        
                                        if ($id) {
                                            if($action == 'enable' && $default_lang == $id && $enabled == 0) {
                                                osc_add_flash_message($id.__(' can not be disabled, it\'s the deault language. Please, change the default language under General Settings in order to disable it'));
                                            } else {
                                                $msg = ($enabled == 1) ? __('The language has been enabled for the public website') : __('The language has been disabled for the public website') ;
                                                $aValues = array('b_enabled' => $enabled) ;
                                                $this->localeManager->update($aValues, array('pk_c_code' => $id)) ;
                                            }
                                            if ($action == 'enable_bo') {
                                                $msg = ($enabled == 1) ? __('The language has been enabled for the backoffice (oc-admin)') : __('The language has been disabled for the backoffice (oc-admin)') ;
                                                $aValues = array('b_enabled_bo' => $enabled) ;
                                                $this->localeManager->update($aValues, array('pk_c_code' => $id)) ;
                                            }

                                            osc_add_flash_message( $msg ) ;
                                        } else {
                                            osc_add_flash_message(__('There was a problem updating the language. The ID of the language was lost')) ;
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=languages') ;
            break;
            case 'enable_selected':     $msg = __('Selected languages have been enabled for the website') ;
                                        $aValues = array('b_enabled' => 1) ;

            case 'disable_selected':    $msg = __('Selected languages have been disabled for the website') ;
                                        $aValues = array('b_enabled' => 0) ;

            case 'enable_bo_selected':  $msg = __('Selected languages have been enabled for the backoffice (oc-admin)') ;
                                        $aValues = array('b_enabled_bo' => 1) ;

            case 'disable_bo_selected': $msg = __('Selected languages have been disabled for the backoffice (oc-admin)') ;
                                        $aValues = array('b_enabled_bo' => 0) ;
                
                                        $id = Params::getParam('id') ;
                                        if ($id != '') {
                                            $default_lang = osc_language() ;
                                            foreach ($id as $i) {
                                                if($default_lang == $i && $action == 'disable_selected') {
                                                    osc_add_flash_message($i.__(' can not be disabled, it\'s the deault language. Please, change the default language under General Settings in order to disable it'));
                                                } else {
                                                    $this->localeManager->update($aValues, array('pk_c_code' => $i)) ;
                                                }
                                            }
                                            osc_add_flash_message($msg) ;
                                        } else {
                                            osc_add_flash_message(__('There was a problem updating the languages. The IDs of the languages were lost')) ;
                                        }

                                        $this->redirectTo(osc_admin_base_url(true) . '?page=languages') ;
            break;
            case 'delete':              if ( is_array(Params::getParam('code') ) )
                                        {
                                            $default_lang = osc_language() ;
                                            foreach ( Params::getParam('code') as $code) {
                                                if( $default_lang != $code ) {
                                                    $this->localeManager->deleteLocale($code) ;
                                                    if (!osc_deleteDir(TRANSLATIONS_PATH . $code)) {
                                                        osc_add_flash_message(__('Directory "%s" could not be removed'), $code) ;
                                                    }
                                                } else {
                                                        osc_add_flash_message(__('Directory "%s" could not be removed, it\' the default language. Set another language as default first and try again'), $code) ;
                                                }
                                            }
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=languages') ;
            break;
            default:                    osc_checkLocales() ;
                                        $locales = Locale::newInstance()->listAll() ;
                                        $this->doView('languages/index.php') ;
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>