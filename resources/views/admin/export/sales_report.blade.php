@php
    $total_amount = 0;
@endphp
<table>
    <tr>
        @if ($type == 'date_wise')
                <td colspan="2"> Type : Date Wise</td>
                <td colspan="3"> Start Date : {{date('d/m/Y',strtotime($extra['start_date']))}}</td>
                <td colspan="3"> End Date : {{date('d/m/Y',strtotime($extra['end_date']))}}</td>
        @endif
        @if ($type == 'product_wise')
            <td colspan="4"> Type : Product Wise</td>
            <td colspan="4"> Product Name : {{$data[0]->product_details->title}}</td>
        @endif
        @if ($type == 'model_wise')
            <td colspan="4"> Type : Model Wise</td>
            <td colspan="4"> Model Name : {{$data[0]->product_details->user_details->full_name}}</td>
        @endif
    </tr>
    <thead>

    <tr>
        <th>No</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Date</th>
        <th>Order By</th>
        <th>Model Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        @php
            $total_amount+=($row->price * $row->quantity);
        @endphp
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $row->product_details->title}}</td>
            <td>{{ $row->price }}</td>
            <td>{{ $row->quantity }}</td>
            <td>{{ $row->price * $row->quantity}}</td>
            <td>{{ $row->created_at->format('d/m/Y') }}</td>
            <td>{{ $row->order_details->order_by->full_name }}</td>
            <td>{{ $row->product_details->user_details->full_name }}</td>
        </tr>
    @endforeach
        <tr>
            <td colspan="5">
                Total
            </td>
            <td colspan="3">
                {{$total_amount}}
            </td>
        </tr>
    </tbody>
</table>
