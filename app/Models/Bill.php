<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';
    protected $fillable = [
        'customer_id',
        'total',
        'note',
    ];

    public function billDetail()
    {
        return $this->hasMany('App\Models\BillDetail', 'bill_id', 'id');
    }

    public function bill()
    {
        return $this->belongTo('App\Models\customer', 'customer_id', 'id');
    }
}
