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

    class CAdminSettingsMedia extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('media'):
                    // calling the media view
                    $max_upload   = (int)( ini_get('upload_max_filesize') );
                    $max_post     = (int)( ini_get('post_max_size') );
                    $memory_limit = (int)( ini_get('memory_limit') );
                    $upload_mb    = min($max_upload, $max_post, $memory_limit) * 1024;

                    $this->_exportVariableToView('max_size_upload', $upload_mb);
                    $this->doView('settings/media.php');
                break;
                case('media_post'):
                    // updating the media config
                    osc_csrf_check();
                    $status = 'ok';
                    $error  = '';

                    $iUpdated          = 0;
                    $maxSizeKb         = Params::getParam('maxSizeKb');
                    $dimThumbnail      = strtolower(Params::getParam('dimThumbnail'));
                    $dimPreview        = strtolower(Params::getParam('dimPreview'));
                    $dimNormal         = strtolower(Params::getParam('dimNormal'));
                    $keepOriginalImage = Params::getParam('keep_original_image');
                    $forceAspectImage  = Params::getParam('force_aspect_image');
                    $forceJPEG         = Params::getParam('force_jpeg');
                    $use_imagick       = Params::getParam('use_imagick');
                    $type_watermark    = Params::getParam('watermark_type');
                    $watermark_color   = Params::getParam('watermark_text_color');
                    $watermark_text    = Params::getParam('watermark_text');

                    switch ($type_watermark) {
                        case 'none':
                            $iUpdated += osc_set_preference('watermark_text_color', '');
                            $iUpdated += osc_set_preference('watermark_text', '');
                            $iUpdated += osc_set_preference('watermark_image', '');
                        break;
                        case 'text':
                            $iUpdated += osc_set_preference('watermark_text_color', $watermark_color);
                            $iUpdated += osc_set_preference('watermark_text', $watermark_text);
                            $iUpdated += osc_set_preference('watermark_image', '');
                            $iUpdated += osc_set_preference('watermark_place', Params::getParam('watermark_text_place'));
                        break;
                        case 'image':
                            // upload image & move to path
                            $watermark_file = Params::getFiles('watermark_image');
                            if($watermark_file['tmp_name']!='' && $watermark_file['size']>0) {
                                if($watermark_file['error'] == UPLOAD_ERR_OK) {
                                    if($watermark_file['type']=='image/png') {
                                        $tmpName = $watermark_file['tmp_name'];
                                        $path    = osc_content_path().'uploads/watermark.png';
                                        if( move_uploaded_file($tmpName, $path) ){
                                            $iUpdated += osc_set_preference('watermark_image', $path);
                                        } else {
                                            $status = 'error';
                                            $error .= _m('There was a problem uploading the watermark image')."<br />";
                                        }
                                    } else {
                                        $status = 'error';
                                        $error .= _m('The watermark image has to be a .PNG file')."<br />";
                                    }
                                } else {
                                    $status = 'error';
                                    $error .= _m('There was a problem uploading the watermark image')."<br />";
                                }
                            }
                            $iUpdated += osc_set_preference('watermark_text_color', '');
                            $iUpdated += osc_set_preference('watermark_text', '');
                            $iUpdated += osc_set_preference('watermark_place', Params::getParam('watermark_image_place'));
                        break;
                        default:
                        break;
                    }

                    // format parameters
                    $maxSizeKb         = trim(strip_tags($maxSizeKb));
                    $dimThumbnail      = trim(strip_tags($dimThumbnail));
                    $dimPreview        = trim(strip_tags($dimPreview));
                    $dimNormal         = trim(strip_tags($dimNormal));
                    $keepOriginalImage = ($keepOriginalImage != '' ? true : false);
                    $forceAspectImage  = ($forceAspectImage != '' ? true : false);
                    $forceJPEG         = ($forceJPEG != '' ? true : false);
                    $use_imagick       = ($use_imagick != '' ? true : false);

                    if(!preg_match('|([0-9]+)x([0-9]+)|', $dimThumbnail, $match)) {
                        $dimThumbnail = is_numeric($dimThumbnail)?$dimThumbnail."x".$dimThumbnail:"100x100";
                    }
                    if(!preg_match('|([0-9]+)x([0-9]+)|', $dimPreview, $match)) {
                        $dimPreview = is_numeric($dimPreview)?$dimPreview."x".$dimPreview:"100x100";
                    }
                    if(!preg_match('|([0-9]+)x([0-9]+)|', $dimNormal, $match)) {
                        $dimNormal = is_numeric($dimNormal)?$dimNormal."x".$dimNormal:"100x100";
                    }

                    // is imagick extension loaded?
                    if( !@extension_loaded('imagick') ) {
                        $use_imagick = false;
                    }

                    // max size allowed by PHP configuration?
                    $max_upload   = (int)( ini_get('upload_max_filesize') );
                    $max_post     = (int)( ini_get('post_max_size') );
                    $memory_limit = (int)( ini_get('memory_limit') );
                    $upload_mb    = min($max_upload, $max_post, $memory_limit) * 1024;

                    // set maxSizeKB equals to PHP configuration if it's bigger
                    if( $maxSizeKb > $upload_mb ) {
                        $status    = 'warning';
                        $maxSizeKb = $upload_mb;
                        // flash message text warning
                        $error     .= sprintf( _m("You cannot set a maximum file size higher than the one allowed in the PHP configuration: <b>%d KB</b>"), $upload_mb );
                    }

                    $iUpdated += osc_set_preference('maxSizeKb', $maxSizeKb);
                    $iUpdated += osc_set_preference('dimThumbnail', $dimThumbnail);
                    $iUpdated += osc_set_preference('dimPreview', $dimPreview);
                    $iUpdated += osc_set_preference('dimNormal', $dimNormal);
                    $iUpdated += osc_set_preference('keep_original_image', $keepOriginalImage);
                    $iUpdated += osc_set_preference('force_aspect_image', $forceAspectImage);
                    $iUpdated += osc_set_preference('force_jpeg', $forceJPEG);
                    $iUpdated += osc_set_preference('use_imagick', $use_imagick);

                    if( $error != '' ) {
                        switch($status) {
                            case('error'):
                                osc_add_flash_error_message($error, 'admin');
                            break;
                            case('warning'):
                                osc_add_flash_warning_message($error, 'admin');
                            break;
                            default:
                                osc_add_flash_ok_message($error, 'admin');
                            break;
                        }
                    } else {
                        osc_add_flash_ok_message(_m('Media config has been updated'), 'admin');
                    }

                    $this->redirectTo(osc_admin_base_url(true).'?page=settings&action=media');
                break;
                case('images_post'):
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true).'?page=settings&action=media');
                    }
                    osc_csrf_check();

                    $aResources = ItemResource::newInstance()->getAllResources();
                    foreach($aResources as $resource) {
                        osc_run_hook('regenerate_image', $resource);
                        if(strpos($resource['s_content_type'], 'image')!==false) {
                            if(file_exists(osc_base_path().$resource['s_path'].$resource['pk_i_id']."_original.".$resource['s_extension'])) {
                                $image_tmp = osc_base_path().$resource['s_path'].$resource['pk_i_id']."_original.".$resource['s_extension'];
                                $use_original = true;
                            } else if(file_exists(osc_base_path().$resource['s_path'].$resource['pk_i_id'].".".$resource['s_extension'])) {
                                $image_tmp = osc_base_path().$resource['s_path'].$resource['pk_i_id'].".".$resource['s_extension'];
                                $use_original = false;
                            } else if(file_exists(osc_base_path().$resource['s_path'].$resource['pk_i_id']."_preview.".$resource['s_extension'])) {
                                $image_tmp = osc_base_path().$resource['s_path'].$resource['pk_i_id']."_preview.".$resource['s_extension'];
                                $use_original = false;
                            } else {
                                $use_original = false;
                                continue;
                            };

                            // Create normal size
                            $path_normal = $path = osc_base_path().$resource['s_path'].$resource['pk_i_id'].'.'.$resource['s_extension'];
                            $size = explode('x', osc_normal_dimensions());
                            $img = ImageResizer::fromFile($image_tmp)->resizeTo($size[0], $size[1]);
                            if($use_original) {
                                if( osc_is_watermark_text() ) {
                                    $img->doWatermarkText(osc_watermark_text(), osc_watermark_text_color());
                                } elseif ( osc_is_watermark_image() ){
                                    $img->doWatermarkImage();
                                }
                            }
                            $img->saveToFile($path);

                            // Create preview
                            $path = osc_base_path().$resource['s_path'].$resource['pk_i_id'].'_preview.'.$resource['s_extension'];
                            $size = explode('x', osc_preview_dimensions());
                            ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);

                            // Create thumbnail
                            $path = osc_base_path().$resource['s_path'].$resource['pk_i_id'].'_thumbnail.'.$resource['s_extension'];
                            $size = explode('x', osc_thumbnail_dimensions());
                            ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);

                            osc_run_hook('regenerated_image', ItemResource::newInstance()->findByPrimaryKey($resource['pk_i_id']));
                        } else {
                            // no es imagen o imagen sin extesión
                        }

                    }

                    osc_add_flash_ok_message( _m('Re-generation complete'), 'admin');
                    $this->redirectTo(osc_admin_base_url(true).'?page=settings&action=media');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/media.php
