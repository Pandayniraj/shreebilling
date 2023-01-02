@extends('layouts.master')

@section('content')

<style type="text/css">
    [data-letters]:before {
    content:attr(data-letters);
    display:inline-block;
    font-size:1.1em;
    width:2.2em;
    height:2.0em;
    line-height:1.8em;
    text-align:center;
    border-radius:50%;
    background:red;
    vertical-align:middle;
    margin-right:0.3em;
    color:white;
    }
</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Employee Directory
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
           {{ TaskHelper::topSubMenu('topsubmenu.hr')}}

            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12'>
            <!-- Box -->
            {!! Form::open( array('route' => 'admin.users.enable-selected', 'id' => 'frmUserList') ) !!}
                <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Directory</h3>
                    &nbsp;
                   

                          
                   
                    <div class="col-md-4 col-sm-4 col-lg-4" style="float: right;margin-top: 4px">  
                                  
                    <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search users" name="search" id="terms" value="{{\Request::get('term')}}">
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="search"><i class="fa fa-search"></i>&nbsp;Filter</button>
                    </div>
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-danger" id="clear"><i class="fa fa-close (alias)"></i>&nbsp; Clear</button>
                    </div>
                      


                    </div>
                        </div>

                        
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-info">
                                   
                                    <th></th>
                                    <th>Name</th>
                                    <th> Org</th>
                                    <th>Project </th>
                                    <th>Department</th>
                                    <th>Present Address</th>
                                    <th>Nationality</th>
                                    <th> Mobile</th>
                                    <th>Gender</th>
                                    <th>Work Phone</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                   
                                    <th></th>
                                    <th>Name</th>
                                    <th> Org</th>
                                    <th>Project </th>
                                    <th>Department</th>
                                    <th>Present Address</th>
                                    <th>Nationality</th>
                                    <th> Mobile</th>
                                    <th>Gender</th>
                                    <th>Work Phone</th>
                                    
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($users as $user)
                                   
                                @endforeach
                            </tbody>
                        </table>
                        {!! $users->render() !!}
                    </div> <!-- table-responsive -->

                </div><!-- /.box-body -->
            </div><!-- /.box -->
            {!! Form::close() !!}
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection


            <!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <script language="JavaScript">
        function toggleCheckbox() {
            checkboxes = document.getElementsByName('chkUser[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = !checkboxes[i].checked;
            }
        }
$('#search').click(function(){
            let terms = $('#terms').val();
           window.location.href = "{!! url('/') !!}/admin/employee/directory?term="+terms;
        });
        $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      let terms = $('#terms').val();
      window.location.href = "{!! url('/') !!}/admin/users?term="+terms;
      return false;
    }
  });
});
           $('#clear').click(function(){
              window.location.href = "{!! url('/') !!}/admin/users";
        })
    </script>
@endsection
