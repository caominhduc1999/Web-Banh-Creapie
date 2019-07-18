<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    protected $table = 'bills';

    public function bill_detail()
    {
        return $this->hasMany('App\BillDeTail', 'id_bill', 'id');
    }

    public function customer()
    {
        $this->hasMany('App\Customer', 'id_customer', 'id');
    }
}
