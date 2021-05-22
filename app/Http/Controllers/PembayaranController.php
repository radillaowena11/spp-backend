<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Pembayaran;
use App\Siswa;
use App\User;
use App\Spp;
use JWTAuth;
use DB;

class PembayaranController extends Controller
{
    public function index()
    {
        try{
            $data["count"] = Pembayaran::count();
            $pembayaran = array();
            $dataPembayaran = DB::table('pembayaran')->join('user', 'user.id', '=', 'pembayaran.id_petugas')
                                                     ->join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
                                                     ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
                                                     ->select('pembayaran.id', 'user.nama_petugas',
                                                     'siswa.nama', 'siswa.nis', 'spp.tahun', 'spp.nominal',
                                                     'pembayaran.tanggal_pembayaran', 'pembayaran.id_petugas',  'pembayaran.id_siswa', 'pembayaran.id as id_spp',
                                                     'pembayaran.jumlah_bayar')
                                                     ->get();
            foreach ($dataPembayaran as $p) {
                $item = [
                    "id"                    => $p->id,
                    // "id_petugas"            => $p->id_petugas,
                    "nama_petugas"          => $p->nama_petugas,
                    // "id_siswa"              => $p->id_siswa,
                    "nis"                   => $p->nis,
                    "nama_siswa"            => $p->nama,
                    // "id_spp"                => $p->id_spp,
                    "spp"                 => $p->tahun,
                    "tanggal_pembayaran"    => date('Y-m-d', strtotime($p->tanggal_pembayaran)),
                    "jumlah_bayar"          => $p->nominal,
                ];
                array_push($pembayaran, $item);
            }
            $data["pembayaran"] = $pembayaran;
            $data["status"] = 1;
            return response($data);
        } catch (\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }


    public function get()
    {
        try{
            $data["count"]  = Pembayaran::count();
            $dataPembayaran = Pembayaran::get();

            $data["pembayaran"] = $dataPembayaran;
            $data["status"]     = 1;
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
        try{
            $data["count"] = Pembayaran::count();
            $pembayaran = array();
            $dataPembayaran = DB::table('pembayaran')->join('user', 'user.id', '=', 'pembayaran.id_petugas')
                                                     ->join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
                                                     ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
                                                     ->select('pembayaran.id', 'user.nama_petugas',
                                                     'siswa.nama', 'siswa.nis', 'spp.tahun','spp.id as sppid','spp.bulan', 'spp.nominal',
                                                     'pembayaran.tanggal_pembayaran', 'pembayaran.id_petugas',  'pembayaran.id_siswa', 'pembayaran.id as id_spp',
                                                     'pembayaran.jumlah_bayar')
                                                     ->skip($offset)
                                                     ->take($limit)
                                                     ->get();

            foreach ($dataPembayaran as $p) {
                $item = [
                    "id"                    => $p->id,
                    // "id_petugas"            => $p->id_petugas,
                    "nama_petugas"          => $p->nama_petugas,
                    // "id_siswa"              => $p->id_siswa,
                    "nis"                   => $p->nis,
                    "nama_siswa"            => $p->nama,
                    // "id_spp"                => $p->id_spp,
                    "tahun"                 => $p->tahun,
                    "sppid"                 => $p->sppid,
                    "bulan"                 => $p->bulan,
                    "nominal"               => $p->nominal,
                    "tanggal_pembayaran"    => date('Y-m-d', strtotime($p->tanggal_pembayaran)),
                    "jumlah_bayar"          => $p->jumlah_bayar,
                ];
                array_push($pembayaran, $item);
            }
            $data["pembayaran"] = $pembayaran;
            $data["status"] = 1;
            return response($data);

        } catch(\Exception $e){
            return response()->json([
                'status'    => '0',
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function getAllku($limit = 10, $offset = 0)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            // return $user;
            $data["count"] = Pembayaran::count();
            $pembayaran = array();
            $dataPembayaran = DB::table('pembayaran')->join('user', 'user.id', '=', 'pembayaran.id_petugas')
                                                     ->join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
                                                     ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
                                                     ->select('pembayaran.id', 'user.nama_petugas',
                                                     'siswa.nama', 'siswa.nis', 'spp.tahun','spp.id as sppid','spp.bulan', 'spp.nominal',
                                                     'pembayaran.tanggal_pembayaran', 'pembayaran.id_petugas',  'pembayaran.id_siswa', 'pembayaran.id as id_spp',
                                                     'pembayaran.jumlah_bayar')
                                                     ->where('pembayaran.id_siswa',$user->id)
                                                     ->skip($offset)
                                                     ->take($limit)
                                                     ->get();

            foreach ($dataPembayaran as $p) {
                $item = [
                    "id"                    => $p->id,
                    // "id_petugas"            => $p->id_petugas,
                    "nama_petugas"          => $p->nama_petugas,
                    // "id_siswa"              => $p->id_siswa,
                    "nis"                   => $p->nis,
                    "nama_siswa"            => $p->nama,
                    // "id_spp"                => $p->id_spp,
                    "tahun"                 => $p->tahun,
                    "sppid"                 => $p->sppid,
                    "bulan"                 => $p->bulan,
                    "nominal"               => $p->nominal,
                    "tanggal_pembayaran"    => date('Y-m-d', strtotime($p->tanggal_pembayaran)),
                    "jumlah_bayar"          => $p->jumlah_bayar,
                ];
                array_push($pembayaran, $item);
            }
            $data["pembayaran"] = $pembayaran;
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
                'id_petugas'        => 'required|integer',
                'id_siswa'          => 'required|integer',
                'tanggal_pembayaran'=> 'required|date_format:Y-m-d',
                'id_spp'            => 'required|integer',
                'jumlah_bayar'        => 'required|',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }
            $myspp = SPP::find($request->input('id_spp'));
            if($myspp<$request->input('jumlah_bayar')){
                return response()->json([
                    'status'    => 0,
                    'message'   => "Jumlah Bayar Terlalu Banyak"
                ]);
            }if(0>$request->input('jumlah_bayar')){
                return response()->json([
                    'status'    => 0,
                    'message'   => "Jumlah Bayar Tidak Boleh 0 atau Kurang"
                ]);
            }
            if(Siswa::where('id', $request->input('id_siswa'))->count() > 0){
                if(User::where('id', $request->input('id_petugas'))->count() > 0){
                    $data = new Pembayaran();
                    $data->id_petugas           = $request->input('id_petugas');
			        $data->id_siswa             = $request->input('id_siswa');
			        $data->tanggal_pembayaran   = $request->input('tanggal_pembayaran');
                    $data->id_spp               = $request->input('id_spp');
			        $data->jumlah_bayar         = $request->input('jumlah_bayar');
                    $myspp->nominal             = $myspp->nominal - $request->input('jumlah_bayar');
			        $data->save();
			        $myspp->save();

                    return response()->json([
                        'status'    => '1',
                        'message'   => 'Data Pembayaran berhasil ditambahkan!'
                    ], 201);
                } else {
                    return response()->json([
                        'status'    => '0',
                        'message'   => 'Data Pembayaran tidak ditemukan.'
                    ]);
                }
            } else {
                return response()->json([
	                'status' => '0',
	                'message' => 'Data Pembayaran tidak ditemukan.'
	            ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_petugas'        => 'required|integer',
                'id_siswa'          => 'required|integer',
                'tanggal_pembayaran'=> 'required|date_format:Y-m-d',
                'id_spp'            => 'required|integer',
                'jumlah_bayar'      => 'required|',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'    => 0,
                    'message'   => $validator->errors()
                ]);
            }

            $data = Pembayaran::where('id', $id)->first();
            $data->id_petugas           = $request->input('id_petugas');
		    $data->id_siswa             = $request->input('id_siswa');
		    $data->tanggal_pembayaran   = $request->input('tanggal_pembayaran');
            $data->id_spp               = $request->input('id_spp');
	        $data->jumlah_bayar         = $request->input('jumlah_bayar');
	        $data->save();

            return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data Pembayaran  berhasil diubah'
      	    ]);

        } catch(\Exception $e){
            return response()->json([
              'status' => '0',
              'message' => $e->getMessage()
            ]);
        }
    }


    public function delete($id)
    {
        try{
            $delete = Pembayaran::where("id", $id)->delete();
            if($delete){
                return response([
                    "status"  => 1,
                    "message"   => "Data pembayaran  berhasil dihapus."
                ]);
            } else {
                return response([
                    "status"  => 0,
                    "message"   => "Data siswa  gagal dihapus."
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
