<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data["count"] = User::count();
        $user = array();

        foreach (User::all() as $p) {
            $item = [
                "id"          => $p->id,
                "nama"        => $p->nama,
                "email"    	  => $p->email,
                "created_at"  => $p->created_at,
                "updated_at"  => $p->updated_at
            ];

            array_push($user, $item);
        }
        $data["user"] = $user;
        $data["status"] = 1;
        return response($data);
    }

    public function getAll($limit = 10, $offset = 0)
    {
        $data["count"] = User::count();
        $user = array();

        foreach (User::take($limit)->skip($offset)->get() as $p) {
            $item = [
                "id"          => $p->id,
                "nama"        => $p->nama,
                "email"    	  => $p->email,
                "password"    => $p->password,
                "created_at"  => $p->created_at,
                "updated_at"  => $p->updated_at
            ];

            array_push($user, $item);
        }
        $data["user"] = $user;
        $data["status"] = 1;
        return response($data);
    }

    public function login(Request $request)
    {
        $cred = $request->only('email', 'password');
        try {
            if (! $token =  JWTAUth::attempt($cred)) {
                return response()->json(['Error' => 'Tidak Valid'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['Error' => 'Tidak bisa membuat token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'nama' => $request->get('nama'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'nama' => 'required|string|max:255',
			'email' => 'required|string|email|max:255',
			'password' => 'required|string|min:6',
		]);

		if($validator->fails()){
			return response()->json([
				'status'	=> '0',
				'message'	=> $validator->errors()
			]);
		}

		$user = User::where('id', $request->id)->first();
		$user->name 	= $request->name;
		$user->email 	= $request->email;
		$user->password = Hash::make($request->password);
		$user->save();


		return response()->json([
			'status'	=> '1',
			'message'	=> 'Data berhasil diubah'
		], 201);
	}

    public function delete($id)
    {
        try{
            User::where("id", $id)->delete();

            return response([
            	"status"	=> 1,
                "message"   => "Data berhasil dihapus."
            ]);

        } catch(\Exception $e){

            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            
            ]);
        }
    }

    public function LoginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					]);
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token expired'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token absent'
					], $e->getStatusCode());
		}

		 return response()->json([
		 		"auth"      => true,
                "user"    => $user
		 ], 201);
	}

	public function logout(Request $request)
    {

        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                "logged"    => false,
                "message"   => 'Logout berhasil'
            ], 201);
        } else {
            return response()->json([
                "logged"    => true,
                "message"   => 'Logout gagal'
            ], 201);
        }
    }
}