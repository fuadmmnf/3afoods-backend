<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHandler
{
    /**
     * Handle the image upload.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $directory
     * @param int $maxSize
     * @param array $allowedExtensions
     * @return string
     * @throws \Exception
     */
    public static function upload($image, $directory, $maxSize = 5048, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'])
    {
        try {
//            // Validate image size
//            if ($image->getSize() > $maxSize * 1024) {
//                throw new \Exception("Image size exceeds the maximum allowed size of {$maxSize} KB.");
//            }

            // Validate image extension
            $extension = $image->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                throw new \Exception('Invalid image extension. Allowed extensions are: ' . implode(', ', $allowedExtensions));
            }

            // Generate a random name for the image
            $imageName = Str::random(20) . '.' . $extension;


            // Save the image to the specified directory
            $path = $image->storeAs($directory, $imageName, 'public');
            return url(Storage::url($path));

        } catch (\Exception $e) {
            throw new \Exception("Failed to upload image. {$e->getMessage()}");
        }
    }
}
