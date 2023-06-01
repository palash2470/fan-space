<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];
    protected $table = 'ticket_system';

    public function created_by_dtl()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function ticket_chat()
    {
        return $this->hasMany('App\TicketChat', 'ticket_id');
    }
}
