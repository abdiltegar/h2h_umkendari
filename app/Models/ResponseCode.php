<?php

namespace App\Models;

class ResponseCode
{
    public string $OK = "OK"; // Transaksi sukses
    public string $ERR_ALREADY_PAID = "ERR-ALREADY-PAID"; // Tagihan sudah dibayar
    public string $ERR_DB = "ERR-DB"; // Masalah database
    public string $ERR_SECURE_HASH = "ERR-SECURE-HASH"; // Hash invalid
    public string $ERR_UNDEFINED = "ERR-UNDEFINED"; // Undefined error
    public string $ERR_NOT_FOUND = "ERR-NOT-FOUND"; // Payment identity not found
    public string $ERR_PARSING_MESSAGE = "ERR-PARSING-MESSAGE"; // Invalid messaging format
    public string $ERR_PAYMENT_WRONG_AMOUNT = "ERR-PAYMENT-WRONG-AMOUNT"; // Payment amount salah
    public string $ERR_BANK_UNKNOWN = "ERR-BANK-UNKNOWN"; // Collecting agent/bank tidak dikenal

    // function untuk konversi rc dari db ke response_code sesuai dokumentasi
    public function mappingDBToRC($rc_db){
        $res = "";
        switch($rc_db){
            case "16":
                $res = $this->ERR_ALREADY_PAID;
                break;
            case "3":
                $res = $this->ERR_NOT_FOUND;
                break;
            case "0":
                $res = $this->OK;
                break;
            default:
                $res = $this->ERR_UNDEFINED;
                break;
        }
        return $res;
    }

    // function untuk konversi rc dari db ke response_code sesuai dokumentasi
    public function mappingRCToDB($rc){
        $res = "";
        switch($rc){
            case $this->ERR_NOT_FOUND:
                $res = "3";
            case $this->ERR_BANK_UNKNOWN:
                $res = "5";   
            case $this->ERR_PAYMENT_WRONG_AMOUNT:
                $res = "7";  
            case $this->ERR_PARSING_MESSAGE:
                $res = "9";   
            case $this->ERR_ALREADY_PAID:
                $res = "16";
            case $this->ERR_UNDEFINED:
                $res = "91";
            default:
                $res = "0";
        }
        return $res;
    }
}
