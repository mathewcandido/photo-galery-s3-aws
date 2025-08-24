<?php

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use App\Models\Image;
use Error;
use Exception;
use Illuminate\Support\Facades\Storage;

class ImageServiceToFileSystem implements ImageServiceInterface
{
    private $rollbackStack = null;


    public function storeNewImage($image, $title): Image
    {
        try {
            $url = $this->storeImageInDisk($image);
            return $this->storeImageInDatabase($url, $title);
        } catch (Exception $e) {
            throw new Error('Erro ao gravar a imagem');
        }
    }

    public function deleteImageFile($imageUrl): bool
    {
        $imagePath = str_replace(asset('storage/'), '', $imageUrl);
        Storage::disk('public')->delete($imagePath);
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

    public function storeImageFromDisk($image): string
    {
        return $this->storeImageInDisk($image);
    }

    private function storeImageInDisk($image): string
    {
        $imageName = $image->storePublicly('uploads', 'public');
        $url = asset('storage/' . $imageName);
        $this->addToRollbackStack('deleteImageFromDisk', [$url]);
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
