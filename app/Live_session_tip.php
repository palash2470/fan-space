<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Live_session_tip extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'live_session_tips';

    protected $fillable = ['live_session_history_id', 'tipper_id'];
}
