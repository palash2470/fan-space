<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'subscribers';

    protected $fillable = ['user_id', 'subscriber_id', 'validity_date', 'next_renewal_date', 'stripe_subscription_id', 'stripe_plan_id', 'duration_days'];

    /*public static function delete_expired_subscription() {
    	self::where('validity_date', '<', date('Y-m-d H:i:s'))->delete();
    }*/

    public function model_detail()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function subscriber_detail()
    {
        return $this->belongsTo('App\User', 'subscriber_id');
    }
}
