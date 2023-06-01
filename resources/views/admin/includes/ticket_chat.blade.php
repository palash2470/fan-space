{{-- @dd($meta_data['ticket_dtl']->created_by_dtl->user_meta_array()) --}}
<div class="mv_loader"><i class="fa fa-spinner fa-spin"></i></div>
    <style type="text/css">
        .mv_loader {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            text-align: center;
            z-index: 9999;
        }

        .mv_loader i {
            font-size: 80px;
            color: #350015;
            margin-top: 200px;
        }
    </style>
<section class="ticket-chat-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                <div class="ticket-wrap">
                    <div class="ticket-wrap-box">
                            @if ($meta_data['ticket_dtl']->status==0)
                                <h3 class="w-100">Ticket details <span class="pull-right"><button type="button" class="btn btn-xs btn-danger">closed</button></span></h3>
                            @else
                                <h3 class="w-100">Ticket details <span class="pull-right"><button type="button" class="btn btn-xs btn-success">open</button></span></h3>
                            @endif

                            <div class="ticket-open-type">
                                <h4>{{ $meta_data['ticket_dtl']->title }} </h4>
                                <p><strong>Issue</strong></p>
                                <p data-scrollbar>{{ $meta_data['ticket_dtl']->description }}</p>
                                <p><strong>How Can We Help : </strong>{{ $meta_data['ticket_dtl']->how_to_help }}</p>
                                <p> <strong>Contact : </strong> {{ $meta_data['ticket_dtl']->contacts }}</p>
                                
                            </div>
                        <div class="ticket-open-time">
                            <ul>
                                <li>
                                    <h4>Ticket date</h4>
                                    <p>{{ date('d/m/Y H:i:s', strtotime($meta_data['ticket_dtl']->created_at)) }}</p>
                                </li>
                                <li>
                                    <h4>Ticket id</h4>
                                    <p>{{ $meta_data['ticket_dtl']->auto_gen_id }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ticket-wrap-box">
                        <h3>Ticket created by</h3>
                        <div class="ticket-opner d-flex flex-wrap align-items-center">
                            <div class="ticket-opner-img">
                                <span>
                                    @if (!empty($meta_data['ticket_dtl']->created_by_dtl->user_meta_array()['profile_photo']))
                                        <img src="{{ url('public/uploads/profile_photo/' . $meta_data['ticket_dtl']->created_by_dtl->user_meta_array()['profile_photo']) }}">
                                    @else
                                        <img src="{{ url('public/uploads/profile_photo/user-placeholder.jpg') }}">
                                    @endif

                                </span>
                            </div>
                            <div class="ticket-opener-name d-flex flex-wrap align-items-center justify-content-between">
                                <div class="lft">
                                    <h5>{{ $meta_data['ticket_dtl']->created_by_dtl->full_name }}</h5>
                                    <p>{{ $meta_data['ticket_dtl']->created_by_dtl->email }}</p>
                                </div>
                                <div class="ticket-opener-phn">
                                    <p>{{ $meta_data['ticket_dtl']->created_by_dtl->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($meta_data['ticket_dtl']->status==1)
                    <div class="ticket-wrap-box">
                        <h3>Ticket action</h3>
                        <div class="ticket-close-btn-wrap">
                            <button type="button" class="ticket-close-btn" id="close_ticket">close the ticket</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                <div class="ticket-chat">
                    @if (\Auth::user()->role==1)
                        <div class="ticket-chat-head">
                            <div class="d-flex align-items-center">
                                <span class="ticket-chat-user">
                                    @if (!empty($meta_data['ticket_dtl']->created_by_dtl->user_meta_array()['profile_photo']))
                                        <img src="{{ url('public/uploads/profile_photo/' . $meta_data['ticket_dtl']->created_by_dtl->user_meta_array()['profile_photo']) }}">
                                    @else
                                        <img src="{{ url('public/uploads/profile_photo/user-placeholder.jpg') }}">
                                    @endif
                                </span>
                                <p>{{ $meta_data['ticket_dtl']->created_by_dtl->full_name }}</p>
                            </div>
                        </div>
                    @else
                        <div class="ticket-chat-head">
                            <div class="d-flex align-items-center">
                                <span class="ticket-chat-user">
                                    <img src="{{ url('public/uploads/profile_photo/user-placeholder.jpg') }}">
                                </span>
                                <p>Admin</p>
                            </div>
                        </div>
                    @endif

                    {{-- data-scrollbar --}}
                    <div class="ticket-msg-wrap ticket-msg-scrollbar" id="ticket_msg_scrollbar">
                        <div class="ticket-msg-history">
                            <div class="ticket-messages">
                                @foreach ($meta_data['ticket_dtl']->ticket_chat as $item)
                                    @if (\Auth::user()->id == $item->created_by)
                                        <div class="ticket-outgoing-msg d-flex justify-content-end mb-2">
                                            <div class="ticket-sent-msg">
                                                <p>{{ $item->message }}</p>
                                                <span class="ticket-time-date"> {{ date('h:i a',strtotime($item->created_at)) }} | {{ date('F j, Y',strtotime($item->created_at)) }}</span>
                                            </div>
                                            <div class="ticket-user-img-wrap">
                                                <span class="ticket-user-img">
                                                    @if (!empty($item->created_by_dtl->user_meta_array()['profile_photo']))
                                                        <img src="{{ url('public/uploads/profile_photo/' . $item->created_by_dtl->user_meta_array()['profile_photo']) }}">
                                                    @else
                                                        <img src="{{ url('public/uploads/profile_photo/user-placeholder.jpg') }}">
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="ticket-incoming-msg d-flex justify-content-start mb-2">
                                            <div class="ticket-user-img-wrap">
                                                <span class="ticket-user-img">
                                                    @if (!empty($item->created_by_dtl->user_meta_array()['profile_photo']))
                                                        <img src="{{ url('public/uploads/profile_photo/' . $item->created_by_dtl->user_meta_array()['profile_photo']) }}">
                                                    @else
                                                        <img src="{{ url('public/uploads/profile_photo/user-placeholder.jpg') }}">
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="ticket-received-msg">
                                                <p>{{ $item->message }} </p>
                                                <span class="ticket-time-date"> {{ date('h:i a',strtotime($item->created_at)) }} | {{ date('F j, Y',strtotime($item->created_at)) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                    @if ($meta_data['ticket_dtl']->status==1)
                    <form action="{{ url('admin/send_ticket_message') }}" method="POST" >
                        @csrf
                        <div class="ticket-message-input">
                            <div class="ticket-input-msg-write">
                                <textarea type="text" class="ticket-write-msg form-control" placeholder="Type a message" name="ticket_message" id="ticket_message"></textarea>
                                <input type="hidden" name="ticket_id" value="{{ $meta_data['ticket_dtl']->id }}">
                                <button class="ticket-msg-send-btn send-message" type="submit" disabled><i class="fa fa-paper-plane"></i></button>

                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script>
        // alert();
        $(document).ready(function() {

            // $(".mv_loader").hide();
            // alert();
            setTimeout(() => {
                $(".mv_loader").hide();
            }, 500);
            $('.ticket-msg-scrollbar').scrollTop($('.ticket-msg-scrollbar').height());

            // $(document).on('click','.send-message',function(){
            //     if($('#ticket_message').val()!=''){

            //     }
            // });
            $('#ticket_message').keyup(function(){
                if($(this).val()!=''){
                    $('.send-message').attr('disabled',false);
                }else{
                    $('.send-message').attr('disabled',true);
                }
            });

            $('#close_ticket').click(function(){
                if (confirm("Are you sure want to close the ticket?") == true) {
                    //console.log('dsadas');
                    $.ajax({
                        type: 'POST',
                        url: "{{url('admin/close_ticket')}}",
                        dataType: 'json',
                        data:{'_token':prop.csrf_token,'id':"{{ $meta_data['ticket_dtl']->id }}"},
                        success: function(result){
                            //console.log(result);
                            if(result.success){
                                alert('Ticket Closed !');
                                location.reload();
                            }
                        }
                    });
                } 
            });

        });
    </script>
@endpush
