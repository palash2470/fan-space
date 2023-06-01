<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketChat extends Model
{
    protected $guarded = [];
    protected $table = 'ticket_chat';

    public function created_by_dtl()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
