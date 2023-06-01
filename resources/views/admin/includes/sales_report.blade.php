{{-- @dd('money'); --}}
<section class="content new-pagination">
    <h1>Sales Report</h1>
    <form action="{{route('export.sales-report')}}" method="POST">
    @csrf
    <div class="bg-white-box">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="type">Type :</label>
                    <select name="type" id="type" class="form-control">
                        <option value="date_wise" @if (old('type')=='date_wise') selected @endif>Date Wise</option>
                        <option value="product_wise" @if (old('type')=='product_wise') selected @endif>Product Wise</option>
                        <option value="model_wise" @if (old('type')=='model_wise') selected @endif>Model Wise</option>
                    </select>
                    @if($errors->has('type'))
                        <div class="error">{{ $errors->first('type') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-sm-4 disabled">
                <div class="form-group">
                    <label for="type">Start Date :</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{old('start_date')}}">
                    @if($errors->has('start_date'))
                        <div class="error">{{ $errors->first('start_date') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-sm-4 disabled">
                <div class="form-group">
                    <label for="type">End Date :</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{old('end_date')}}">
                    @if($errors->has('end_date'))
                        <div class="error">{{ $errors->first('end_date') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-sm-4 disabled">
                <div class="form-group">
                    <label for="product">Product :</label>
                    <select name="product" id="product" class="form-control">
                        <option value="">select</option>
                        @foreach ($meta_data['product'] as $item)
                            <option value="{{$item->id}}" @if (old('product')==$item->id) selected @endif>{{$item->title}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('product'))
                        <div class="error">{{ $errors->first('product') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-sm-4 disabled">
                <div class="form-group">
                    <label for="model">Model :</label>
                    <select name="model" id="model" class="form-control">
                        <option value="">select</option>
                        @foreach ($meta_data['model'] as $item)
                            <option value="{{$item->id}}" @if (old('model')==$item->id) selected @endif>{{$item->full_name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('model'))
                        <div class="error">{{ $errors->first('model') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary" id="export_sales_report">Export</button>
            </div>
        </div>
    </div>
    </form>
</section>
@push('scripts')

<script>
$(document).ready(function(){
    $('.disabled').hide();
    let type = $('#type').val();
    if(type=="date_wise"){
        $('#start_date').closest('.disabled').show();
        $('#end_date').closest('.disabled').show();
    }
    if(type=="product_wise"){
        $('#product').closest('.disabled').show();
    }
    if(type=="model_wise"){
        $('#model').closest('.disabled').show();
    }
});
$(document).on('change','#type',function(){
    $('.disabled').hide();
    let type = $('#type').val();
    if(type=="date_wise"){
        $('#start_date').closest('.disabled').show();
        $('#end_date').closest('.disabled').show();
    }
    if(type=="product_wise"){
        $('#product').closest('.disabled').show();
    }
    if(type=="model_wise"){
        $('#model').closest('.disabled').show();
    }
});
</script>
@endpush
