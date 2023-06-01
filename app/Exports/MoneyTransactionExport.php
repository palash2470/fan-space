<?php

namespace App\Exports;

use App\Payment;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class MoneyTransactionExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($user,$type)
    {
        $this->user=$user;
        $this->type=$type;
    }
    // public function collection()
    // {
    //     return User::all();
    // }
    // public function query()
    // {
    //     return User::all();
    // }
    public function view(): View
    {
        $data = new Payment;
        $data = $data->with('getUser');
        if ($this->type != '' && $this->type != 'all') {
            $data = $data->where('type', $this->type);
        }
        if ($this->user != '' && $this->user != 'all') {
            $data = $data->where('user_id', $this->user);
        }
        $data = $data->get();
        return view('admin.export.money_transaction', [
            'data' => $data
        ]);
    }
}
