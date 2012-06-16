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

    class CAdminMedia extends AdminSecBaseModel
    {
        private $resourcesManager ;

        function __construct()
        {
            parent::__construct() ;

            //specific things for this class
            $this->resourcesManager = ItemResource::newInstance() ;
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel() ;

            //specific things for this class
            switch($this->action) {
                case('bulk_actions'):
                                        switch ( Params::getParam('bulk_actions') ) {
                                            case 'delete_all':
                                                $ids = Params::getParam("id");
                                                if( is_array($ids) ) {
                                                    foreach($ids as $id) {
                                                        osc_deleteResource( $id , true);
                                                    }
                                                    $log_ids = substr(implode(",",$ids),0, 250);
                                                    Log::newInstance()->insertLog('media', 'delete bulk', $log_ids, $log_ids, 'admin', osc_logged_admin_id()) ;
                                                    $this->resourcesManager->deleteResourcesIds($ids);
                                                }
                                                osc_add_flash_ok_message( _m('Resource deleted'), 'admin') ;
                                            break ;
                                            default:
                                            break ;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=media' ) ;
                break;
                case('delete'):
                                        $ids = Params::getParam('id') ;
                                        if( is_array($ids) ) {
                                            foreach($ids as $id) {
                                                osc_deleteResource( $id , true);
                                            }
                                            $log_ids = substr(implode(",",$ids),0, 250);
                                            Log::newInstance()->insertLog('media', 'delete', $log_ids, $log_ids, 'admin', osc_logged_admin_id()) ;
                                            $this->resourcesManager->deleteResourcesIds($ids);
                                        }
                                        osc_add_flash_ok_message( _m('Resource deleted'), 'admin' ) ;
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=media' ) ;
                break ;
                default:
                                        if( Params::getParam('iDisplayLength') == '' ) {
                                            Params::setParam('iDisplayLength', 10 ) ;
                                        }
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));
                                        
                                        require_once osc_admin_base_path() . 'ajax/media_processing.php';
                                        $params = Params::getParamsAsArray("get") ;
                                        $media_processing = new MediaProcessingAjax( $params );
                                        $aData = $media_processing->result( $params ) ;
                                        
                                        $page  = (int)Params::getParam('iPage');
                                        if(count($aData['aaData']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] ) ;

                                            $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url) ;
                                                $this->redirectTo($url) ;
                                            }

                                            if($page > 1) {   
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url) ;
                                                $this->redirectTo($url) ;
                                            }
                                        }
                                        
                                        $this->_exportVariableToView('aMedia', $aData) ;
                                        
                                        $this->doView('media/index.php') ;
                break ;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables() ;
        }
    }

    /* file end: ./oc-admin/media.php */
?>