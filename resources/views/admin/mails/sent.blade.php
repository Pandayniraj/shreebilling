@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
<style>
	.mail-body { padding:20px; font-size:15px; background:#CCC; border:2px solid #000 !important; }
	.pad-10 { padding-bottom:10px; }
	.mail-body .lbl { width:65px; display:inline-block; }
	.mail-body .header { border-bottom:dashed; margin-bottom:10px; }
	.loading { margin-left:23px; font-weight:bold; font-size:15px; color:#090 }
	.close-msg { float:right; }
	.close-msg:hover { cursor:pointer; color:#F00; }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
            <span class="material-icons">inbox </span> Meronetwork Sent Mails
                <small>{!! $page_descriptions !!}</small>
            </h1>
            <p>You may need to enable 2FA and use app passwords to connect to your IMAP account <a target="_blank" href="https://support.google.com/mail/answer/185833">Gmail Guidelines</a>  | <a target="_blank" href="https://help.zoho.com/portal/en/kb/bigin/channels/email/articles/generate-an-app-specific-password">Zoho Guidelines</a>

            </p>
           
        </section>

    <div class='row'>
        	 <div class="col-md-12">
        	 &nbsp; <a class="btn btn-info" href="/admin/mail/inbox">Inbox</a><br/><br/>
        </div>


        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border bg-success">
              <h3 class="box-title">Sent Mails</h3>

              <!--<div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" placeholder="Search Mail" class="form-control input-sm">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>-->
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="table-responsive mailbox-messages">
              	{!! csrf_field() !!}
                <table class="table table-hover table-striped" id="mail-list">
                 <tbody>

                  		<!-- if there is the cache of the inbox_messages, bypass the zoho mail connection and show the inbox messages stored in cache -->
                  		@if(!\Cache::has('sent_size'.\Auth::user()->id))

                        	<?php $flag = 0; $toCache = []; ?>
                            @foreach ($messages as $message) 
                     		
                             <tr id="mail-{{ $message->getNumber() }}">
                                <td><input type="checkbox" style="position: absolute; opacity: 0;"></td>
                                <!--@if($message->isSeen())
                                    <td class="mailbox-star"><a href="#">{!! $message->isSeen() !!}<i class="fa fa-star text-yellow"></i></a></td>
                                @else
                                    <td class="mailbox-star"><a href="#"><i class="fa fa-star-0 text-yellow"></i></a></td>
                                @endif-->
                                <td style="font-weight: bold;">{{ mb_substr($message->getFrom()->getAddress(),0,20)}}</td>
                               
                                <td style="font-size: 16.5px" class="mailbox-subject">{!! mb_substr($message->getSubject(),0,50) !!}</td>
                                <td class="mailbox-attachment">
                                    @if($message->getAttachments()) 
                                        <i class="fa fa-paperclip"></i>
                                    @endif
                                </td>
                                <td class="mailbox-date">{!! \Carbon\Carbon::createFromTimeStamp(strtotime($message->getDate()->format('Y-m-d H:i:s')))->diffForHumans()!!}</td>

                              </tr>

                              <?php 
                              
                             $toCache [] =  ['number'=>$message->getNumber(), 'name'=>$message->getFrom(), 'subject'=>$message->getSubject(), 'attachment'=>$message->getAttachments(), 'date'=> \Carbon\Carbon::createFromTimeStamp(strtotime($message->getDate()->format('Y-m-d H:i:s')))->diffForHumans()];  
                     
                              $flag++; 
                              
                              ?>
                            @endforeach
                            <?php \Cache::put('sent_size'.\Auth::user()->id, $toCache, 1200);?>
                         @else

                         	<?php
                         		//dd( \Cache::get('inbox_size'.\Auth::user()->id));
								foreach(\Cache::get('sent_size'.\Auth::user()->id) as $message)
								{
					
								//$message = \Cache::get('inbox_messages'.\Auth::user()->id.'['.$i.']');
									
									echo '<tr id="mail-'.$message['number'].'">
											<td><input type="checkbox" style="position: absolute; opacity: 0; font-weight:bold"></td>
											<td></td>
											<td class="mailbox-name"><a href="#">'.mb_substr($message['name']->getAddress(),0,20).'</a></td>
											<td style="font-size:16.5px" class="mailbox-subject">'.mb_substr($message['subject'],0,50).'</td>
											<td class="mailbox-attachment">';
												if(sizeof($message['attachment']) > 0)
												{ 
													echo '<i class="fa fa-paperclip"></i>';
												}
									 echo '</td>
											<td class="mailbox-date">'.$message['date'].'</td>
										  </tr>';
								}
							?>
                        @endif
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
          </div>
          <!-- /. box -->
        </div>
        
    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')\
    <script>
		<!-- To show the detail of the mail -->
		$(document).on('click', '#mail-list tr', function() {
			var token =  $('meta[name="csrf-token"]').attr('content');
			var mailid = $(this).attr('id').split('-')[1];
			// Load the related mail detail			
			if(mailid != 'mail')
			{			
				$('#show-mail').remove();
				var thisrow = $("tr").index(this) + 1;
				$('#mail-list > tbody > tr:nth-child(' + thisrow + ')').after('<tr id="show-mail"><td colspan="6"><div class="loading">Loading...</div></td></tr>');
				var datastring = '_token='+token+'&mailid='+mailid;
				$.ajax({
					url: '/admin/mail/showsent/'+mailid,
					dataType: 'JSON',
					type: 'get',
					contentType: 'application/x-www-form-urlencoded',
					data: datastring,
					success: function(data){
					   document.getElementById('show-mail').innerHTML = data.messages;
					},
					error: function( jqXhr, textStatus, errorThrown ){
						console.log( errorThrown );
					}
				});
			}
		});
		
		<!-- To reply the mail -->
		$(document).on('click', '#reply_btn', function() {
			var token =  $('meta[name="csrf-token"]').attr('content');
			var from = '{!! \Auth::user()->email !!}';
			var to = $('#email_from').html();
			var subject = 'Re: '+$('#email_subject').html();
			var msg = $('#reply_msg').val();
			// Load the related mail detail			
			if(from != '')
			{			
				$('#reply_noti').html('Sending...');
				var datastring = '_token='+token+'&from='+from+'&to='+to+'&msg='+msg+'&subject='+subject;
				$.ajax({
					url: '/admin/mail/reply',
					dataType: 'JSON',
					type: 'post',
					contentType: 'application/x-www-form-urlencoded',
					data: datastring,
					success: function(data){
					   document.getElementById('reply_msg').val = '';
					   $('#reply_noti').html(data.messages);
					},
					error: function( jqXhr, textStatus, errorThrown ){
						console.log( errorThrown );
					}
				});
			}
		});
		
		<!-- Close the message box -->
		$(document).on('click', '.close-msg', function() {
			$('#show-mail').remove();
		});
		
	</script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}"></script>
	<script>
      $(function () {
        //Add text editor
        $("#reply_msg").wysihtml5();
      });
    </script>
@endsection
