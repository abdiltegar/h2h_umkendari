<?php

namespace App\UseCases\Bank;

use App\Services\Bank\BankService;

class BankUseCase
{
    public function AuthenticationBank($kodeBank, $passwordBank){

        $serv = new BankService();
        $Bank = $serv->AuthenticationBank($kodeBank, \md5($passwordBank));

        return $Bank;
    }
}
