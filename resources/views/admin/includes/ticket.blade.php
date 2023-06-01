<section class="content new-pagination">
    <h1>Ticket List</h1>

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Ticket Id</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created by</th>
                <th>Created Date</th>
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
                        data: 'ticket_id',
                        name: 'ticket_id'
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'created_date',
                        name: 'created_date'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
