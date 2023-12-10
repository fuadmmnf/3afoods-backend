<?php
// app/Helpers/FileHandler.php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHandler
{
    public static function upload($file, $directory, $maxSize = 4096, $allowedExtensions = ['pdf', 'doc', 'docx'])
    {
        try {
            // Validate file size
            if ($file && $file->getSize() > $maxSize * 1024) {
                throw new \Exception("File size exceeds the maximum allowed size of {$maxSize} KB.");
            }

            // Validate file extension
            $extension = $file ? $file->getClientOriginalExtension() : null;
            if ($file && !in_array(strtolower($extension), $allowedExtensions)) {
                throw new \Exception('Invalid file extension. Allowed extensions are: ' . implode(', ', $allowedExtensions));
            }

            // Generate a random name for the file
            $fileName = $file ? Str::random(20) . '.' . $extension : null;

            // Save the file to the specified directory
            if($file){
                $file->move(public_path("storage/files"), $fileName);
                return "/storage/files/$fileName";
            }
            return null;


        } catch (\Exception $e) {
            throw new \Exception("Failed to upload file. {$e->getMessage()}");
        }
    }
}
