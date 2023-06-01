<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'payments';

    protected $fillable = ['user_id','vip_member_id', 'type', 'amount', 'gateway', 'txn_id', 'payment_data'];

    public function getUser()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function getVip()
    {
        return $this->belongsTo('App\User', 'vip_member_id');
    }
}
