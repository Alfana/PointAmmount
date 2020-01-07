<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Point;
use App\User;
use App\Mitra;
use App\Reward;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DataCsController extends Controller
{
    public function detunit(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT users.name, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN users ON points.id_user = users.id 
        WHERE points.jabatan != '3' AND users.id_officer = '$idofficer'
        GROUP BY users.id
        ORDER BY users.unit DESC");
    }

    public function allunit(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT users.id_officer, users.unit, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN users ON points.id_user = users.id 
        WHERE points.jabatan != '3'
        GROUP BY users.unit
        ORDER BY point DESC");
    }

    public function tambahhadiah(Request $request){
        // return $request->all();
        $img = $request['gambar'];
        $exploded = explode(",",$img);

        if (Str::contains($exploded[0],'png')) {
            $ext = 'png';
        }elseif (Str::contains($exploded[0],'jpg')) {
            $ext = 'jpg';
        }elseif (Str::contains($exploded[0],'jpeg')) {
            $ext = 'jpeg';
        }

        $decode = base64_decode($exploded[1]);
        $namaFile = Str::random(20).".".$ext;
        
        $path = public_path()."/produk/".$namaFile;
        
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date('Y/m/d');
        $input = DB::table('rewards')->insertGetId([
            'katergori_reward'  => $request['produk'],
            'nama_reward'       => $request['nama'],
            'point'             => $request['point'],
            'gambar'            => $namaFile
        ]);
        if ($input) {
            file_put_contents($path,$decode);
            return ['Pesan'=>"Foto berhasil disimpan"];
        }
    }
    
    public function inputreedem(Request $request){
        // return $request->all();

        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date('d-m-Y');
        $input = DB::table('points')->insertGetId([
            'id_officer'        => $request[0],
            'id_user'           => $request[1],
            'produk'            => $request[2],
            'reedem'            => $request[3],
            'produk_reedem'     => $request[4],
            'qty'               => $request[5],
            'jabatan'           => '2',
            'stat'              => 'k',
            'status_pengajuan'  => '0',
            'tanggal'           => $tanggal
        ]);
    }

    public function detmitra(Request $request){
        $idmitra = $request[0];
        $idofficer = $request[1];
        // return $request->all();
        return DB::select("SELECT points.produk, points.keterangan, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN mitras ON points.id_user = mitras.id_mitra 
        WHERE points.id_user='$idmitra' AND users.id_officer='$idofficer' AND points.jabatan='3'
        GROUP BY points.produk");
    }

    public function detcs(Request $request){
        $idcs = $request[0];
        $idofficer = $request[1];
        // return $request->all();
        return DB::select("SELECT points.produk, points.keterangan, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN users ON points.id_user = users.id 
        WHERE points.id_user='$idcs' AND users.id_officer='$idofficer' AND points.jabatan='2'
        GROUP BY points.produk");
    }

    public function detao(Request $request){
        $idao = $request[0];
        $idofficer = $request[1];
        // return $request->all();
        return DB::select("SELECT points.produk, points.keterangan, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN users ON points.id_user = users.id 
        WHERE points.id_user='$idao' AND users.id_officer='$idofficer' AND points.jabatan='4'
        GROUP BY points.produk");
    }

    public function tampilcs(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        // return DB::select("SELECT * FROM points INNER JOIN users on points.id_officer = users.id WHERE points.id_officer =  '$idofficer'");
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE users.id_officer =  '$idofficer' AND points.jabatan = '2'
        ORDER BY points.id_point DESC");
    }

    public function tampilao(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        // return DB::select("SELECT * FROM points INNER JOIN users on points.id_officer = users.id WHERE points.id_officer =  '$idofficer'");
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE users.id_officer = '$idofficer' AND points.jabatan = '4'
        ORDER BY points.id_point DESC");
    }

    public function tampilmitra(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT * 
        FROM points 
        INNER JOIN mitras ON points.id_user = mitras.id_mitra 
        INNER JOIN users ON points.id_referensi = users.id
        WHERE mitras.id_officer = '$idofficer' AND points.jabatan = '3'
        ORDER BY points.id_point DESC");
    }

    public function historyreedem(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT *
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE users.id_officer =  '$idofficer' AND points.reedem != '0' OR points.jabatan = '4' AND points.jabatan = '2' 
        ORDER BY points.status_pengajuan ASC");
    }

    public function historyreedemexternal(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT * 
        FROM points 
        INNER JOIN mitras ON points.id_user = mitras.id_mitra 
        INNER JOIN users ON points.id_referensi = users.id
        WHERE mitras.id_officer = '$idofficer' AND points.jabatan = '3' AND points.reedem != '0'
        ORDER BY points.status_pengajuan ASC");
    }

    public function historyreedem_admin(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT *
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.reedem != '0' OR points.jabatan = '4' AND points.jabatan = '2' 
        ORDER BY points.status_pengajuan ASC");
    }

    public function historyreedemexternal_admin(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT * 
        FROM points 
        INNER JOIN mitras ON points.id_user = mitras.id_mitra 
        INNER JOIN users ON points.id_referensi = users.id
        WHERE points.jabatan = '3' AND points.reedem != '0'
        ORDER BY points.status_pengajuan ASC");
    }

    public function tampilcs_admin(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        // return DB::select("SELECT * FROM points INNER JOIN users on points.id_officer = users.id WHERE points.id_officer =  '$idofficer'");
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.jabatan = '2'
        ORDER BY points.id_point DESC");
    }

    public function tampilao_admin(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        // return DB::select("SELECT * FROM points INNER JOIN users on points.id_officer = users.id WHERE points.id_officer =  '$idofficer'");
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.jabatan = '4'
        ORDER BY points.id_point DESC");
    }

    public function tampilmitra_admin(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT * 
        FROM points 
        INNER JOIN mitras ON points.id_user = mitras.id_mitra 
        INNER JOIN users ON points.id_referensi = users.id
        WHERE points.jabatan = '3'
        ORDER BY points.id_point DESC");
    }

    // public function historyreedem_admin(Request $request){
    //     // return $request->all();
    //     $idofficer =$request[0];
    //     return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
    //     FROM points 
    //     INNER JOIN users on points.id_user = users.id 
    //     WHERE points.reedem != '0' OR points.jabatan = '4' AND points.jabatan = '2' 
    //     ORDER BY points.id_point DESC");
    // }

    // public function historyreedemexternal_admin(Request $request){
    //     // return $request->all();
    //     $idofficer =$request[0];
    //     return DB::select("SELECT * 
    //     FROM points 
    //     INNER JOIN mitras ON points.id_user = mitras.id_mitra 
    //     INNER JOIN users ON points.id_referensi = users.id
    //     WHERE points.jabatan = '3' AND points.reedem != '0'
    //     ORDER BY points.id_point DESC");
    // }

    public function historyao_ini(Request $request){
        // return $request->all();
        $iduser =$request[0];
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.id_user =  '$iduser'
        ORDER BY points.id_point DESC");
    }

    public function historyreedemao_ini(Request $request){
        // return $request->all();
        $iduser =$request[0];
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.id_user =  '$iduser' AND points.reedem != '0'
        ORDER BY points.id_point DESC");
    }

    public function historycs_ini(Request $request){
        // return $request->all();
        $iduser =$request[0];
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.id_user =  '$iduser'
        ORDER BY points.id_point DESC");
    }

    public function historyreedemcs_ini(Request $request){
        // return $request->all();
        $iduser =$request[0];
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal 
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.id_user =  '$iduser' AND points.reedem != '0'
        ORDER BY points.id_point DESC");
    }

    public function ambilcs(Request $request){
        $name = $request[0];
        return DB::select("SELECT id, akses FROM Users WHERE name='$name'");
    }

    public function ambilmitra(Request $request){
        $namamitra = $request[0];
        // return $request->all();
        return DB::select("SELECT id_mitra, akses FROM Mitras WHERE nama_mitra='$namamitra'");
    }

    public function ambilnasabah(Request $request){
        $namanasabah = $request[0];
        return DB::select("SELECT id FROM Nasabahs WHERE nama='$namanasabah'");
    }

    public function mitra(){
        return ['datamitra'=>Mitra::all()];
    }

    public function cs(Request $request){
        $id = $request[0];
        return DB::select("SELECT name FROM Users WHERE id_officer='$id'");
    }

    public function inputcs(Request $request){
        // return $request->all();
        if ($request[2] == "Western Union") {
            if ((int)$request[5] >= 2000000) {
                $point = 1;
            }else{
                $point = 0;
            }      
        }else{
            $point = 1;
        }
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date('d-m-Y');
        $input = DB::table('points')->insertGetId([
            'id_user'       => $request[3],
            'id_officer'    => $request[6],
            'jabatan'       => $request[4],
            'produk'        => $request[2],
            'id_nasabah'    => $request[2],
            'des'           => $request[5],
            'point'         => $point,
            'tanggal'       => $tanggal,
            'stat'          => "t"
        ]);
        if ($input) {
            return ["Pesan"=>"Point Berhasil Ditambahkan"];
        }else{
            return ["Pesan"=>"Point Gagal Ditambahkan"];
        }
    }

    public function inputmitra(Request $request){
        // return $request->all();
        if ($request[3] == "Western Union") {
            if ((int)$request[9] >= 2000000) {
                $point = 1;
            }else{
                $point = 0;
            }      
        }else if ($request[3] == "Bancasurance") {
            if ((int)$request[9] >= 2000000) {
                $point = 1;
            }else{
                $point = 0;
            }      
        }else{
            $point = 1;
        }
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date('d-m-Y');
        $input = DB::table('points')->insertGetId([
            'id_user'       => $request[4],
            'id_officer'    => $request[6],
            'id_nasabah'    => $request[2],
            'jabatan'       => $request[5],
            'produk'        => $request[3],
            'point'         => $point,
            'tanggal'       => $tanggal,
            'stat'          => "t",
            'id_referensi'  => $request[7],
            'noa'           => $request[8],
            'nominal'       => $request[9]
        ]);
        DB::table('points')->insertGetId([
            'id_user'       => $request[7],
            'id_officer'    => $request[6],
            'id_nasabah'    => $request[2],
            'jabatan'       => $request[10],
            'produk'        => $request[3],
            'point'         => $point,
            'tanggal'       => $tanggal,
            'stat'          => "t",
            'id_referensi'  => $request[4],
            'noa'           => $request[8],
            'nominal'       => $request[9]
        ]);
        if ($input) {
            return ["Pesan"=>"Point Berhasil Ditambahkan"];
        }else{
            return ["Pesan"=>"Point Gagal Ditambahkan"];
        }
    }

    public function inputdatamitra(Request $request){
        // return $request->all();
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date('d-m-Y');
        $input = DB::table('mitras')->insertGetId([
            'id_officer'    => $request[6],
            'nik'           => $request[0],
            'nama_mitra'    => $request[1],
            'jk'            => $request[2],
            'alamat'        => $request[3],
            'agama'         => $request[4],
            'pekerjaan'     => $request[5],
            'akses'         => "3",
            'created'       => $tanggal
        ]);
        if ($input) {
            return ["Pesan"=>"Point Berhasil Ditambahkan"];
        }else{
            return ["Pesan"=>"Point Gagal Ditambahkan"];
        }
    }

    public function allpointcs(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT users.id_officer, points.id_user, users.name, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN users ON points.id_user = users.id 
        WHERE points.jabatan=2 AND users.id_officer='$idofficer'
        GROUP BY points.id_user DESC");
    }

    public function allpointao(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT points.id_user, users.name, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN users ON points.id_user = users.id 
        WHERE jabatan=4 AND users.id_officer='$idofficer'
        GROUP BY points.id_user DESC");
    }

    public function pointcstambah(Request $request){
        $idofficer =$request[0];

        return DB::select("SELECT p1.id_user, users.name, p1.tpoint 
        FROM (
            SELECT points.id_user, points.jabatan, points.id_officer, SUM(points.point) AS tpoint 
            FROM points 
            WHERE points.stat = 't' AND points.jabatan=2 AND users.id_officer='$idofficer' 
            GROUP BY points.id_user
            ) AS p1 
        INNER JOIN users ON p1.id_user = users.id");
    }

    public function pointcskurang(Request $request){
        $idofficer =$request[0];
        return DB::select("SELECT p1.id_user, users.name, p1.tpoint 
        FROM (
            SELECT points.id_user, points.jabatan, points.id_officer, SUM(points.point) AS tpoint 
            FROM points 
            WHERE points.stat = 'k' AND points.jabatan=2 AND users.id_officer='$idofficer' 
            GROUP BY points.id_user
            ) AS p1 
        INNER JOIN users ON p1.id_user = users.id");
    }

    public function allpointmitra(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT points.id_user, mitras.nama_mitra, (SUM(points.point)-SUM(points.reedem)) AS point 
        FROM points 
        INNER JOIN mitras ON points.id_user = mitras.id_mitra 
        WHERE jabatan=3 AND points.id_officer='$idofficer' 
        GROUP BY points.id_user ASC");
    }

    public function reward(Request $request){
        // return $request->all();
        $produk =$request[0];
        return DB::select("SELECT * FROM rewards WHERE katergori_reward = '$produk'");
    }

    public function reedemreward(Request $request){
        // return $request->all();
        $produk = $request[0];
        $maxpoint = $request[1];
        return DB::select("SELECT * FROM rewards WHERE katergori_reward = '$produk' AND point <= '$maxpoint'");
    }

    public function konfirmasi(Request $request){
        // return $request->all();
        $idofficer =$request[0];
        return DB::select("SELECT points.id_point, users.name, points.jabatan, points.produk, points.noa, points.nominal, points.point, points.reedem, points.produk_reedem, points.qty, points.tanggal, points.status_pengajuan
        FROM points 
        INNER JOIN users on points.id_user = users.id 
        WHERE points.status_pengajuan = '1' AND points.id_officer = '23' OR points.jabatan = '4' AND points.jabatan = '2' 
        ORDER BY points.id_point DESC");
    }

    public function setuju(Request $request){
        // return $request->all();
        $id = $request[0];
        return DB::update("UPDATE points SET status_pengajuan = '1' WHERE id_point = '$id'");
    }

    public function tolak(Request $request){
        // return $request->all();
        $id = $request[0];
        $reedem = $request[1];
        $ket = $request[2];
        return DB::update("UPDATE points SET status_pengajuan = '2', point='$reedem', keterangan='$ket' WHERE id_point = '$id'");
    }

    public function disampaikan(Request $request){
        // return $request->all();
        $id = $request[0];
        return DB::update("UPDATE points SET status_pengajuan = '3' WHERE id_point = '$id'");
    }
}
