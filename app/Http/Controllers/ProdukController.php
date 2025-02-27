<?php

namespace App\Http\Controllers;

use App\Models\LogStok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $title = 'Produk';
        $subtitle = 'Index';
        $produks = Produk::all();
        return view('admin.produk.index', compact('title', 'subtitle', 'produks'));
    }

    public function create()
    {
        $title = 'Produk';
        $subtitle = 'Create';
        return view('admin.produk.create', compact('title', 'subtitle'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'NamaProduk' => 'required',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ]);
        
        $validate['Users_Id'] = Auth::id();
        $simpan = Produk::create($validate);
        
        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil Ditambahkan']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal Ditambahkan']);
        }
    }

    public function update(Request $request, Produk $produk)
    {
        $validate = $request->validate([
            'NamaProduk' => 'required',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ]);
        
        $validate['Users_Id'] = Auth::id();
        $simpan = $produk->update($validate);
        
        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil Diubah']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal Diubah']);
        }
    }

    public function tambahStok(Request $request, $id)
    {
        $validate = $request->validate([
            'stok' => 'required|numeric|min:1',
        ]);
    
        $produk = Produk::findOrFail($id);
        $stokSebelumnya = $produk->Stok;
        $produk->Stok += $validate['stok'];
        $update = $produk->save();
    
        if ($update) {
            LogStok::create([
                'ProdukId' => $produk->id,
                'JumlahProduk' => $validate['stok'],
                'Users_Id' => Auth::id(),
            ]);
    
            return response()->json(['status' => 200, 'message' => 'Stok berhasil ditambahkan']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Stok gagal ditambahkan']);
        }
    }

    public function logproduk()
    {
        $title = 'Produk';
        $subtitle = 'Log Produk';
        
        $produks = LogStok::join('produks', 'log_stoks.ProdukId', '=', 'produks.id')
            ->join('users', 'log_stoks.Users_Id', '=', 'users.id')
            ->select('log_stoks.JumlahProduk', 'log_stoks.created_at', 'produks.NamaProduk', 'users.name')
            ->get();
    
        return view('admin.produk.logproduk', compact('title', 'subtitle', 'produks'));
    }
}
