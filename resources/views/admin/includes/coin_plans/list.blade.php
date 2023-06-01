<div class="box box-solid">
    <!-- <div class="box-header with-border">
      <h3 class="box-title">Collapsible Accordion</h3>
    </div> -->
    <!-- /.box-header -->
    <div class="box-body">

    	<div class="table-responsive">
	      <table class="table table-bordered table-hover" style1="background: {{ $meta_data['global_settings']['settings_misc_admin_tablebg'] }};">
	        <tbody>
	          <tr>
		          <th>Title</th><th>Info</th><th>Coins</th><th>Price</th><th style="width: 200px">Action</th>
		        </tr>

		        <?php
		        foreach ($meta_data['coin_plans'] as $key => $value) {
		        	echo '<tr><td>' . $value->title . '</td><td>' . $value->info . '</td><td>' . $value->coins . '</td><td>' . $value->price . '</td>
		        	<td>
		        		<a href="' . url('admin/coin_plans?form=' . $value->id) . '" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
		        		<a href="javascript:;" class="btn btn-danger delete" cpid="' . $value->id . '"><i class="fa fa-trash"></i></a>
		        	</td></tr>';
		        }
		        ?>

	        </tbody></table>
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




@push('scripts')

<script type="text/javascript">
	function validate_coin_plan() {
		return true;
	}

	$(document).ready(function(){

		$(".delete[cpid]").click(function(e){
      e.preventDefault();
      if(confirm('Are you sure to delete?')) {
        var coin_plan_id = $(this).attr('cpid');
        $(".box .overlay").show();
        var data = {
          'coin_plan_id': coin_plan_id,
        };
        $.ajax({
          method: "POST",
          url: "<?php echo url('adminajax/coin_plan_delete'); ?>",
          dataType : 'json',
          data: { _token: prop.csrf_token, data: data},
          success: function(data) {
            window.location.href = "{{ url('admin/coin_plans') }}";
          }
        });

      }
    });

	});
  
</script>

@endpush