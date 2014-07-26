<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    class CAdminPages extends AdminSecBaseModel
    {
        //specific for this class
        private $pageManager;

        function __construct()
        {
            parent::__construct();

            //specific things for this class
            $this->pageManager = Page::newInstance();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch($this->action) {
                case 'edit':
                    if(Params::getParam("id")=='') {
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                    }

                    $form     = count(Session::newInstance()->_getForm());
                    $keepForm = count(Session::newInstance()->_getKeepForm());
                    if($form == 0 || $form == $keepForm) {
                        Session::newInstance()->_dropKeepForm();
                    }

                    $templates = osc_apply_filter('page_templates', WebThemes::newInstance()->getAvailableTemplates());
                    $this->_exportVariableToView('templates', $templates);
                    $this->_exportVariableToView("page", $this->pageManager->findByPrimaryKey(Params::getParam("id")));
                    $this->doView("pages/frm.php");
                    break;
                case 'edit_post':
                    osc_csrf_check();
                    $id = Params::getParam("id");
                    $b_link = (Params::getParam("b_link") != '') ? 1 : 0;
                    $s_internal_name = Params::getParam("s_internal_name");
                    $s_internal_name = osc_sanitizeString($s_internal_name);

                    $meta = Params::getParam('meta');
                    $this->pageManager->updateMeta($id, json_encode($meta));

                    $aFieldsDescription = array();
                    $postParams = Params::getParamsAsArray('', false);
                    $not_empty = false;
                    foreach ($postParams as $k => $v) {
                        if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                            if($m[2]=='s_title' && $v!='') { $not_empty = true; };
                            $aFieldsDescription[$m[1]][$m[2]] = $v;
                        }
                    }
                    Session::newInstance()->_setForm('aFieldsDescription',$aFieldsDescription);

                    if( $s_internal_name == '' ) {
                        osc_add_flash_error_message(_m('You have to set an internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=edit&id=" . $id);
                    }

                    if( !WebThemes::newInstance()->isValidPage($s_internal_name) ) {
                        osc_add_flash_error_message(_m('You have to set a different internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=edit&id=" . $id);
                    }
                    Session::newInstance()->_setForm('s_internal_name',$s_internal_name);

                    if($not_empty) {
                        foreach($aFieldsDescription as $k => $_data) {
                            $this->pageManager->updateDescription($id, $k, $_data['s_title'], $_data['s_text']);
                        }

                        if(!$this->pageManager->internalNameExists($id, $s_internal_name)) {
                            if(!$this->pageManager->isIndelible($id)) {
                                $this->pageManager->updateInternalName($id, $s_internal_name);
                                $this->pageManager->updateLink($id,$b_link);
                            }
                            osc_run_hook('edit_page', $id);
                            Session::newInstance()->_clearVariables();
                            osc_add_flash_ok_message(_m('The page has been updated'), 'admin');
                            $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                        }
                        osc_add_flash_error_message(_m("You can't repeat internal name"), 'admin');
                    } else {
                        osc_add_flash_error_message(_m("The page couldn't be updated, at least one title should not be empty"), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=edit&id=" . $id);
                    break;
                case 'add':
                    $form     = count(Session::newInstance()->_getForm());
                    $keepForm = count(Session::newInstance()->_getKeepForm());
                    if($form == 0 || $form == $keepForm) {
                        Session::newInstance()->_dropKeepForm();
                    }

                    $templates = osc_apply_filter('page_templates', WebThemes::newInstance()->getAvailableTemplates());
                    $this->_exportVariableToView('templates', $templates);
                    $this->_exportVariableToView("page", array());
                    $this->doView("pages/frm.php");
                    break;
                case 'add_post':
                    osc_csrf_check();
                    $s_internal_name = Params::getParam("s_internal_name");
                    $b_link = (Params::getParam("b_link") != '') ? 1 : 0;
                    $s_internal_name = osc_sanitizeString($s_internal_name);

                    $meta = Params::getParam('meta');

                    $aFieldsDescription = array();
                    $postParams = Params::getParamsAsArray('', false);
                    $not_empty = false;
                    foreach($postParams as $k => $v) {
                        if(preg_match('|(.+?)#(.+)|', $k, $m)) {
                            if($m[2]=='s_title' && $v!='') {
                                $not_empty = true;
                            }
                            $aFieldsDescription[$m[1]][$m[2]] = $v;
                        }
                    }
                    Session::newInstance()->_setForm('aFieldsDescription',$aFieldsDescription);

                    if( $s_internal_name == '' ) {
                        osc_add_flash_error_message(_m('You have to set an internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=add");
                    }

                    if( !WebThemes::newInstance()->isValidPage($s_internal_name) ) {
                        osc_add_flash_error_message(_m('You have to set a different internal name'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=add");
                    }
                    $aFields = array('s_internal_name' => $s_internal_name, 'b_indelible' => '0', 's_meta' => json_encode($meta), 'b_link' => $b_link);
                    Session::newInstance()->_setForm('s_internal_name',$s_internal_name);

                    $page = $this->pageManager->findByInternalName($s_internal_name);
                    if(!isset($page['pk_i_id'])) {
                        if($not_empty) {
                            $result = $this->pageManager->insert($aFields, $aFieldsDescription);
                            Session::newInstance()->_clearVariables();
                            osc_add_flash_ok_message(_m('The page has been added'), 'admin');
                            $this->redirectTo(osc_admin_base_url(true)."?page=pages");
                        } else {
                            osc_add_flash_error_message(_m("The page couldn't be added, at least one title should not be empty"), 'admin');
                        }
                    } else {
                        osc_add_flash_error_message(_m("Oops! That internal name is already in use. We can't make the changes"), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true)."?page=pages&action=add");
                    break;
                case 'delete':
                    osc_csrf_check();
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
                            osc_add_flash_error_message( _m("One page can't be deleted because it is indelible"), 'admin');
                        } else {
                            osc_add_flash_error_message(sprintf(_m("%s pages couldn't be deleted because they are indelible"), $page_indelible), 'admin');
                        }
                    }
                    if($page_deleted_error > 0) {
                        if($page_deleted_error == 1) {
                            osc_add_flash_error_message(_m("One page couldn't be deleted"), 'admin');
                        } else {
                            osc_add_flash_error_message(sprintf(_m("%s pages couldn't be deleted"), $page_deleted_error), 'admin');
                        }
                    }
                    if($page_deleted_correcty > 0) {
                        if($page_deleted_correcty == 1) {
                            osc_add_flash_ok_message(_m('One page has been deleted correctly'), 'admin');
                        } else {
                            osc_add_flash_ok_message(sprintf(_m('%s pages have been deleted correctly'), $page_deleted_correcty), 'admin');
                        }
                    }
                    $this->redirectTo(osc_admin_base_url(true) . "?page=pages");
                    break;
                default:

                    if(Params::getParam("action")!="") {
                        osc_run_hook("page_bulk_".Params::getParam("action"), Params::getParam('id'));
                    }

                    require_once osc_lib_path()."osclass/classes/datatables/PagesDataTable.php";

                    // set default iDisplayLength
                    if( Params::getParam('iDisplayLength') != '' ) {
                        Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                        Cookie::newInstance()->set();
                    } else {
                        // set a default value if it's set in the cookie
                        $listing_iDisplayLength = (int) Cookie::newInstance()->get_value('listing_iDisplayLength');
                        if ($listing_iDisplayLength == 0) $listing_iDisplayLength = 10;
                        Params::setParam('iDisplayLength', $listing_iDisplayLength );
                    }
                    $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                    // Table header order by related
                    if( Params::getParam('sort') == '') {
                        Params::setParam('sort', 'date');
                    }
                    if( Params::getParam('direction') == '') {
                        Params::setParam('direction', 'desc');
                    }

                    $page  = (int)Params::getParam('iPage');
                    if($page==0) { $page = 1; };
                    Params::setParam('iPage', $page);

                    $params = Params::getParamsAsArray();

                    $pagesDataTable = new PagesDataTable();
                    $pagesDataTable->table($params);
                    $aData = $pagesDataTable->getData();

                    if(count($aData['aRows']) == 0 && $page!=1) {
                        $total = (int)$aData['iTotalDisplayRecords'];
                        $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                        $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                        if($maxPage==0) {
                            $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                            $this->redirectTo($url);
                        }

                        if($page > 1) {
                            $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                            $this->redirectTo($url);
                        }
                    }


                    $this->_exportVariableToView('aData', $aData);
                    $this->_exportVariableToView('aRawRows', $pagesDataTable->rawRows());

                    $bulk_options = array(
                        array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                        array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected pages?'), strtolower(__('Delete'))), 'label' => __('Delete'))
                    );
                    $bulk_options = osc_apply_filter("page_bulk_filter", $bulk_options);
                    $this->_exportVariableToView('bulk_options', $bulk_options);

                    $this->doView("pages/index.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/pages.php */
?>
