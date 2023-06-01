<section class="content new-pagination">
    <h1>Coin Transaction History</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="bg-white-box">
                <h3>search</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group lbl-text">
                                    <label for="user">Model :</label>
                                    <select name="model" id="model" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($meta_data['model'] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->first_name . ' ' . $item->last_name }}
                                                ({{ $item->display_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group lbl-text">
                                    <label for="user">Follower :</label>
                                    <select name="follower" id="follower" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($meta_data['follower'] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->first_name . ' ' . $item->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group lbl-text">
                                    <label for="type">Transaction Type :</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="order">Product Order</option>
                                        <option value="post_tips">Post Tips</option>
                                        <option value="post_unlock">Post Unlock</option>
                                        <option value="chat_tips">Chat Tips</option>
                                        <option value="private_chat">Private Session</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="btn-wrap">
                                    <ul>
                                        <li><button type="button" class="btn btn-primary search-tnx">Search</button>
                                        </li>
                                        <li><button type="button" class="btn btn-primary export">Export</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Model Name</th>
                <th>Follower Name</th>
                <th>Coin</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</section>
@push('scripts')
    <script type="text/javascript">
        $(function() {
            var type = $('#type').val();
            var coloumn = [];
            if (type == "order") {
                // $('.data-table thead tr').append(`<th id="status">Status</th>`);
                // $('#status').css('display','block');
                coloumn = [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'model_name',
                        name: 'model_name'
                    },
                    {
                        data: 'follower_name',
                        name: 'follower_name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                ]
                // let column = table.column(5); // here is the index of the column, starts with 0
                // column.visible(true);
            } else {
                // alert();
                coloumn = [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'model_name',
                        name: 'model_name'
                    },
                    {
                        data: 'follower_name',
                        name: 'follower_name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                ]
                // let column = table.column(5); // here is the index of the column, starts with 0
                // column.visible(false);
                // $('#status').remove();
            }
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                "bLengthChange": false,
                //   ajax: "{{ \Route::currentRouteName() }}",
                ajax: {
                    url: "{{ \Route::currentRouteName() }}",
                    data: function(d) {
                        d.model = $('#model').val(),
                            d.follower = $('#follower').val(),
                            d.type = $('#type').val()
                    }
                },
                columns: coloumn
            });
            $(".search-tnx").click(function() {
                type = $('#type').val();
                if (type == "order") {
                    // $('.data-table thead tr').append(`<th id="status">Status</th>`);
                    coloumn = [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'model_name',
                            name: 'model_name'
                        },
                        {
                            data: 'follower_name',
                            name: 'follower_name'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },

                    ]
                    let column = table.column(5); // here is the index of the column, starts with 0
                    column.visible(true);
                } else {
                    coloumn = [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'model_name',
                            name: 'model_name'
                        },
                        {
                            data: 'follower_name',
                            name: 'follower_name'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'type',
                            name: 'type'
                        },
                    ];
                    let column = table.column(5); // here is the index of the column, starts with 0
                    column.visible(false);
                    // $('#status').remove();
                    // alert();
                    // $('#status').css('display','none');
                }
                table.draw();
            });
            $('.export').click(function(){
                window.location.href= "{{url('export/coin-transaction')}}/"+$('#model').val()+"/"+$('#follower').val()+"/"+$('#type').val();
            });
        });
    </script>

@endpush
