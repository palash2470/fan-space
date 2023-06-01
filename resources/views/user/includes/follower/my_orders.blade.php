<h4>Orders</h4>

<div class="table-responsive">
  <table class="table">
      <thead>
          <tr>
              <th scope="col">Seller</th>
              <th scope="col">Email</th>
              <th scope="col">Phone</th>
              <th scope="col">Total Amount</th>
              <th scope="col">Status</th>
              <th scope="col">Date</th>
              <th scope="col">Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php
          foreach ($meta_data['orders'] as $key => $value) {
            $billing_address = $shipping_address = [];
            foreach ($value->address_data as $k => $v) {
              if($v->type == '1') $billing_address = $v;
              if($v->type == '2') $shipping_address = $v;
            }
            $status = '';
            if($value->status == 0) $status = 'Inactive';
            if($value->status == 1) $status = 'Pending';
            if($value->status == 2) $status = 'Cancelled';
            if($value->status == 3) $status = 'Completed';
            echo '<tr order_status="' . $value->status . '">
                <td><a href="' . url('u/' . $value->vip_member_username) . '">' . $value->vip_member_display_name . '</a></td>
                <td>' . $value->email . '</td>
                <td>' . $value->phone . '</td>
                <td>' . $value->total_amount . '</td>
                <td>' . $status . '</td>
                <td>' . date('d-m-Y', strtotime($value->created_at)) . '</td>
                <td><a href="javascript:;" class="commonBtn2 toggleRowDetails">View</a></td>
            </tr>
            <tr class="rowDetailsRow"><td colspan="7" style="display: none;">
              <div class="row">
                <div class="col-md-6">
                  <h5>Billing Address</h5>
                  <p><b>' . ($billing_address->first_name . ' ' . $billing_address->last_name) . '</b></p>
                  ' . ($billing_address->company_name != '' ? '<p>' . $billing_address->company_name . '</p>' : '') . '
                  <p>' . $billing_address->address_line_1 . '</p>
                  <p>' . $billing_address->country_title . ', ' . $billing_address->zip_code . '</p>
                </div>
                <div class="col-md-6 text-right">
                  <h5>Shipping Address</h5>
                  <p><b>' . ($shipping_address->first_name . ' ' . $shipping_address->last_name) . '</b></p>
                  ' . ($shipping_address->company_name != '' ? '<p>' . $shipping_address->company_name . '</p>' : '') . '
                  <p>' . $shipping_address->address_line_1 . '</p>
                  <p>' . $shipping_address->country_title . ', ' . $shipping_address->zip_code . '</p>
                </div>
              </div>
              <br>
              <table class="table table-hover">
                <thead><tr><th scope="col">Name</th><th scope="col">Attachment</th><th scope="col">Coins</th><th scope="col">Quantity</th><th scope="col">Total Amount</th></tr></thead>
                <tbody>';
                foreach ($value->item_data as $k => $v) {
                  echo '<tr><td><a href="javascript:;" class="show_product_details" product_id="' . $v->product_id . '">' . $v->title . '</a></td><td>' .($v->attachment != '' ? '<button type="button" class="btn btn-primary myModal" data-toggle="modal" data-attachmentType="'.$v->type.'" data-attachment="'.$v->attachment.'" data-target="#myModal"><i class="fas fa-eye"></i></button>': ''). '</td><td>' . $v->price . '</td><td>' . $v->quantity . '</td><td>' . ($v->price * $v->quantity) . '</td></tr>';
                }
                echo '</tbody>
              </table>
            </td></tr>
            <tr></tr>';
          }
          ?>
      </tbody>
  </table>
</div>
<div class="samplePage d-flex justify-content-center">
  {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], '') !!}
  <?php
  /*
  {!! App\Http\Helpers::paginate(10, 25, 8524, '#', '', 'pagination-sm no-margin pull-right') !!}
  */
  ?>
</div>


@include('front._partials.modal_product_details')

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          {{-- <h4 class="modal-title">Modal Heading</h4> --}}
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body show-attachment" id="pdfRenderer">

        </div>

        <!-- Modal footer -->


      </div>
    </div>
</div>

@push('script')

<script type="text/javascript">
  $(document).ready(function(){

    var btn = document.querySelectorAll('.myModal');
    Array.from(btn).forEach(link => {
        link.addEventListener('click', (event)=> {
            let type= event.currentTarget.getAttribute('data-attachmentType');
            let attachment= event.currentTarget.getAttribute('data-attachment');
            let show_attachment = document.querySelector('.show-attachment');
            let data = {type:type,attachment:attachment,"_token": prop.csrf_token}
            // let src = "{{ url('/show-attachment/') }}/{{ (base_path('/public/uploads/product/attachments/')) }}/"+attachment;
            let src= "{{ url('/show-attachment/') }}/"+attachment;
            // alert(src)
            // $.ajax({
			// type: 'POST',
			// dataType: 'json',
			// url: "{{ url('/show-attachment') }}",
			// data: {type:type,attachment:attachment,"_token": prop.csrf_token},

            // async:false,
			// success: function(data){
            //     console.log('data',data)
            //     // show_attachment.innerHTML = data.html
			// }
		    // });
            if(type == '1' || type==1){
                if(attachment.split('.').pop()=='pdf'){
                    src= "{{ url('/show-attachment/') }}/"+attachment+'/1';
                    show_attachment.innerHTML =`<iframe src="`+src+`#toolbar=0" height="600px" width="100%"></iframe>`;
                    // show_attachment.innerHTML =`<iframe src="`+src+`" runat="server" height="100px" width="100px"></iframe>`;
                    // var pdf = new PDFObject({
                    // url: src,
                    // id: "pdfRendered",
                    // pdfOpenParams: {
                    //     view: "FitH"
                    // }
                    // }).embed("pdfRenderer");
                    // var options = {
                    //     height: "400px",
                    //     pdfOpenParams: { view: 'FitV', page: '2' }
                    // };
                    // PDFObject.embed(src, "#pdfRenderer", {fallbackLink: false});
                    // show_attachment.innerHTML =`<a class="fancybox" href="`+src+`"></a>`;
                    // $(".fancybox").fancybox({
                    //     openEffect: 'elastic',
                    //     closeEffect: 'elastic',
                    //     autoSize: true,
                    //     type: 'iframe',
                    //     iframe: {
                    //         preload: false // fixes issue with iframe and IE
                    //     }
                    // });
                    // $('.fancybox').click();
                }else{
                show_attachment.innerHTML =`<img src="`+src+`"">`;
                }
            }else if(type == '2' || type==2){
                show_attachment.innerHTML =`<audio controls>
                <source src="`+(src)+`" type="video/mp4">

                Your browser does not support HTML video.
                </audio>`;
            }else if(type == '3' || type==3){
                show_attachment.innerHTML =`<video controls>
                <source src="`+(src)+`" type="video/mp4">

                Your browser does not support HTML video.
                </video>`;
            }



        });
    });

    $(document).on('click', '.toggleRowDetails', function(){
      $(this).closest('tr').next('.rowDetailsRow').find('> td').stop(true, true).toggle();
    });

  });
</script>

@endpush
