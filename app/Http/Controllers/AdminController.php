<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\File;

class AdminController extends Controller
{
    private const PAGE = 100;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::orderBy('id', 'desc')->paginate(self::PAGE);

        return view('admin.admin', compact('files'));
    }

    public function info(int $file_id)
    {
        $file = File::findOrFail($file_id);

        return view('admin.admin_edit', ['file' => $file]);
    }

    public function edit(Request $request)
    {
        $file = File::findOrFail( $request->file_id );
        
        $reditect_to = route('admin_file_info', ['file_id' => $file->id]);

        switch ($request->action) {
            case 'update':
                $file->description = $request->description;
                $file->save();
                break;
            case 'delete':
                $file->delete(); // soft delete ? 
                Storage::delete('public' . '/' . $file->url); //rework
                break;
        }
        
        return redirect()->route('admin_file_list');
    }

}
