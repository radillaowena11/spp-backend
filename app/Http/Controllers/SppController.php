<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spp;
use Illuminate\Support\Facades\Validator;
use JWTAuth;


class SppController extends Controller
{
    public function index()
    {
        try{
            $data["count"] = Spp::count();
            $spp = array();

            foreach (Spp::all() as $p){
                $item = [
                    "id"            => $p->id,
                    "id_siswa"      => $p->id_siswa,
                    "tahun"         => $p->tahun,
                    "bulan"         => $p->bulan,
                    "nominal"       => $p->nominal,
                    "created_at"    => $p->created_at,
                    "updated_at"    => $p->updated_at
                ];

                array_push($spp, $item);
            }
            $data["spp"]    = $spp;
            $data["status"] = 1;
            return response($data);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function getSppSiswa($id_siswa)
    {
        try{
            $data["count"] = Spp::count();
            $spp = array();

            foreach (Spp::where('id_siswa',$id_siswa)->where('nominal','>','0')->get() as $p){
                $item = [
                    "id"            => $p->id,
                    "id_siswa"      => $p->id_siswa,
                    "tahun"         => $p->tahun,
                    "bulan"         => $p->bulan,
                    "nominal"       => $p->nominal,
                    "created_at"    => $p->created_at,
                    "updated_at"    => $p->updated_at
                ];

                array_push($spp, $item);
            }
            $data["spp"]    = $spp;
            $data["status"] = 1;
            return response($data);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function getAllKu($limit = 10, $offset = 0)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();

            $data["count"] = Spp::count();
            $spp = array();

            foreach (Spp::where('id_siswa',$user->id)->where('nominal','>','0')->join('siswa', 'siswa.id','=', 'spp.id_siswa')->select('spp.*','nama')->take($limit)->skip($offset)->get() as $p){
                $item = [
                    "id"            => $p->id,
                    "id_siswa"      => $p->id_siswa,
                    "nama"      => $p->nama,
                    "tahun"         => $p->tahun,
                    "bulan"         => $p->bulan,
                    "nominal"       => $p->nominal,
                    "created_at"    => $p->created_at,
                    "updated_at"    => $p->updated_at
                ];

                array_push($spp, $item);
            }
            $data["spp"]    = $spp;
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
            $data["count"] = Spp::count();
            $spp = array();

            foreach (Spp::join('siswa', 'siswa.id','=', 'spp.id_siswa')->select('spp.*','nama')->take($limit)->skip($offset)->get() as $p){
                $item = [
                    "id"            => $p->id,
                    "id_siswa"      => $p->id_siswa,
                    "nama"      => $p->nama,
                    "tahun"         => $p->tahun,
                    "bulan"         => $p->bulan,
                    "nominal"       => $p->nominal,
                    "created_at"    => $p->created_at,
                    "updated_at"    => $p->updated_at
                ];

                array_push($spp, $item);
            }
            $data["spp"]    = $spp;
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
                'id_siswa'  => 'required|integer',
                'tahun'     => 'required|integer',
                'bulan'     => 'required|integer|min:1|max:12',
                'nominal'   => 'required|',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }

            $data = new Spp();
            $data->id_siswa    = $request->input('id_siswa');
            $data->bulan    = $request->input('bulan');
            $data->tahun    = $request->input('tahun');
            $data->nominal  = $request->input('nominal');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data spp berhasil ditambahkan!'
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
        try{
            $validator = Validator::make($request->all(), [
                'id_siswa'     => 'required|integer',
                'tahun'     => 'required|integer',
                'bulan'     => 'required|integer|min:1|max:12',
                'nominal'   => 'required|',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }

            $data = Spp::where('id', $id)->first();
            $data->id_siswa    = $request->input('id_siswa');
            $data->bulan    = $request->input('bulan');
            $data->tahun    = $request->input('tahun');
            $data->nominal  = $request->input('nominal');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data spp berhasil diubah!'
            ], 201);

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
            $delete = Spp::where("id", $id)->delete();

            if($delete){
                return response([
                    "status"    => 1,
                    "message"   => "Data spp berhasil dihapus!"
                ]);
            } else {
                return response([
                    "status"    => 0,
                    "message"   => "Data spp gagal dihapus!"
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
