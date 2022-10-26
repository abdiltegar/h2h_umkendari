<?php

namespace App\UseCases\Bank;

use App\Services\Auth\BankService;

class BankUseCase
{
    public function AuthenticationBank($kodeBank, $passwordBank){

        $serv = new BankService();
        $Bank = $serv->AuthenticationBank($kodeBank, $passwordBank);

        return $Bank;
    }
}
