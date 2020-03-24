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
    public function index($limit = 10, $offset = 0)
    {
        $user = array();
        foreach (User::take($limit)->skip($offset)->get() as $p) {
            $item = $p;
            
            array_push($user, $item);
        };
        $data["user"] = $user;
        $data["status"] = 1;
        $data["count"] = User::count();
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

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
}