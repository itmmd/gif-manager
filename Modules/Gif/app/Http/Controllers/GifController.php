<?php

namespace Modules\Gif\Http\Controllers;

use App\Http\Controllers\Controller;

class GifController extends Controller
{
    public function index()
    {
        return view('gif::index');
    }
}
