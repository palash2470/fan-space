
<section class="content-header">
    <h1>
      Edit Faq
    </h1>
</section>
<section class="content">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <form action="{{url('admin/edit_faq')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="box-wrap">
            <div class="row">
                <label class="col-sm-3 control-label">Question</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="question" id="question" value="{{old('question',$meta_data['faq']->question)}}">
                    @if($errors->has('question'))
                        <div class="error">{{ $errors->first('question') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="box-wrap">
            <div class="row">
                <label class="col-sm-3 control-label">Answer</label>
                <div class="col-sm-9">
                    <textarea class="form-control editor" id="answer" name="answer" rows="6">{{old('answer',$meta_data['faq']->answer)}}</textarea>
                    @if($errors->has('answer'))
                        <div class="error">{{ $errors->first('answer') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="box-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <input type="hidden" name="id" value="{{$meta_data['faq']->id}}">
                    <button type="submit" class="btn btn-primary pull-right settings_submit">Save</button>
                </div>
            </div>
        </div>

    </form>
</section>

@push('scripts')
<script type="text/javascript">
    function preview_image(input, target) {
      var ext = $(input).val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
        $(input).val('');
          alert('invalid photo!');
          return;
      }
      if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              var output = "<img src='"+e.target.result+"' />";
                $(target).html(output);
                remove_photo(target, input);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function remove_photo(target, element) {
      $('.remove_photo').click(function(){
        $(target).html('');
        $(element).val('');
      });
    }
  </script>
<script>
    $(function () {


      $(".editor").each(function(){
        var eid = $(this).attr('id');
        var editor = CKEDITOR.replace( eid, {
          format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
          filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
          filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
          filebrowserWindowWidth: '1000',
          filebrowserWindowHeight: '700'
        });
        CKFinder.setupCKEditor( editor );
      });

      /*var editor = CKEDITOR.replace( 'editor1', {
        format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
        filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
        filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
        filebrowserWindowWidth: '1000',
        filebrowserWindowHeight: '700'
      });
      CKFinder.setupCKEditor( editor );*/

    })
  </script>
@endpush
