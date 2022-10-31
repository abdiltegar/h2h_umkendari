<?php

namespace App\Services\Inquiry;

use DB;
use App\Services\Student\StudentService;
use App\Models\DTOTagihanResponse;
use App\Models\ResponseCode;

class InquiryService
{
    /*
    $identitas string = Bisa berupa nim / register number
    $kodeBayar = [1 = Penmaru, 2 = Her Registrasi, 3 = SPP Tetap, 4 = KRS / SPP Variable]
    */    
    public function InquiryService($identitas, $kodeBayar){
        $res = new DTOTagihanResponse();
        $resCode = new ResponseCode();

        $SpResult = DB::connection('SIA')->select("call usp_h2h_inquiry('".$identitas."', '".$kodeBayar."')");

        if (count($SpResult) == 0) {
            $stdServ = new StudentService();

            $student = $stdServ->GetCamaruByRegNum($identitas);
            if($student->nama == null){
                $student = $stdServ->GetStudentByNim($identitas);
                if($student->nama == null){
                    $student = $stdServ->GetStudentByRegNum($identitas);
                }
            }

            if($student->nama == null){
                $res->idTagihan = "";
                $res->nama = "";
                $res->fakultas = "";
                $res->jurusan = "";
                $res->angkatan = "";
                $res->code = $resCode->ERR_NOT_FOUND;
                $res->message = "Nomor Pembayaran Salah";
                $res->totalNominal = 0;
                $res->deskripsi = "";
            }else{
                $res->idTagihan = "";
                $res->nama = $student->nama;
                $res->fakultas = $student->fakultas;
                $res->jurusan = $student->jurusan;
                $res->angkatan = $student->angkatan;
                $res->code = $resCode->ERR_ALREADY_PAID;
                $res->message = "Tidak ada tagihan untuk saat ini / Tagihan telah dibayar";
                $res->totalNominal = 0;
                $res->deskripsi = "";
            }

        }else{
            foreach ($SpResult as $key => $value) {
                if(isset($value->idTagihan)){
                    $res->idTagihan = $value->idTagihan;
                }else{
                    $res->idTagihan = "";
                }
                $res->nama = "".$value->nama."";
                $res->fakultas = "".$value->fakultas."";
                $res->jurusan = "".$value->jurusan."";
                $res->angkatan = "".$value->angkatan."";
                $res->code = "".$resCode->OK."";
                $res->message = "".$value->message."";
                $res->totalNominal = $value->totalNominal;
                $res->deskripsi = "".$value->deskripsi."";
            }
        }

        return $res;
    }
}
