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

    public function edit(Request $request)
    {
        $barang = KategoriMainan::find($request->id);

        return response()->json([
            'data' => $barang
        ]);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:kategori_mainans,id',
            'nama_kategori' => 'required',
            'icon' => 'required'
        ], [
            'old_kategori_id.required' => 'ID kategori harus diisi',
            'old_kategori_id.exists' => 'ID kategori tidak ditemukan',
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'icon.required' => 'Icon kategori harus diisi'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Failed to update barang',
                'errors' => $validation->errors(),
                'data' => null
            ], 400);
        }

        DB::beginTransaction();
        try {
            $kategori = KategoriMainan::find($request->id);
            $kategori->nama = $request->nama_kategori;
            $kategori->icon = $request->icon;
            $kategori->save();

            DB::commit();

            return response()->json([
                'message' => 'Barang updated successfully',
                'errors' => [],
                'data' => ['kategoris' => $kategori]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update barang',
                'errors' => [$e->getMessage()],
                'data' => null
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $kategori = KategoriMainan::find($request->id);
            $kategori->delete();

            DB::commit();

            return response()->json([
                'message' => 'Barang deleted successfully',
                'errors' => [],
                'data' => ['kategoris' => $kategori]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to delete barang',
                'errors' => [$e->getMessage()],
                'data' => null
            ], 500);
        }
    }
}
