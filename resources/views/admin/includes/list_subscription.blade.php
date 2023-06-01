<section class="content new-pagination">
    <h1>Subscription List</h1>

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Subscriber Name</th>
                <th>Model Name</th>
                <th>Validity Date</th>
                <th>Next Renewal Date</th>
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
        $(document).on('click','.cancel_subscription',function(){
            // alert($(this).data('id'));
            if(confirm('Are you sure you to want cancel this subscription?')){
                // alert($(this).data('id'));
                let url = "{{ url('admin/cancel_subscription') }}";
                let data = {id:$(this).data('id'),_token:prop.csrf_token}; 
                $.ajax({type: 'POST', dataType: 'json', url: url, data: data,success: function(result){
					console.log(result);
				}
			});
            }
        });
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
                //         d.user = $('#user').val(),
                //             d.type = $('#type').val()
                //     }
                // },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'subscriber_name',
                        name: 'subscriber_name'
                    },
                    {
                        data: 'model_name',
                        name: 'model_name'
                    },
                    {
                        data: 'validity_date',
                        name: 'validity_date'
                    },
                    {
                        data: 'next_renewal_date',
                        name: 'next_renewal_date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions'
                    },
                ]
            });
        });
    </script>
@endpush
