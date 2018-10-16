<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\ProcessUploadedFileParts;

class File extends Model
{
    protected $fillable = [
        'email',
        'name',
        'description',
        'md5',
        'size',
        'type',
        'status',
        'last_update',
        'url'
    ];

    public static function uploaded(int $id)
    {
        $file = File::findOrFail($id);
        $file->status = 'uploaded';
        $file->save();

        ProcessUploadedFileParts::dispatch($file);
    }

    public static function completed(int $id, string $url)
    {
        $file = File::findOrFail($id);
        $file->status = 'completed';
        $file->url = $url;
        $file->save();
    }

    public static function wrongChecksum(int $id)
    {
        $file = File::findOrFail($id);
        $file->status = 'wrong_checksum';
        $file->save();
    }

    public static function getByUrl(string $url)
    {
        return File::where([
            ['url', '=', $url],
        ])->firstOrFail();
        
    }

    public static function create(array $attributes = [])
    {
        $exist_file = File::where([
            ['md5', '=', $attributes['md5']],
            ['email', '=', $attributes['email']],
            ['status', '=', 'completed'],
        ])->count();
        
        if ( $exist_file === 0 ) {
            $model = static::query()->create($attributes);
            return $model;
        }
        
        throw new \Exception('file exist');
    }
}
