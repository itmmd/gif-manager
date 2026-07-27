<?php

namespace Modules\Ai\Http\Controllers;

use App\Http\Controllers\Controller;

class AiController extends Controller
{
    public function index()
    {
        return view('ai::index');
    }
}
