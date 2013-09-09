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

        private function __construct($imagePath) {
            if(!file_exists($imagePath)) { throw new Exception(sprintf(__("%s does not exist!"), $imagePath)); };
            if(!is_readable($imagePath)) { throw new Exception(sprintf(__("%s is not readable!"), $imagePath)); };
            if(filesize($imagePath)==0) { throw new Exception(sprintf(__("%s is corrupt or broken!"), $imagePath)); };

            if(osc_use_imagick()) {
                $this->im = new Imagick($imagePath);
            } else {
                $content = file_get_contents($imagePath);
                $this->im = imagecreatefromstring($content);
            }

            $this->image_info = @getimagesize($imagePath);
            switch (@$this->image_info['mime']) {
                case 'image/png':
                    $this->ext = 'png';
                    $this->mime = 'image/png';
                    break;
                case 'image/gif':
                    $this->ext = 'png';
                    $this->mime = 'image/png';
                    break;
                default:
                    $this->ext = 'jpg';
                    $this->mime = 'image/jpeg';
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
            if(osc_use_imagick()) {
                $bg = new Imagick();
                $geometry = $this->im->getImageGeometry();
                if($force_aspect) {
                    if(($geometry['width']/$geometry['height'])>=($width/$height)) {
                        if($upscale) { $newW = $width; } else { $newW = ($geometry['width'] > $width)? $width : $geometry['width']; };
                        $height = ceil($geometry['height'] * ($newW / $geometry['width']));
                    } else {
                        if($upscale) { $newH = $height; } else { $newH = ($geometry['height'] > $height)? $height : $geometry['height']; };
                        $width = ceil($geometry['width'] * ($newH / $geometry['height']));
                    }
                }
                $bg->newImage($width, $height, 'none');
                
                $this->im->thumbnailImage($width, $height, true);
                $geometry = $this->im->getImageGeometry();

                $x = ceil( $width - $geometry['width'] ) / 2;
                $y = ceil( $height - $geometry['height'] ) / 2;

                $bg->compositeImage( $this->im, imagick::COMPOSITE_OVER, $x, $y );
                $this->im = $bg;
            } else {
                $w = imagesx($this->im);
                $h = imagesy($this->im);

                if(($w/$h)>=($width/$height)) {
                    if($upscale) { $newW = $width; } else { $newW = ($w > $width)? $width : $w; };
                    $newH = ceil($h * ($newW / $w));
                    if($force_aspect) { $height = $newH; }
                } else {
                    if($upscale) { $newH = $height; } else { $newH = ($h > $height)? $height : $h; };
                    $newW = ceil($w * ($newH / $h));
                    if($force_aspect) { $width = $newW; }
                }

                $newIm = imagecreatetruecolor($width,$height);
                imagealphablending($newIm, false);
                $colorTransparent = imagecolorallocatealpha($newIm, 255, 255, 255, 127);
                imagefill($newIm, 0, 0, $colorTransparent);
                imagesavealpha($newIm, true);
                imagecopyresampled($newIm, $this->im, floor(($width-$newW)/2), floor(($height-$newH)/2), 0, 0, $newW, $newH, $w, $h);
                imagedestroy($this->im);

                $this->im = $newIm;
            }
            return $this;
        }

        public function saveToFile($imagePath) {
            if(file_exists($imagePath) && !is_writable($imagePath)) { throw new Exception("$imagePath is not writable!"); };
            if(osc_use_imagick()) {
                $this->im->setImageFormat($this->ext);
                $this->im->setImageFileName($imagePath);
                $this->im->writeImage($imagePath);
            } else {
                switch ($this->ext) {
                    case 'png':
                        imagepng($this->im, $imagePath,0);
                        break;
                    case 'gif':
                        imagepng($this->im, $imagePath,0);
                        break;
                    default:
                        imagejpeg($this->im, $imagePath);
                        break;
                }               
            }
        }

        public function show() {
            header('Content-Disposition: Attachment;filename=image.'.$this->ext);
            header('Content-type: '.$this->mime);
            if(osc_use_imagick()) {
            } else {
                switch ($this->ext) {
                    case 'png':
                        imagepng($this->im);
                        break;
                    case 'gif':
                        imagepng($this->im);
                        break;
                    default:
                        imagejpeg($this->im);
                        break;
                }
            }
        }

    }

?>