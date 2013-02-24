<?php

if (!defined('ABS_PATH'))
    exit('ABS_PATH is not loaded. Direct access is not allowed.');
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

	protected $im;
	protected $image_info;

	private function __construct($imagePath) {
		if (!file_exists($imagePath))
			throw new Exception("$imagePath does not exist!");
		if (!is_readable($imagePath))
			throw new Exception("$imagePath is not readable!");
		if (filesize($imagePath) == 0)
			throw new Exception("$imagePath is corrupt or broken!");

		if (osc_use_imagick()) {
			$this->im = new Imagick($imagePath);
		} else {
			$content = file_get_contents($imagePath);
			$this->im = imagecreatefromstring($content);
			$this->image_info = getimagesize($imagePath);
		}

		return $this;
	}

	public function __destruct() {
		if (osc_use_imagick()) {
			$this->im->destroy();
		} else {
			imagedestroy($this->im);
		}
	}

	public function resizeTo($width, $height) {
		if (osc_use_imagick()) {
			$bg = new Imagick();
			$bg->newImage($width, $height, 'white');

			$this->im->thumbnailImage($width, $height, true);
			$geometry = $this->im->getImageGeometry();

			$x = ( $width - $geometry['width'] ) / 2;
			$y = ( $height - $geometry['height'] ) / 2;

			$bg->compositeImage($this->im, imagick::COMPOSITE_OVER, $x, $y);
			$this->im = $bg;
		} else {
			$resized = $this->getResizedImage($width, $height);
			if ($resized) {
				return $resized;
			}
		}
		return $this;
	}

	/**
	 * Gets the jpeg contents of the resized version of an already uploaded image
	 * (Returns false if the file was not an image)
	 *
	 * @param string $input_name The name of the file on the disk
	 * @param int    $maxwidth   The desired width of the resized image
	 * @param int    $maxheight  The desired height of the resized image
	 * @param bool   $square     If set to true, takes the smallest of maxwidth and
	 * 			                 maxheight and use it to set the dimensions on the new image.
	 *                           If no crop parameters are set, the largest square that fits
	 *                           in the image centered will be used for the resize. If square,
	 *                           the crop must be a square region.
	 * @param int    $x1         x coordinate for top, left corner
	 * @param int    $y1         y coordinate for top, left corner
	 * @param int    $x2         x coordinate for bottom, right corner
	 * @param int    $y2         y coordinate for bottom, right corner
	 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
	 *
	 * @return false|mixed The contents of the resized image, or false on failure
	 */
	public function getResizedImage($maxwidth, $maxheight, $square = FALSE, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $upscale = TRUE) {

		$original_image = $this->im;
		// Get the size information from the image
		$imgsizearray = $this->image_info;
		
		if ($imgsizearray == FALSE) {
			return FALSE;
		}

		$width = $imgsizearray[0];
		$height = $imgsizearray[1];

		$accepted_formats = array(
			'image/jpeg' => 'jpeg',
			'image/pjpeg' => 'jpeg',
			'image/png' => 'png',
			'image/x-png' => 'png',
			'image/gif' => 'gif'
		);

		// make sure the function is available
		$load_function = "imagecreatefrom" . $accepted_formats[$imgsizearray['mime']];
		if (!is_callable($load_function)) {
			return FALSE;
		}

		// get the parameters for resizing the image
		$options = array(
			'maxwidth' => $maxwidth,
			'maxheight' => $maxheight,
			'square' => $square,
			'upscale' => $upscale,
			'x1' => $x1,
			'y1' => $y1,
			'x2' => $x2,
			'y2' => $y2,
		);
		$params = $this->getImageResizeParameters($width, $height, $options);
		if ($params == FALSE) {
			return FALSE;
		}

		// allocate the new image
		$new_image = imagecreatetruecolor($params['newwidth'], $params['newheight']);
		if (!$new_image) {
			return FALSE;
		}

		// color transparencies white (default is black)
		imagefilledrectangle(
				$new_image, 0, 0, $params['newwidth'], $params['newheight'], imagecolorallocate($new_image, 255, 255, 255)
		);

		$rtn_code = imagecopyresampled($new_image, $original_image, 0, 0, $params['xoffset'], $params['yoffset'], $params['newwidth'], $params['newheight'], $params['selectionwidth'], $params['selectionheight']);
		if (!$rtn_code) {
			return FALSE;
		}

		// grab a compressed jpeg version of the image
		
//		imagejpeg($new_image, NULL, 90);
		imagedestroy($original_image);
		
		$this->im = $new_image;

		return $this;
	}

	/**
	 * Calculate the parameters for resizing an image
	 *
	 * @param int   $width   Width of the original image
	 * @param int   $height  Height of the original image
	 * @param array $options See $defaults for the options
	 *
	 * @return array or FALSE
	 */
	public function getImageResizeParameters($width, $height, $options) {

		$defaults = array(
			'maxwidth' => 100,
			'maxheight' => 100,
			'square' => FALSE,
			'upscale' => FALSE,
			'x1' => 0,
			'y1' => 0,
			'x2' => 0,
			'y2' => 0,
		);

		$options = array_merge($defaults, $options);

		extract($options);

		// crop image first?
		$crop = TRUE;
		if ($x1 == 0 && $y1 == 0 && $x2 == 0 && $y2 == 0) {
			$crop = FALSE;
		}

		// how large a section of the image has been selected
		if ($crop) {
			$selection_width = $x2 - $x1;
			$selection_height = $y2 - $y1;
		} else {
			// everything selected if no crop parameters
			$selection_width = $width;
			$selection_height = $height;
		}

		// determine cropping offsets
		if ($square) {
			// asking for a square image back
			// detect case where someone is passing crop parameters that are not for a square
			if ($crop == TRUE && $selection_width != $selection_height) {
				return FALSE;
			}

			// size of the new square image
			$new_width = $new_height = min($maxwidth, $maxheight);

			// find largest square that fits within the selected region
			$selection_width = $selection_height = min($selection_width, $selection_height);

			// set offsets for crop
			if ($crop) {
				$widthoffset = $x1;
				$heightoffset = $y1;
				$width = $x2 - $x1;
				$height = $width;
			} else {
				// place square region in the center
				$widthoffset = floor(($width - $selection_width) / 2);
				$heightoffset = floor(($height - $selection_height) / 2);
			}
		} else {
			// non-square new image
			$new_width = $maxwidth;
			$new_height = $maxheight;

			// maintain aspect ratio of original image/crop
			if (($selection_height / (float) $new_height) > ($selection_width / (float) $new_width)) {
				$new_width = floor($new_height * $selection_width / (float) $selection_height);
			} else {
				$new_height = floor($new_width * $selection_height / (float) $selection_width);
			}

			// by default, use entire image
			$widthoffset = 0;
			$heightoffset = 0;

			if ($crop) {
				$widthoffset = $x1;
				$heightoffset = $y1;
			}
		}

		if (!$upscale && ($selection_height < $new_height || $selection_width < $new_width)) {
			// we cannot upscale and selected area is too small so we decrease size of returned image
			if ($square) {
				$new_height = $selection_height;
				$new_width = $selection_width;
			} else {
				if ($selection_height < $new_height && $selection_width < $new_width) {
					$new_height = $selection_height;
					$new_width = $selection_width;
				}
			}
		}

		$params = array(
			'newwidth' => $new_width,
			'newheight' => $new_height,
			'selectionwidth' => $selection_width,
			'selectionheight' => $selection_height,
			'xoffset' => $widthoffset,
			'yoffset' => $heightoffset,
		);

		return $params;
	}

	public function saveToFile($imagePath) {
		if (file_exists($imagePath) && !is_writable($imagePath))
			throw new Exception("$imagePath is not writable!");
		if (osc_use_imagick()) {
			$this->im->setImageFileName($imagePath);
			$this->im->writeImage($imagePath);
		} else {
			imagejpeg($this->im, $imagePath);
		}
	}

	public function show() {
		header('Content-Disposition: Attachment;filename=image.jpg');
		header('Content-type: image/jpg');
		if (osc_use_imagick()) {
			
		} else {
			imagepng($this->im);
		}
	}

}
