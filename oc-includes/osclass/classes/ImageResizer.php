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

    /**
     * This class represents a utility to load and resize images easily.
     */
    class ImageResizer {

        public static function fromFile($imagePath) {
            return new ImageResizer($imagePath);
        }

        private $im;
        private $image_info;
        private $ext;
        private $mime;

        private $_font;
        private $_color;
        private $_width;
        private $_height;
        private $_watermarked = false;


        private function __construct($imagePath) {
            if(!file_exists($imagePath)) { throw new Exception(sprintf(__("%s does not exist!"), $imagePath)); };
            if(!is_readable($imagePath)) { throw new Exception(sprintf(__("%s is not readable!"), $imagePath)); };
            if(filesize($imagePath)==0) { throw new Exception(sprintf(__("%s is corrupt or broken!"), $imagePath)); };

            if(osc_use_imagick()) {
                $this->im = new Imagick($imagePath);
                $geometry = $this->im->getImageGeometry();
                $this->_width = $geometry['width'];
                $this->_height = $geometry['height'];
            } else {
                $content = file_get_contents($imagePath);
                $this->im = imagecreatefromstring($content);
                $this->_width = imagesx($this->im);
                $this->_height = imagesy($this->im);
            }

            $this->image_info = @getimagesize($imagePath);
            switch (@$this->image_info['mime']) {
                case 'image/gif':
                case 'image/png':
                    $this->ext = 'png';
                    $this->mime = 'image/png';
                    break;
                default:
                    $this->ext = 'jpg';
                    $this->mime = 'image/jpeg';
                    if(!osc_use_imagick()) {
                        $bg = imagecreatetruecolor($this->_width, $this->_height);
                        imagefill($bg, 0, 0, imagecolorallocatealpha($bg, 255, 255, 255, 127));
                        imagesavealpha($bg, true);
                        imagealphablending($bg, TRUE);
                        imagecopy($bg, $this->im, 0, 0, 0, 0, $this->_width, $this->_height);
                        imagedestroy($this->im);
                        $this->im = $bg;
                    }
                    break;
            }

            return $this;
        }

        public function __destruct() {
            if(osc_use_imagick()) {
                $this->im->destroy();
            } else {
                imagedestroy($this->im);
            }
        }

        public function getExt() { return $this->ext; }
        public function getMime() { return $this->mime; }

        public function resizeTo($width, $height, $force_aspect = null, $upscale = true) {
            if($force_aspect==null) {
                $force_aspect = osc_force_aspect_image();
            }

            if(($this->_width/$this->_height)>=($width/$height)) {
                if($upscale) { $newW = $width; } else { $newW = ($this->_width> $width)? $width : $this->_width; };
                $newH = ceil($this->_height * ($newW / $this->_width));
                if($force_aspect) { $height = $newH; }
            } else {
                if($upscale) { $newH = $height; } else { $newH = ($this->_height > $height)? $height : $this->_height; };
                $newW = ceil($this->_width* ($newH / $this->_height));
                if($force_aspect) { $width = $newW; }
            }

            if(osc_use_imagick()) {
                $bg = new Imagick();
                if($this->ext=='jpg') {
                    $bg->newImage($width, $height, 'white');
                } else {
                    $bg->newImage($width, $height, 'none');
                }
                $this->im->thumbnailImage($width, $height, true);
                $bg->compositeImage( $this->im, imagick::COMPOSITE_OVER, floor(($width-$newW)/2), floor(($height-$newH)/2));
                $this->im = $bg;
            } else {
                $newIm = imagecreatetruecolor($width,$height);
                imagealphablending($newIm, false);
                $colorTransparent = imagecolorallocatealpha($newIm, 255, 255, 255, 127);
                imagefill($newIm, 0, 0, $colorTransparent);
                imagesavealpha($newIm, true);
                imagecopyresampled($newIm, $this->im, floor(($width-$newW)/2), floor(($height-$newH)/2), 0, 0, $newW, $newH, $this->_width, $this->_height);
                imagedestroy($this->im);
                $this->im = $newIm;
            }
            $this->_width = $width;
            $this->_height = $height;
            return $this;
        }

        public function saveToFile($imagePath, $ext = null) {
            if(file_exists($imagePath) && !is_writable($imagePath)) { throw new Exception("$imagePath is not writable!"); };
            if($ext==null) { $ext = $this->ext; };
            if($ext!='png' && $ext!='gif') { $ext = 'jpeg'; };
            if(osc_use_imagick()) {
                if($ext=='jpeg' && ($this->ext!='jpeg' && $this->ext!='jpg')) {
                    $bg = new Imagick();
                    $bg->newImage($this->_width, $this->_height, 'white');
                    $this->im->thumbnailImage($this->_width, $this->_height, true);
                    $bg->compositeImage( $this->im, imagick::COMPOSITE_OVER, 0, 0);
                    $this->im = $bg;
                    $this->ext = 'jpeg';
                }
                $this->im->setImageFileName($imagePath);
                $this->im->setImageFormat($ext);
                $this->im->writeImage($imagePath);
            } else {
                switch ($ext) {
                    case 'gif':
                    case 'png':
                        imagepng($this->im, $imagePath, 0);
                        break;
                    default:
                        if(($ext=='jpeg' && ($this->ext!='jpeg' && $this->ext!='jpg')) || $this->_watermarked) {
                            $this->ext = 'jpeg';
                        }
                        imagejpeg($this->im, $imagePath);
                        break;
                }               
            }
        }

        public function autoRotate() {
            if(osc_use_imagick()) {
                switch($this->im->getImageOrientation()) {
                    case 1:
                    default:
                        // DO NOTHING, THE IMAGE IS OK OR WE DON'T KNOW IF IT'S ROTATED
                        break;
                    case 2:
                        $this->im->flipImage();
                        break;
                    case 3:
                        $this->im->rotateImage(new ImagickPixel('none'), 180);
                        break;
                    case 4:
                        $this->im->flipImage();
                        $this->im->rotateImage(new ImagickPixel('none'), 180);
                        break;
                    case 5:
                        $this->im->flipImage();
                        $this->im->rotateImage(new ImagickPixel('none'), 90);
                        break;
                    case 6:
                        $this->im->rotateImage(new ImagickPixel('none'), 90);
                        break;
                    case 7:
                        $this->im->flipImage();
                        $this->im->rotateImage(new ImagickPixel('none'), 270);
                        break;
                    case 8:
                        $this->im->rotateImage(new ImagickPixel('none'), 270);
                        break;
                }
            } else {
                if(isset($this->_exif['Orientation'])) {
                    switch($this->_exif['Orientation']) {
                        case 1:
                        default:
                            // DO NOTHING, THE IMAGE IS OK OR WE DON'T KNOW IF IT'S ROTATED
                            break;
                        case 2:
                            $this->im = imageflip($this->im, IMG_FLIP_HORIZONTAL);
                            break;
                        case 3:
                            $this->im = imagerotate($this->im, 180, 0);
                            break;
                        case 4:
                            $this->im = imagerotate($this->im, 180, 0);
                            $this->im = imageflip($this->im, IMG_FLIP_HORIZONTAL);
                            break;
                        case 5:
                            $this->im = imagerotate($this->im, 90, 0);
                            $this->im = imageflip($this->im, IMG_FLIP_HORIZONTAL);
                            break;
                        case 6:
                            $this->im = imagerotate($this->im, 90, 0);
                            break;
                        case 7:
                            $this->im = imagerotate($this->im, 270, 0);
                            $this->im = imageflip($this->im, IMG_FLIP_HORIZONTAL);
                            break;
                        case 8:
                            $this->im = imagerotate($this->im, 270, 0);
                            break;
                    }
                }
            }
            return $this;
        }

        public function show() {
            header('Content-Disposition: Attachment;filename=image.'.$this->ext);
            header('Content-type: '.$this->mime);
            if(osc_use_imagick()) {
            } else {
                switch ($this->ext) {
                    case 'gif':
                    case 'png':
                        imagepng($this->im);
                        break;
                    default:
                        imagejpeg($this->im);
                        break;
                }
            }
        }

        public function doWatermarkText($text, $color = 'ff0000') {
            $this->_watermarked = true;
            $this->_font = osc_apply_filter('watermark_font_path', LIB_PATH . "osclass/assets/fonts/Arial.ttf");
            if(osc_use_imagick()) {
                $draw = new ImagickDraw();
                $draw->setFillColor("#".$color);
                $draw->setFont($this->_font);
                $draw->setFontSize( 30 );
                $metrics = $this->im->queryFontMetrics($draw, $text);
                switch(osc_watermark_place()) {
                    case 'tl':
                        $offset['x'] = 1;
                        $offset['y'] = $metrics['ascender']+1;
                        break;
                    case 'tr':
                        $offset['x'] = $this->_width - $metrics['textWidth']-1;
                        $offset['y'] = $metrics['ascender']+1;
                        break;
                    case 'bl':
                        $offset['x'] = 1;
                        $offset['y'] = $this->_height-1;
                        break;
                    case 'br':
                        $offset['x'] = $this->_width - $metrics['textWidth']-1;
                        $offset['y'] = $this->_height-1;
                        break;
                    default:
                        $offset['x'] = ($this->_width / 2) - ($metrics['textWidth'] / 2);
                        $offset['y'] = ($this->_height / 2) - ($metrics['ascender'] / 2);
                        break;
                }
                $this->im->annotateImage($draw, $offset['x'], $offset['y'], 0, $text);
            } else {
                imagealphablending( $this->im, true );
                imagesavealpha( $this->im, true );
                if($this->ext!='jpg') {
                    $white = imagecolorallocatealpha($this->im, 255, 255, 255, 127);
                    imagefill($this->im, 0, 0, $white);
                }
                $color  = $this->_imageColorAllocateHex($color);
                $offset = $this->_calculateOffset($text);
                imagettftext($this->im, 20, 0, $offset['x'], $offset['y'], $color, $this->_font , html_entity_decode($text, null, "UTF-8"));
            }
            return $this;
        }


        private function _imageColorAllocateHex($hexstr) {
            $red    = hexdec(substr($hexstr, 0, 2));
            $green  = hexdec(substr($hexstr, 2, 2));
            $blue   = hexdec(substr($hexstr, 4, 2));


            return imagecolorallocate($this->im, $red, $green, $blue);
        }

        private function _calculateOffset($text) {
            $offset = array('x' => 0, 'y' => 0);
            $bbox   = $this->_calculateBBox($text);

            switch( osc_watermark_place() ) {
                case 'tl':
                    $offset['x'] = $bbox['height'];
                    $offset['y'] = $bbox['height'] * 1.5;
                    break;
                case 'tr':
                    $offset['x'] = $this->_width - ($bbox['width'] + $bbox['height']);
                    $offset['y'] = $bbox['height'] * 1.5;
                    break;
                case 'bl':
                    $offset['x'] = $bbox['height'];
                    $offset['y'] = $this->_height - $bbox['height'];
                    break;
                case 'br':
                    $offset['x'] = $this->_width - ($bbox['width'] + $bbox['height']);
                    $offset['y'] = $this->_height - $bbox['height'];
                    break;
                default:
                    $offset['x'] = ($this->_width / 2) - ($bbox['top_right']['x'] / 2);
                    $offset['y'] = ($this->_height / 2) - ($bbox['top_right']['y'] / 2);
                    break;
            }

            return $offset;
        }

        private function _calculateBBox($text) {
            $bbox = imagettfbbox(
                20,
                0,
                $this->_font,
                $text
            );

            $bbox = array(
                'bottom_left'  => array(
                    'x' => $bbox[0],
                    'y' => $bbox[1]
                ),
                'bottom_right' => array(
                    'x' => $bbox[2],
                    'y' => $bbox[3]
                ),
                'top_right'    => array(
                    'x' => $bbox[4],
                    'y' => $bbox[5]
                ),
                'top_left'     => array(
                    'x' => $bbox[6],
                    'y' => $bbox[7]
                )
            );

            $bbox['width']  = $bbox['top_right']['x'] - $bbox['top_left']['x'];
            $bbox['height'] = $bbox['bottom_left']['y'] - $bbox['top_left']['y'];

            return $bbox;
        }

        public function doWatermarkImage()
        {
            $this->_watermarked = true;
            $path_watermark = osc_uploads_path() . 'watermark.png';
            if(osc_use_imagick()) {
                $wm = new Imagick($path_watermark);
                $wgeo = $wm->getImageGeometry();

                switch(osc_watermark_place()) {
                    case 'tl':
                        $dest_x = 0;
                        $dest_y = 0;
                        break;
                    case 'tr':
                        $dest_x = $this->_width - $wgeo['width'];
                        $dest_y = 0;
                        break;
                    case 'bl':
                        $dest_x = 0;
                        $dest_y = $this->_height - $wgeo['height'];
                        break;
                    case 'br':
                        $dest_x = $this->_width - $wgeo['width'];
                        $dest_y = $this->_height - $wgeo['height'];
                        break;
                    default:
                        $dest_x = ($this->_width-$wgeo['width'])/2;
                        $dest_y = ($this->_height-$wgeo['height'])/2;
                        break;
                }

                $this->im->compositeImage( $wm, imagick::COMPOSITE_OVER, $dest_x, $dest_y );
                $wm->destroy();
            } else {
                $watermark = imagecreatefrompng( $path_watermark );

                $watermark_width  = imagesx($watermark);
                $watermark_height = imagesy($watermark);

                switch(osc_watermark_place()) {
                    case 'tl':
                        $dest_x = 0;
                        $dest_y = 0;
                        break;
                    case 'tr':
                        $dest_x = $this->_width - $watermark_width;
                        $dest_y = 0;
                        break;
                    case 'bl':
                        $dest_x = 0;
                        $dest_y = $this->_height - $watermark_height;
                        break;
                    case 'br':
                        $dest_x = $this->_width - $watermark_width;
                        $dest_y = $this->_height - $watermark_height;
                        break;
                    default:
                        $dest_x = ($this->_width-$watermark_width)/2;
                        $dest_y = ($this->_height-$watermark_height)/2;
                        break;
                }

                $this->_imagecopymerge_alpha($this->im, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 100);
                imagedestroy($watermark);
            }
            return $this;
        }



        private function _imagecopymerge_alpha(&$dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct, $trans = NULL)
        {
            imagealphablending( $dst_im, false );
            imagesavealpha( $dst_im, true );

            $dst_w = imagesx($dst_im);
            $dst_h = imagesy($dst_im);

            $src_x = max($src_x, 0);
            $src_y = max($src_y, 0);
            $dst_x = max($dst_x, 0);
            $dst_y = max($dst_y, 0);
            if ($dst_x + $src_w > $dst_w)
                $src_w = $dst_w - $dst_x;
            if ($dst_y + $src_h > $dst_h)
                $src_h = $dst_h - $dst_y;

            for($x_offset = 0; $x_offset < $src_w; $x_offset++) {
                for($y_offset = 0; $y_offset < $src_h; $y_offset++) {
                    $srccolor = imagecolorsforindex($src_im, imagecolorat($src_im, $src_x + $x_offset, $src_y + $y_offset));
                    $dstcolor = imagecolorsforindex($dst_im, imagecolorat($dst_im, $dst_x + $x_offset, $dst_y + $y_offset));

                    if (is_null($trans) || ($srccolor !== $trans))
                    {
                        $src_a = $srccolor['alpha'] * $pct / 100;
                        // blend
                        $src_a = 127 - $src_a;
                        $dst_a = 127 - $dstcolor['alpha'];
                        $dst_r = ($srccolor['red'] * $src_a + $dstcolor['red'] * $dst_a * (127 - $src_a) / 127) / 127;
                        $dst_g = ($srccolor['green'] * $src_a + $dstcolor['green'] * $dst_a * (127 - $src_a) / 127) / 127;
                        $dst_b = ($srccolor['blue'] * $src_a + $dstcolor['blue'] * $dst_a * (127 - $src_a) / 127) / 127;
                        $dst_a = 127 - ($src_a + $dst_a * (127 - $src_a) / 127);
                        $color = imagecolorallocatealpha($dst_im, $dst_r, $dst_g, $dst_b, $dst_a);

                        if (!imagesetpixel($dst_im, $dst_x + $x_offset, $dst_y + $y_offset, $color))
                            return false;
                        imagecolordeallocate($dst_im, $color);
                    }
                }
            }
            return true;
        }
    }


if(!function_exists('imageflip')) {
    function imageflip(&$image, $x = 0, $y = 0, $width = null, $height = null)
    {
        if ($width  < 1) $width  = imagesx($image);
        if ($height < 1) $height = imagesy($image);
        // Truecolor provides better results, if possible.
        if (function_exists('imageistruecolor') && imageistruecolor($image))
        {
            $tmp = imagecreatetruecolor(1, $height);
        }
        else
        {
            $tmp = imagecreate(1, $height);
        }
        $x2 = $x + $width - 1;
        for ($i = (int) floor(($width - 1) / 2); $i >= 0; $i--)
        {
            // Backup right stripe.
            imagecopy($tmp,   $image, 0,        0,  $x2 - $i, $y, 1, $height);
            // Copy left stripe to the right.
            imagecopy($image, $image, $x2 - $i, $y, $x + $i,  $y, 1, $height);
            // Copy backuped right stripe to the left.
            imagecopy($image, $tmp,   $x + $i,  $y, 0,        0,  1, $height);
        }
        imagedestroy($tmp);
        return $image;
    }
    define('IMG_FLIP_HORIZONTAL', 0);
}
