<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\Inquiry\InquiryUseCase;
use App\UseCases\Bank\BankUseCase;
use App\UseCases\Log\LogUseCase;
use App\Models\DTOTagihanResponse;
use App\Models\DTOPaymentResponse;

class Test {
    public $nama;
}

class RestController extends Controller
{
    private $kodeBankLocal = "999x";
    private $passwordBankLocal = "Bank@123";
    private $univName = "Universitas Muhammadiyah Kendari";

    function Inquiry(Request $request) {
        // TODO get parameters
        $kodeBank = $request->kodeBank;
        $passwordBank = $request->passwordBank;
        $kodeChannel = $request->kodeChannel;
        $kodeTerminal = $request->kodeTerminal;
        $nomorPembayaran = $request->nomorPembayaran;
        $tanggalTransaksi = $request->tanggalTransaksi;
        $idTransaksi = $request->idTransaksi;

        // TODO Prepare response
        $res = new DTOTagihanResponse();

        // TODO Authentication Bank
        $bankUseCase = new BankUseCase();
        $auth = $bankUseCase->AuthenticationBank($kodeBank, $passwordBank);
    
        if($auth){

            // TODO Process Inquiry
            $useCase = new InquiryUseCase();
            $resUseCase = $useCase->InquiryUseCase($nomorPembayaran);

            $res->idTagihan = $resUseCase->idTagihan;
            $res->nama = $resUseCase->nama;
            $res->fakultas = $resUseCase->fakultas;
            $res->jurusan = $resUseCase->jurusan;
            $res->angkatan = $resUseCase->angkatan;
            $res->code = $resUseCase->code;
            $res->message = $resUseCase->message;
            $res->totalNominal = $resUseCase->totalNominal;
            $res->deskripsi = $resUseCase->deskripsi;

        } else {

            $res->idTagihan = "";
            $res->nama = "";
            $res->fakultas = "";
            $res->jurusan = "";
            $res->angkatan = "";
            $res->code = "5";
            $res->message = "Identitas collecting agent tidak dikenal.";
            $res->totalNominal = "";
            $res->deskripsi = "";

        }

        $log = new LogUseCase();
        $log->LogInquiry($res->idTagihan, $kodeBank, $kodeChannel, $kodeTerminal, $nomorPembayaran, $tanggalTransaksi, $idTransaksi, $res->code, $res->message);
        return response()->json($res);
    }

    function Payment(Request $request){
        // TODO get parameters
        $idTagihan = $request->idTagihan;
        $kodeBank = $request->kodeBank;
        $passwordBank = $request->passwordBank;
        $kodeChannel = $request->kodeChannel;
        $kodeTerminal = $request->kodeTerminal;
        $nomorPembayaran = $request->nomorPembayaran;
        $waktuTransaksiBank = $request->waktuTransaksiBank;
        $totalNominal = $request->totalNominal;
        $kodeUnikBank = $request->kodeUnikBank;
        $nomorJurnalBank = $request->nomorJurnalBank;
        $passwordAdmin = $request->passwordAdmin;
        $emailAdmin = $request->emailAdmin;
        $PetugasLogin = $request->petugasLogin;

        // TODO Prepare response
        $res = new DTOPaymentResponse();
        $res->idTagihan = $idTagihan;
        $res->nomorPembayaran = $nomorPembayaran;
        $res->totalNominal = $totalNominal;

        // TODO Authentication Bank
        $bankUseCase = new BankUseCase();
        $auth = $bankUseCase->AuthenticationBank($kodeBank, $passwordBank);
    
        if($auth){

            // TODO Process Payment
            $useCase = new PaymentUseCase();
            $resUseCase = $useCase->PaymentUseCase($kodeBank, $nomorPembayaran, $tanggalTransaksi, $totalNominal);

            $res->code;
            $res->message;

        } else {
            $res->code = "5";
            $res->message = "Identitas collecting agent tidak dikenal.";
        }

        $log = new LogUseCase();
        $log->LogPayment($res->idTagihan, $kodeBank, $kodeChannel, $kodeTerminal, $nomorPembayaran, $tanggalTransaksi, $idTransaksi, $res->code, $res->message);
        return response()->json($res);
    }

    function test() {
        return response()->json($this->get());
    }

    function get(){
        $test = new Test();
    
        return $test;
    }
}
