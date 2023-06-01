<?php
$profile_photo = url('/public/front/images/user-placeholder.jpg');
if(isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
    $profile_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
?>
<div class="post-wrap">                        
    <div class="type-post">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="back-new-post post-head">
                    <h3>new post</h3>
                    <ul class="post-cata">
                        <li class="post_visibility" visibility="public" subscriber_ids=""><i class="fa fa-eye"></i> <span>Public</span></li>
                    </ul>
                    <!-- <a class="back-new-post-btn" href111="javascript:;"><span class="ti-arrow-left"></span>new post</a>
                    <a href="javascript:;" class="post_visibility" visibility="public" subscriber_ids=""><i class="fa fa-eye"></i> <span>Public</span></a> -->
                </div>
            </div>
            <div class="col-auto">
                <div class="new-post-btn">
                    <button type="button" class="post-btn post_option">post</button>
                </div>
            </div>
        </div>
        <div class="compose-new-post d-flex align-items-center">
            <div class="post-user">
                <span><img src="{{ $profile_photo }}" alt=""></span>
            </div>
            <div class="post-input-wrap">
                <textarea class="form-control post-txtare-style autoheight" placeholder="new post..... " name="post_content" style="max-height: 200px;"></textarea>
            </div>
        </div>
        <div class="w-100">
            <input type="hidden" name="post_type" value="text" />
            <ul class="d-flex social-live-icon post-social-icon-list justify-content-center">
                <li><a href="javascript:;" post_type="video"><span class="ti-video-camera"></span> Video</a></li>
                <li><a href="javascript:;" post_type="photo"><span class="ti-image"></span> Photo</a></li>
                <li><a href="javascript:;" post_type="doc"><span class="ti-folder"></span> Document</a></li>
                <li><a href="{{ url('dashboard/go_live') }}"><span class="ti-eye"></span> Live</a></li>
            </ul>
        </div>
        <div class="w-100">
            <div class="m-t-20 post_type_file" for="video" style="display: none;">
                <h5>Video</h5>
                <div class="file-upload" allowedExt="mp4" maxSize="{{ 500 * 1024 *1024 }}" maxSize_txt="500mb" no_preview>
                    <div class="file-select">
                        <div class="file-select-button" id111="fileName">Choose File</div>
                        <div class="file-select-name" id111="noFile"></div>
                        <input type="file" name="post_media_video" id="chooseFile">
                        <input type="hidden" name="post_media_video_removed" value="" />
                        <a href="javascript:;" class="fp-close">x</a>
                    </div>
                </div>
                <h5>Thumbnail</h5>
                <div class="file-upload" allowedExt="jpg,png" maxSize="{{ 10 * 1024 *1024 }}" maxSize_txt="10mb" preview>
                    <div class="file-preview">
                        <img src="" class="sel_img" />
                    </div>
                    <div class="file-select">
                        <div class="file-select-button" id111="fileName">Choose File</div>
                        <div class="file-select-name" id111="noFile"></div>
                        <input type="file" name="post_media_video_thumbnail" id="chooseFile">
                        <input type="hidden" name="post_media_video_thumbnail_removed" value="" />
                        <a href="javascript:;" class="fp-close">x</a>
                    </div>
                </div>
            </div>
            <div class="m-t-20 post_type_file" for="photo" style="display: none;">
                <h5>Photo</h5>
                <div class="file-upload" allowedExt="jpg,png" maxSize="{{ 10 * 1024 *1024 }}" maxSize_txt="10mb" preview>
                    <div class="file-preview">
                        <img src="" class="sel_img" />
                    </div>
                    <div class="file-select">
                        <div class="file-select-button" id111="fileName">Choose File</div>
                        <div class="file-select-name" id111="noFile"></div>
                        <input type="file" name="post_media_photo" id="chooseFile">
                        <input type="hidden" name="post_media_photo_removed" value="" />
                        <a href="javascript:;" class="fp-close">x</a>
                    </div>
                </div>
            </div>
            <div class="m-t-20 post_type_file" for="doc" style="display: none;">
                <h5>Document</h5>
                <div class="file-upload" allowedExt="pdf" maxSize="{{ 50 * 1024 *1024 }}" maxSize_txt="50mb" no_preview>
                    <div class="file-select">
                        <div class="file-select-button" id111="fileName">Choose File</div>
                        <div class="file-select-name" id111="noFile"></div>
                        <input type="file" name="post_media_doc" id="chooseFile">
                        <input type="hidden" name="post_media_doc_removed" value="" />
                        <a href="javascript:;" class="fp-close">x</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- <ul class="d-flex social-live-icon">
            <li><a href="#"><span class="ti-video-camera"></span> Video</a></li>
            <li><a href="#"><span class="ti-image"></span>Photo</a></li>
            <li><a href="#"><span class="ti-image"></span>Document</a></li>
            <li><a href="#"><span class="ti-image"></span>Live</a></li>
        </ul> -->
    </div>
    <!-- <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">30 post</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">30 media</a>
        </li>
    </ul> -->
    <?php
    $total_page = ceil($meta_data['total_posts'] / $meta_data['per_page']);
    //dd($meta_data['posts']);
    ?>
    <div class="tab-content m-t-30">
        <div class="tab-pane active post_list_box" id="tabs-1" role="tabpanel" ajax_running="0" total_page="{{ $total_page }}" cur_page="{{ $meta_data['cur_page'] }}" per_page="{{ $meta_data['per_page'] }}">
            <?php foreach ($meta_data['posts'] as $key => $value) {
                $post_html = \App\Post::own_post_html(['post' => $value, 'user_data' => $user_data]);
                echo $post_html['html'];
            ?>
            <?php } ?>
            <!-- <div class="post-wrap-box">
                <div class="post-wrap-box-top d-flex align-items-center">
                    <div class="post-user-img">
                        <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                    </div>
                    <div class="post-user-details">
                        <a href="#">
                            <h3>KellyLouiseX <span><i class="ti-heart"></i></span></h3>
                            <p>@Bikiniwarrior</p>
                        </a>
                    </div>
                </div>
                <div class="post-wrap-box-middle">
                    <div class="post-only-text">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio voluptatum aperiam soluta hic repudiandae, explicabo iusto laboriosam harum voluptate. Cupiditate modi accusantium iusto aliquam a officiis natus laboriosam dolore? Provident.</p>
                    </div>
                </div>
                <div class="post-wrap-box-btm">
                    <div class="row align-items-center justify-content-between post-time-wrap">
                        <div class="col-auto">
                            <div class="d-flex post-time-description">
                                <span><img src="{{ url('public/front/images/confetti-lft.png') }}" alt=""></span>
                                <p>Birthday Month</p>
                                <span><img src="{{ url('public/front/images/confetti-rt.png') }}" alt=""></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <p>1 minute 11 second</p>
                        </div>
                    </div>
                    <div class="post-content">
                        <p>Be sure to subscribe to see what i get upto my birthday <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                        <p>Plus tips & purchase are the best presents you can get me <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                    </div>
                    <div class="row align-items-center justify-content-between comment-love-wrap">
                        <div class="col-auto">
                            <ul class="d-flex comment-live-icon">
                                <li><a href="#"><span class="ti-heart"></span> 2</a></li>
                                <li><a href="#"><span class="ti-comment"></span>2</a></li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <div class="option-btn-wrap relative">
                                <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                                <ul class="option-btn-details">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="no-comment-wrap">
                        <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                    </div>
                    <div class="post-user-btm d-flex align-items-center">
                        <div class="post-user-img">
                            <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                        </div>
                        <div class="comment-input-wrap relative">
                            <textarea class="form-control comment-txtare-style" placeholder="write a comment.......... "></textarea>
                            <div class="comment-emoji">
                                <button class="comment-emoji-btn" type="button">
                                    <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="post-wrap-box">
                <div class="post-wrap-box-top d-flex align-items-center">
                    <div class="post-user-img">
                        <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                    </div>
                    <div class="post-user-details">
                        <a href="#">
                            <h3>KellyLouiseX <span><i class="ti-heart"></i></span></h3>
                            <p>@Bikiniwarrior</p>
                        </a>
                    </div>
                </div>
                <div class="post-wrap-box-middle">
                    <div class="homeVideoInner">
                        <video id="example_video_1" class="video-js" controls preload="none" poster="{{ url('public/front/images/video-img.jpg') }}" data-setup="{}">
                            <source src="{{ url('public/front/media/1.mp4') }}" type="video/mp4">
                          </video>
                    </div>
                </div>
                <div class="post-wrap-box-btm">
                    <div class="row align-items-center justify-content-between post-time-wrap">
                        <div class="col-auto">
                            <div class="d-flex post-time-description">
                                <span><img src="{{ url('public/front/images/confetti-lft.png') }}" alt=""></span>
                                <p>Birthday Month</p>
                                <span><img src="{{ url('public/front/images/confetti-rt.png') }}" alt=""></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <p>1 minute 11 second</p>
                        </div>
                    </div>
                    <div class="post-content">
                        <p>Be sure to subscribe to see what i get upto my birthday <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                        <p>Plus tips & purchase are the best presents you can get me <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                    </div>
                    <div class="row align-items-center justify-content-between comment-love-wrap">
                        <div class="col-auto">
                            <ul class="d-flex comment-live-icon">
                                <li><a href="#"><span class="ti-heart"></span> 2</a></li>
                                <li><a href="#"><span class="ti-comment"></span>2</a></li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <div class="option-btn-wrap relative">
                                <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                                <ul class="option-btn-details">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="no-comment-wrap">
                        <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                    </div>
                    <div class="post-user-btm d-flex align-items-center">
                        <div class="post-user-img">
                            <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                        </div>
                        <div class="comment-input-wrap relative">
                            <textarea class="form-control comment-txtare-style" placeholder="write a comment.......... "></textarea>
                            <div class="comment-emoji">
                                <button class="comment-emoji-btn" type="button">
                                    <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="post-wrap-box">
                <div class="post-wrap-box-top d-flex align-items-center">
                    <div class="post-user-img">
                        <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                    </div>
                    <div class="post-user-details">
                        <a href="#">
                            <h3>KellyLouiseX <span><i class="ti-heart"></i></span></h3>
                            <p>@Bikiniwarrior</p>
                        </a>
                    </div>
                </div>
                <div class="post-wrap-box-middle">
                    <div class="post-subscribe-wrap-box relative d-flex align-items-center justify-content-center">
                        <div class="post-subscribe-details">
                            <i class="fas fa-lock"></i>
                            <p class="post-subscribe">Please Subscribe See this user post</p>                                                
                            <div class="post-subscribe-btm-main">
                                <div class="post-subscribe-btm d-flex align-items-center justify-content-center">
                                    <p class="d-flex align-items-center"><span class="ti-video-camera"></span> <span class="live-number">2</span></p>
                                    <a class="post-subscribe-btn" href="#">Unloke post for $6.69</a>
                                    <p><i class="fas fa-lock"></i> $6.69</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-wrap-box-btm">
                    <div class="row align-items-center justify-content-between post-time-wrap">
                        <div class="col-auto">
                            <div class="d-flex post-time-description">
                                <span><img src="{{ url('public/front/images/confetti-lft.png') }}" alt=""></span>
                                <p>Birthday Month</p>
                                <span><img src="{{ url('public/front/images/confetti-rt.png') }}" alt=""></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <p>1 minute 11 second</p>
                        </div>
                    </div>
                    <div class="post-content">
                        <p>Be sure to subscribe to see what i get upto my birthday <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                        <p>Plus tips & purchase are the best presents you can get me <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                    </div>
                    <div class="row align-items-center justify-content-between comment-love-wrap">
                        <div class="col-auto">
                            <ul class="d-flex comment-live-icon">
                                <li><a href="#"><span class="ti-heart"></span> 2</a></li>
                                <li><a href="#"><span class="ti-comment"></span>2</a></li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <div class="option-btn-wrap relative">
                                <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                                <ul class="option-btn-details">
                                    <li><a href="#">Edit</a></li>
                                    <li><a href="#">delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="no-comment-wrap">
                        <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                    </div>
                    <div class="post-user-btm d-flex align-items-center">
                        <div class="post-user-img">
                            <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                        </div>
                        <div class="comment-input-wrap relative">
                            <textarea class="form-control comment-txtare-style" placeholder="write a comment.......... "></textarea>
                            <div class="comment-emoji">
                                <button class="comment-emoji-btn" type="button">
                                    <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="tab-pane" id="tabs-2" role="tabpanel">
            
        </div>
    </div>
</div>
</div>
<div class="modal fade bd-example-modal-sm post_visibility_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Post Visibility</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <!-- <div class="modelModalBox w-100">
                    <ul>
                        <li class="checkbox checkbox-info">
                            <input id="vcb1" class="styled" type="radio" name="visibility" value="public" checked="checked" />
                            <label for="vcb1">Public</label>
                        </li>
                        <li class="checkbox checkbox-info">
                            <input id="vcb2" class="styled" type="radio" name="visibility" value="subscriber">
                            <label for="vcb2">Subscribers</label>
                        </li>
                        <li class="checkbox checkbox-info">
                            <input id="vcb3" class="styled" type="radio" name="visibility" value="subscriber_except">
                            <label for="vcb3">Subscribers Except</label>
                        </li>
                    </ul>
                    <div class="subscriber_except_options" style="display: none;">
                        <div class="form-group from-input-wrap">
                            <h5>Search Subscribers</h5>
                            <select name="subscriber_id[]" multiple action="search_subscribers" max_selection="3" min_input="1" per_page="10" sortable_container=".post_visibility_modal .subscriber_except_options"></select>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary set_post_visibility">Set Visibility</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-md post_option_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Post Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="modelModalBox w-100">
                    <ul>
                        <li class="checkbox checkbox-info">
                            <input id="pocb1" class="styled" type="radio" name="post_value" value="0" checked="checked" />
                            <label for="pocb1">Free <i class="fas fa-info-circle" data-toggle="tooltip" title="Will create free posts"></i></label>
                        </li>
                        <li class="checkbox checkbox-info">
                            <input id="pocb2" class="styled" type="radio" name="post_value" value="1" />
                            <label for="pocb2">Paid <i class="fas fa-info-circle" data-toggle="tooltip" title="Will create paid posts"></i></label>
                        </li>
                    </ul>
                    <div class="post_value_options" style="display: none;">
                        <div class="form-group from-input-wrap">
                            <h5>Post heading </h5>
                            
                            <textarea name="post_head" id="post_head" class="input-3" placeholder="Post heading" style="height: 74px;"></textarea>
                            
                            
                            
                        </div>
                        <div class="form-group from-input-wrap">
                            <h5>Coin Amount</h5>
                            <input type="text" name="post_price" id="" class="input-3" placeholder="Coin Amount" value="" />
                        </div>
                    </div>
                    
                    <div class="modelModalBox w-100">
                        <h4>Post Visibility</h4>
                        <ul>
                            <li class="checkbox checkbox-info">
                                <input id="vcb1" class="styled" type="radio" name="visibility" value="public" checked="checked" />
                                <label for="vcb1">Public <i class="fas fa-info-circle" data-toggle="tooltip" title="Posts for everyone"></i></label>
                            </li>
                            <li class="checkbox checkbox-info">
                                <input id="vcb2" class="styled" type="radio" name="visibility" value="subscriber">
                                <label for="vcb2">Subscribers <i class="fas fa-info-circle" data-toggle="tooltip" title="Posts for subscribers only"></i></label>
                            </li>
                            <li class="checkbox checkbox-info">
                                <input id="vcb3" class="styled" type="radio" name="visibility" value="subscriber_except">
                                <label for="vcb3">Subscribers Except <i class="fas fa-info-circle" data-toggle="tooltip" title="Posts for subscribers excluded selected"></i></label>
                            </li>
                        </ul>
                        <div class="subscriber_except_options" style="display: none;">
                            <div class="form-group from-input-wrap">
                                <h5>Search Subscribers</h5>
                                <select name="subscriber_id[]" multiple action="search_subscribers" max_selection="3" min_input="1" per_page="10" sortable_container=".post_visibility_modal .subscriber_except_options"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary post_submit">Create Post</button>
            </div>
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


<!-- <div class="modal fade" id="shareModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! $meta_data['shareComponent'] !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div> -->
<!-- <textarea id="demo1">
  Lorem ipsum dolor 😍 sit amet, consectetur 👻 adipiscing elit, 🖐 sed do eiusmod tempor ☔ incididunt ut labore et dolore magna aliqua 🐬.
  </textarea>
  <div id="emoji_container"></div>
  <span class="aa">dfdfdfdfdff</span>
<div class="emoji_pad">
    <textarea>Lorem ipsum dolor 😍 sit magna aliqua 1 🐬.</textarea>
    <div class="emoji_container" id="emoji_container_1"></div>
    <a href="javascript:;" class="emoji_submit">Save</a>
</div>
<div class="emoji_pad">
    <textarea>Lorem ipsum dolor 😍 sit magna aliqua 2 🐬.</textarea>
    <div class="emoji_container" id="emoji_container_2"></div>
    <a href="javascript:;" class="emoji_submit">Save</a>
</div>
<div class="emoji_pad" save_action="set_comment" save_params="<?php echo json_encode(['post_id' => 20]); ?>">
    <textarea>Lorem ipsum dolor 😍 sit magna aliqua 3 🐬.</textarea>
    <div class="emoji_container" id="emoji_container_3"></div>
    <a href="javascript:;" class="emoji_submit">Save</a>
</div> -->
  @push('script')
  <script type="text/javascript">
      $(document).ready(function() {
        vip_member_own_posts();
        $('.view_full_media').click(function(){
        //   alert();
        var src = $(this).data('src');
        $('#mediaViewModal .media_details').html(`<img src="`+src+`" class="post-media" />`);
        $('#mediaViewModal').modal('show');
      });
    //   $('.socialShareModal').click(function(){
    //     $('#shareModal').modal('show');
    //   });
      });
  </script>
    <!-- <script type="text/javascript">
        function emoji_pad() {
            $('.emoji_pad').each(function(){
                var container = $(this).find('.emoji_container').attr('id');
                var el = $(this).find('textarea').emojioneArea({
                    container: "#" + container,
                    hideSource: false,
                    useSprite: true,
                    autocomplete: false,
                    saveEmojisAs: 'image'
                });
                el[0].emojioneArea.on("keydown", function(editor, event) {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if(keycode == '13'){
                        console.log(el[0].emojioneArea.getText());  
                    }
                });
                $(this).find('.emoji_submit').unbind('click').on('click', function(){
                    console.log(el[0].emojioneArea.getText());
                    el[0].emojioneArea.setText('');
                });
            });
            
        }
    $(document).ready(function() {
        var el = $("#demo1").emojioneArea({
            container: "#emoji_container",
            hideSource: false,
            useSprite: true,
            autocomplete: false,
            saveEmojisAs: 'image'
          });
        $(document).on('click', '.aa', function(){
            console.log(el[0].emojioneArea.getText());
        });
        el[0].emojioneArea.on("keydown", function(editor, event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                console.log(el[0].emojioneArea.getText());  
            }
        });
      emoji_pad();
    });
  </script> -->
@endpush