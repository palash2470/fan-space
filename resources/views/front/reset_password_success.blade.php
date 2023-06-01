@extends('layouts.front')
@section('content')



<section class="samplePageWrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="samplePage">
                        <h4>{{ Session::get('success') }}</h4>
                    </div>
                </div>
               
            </div>
        </div>
    </section>


@stop




@push('script')

<script type="text/javascript">
    $(document).ready(function(){

        

    });
</script>

@endpush