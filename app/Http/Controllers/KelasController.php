<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kelas;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        try{
            $data["count"] = Kelas::count();
            $kelas = array();

            foreach (Kelas::all() as $p) {
                $item = [
                    "id"                    => $p->id,
                    "nama_kelas"            => $p->nama_kelas,
                    "kompetensi_keahlian"   => $p->kompetensi_keahlian,
                    "created_at"            => $p->created_at,
                    "updated_at"            => $p->updated_at,
                ];

                array_push($kelas, $item);
            }
            $data["kelas"]  = $kelas;
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
            $data["count"] = Kelas::count();
            $kelas = array();

            foreach (Kelas::take($limit)->skip($offset)->get() as $p) {
                $item = [
                    "id"                    => $p->id,
                    "nama_kelas"            => $p->nama_kelas,
                    "kompetensi_keahlian"   => $p->kompetensi_keahlian,
                    "created_at"            => $p->created_at,
                    "updated_at"            => $p->updated_at,
                ];

                array_push($kelas, $item);
            }
            $data["kelas"]  = $kelas;
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
                'nama_kelas'            => 'required|string|max:255',
                'kompetensi_keahlian'   => 'required|string|max:255'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }

            $data = new Kelas();
            $data->nama_kelas           = $request->input('nama_kelas');
            $data->kompetensi_keahlian  = $request->input('kompetensi_keahlian');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data kelas berhasil ditambahkan!'
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
                'nama_kelas'            => 'required|string|max:255',
                'kompetensi_keahlian'   => 'required|string|max:255'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }

            $data = Kelas::where('id', $id)->first();
            $data->nama_kelas           = $request->input('nama_kelas');
            $data->kompetensi_keahlian  = $request->input('kompetensi_keahlian');
            $data->save();

            return response()->json([
                'status'    => '1',
                'message'   => 'Data kelas berhasil diubah!'
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
            $delete = Kelas::where("id", $id)->delete();

            if($delete){
                return response([
                    "status"    => 1,
                    "message"   => "Data kelas berhasil dihapus!"
                ]);
            } else {
                return response([
                    "status"    => 0,
                    "message"   => "Data kelas gagal dihapus!"
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
