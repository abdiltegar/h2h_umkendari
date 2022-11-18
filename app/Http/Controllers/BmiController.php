<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UseCases\Inquiry\InquiryUseCase;
use App\UseCases\Payment\PaymentUseCase;
use App\UseCases\Bank\BankUseCase;
use App\UseCases\Log\LogUseCase;
use App\Models\ResponseCode;

class BmiController extends Controller
{
    //

    function Index(Request $request) {
        // Prepare untuk response
        $res = new \stdClass();
        $resCode = new ResponseCode();
        
        // TODO Validasi parameters
        $validator = Validator::make($request->all(), [
            'action' => 'required',
            'kodeBank' => 'required',
            'kodeBiller' => 'required',
            'kodeChannel' => 'required',
            'kodeTerminal' => 'required',
            'nomorPembayaran' => 'required',
            'tanggalTransaksi' => 'required',
            'idTransaksi' => 'required',
            'checksum' => 'required'
        ]);

        if ($validator->fails()) {
            $res->rc = $resCode->ERR_PARSING_MESSAGE;
            $res->msg = "Invalid messaging format";

            return response()->json($res);
        }

        // TODO get parameters
        $action = $request->action;
        $kodeBank = $request->kodeBank;
        $kodeBiller = $request->kodeBiller;
        $kodeChannel = $request->kodeChannel;
        $kodeTerminal = $request->kodeTerminal;
        $nomorPembayaran = $request->nomorPembayaran;
        $tanggalTransaksi = $request->tanggalTransaksi;
        $idTransaksi = $request->idTransaksi;
        $idTagihan = $request->idTagihan;
        $totalNominal = $request->totalNominal;
        $checksum = $request->checksum;

        // TODO Authentication Bank
        $bankUseCase = new BankUseCase();
        $totalNominalStr = "";
        if($action == "payment"){
            $totalNominalStr = $totalNominal;
        }

        $bank = $bankUseCase->GetByKodeBank($kodeBank);
        if($bank == null) {
            $res->rc = $resCode->ERR_BANK_UNKNOWN;
            $res->msg = "Identitas collecting agent tidak dikenal.";

            return response()->json($res);
        }

        $auth = $bankUseCase->CheckSumBmi($kodeBank, $bank->password, $nomorPembayaran, $tanggalTransaksi, $totalNominalStr, $checksum);
        if(!$auth){
            $res->rc = $resCode->ERR_SECURE_HASH;
            $res->msg = "Hash invalid";

            return response()->json($res);
        }

        // Process Action
        if($action == "inquiry"){
            $useCase = new InquiryUseCase();
            $resUseCase = $useCase->InquiryUseCase($nomorPembayaran);

            if ($resUseCase->code == $resCode->OK){
                $res->rc = $resUseCase->code;
                $res->msg = $resUseCase->message;
                $res->nomorPembayaran = $resUseCase->nomorPembayaran ;
                $res->idPelanggan = $resUseCase->idPelanggan ;
                $res->nama = $resUseCase->nama;
                $res->email = $resUseCase->email ;
                $res->totalNominal = $resUseCase->totalNominal;
                $res->rincian = $resUseCase->deskripsi;
                $res->idTagihan = $resUseCase->idTagihan;
            }else{
                $res->rc = $resUseCase->code;
                $res->msg = $resUseCase->message;
            }
        }
        if($action == "payment"){
            $useCase = new PaymentUseCase();
            $resUseCase = $useCase->PaymentUseCase($idTagihan, $nomorPembayaran, "", "", $tanggalTransaksi, $kodeBank, $kodeChannel, $kodeTerminal, $totalNominal, "", "");

            if ($resUseCase->code == $resCode->OK){
                $res->rc = $resUseCase->code;
                $res->msg = $resUseCase->message;
                $res->nomorPembayaran = $resUseCase->nomorPembayaran;
                $res->idPelanggan = $resUseCase->nomorMahasiswa;
                $res->nama = $resUseCase->nama;
                $res->email = $resUseCase->email;
                $res->totalNominal = $resUseCase->totalNominal;
                $res->rincian = $resUseCase->rincian;
                $res->idTagihan = $resUseCase->idTagihan;
            }else{
                $res->rc = $resUseCase->code;
                $res->msg = $resUseCase->message;
            }
        }

        return response()->json($res);
        
    }

    function Generate(Request $request) {
        return \md5("".$request->nomorPembayaran."".$request->secretKey."".$request->tanggalTransaksi."".$request->totalNominal."");
    }
    
}
