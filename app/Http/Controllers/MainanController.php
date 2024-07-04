<?php

namespace App\Http\Controllers;

use App\Models\KategoriMainan;
use App\Models\Mainan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MainanController extends Controller
{
    public function index()
    {
        return view('admin.mainan', [
            'kategoris' => KategoriMainan::all(),
            'mainans' => Mainan::with('kategori')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'id_kategori' => 'required|exists:kategori_mainans,id',
            'harga_produk' => 'required|numeric',
            'foto_produk' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi',
            'id_kategori.required' => 'Kategori produk harus diisi',
            'id_kategori.exists' => 'Kategori produk tidak ditemukan',
            'harga_produk.required' => 'Harga produk harus diisi',
            'harga_produk.numeric' => 'Harga produk harus berupa angka',
            'foto_produk.required' => 'Foto produk harus diisi',
            'foto_produk.image' => 'Foto produk harus berupa gambar',
            'foto_produk.mimes' => 'Foto produk harus berupa gambar dengan format: jpeg, png, jpg, gif, svg',
            'foto_produk.max' => 'Foto produk maksimal berukuran 2MB'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create product',
                'errors' => $validation->errors()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $file = $request->file('foto_produk');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/products', $file_name);

            Mainan::create([
                'nama' => $request->nama_produk,
                'harga' => $request->harga_produk,
                'foto' => $file_name,
                'id_kategori' => (int) $request->id_kategori,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Storage::delete('public/products/' . $file_name);
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
