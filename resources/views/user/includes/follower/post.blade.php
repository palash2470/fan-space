<?php
$profile_photo = url('/public/front/images/user-placeholder.jpg');
if(isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
    $profile_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
?>

<div class="post-wrap">                        
    
    <?php
    //dd($meta_data['posts']);
    ?>
    <div class="tab-content">
        <div class="tab-pane active post_list_box" id="tabs-1" role="tabpanel" ajax_running="0" total_page="1" cur_page="1" per_page="1">
            <?php 
            $post_html = \App\Post::own_post_html(['post' => $meta_data['post'], 'user_data' => $user_data]);
            echo $post_html['html'];
            ?>
        </div>
    </div>
</div>
</div>





  @push('script')

  <script type="text/javascript">
      $(document).ready(function() {

      });
  </script>

@endpush