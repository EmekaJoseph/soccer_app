<?php

use Illuminate\Support\Facades\File;

use Intervention\Image\ImageManagerStatic as Image;


if (!function_exists('HelperUploadFile')) {
    /**
     * Method for processing single images
     */
    function HelperFileUpload($request, String $name, String $filePath, String $refCode = null)
    {
        if (!File::exists(public_path($filePath))) {
            File::makeDirectory(public_path($filePath));
        }

        $fileNameToStore = null;

        if ($request->hasFile($name)) {

            $originalTempFile =  $request->file($name);
            $filenamewithextension = $originalTempFile->getClientOriginalName();
            $filename              = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension             = $originalTempFile->getClientOriginalExtension();
            $fileNameToStore       = $refCode === null
                ? str_ireplace(' ', '_', $filename) . '_' . time() . '.' . $extension
                : $refCode . '_' . str_ireplace(' ', '_', $filename) . '_' . '.' . $extension;
            $originalTempFile->move(public_path($filePath), $fileNameToStore);
        }

        return $fileNameToStore;
    }
}

if (!function_exists('HelperUploadImageAndResize')) {
    function HelperUploadImageAndResize($folder, $file, $width = 100, $height = 100, $name = '')
    {

        if (!File::exists(public_path($folder))) {
            File::makeDirectory(public_path($folder));
        }

        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = $name . time() . "." . $extension;

        $img = Image::make($file);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($folder  . '/' . $filename);

        return $filename;
    }
}


if (!function_exists('HelperUnlinkFile')) {
    function HelperUnlinkFile($file)
    {
        if ($file !== null) {
            if (File::exists(public_path($file))) {
                File::delete(public_path($file));
            }
        }
    }
}
