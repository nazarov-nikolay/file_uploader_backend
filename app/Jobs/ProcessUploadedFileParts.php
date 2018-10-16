<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use App\File;
use Mail;

class ProcessUploadedFileParts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->file;

        $salt = Config::get('app.salt');

        $email_md5 = md5( $salt . $file->email);
        $file_md5 = md5( $salt . $file->md5);
        $path = $email_md5 . '/' . $file_md5;
        $result_file_path = $path . '/' . 'file';

        $parts = Storage::disk('local')->files($path);
        natsort($parts);
        
        $result_file = fopen( storage_path('app/') . $result_file_path, 'w');
        foreach ($parts as $part) {
            $basename = basename($part);
            if ( strpos($basename, '.') === 0 )
                continue;

            $data = file_get_contents(storage_path('app/') . $part);
            $content = base64_decode($data);
            fwrite($result_file, $content);

            Storage::delete($part);
        } 
        fclose($result_file);

        $contents = Storage::get($result_file_path);
        if ( md5($contents) == $file->md5 ) {
            $result_file_path_public = 'public' . '/' . $path;
            Storage::move($result_file_path, $result_file_path_public);
            File::completed($file->id, $path);

            $link = env('APP_URL') . '/' . 'uploaded' . '/' . $path;
            Mail::send('emails.notification', ['link' => $link], function ($m) use ($file) {
                $m->to($file->email)->subject('File uploaded');
            });
        }
        else {
            File::wrongChecksum($file->id);
            Storage::delete($result_file_path);
        }

        Storage::deleteDirectory($path);
    }
}
