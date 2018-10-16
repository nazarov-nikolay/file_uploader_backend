<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FilePart extends Model
{
    public static function create(array $attributes = [])
    {
        $file = File::findOrFail($attributes['id']);
        $salt = Config::get('app.salt');

        $email_md5 = md5( $salt . $file->email);
        $file_md5 = md5( $salt . $file->md5);
        $path = $email_md5 . '/' . $file_md5 . '/' . $attributes['part_counter'];
        $content = $attributes['content'];

        $result = Storage::put($path, $content); 
        if ( $result ) {
            if ( $attributes['percent'] == 100) {
                File::uploaded($file->id);
            }
            return [
                "completed" => true,
                "message" => "ok"
            ];
        }

        throw new \Exception('Error');
    }
}
