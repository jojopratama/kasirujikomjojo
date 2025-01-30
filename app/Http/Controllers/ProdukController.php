<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Produk';
        $subtitle = 'Index';
        $produks = Produk::all();
        return view('admin.produk.index', compact('title', 'subtitle', 'produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Produk';
        $subtitle = 'Create';
        return view('admin.produk.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'NamaProduk' => 'required',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ]);

        $simpan = Produk::create($validate);
        
        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal']);
        }
    }

    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Produk';
        $subtitle = 'Edit';
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('title', 'subtitle', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validate = $request->validate([
            'NamaProduk' => 'required',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ]);
        
        $simpan = $produk->update($validate);
        
        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil Diubah']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $delete = $produk->delete();
        if ($delete) {
            return redirect(route('produk.index'))->with('success','Produk Berhasil Dihapus!');
        } else {
            return redirect(route('produk.index'))->with('error','Produk Gagal Dihapus!');
        }
    }
}