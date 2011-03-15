<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminTools extends AdminSecBaseModel
    {
        function __construct() {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel() {

            switch ($this->action) {
                case 'import':          // calling import view
                                        $this->doView('tools/import.php');
                break;
                case 'import_post':     // calling
                                        $sql = Params::getFiles('sql') ;
                                        //dev.conquer: if the file es too big, we can have problems with the upload or with memory
                                        $content_file = file_get_contents($sql['tmp_name']) ;

                                        $conn = getConnection() ;
                                        if ( $conn->osc_dbImportSQL($content_file) ) {
                                            osc_add_flash_message( _m('Import complete'), 'admin') ;
                                        } else {
                                            osc_add_flash_message( _m('There was a problem importing data to the database'), 'admin') ;
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=import') ;
                break;
                case 'images':          // calling images view
                                        $this->doView('tools/images.php') ;
                break;
                case 'images_post':
                                        $preferences = Preference::newInstance()->toArray() ;

                                        $path = osc_base_path() . 'oc-content/uploads' ;
                                        $dir = opendir($path) ;
                                        while($file = readdir($dir)) {

                                            if(preg_match('|([0-9]+)_thumbnail\.png|i', $file, $matches)) {

                                                $orig_file = str_replace('_thumbnail.', '_original.', $file) ;
                                                $tmpName = osc_base_path() . 'oc-content/uploads/' . $orig_file ;
                                                if(!file_exists($orig_file)) {
                                                    copy(str_replace('_original.', '.', $tmpName), $tmpName) ;
                                                }

                                                // Create thumbnail
                                                $thumbnailPath = osc_base_path() . 'oc-content/uploads/' . $file ;
                                                $size = explode('x', osc_thumbnail_dimensions()) ;
                                                ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                                                // Create preview
                                                $thumbnailPath = osc_base_path() . 'oc-content/uploads/'.str_replace('_thumbnail.', '_preview.', $file) ;
                                                $size = explode('x', osc_preview_dimensions()) ;
                                                ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                                                // Create normal size
                                                $thumbnailPath = osc_base_path() . 'oc-content/uploads/'.str_replace('_thumbnail.', '.', $file) ;
                                                $size = explode('x', osc_normal_dimensions()) ;
                                                ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;

                                                if(!osc_keep_original_image()) {
                                                    @unlink($tmpName) ;
                                                }

                                            }

                                        }
                                        closedir($dir) ;
                                        osc_add_flash_message( _m('Re-generation complete'), 'admin') ;
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=tools&action=images') ;
                break;
                case 'upgrade':
                                        $this->doView('tools/upgrade.php') ;
                break;
                case 'backup':
                                        $this->doView('tools/backup.php') ;
                break;
                case 'backup-sql':      //databasse dump...
                                        if( Params::getParam('bck_dir') != '' ) {
                                            $path = trim( Params::getParam('bck_dir') ) ;
                                            if(substr($path, -1, 1) != "/") {
                                                 $path .= '/' ;
                                            }
                                        } else {
                                            $path = osc_base_path() ;
                                        }
                                        $filename = 'OSClass_mysqlbackup.' . date('YmdHis') . '.sql' ;

                                        switch ( osc_dbdump($path, $filename) ) {
                                            case(-1):   $msg = _m('Path is empty') ;
                                            break;
                                            case(-2):   $msg = _m('Could not connect with the database') . '. Error: ' . mysql_error() ;
                                            break;
                                            case(-3):   $msg = _m('Could not select the database') . '. Error: ' . mysql_error() ;
                                            break;
                                            case(-4):   $msg = _m('There are no tables to back up') ;
                                            break;
                                            case(-5):   $msg = _m('The folder is not writable') ;
                                            break;
                                            default:    $msg = _m('Backup has been done properly') ;
                                            break;
                                        }
                                        osc_add_flash_message( $msg, 'admin') ;
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=backup' ) ;
                break;
                case 'backup-zip':      //zip of the code just to back it up
                                        if( Params::getParam('bck_dir') != '' ) {
                                            $archive_name = trim( Params::getParam('bck_dir') ) ;
                                            if(substr(trim($archive_name), -1, 1) != "/") {
                                                 $archive_name .= '/' ;
                                            }
                                            $archive_name = Params::getParam('bck_dir') . '/OSClass_backup.' . date('YmdHis') . '.zip' ;
                                        } else {
                                            $archive_name = osc_base_path() . "OSClass_backup." . date('YmdHis') . ".zip" ;
                                        }
                                        $archive_folder = osc_base_path() ;

                                        if ( osc_zipFolder($archive_folder, $archive_name) ) {
                                            $msg = _m('Archiving successful!') ;
                                        }else{
                                            $msg = _m('Error, the zip file was not created at the specified directory') ;
                                        }
                                        osc_add_flash_message( $msg, 'admin') ;
                                        $this->redirectTo( osc_admin_base_url(true) . '?page=tools&action=backup' ) ;
                break;
                case 'backup_post':
                                        $this->doView('tools/backup.php');
                break;
                default:
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
        }
    }

?>