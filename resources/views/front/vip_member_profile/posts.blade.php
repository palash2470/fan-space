<?php
$section = Request::segment(3);
$age = $_GET['age'] ?? '';
$ordby = $_GET['ordby'] ?? '';
$ord = $_GET['ord'] ?? '';
?>
<div class="d-flex justify-content-end">
<a href="javascript:;" class="commonBtn filter-btn" data-toggle="modal" data-target=".filterPostModal">Filter Posts <i class="fas fa-filter"></i></a>
</div>
<?php
$total_page = ceil($meta_data['total_posts'] / $meta_data['per_page']);
?>
<div class="mt-3 post_list_box {{ in_array($section, ['photos', 'videos']) ? 'row view_media-row' : '' }}" ajax_running="0" total_page="{{ $total_page }}" cur_page="{{ $meta_data['cur_page'] }}" per_page="{{ $meta_data['per_page'] }}">
    <?php foreach ($meta_data['posts'] as $key => $value) {
        $grid_view = 0;
        if(in_array($section, ['photos', 'videos'])) $grid_view = 1;
        $post_html = \App\Post::post_html(['post' => $value, 'grid_view' => $grid_view, 'user_data' => $user_data]);
        echo $post_html['html'];
    }
    ?>
</div>
<div class="modal fade modelModal filterPostModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content relative">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            
                            <div class="modelModalBox w-100">
                                <!-- <h5>Status</h5> -->
                                <ul>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_age_1" class="styled" type="radio" name="age" value="" {{ $age == '' ? 'checked' : '' }} />
                                        <label for="fp_age_1">All Time</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_age_2" class="styled" type="radio" name="age" value="3m" {{ $age == '3m' ? 'checked' : '' }} />
                                        <label for="fp_age_2">Last Three Months</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_age_3" class="styled" type="radio" name="age" value="1m" {{ $age == '1m' ? 'checked' : '' }} />
                                        <label for="fp_age_3">Last One Month</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_age_4" class="styled" type="radio" name="age" value="1w" {{ $age == '1w' ? 'checked' : '' }} />
                                        <label for="fp_age_4">Last Week</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="modelModalBox w-100">
                                <!-- <h5>Status</h5> -->
                                <ul>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_ordby_1" class="styled" type="radio" name="ordby" value="latest" {{ $ordby == 'latest' ? 'checked' : '' }} />
                                        <label for="fp_ordby_1">Latest Post</label>
                                    </li>
                                		<li class="checkbox checkbox-info">
                                        <input id="fp_ordby_2" class="styled" type="radio" name="ordby" value="liked" {{ $ordby == 'liked' ? 'checked' : '' }} />
                                        <label for="fp_ordby_2">Most Liked</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_ordby_3" class="styled" type="radio" name="ordby" value="tip" {{ $ordby == 'tip' ? 'checked' : '' }} />
                                        <label for="fp_ordby_3">Highest Tipped</label>
                                    </li>
                                  </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="modelModalBox w-100">
                                <!-- <h5>Status</h5> -->
                                <ul>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_ord_1" class="styled" type="radio" name="ord" value="asc" {{ $ord == 'asc' ? 'checked' : '' }} />
                                        <label for="fp_ord_1">Ascending</label>
                                    </li>
                                    <li class="checkbox checkbox-info">
                                        <input id="fp_ord_2" class="styled" type="radio" name="ord" value="desc" {{ $ord == 'desc' ? 'checked' : '' }} />
                                        <label for="fp_ord_2">Descending</label>
                                    </li>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                            <button type="button" class="commonBtn filter_post_submit">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade media_modal" id="mediaViewModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body relative">
                <a href="javascript:;" class="close">x</a>
                <div class="media_details"></div>
            </div>
        </div>
    </div>
</div>
@push('script')
  <script type="text/javascript">
      $(document).ready(function() {
        vip_member_profile_posts({'vip_member_id': {{ $meta_data['vip_member']['user']->id }}, 'age': '{{ $age }}', 'ordby': '{{ $ordby }}', 'ord': '{{ $ord }}', 'section': '{{ $section }}' });
        profile_filter_post({'vip_member_id': {{ $meta_data['vip_member']['user']->id }}, 'vip_member_username': '{{ $meta_data['vip_member']['user']->username }}', 'section': '{{ $section }}'});
      });
  </script>
@endpush