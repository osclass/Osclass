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

    class CAdminMedia extends AdminSecBaseModel
    {
        private $resourcesManager;

        function __construct()
        {
            parent::__construct();

            //specific things for this class
            $this->resourcesManager = ItemResource::newInstance();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            //specific things for this class
            switch($this->action) {
                case('bulk_actions'):
                                        osc_csrf_check();
                                        switch ( Params::getParam('bulk_actions') ) {
                                            case 'delete':
                                                $ids = Params::getParam("id");
                                                if( is_array($ids) ) {
                                                    foreach($ids as $id) {
                                                        osc_deleteResource( $id , true);
                                                    }
                                                    $log_ids = substr(implode(",",$ids),0, 250);
                                                    Log::newInstance()->insertLog('media', 'delete bulk', $log_ids, $log_ids, 'admin', osc_logged_admin_id());
                                                    $this->resourcesManager->deleteResourcesIds($ids);
                                                }
                                                osc_add_flash_ok_message( _m('Resource deleted'), 'admin');
                                            break;
                                            default:

                                                if(Params::getParam("bulk_actions")!="") {
                                                    osc_run_hook("media_bulk_".Params::getParam("bulk_actions"), Params::getParam('id'));
                                                }

                                            break;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=media' );
                break;
                case('delete'):
                                        osc_csrf_check();
                                        $ids = Params::getParam('id');
                                        if( is_array($ids) ) {
                                            foreach($ids as $id) {
                                                osc_deleteResource( $id , true);
                                            }
                                            $log_ids = substr(implode(",",$ids),0, 250);
                                            Log::newInstance()->insertLog('media', 'delete', $log_ids, $log_ids, 'admin', osc_logged_admin_id());
                                            $this->resourcesManager->deleteResourcesIds($ids);
                                        }
                                        osc_add_flash_ok_message( _m('Resource deleted'), 'admin' );
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=media' );
                break;
                default:
                                        require_once osc_lib_path()."osclass/classes/datatables/MediaDataTable.php";

                                        // set default iDisplayLength
                                        if( Params::getParam('iDisplayLength') != '' ) {
                                            Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                            Cookie::newInstance()->set();
                                        } else {
                                            // set a default value if it's set in the cookie
                                            if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                            } else {
                                                Params::setParam('iDisplayLength', 10 );
                                            }
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

                                        $mediaDataTable = new MediaDataTable();
                                        $mediaDataTable->table($params);
                                        $aData = $mediaDataTable->getData();

                                        if(count($aData['aRows']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                                            $url = osc_admin_base_url(true).'?'.Params::getServerParam('QUERY_STRING', false, false);

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
                                        $this->_exportVariableToView('aRawRows', $mediaDataTable->rawRows());

                                        $bulk_options = array(
                                            array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                                            array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected media files?'), strtolower(__('Delete'))), 'label' => __('Delete'))
                                        );
                                        $bulk_options = osc_apply_filter("media_bulk_filter", $bulk_options);
                                        $this->_exportVariableToView('bulk_options', $bulk_options);

                                        $this->doView('media/index.php');
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

    /* file end: ./oc-admin/media.php */
?>