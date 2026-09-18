<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function roles()
    {
        return view('admin.roles');
    }

    public function facilities()
    {
        return view('admin.facilities');
    }

    public function summary()
    {
        return view('admin.summary');
    }
}