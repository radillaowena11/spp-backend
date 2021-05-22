<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_petugas'  => 'required|string|max:255',
            'username'      => 'required|string|unique:user',
            'password'      => 'required|string|min:6',
            'level'         => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'nama_petugas'  => $request->get('nama_petugas'),
            'username'      => $request->get('username'),
            'password'      => Hash::make($request->get('password')),
            'level'         => $request->get('level'),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'logged'    => false,
                    'message'   => 'Invalid username and password'
                ]);
            }
        } catch(JWTException $e){
            return response()->json([
                'logged'    => true,
                'message'   => 'Generate Token Failed'
            ]);
        }
        return response()->json([
            "logged"    => true,
            "token"     => $token,
            "message"   => 'Login berhasil'
        ]);
    }


    public function LoginCheck(){
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return response()->json([
                    'auth'      => false,
                    'message'   => 'Invalid token'
                ]);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json([
                'auth'      => false,
                'message'   => 'Token expired'
            ], $e->getStatusCode());
        } catch(Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json([
                'auth'      => false,
                'message'   => 'Invalid token'
            ], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json([
                'auth'      => false,
                'message'   => 'Token absent'
            ], $e->getStatusCode());
        }

        return response()->json([
            "auth"  => true,
            "user"  => $user
        ], 201);
    }


    public function index()
    {
        try{
            $data["count"] = User::count();
            $user = array();

            foreach (User::all() as $p) {
                $item = [
                    "id"            => $p->id,
                    "nama_petugas"  => $p->nama_petugas,
                    "username"      => $p->username,
                    // "password"      => $p->password,
                    "level"         => $p->level,
                    "created_at"    => $p->created_at,
                    "updated_at"    => $p->updated_at
                ];

                array_push($user, $item);
            }
            $data["user"]   = $user;
            $data["status"] = 1;
            return response($data);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function getAll($limit = 10, $offset = 0)
    {
        try{
            $data["count"] = User::count();
            $user = array();

            foreach (User::take($limit)->skip($offset)->get() as $p){
                $item = [
                    "id"            => $p->id,
                    "nama_petugas"  => $p->nama_petugas,
                    "username"      => $p->username,
                    // "password"      => $p->password,
                    "level"         => $p->level,
                    "created_at"    => $p->created_at,
                    "updated_at"    => $p->updated_at,
                ];

                array_push($user, $item);
            }
            $data["user"]   = $user;
            $data["status"] = 1;
            return response($data);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'nama_petugas'  => 'required|string|max:255',
                'username'      => 'required|string|unique:user',
                'password'      => 'required|string|min:6',
                'level'         => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }

            $data = new User();
            $data->nama_petugas = $request->input('nama_petugas');
            $data->username     = $request->input('username');
            $data->password     = Hash::make($request->password);
            $data->level        = $request->input('level');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data petugas berhasil ditambahkan!'
            ], 201);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_petugas'  => 'required|string|max:255',
                'username'      => 'required|string|unique:user',
                'password'      => 'required|string|min:6',
                'level'         => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => '0',
                    'message'   => $validator->errors()
                ]);
            }

            $data = User::where('id', $id)->first();
            $data->nama_petugas = $request->input('nama_petugas');
            $data->username     = $request->input('username');
            $data->password     = Hash::make($request->password);
            $data->level        = $request->input('level');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data petugas berhasil diubah!'
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function delete($id)
    {
        try{
            $delete = User::where("id", $id);
            if($delete->first()->level != 'admin'){
                $delete->delete();

                if($delete){
                    return response([
                        "status"    => 1,
                        "message"   => "Data berhasil dihapus."
                    ]);
                } else {
                    return response([
                        "status"    => 0,
                        "message"   => "Data gagal dihapus."
                    ]);
                }
            } else {
                return response([
                    "status"    => 0,
                    "message"   => "User admin tidak boleh dihapus."
                ]);
            }
        } catch(\Exception $e){
            return response([
                "status"    => 0,
                "message"   => $e->getMessage()
            ]);
        }
    }


    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())){
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
