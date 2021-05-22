<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Spp;
use App\Kelas;
use App\Siswa;
use Config;
use JWTAuth;
use Illuminate\Support\Facades\Hash;
use DB;

class SiswaController extends Controller
{
    public function login(Request $request)
    {
        \Config::set('jwt.user', 'App\Siswa'); 
		\Config::set('auth.providers.users.model', \App\Siswa::class);
        // $credentials = $request->only('username', 'password');
        $akunSiswa = Siswa::where('username',$request->input('username'))->first();
        // $token = JWTAuth::attempt($credentials);
        // return $token;
        try {
            if(!$token = JWTAuth::fromUser($akunSiswa)){
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
    public function index()
    {
        try{
            $data["count"] = Siswa::count();
            $siswa = array();
            $dataSiswa = DB::table('siswa')->join('kelas', 'kelas.id','=', 'siswa.id_kelas')
                                           ->select('siswa.id', 'kelas.nama_kelas',
                                           'siswa.nis', 'siswa.nama', 'siswa.alamat', 'siswa.no_telp', 
                                           'siswa.id_kelas')
                                           ->get();
            foreach ($dataSiswa as $s) {
                $item = [
                    "id"            => $s->id,
                    // "id_kelas"      => $s->id_kelas,
                    "kelas"         => $s->nama_kelas,
                    // "id_spp"        => $s->id_spp,
                    // "spp"           => $s->tahun,
                    "nis"           => $s->nis,
                    "nama_siswa"    => $s->nama,
                    "alamat"        => $s->alamat,
                    "no_telp"       => $s->no_telp,
                ];
                array_push($siswa, $item);
            }
            $data["siswa"] = $siswa;
            $data["status"] = 1;
            return response($data);
        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function get()
    {
        try{
            $data["count"]  = Siswa::count();
            $dataSiswa      = Siswa::get();

            $data["siswa"]  = $dataSiswa;
            $data["status"] = 1;
            return response()->json($data);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function getAll($limit = 10, $offset = 0)
    {
        try {
            $data["count"] = Siswa::count();
            $siswa = array();
            $dataSiswa = DB::table('siswa')->join('kelas', 'kelas.id','=', 'siswa.id_kelas')
                                           ->select('siswa.id', 'kelas.nama_kelas',
                                           'username','kelas.kompetensi_keahlian',  
                                           'siswa.nis', 'siswa.nama', 'siswa.alamat', 'siswa.no_telp', 
                                           'siswa.id_kelas')
                                           ->skip($offset)
                                           ->take($limit)
                                           ->get();
            
            foreach ($dataSiswa as $s) {
                $item = [
                    "id"            => $s->id,
                    // "id_kelas"      => $s->id_kelas,
                    "kelas"         => $s->nama_kelas,
                    "kompetensi_keahlian"         => $s->kompetensi_keahlian,
                    // "id_spp"        => $s->id_spp,
                    // "spp"           => $s->tahun,
                    "nis"           => $s->nis,
                    "nama_siswa"    => $s->nama,
                    "username"    => $s->username,
                    "alamat"        => $s->alamat,
                    "no_telp"       => $s->no_telp,
                ];
                array_push($siswa, $item);
            }
            $data["siswa"] = $siswa;
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
                'nis'       => 'required|integer|unique:siswa',
                'nama'      => 'required|string|max:255',
                'id_kelas'  => 'required|integer',
                'alamat'    => 'required|string',
                'no_telp'   => 'required|string',
                'username'    => 'required|string|unique:siswa',
                'password'    => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }
            if(Kelas::where('id', $request->input('id_kelas'))->count() > 0){
                $data = new Siswa();
                $data->nis      = $request->input('nis');
                $data->nama     = $request->input('nama');
                $data->username = $request->input('username');
                $data->password = Hash::make($request->get('password'));
                $data->id_kelas = $request->input('id_kelas');
                $data->alamat   = $request->input('alamat');
                $data->no_telp  = $request->input('no_telp');
                $data->save();

                    return response()->json([
                        'status'    => '1',
                        'message'   => 'Data siswa berhasil ditambahkan!'
                    ], 201);
            } else {
                return response()->json([
                    'status'    => '0',
                    'message'   => 'Data siswa gagal ditambahkan.'
                ]);
            }
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
                'nis'       => 'required|integer',
                'nama'      => 'required|string|max:255',
                'id_kelas'  => 'required|integer',
                'alamat'    => 'required|string',
                'no_telp'   => 'required|string',
                'username'    => 'required|string',
                'password'    => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => '0',
                    'message'   => $validator->errors()
                ]);
            }

            $data = Siswa::where('id', $id)->first();
            $data->nis      = $request->input('nis');
            $data->nama     = $request->input('nama');
            $data->username = $request->input('username');
            $data->password = Hash::make($request->get('password'));
            $data->id_kelas = $request->input('id_kelas');
            $data->alamat   = $request->input('alamat');
            $data->no_telp  = $request->input('no_telp');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data siswa berhasil diubah'
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
            $delete = Siswa::where("id", $id)->delete();
            if($delete){
                return response([
                    "status"    => 1,
                    "message"   => "Data siswa berhasil dihapus."
                ]);
            } else {
                return response([
                    "status"    => 0,
                    "message"   => "Data siswa gagal dihapus."
                ]);
            }
        } catch(\Exception $e){
            return response([
                "status"    => 0,
                "message"   => $e->getMessage()
            ]);
        }
    }
}
