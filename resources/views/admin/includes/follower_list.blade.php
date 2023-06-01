<section class="content new-pagination">
    <div class="heading-wrap">
        <div class="row d-flex justify-content-between new-row">
            <div class="col-auto">
                <div class="wrap-head">
                    <h1>Follower List</h1>
                    {{-- <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider round"></span>
                    </label> --}}
                </div>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    {{-- <div class="table-responsive"> --}}
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    {{-- </div> --}}
</section>
@push('scripts')
    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 350,
                // searching: false,
                // "bLengthChange": false,
                ajax: "{{ \Route::currentRouteName() }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                    },
                    {
                        data: 'address',
                        name: 'address',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                    },
                ]
            });
        });

        $(document).on('change', '.user_status', function() {
            let url = "{{ url('adminajax/change_user_status') }}";
            let id = $(this).val();
            var data = {
                'user_id': id
            };

            $.ajax({
                url: url,
                dataType: "json",
                type: "Post",
                data: {
                    _token: prop.csrf_token,
                    data: data
                },
                success: function(data) {
                    console.log(data);
                    location.reload();
                },
                error: function(xhr, exception) {

                }
            });


        });
        $(document).on('click', '.delete-user', function() {
            // alert($(this).data('id'));
            if (confirm('Are you sure  want to delete?')) {
                let id = $(this).data('id');
                let url = "{{ url('adminajax/delete_user') }}";
                $.ajax({
                    url: url,
                    dataType: "json",
                    type: "Post",
                    data: {
                        _token: prop.csrf_token,
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function(xhr, exception) {

                    }
                });
            }
        })
    </script>
@endpush
