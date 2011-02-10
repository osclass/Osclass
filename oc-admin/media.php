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


class CAdminMedia extends AdminSecBaseModel
{
    private $resourcesManager ;

    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->resourcesManager = ItemComment::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {
            case 'config':          //calling the view...
                                    $this->add_css('demo_table.css') ;
                                    $this->doView('media/config.php') ;
            break;
            case 'config_post':     unset($_POST['action']) ;
                                    if(!isset($_POST['keep_original_image'])) {
                                        $_POST['keep_original_image'] = 0 ;
                                    }

                                    foreach($_POST as $k => $v) {
                                        Preference::newInstance()->update(
                                            array('s_value' => $v)
                                            ,array('s_name' => $k)
                                        ) ;
                                    }

                                    //calling the view...
                                    $this->add_css('demo_table.css') ;
                                    $this->doView('media/config.php') ;
            break ;
            case 'delete':          if(isset($_REQUEST['id']) && is_array($_REQUEST['id'])) {
                                        $resourcesManager->delete(array(
                                            DB_CUSTOM_COND => 'pk_i_id IN (' . implode(', ', $_REQUEST['id']). ')'
                                        ));
                                    }
                                    $this->redirectTo( osc_admin_base_url(true) . "?page=media" ) ;
            break;
            default:                $resourceId = null;
                                    if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
                                        $resourceId = $_GET['id'];

                                    !is_null($resourceId) ? $resources = $resourcesManager->getAllResources($resourceId) :	$resources = $resourcesManager->getAllResources();

                                    //calling the view...
                                    $this->add_global_js('jquery.dataTables.min.js') ;
                                    $this->add_css('demo_table.css') ;
                                    $this->doView('media/index.php') ;
                
        }
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>