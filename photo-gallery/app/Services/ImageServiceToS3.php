<?php

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use App\Models\Image;
use Error;
use Exception;
use Illuminate\Support\Facades\Storage;

class ImageServiceToS3 implements ImageServiceInterface
{
    private $rollbackStack = null;


    public function storeNewImage($image, $title): Image
    {
        try {
            $url = $this->storeImageInS3($image);
            return $this->storeImageInDatabase($url, $title);
        } catch (Exception $e) {
            throw new Error('Erro ao gravar a imagem');
        }
    }

    public function deleteImageFile($imageUrl): bool
    {
        $imagePath = str_replace('https://gallery-of-photos.s3.sa-east-1.amazonaws.com/', '', $imageUrl);
        return Storage::disk('s3')->delete($imagePath);
        return true;
    }

    public function deleteDatabaseImage($databaseImage): bool
    {
        if ($databaseImage) {
            $databaseImage->delete();
            return true;
        }
        return false;
    }

    public function rollback(): bool
    {
        while (!empty($this->rollbackStack)) {
            $action = array_pop($this->rollbackStack);
            $method = $action['method'];
            $params = $action['params'];
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], $params);
            }
        }
        return true;
    }

    public function storeImageFromS3($image): string
    {
        return $this->storeImageInS3($image);
    }

    private function storeImageInS3($image): string
    {
        $imageName = $image->storePublicly('public', 's3');
        $url = 'https://gallery-of-photos.s3.sa-east-1.amazonaws.com/' . $imageName;
        $this->addToRollbackStack('deleteImageFromS3', [$imageName]);
        return $url;
    }

    private function storeImageInDatabase($image, $title): Image
    {
        $image =  Image::create([
            'title' => $title,
            'url' => $image
        ]);

        $this->addToRollbackStack('deleteDatabaseImage', [$image]);
        return $image;
    }

    private function addToRollbackStack($method, $params = []): void
    {
        $this->rollbackStack[] = [
            'method' => $method,
            'params' => $params
        ];
    }
}
