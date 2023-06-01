<?php

namespace App\Exports;

use App\Order_item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class SalesReport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data)
    {
       $this->type = $data['type'];
       $this->start_date = $data['start_date'].' 00:00:00';
       $this->end_date = $data['end_date'].' 23:59:59';
       $this->product = $data['product'];
       $this->model = $data['model'];
       $this->extra = $data;
    }
    public function view(): View
    {
        if($this->type=='date_wise'){
            $data = Order_item::with('order_details','order_details.order_by','product_details','product_details.user_details')->where('created_at','>=',$this->start_date)->where('created_at','<=',$this->end_date)->get();
            // dd($data);
        }
        if($this->type=='product_wise'){
            $data = Order_item::with('order_details','order_details.order_by','product_details','product_details.user_details')->where('product_id',$this->product)->get();
        }
        if($this->type=='model_wise'){
            $model = $this->model;
            $data = Order_item::with('order_details.order_by','product_details','product_details.user_details')->with(['order_details'=>function($q)use($model){$q->where('vip_member_id',$model);}])->whereHas('order_details',function($q) use ($model){
                $q->where('vip_member_id',$model);
            })->get();
        }
        return view('admin.export.sales_report', [
            'data' => $data,
            'type' => $this->type,
            'extra' => $this->extra
        ]);
    }
}
