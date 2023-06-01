{{-- @dd('hi') --}}
<div class="d-flex flex-wrap align-items-center justify-content-between ticket-heading-wrap">
    <h3>Ticket List</h3> 
    <button class="dashboard-btn" type="button" data-toggle="modal" data-target="#myModal">Add New Ticket</button>
</div>

<div class="table-responsive">
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Ticket Id</th>
                <th>Support Title</th>
                <th>Issue</th>
                <th>Created by</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($meta_data['tickets'] as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->auto_gen_id}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->created_by_dtl->full_name}}</td>
                    <td>{{date('d/m/Y H:i:s',strtotime($item->created_at))}}</td>
                    <td>{{$item->status==0?'Closed':'Open'}}</td>
                    <td>
                        <ul class="ticket-view-btn-wrap">
                            <li><a class="ticket-view-btn" href="{{url('dashboard/ticket_chat?id='.encrypt($item->id))}}"><i class="fas fa-eye"></i></a></li>
                        </ul>
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
</div>

<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add New Ticket</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="{{url('dashboard/add_ticket')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Support Title :</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Issue :</label>
                    <input type="text" name="description" id="description" class="form-control" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">How can we help :</label>
                    <input type="text" name="how_to_help" id="how_to_help" class="form-control" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Email :</label>
                    <input type="text" name="contacts" id="contacts" class="form-control" required>
                </div>
            </div>
            <div class="col-sm-12">
                <button class="dashboard-btn" type="submit">Submit</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>