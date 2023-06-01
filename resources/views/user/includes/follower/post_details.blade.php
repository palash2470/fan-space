<div class="post-wrap">
    <?php
    $total_page = ceil($meta_data['total_posts'] / $meta_data['per_page']);
    ?>
    <div class="tab-content m-t-10">
        <div class="tab-pane active post_list_box" id="tabs-1" role="tabpanel" ajax_running="0" total_page="{{ $total_page }}" cur_page="{{ $meta_data['cur_page'] }}" per_page="{{ $meta_data['per_page'] }}">
            <?php foreach ($meta_data['posts'] as $key => $value) {
                $post_html = \App\Post::post_html(['post' => $value, 'user_data' => $user_data]);
                echo $post_html['html'];
            ?>
            <?php } ?>
            
        </div>
        <div class="tab-pane" id="tabs-2" role="tabpanel">
            
        </div>
    </div>
</div>
<div class="modal fade media_modal" id="mediaViewModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body relative">
                <a href="javascript:;" class="close" data-dismiss="modal">x</a>
                <div class="media_details"></div>
            </div>
        </div>
    </div>
</div>
@push('script')
  <script type="text/javascript">
      $(document).ready(function() {
        vip_member_following_posts({});
      });
      $('.view_full_media').click(function(){
        //   alert();
        var src = $(this).data('src');
        $('#mediaViewModal .media_details').html(`<img src="`+src+`" class="post-media" />`);
        $('#mediaViewModal').modal('show');
      });
  </script>
@endpush