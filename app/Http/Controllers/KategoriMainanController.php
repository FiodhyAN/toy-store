<?php

namespace App\Http\Controllers;

use App\Models\KategoriMainan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KategoriMainanController extends Controller
{
    public function index()
    {
        return view('admin.kategori_mainan', [
            'kategoris' => KategoriMainan::all()
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'nama' => 'required',
                'icon' => 'required'
            ], [
                'nama.required' => 'Nama kategori harus diisi',
                'icon.required' => 'Icon kategori harus diisi'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Failed to create barang',
                    'errors' => $validation->errors(),
                    'data' => null
                ], 400);
            }

            $kategori = KategoriMainan::create([
                'nama' => $request->nama,
                'icon' => $request->icon
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Barang created successfully',
                'errors' => [],
                'data' => ['kategoris' => $kategori]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create barang',
                'errors' => [$e->getMessage()],
                'data' => null
            ], 500);
        }
    }
}
