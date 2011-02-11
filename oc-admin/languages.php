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

class CAdminLanguages extends AdminSecBaseModel
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
            case 'add_post':            $filePackage = Params::getFiles('package');
                                        $path = osc_translations_path() . pathinfo($filePackage['name'], PATHINFO_FILENAME);

                                        if(preg_match('/^[a-z_]+\.zip$/i', $filePackage['name'])) {
                                            if(osc_packageExtract($filePackage['tmp_name'], $path)) {
                                                osc_add_flash_message(__('The language has been installed correctly.'));
                                            } else {
                                                osc_add_flash_message(__('There was a problem adding the language. Please, try again. If the problem persist, contact the developer of the package or install it manually via FTP/SSH.'));
                                            }
                                        }else{
                                            osc_add_flash_message(__('There was a problem adding the language. The language zip must be aa_AA.zip. Please rename it.'));
                                        }

                                        
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=languages' ) ;
            break;
            case 'edit':                $locale = $this->localeManager->findByPrimaryKey(Params::getParam('id')) ;

                                        $this->_exportVariableToView("locale", $locale);

                                        $this->doView('languages/frm.php') ;
            break;
            case 'edit_post':           $code = Params::getParam('pk_c_code') ;
                                        $enabled = Params::getParam('b_enabled') ;
                                        if ($enabled == '' ){
                                            $enabled = DB_CONST_FALSE ;
                                        }else{
                                            $enabled = DB_CONST_TRUE ;
                                        }
                                        $enabled_bo = Params::getParam('b_enabled_bo') ;
                                        if ( $enabled_bo == '' ){
                                            $enabled_bo = DB_CONST_FALSE ;
                                        }else{
                                            $enabled_bo = DB_CONST_TRUE;
                                        }

                                        $array = array(
                                            'b_enabled'         => $enabled,
                                            'b_enabled_bo'      => $enabled_bo,
                                            's_name'            => Params::getParam('s_name'),
                                            's_short_name'      => Params::getParam('s_short_name'),
                                            's_description'     => Params::getParam('s_description'),
                                            's_currency_format' => Params::getParam('s_currency_format'),
                                            's_date_format'     => Params::getParam('s_date_format'),
                                            's_stop_words'      => Params::getParam('s_stop_words'),
                                        );
                                        
                                        $this->localeManager->update($array, array('pk_c_code' => $code)) ;

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
                                                    if (!osc_deleteDir(osc_translations_path() . $code)) {
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

                                        $this->add_css('languages_layout.css');
                                        $this->add_css('demo_table.css');
                                        $this->add_global_js('jquery.dataTables.min.js');

                                        $this->_exportVariableToView("locales", $locales);

                                        $this->doView('languages/index.php') ;
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>