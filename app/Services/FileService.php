<?php

namespace App\Services;

use Illuminate\Http\Request;

class FileService
{
    public function createAvatar(Request $request, string $storePath = '/images/'): string
    {
        $uploadedFiles = $request->file('FILE');
        $fileName = $uploadedFiles->getClientOriginalName();
        $username = $request->user()->getUsername();
        $directory = public_path($storePath . $username);
        $uploadedFiles->move($directory, $fileName);

        return $storePath . $username . '/' . $fileName;
    }
}
