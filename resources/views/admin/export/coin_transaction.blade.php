<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Model Name</th>
        <th>Follower Name</th>
        <th>Coin</th>
        <th>Date</th>
        @if ($type=='order')
        <th>Status</th>
        @endif

        <th>Type</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ $row->model_first_name . ' ' . $row->model_last_name }}</td>
            <td>{{ $row->follower_first_name . ' ' . $row->follower_last_name }}</td>
            <td>{{ $row->token_coins }}</td>
            <td>{{ $row->created_at->format('d/m/Y') }}</td>
            @if ($type=='order')
                @php
                    $status = "";
                    if ($row->status == 0) {
                        $status = "Inactive";
                    }
                    if ($row->status == 1) {
                        $status = "Pending";
                    }
                    if ($row->status == 2) {
                        $status = "Cancelled";
                    }
                    if ($row->status == 3) {
                        $status = "Completed";
                    }
                @endphp
                <td>{{ $status }}</td>
            @endif
            <td>{{ $display_type }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
