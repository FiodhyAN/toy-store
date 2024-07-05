<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mainan;
use App\Models\KategoriMainan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mainan = Mainan::with('kategori')
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('id_kategori', $request->kategori);
            })
            ->get();
        $kategori =  '';
        if($request->has('kategori'))
        {
            $kategori = " - ".KategoriMainan::find($request->kategori)->nama;
        }

        $isLogin = $request->user() ? true : false;
        return view('dashboard.index', compact('mainan', 'kategori', 'isLogin'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'id_produk' => 'required|exists:mainans,id',
                'jumlah_barang' => 'required|integer|min:1',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'bank' => 'required|string|in:BCA,BRI'
            ], [
                'id_produk.required' => 'Produk harus diisi',
                'id_produk.exists' => 'Produk tidak ditemukan',
                'jumlah_barang.required' => 'Jumlah barang harus diisi',
                'jumlah_barang.integer' => 'Jumlah barang harus berupa angka',
                'jumlah_barang.min' => 'Jumlah barang minimal 1',
                'bukti_pembayaran.required' => 'Bukti pembayaran harus diisi',
                'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar',
                'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berupa gambar dengan format jpeg, png, jpg, gif, atau svg',
                'bank.required' => 'Bank harus diisi',
                'bank.string' => 'Bank harus berupa string',
                'bank.in' => 'Bank harus BCA atau BRI'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Failed to purchase product',
                    'errors' => $validation->errors(),
                    'data' => null
                ], 400);
            }

            $file = $request->file('bukti_pembayaran');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/bukti_pembayaran', $file_name);

            $transaksi = Transaksi::create([
                'id_user' => $request->user()->id,
                'id_mainan' => $request->id_produk,
                'jumlah' => $request->jumlah_barang,
                'total_harga' => Mainan::find($request->id_produk)->harga * $request->jumlah_barang,
                'foto' => $file_name,
                'bank' => $request->bank
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Barang created successfully',
                'errors' => [],
                'data' => ['transaksi' => $transaksi]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to purchase product',
                'errors' => [$e->getMessage()],
                'data' => null
            ], 500);
        }
    }

    public function history(Request $request)
    {
        $transaksi = Transaksi::with(['mainan', 'user'])
            ->when(!auth()->user()->is_admin, function ($query) {
                return $query->where('id_user', auth()->user()->id);
            })
            ->get();

        return view('dashboard.history', compact('transaksi'));
    }
}