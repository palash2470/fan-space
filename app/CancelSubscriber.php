<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelSubscriber extends Model
{
    protected $guarded=[];
    protected $table = 'canceled_subscriber';

    public function model_detail()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function subscriber_detail()
    {
        return $this->belongsTo('App\User', 'subscriber_id');
    }
}
