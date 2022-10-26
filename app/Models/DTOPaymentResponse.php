<?php

namespace App\Models;

class DTOPaymentResponse{
    public string $idTagihan;
    public string $nomorPembayaran;
    public int $totalNominal;
    public string $code;
    public string $message;
}