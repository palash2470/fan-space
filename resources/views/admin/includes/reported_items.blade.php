<section class="content-header">
    <h1>
        Reported Items
        <!-- <small>it all starts here</small> -->


    </h1>
    <div class="clearfix"></div>
    <!--<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Examples</a></li>
    <li class="active">Blank page</li>
  </ol>-->
</section>


<section class="content">

    @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
    @endif

    <div class="box box-solid">
        <!-- <div class="box-header with-border">
      <h3 class="box-title">Collapsible Accordion</h3>
    </div> -->
        <!-- /.box-header -->
        <div class="box-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover"
                    style1="background: {{ $meta_data['global_settings']['settings_misc_admin_tablebg'] }};">
                    <tbody>
                        <tr>
                            <th>Item</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="width: 200px">Action</th>
                        </tr>
                        <?php
        foreach ($meta_data['reported_items'] as $value) {
          $item_html = '';
          if($value->type == '1') {
            if(isset($value->item_data->id)) {
              $item_html .= '<a href="' . url('u/' . $value->item_data->username) . '" target="_blank">' . $value->item_data->display_name . ' (@' . $value->item_data->username . ')' . '</a>';
            }
          }
          if($value->type == '2') {
            if(isset($value->item_data->id)) {
              $item_html .= $value->item_data->post_content;
              if($value->item_data->post_type == 'photo') {
                $item_html .= '<br><img src="' . url('public/uploads/post_media/' . $value->item_data->media_file) . '" style="max-width: 200px; max-height: 100px;" />';
              }
              if($value->item_data->post_type == 'video') {
                $item_html .= '<br><a href="' . url('public/uploads/post_media/' . $value->item_data->media_file) . '" target="_blank">view / download</a>';
              }
              if($value->item_data->post_type == 'doc') {
                $item_html .= '<br><a href="' . url('public/uploads/post_media/' . $value->item_data->media_file) . '" target="_blank">view / download</a>';
              }
            }
          }
          ?>
                        <tr>
                            <td>{!! $item_html !!}</td>
                            <td>{{ $value->first_name . ' ' . $value->last_name }}</td>
                            <td>{{ date('d-m-Y H:i') }}</td>
                            <td>{{ $value->status }}</td>
                            <td>
                                {{-- <a href="javascript:;" class="btn btn-danger delete_item"
                                    item_id="{{ $value->id }}"><i class="fa fa-trash"></i></a> --}}
                                @if ($value->status == 'Pending')
                                    @if ($value->type == '1')
                                        <a href="javascript:;" class="btn btn-danger delete_item"
                                            item_id="{{ $value->id }}" data-type="block">Block User</a>
                                    @else
                                        <a href="javascript:;" class="btn btn-danger delete_item"
                                            item_id="{{ $value->id }}" data-type="block">Block Post</a>
                                    @endif
                                    <a href="javascript:;" class="btn btn-danger delete_item"
                                        item_id="{{ $value->id }}" data-type="reject">Reject</a>
                                @endif

                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer clearfix">
            <!-- <ul class="pagination pagination-sm no-margin pull-right">
        <li><a href="#">«</a></li>
        <li><a href="#" class="active">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">»</a></li>
      </ul> -->
            {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], 'pagination-sm no-margin pull-right') !!}
        </div>

    </div>

</section>



@push('scripts')
    <script type="text/javascript">

    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            setTimeout(function() {
                $(".content .alert").remove();
            }, 3000);



            $(document).on('click', '.delete_item', function() {
                var item_id = $(this).attr('item_id');
                let type = $(this).data('type');
                if (confirm('Are you sure ?')) {
                    $(".box .overlay").show();
                    var data = {
                        'item_id': item_id,
                        'type': type,
                    };
                    $.ajax({
                        method: "POST",
                        url: "<?php echo url('adminajax/delete_reported_item'); ?>",
                        dataType: 'json',
                        data: {
                            _token: prop.csrf_token,
                            data: data
                        },
                        success: function(data) {
                            location.reload();
                        }
                    });
                }
            });


        });
    </script>
@endpush
