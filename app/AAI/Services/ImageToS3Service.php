<?php
namespace App\AAI\Services;

use Illuminate\Http\UploadedFile;

class ImageToS3Service {
    public function processImage(UploadedFile $image)
    {
        $path = $this->getPath();
        $fileName = uniqid() . ".jpg";

        $image->move("{$path}/{$fileName}");
        // TODO: MOVE TO S3

        return $fileName;
    }

    private function getPath()
    {
        $path = public_path('images/uploads/');

        if (! file_exists($path)) {
            mkdir($path, '0755');
        }

        return $path;
    }
}