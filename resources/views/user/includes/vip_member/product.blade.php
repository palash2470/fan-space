<?php
$product = $meta_data['product'] ?? '';
?>

<div class="uploadProduct product_vip_form">
    <h4>{{ isset($product->id) ? 'Update' : 'Create' }} Product</h4>
    <ul class="d-flex w-100 align-items-center">
        <li class="inf">Product Type</li>
        <li class="val">
          <?php
          $opt1 = $opt2 = $opt3 = $opt4 = '';
          if(isset($product->type)) {
            if($product->type == '1') $opt1 = 'selected="selected"';
            if($product->type == '2') $opt2 = 'selected="selected"';
            if($product->type == '3') $opt3 = 'selected="selected"';
            if($product->type == '4') $opt4 = 'selected="selected"';
          }
          ?>
            <select name="type" id="" class="selectMd-3">
                <option value="1" {{ $opt1 }}>Document</option>
                <option value="2" {{ $opt2 }}>Audio</option>
                <option value="3" {{ $opt3 }}>Video</option>
                <option value="4" {{ $opt4 }}>Merchandise</option>
            </select>
        </li>
    </ul>
    <ul class="d-flex w-100 align-items-center">
        <li class="inf">Product Image</li>
        <li class="val">
            <!-- <div class="file-upload active">
                <div class="file-select">
                    <div class="file-select-button" id="fileName">Choose File</div>
                    <div class="file-select-name" id="noFile">No file chosen...</div>
                    <input type="file" name="researcher_file" id="chooseFile">
                </div>
            </div> -->
            <?php
            $thumbnail = $product->thumbnail ?? '';
            ?>
            <div class="file-upload {{ $thumbnail != '' ? 'active' : '' }}" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" preview>
              <div class="file-preview">
                  <img src="{{ url('public/uploads/product/' . $thumbnail) }}" class="sel_img" />
              </div>
              <div class="file-select">
                  <div class="file-select-button">Choose File</div>
                  <div class="file-select-name">{{ $thumbnail }}</div>
                  <input type="file" name="thumbnail" id="chooseFile">
                  <input type="hidden" name="thumbnail_removed" value="" />
                  <a href="javascript:;" class="fp-close">x</a>
              </div>
            </div>
        </li>
    </ul>
    <ul class="d-flex w-100 align-items-center">
        <li class="inf">Product Description</li>
        <li class="val">
            <textarea name="product_desc" id="product_desc" cols="30" class="input-4" rows="10" style="height: 120px;">{{ $product->description ?? '' }}</textarea>
        </li>
    </ul>
    <ul class="d-flex w-100 align-items-center">
        <li class="inf">Available Stock</li>
        <li class="val">
            <input type="text" name="stock" id="stock" placeholder="" class="input-4" value="{{ $product->stock ?? '' }}" />
        </li>
    </ul>
    <ul class="d-flex w-100 align-items-center">
        <li class="inf">Product title</li>
        <li class="val">
            <input type="text" name="title" id="" placeholder="" class="input-4" value="{{ $product->title ?? '' }}" />
        </li>
    </ul>
    <ul class="d-flex w-100 align-items-center">
        <li class="inf">Product Coin Price <i class="fas fa-info-circle" data-toggle="tooltip" title="" data-original-title="1 coin is equivalent to 1 gbp"></i></li>
        
        
        <li class="val"><input type="text" name="price" id="" placeholder="" class="input-4" value="{{ $product->price ?? '' }}" />
        
        </li>
       
    </ul>
    <div class="product_attachment" style="{{ ((isset($product->type) && in_array($product->type, [1, 2, 3]) != '') || !isset($product->type)) ? '' : 'display: none;' }}">
    <ul class="d-flex w-100 align-items-start">
        <li class="inf">Upload Product</li>
        <li class="val">
            <!-- <div class="file-upload active">
                <div class="file-select">
                    <div class="file-select-button" id="fileName">Choose File</div>
                    <div class="file-select-name" id="noFile">No file chosen...</div>
                    <input type="file" name="researcher_file" id="chooseFile">
                </div>
            </div>
            <div class="imgDesc">
                <ul class="d-flex justify-content-between">
                    <li>Title</li>
                    <li>Size : <span>30MB</span></li>
                    <li>
                        <a href="#"><i class="fas fa-trash-alt"></i></a>
                    </li>
                </ul>
            </div> -->
            <?php
            $attachment = $product->attachment ?? '';
            $allowedExt = 'pdf,png,jpg,jpeg';
            if(isset($product->type)) {
              $allowedExt = '';
              if($product->type == '1') $allowedExt = 'pdf,png,jpg,jpeg';
              if($product->type == '2') $allowedExt = 'mp3';
              if($product->type == '3') $allowedExt = 'mp4';
            }
            ?>
            <div class="file-upload {{ $attachment != '' ? 'active' : '' }}" allowedExt="{{ $allowedExt }}" maxSize="524288000" maxSize_txt="500mb" no_preview>
              <div class="file-preview--- relative text-left">
                  <?php
                  /*if($attachment != '')
                    echo '<a href="' . url('public/uploads/product/' . $attachment) . '" target="_blank">Download</a>';*/
                    if($attachment != '')
                    echo '<a href="' . url('download?t=product&pid=' . $product->id) . '">Download</a>';
                  ?>
              </div>
              <div class="file-select">
                  <div class="file-select-button">Choose File</div>
                  <div class="file-select-name">{{ $attachment }}</div>
                  <input type="file" name="attachment" id="chooseFile">
                  <input type="hidden" name="attachment_removed" value="" />
                  <a href="javascript:;" class="fp-close">x</a>
              </div>
            </div>
        </li>
    </ul>
    </div>
    <ul class="d-flex w-100 align-items-center mt-3">
        <li class="inf">&nbsp;</li>
        <li class="val">
          <input type="hidden" name="id" value="{{ $product->id ?? '' }}" />
          <button class="createProduct product_submit">Save Product</button>
        </li>
    </ul>
</div>
