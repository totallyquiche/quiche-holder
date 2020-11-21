<?php

namespace App\Http\Controllers;

use Imagick;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function serve(int $width, int $height) : void
    {
        header('Content-Type: image/jpg');

        echo self::scaleAndCropImage('../public/images/quiche.jpg', $width, $height);

        exit;
    }

    /**
     * Scale and crop and image using Imagick. Returns the image as a blob.
     *
     * @param string $image_file_path
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */

    private static function scaleAndCropImage(string $image_file_path, int $width, int $height) : string
    {
        $image = new Imagick(realpath($image_file_path));

        $ratio = $width / $height;

        // Original image dimensions
        $original_width = $image->getImageWidth();
        $original_height = $image->getImageHeight();
        $original_ratio = $original_width / $original_height;

        // Determine new image dimensions to scale to.
        // Also determine cropping coordinates.
        if ($ratio > $original_ratio) {
          $new_width = $width;
          $new_height = $width / $original_width * $original_height;
          $crop_x = 0;
          $crop_y = intval(($new_height - $height) / 2);
        } else {
          $new_width = $height / $original_height * $original_width;
          $new_height = $height;
          $crop_x = intval(($new_width - $width) / 2);
          $crop_y = 0;
        }

        // Scale image to fit minimal of provided dimensions.
        $image->scaleImage($new_width, $new_height, true);

        // Now crop image to exactly fit provided dimensions.
        $image->cropImage($width, $height, $crop_x, $crop_y);

        $image_blob = $image->getImageBlob();

        $image->clear();

        return $image_blob;
      }
}
