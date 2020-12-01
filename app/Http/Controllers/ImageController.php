<?php

namespace App\Http\Controllers;

use App\Models\Image;
use AsyncAws\Lambda\LambdaClient;

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
            $blob = $this->getImageBlobFromApi($width, $height);

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
    private function getImageBlobFromApi(int $width, int $height) : string {
        $lambda = new LambdaClient([
            'accessKeyId' => env('AWS_LAMBDA_ACCESS_KEY_ID'),
            'accessKeySecret' => env('AWS_LAMBDA_ACCESS_KEY_SECRET'),
            'region' => 'us-east-1',
        ]);

        $payload = [
            'width' => $width,
            'height' => $height
        ];

        $result = $lambda->invoke([
            'FunctionName' => 'quiche-holder-engine-production-getImage',
            'Payload' => json_encode($payload),
        ]);

        return base64_decode($result->getPayload());
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