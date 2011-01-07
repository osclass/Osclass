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

		$content = file_get_contents($imagePath);
		$this->im = imagecreatefromstring($content);

		return $this;		
	}

	public function __destruct() {
		imagedestroy($this->im);
	}

	public function resizeToMax($size) {
		$w = imagesx($this->im);
		$h = imagesy($this->im);

		if($w >= $h) {
			$newW = $size;
			$newH = $h * ($newW / $w);
		} else {
			$newH = $size;
			$newW = $w * ($newH / $h);
		}

		$newIm = imagecreatetruecolor($newW, $newH);
		imagecopyresized($newIm, $this->im, 0, 0, 0, 0, $newW, $newH, $w, $h);
		imagedestroy($this->im);

		$this->im = $newIm;

		return $this;
	}

	public function resizeTo($width, $height) {
		$w = imagesx($this->im);
		$h = imagesy($this->im);

		if(($w/$h)>=($width/$height)) {
			$newW = $width;
			$newH = $h * ($newW / $w);
		} else {
			$newH = $height;
			$newW = $w * ($newH / $h);
		}

		$newIm = imagecreatetruecolor($width,$height);//$newW, $newH);
		imagecopyresampled($newIm, $this->im, (($width-$newW)/2), (($height-$newH)/2), 0, 0, $newW, $newH, $w, $h);
		imagedestroy($this->im);

		$this->im = $newIm;

		return $this;
	}

	public function saveToFile($imagePath) {
		if(file_exists($imagePath) && !is_writable($imagePath)) throw new Exception("$imagePath is not writable!");

		imagepng($this->im, $imagePath, 7);
	}
}

