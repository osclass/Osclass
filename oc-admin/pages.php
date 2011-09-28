<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CAdminPages extends AdminSecBaseModel
    {
        //specific for this class
        private $pageManager ;

        function __construct() {
            parent::__construct() ;

            //specific things for this class
            $this->pageManager = Page::newInstance() ;
        }

        //Business Layer...
        function doModel() {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action)
            {

                case 'edit':
                    if(Params::getParam("id")=='') {
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                    }
                    $this->_exportVariableToView("page", $this->pageManager->findByPrimaryKey(Params::getParam("id")));
                    $this->doView("pages/frm.php");
                    break;
                case 'edit_post':
                    $id = Params::getParam("id");
                    $s_internal_name = Params::getParam("s_internal_name");

                    if( !WebThemes::newInstance()->isValidPage($s_internal_name) ) {
                        osc_add_flash_error_message( _m('You have to set a different internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages?action=edit&id=" . $id);
                    }

                    $aFieldsDescription = array();
                    $postParams = Params::getParamsAsArray();
                    $not_empty = false;
                    foreach ($postParams as $k => $v) {
                        if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                            if($m[2]=='s_title' && $v!='') { $not_empty = true; };
                            $aFieldsDescription[$m[1]][$m[2]] = $v;
                        }
                    }

                    if($not_empty) {
                        foreach($aFieldsDescription as $k => $_data) {
                            $this->pageManager->updateDescription($id, $k, $_data['s_title'], $_data['s_text']);
                        }

                        if(!$this->pageManager->internalNameExists($id, $s_internal_name)) {
                            if(!$this->pageManager->isIndelible($id)) {
                                $this->pageManager->updateInternalName($id, $s_internal_name);
                            }
                            osc_add_flash_ok_message( _m('The page has been updated'), 'admin' );
                            $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                        }
                        osc_add_flash_error_message( _m('You can\'t repeat internal name'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m('The page couldn\'t be updated, at least one title should not be empty'), 'admin') ;
                    }
                    $this->redirectTo(osc_admin_base_url(true)."?page=pages?action=edit&id=" . $id);
                    break;
                case 'add':
                    $this->_exportVariableToView("page", array());
                    $this->doView("pages/frm.php");
                    break;
                case 'add_post':
                    // setForm just in case the form fails
                    foreach (Params::getParamsAsArray() as $k => $v) {
                        Session::newInstance()->_setForm($k, $v);
                    }

                    $s_internal_name = Params::getParam("s_internal_name");
                    if($s_internal_name=='') {
                        osc_add_flash_error_message( _m('You have to set an internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=add");
                    }

                    if( !WebThemes::newInstance()->isValidPage($s_internal_name) ) {
                        osc_add_flash_error_message( _m('You have to set a different internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=add");
                    }

                    $page = $this->pageManager->findByInternalName($s_internal_name);
                    if(!isset($page['pk_i_id'])) {
                        $aFields = array('s_internal_name' => $s_internal_name, 'b_indelible' => '0');
                        $aFieldsDescription = array();
                        $postParams = Params::getParamsAsArray();
                        $not_empty = false;
                        foreach ($postParams as $k => $v) {
                            if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                                if($m[2]=='s_title' && $v!='') {
                                    $not_empty = true;
                                }
                                $aFieldsDescription[$m[1]][$m[2]] = $v;
                            }
                        }
                        if($not_empty) {
                            $result = $this->pageManager->insert($aFields, $aFieldsDescription) ;
                            osc_add_flash_ok_message( _m('The page has been added'), 'admin') ;
                        } else {
                            osc_add_flash_error_message( _m('The page couldn\'t be added, at least one title should not be empty'), 'admin') ;
                        }
                    } else {
                        osc_add_flash_error_message( _m('Oops! That internal name is already in use. We can\'t made the changes'), 'admin') ;
                    }
                    $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                    break;
                case 'delete':
                    $id = Params::getParam("id");
                    $page_deleted_correcty = 0;
                    $page_deleted_error = 0;
                    $page_indelible = 0;

                    if(!is_array($id)) {
                        $id = array($id);
                    }

                    foreach($id as $_id) {
                        $result = (int) $this->pageManager->deleteByPrimaryKey($_id);
                        switch ($result) {
                            case -1:
                                $page_indelible++;
                                break;
                            case 0:
                                $page_deleted_error++;
                                break;
                            case 1:
                                $page_deleted_correcty++;
                        }
                    }

                    if($page_indelible > 0) {
                        if($page_indelible == 1) {
                            osc_add_flash_error_message( _m('One page can\'t be deleted because it is indelible'), 'admin');
                        } else {
                            osc_add_flash_error_message($page_indelible . ' ' ._m('pages couldn\'t be deleted because are indelible'), 'admin');
                        }
                    }
                    if($page_deleted_error > 0) {
                        if($page_deleted_error == 1) {
                            osc_add_flash_error_message( _m('One page couldn\'t be deleted'), 'admin');
                        } else {
                            osc_add_flash_error_message($page_deleted_error . ' ' ._m('pages couldn\'t be deleted'), 'admin');
                        }
                    }
                    if($page_deleted_correcty > 0) {
                        if($page_deleted_correcty == 1) {
                            osc_add_flash_ok_message( _m('One page has been deleted correctly'), 'admin');
                        } else {
                            osc_add_flash_ok_message($page_deleted_correcty . ' ' ._m('pages have been deleted correctly'), 'admin');
                        }
                    }
                    $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                    break;


                default:
                    $this->_exportVariableToView("prefLocale", osc_current_admin_locale());
                    $this->_exportVariableToView("pages", $this->pageManager->listAll(0));
                    $this->doView("pages/index.php");

            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }
    }
?>