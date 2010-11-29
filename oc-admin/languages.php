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

require_once 'oc-load.php';


$localeManager = Locale::newInstance();
$action = osc_readAction();
switch ($action) {
    case 'add':
        osc_renderAdminSection('languages/add.php', __('Languages'), __('Upload'));
    break;
    case 'add_post':
        $path = TRANSLATIONS_PATH . pathinfo($_FILES['package']['name'], PATHINFO_FILENAME);
        if(osc_packageExtract($_FILES['package']['tmp_name'], $path)) {
            osc_addFlashMessage(__('The language has been installed correctly.'));
        } else {
            osc_addFlashMessage(__('There was a problem adding the language. Please, try again. If the problem persist, contact the developer of the package or install it manually via FTP/SSH.'));
        }
        osc_redirectTo('languages.php');
    break;
    case 'edit':
        $locale = Locale::newInstance()->findByPrimaryKey($_GET['id']);
        osc_renderAdminSection('languages/frm.php', __('Languages'), __('Edit'));
    break;
    case 'edit_post':
        $code = $_POST['pk_c_code'];
        unset($_POST['pk_c_code']);

        if (!isset($_POST['b_enabled']))
            $_POST['b_enabled'] = DB_CONST_FALSE;
        if (!isset($_POST['b_enabled_bo']))
            $_POST['b_enabled_bo'] = DB_CONST_FALSE;

        Locale::newInstance()->update($_POST, array('pk_c_code' => $code));

        osc_redirectTo('languages.php');
    break;
    case 'enable':
    case 'enable_bo':
        $id = osc_paramRequest('id', false);
        $enabled = osc_paramRequest('enabled', false);
        try {
            if ($id) {
                switch($action) {
                    case('enable'):     $msg = ($enabled == 1) ? __('The language has been enabled for the public website.') : __('The language has been disabled for the public website.') ;
                                        $aValues = array('b_enabled' => $enabled) ;
                    break;
                    case('enable_bo'):  $msg = ($enabled == 1) ? __('The language has been enabled for the backoffice (oc-admin).') : __('The language has been disabled for the backoffice (oc-admin).');
                                        $aValues = array('b_enabled_bo' => $enabled) ;
                    break;
                }

                $localeManager->update($aValues, array('pk_c_code' => $id));
                osc_addFlashMessage( $msg );
            } else {
                osc_addFlashMessage(__('There was a problem updating the language. The ID of the language was lost.'));
            }
            
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('languages.php');
    break;
    case 'enable_selected':
    case 'disable_selected':
    case 'enable_bo_selected':
    case 'disable_bo_selected':
        $id = osc_paramRequest('id', false);
        try {
            if ($id) {
                switch($action) {
                    case('enable_selected'):    $msg = __('Selected languages have been enabled for the website.') ;
                                                $aValues = array('b_enabled' => 1) ;
                    break;
                    case('disable_selected'):   $msg = __('Selected languages have been disabled for the website.') ;
                                                $aValues = array('b_enabled' => 0) ;
                    break;
                    case('enable_bo_selected'): $msg = __('Selected languages have been enabled for the backoffice (oc-admin).') ;
                                                $aValues = array('b_enabled_bo' => 1) ;
                    break;
                    case('disable_bo_selected'):$msg = __('Selected languages have been disabled for the backoffice (oc-admin).') ;
                                                $aValues = array('b_enabled_bo' => 0) ;
                    break;
                }

                foreach ($id as $i) {
                    $localeManager->update($aValues, array('pk_c_code' => $i));
                }

                osc_addFlashMessage($msg);
            } else {
                osc_addFlashMessage(__('There was a problem updating the languages. The IDs of the languages were lost.'));
            }
            
        } catch (Exception $e) {
            osc_addFlashMessage(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('languages.php');
    break;
    case 'delete':
        if (isset($_GET['code']) && is_array($_GET['code'])) {
            $default_lang = Preference::newInstance()->findValueByName('language');
            foreach ($_GET['code'] as $code) {
                if($default_lang!=$code) {
                    if (!osc_deleteDir(TRANSLATIONS_PATH . $code)) {
                        osc_addFlashMessage(__('Directory "%s" could not be removed.'), $code);
                    }
                } else {
                        osc_addFlashMessage(__('Directory "%s" could not be removed, it\' the default language. Set another language as default first and try again.'), $code);
                }
            }
        }
        osc_redirectTo('languages.php');
    break;
    default:
        osc_checkLocales();
        $locales = Locale::newInstance()->listAll();
        osc_renderAdminSection('languages/index.php', __('Languages'));
}

?>
