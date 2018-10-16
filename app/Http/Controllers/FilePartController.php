<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FilePart;

class FilePartController extends Controller
{
    public function store(Request $request)
    {
        return FilePart::create($request->all());
    }
}
