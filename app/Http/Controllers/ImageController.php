<?php

namespace App\Http\Controllers;

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

        header('Content-Type: image/jpg');

        echo base64_decode($result->getPayload());

        exit;
    }
}