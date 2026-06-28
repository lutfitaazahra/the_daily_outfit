<?php

namespace App\Helpers;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryHelper
{
    protected static function getCloudinary(): Cloudinary
    {
        return new Cloudinary(
            Configuration::instance(env('CLOUDINARY_URL'))
        );
    }

    public static function upload(string $filePath, string $folder = 'products'): string
    {
        $cloudinary = static::getCloudinary();

        $result = $cloudinary->uploadApi()->upload($filePath, [
            'folder' => $folder,
        ]);

        return $result['secure_url'];
    }

    public static function delete(string $publicId): void
    {
        $cloudinary = static::getCloudinary();
        $cloudinary->uploadApi()->destroy($publicId);
    }
}