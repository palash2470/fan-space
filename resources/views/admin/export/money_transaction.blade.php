<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Type</th>
        <th>Txn</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $row->getUser->first_name . ' ' . $row->getUser->last_name }}</td>
            <td>{{ $row->type == 1 ? 'Buy Coins' : 'Buy Subscription' }}</td>
            <td>{{ $row->txn_id }}</td>
            <td>{{ $row->amount }}</td>
            <td>{{ $row->created_at->format('d/m/Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
