
<section class="content new-pagination">
    <h1>Money Transaction History</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="bg-white-box">
                <h3>search</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="lbl-text">
                                    <label for="user">User :</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="lbl-text">
                                    <label for="type">Transaction Type :</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select name="user" id="user" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($meta_data['follower'] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->first_name . ' ' . $item->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select name="type" id="type" class="form-control">
                                        <option value="all">All</option>
                                        <option value="1">Buy Coins</option>
                                        <option value="2">Buy Subscription</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
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
                <th>Name</th>
                <th>Type</th>
                <th>Txn</th>
                <th>Amount</th>
                <th>Date</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</section>
@push('scripts')
    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                "bLengthChange": false,
                //   ajax: "{{ \Route::currentRouteName() }}",
                ajax: {
                    url: "{{ \Route::currentRouteName() }}",
                    data: function(d) {
                        d.user = $('#user').val(),
                            d.type = $('#type').val()
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'txn',
                        name: 'txn'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                ]
            });
            $(".search-tnx").click(function() {
                table.draw();
            });

            $('.export').click(function(){
                window.location.href= "{{url('export/money-transaction')}}/"+$('#user').val()+"/"+$('#type').val();
            });
        });
    </script>

@endpush
