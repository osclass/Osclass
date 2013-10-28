<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    class CAdminTools extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();

            switch($this->action) {
                case('import'):         // calling import view
                                        $this->doView('tools/import.php');
                break;
                case('import_post'):    if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=import');
                                        }
                                        // calling
                                        osc_csrf_check();
                                        $sql = Params::getFiles('sql');
                                        if( isset($sql['size']) && $sql['size'] != 0 ) {
                                            $content_file = file_get_contents($sql['tmp_name']);

                                            $conn = DBConnectionClass::newInstance();
                                            $c_db = $conn->getOsclassDb();
                                            $comm = new DBCommandClass($c_db);
                                            if ( $comm->importSQL($content_file) ) {
                                                osc_calculate_location_slug(osc_subdomain_type());
                                                osc_add_flash_ok_message( _m('Import complete'), 'admin');
                                            } else {
                                                osc_add_flash_error_message( _m('There was a problem importing data to the database'), 'admin');
                                            }
                                        } else {
                                            osc_add_flash_warning_message( _m('No file was uploaded'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=import');
                break;
                case('images'):         // calling images view
                                        $this->doView('tools/images.php');
                break;
                case('images_post'):    if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=images');
                                        }

                                        osc_csrf_check();
                                        $preferences = Preference::newInstance()->toArray();

                                        $wat = new Watermark();
                                        $aResources = ItemResource::newInstance()->getAllResources();
                                        foreach($aResources as $resource) {
                                            osc_run_hook('regenerate_image', $resource);

                                            $path = osc_uploads_path();
                                            // comprobar que no haya original
                                            $img_original = $path . $resource['pk_i_id']. "_original*";
                                            $aImages = glob($img_original);
                                            // there is original image
                                            if( count($aImages) == 1 ) {
                                                $image_tmp = $aImages[0];
                                            } else {
                                                $img_normal = $path . $resource['pk_i_id']. ".*";
                                                $aImages = glob( $img_normal );
                                                if( count($aImages) == 1 ) {
                                                    $image_tmp = $aImages[0];
                                                } else {
                                                    $img_thumbnail = $path . $resource['pk_i_id']. "_thumbnail*";
                                                    $aImages = glob( $img_thumbnail );
                                                    $image_tmp = $aImages[0];
                                                }
                                            }

                                            // extension
                                            preg_match('/\.(.*)$/', $image_tmp, $matches);
                                            if( isset($matches[1]) ) {
                                                $extension = $matches[1];

                                                // Create normal size
                                                $path_normal = $path = osc_uploads_path() . $resource['pk_i_id'] . '.jpg';
                                                $size = explode('x', osc_normal_dimensions());
                                                ImageResizer::fromFile($image_tmp)->resizeTo($size[0], $size[1])->saveToFile($path);

                                                if( osc_is_watermark_text() ) {
                                                    $wat->doWatermarkText( $path , osc_watermark_text_color(), osc_watermark_text() , 'image/jpeg' );
                                                } elseif ( osc_is_watermark_image() ){
                                                    $wat->doWatermarkImage( $path, 'image/jpeg');
                                                }

                                                // Create preview
                                                $path = osc_uploads_path() . $resource['pk_i_id'] . '_preview.jpg';
                                                $size = explode('x', osc_preview_dimensions());
                                                ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);

                                                // Create thumbnail
                                                $path = osc_uploads_path() . $resource['pk_i_id'] . '_thumbnail.jpg';
                                                $size = explode('x', osc_thumbnail_dimensions());
                                                ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);

                                                // update resource info
                                                ItemResource::newInstance()->update(
                                                    array(
                                                        's_path'          => str_replace(osc_base_path(), '', osc_uploads_path())
                                                        ,'s_name'         => osc_genRandomPassword()
                                                        ,'s_extension'    => 'jpg'
                                                        ,'s_content_type' => 'image/jpeg'
                                                    )
                                                    ,array(
                                                        'pk_i_id'       => $resource['pk_i_id']
                                                    )
                                                );
                                                osc_run_hook('regenerated_image', ItemResource::newInstance()->findByPrimaryKey($resource['pk_i_id']));
                                                // si extension es direfente a jpg, eliminar las imagenes con $extension si hay
                                                if( $extension != 'jpg' ) {
                                                    $files_to_remove = osc_uploads_path() . $resource['pk_i_id'] . "*" . $extension;
                                                    $fs = glob( $files_to_remove );
                                                    if(is_array($fs)) {
                                                        array_map( "unlink", $fs );
                                                    }
                                                }
                                                // ....
                                            } else {
                                                // no es imagen o imagen sin extesión
                                            }

                                        }

                                        osc_add_flash_ok_message( _m('Re-generation complete'), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=images');
                break;
                case('category'):       $this->doView('tools/category.php');
                break;
                case('category_post'):  if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=category');
                                        }
                                        osc_update_cat_stats();
                                        osc_add_flash_ok_message(_m("Recount category stats has been successful"), 'admin');
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=category');
                break;
                case('locations'):      $this->doView('tools/locations.php');
                break;
                case('locations_post'): if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=locations');
                                        }

                                        osc_update_location_stats(true);

                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=locations' );
                break;
                case('upgrade'):
                                        $this->doView('tools/upgrade.php');
                break;
                case('backup'):
                                        $this->doView('tools/backup.php');
                break;
                case('backup-sql'):     if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=backup');
                                        }
                                        osc_csrf_check();
                                        //databasse dump...
                                        if( Params::getParam('bck_dir') != '' ) {
                                            $path = trim( Params::getParam('bck_dir') );
                                            if(substr($path, -1, 1) != "/") {
                                                 $path .= '/';
                                            }
                                        } else {
                                            $path = osc_base_path();
                                        }
                                        $filename = 'Osclass_mysqlbackup.' . date('YmdHis') . '.sql';

                                        switch ( osc_dbdump($path, $filename) ) {
                                            case(-1):   $msg = _m('Path is empty');
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-2):   $msg = sprintf(_m('Could not connect with the database. Error: %s'), mysql_error());
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-3):   $msg = _m('There are no tables to back up');
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-4):   $msg = _m('The folder is not writable');
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            default:    $msg = _m('Backup completed successfully');
                                                        osc_add_flash_ok_message( $msg, 'admin');
                                            break;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=backup' );
                break;
                case('backup-sql_file'):
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=backup');
                                        }
                                        //databasse dump...

                                        $filename = 'Osclass_mysqlbackup.' . date('YmdHis') . '.sql';
                                        $path = sys_get_temp_dir()."/";

                                        switch ( osc_dbdump($path, $filename) ) {
                                            case(-1):   $msg = _m('Path is empty');
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-2):   $msg = sprintf(_m('Could not connect with the database. Error: %s'), mysql_error());
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-3):   $msg = sprintf(_m('Could not select the database. Error: %s'), mysql_error());
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-4):   $msg = _m('There are no tables to back up');
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            case(-5):   $msg = _m('The folder is not writable');
                                                        osc_add_flash_error_message( $msg, 'admin');
                                            break;
                                            default:    $msg = _m('Backup completed successfully');
                                                        osc_add_flash_ok_message( $msg, 'admin');
                                                        header('Content-Description: File Transfer');
                                                        header('Content-Type: application/octet-stream');
                                                        header('Content-Disposition: attachment; filename='.basename($filename));
                                                        header('Content-Transfer-Encoding: binary');
                                                        header('Expires: 0');
                                                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                                                        header('Pragma: public');
                                                        header('Content-Length: ' . filesize($path.$filename));
                                                        flush();
                                                        readfile($path.$filename);
                                                        exit;
                                            break;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=backup' );
                break;
                case('backup-zip_file'):
                                        if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=backup');
                                        }
                                        $filename = "Osclass_backup." . date('YmdHis') . ".zip";
                                        $path = sys_get_temp_dir()."/";

                                        if ( osc_zip_folder(osc_base_path(),$path. $filename) ) {
                                            $msg = _m('Archived successfully!');
                                            osc_add_flash_ok_message( $msg, 'admin');
                                            header('Content-Description: File Transfer');
                                            header('Content-Type: application/octet-stream');
                                            header('Content-Disposition: attachment; filename='.basename($filename));
                                            header('Content-Transfer-Encoding: binary');
                                            header('Expires: 0');
                                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                                            header('Pragma: public');
                                            header('Content-Length: ' . filesize($path.$filename));
                                            flush();
                                            readfile($path.$filename);
                                            exit;
                                        }else{
                                            $msg = _m('Error, the zip file was not created in the specified directory');
                                            osc_add_flash_error_message( $msg, 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=backup' );
                break;
                case('backup-zip'):     if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=backup');
                                        }
                                        //zip of the code just to back it up
                                        osc_csrf_check();
                                        if( Params::getParam('bck_dir') != '' ) {
                                            $archive_name = trim( Params::getParam('bck_dir') );
                                            if(substr(trim($archive_name), -1, 1) != "/") {
                                                 $archive_name .= '/';
                                            }
                                            $archive_name = Params::getParam('bck_dir') . '/Osclass_backup.' . date('YmdHis') . '.zip';
                                        } else {
                                            $archive_name = osc_base_path() . "Osclass_backup." . date('YmdHis') . ".zip";
                                        }
                                        $archive_folder = osc_base_path();

                                        if ( osc_zip_folder($archive_folder, $archive_name) ) {
                                            $msg = _m('Archived successfully!');
                                            osc_add_flash_ok_message( $msg, 'admin');
                                        }else{
                                            $msg = _m('Error, the zip file was not created in the specified directory');
                                            osc_add_flash_error_message( $msg, 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=backup' );
                break;
                case('backup_post'):
                                        $this->doView('tools/backup.php');
                break;
                case('maintenance'):    if( defined('DEMO') ) {
                                            osc_add_flash_warning_message( _m("This action cannot be done because it is a demo site"), 'admin');
                                            $this->doView('tools/maintenance.php');
                                            break;
                                        }
                                        $mode = Params::getParam('mode');
                                        if( $mode == 'on' ) {
                                            osc_csrf_check();
                                            $maintenance_file = osc_base_path() . '.maintenance';
                                            $fileHandler = @fopen($maintenance_file, 'w');
                                            if( $fileHandler ) {
                                                osc_add_flash_ok_message( _m('Maintenance mode is ON'), 'admin');
                                            } else {
                                                osc_add_flash_error_message( _m('There was an error creating the .maintenance file, please create it manually at the root folder'), 'admin');
                                            }
                                            fclose($fileHandler);
                                            $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=maintenance' );
                                        } else if( $mode == 'off' ) {
                                            osc_csrf_check();
                                            $deleted = @unlink(osc_base_path() . '.maintenance');
                                            if( $deleted ) {
                                                osc_add_flash_ok_message( _m('Maintenance mode is OFF'), 'admin');
                                            } else {
                                                osc_add_flash_error_message( _m('There was an error removing the .maintenance file, please remove it manually from the root folder'), 'admin');
                                            }
                                            $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=maintenance' );
                                        }
                                        $this->doView('tools/maintenance.php');
                break;
                default:
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

    /* file end: ./oc-admin/tools.php */
?>