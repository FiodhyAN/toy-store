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

    public function edit(Request $request)
    {
        $mainan = Mainan::find($request->id);
        return response()->json([
            'data' => $mainan
        ]);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'old_produk_id' => 'required|exists:mainans,id',
            'nama_produk' => 'required',
            'id_kategori' => 'required|exists:kategori_mainans,id',
            'harga_produk' => 'required|numeric',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'old_produk_id.required' => 'ID produk harus diisi',
            'old_produk_id.exists' => 'ID produk tidak ditemukan',
            'nama_produk.required' => 'Nama produk harus diisi',
            'id_kategori.required' => 'Kategori produk harus diisi',
            'id_kategori.exists' => 'Kategori produk tidak ditemukan',
            'harga_produk.required' => 'Harga produk harus diisi',
            'harga_produk.numeric' => 'Harga produk harus berupa angka',
            'foto_produk.image' => 'Foto produk harus berupa gambar',
            'foto_produk.mimes' => 'Foto produk harus berupa gambar dengan format: jpeg, png, jpg, gif, svg',
            'foto_produk.max' => 'Foto produk maksimal berukuran 2MB'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
                'errors' => $validation->errors()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $mainan = Mainan::find($request->old_produk_id);
            $mainan->nama = $request->nama_produk;
            $mainan->harga = $request->harga_produk;
            $mainan->id_kategori = (int) $request->id_kategori;

            if ($request->hasFile('foto_produk')) {
                $file = $request->file('foto_produk');
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/products', $file_name);
                Storage::delete('public/products/' . $mainan->foto);
                $mainan->foto = $file_name;
            }

            $mainan->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        $tempFilePath = null;

        try {
            $mainan = Mainan::find($request->id);

            if (Storage::exists('public/products/' . $mainan->foto)) {
                $tempFilePath = 'public/temp/' . $mainan->foto;
                Storage::move('public/products/' . $mainan->foto, $tempFilePath);
            }

            $mainan->delete();
            DB::commit();

            if ($tempFilePath) {
                Storage::delete($tempFilePath);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            if ($tempFilePath && Storage::exists($tempFilePath)) {
                Storage::move($tempFilePath, 'public/products/' . $mainan->foto);
            }

            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
