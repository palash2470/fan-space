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
              <th>User</th><th>Coins</th><th>Amount</th><th>Txn</th><th>Status</th><th>Date</th><th style="width: 100px">Action</th>
            </tr>

		        <?php
            foreach ($meta_data['payouts'] as $value) {
              ?>
              <tr>
                <td>{{ $value->first_name . ' ' . $value->last_name }}</td>
                <td>{{ $value->token_coins }}</td>
                <td>${{ $value->price_amount }}</td>
                <td>{{ $value->transaction_id }}</td>
                <td>{!! $value->status == '0' ? '<span class="text-yellow"><i class="fa fa-minus-circle"></i></span>' : '' !!}{!! $value->status == '1' ? '<span class="text-green"><i class="fa fa-check"></i></span>' : '' !!}</td>
                <td>{{ date('d-m-Y h:i:a', strtotime($value->created_at)) }}</td>
                <td><a href="{{ url('/admin/payouts?form=' . $value->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a><!-- <a href="javascript:;" class="btn btn-danger payout_delete" payout_id="{{ $value->id }}"><i class="fa fa-trash"></i></a> --></td>
              </tr>
            <?php } ?>

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
	

	$(document).ready(function(){


	});
  
</script>

@endpush