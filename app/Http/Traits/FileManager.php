<?php

namespace App\Http\Traits;

trait FileManager
{
    /**
     * Validates the file from the request & persists it into storage
     * @param String $requestAttributeName from request
     * @param String $folder
     * @param String $disk
     * @return String $path
     */
    public function upload($requestAttributeName = null, $folder = '', $disk = 'public'){
        $path = null;
        if(request()->hasFile($requestAttributeName) && request()->file($requestAttributeName)->isValid()){
            $path = 'storage/'.request()->file($requestAttributeName)->store($folder, $disk);
        }
        return $path;
    }

    /**
     * Validates the file from the request & persists it into storage then unlink old one
     * @param String $requestAttributeName from request
     * @param String $folder
     * @param String $oldPath
     * @return String $path
     */
    public function updateFile($requestAttributeName = null, $folder = '',$oldPath){
        $path = null;
        if(request()->hasFile($requestAttributeName) && request()->file($requestAttributeName)->isValid()){
            $path = $this->upload($requestAttributeName,$folder);
            if(file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        return $path;
    }

    /**
     * Delete the file from the path
     * @param String $oldPath
     */

    public function deleteFile($oldPath){
        if(file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
}
