<?php

namespace App\Http\Services\Mutual;

use App\Http\Traits\FileManager;

class FileManagerService
{
    use FileManager;
    public function handle($requestAttributeName, $folderName, $target = null) {
        $path = $this->upload($requestAttributeName, $folderName);
        if (!is_null($target)) {
            $this->deleteFile($target);
        }
        return $path;
    }

}
