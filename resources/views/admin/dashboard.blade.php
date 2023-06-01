@extends('layouts.admin')
@section('content')


    @include('admin.includes.' . $meta_data['slug'])


@stop


@push('scripts')

    <script type="text/javascript">

    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            setTimeout(function() {
                $(".content .alert").remove();
            }, 3000);

        });
    </script>

    {{-- <script>
  $(function () {

    var editor = CKEDITOR.replace( 'editor1', {
      filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
      filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
      filebrowserWindowWidth: '1000',
      filebrowserWindowHeight: '700'
    });
    CKFinder.setupCKEditor( editor );

    var editor = CKEDITOR.replace( 'editor2', {
      filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
      filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
      filebrowserWindowWidth: '1000',
      filebrowserWindowHeight: '700'
    });
    CKFinder.setupCKEditor( editor );

  })
</script> --}}

@endpush
