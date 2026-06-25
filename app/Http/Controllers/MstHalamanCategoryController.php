<?php

namespace App\Http\Controllers;

use App\Models\HalamanCategory;
use Illuminate\Http\Request;

class MstHalamanCategoryController extends Controller
{
    public function index()
    {
       $halaman = HalamanCategory::orderBy('id', 'desc')
       ->get();


       return view('linknavigasi.halaman', compact('halaman'));
    }
}
