<?php
$section = Request::segment(3);
?>

<!-- <a href="javascript:;" class="commonBtn" data-toggle="modal" data-target=".filterPostModal">Filter Posts</a> -->


<?php
$total_page = ceil($meta_data['total_products'] / $meta_data['per_page']);
?>
<div class="mt-3 row storeWrap product_list_box" ajax_running="0" total_page="{{ $total_page }}" cur_page="{{ $meta_data['cur_page'] }}" per_page="{{ $meta_data['per_page'] }}">
    <?php foreach ($meta_data['products'] as $key => $value) {
        /*$grid_view = 0;
        if(in_array($section, ['photos', 'videos'])) $grid_view = 1;
        $post_html = \App\Post::post_html(['post' => $value, 'grid_view' => $grid_view, 'user_data' => $user_data]);
        echo $post_html['html'];*/
        $product_html = \App\Product::product_html(['product' => $value, 'user_data' => $user_data]);
        echo $product_html['html'];
        /*$thumbnail = url('public/front/images/product-thumbnail.png');
        if($value->thumbnail != '')
          $thumbnail = url('public/uploads/product/' . $value->thumbnail);*/
    ?>
    
    <?php } ?>
</div>



@include('front._partials.modal_product_details')








@push('script')

  <script type="text/javascript">
      $(document).ready(function() {

        vip_member_profile_products({'vip_member_id': {{ $meta_data['vip_member']['user']->id }} });

      });
  </script>

@endpush