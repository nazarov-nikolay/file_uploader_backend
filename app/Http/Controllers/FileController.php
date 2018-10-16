<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $last_update = $request->input('lastModified');
        $last_update = $last_update ? $last_update : 0;
        
        $request->merge([ 'last_update' => $last_update ]);
        return File::create($request->all());
    }

    public function download(string $email_hash, string $file_hash)
    {
        $path = $email_hash 
              . '/'
              . $file_hash;

        $file_path = 'public' 
                    . '/' 
                    . $path;
        
        $file = File::getByUrl($path);
        if ( Storage::exists($file_path) ) {
            return response()->download( storage_path('app/') . $file_path, $file->name);
        }
    }
}
