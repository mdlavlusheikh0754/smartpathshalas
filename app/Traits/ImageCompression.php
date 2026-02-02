<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ImageCompression
{
    /**
     * Compress and store an uploaded image
     *
     * @param UploadedFile $file
     * @param string $path
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @return string
     */
    protected function compressAndStoreImage(
        UploadedFile $file, 
        string $path, 
        int $maxWidth = 800, 
        int $maxHeight = 800, 
        int $quality = 85
    ): string {
        // Create image manager with GD driver
        $manager = new ImageManager(new Driver());
        
        // Read the uploaded image
        $image = $manager->read($file->getPathname());
        
        // Resize image while maintaining aspect ratio
        $image->scaleDown($maxWidth, $maxHeight);
        
        // Generate unique filename
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;
        
        // Encode with compression
        if (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg'])) {
            $encoded = $image->toJpeg($quality);
        } elseif (strtolower($file->getClientOriginalExtension()) === 'png') {
            $encoded = $image->toPng();
        } else {
            $encoded = $image->toJpeg($quality);
        }
        
        // Store the compressed image
        Storage::disk('public')->put($fullPath, $encoded);
        
        return $fullPath;
    }
    
    /**
     * Compress logo specifically (square format)
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    protected function compressLogo(UploadedFile $file, string $path): string
    {
        return $this->compressAndStoreImage($file, $path, 400, 400, 90);
    }
    
    /**
     * Compress photo (rectangular format)
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    protected function compressPhoto(UploadedFile $file, string $path): string
    {
        return $this->compressAndStoreImage($file, $path, 600, 800, 85);
    }
}