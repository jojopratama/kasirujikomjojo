<?php

namespace App\Http\Controllers;

use App\Models\LogStok;
use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\Facades\DNS1DFacade;

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
            'NamaProduk' => 'required|unique:produks,NamaProduk|string|max:255',
            'Harga' => 'required|numeric',
            'Stok' => 'required|numeric',
        ], [
          'NamaProduk.unique' => 'Nama produk sudah digunakan, silahkan pilih nama produk lain',
        ]);

        $validate['Users_Id'] = Auth::id();
        $simpan = Produk::create($validate);

        if ($simpan) {
            return response()->json(['status' => 200, 'message' => 'Produk Berhasil Ditambahkan']);
        } else {
            return response()->json(['status' => 500, 'message' => 'Produk Gagal Ditambahkan']);
        }
    }

    public function edit($id)
    {
      $produk = Produk::findOrFail($id);
      $title = 'Produk';
      $subtitle = 'Edit';
      return view('admin.produk.edit', compact('title', 'subtitle', 'produk'));
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

    public function cetaklabel(Request $request)
    {
        $id_produk = $request->id_produk; // Bisa berupa array atau nilai tunggal
        $barcodes = [];

        if (is_array($id_produk)) {
            // Jika $id_produk adalah array, proses setiap ID dalam array
            foreach ($id_produk as $id) {
                $id = (string) $id; // Pastikan ID adalah string 
                $harga = Produk::find($id)->Harga;
                $barcode = DNS1DFacade::getBarcodeHTML($id, 'C128'); // Membuat barcode untuk setiap ID
                $barcodes[] = ['barcode' => $barcode, 'harga' => $harga]; // Simpan barcode ke array
            }
        } else {
            // Jika hanya satu nilai, konversi menjadi string dan proses
            $id_produk = (string) $id_produk;
            $harga = Produk::find($id_produk)->Harga;
            $barcode = DNS1DFacade::getBarcodeHTML($id_produk, 'C128'); // Membuat barcode untuk setiap ID
            $barcodes[] = ['barcode' => $barcode, 'harga' => $harga]; // Simpan barcode ke array
        }
        $pdf = Pdf::loadView('admin.produk.cetaklabel', compact('barcodes'));
        
        $file_path = storage_path('app/public/barcodes.pdf');
        $pdf->save($file_path);

        return response()->json(['url' => asset('storage/barcodes.pdf')]);
    }



    public function destroy($id)
    {
      try {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
      } catch (\Exception $e) {
        return redirect()->route('produk.index')->with('error', 'Produk gagal dihapus');
      }
    }
}
