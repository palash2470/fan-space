<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Live_session_chat_message extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'live_session_chat_messages';

    protected $fillable = ['live_session_history_id', 'sender_id', 'message'];
}
