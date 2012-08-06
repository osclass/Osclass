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

    /**
     * This class represents a utility to load and resize images easily.
     */
    class ImageResizer {

        public static function fromFile($imagePath) {
            return new ImageResizer($imagePath);
        }

        private $im;

        private function __construct($imagePath) {
            if(!file_exists($imagePath)) throw new Exception("$imagePath does not exist!");
            if(!is_readable($imagePath)) throw new Exception("$imagePath is not readable!");
            if(filesize($imagePath)==0) throw new Exception("$imagePath is corrupt or broken!");

            if(osc_use_imagick()) {
                $this->im = new Imagick($imagePath);                
            } else {
                $content = file_get_contents($imagePath);
                $this->im = imagecreatefromstring($content);
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

        public function resizeTo($width, $height) {
            if(osc_use_imagick()) {
                $bg = new Imagick();
                $bg->newImage($width, $height, 'white');
                
                $this->im->thumbnailImage($width, $height, true);
                $geometry = $this->im->getImageGeometry();

                $x = ( $width - $geometry['width'] ) / 2;
                $y = ( $height - $geometry['height'] ) / 2;

                $bg->compositeImage( $this->im, imagick::COMPOSITE_OVER, $x, $y );
                $this->im = $bg;
            } else {
                $w = imagesx($this->im);
                $h = imagesy($this->im);

                if(($w/$h)>=($width/$height)) {
                    //$newW = $width;
                    $newW = ($w > $width)? $width : $w;
                    $newH = $h * ($newW / $w);
                } else {
                    //$newH = $height;
                    $newH = ($h > $height)? $height : $h;
                    $newW = $w * ($newH / $h);
                }

                $newIm = imagecreatetruecolor($width,$height);//$newW, $newH);
                imagealphablending($newIm, false);
                $colorTransparent = imagecolorallocatealpha($newIm, 255, 255, 255, 127);
                imagefill($newIm, 0, 0, $colorTransparent);
                imagesavealpha($newIm, true);
                imagecopyresampled($newIm, $this->im, (($width-$newW)/2), (($height-$newH)/2), 0, 0, $newW, $newH, $w, $h);
                imagedestroy($this->im);

                $this->im = $newIm;
            }
            return $this;
        }

        public function saveToFile($imagePath) {
            if(file_exists($imagePath) && !is_writable($imagePath)) throw new Exception("$imagePath is not writable!");
            if(osc_use_imagick()) {
                $this->im->setImageFileName($imagePath);
                $this->im->writeImage($imagePath);
            } else {
               imagejpeg($this->im, $imagePath);
            }
        }

        public function show() {
            header('Content-Disposition: Attachment;filename=image.jpg');
            header('Content-type: image/jpg');
            if(osc_use_imagick()) {
            } else {
                imagepng($this->im);
            }
        }

    }

?>