<?php

namespace App\Services\Inquiry;

use DB;
use App\Services\Student\StudentService;
use App\Models\DTOTagihanResponse;

class TagihanService
{
    public function GetTagihanById($idTagihan){
        return DB::connection('H2H')->table('ca_tagihan')->select('totalNominal')->where([['idTagihan',$idTagihan]])->first();
    }

    public function GetTagihanDetilById($idTagihan){
        return DB::connection('H2H')->table('ca_tagihan_detil')->where('idTagihan',$idTagihan)->get();
    }
}