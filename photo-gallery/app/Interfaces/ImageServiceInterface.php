<?php

namespace App\Interfaces;

use App\Models\Image;

interface ImageServiceInterface
{
    public function storeNewImage($image, $title): Image;
    public function deleteImageFile($image): bool;
    public function deleteDatabaseImage($imageUrl): bool;
}
