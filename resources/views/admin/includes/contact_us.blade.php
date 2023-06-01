<section class="content new-pagination">
    <h1>Contact Us List</h1>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
    @endif
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Phone No.</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</section>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Email</h4>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin/send_email') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" name="subject" id="subject" placeholder="Subject"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea name="message" id="message" placeholder="message" class="form-control"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <input type="hidden" name="eloquent" id="eloquent" value="">
                            <input type="hidden" name="eloquent_id" id="eloquent_id" value="">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

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
                /*  ajax: {
                     url: "{{ \Route::currentRouteName() }}",
                     data: function(d) {
                         d.user = $('#user').val(),
                             d.type = $('#type').val()
                     }
                 }, */
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'ph_no',
                        name: 'ph_no'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },
                ]
            });

        });
        $(document).on('click', '.send-email', function() {
            // alert();
            $('#eloquent').val($(this).data('eloquent'));
            $('#eloquent_id').val($(this).data('id'));
        });
    </script>
@endpush
