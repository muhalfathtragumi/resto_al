<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = Transaksi::where('nama_pegawai', Auth::user()->name)->latest()->paginate(5);
        return view('user.pembelian.index', compact('transaksi'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $menu = Menu::all();
        return view('user.pembelian.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelanggan' => 'required',
            'menu' => 'required',
            'jumlah' => 'required',
            'nama_pegawai' => 'required',
        ]);

        $menu = Menu::whereNamaMenu($data['menu'])->first();

        if ($data['jumlah'] > $menu->ketersediaan) {
            return redirect(route('transaksi.index'))->with('error', 'Jumlah stok kurang dari yang dipesan !');
        }
        $transaksi = Transaksi::create([
            'nama_pelanggan' => $data['nama_pelanggan'],
            'menu' => $data['menu'],
            'jumlah' => $data['jumlah'],
            'total_harga' => $menu->harga * $data['jumlah'],
            'nama_pegawai' => $data['nama_pegawai']
        ]);

        $menu->update(['ketersediaan' => $menu->ketersediaan - $data['jumlah']]);

        return redirect()->route('pembelian.index')->with('success', 'Berhasil Menyimpan!');
    }

    public function edit(Transaksi $transaksi)
    {
        return view('user.pembelian.edit', compact('transaksi'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'menu' => 'required',
            'jumlah' => 'required',
            'total_harga' => 'required',
            'nama_pegawai' => 'required',
        ]);

        $transaksi->update($request->all());
        return redirect()->route('transaksi.index')->with('success', 'Berhasil Update!');
    }

    public function destroy($id)
    {
        $category = Transaksi::findOrFail($id);
        if($category) {
            $category->delete();
            return redirect()->route('transaksi.index')->with('success', 'Berhasil Hapus!');
        }
        return redirect()->route('transaksi.index');
    }

    public function filtering(Request $request)
    {
        $transaksis = Transaksi::all()->sortByDesc('id');
        if (isset($request->start_date) && isset($request->end_date)) {
            $transaksis = Transaksi::whereBetween('created_at', [Carbon::parse($request->start_date), Carbon::parse(date($request->end_date) . ' 23:59:59')])->get()->sortByDesc('id');
        }
        return view('manager.laporan.load', compact('transaksis'))->render();
    }
}