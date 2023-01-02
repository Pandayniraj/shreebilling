@extends('layouts.master')
@section('content')

<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<div class="callout callout-danger">
          <h4>Queues!</h4>

          <?php ?>

          <p>{{ \DB::table('jobs')->count('id') }} mails are in in queues </p>
        </div><br/>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
               {{ $page_title}}
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>

    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.contacts.enable-selected', 'id' => 'frmContactList') ) !!}
                <div class="box box-primary">
                    
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="contacts-table table-striped">
                                <thead>
                                    <tr class="bg bg-green">
                                        <th>Campaign Title</th>
                                        <th>Subject</th>
                                        <th>message</th>
                                        <th>Product</th>
                                      
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Campaign Title</th>
                                        <th>Subject</th>
                                        <th>message</th>
                                        <th>Product</th>
                                      
                                        <th>Date</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @if(isset($mails) && !empty($mails))
                                    @foreach($mails as $m)
                                        <tr>
                                            <td style="font-size: 16px"><a href="#">{!! mb_substr($m->title,0,40) !!}</a></td>
                                            <td>{!! $m->subject !!}</td>
                                            <td>{{ strip_tags(mb_substr($m->message,0,90))  }}</td>
                                            <td>{!! $m->course->name !!}</td>
                                          
                                            <td>{!! date('d M y', strtotime($m->created_at)) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                          
                        </div> <!-- table-responsive -->

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection
@endif

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
<!-- DataTables -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkContact[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
    </script>

    <script>
    $(function() {
        $('#contacts-table').DataTable({
            pageLength: 35
        });
    });
    </script>

@endsection
