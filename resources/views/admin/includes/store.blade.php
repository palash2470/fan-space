<section class="content new-pagination">
    <h1>Product List</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="bg-white-box">
                <h3>search</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="lbl-text">
                                    <label for="model">Vip Member :</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select name="model" id="model" class="form-control">
                                        <option value="all">All</option>
                                        @foreach ($meta_data['model'] as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="btn-wrap">
                                    <ul>
                                        <li><button type="button" class="btn btn-primary search-product">Search</button>
                                        </li>
                                        {{-- <li><button type="button" class="btn btn-primary export">Export</button>
                                        </li> --}}
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
                <th>Vip Name</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>

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
                        d.model = $('#model').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $(".search-product").click(function() {
                table.draw();
            });

            $('.export').click(function() {
                window.location.href = "{{ url('export/money-transaction') }}/" + $('#user').val() + "/" +
                    $('#type').val();
            });
        });
    </script>
@endpush
