<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Barang;
use Auth;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try{    
            $barang = new barang();
            $barang->jenis = $request->jenis;
            $barang->nama = $request->nama;
            $barang->merk = $request->merk;
            $barang->transmisi = $request->transmisi;
            $barang->harga = $request->harga;
            $barang->deskripsi = $request->deskripsi;
            $barang->save();
            return response()->json([
                'status' => '1',
                'pesan' => 'Tambah barang berhasil gan!',
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'pesan' => 'Tambah barang gagal gan!',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $barang = barang::find($id);
            $barang->delete();
            return response()->json([
                'status' => '1',
                'pesan' => 'Hapus data barang berhasil gan',
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'pesan' => 'Hapus data barang gagal gan',
            ]);
        }
    }
}
