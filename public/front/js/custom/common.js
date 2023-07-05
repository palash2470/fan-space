function isEmail(emailid) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailid);
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function getAllUrlParams(url) {
    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
    // we'll store the parameters here
    var obj = {};
    // if query string exists
    if (queryString) {
        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];
        // split our query string into its component parts
        var arr = queryString.split('&');
        for (var i = 0; i < arr.length; i++) {
            // separate the keys and the values
            var a = arr[i].split('=');
            // set parameter name and value (use 'true' if empty)
            var paramName = a[0];
            var paramValue = typeof(a[1]) === 'undefined' ? true : a[1];
            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();
            // if the paramName ends with square brackets, e.g. colors[] or colors[2]
            if (paramName.match(/\[(\d+)?\]$/)) {
                // create key if it doesn't exist
                var key = paramName.replace(/\[(\d+)?\]/, '');
                if (!obj[key]) obj[key] = [];
                // if it's an indexed array e.g. colors[2]
                if (paramName.match(/\[\d+\]$/)) {
                    // get the index value and add the entry at the appropriate position
                    var index = /\[(\d+)\]/.exec(paramName)[1];
                    obj[key][index] = paramValue;
                } else {
                    // otherwise add the value to the end of the array
                    obj[key].push(paramValue);
                }
            } else {
                // we're dealing with a string
                if (!obj[paramName]) {
                    // if it doesn't exist, create property
                    obj[paramName] = paramValue;
                } else if (obj[paramName] && typeof obj[paramName] === 'string') {
                    // if property does exist and it's a string, convert it to an array
                    obj[paramName] = [obj[paramName]];
                    obj[paramName].push(paramValue);
                } else {
                    // otherwise add the property
                    obj[paramName].push(paramValue);
                }
            }
        }
    }
    return obj;
}

function onlyNumbers(elem) {
    $(document).on("keypress keyup blur", elem, function(event) {
        //$(elem).on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
}

function onlyNumbersWithDecimal(elem) {
    $(document).on("keypress keyup blur", elem, function(event) {
        //$(elem).on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
}


function textareaMaxLength(elem, length) {
    $(document).on('keyup', elem, function(e) {
        var value = $.trim($(this).val());
        totchar = value.length;
        if (totchar > length) {
            $(this).val(value.substring(0, length));
        }
        var value2 = $.trim($(this).val());
        totchar2 = value2.length;
    });
}


function imagereadURL(input, imgChange, wrapper, maxSize = '') {
    imgChange.closest('.dragAndDrop').closest(wrapper).find('.error').remove();
    imgChange.attr('src', prop.url + "/public/front/images/noImg2.jpg").fadeIn(650);
    imgChange.closest('.dragAndDrop').find('.imgHide').removeClass('active');
    imgChange.closest('.dragAndDrop').closest(wrapper).find('.remove_img_field').val('1');
    //$(input).siblings('.fileUploadLabel').html('<i class="fa fa-upload"></i> ' + translations_common.image_field_upload_image);
    var ext = $(input).val().split('.').pop().toLowerCase();
    if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
        $(input).val('');
        imgChange.closest('.dragAndDrop').closest(wrapper).append('<div class="error"><div class="clearfix"></div>Upload only image file</div>');
        return;
    }
    if (maxSize != '' && input.files && input.files[0].size > parseInt(maxSize)) {
        $(input).val('');
        imgChange.closest('.dragAndDrop').closest(wrapper).append('<div class="error"><div class="clearfix"></div>Select lower resolution</div>');
        return;
    }
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imgChange.attr('src', e.target.result).fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
        imgChange.closest('.dragAndDrop').find('.imgHide').addClass('active');
        //$(input).siblings('.fileUploadLabel').html('<i class="fa fa-refresh"></i> ' + translations_common.image_field_change_image);
    }
}


function removeImageInput(button, wrapper) {
    $(document).on('click', button, function(e) {
        e.preventDefault();
        $(this).removeClass("active");
        $(this).closest(".dragAndDrop ").find(".imgContainer img").attr("src", prop.url + "/public/front/images/noImg2.jpg");
        $(this).closest(".dragAndDrop ").find("input[type='file']").val('');
        //$(this).closest(".dragAndDrop ").find('.fileUploadLabel').html('<i class="fa fa-upload"></i> ' + translations_common.image_field_upload_image);
        $(this).closest('.dragAndDrop').closest(wrapper).find('.error').remove();
        $(this).closest('.dragAndDrop').closest(wrapper).find('.remove_img_field').val('1');
    });
}




/*function noPrevFileUpload(input, wrapper, maxSize = '', allowedExt = ['png','jpg','jpeg', 'pdf']) {
  $(input).closest(wrapper).find('.error').remove();
  $(input).closest(wrapper).find('.fileHide').removeClass('active');
  $(input).closest(wrapper).find('.file-uploaded').text('');
  var ext = $(input).val().split('.').pop().toLowerCase();
  if($.inArray(ext, allowedExt) == -1) {
    $(input).val('');
    $(input).closest(wrapper).append('<div class="error"><div class="clearfix"></div>Upload only ' + allowedExt.join(', ') + ' file</div>');
    return;
  }
  if(maxSize != '' && input.files && input.files[0].size > parseInt(maxSize)) {
    $(input).val('');
    $(input).closest(wrapper).append('<div class="error"><div class="clearfix"></div>Select lower file size</div>');
    return;
  }
  if(input.files && input.files[0]) {
    var file = input.files[0].name;
    $(input).closest(wrapper).find('.file-uploaded').text(file);
    $(input).closest(wrapper).find('.fileHide').addClass('active');
  }
}*/


function removeNoPrevInput(button, wrapper) {
    $(document).on('click', button, function(e) {
        e.preventDefault();
        $(this).removeClass("active");
        $(this).closest(wrapper).find("input[type='file']").val('');
        $(this).closest(wrapper).find('.file-uploaded').text('');
        $(this).closest(wrapper).find('.error').remove();
        $(this).closest(wrapper).find('.remove_file_field').val('1');
    });
}

function noPrevFileUpload() {
    $('.file-upload[no_preview]').each(function() {
        var noPrevFileUpload = $(this);
        var allowedExt = $(this).attr('allowedExt').split(',');
        var maxSize = $(this).attr('maxSize');
        var maxSize_txt = $(this).attr('maxSize_txt');
        var input_name = $(this).find('input[type="file"]').attr('name');
        var input = document.getElementsByName(input_name)[0];
        noPrevFileUpload.find('.file-select-button').unbind('click').click(function() {
            noPrevFileUpload.find('.error').remove();
            noPrevFileUpload.find('.file-select-name').text('');
            //noPrevFileUpload.find('.file-preview a').text('');
            noPrevFileUpload.removeClass('active');
            noPrevFileUpload.find('input[type="file"]').trigger('click');
        });
        noPrevFileUpload.find('.fp-close').unbind('click').click(function() {
            noPrevFileUpload.find('.file-select-name').text('');
            //noPrevFileUpload.find('.file-preview a').text('');
            noPrevFileUpload.find('input[type="file"]').val('');
            noPrevFileUpload.find('input[name="' + input_name + '_removed"]').val('1');
            noPrevFileUpload.removeClass('active');
        });
        noPrevFileUpload.find('input[type="file"]').unbind('change').on('change', function() {
            var ext = $(this).val().split('.').pop().toLowerCase();
            noPrevFileUpload.find('.error').remove();
            if (input.files && $.inArray(ext, allowedExt) == -1) {
                $(this).val('');
                noPrevFileUpload.append('<div class="error">Upload only ' + allowedExt.join(', ') + ' file</div>');
                return;
            }
            if (maxSize != '' && input.files && input.files[0].size > parseInt(maxSize)) {
                $(this).val('');
                noPrevFileUpload.append('<div class="error">Max file size ' + maxSize_txt + '</div>');
                return;
            }
            var file_name = input.files[0].name;
            noPrevFileUpload.find('.file-select-name').text(file_name);
            noPrevFileUpload.addClass('active');
        });
    });
}


function prevFileUpload() {
    $('.file-upload[preview]').each(function() {
        var prevFileUpload = $(this);
        var allowedExt = $(this).attr('allowedExt').split(',');
        var maxSize = $(this).attr('maxSize');
        var maxSize_txt = $(this).attr('maxSize_txt');
        var input_name = $(this).find('input[type="file"]').attr('name');
        var input = document.getElementsByName(input_name)[0];
        prevFileUpload.find('.file-select-button').unbind('click').click(function() {
            prevFileUpload.find('.error').remove();
            prevFileUpload.find('.file-select-name').text('');
            prevFileUpload.find('.file-preview img').attr('src', '');
            prevFileUpload.removeClass('active');
            prevFileUpload.find('input[type="file"]').trigger('click');
        });
        prevFileUpload.find('.fp-close').unbind('click').click(function() {
            prevFileUpload.find('.file-select-name').text('');
            prevFileUpload.find('.file-preview img').attr('src', '');
            prevFileUpload.find('input[type="file"]').val('');
            prevFileUpload.find('input[name="' + input_name + '_removed"]').val('1');
            prevFileUpload.removeClass('active');
        });
        prevFileUpload.find('input[type="file"]').unbind('change').on('change', function() {
            var ext = $(this).val().split('.').pop().toLowerCase();
            prevFileUpload.find('.error').remove();
            if (input.files && $.inArray(ext, allowedExt) == -1) {
                $(this).val('');
                prevFileUpload.append('<div class="error">Upload only ' + allowedExt.join(', ') + ' file</div>');
                return;
            }
            if (maxSize != '' && input.files && input.files[0].size > parseInt(maxSize)) {
                $(this).val('');
                prevFileUpload.append('<div class="error">Max file size ' + maxSize_txt + '</div>');
                return;
            }
            var file_name = input.files[0].name;
            prevFileUpload.find('.file-select-name').text(file_name);
            var reader = new FileReader();
            reader.onload = function(e) {
                prevFileUpload.find('.file-preview img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            prevFileUpload.addClass('active');
        });
    });
}

/*function prevFileUpload() {
  $('.prevFileUpload').each(function(){
    var prevFileUpload = $(this);
    var allowedExt = $(this).attr('allowedExt').split(',');
    var maxSize = $(this).attr('maxSize');
    var placeholder_img = $(this).find('img').attr('placeholder_img');
    var input_name = $(this).find('input[type="file"]').attr('name');
    var input = document.getElementsByName(input_name)[0];
    prevFileUpload.find('img').click(function(){
      prevFileUpload.find('.p-image').trigger('click');
    });
    prevFileUpload.find('.p-image').click(function(){
      prevFileUpload.find('.error').remove();
      prevFileUpload.find('img').attr('src', placeholder_img);
      prevFileUpload.removeClass('active');
      prevFileUpload.find('input[type="file"]').trigger('click');
    });
    prevFileUpload.find('.p-cross').click(function(){
      prevFileUpload.find('img').attr('src', placeholder_img);
      prevFileUpload.find('input[name="' + input_name + '_removed"]').val('1');
      prevFileUpload.removeClass('active');
    });
    prevFileUpload.find('input[type="file"]').on('change', function(){
      var ext = $(this).val().split('.').pop().toLowerCase();
      if(input.files && $.inArray(ext, allowedExt) == -1) {
        $(this).val('');
        prevFileUpload.append('<div class="error">Upload only ' + allowedExt.join(', ') + ' file</div>');
        return;
      }
      if(maxSize != '' && input.files && input.files[0].size > parseInt(maxSize)) {
        $(this).val('');
        prevFileUpload.append('<div class="error">Select lower file size</div>');
        return;
      }
      //var file_name = input.files[0].name;
      var reader = new FileReader();
      reader.onload = function (e) {
        prevFileUpload.find('img').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
      prevFileUpload.addClass('active');
    });
  });
}*/


function jplayer_video() {
    $(".jplayer_video").each(function() {
        var player_id = $(this).attr('player-id');
        var poster = $(this).attr('poster');
        var video = $(this).attr('video');
        var title = $(this).attr('video-title');

        if (video == '')
            return;

        var html = '<div id="jp_container_' + player_id + '" class="jp-video jp-video-270p" role="application" aria-label="media player">\
  <div class="jp-type-single">\
      <div id="jquery_jplayer_' + player_id + '" class="jp-jplayer"></div>\
      <div class="jp-gui">\
          <div class="jp-video-play">\
              <button class="jp-video-play-icon" role="button" tabindex="0">play</button>\
          </div>\
          <div class="jp-interface">\
              <div class="jp-progress">\
                  <div class="jp-seek-bar">\
                      <div class="jp-play-bar"></div>\
                  </div>\
              </div>\
              <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>\
              <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>\
              <div class="jp-controls-holder">\
                  <div class="jp-controls">\
                      <button class="jp-play" role="button" tabindex="0">play</button>\
                      <button class="jp-stop" role="button" tabindex="0">stop</button>\
                  </div>\
                  <div class="jp-volume-controls">\
                      <button class="jp-mute" role="button" tabindex="0">mute</button>\
                      <button class="jp-volume-max" role="button" tabindex="0">max volume</button>\
                      <div class="jp-volume-bar">\
                          <div class="jp-volume-bar-value"></div>\
                      </div>\
                  </div>\
                  <div class="jp-toggles">\
                      <button class="jp-repeat" role="button" tabindex="0">repeat</button>\
                      <button class="jp-full-screen" role="button" tabindex="0">full screen</button>\
                  </div>\
              </div>\
              <div class="jp-details">\
                  <div class="jp-title" aria-label="title">&nbsp;</div>\
              </div>\
          </div>\
      </div>\
      <div class="jp-no-solution">\
          <span>Update Required</span>\
          To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.\
      </div>\
  </div>\
</div>';

        $(this).html(html);

        $("#jquery_jplayer_" + player_id).jPlayer({
            ready: function() {
                $(this).jPlayer("setMedia", {
                    title: title,
                    m4v: video,
                    //webmv: "http://www.jplayer.org/video/webm/Big_Buck_Bunny_Trailer.webm",
                    poster: poster
                });
            },
            play: function() { // To avoid multiple jPlayers playing together.
                $(this).jPlayer("pauseOthers");
                //console.log('play');
            },
            pause: function() {
                //console.log('pause');
            },
            swfPath: prop.url + "/public/front/jplayer-dist/jplayer",
            supplied: "m4v",
            //supplied: "webmv",
            size: {
                width: "100%",
                height: "100%"
            },
            globalVolume: true,
            useStateClassSkin: true,
            autoBlur: false,
            smoothPlayBar: true,
            cssSelectorAncestor: "#jp_container_" + player_id,
            preload: 'none',
            keyEnabled: true
        });

    });
}


function date_range_picker(params) {
    var defaults = {
        'field_group': '.date_range',
        'field_from': 'input[name="date_from"]',
        'field_to': 'input[name="date_to"]',
        'date_format': 'dd/mm/yy',
        'min_start': { 'd': '', 'm': '', 'y': '' },
        'max_end': { 'd': '', 'm': '', 'y': '' },
        'yearRange': '-10:+20'
    }
    var options = $.extend(defaults, params);
    var timestamp = new Date().getTime();
    //console.log(timestamp);
    var maxDate = '';
    if (options.max_end.d != '' && options.max_end.m != '' && options.max_end.y != '')
        maxDate = new Date(options.max_end.y, options.max_end.m, options.max_end.d);
    window['from' + timestamp] = $(options.field_group + ' ' + options.field_from)
        .datepicker({
            showButtonPanel: true,
            beforeShow: function(input, obj) {
                setTimeout(function() {
                    var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                    $("button.ui-datepicker-close").hide();
                    $("button.ui-datepicker-current").hide();
                    $("<button>", {
                        text: "Clear",
                        click: function() {
                            $(input).val('').trigger('change');
                            window['to' + timestamp].datepicker("option", "minDate", null);
                        }
                    }).addClass('ui-state-default ui-corner-all').appendTo(buttonPane);
                }, 1);
            },
            defaultDate: "-1w",
            changeMonth: true,
            changeYear: true,
            dateFormat: options.date_format,
            maxDate: maxDate,
            yearRange: options.yearRange
        })
        .on("change", function() {
            //to.datepicker( "option", "minDate", getDateObj( options.field_group + ' ' + options.field_from ));
            window['to' + timestamp].datepicker("option", "minDate", getDate(this));
        }),
        window['to' + timestamp] = $(options.field_group + ' ' + options.field_to).datepicker({
            showButtonPanel: true,
            beforeShow: function(input, obj) {
                setTimeout(function() {
                    var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                    $("button.ui-datepicker-close").hide();
                    $("button.ui-datepicker-current").hide();
                    $("<button>", {
                        text: "Clear",
                        click: function() {
                            $(input).val('').trigger('change');
                            window['from' + timestamp].datepicker("option", "maxDate", null);
                        }
                    }).addClass('ui-state-default ui-corner-all').appendTo(buttonPane);
                }, 1);
            },
            defaultDate: "-1w",
            changeMonth: true,
            changeYear: true,
            dateFormat: options.date_format,
            maxDate: maxDate,
            yearRange: options.yearRange
        })
        .on("change", function() {
            //from.datepicker( "option", "maxDate", getDateObj( options.field_group + ' ' + options.field_to ) );
            window['from' + timestamp].datepicker("option", "maxDate", getDate(this));
        });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(options.date_format, element.value);
        } catch (error) {
            date = null;
        }
        return date;
    }
}


function getDateObj(element) {
    var elv = $(element).val();
    var elvs = elv.split('-');
    var elv_d = parseFloat(elvs[0]);
    var elv_m = (parseFloat(elvs[1]) - 1);
    var elv_y = parseFloat(elvs[2]);
    var dt = new Date(elv_y, elv_m, elv_d);
    return dt;
}


function datepicker_single() {
    $('.datepicker_single').each(function() {
        var defaultDate = "today";
        var maxDate = "today";
        var yearRange = "1980:2019";
        var defaultDate2 = $(this).attr('defaultDate');
        var maxDate2 = $(this).attr('maxDate');
        var yearRange2 = $(this).attr('yearRange');
        if (defaultDate2 != '-')
            defaultDate = defaultDate2;
        if (maxDate2 != '-')
            maxDate = maxDate2;
        if (yearRange2 != '-')
            yearRange = yearRange2;
        $(this).datepicker({
            showButtonPanel: true,
            beforeShow: function(input, obj) {
                setTimeout(function() {
                    var buttonPane = $(input).datepicker("widget").find(".ui-datepicker-buttonpane");
                    $("button.ui-datepicker-close").hide();
                    $("button.ui-datepicker-current").hide();
                    $("<button>", {
                        text: "Clear",
                        click: function() {
                            $(input).val('').trigger('change');
                        }
                    }).addClass('ui-state-default ui-corner-all').appendTo(buttonPane);
                }, 1);
            },
            defaultDate: defaultDate,
            maxDate: maxDate,
            changeMonth: true,
            changeYear: true,
            yearRange: yearRange,
            numberOfMonths: 1,
            dateFormat: "dd-mm-yy",
        });
    });
}


function mCustomScrollbar_init(element) {
    if (element == '') {
        $('.mCustomScrollbar_element').each(function() {
            var scrollbarPosition = 'outside';
            if ($(this).attr('scrollbarPosition') != '')
                scrollbarPosition = $(this).attr('scrollbarPosition');
            $(this).mCustomScrollbar({
                advanced: {
                    updateOnContentResize: true,
                    updateOnImageLoad: true
                },
                scrollInertia: 500,
                scrollbarPosition: scrollbarPosition,
                callbacks: {
                    //onTotalScroll: function() { mCustomScrollbar_onTotalScroll(this); },
                    //onScroll: function() { console.log(this.mcs.topPct); }
                    onScroll: function() { if (this.mcs.topPct > 70) { mCustomScrollbar_onTotalScroll(this); } }
                        //whileScrolling: function() { console.log(this.mcs.topPct); }
                }
            });
        });
    } else {
        var scrollbarPosition = 'outside';
        if ($(element).attr('scrollbarPosition') != '')
            scrollbarPosition = $(element).attr('scrollbarPosition');
        $(element).mCustomScrollbar("destroy");
        $(element).mCustomScrollbar({
            advanced: {
                updateOnContentResize: true,
                updateOnImageLoad: true
            },
            scrollInertia: 500,
            scrollbarPosition: scrollbarPosition,
            callbacks: {
                //onTotalScroll: function() { mCustomScrollbar_onTotalScroll(this); }
                onScroll: function() { if (this.mcs.topPct > 70) { mCustomScrollbar_onTotalScroll(this); } }
            }
        });
    }
}

function mCustomScrollbar_onTotalScroll(el) {
    var callback = $(el).attr('callback');
    if (callback != undefined && callback != '')
        window[callback]();
}

function dragAndDrop_dynamicHeight() {
    $('.dragAndDrop.dynamicHeight').each(function() {
        var imgH = $(this).find('.imgContainer').width();
        $(this).find('.imgContainer img').height(imgH);
    });
}


function SumoSelect_init(params = []) {
    var defaults = {
        'element': '.SumoSelect'
    }
    var options = $.extend(defaults, params);
    $(options.element).each(function() {
        var placeholder = $(this).attr('placeholder_text');
        $(this).SumoSelect({ csvDispCount: 2, placeholder: placeholder });
    });
}


function autocomplete_init(params = []) {
    var defaults = {
        'element': '.autocomplete_field',
        'hidden_element': '.autocomplete_field_hidden'
    }
    var options = $.extend(defaults, params);
    $(options.element).each(function(i, v) {
        /*$(document).on('change', options.element, function(){
          $(this).parent().find(options.hidden_element).val('');
        });*/
        var callback = $(v).attr('callback');
        var exception = $(v).attr('exception');
        var select_callback = $(v).attr('select_callback');
        var that = this;
        $(that).autocomplete({
            minLength: 2,
            source: function(request, response) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: {
                        action: callback,
                        exception: exception,
                        search: request.term,
                        _token: prop.csrf_token
                    },
                    success: function(data) {
                        $(that).parent().find(options.hidden_element).val('');
                        response($.map(data.data.data, function(item) {
                            var meta_data = {};
                            $.each(item, function(x, y) {
                                if (x != 'title' && x != 'value')
                                    meta_data[x] = y;
                            });
                            return {
                                label: item.title,
                                value: item.value,
                                meta_data: meta_data
                            }
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $(that).val(ui.item.label);
                $(that).parent().find(options.hidden_element).val(ui.item.value);
                if (typeof select_callback != 'undefined' && select_callback != '')
                    window[select_callback](that, ui.item);
                return false;
            }
        });
    });
}


function initMap() {
    $('.google_map').each(function() {
        var callback = $(this).attr('callback');
        if (callback != undefined && callback != '')
            window[callback]();
    });
}


function displayChart(params) {
    var chart = new CanvasJS.Chart(params.id, {
        animationEnabled: true,

        title: {
            //text:"Fortune 500 Companies by Country"
        },
        axisX: {
            interval: 1,
            titleFontFamily: "Arial, Helvetica, sans-serif",
            labelFontSize: 15
        },
        axisY2: {
            interlacedColor: "rgba(1,77,101,.05)",
            gridColor: "rgba(1,77,101,.1)",
            title: params.top_heading,
            titleFontFamily: "Arial, Helvetica, sans-serif",
            titleFontSize: 20,
            labelFontSize: 15,
            /*valueFormatString: '#'*/
        },
        data: [{
            type: "bar",
            name: "companies",
            axisYType: "secondary",
            indexLabelFontSize: 13,
            //color: "#014D65",
            //ShowInLegend: true,
            //indexLabel: "{x}, {y}",
            //indexLabelPlacement: "outside",
            indexLabelPlacement: "inside",
            //indexLabel: "{label}: {y}",
            indexLabel: "{y}",
            /*dataPoints111: [
              { y: 87, label: "Taiwan" },
              { y: 105, label: "Russia" },
              { y: 91, label: "Spain" },
              { y: 67, label: "Brazil" },
              { y: 157, label: "India" },
              { y: 209, label: "Italy" },
              { y: 300, label: "Sweden", color: "#fc0000" },
              { y: 134, label: "US" },
              { y: 118, label: "Australia lorem ipsum sit dolor amet lorem ipsum sit dolor" },
              { y: 110, label: "Canada" }
            ],*/
            dataPoints: params.dataPoints
        }]
    });
    chart.render();

    $(document).on('changingwidth', '.areaPannel', function() {
        setTimeout(function() { chart.render(); }, 500);
    });

    //chart.set("dataPointWidth",Math.ceil(chart.axisX[0].bounds.width/chart.data[0].dataPoints.length),true);

    //chart.set("dataPointWidth",Math.ceil(chart.axisX[0].bounds.width/(chart.data[0].dataPoints.length * 2)),true);

}



function render_chart(params) {
    var dataPoints = [];
    //var colorset = ['#ff4560', '#008ffb', '#feb019', '#00e396', '#ff498c', '#00ffff', '#ff8d00', '#5eda00', '#ff5f5f', '#9790ff'];
    var colorset = ['#ce1430', '#1444c7', '#fff742', '#a2c833', '#562514', '#c43973', '#6eece8', '#deb74a', '#52b42e', '#db7c87', '#ff49f7', '#6b18b2', '#fd9900', '#46a100', '#e5c58a', '#fd00ff', '#1415cd', '#fd9900', '#58492c', '#cb4c3a', '#feff51', '#497c3e', '#bfbdbd', '#ffe69a', '#566b4c', '#5e5e5e', '#c9ff00', '#b0e4ea', '#006837', '#9cbf9d'];
    $(params.data).each(function(i, v) {
        dataPoints.push({ y: parseInt(v.value), label: v.title, color: colorset[i] });
    });
    if (dataPoints.length > 0) {
        dataPoints.reverse();
        displayChart({ 'id': params.id, 'top_heading': params.top_heading, 'dataPoints': dataPoints });
    } else {
        $('#' + params.id).html('<h3 class="text-center" style="padding-top: 100px;">No data found</h3>');
    }
}


/*function update_sortable_position(params) {
  var type = params.type;
  var data = params.data;

}*/

/*function dashBoard_dbleft_userArea_h() {
  var window_h = $(window).height();
  var dashBoard_dbleft_userArea_h = $(".dashBoard .dbleft .userArea").outerHeight();
  var dashBoard_dbleft_menuarea_h = window_h - dashBoard_dbleft_userArea_h - 98 - 20;
  $('.dashBoard .dbleft .menuarea').css('max-height', dashBoard_dbleft_menuarea_h + 'px');
  $(".dashBoard .dbleft .menuarea").mCustomScrollbar('destroy');
  $(".dashBoard .dbleft .menuarea").mCustomScrollbar({
    theme:"minimal"
  });
}*/

function dashBoard_userArea_h() {
    var window_h = $(window).height();
    var hd_h = $("header").outerHeight();
    var ft_h = $("footer").outerHeight();
    var dbRtcontent_ht = window_h - hd_h - ft_h - 40;
    $('.dbRtcontent').css('min-height', dbRtcontent_ht + 'px');
}

function textareaAutoHeight() {
    $('textarea.autoheight').each(function() {
        $(this).on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
}


function select2_ajax(params) {
    $(params.element).select2({
        maximumSelectionLength: parseInt($(params.element).attr('max_selection')),
        minimumInputLength: parseInt($(params.element).attr('min_input')),
        ajax: {
            url: prop.ajaxgeturl,
            dataType: 'json',
            data: function(params1) {
                var query = {
                    search: params1.term,
                    cur_page: params1.cur_page || 1,
                    per_page: parseInt($(params.element).attr('per_page')),
                    action: $(params.element).attr('action')
                }
                return query;
            },
            processResults: function(data, params1) {
                params1.cur_page = params1.page || 1;
                //console.log(params);
                return {
                    results: data.results,
                    pagination: {
                        more: (params1.cur_page * parseInt($(params.element).attr('per_page'))) < data.total_data
                    }
                };
            }
        }
    });
    if (typeof $(params.element).attr('sortable_container') != 'undefined') {
        $($(params.element).attr('sortable_container') + " ul.select2-selection__rendered").sortable({
            containment: 'parent'
        });
    }
}


function emoji_pad() {
    $('.emoji_pad').each(function() {
        var container = $(this).find('.emoji_container').attr('id');
        var placeholder = $(this).find('textarea').attr('placeholder');
        var save_action = $(this).attr('save_action');
        var save_params = $(this).attr('save_params');
        var el = $(this).find('textarea').emojioneArea({
            container: "#" + container,
            hideSource: false,
            useSprite: true,
            autocomplete: false,
            saveEmojisAs: 'image',
            placeholder: placeholder
        });
        el[0].emojioneArea.on("keydown", function(editor, event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                //console.log(el[0].emojioneArea.getText());
                var text = el[0].emojioneArea.getText();
                el[0].emojioneArea.setText('');
                window[save_action](text, save_params);
            }
        });
        $(this).find('.emoji_submit').unbind('click').on('click', function() {
            console.log("submit");
            //console.log(el[0].emojioneArea.getText());
            var text = el[0].emojioneArea.getText();
            el[0].emojioneArea.setText('');
            window[save_action](text, save_params);
        });
    });

}

function passwordStrengthChecker(password) {
    /*
    aaaaA1@111 = strong
    aaaaA1@ = medium
    aaaa = weak
    */
    let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})');
    let mediumPassword = new RegExp('((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))');
    var data = { 'type': '' };
    if (strongPassword.test(password)) {
        data.type = 'strong';
    } else if (mediumPassword.test(password)) {
        data.type = 'medium';
    } else {
        data.type = 'weak';
    }
    return data;
}






$(window).resize(function() {
    //dashBoard_dbleft_userArea_h();
    //dashBoard_userArea_h();
});

$(document).ready(function() {
    $(".emoji-area").emojioneArea();
});