<?php

namespace App\Http\Controllers;

use App\Barang;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
	        $data["count"] = Barang::count();
	        $barang = array();

	        foreach (Barang::all() as $p) {
	            $item = [
	                "id"            => $p->id,
	                "jenis" 	    => $p->jenis,
	                "nama"          => $p->nama,
	                "gambar" 	    => $p->gambar,
	                "deskripsi" 	=> $p->deskripsi,
	                "harga" 	    => $p->harga,
	                "created_at"    => $p->created_at,
	                "updated_at"    => $p->updated_at,
	            ];

	            array_push($barang, $item);
	        }
	        $data["barang"] = $barang;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = Barang::count();
	        $barang = array();

	        foreach (Barang::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"            => $p->id,
	                "jenis" 	    => $p->jenis,
	                "nama"          => $p->nama,
	                "gambar" 	    => $p->gambar,
	                "deskripsi" 	=> $p->deskripsi,
	                "harga" 	    => $p->harga,
	                "created_at"    => $p->created_at,
	                "updated_at"    => $p->updated_at
	            ];

	            array_push($barang, $item);
	        }
	        $data["barang"] = $barang;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
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
    		$validator = Validator::make($request->all(), [
    			'jenis'         => 'required',
				'nama'	        => 'required|string|max:255',
				'gambar'        => 'string',
                'deskripsi'	    => 'required|string|max:500',
				'harga'		    => 'required|numeric',
                ]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Barang();
	        $data->jenis        = $request->input('jenis');
	        $data->nama         = $request->input('nama');
	        $data->gambar       = $request->input('gambar');
            $data->deskripsi    = $request->input('deskripsi');
            $data->harga        = $request->input('harga');
            $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data berhasil ditambahkan!'
    		], 201);

        }catch(\Exception $e){

            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
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
        try {
            $validator = Validator::make($request->all(), [
                'jenis'         => 'required',
				'nama'	        => 'required|string|max:255',
				'gambar'        => 'string',
                'deskripsi'	    => 'required|string|max:500',
				'harga'		    => 'required|numeric',
          ]);
  
            if($validator->fails()){
                return response()->json([
                    'status'	=> '0',
                    'message'	=> $validator->errors()
                ]);
            }
  
            $data = Barang::where('id', $id)->first();
            $data->jenis        = $request->input('jenis');
	        $data->nama         = $request->input('nama');
	        $data->gambar       = $request->input('gambar');
            $data->deskripsi    = $request->input('deskripsi');
            $data->harga        = $request->input('harga');
            $data->save();
  
            return response()->json([
                'status'	=> '1',
                'message'	=> 'Data berhasil diubah'
            ]);
          
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
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

            $delete = Barang::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data gagal dihapus."
              ]);
            }
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }
}
