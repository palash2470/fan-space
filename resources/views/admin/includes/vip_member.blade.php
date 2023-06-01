<?php
$status = $_GET['status'] ?? '';
$search = $_GET['s'] ?? '';
$countries = [];
foreach ($meta_data['countries'] as $key => $value) {
    $countries[$value->country_id] = $value->name;
}
?>

<section class="content-header">
    <h1>
        Model {{ $status == 0 ? '(Inactive)' : '' }} {{ $status == 1 ? '(Active)' : '' }}

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

            <form class="form-horizontal margin-bottom" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <label class="">Search</label>
                        <input type="text" class="form-control" name="s" value="{{ $search }}" />
                    </div>
                    <div class="col-md-2">
                        <label class="">Status</label>
                        <?php
                        $opt2 = $opt3 = '';
                        if ($status == '1') {
                            $opt2 = 'selected="selected"';
                        }
                        if ($status == '0') {
                            $opt3 = 'selected="selected"';
                        }
                        ?>
                        <select class="form-control" name="status">
                            <option value="">-- Select --</option>
                            <option value="1" {!! $opt2 !!}>Active</option>
                            <option value="0" {!! $opt3 !!}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="">&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover"
                    style1="background: {{ $meta_data['global_settings']['settings_misc_admin_tablebg'] }};">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <?php
        foreach ($meta_data['vip_members'] as $value) {
          $address = '';
          if(isset($value->usermeta_data['address_line_1'])) $address .= $value->usermeta_data['address_line_1'];
          $address .= ', ' . $value->country_name;
          if(isset($value->usermeta_data['zipcode'])) $address .= ' ' . $value->usermeta_data['zipcode'];
          $own_categories = [];
          foreach ($value->own_cat_data as $k => $v) {
            $own_categories[] = $v->title;
          }
          $interested_categories = [];
          foreach ($value->interest_cat_data as $k => $v) {
            $interested_categories[] = $v->title;
          }
          $bank_details = '';
          if(isset($value->usermeta_data['bank_account_name'])) $bank_details .= '<b>A/C Name :</b> ' . $value->usermeta_data['bank_account_name'] . '<br>';
          if(isset($value->usermeta_data['bank_account_sort_code'])) $bank_details .= '<b>Sort Code :</b> ' . $value->usermeta_data['bank_account_sort_code'] . '<br>';
          if(isset($value->usermeta_data['bank_account_number'])) $bank_details .= '<b>A/C :</b> ' . $value->usermeta_data['bank_account_number'] . '<br>';
          $bank_details .= '<b>Country :</b> ' . $value->bank_country_name;
          $id_proof_doc = $value->usermeta_data['id_proof_doc'] ?? '';
          $id_proof = $value->usermeta_data['id_proof'] ?? '';
          $profile_photo = $value->usermeta_data['profile_photo'] ?? '';
          $profile_video = $value->usermeta_data['profile_video'] ?? '';
          $profile_banner = $value->usermeta_data['profile_banner'] ?? '';
          if($id_proof_doc != '') $id_proof_doc = '<a href="' . url('public/uploads/id_proof_doc/' . $id_proof_doc) . '" target="_blank">View / Download</a>';
          if($id_proof != '') $id_proof = '<a href="' . url('public/uploads/id_proof/' . $id_proof) . '" target="_blank">View / Download</a>';
          if($profile_photo != '') $profile_photo = '<a href="' . url('public/uploads/profile_photo/' . $profile_photo) . '" target="_blank">View / Download</a>';
          if($profile_video != '') $profile_video = '<a href="' . url('public/uploads/profile_video/' . $profile_video) . '" target="_blank">View / Download</a>';
          if($profile_banner != '') $profile_banner = '<a href="' . url('public/uploads/profile_banner/' . $profile_banner) . '" target="_blank">View / Download</a>';
          ?>
                        <tr>
                            <td>{{ $value->first_name . ' ' . $value->last_name }}</td>
                            <td>{{ $value->email }}</td>
                            <td>{{ $value->username }}</td>
                            <td>{{ $value->phone }}</td>
                            <td>{{ $address }}</td>
                            <td>{!! implode(', ', $own_categories) !!}</td>
                            <td>{!! $value->status == 1 ? '<span class="text-green"><i class="fa fa-check"></i></span>' : '' !!}{!! $value->status == 0 ? '<span class="text-red"><i class="fa fa-times"></i></span>' : '' !!}</td>
                            <td>
                            <div class="dashboard-btn-list-wrap">
                                <ul>
                                    <li>
                                        <a href="javascript:;" class="btn btn-primary details" uid="{{ $value->id }}"><i
                                        class="fa fa-eye"></i></a>
                                    </li>
                                <?php
                                if ($value->status == 1) {
                                    echo '<li><a href="javascript:;" class="btn btn-danger change_status" uid="' . $value->id . '"><i class="fa fa-times"></i></a></li>';
                                } else {
                                    echo '<li><a href="javascript:;" class="btn btn-success change_status" uid="' . $value->id . '"><i class="fa fa-check"></i></a></li>';
                                }
                                ?>
                                        <li>
                                            <a class="btn btn-info" href="javascript:;" data-toggle="modal" data-target="#myModal"
                                            data-id="{{ $value->id }}" data-eloquent="user" class="send-email"><i
                                            class="fa fa-envelope"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- <a href="javascript:;" class="btn btn-primary change_password" uid="{{ $value->id }}"><i class="fa fa-key"></i></a> -->
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" style="padding: 0px;">
                                <div class="row_details" style="display: none; padding: 20px;">
                                    <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <th style="width: 30%;">Display Name</th>
                                                <th style="width: 30px;">:</th>
                                                <td>{!! $value->display_name !!}</td>
                                            </tr>
                                            <tr>
                                                <th>DOB</th>
                                                <th>:</th>
                                                <td>{!! date('d-m-Y', strtotime($value->dob)) !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Interested In</th>
                                                <th>:</th>
                                                <td>{!! implode(', ', $interested_categories) !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Allow Vip Friend Request</th>
                                                <th>:</th>
                                                <td>{!! isset($value->usermeta_data['allow_vip_friend_request']) && $value->usermeta_data['allow_vip_friend_request'] == 1 ? '<span class="text-green"><i class="fa fa-check"></i></span>' : '<span class="text-red"><i class="fa fa-times"></i></span>' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Subscription Price</th>
                                                <th>:</th>
                                                <td>{!! $value->usermeta_data['subscription_price'] ?? '' !!}</td>
                                            </tr>

                                            <tr>
                                                <th>Twitter Url</th>
                                                <th>:</th>
                                                <td>{!! isset($value->usermeta_data['twitter_url']) && $value->usermeta_data['twitter_url'] != '' ? '<a href="' . $value->usermeta_data['twitter_url'] . '" target="_blank">' . $value->usermeta_data['twitter_url'] . '</a>' : '' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Wishlist Url</th>
                                                <th>:</th>
                                                <td>{!! isset($value->usermeta_data['wishlist_url']) && $value->usermeta_data['wishlist_url'] != '' ? '<a href="' . $value->usermeta_data['wishlist_url'] . '" target="_blank">' . $value->usermeta_data['wishlist_url'] . '</a>' : '' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Bio</th>
                                                <th>:</th>
                                                <td>{!! nl2br($value->usermeta_data['about_bio'] ?? '') !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Enable Free Follower</th>
                                                <th>:</th>
                                                <td>{!! isset($value->usermeta_data['free_follower']) && $value->usermeta_data['free_follower'] == 1 ? '<span class="text-green"><i class="fa fa-check"></i></span>' : '<span class="text-red"><i class="fa fa-times"></i></span>' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Profile Keywords</th>
                                                <th>:</th>
                                                <td>{!! $value->usermeta_data['profile_keywords'] ?? '' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Bank Details</th>
                                                <th>:</th>
                                                <td>{!! $bank_details !!}</td>
                                            </tr>
                                            <tr>
                                                <th>ID Proof Doc</th>
                                                <th>:</th>
                                                <td>{!! $id_proof_doc !!}</td>
                                            </tr>
                                            <tr>
                                                <th>ID Proof</th>
                                                <th>:</th>
                                                <td>{!! $id_proof !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Profile Photo</th>
                                                <th>:</th>
                                                <td>{!! $profile_photo !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Profile Video</th>
                                                <th>:</th>
                                                <td>{!! $profile_video !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Profile Banner</th>
                                                <th>:</th>
                                                <td>{!! $profile_banner !!}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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
        $(document).ready(function() {

            setTimeout(function() {
                $(".content .alert").remove();
            }, 3000);

            $(document).on('click', 'td a.details', function() {
                $(this).closest('tr').next().find('.row_details').stop(true, true).slideToggle();
            });

            $(document).on('click', '.change_status', function() {
                var user_id = $(this).attr('uid');
                if (confirm('Are you sure to change status?')) {
                    $(".box .overlay").show();
                    var data = {
                        'user_id': user_id
                    };
                    $.ajax({
                        method: "POST",
                        url: "<?php echo url('adminajax/change_user_status'); ?>",
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

            $('.send-email').click(function() {
                // alert();
                $('#eloquent').val($(this).data('eloquent'));
                $('#eloquent_id').val($(this).data('id'));
            });
        });
    </script>
@endpush
