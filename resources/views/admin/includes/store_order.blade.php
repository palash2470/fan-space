<section class="content new-pagination">
    <h1>Order Details</h1>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="product-box-wrap">
                <span class="product-img">
                    @if ($meta_data['product_details']->thumbnail)
                        <img src="{{ url('public/uploads/product/' . $meta_data['product_details']->thumbnail) }}"
                            alt="">
                    @else
                        <img src="{{ url('public/uploads/product/product-placeholder.jpg') }}" alt=""
                            style="max-width:100px;">
                    @endif
                </span>
                <h4>{{ $meta_data['product_details']->title }}</h4>
                <h5><strong>Model Name :</strong> {{ $meta_data['product_details']->user_details->full_name }}</h5>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order By</th>
                        <th>Order Address</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        {{-- <div class="col-lg-12">
            <div class="bg-white-box">
                Product Name : {{ $meta_data['product_details']->title }}
                Product Image : @if ($meta_data['product_details']->thumbnail)
                    <img src="{{ url('public/uploads/product/' . $meta_data['product_details']->thumbnail) }}" alt=""
                        style="max-width:100px;">
                @else
                    <img src="{{ url('public/uploads/product/product-placeholder.jpg') }}" alt=""
                        style="max-width:100px;">
                @endif
                Model Name : {{ $meta_data['product_details']->user_details->full_name }}
            </div>
        </div> --}}
    </div>

</section>
@push('scripts')
    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                "bLengthChange": false,
                ajax: "{{ \Route::currentRouteName() }}",
                // ajax: {
                //     url: "{{ \Route::currentRouteName() }}",
                //     data: function(d) {
                //         d.model = $('#model').val()
                //     }
                // },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'order_by',
                        name: 'order_by'
                    },
                    {
                        data: 'order_address',
                        name: 'order_address'
                    },
                    {
                        data: 'item_price',
                        name: 'item_price',
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'order_date',
                        name: 'order_date'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
