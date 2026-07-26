<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth::index');
    }

    public function create()
    {
        return view('auth::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('auth::show');
    }

    public function edit($id)
    {
        return view('auth::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
