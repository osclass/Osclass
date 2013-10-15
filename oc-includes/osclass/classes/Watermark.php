<?php
class Watermark{

    private $font;
    private $color;

    public function __construct() {
        $this->font = osc_apply_filter('watermark_font_path', LIB_PATH . "osclass/classes/Arial.ttf");
    }

    /**
     * Apply watermark to certain image
     *
     * @param string $filepath
     * @return boolean
     */
    public function doWatermarkText($filepath, $color, $text, $mime = 'image/png') {
        // get image resource
        $image = $this->getImageResource($filepath, $mime );

        // add text watermark to image
        $image = $this->imageAddText($image, $color, $text);

        // save watermarked image
        return $this->saveImageFile($image, $mime, $filepath);
    }

    public function doWatermarkImage($filepath, $mime = 'image/png')
    {

        $path_watermark = osc_uploads_path() . 'watermark.png';
        if(osc_use_imagick()) {
            $im = new Imagick($filepath);
            $wm = new Imagick($path_watermark);

            $geo = $im->getImageGeometry();
            $wgeo = $wm->getImageGeometry();

            switch(osc_watermark_place()) {
                case 'tl':
                    $dest_x = 0;
                    $dest_y = 0;
                    break;
                case 'tr':
                    $dest_x = $geo['width'] - $wgeo['width'];
                    $dest_y = 0;
                    break;
                case 'bl':
                    $dest_x = 0;
                    $dest_y = $geo['height'] - $wgeo['height'];
                    break;
                case 'br':
                    $dest_x = $geo['width'] - $wgeo['width'];
                    $dest_y = $geo['height'] - $wgeo['height'];
                    break;
                default:
                    $dest_x = ($geo['width']/2) - ($wgeo['width']/2);
                    $dest_y = ($geo['height']/2) - ($wgeo['height']/2);
                    break;
            }

            $im->compositeImage( $wm, imagick::COMPOSITE_OVER, $dest_x, $dest_y );
            $this->saveImageFile($im, 'image/jpeg', $filepath);
            $im->destroy();
            $wm->destroy();
        } else {
            $watermark = imagecreatefrompng( $path_watermark );

            $watermark_width  = imagesx($watermark);
            $watermark_height = imagesy($watermark);

            $image = $this->getImageResource($filepath, $mime );
            $size = getimagesize( $filepath );

            switch(osc_watermark_place()) {
                case 'tl':
                    $dest_x = 0;
                    $dest_y = 0;
                    break;
                case 'tr':
                    $dest_x = $size[0] - $watermark_width;
                    $dest_y = 0;
                    break;
                case 'bl':
                    $dest_x = 0;
                    $dest_y = $size[1] - $watermark_height;
                    break;
                case 'br':
                    $dest_x = $size[0] - $watermark_width;
                    $dest_y = $size[1] - $watermark_height;
                    break;
                default:
                    $dest_x = ($size[0]/2) - ($watermark_width /2);
                    $dest_y = ($size[1]/2) - ($watermark_height/2);
                    break;
            }

            $this->imagecopymerge_alpha($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 100);

            $this->saveImageFile($image, $mime, $filepath);
            imagedestroy($image);
            imagedestroy($watermark);
        }
    }
    /**
     * Add watermark text to image
     *
     * @param resource $image
     * @param array $opt
     * @return resource
     */
    private function imageAddText($image, $color, $text) {
        if(osc_use_imagick()) {
            $draw = new ImagickDraw();
            $draw->setFillColor("#".$color);
            $draw->setFont($this->font);
            $draw->setFontSize( 30 );
            $metrics = $image->queryFontMetrics($draw, $text);
            $geometry = $image->getImageGeometry();
            switch(osc_watermark_place()) {
                case 'tl':
                    $offset['x'] = 1;
                    $offset['y'] = $metrics['ascender']+1;
                    break;
                case 'tr':
                    $offset['x'] = $geometry['width'] - $metrics['textWidth']-1;
                    $offset['y'] = $metrics['ascender']+1;
                    break;
                case 'bl':
                    $offset['x'] = 1;
                    $offset['y'] = $geometry['height']-1;
                    break;
                case 'br':
                    $offset['x'] = $geometry['width'] - $metrics['textWidth']-1;
                    $offset['y'] = $geometry['height']-1;
                    break;
                default:
                    $offset['x'] = ($geometry['width'] / 2) - ($metrics['textWidth'] / 2);
                    $offset['y'] = ($geometry['height'] / 2) - ($metrics['ascender'] / 2);
                    break;
            }
            $image->annotateImage($draw, $offset['x'], $offset['y'], 0, $text);
        } else {
            imagealphablending( $image, false );
            imagesavealpha( $image, true );
            $white = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefill($image, 0, 0, $white);            // allocate text color
            $color  = $this->imageColorAllocateHex($image, $color);

            // calculate watermark position and get full path to font file
            $offset = $this->calculateOffset($image, $text);

            // Add the text to image
            imagettftext($image, 20, 0, $offset['x'], $offset['y'], $color, $this->font , html_entity_decode($text, null, "UTF-8"));
        }
        return $image;
    }
    /**
     * Allocate a color for an image from HEX code
     *
     * @param resource $image
     * @param string $hexstr
     * @return int
     */
    private function imageColorAllocateHex($image, $hexstr) {
        $red    = hexdec(substr($hexstr, 0, 2));
        $green  = hexdec(substr($hexstr, 2, 2));
        $blue   = hexdec(substr($hexstr, 4, 2));


        return imagecolorallocate($image, $red, $green, $blue);
    }

    /**
     * Calculate offset acording to watermark alignment
     *
     * @param resource $image
     * @param array $text
     * @return array
     */
    private function calculateOffset($image,$text) {
        $offset = array('x' => 0, 'y' => 0);

        // get image size and calculate bounding box
        $isize  = $this->getImageSize($image);
        $bbox   = $this->calculateBBox($text);

        switch( osc_watermark_place() ) {
            case 'tl':
                $offset['x'] = $bbox['height'];
                $offset['y'] = $bbox['height'] * 1.5;
            break;
            case 'tr':
                $offset['x'] = $isize['x'] - ($bbox['width'] + $bbox['height']);
                $offset['y'] = $bbox['height'] * 1.5;
            break;
            case 'bl':
                $offset['x'] = $bbox['height'];
                $offset['y'] = $isize['y'] - $bbox['height'];
            break;
            case 'br':
                $offset['x'] = $isize['x'] - ($bbox['width'] + $bbox['height']);
                $offset['y'] = $isize['y'] - $bbox['height'];
            break;
            default:
                $offset['x'] = ($isize['x'] / 2) - ($bbox['top_right']['x'] / 2);
                $offset['y'] = ($isize['y'] / 2) - ($bbox['top_right']['y'] / 2);
            break;
        }

        return $offset;
    }

    /**
     * Get array with image size
     *
     * @param resource $image
     * @return array
     */
    private function getImageSize($image) {
        return array(
            'x' => imagesx($image),
            'y' => imagesy($image)
        );
    }

    /**
     * Calculate bounding box of watermark
     *
     * @param array $text
     * @return array
     */
    private function calculateBBox($text) {
        $bbox = imagettfbbox(
            20,
            0,
            $this->font,
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

        // calculate width & height of text
        $bbox['width']  = $bbox['top_right']['x'] - $bbox['top_left']['x'];
        $bbox['height'] = $bbox['bottom_left']['y'] - $bbox['top_left']['y'];

        return $bbox;
    }

    /**
     * Get image resource accordingly to mimetype
     *
     * @param string $filepath
     * @param string $mime_type
     * @return resource
     */
    private function getImageResource($filepath, $mime_type) {
        if(osc_use_imagick()) {
            return new Imagick($filepath);
        } else {
            switch ( $mime_type ) {
                case 'image/jpeg':
                    return imagecreatefromjpeg($filepath);
                case 'image/png':
                    return imagecreatefrompng($filepath);
                case 'image/gif':
                    return imagecreatefromgif($filepath);
                default:
                    return false;
            }
        }
    }

    /**
     * Save image from image resource
     *
     * @param resource $image
     * @param string $mime_type
     * @param string $filepath
     * @return boolean
     */
    private function saveImageFile($image, $mime_type, $filepath) {
        if(osc_use_imagick()) {
            $image->setImageFileName($filepath);
            $image->writeImage();
        } else {
            switch ( $mime_type ) {
                case 'image/jpeg':
                    return imagejpeg($image, $filepath);
                case 'image/png':
                    return imagepng($image, $filepath);
                case 'image/gif':
                    return imagegif($image, $filepath);
                default:
                    return false;
            }
        }
    }


    function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct, $trans = NULL)
    {
        imagealphablending( $dst_im, false );
        imagesavealpha( $dst_im, true );
        $dst_w = imagesx($dst_im);
        $dst_h = imagesy($dst_im);

        // bounds checking
        $src_x = max($src_x, 0);
        $src_y = max($src_y, 0);
        $dst_x = max($dst_x, 0);
        $dst_y = max($dst_y, 0);
        if ($dst_x + $src_w > $dst_w)
            $src_w = $dst_w - $dst_x;
        if ($dst_y + $src_h > $dst_h)
            $src_h = $dst_h - $dst_y;

        for($x_offset = 0; $x_offset < $src_w; $x_offset++)
            for($y_offset = 0; $y_offset < $src_h; $y_offset++)
            {
                // get source & dest color
                $srccolor = imagecolorsforindex($src_im, imagecolorat($src_im, $src_x + $x_offset, $src_y + $y_offset));
                $dstcolor = imagecolorsforindex($dst_im, imagecolorat($dst_im, $dst_x + $x_offset, $dst_y + $y_offset));

                // apply transparency
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
                    // paint
                    if (!imagesetpixel($dst_im, $dst_x + $x_offset, $dst_y + $y_offset, $color))
                        return false;
                    imagecolordeallocate($dst_im, $color);
                }
            }
            return true;
    }


}
?>
