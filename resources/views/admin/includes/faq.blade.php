
<section class="content new-pagination">
    <div class="heading-wrap">
        <div class="row d-flex justify-content-between new-row">
            <div class="col-auto">
                <div class="wrap-head">
                    <h1>FAQ List</h1>
                </div>
            </div>
            <div class="col-auto">
                <div class="wrap">
                    <a href="{{url('admin/add_faq')}}" class="btn btn-primary">Add FAQ</a>
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
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Date</th>
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
                searchDelay: 350,
                // searching: false,
                // "bLengthChange": false,
                ajax: "{{ \Route::currentRouteName() }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'question',
                        name: 'question'
                    },
                    {
                        data: 'answer',
                        name: 'answer'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false, searchable: false
                    },
                ]
            });
            $(document).on('click','.del-faq',function(){
                if (confirm('Are you sure you want to delete?')) {
                    var url = $(this).data('url');
                    window.location.href = url;
                }
            })
        });
    </script>

@endpush
