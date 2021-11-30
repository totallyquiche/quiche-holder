<?php

namespace App\Http\Controllers;

use App\Models\Image;
use \Imagick;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function serve(int $width, int $height) : void
    {
        $image = Image::where('width', $width)
            ->where('height', $height)
            ->first();

        if ($image) {
            $blob = $image->blob;
        } else {
            $blob = $this->getImageBlob($width, $height);

            $this->createImage($width, $height, $blob);
        }

        header('Content-Type: image/jpg');

        echo $blob;

        exit;
    }

    /**
     * Calls the API to get the image blob.
     *
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    private function getImageBlob(int $width, int $height) : string {
        $image = new Imagick(Storage::url('quiche.jpg'));

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
        $image->scaleImage((int) $new_width, (int) $new_height, true);

        // Now crop image to exactly fit provided dimensions.
        $image->cropImage($width, $height, $crop_x, $crop_y);

        $image_blob = $image->getImageBlob();

        $image->clear();

        return base64_encode($image_blob);
    }

    /**
     * Create and save a new Image record.
     *
     * @param int    $width
     * @param int    $height
     * @param string $blob
     *
     * @return void
     */
    private function createImage(int $width, int $height, string $blob) : void {
        $image = new Image();

        $image->width = $width;
        $image->height = $height;
        $image->blob = $blob;

        $image->save();
    }
}
