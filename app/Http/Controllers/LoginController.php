<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('login.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $user = Auth::attempt($data);
        if (!$user) {
            return redirect()->back()->with('pesan', 'Email atau password salah..!!');
        }

        if (Auth::user()->role == 'Admin') {
            return redirect()->route('admin.index')->with('success', 'Selamat Datang Admin');
        } elseif (Auth::user()->role == 'Manager') {
            return redirect()->route('manager.index')->with('success', 'Selamat Datang Manager');
        } else {
            return redirect()->route('user.index')->with('success', 'Selamat Datang Pelanggan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function logout($id)
    {
        Auth::logout();
        return redirect()->route('login.index')->with('success', 'Logout berhasil');
    } 
}
