<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Payouts
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
        @include('admin.includes.payouts.form')
      <?php } else { ?>
        @include('admin.includes.payouts.list')
      <?php } ?>


      

    </section>
    <!-- /.content -->


@push('scripts')

<script type="text/javascript">
  
</script>

@endpush