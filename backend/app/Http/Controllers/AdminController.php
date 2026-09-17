<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Menampilkan Dashboard Utama Admin
    public function index()
    {
        return view('admin.dashboard');
    }

    // Menampilkan Halaman Modify Roles
    public function roles()
    {
        return view('admin.roles');
    }

    // Menampilkan Halaman Master Data Facilities
    public function facilities()
    {
        return view('admin.facilities');
    }

    // Menampilkan Halaman Summary / Laporan
    public function summary()
    {
        return view('admin.summary');
    }
}