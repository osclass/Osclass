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
                            if( $_FILES['watermark_image']['error'] == UPLOAD_ERR_OK ) {
                                if($_FILES['watermark_image']['type']=='image/png') {
                                    $tmpName = $_FILES['watermark_image']['tmp_name'];
                                    $path    = osc_content_path() . 'uploads/watermark.png';
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
                            $iUpdated += osc_set_preference('watermark_text_color', '');
                            $iUpdated += osc_set_preference('watermark_text', '');
                            $iUpdated += osc_set_preference('watermark_place', Params::getParam('watermark_image_place'));
                        break;
                        default:
                        break;
                    }

                    // format parameters
                    $maxSizeKb         = strip_tags($maxSizeKb);
                    $dimThumbnail      = strip_tags($dimThumbnail);
                    $dimPreview        = strip_tags($dimPreview);
                    $dimNormal         = strip_tags($dimNormal);
                    $keepOriginalImage = ($keepOriginalImage != '' ? true : false);
                    $forceAspectImage  = ($forceAspectImage != '' ? true : false);
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

                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media');
                break;
                case('images_post'):
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media');
                    }
                    osc_csrf_check();

                    $wat = new Watermark();
                    $aResources = ItemResource::newInstance()->getAllResources();
                    foreach($aResources as $resource) {
                        osc_run_hook('regenerate_image', $resource);

                        $path = osc_content_path() . 'uploads/';
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
                            $path_normal = $path = osc_content_path() . 'uploads/' . $resource['pk_i_id'] . '.jpg';
                            $size = explode('x', osc_normal_dimensions());
                            ImageResizer::fromFile($image_tmp)->resizeTo($size[0], $size[1])->saveToFile($path);

                            if( osc_is_watermark_text() ) {
                                $wat->doWatermarkText( $path , osc_watermark_text_color(), osc_watermark_text() , 'image/jpeg' );
                            } elseif ( osc_is_watermark_image() ){
                                $wat->doWatermarkImage( $path, 'image/jpeg');
                            }

                            // Create preview
                            $path = osc_content_path(). 'uploads/' . $resource['pk_i_id'] . '_preview.jpg';
                            $size = explode('x', osc_preview_dimensions());
                            ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);

                            // Create thumbnail
                            $path = osc_content_path(). 'uploads/' . $resource['pk_i_id'] . '_thumbnail.jpg';
                            $size = explode('x', osc_thumbnail_dimensions());
                            ImageResizer::fromFile($path_normal)->resizeTo($size[0], $size[1])->saveToFile($path);

                            // update resource info
                            ItemResource::newInstance()->update(
                                                    array(
                                                        's_path'            => 'oc-content/uploads/'
                                                        ,'s_name'           => osc_genRandomPassword()
                                                        ,'s_extension'      => 'jpg'
                                                        ,'s_content_type'   => 'image/jpeg'
                                                    )
                                                    ,array(
                                                        'pk_i_id'       => $resource['pk_i_id']
                                                    )
                            );
                            osc_run_hook('regenerated_image', ItemResource::newInstance()->findByPrimaryKey($resource['pk_i_id']));
                            // si extension es direfente a jpg, eliminar las imagenes con $extension si hay
                            if( $extension != 'jpg' ) {
                                @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "." . $extension);
                                @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "_original." . $extension);
                                @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "_preview." . $extension);
                                @unlink(osc_content_path(). 'uploads/' . $resource['pk_i_id'] . "_thumbnail." . $extension);
                            }
                            // ....
                        } else {
                            // no es imagen o imagen sin extesión
                        }

                    }

                    osc_add_flash_ok_message( _m('Re-generation complete'), 'admin');
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=media');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/media.php