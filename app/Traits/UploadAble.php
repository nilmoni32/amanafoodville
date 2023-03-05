<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Creating image upload logic using this trait
 * Trait Uploadedable
 * @package App\Traits
 */

trait UploadAble
{
    /**
     *@param UploadedFile $file
     *@param null $folder [directory]
     *@param string $disk [where uploaded images will store... config/filesystem.php]
     *@param null $filename
     *@return false|string 
     */
    public function uploadOne(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $random = Str::random(25);
        $name = !is_null($filename) ? $filename : $random; // if $filename is not available then generating random name.
        // return image path location e.g img/filename
        // To create a symbolic link at public/storage which points to the storage/app/public directory
        // we need to run the php artisan storage:link command.
        return $file->storeAs(
            $folder,
            $name . "." . $file->getClientOriginalExtension(),
            $disk
        );
    }

    /**
     * @param null $path
     * @param string $disk
     */
    public function deleteOne($path = null, $disk = 'public')
    {
        Storage::disk($disk)->delete($path);
    }
}