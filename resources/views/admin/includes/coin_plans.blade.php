<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Coin Plans <a href="{{ url('/admin/coin_plans?form') }}" class="btn btn-primary pull-right">Add New</a>
        <!-- <small>it all starts here</small> -->
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>-->
    </section>


    <!-- Main content -->
    <section class="content">

      @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
      @endif

      <?php
      if(isset($_GET['form'])) { ?>
        @include('admin.includes.coin_plans.form')
      <?php } else { ?>
        @include('admin.includes.coin_plans.list')
      <?php } ?>


      

    </section>
    <!-- /.content -->


@push('scripts')

<script type="text/javascript">
  
</script>

@endpush