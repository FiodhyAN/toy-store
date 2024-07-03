<?php

namespace App\Http\Controllers;

use App\Models\Mainan;
use Illuminate\Http\Request;

class MainanController extends Controller
{
    public function index()
    {
        return view('admin.mainan', [
            'mainans' => Mainan::with('kategori')->get()
        ]);
    }
}
