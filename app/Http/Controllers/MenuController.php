<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $menu = Menu::latest()->simplePaginate(5);

        return view('manager.menu.index', compact('menu'))
            ->with('i', (request()->input('page', 1) - 1) * 5);;

        // $Menu = Menu::oldest()->paginate(5);
        // return view('manager.menu.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('manager.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $menu = new Menu;
        $menu->menu = $request->input('menu');
        if($request->hasfile('gambar'))
        {
            $file = $request->file('gambar');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/produks/', $filename);
            $menu->gambar = $filename;
        }
        $menu->harga = $request->input('harga');
        $menu->jumlah = $request->input('jumlah');
        $menu->deskripsi = $request->input('deskripsi');
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Berhasil Menyimpan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::find($id);
        return view('manager.menu.edit', compact('menu'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);
        $menu->menu = $request->input('menu');
        if($request->hasfile('gambar'))
        {
            $destination = 'uploads/produks/'.$menu->gambar;
            if(File::exists($destination))
            {
                file::delete($destination);
            }
            $file = $request->file('gambar');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/produks/', $filename);
            $menu->gambar = $filename;
        }
        $menu->harga = $request->input('harga');
        $menu->jumlah = $request->input('jumlah');
        $menu->deskripsi = $request->input('deskripsi');
        $menu->update();

        $menu->update($request->all());
        return redirect()->route('menu.index')->with('success', 'Berhasil Update!');

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Menu::findOrFail($id);
        if($category) {
            $category->delete();
            return redirect()->route('menu.index')->with('success', 'Berhasil Hapus!');
        }
        return redirect()->route('menu.index');
        //
    }
}
