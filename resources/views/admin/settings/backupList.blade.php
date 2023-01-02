@extends('layouts.master')
@section('content')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Backup Database
                <small>lists Of Database Backup</small>

                
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">

          <div class="box box-default">
            <div class="box-body">
              <div class="row">
                <div class="col-md-9">  
                 <div class="top-bar-title padding-bottom">DB Backup</div>
                </div> 
                <div class="col-md-3">
              
                    <a href="{{url('/admin/backup')}}" class="btn btn-block btn-default btn-flat btn-border-orange" id="backupid"><span class="fa fa-plus"> &nbsp;</span>New Backup</a>
                
                </div>
              </div>
            </div>
          </div>

          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th width="5%">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 0;?>
                @foreach ($backupData as $data)
                <tr>
                  <td>{{ ++$i }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->created_at }}</td>
                  <td>
              
               
                   <a title="Download" href="/admin/download/backup/{{$data->name}}"  class="btn btn-xs btn-info edit_unit" id="{{$data->id}}" ><span class="fa fa-download"></span></a> &nbsp;     
            
               
                 <form method="POST" action="{{ url("/admin/backup/delete/$data->id") }}" accept-charset="UTF-8" style="display:inline">
                    {!! csrf_field() !!}
                    <button class="btn btn-xs btn-danger" type="submit" data-toggle="modal" data-target="#confirmDelete" data-title="Backup Delete" data-message="Are you sure to delete this backup ?">
                       <i class="fa fa-remove" aria-hidden="true"></i>
                    </button>
                </form> 
              
                  </td>
                </tr>
               @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>


@endsection

@section('js')
    <script type="text/javascript">
      $(function () {
        $("#example1").DataTable({
          "order": [],
          "columnDefs": [ {
            "targets": 3,
            "orderable": false
            } ],
            
            "language": '{{Session::get('dflt_lang')}}',
            "pageLength": '{{Session::get('row_per_page')}}'
        });
        

        $("#backupids").on('click', function(){
          alert("This option is not available on demo version");
          return false;
        });

      });
    </script>
@endsection