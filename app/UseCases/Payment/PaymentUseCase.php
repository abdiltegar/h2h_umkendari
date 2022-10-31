<?php

namespace App\UseCases\Payment;

use App\Services\Student\StudentService;
use App\Services\Payment\PaymentService;
use App\Models\DTOPaymentResponse;
use App\Models\ResponseCode;

class PaymentUseCase
{
    public function PaymentUseCase($idTagihan, $nomorPembayaran, $kodeUnikBank, $nomorJurnalBank, $tanggalTransaksi, $kodeBank, $kodeChannel, $kodeTerminal, $totalNominal, $petugasLogin, $catatan){
        $res = new DTOPaymentResponse();
        $resCode = new ResponseCode();

        $tghnServ = new TagihanService();
        $payServ = new PaymentService();

        $kodeBayar = substr($nomorPembayaran,0,1);
        $nimRegnum = substr($nomorPembayaran,1);

        $stdServ = new StudentService();
        $student = $stdServ->GetStudentByRegNum($nimRegnum);
        if($student->nama != null){
            $nimRegnum = $student->registerNumber;
        }

        $tagihan = $tghnServ->GetTagihanById($idTagihan);
        if($tagihan->nomorPembayaran != $nomorPembayaran){
            $res->idTagihan = $idTagihan;
            $res->nomorPembayaran = $tagihan->nomorPembayaran;
            $res->totalNominal = $tagihan->totalNominal;
            $res->code = $resCode->ERR_NOT_FOUND;
            $res->message = "Data Tagihan Tidak Ditemukan";
            return $res;
        }
        
        switch($kodeBayar){
            case "1";
        }
        
        $paymentReff = $payServ->InsertReffPayment($idTagihan, $kodeUnikBank, $nomorJurnalBank, $tanggalTransaksi, $kodeBank, $kodeChannel, $kodeTerminal, $totalNominal, $kodeBayar);
        if($paymentReff->code != $resCode->OK){
            return $paymentReff;
        }
        $paymentStud = $payServ->InsertStudentPayment($idTagihan, $reffId, $kodeBank, $catatan, $petugasLogin, $kodeBayar);
        if($paymentStud->code != $resCode->OK){
            return $paymentStud;
        }

        $res->idTagihan = $idTagihan;
        $res->nomorPembayaran = $tagihan->nomorPembayaran;
        $res->totalNominal = $tagihan->totalNominal;
        $res->code = $resCode->OK;
        $res->message = "Transaksi berhasil";
        return $res;
    }
}
