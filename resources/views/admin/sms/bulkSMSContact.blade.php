@extends('layouts.master')



@section('content')



<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{$page_title ?? "Page Title"}}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body">
                {!! Form::open( ['route' => 'admin.post-send-bulk-sms-contact', 'id' => 'form_edit_lead'] ) !!}        
                <div class="content col-md-9">
                    <div class="row">
                   

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client_id">Groups</label>
                                <select name="group_id" id="group_id" class="form-control">
                                    <option value="0">Select any Group</option>
                                    @foreach($groups as $ck => $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select name="client_id" id="select_clients" class="form-control">
                                </select>
                            </div>
                        </div> 

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client_id">Contacts</label>
                                <select name="contact_id" id="select_contacts" class="form-control">
                                </select>
                            </div>
                        </div> 
                        
                     <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('start_date', \Request::get('start'), ['class' => 'form-control', 'id'=>'start_date', 'placeholder'=>"Start Date"]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom:5px;">
                                {!! Form::text('end_date', \Request::get('end'), ['class' => 'form-control', 'id'=>'end_date', 'placeholder'=>"End Date"]) !!}
                            </div>                    
                        </div> 
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Note: Maximum 138 character limit</span><br>
                                <!-- <textarea rows="3" name="message" class="form-control" id="message-textarea" onblur="countChar(this)" placeholder="Type your message." maxlength="138"></textarea> -->
                                <textarea rows="3" name="message" class="form-control" id="message-textarea" placeholder="Type your message."></textarea>
                                <!-- <span class="char-cnt">138 characters remaining</span>    -->     
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit('Send SMS', ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                            </div>
                        </div>
                    </div>                         
                </div>
                {!! Form::close() !!}
                 <div class="col-md-3">
                       <div class="callout callout-maroon contacts_status">
                          <h4>Contact Mail Stat!</h4>
                        <p>
                            Total valid emails: <strong><span id="emails_count">{{ $valid_addresses }}</span></strong><br/>
                            Total Contacts: <strong><span id="contacts_count">{{ $total_leads }} </strong>
                        </p>
                    </div> 
                           

                    <div class="callout bg-maroon">
                     <h4>Contacts!</h4>

                      <p>Contacts comes from Customers or Suppliers. When you add a customer contact it appears here, or you can add contact here <a href="/admin/contacts"> Add contact + </a></p>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection

@section('body_bottom')
 <!-- <style>
    select { width:200px !important; }
</style> -->

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>


<script>
$(function() {
    $('#start_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });
    $('#end_date').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });
});

</script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $('#group_id').on('change', function () {
                var ID = $(this).val();
                if (ID) {
                    $.ajax({
                        url:"{{ route('admin.group_select_clients') }}",
                        data: {
                        ID: ID,
                        "_token": "{{ csrf_token() }}"
                            },
                        type: "POST",
                        dataType: "json",
                        success: function (data) {
                            console.log(data.clients);
                            console.log(data.contacts_count);
                            $('#select_clients').empty();
                            $('#contacts_count').empty();
                            $('#emails_count').empty();
                            $('.contacts_status').show();
                            $('#emails_count').html(data.emails_count);
                            $('#contacts_count').html(data.contacts_count);
                            $('#select_clients').append('<option value="">Select Clients</option>');
                            $.each(data.clients, function (key, value) {
                                $('#select_clients').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }else{
                     $('#select_clients').empty();
                     $('#select_contacts').empty();
                }
            });

             $('#select_clients').on('change', function () {
                var ID = $(this).val();
                if (ID) {
                    $.ajax({
                        url:"{{ route('admin.client_select_contacts') }}",
                        data: {
                        ID: ID,
                        "_token": "{{ csrf_token() }}"
                            },
                        type: "POST",
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            $('#select_contacts').empty();
                            $('#contacts_count').empty();
                            $('#emails_count').empty();
                            $('.contacts_status').show();
                            $('#emails_count').html(data.emails_count);
                            $('#contacts_count').html(data.contacts_count);
                            $('#select_contacts').append('<option value="">Select Clients</option>');
                            $.each(data.contacts, function (key, value) {
                                $('#select_contacts').append('<option value="' + value.id + '">' + value.full_name + '</option>');
                            });
                        }
                    });
                }else{
                     $('.contacts_status').show();
                       $('#select_contacts').empty();
                }
            });

            $('#select_contacts').on('change', function () {
                var ID = $(this).val();
                if (ID) {
                     $('.contacts_status').hide();
                }else{
                    $('.contacts_status').show();
                }
            });  
    });
</script>

@endsection