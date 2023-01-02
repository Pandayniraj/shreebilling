<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Ticket Form::</title>
    <meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="{{ asset ("/img/favicon.png") }}" rel="icon">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Application CSS-->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />    
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body{
            background-color: #ECF0F5;
        }
        li.active a{
            color: black !important;
        }
        li a{
            color: #4F8FCB;
        }
        .nav-tabs li{
            
        }
        .nav-tab{
            padding: 2px;
        }
        @media only screen and (max-width: 600px) {
          .logo{
            display: none;
          }
        }
    </style>
  </head>

  <!-- Body -->
  <body>
  	
    <div class="container">
       <div class="row">
        <div class="col-md-12">
            <div class="panel with-nav-tabs">
                <div class="panel-heading">

         <h1 style="font-weight: 1000;line-height: 18px;">SUPPORT CENTER<br>
           <span style="float: right;"><img src="https://www.customers.meronetwork.com/org/1600240888POS.png" alt="logo" class="logo" style=" height: 37px;margin-top: -50px;"></span>
            <small style="font-size: 14px;margin-top: -30px;margin-left: 180px;font-weight: 600">Support Ticket System</small>

        </h1>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab1primary" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-home"></i> Support Center Home</a>
                        </li>
                        <li class="second" >
                            <a href="#tab2primary" data-toggle="tab" aria-expanded="false ">
                                <i class="fa fa-file-code-o"></i> Open a New Ticket</a>
                        </li>
                        
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1primary">
                            
                            <div class="row">
                                <div class="col-md-9 col-sm-9">
                                    <h2>Welcome to the Support Center</h2>
                                    <p>
                                        In order to streamline support requests and better serve you, we utilize a support ticket system. Every support request is assigned a unique ticket number which you can use to track the progress and responses online. For your reference we provide complete archives and history of all your support requests. A valid email address is required to submit a ticket.
                                    </p>
                                </div>
                                <div class="col-sm-3 col-md-3">
                                    <a  class="btn btn-info btn-block" onclick="openTab('tab2primary')">Open a New Ticket</a>
                                    <br>
                                    
                                </div>

                            </div>



                        </div>
                        <div class="tab-pane fade" id="tab2primary">
                            <h3 class="text-info">Open a New Ticket</h3>
                            <p>Please fill in the form below to open a new ticket.</p>
                            <hr>
                            <form action="/post_ticket" method="post">
                                {{ csrf_field() }}
                            <h4>Contact Information</h4>
                              <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" required="" placeholder="Enter the valid email address" name="from_email">
                              </div>
                              <div class="form-group">
                                <label for="pwd">Full Name:</label>
                                <input type="text" class="form-control" placeholder="Full Name.." required="" name="from_user">
                              </div>
                             <div class="form-group">
                                <label for="pwd">Phone Number:</label>
                                <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" placeholder="Phone Number" required="" name="from_phone">
                                </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                    <label class="control-label col-sm-2" for="pwd">Ext:</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control"  placeholder="Ext." required="" name="from_ext">
                                    </div>
                                  </div>
                                </div>
                                </div>
                              </div>
                              <hr>
                       
                              <div class="row">
                                <div class="col-md-6">
                               <div class="form-group">
                                <label for="pwd">Help Topic:</label>
                                <select class="form-control" required="" id='helpTopic' name="help_topic">
                                    <option value="">---Select Help Topics---</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="general_enquiry">General Enquiry</option>
                                    <option value="report_problem">Report a Problem</option>
                                </select>
                              </div>
                          </div>
                          </div>
                                    <div class="row" id='moreDetails' style="display: none;">
                                      <div class="col-md-12">  
                                        <div class="form-group">
                                            <label class="control-label" for="pwd">Issue Summary:</label>
                                          
                                            <input type="text" name="issue_summary" placeholder="Enter Issue Summary" class="form-control input-sm" >

                                          </div>

                                           <div class="form-group">
                                            <label class="control-label" for="pwd">Detail Reason</label>
                                          
                                                <textarea class="form-control notepad" placeholder="Details Reason For Opening Tickets"name='detail_reason'></textarea>
                                            
                                          </div>
                                              <div class="row">
                                            <div class="col-md-6 ">
                                                  <div class="more-tr">
                                                     <table class="table more table-hover table-no-border" style="width: 100%;" cellspacing="2">
                                                        <tbody style="float: left">
                                                          <thead>
                                                            <tr>
                                                              <th> <button class="btn  bg-maroon btn-xs" id='more-button' type="button"><i class="fa fa-plus"></i>  Add More Files</button></th>
                                                              <th colspan="2"></th>
                                                            </tr>
                                                          </thead>
                                                       
                                                           <tr class="multipleDiv-attachment" style="float: left">
                                                           </tr>
                                                               <tr>
                                                              <td class="moreattachment" style=""> 
                                                                 <input type="file" name="attachment[]" class="attachment" >
                                                              </td>
                                                              <td class="w-25" >
                                                                 <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
                                                              </td>
                                                              <td >
                                                                 <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
                                                              </td>
                                                           </tr>
                                                        </tbody>
                                                     </table>
                                                  </div>
                                              </div>
                                        </div>
                                      </div>

                                    </div>


                              <button type="submit" class="btn btn-primary">Submit</button>
                               <a href="/ticket" class="btn btn-default">Cancel</a>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tab3primary">Primary 3</div>
                    </div>
                </div>
            </div>
     

    </div>
 </div>
</div>
  <div id="morefiles" style="display: none">
   <table class="table">
      <tbody id="more-custom-tr">
         <tr>
            <td class="moreattachment" style=""> 
               <input type="file" name="attachment[]" class="attachment" >
            </td>
            <td class="w-25" >
               <img src=""  style="max-height: 100px;float: right;margin-left: 30px" class='uploads'>
            </td>
            <td >
               <a href="javascript:void()" style="font-size: 20px; float: right" class="remove-this-attachment"> <i class="fa fa-close deletable"></i></a>
            </td>
         </tr>
      </tbody>
   </table>
</div>
    <!-- jQuery library -->
     <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
 <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
    
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>
    <script type="text/javascript">
        function openTab(tab){
      
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        }


            $('#more-button').click(function(){
                   $(".multipleDiv-attachment").after($('#morefiles #more-custom-tr').html());
            });

                $(document).on('click','.remove-this-attachment',function(){
                  $(this).parent().parent().remove();
                });

            const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
            $(document).on('change','.attachment',function(){
              var input = this;
              // console.log('done');
               var parent = $(this).parent().parent();
                  if (input.files && input.files[0]) {
                    var fileType = input.files[0]['type'];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                      if (validImageTypes.includes(fileType)) {
                        parent.find('.uploads')
                            .attr('src', e.target.result)
                            .width(150)
                            .height(200);
                        }
                      else{
                         parent.find('.uploads')
                            .attr('src','')
                            .width(0)
                            .height(0);
                      }
                   
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            });
   $('textarea.notepad').wysihtml5();

$('#helpTopic').change(function(){
    if($(this).val() == ''){

      $('#moreDetails').hide(); 
    }else{
        $('#moreDetails').show(); 
    }
  });

    </script>
 {{--    <script>
		
		$(document).ready(function() {

            $("#client_id").autocomplete({
                source: "/getClients",
                minLength: 1
            });			

		});
	</script>
    <script>
        (function() {
            // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
            if (!String.prototype.trim) {
                (function() {
                    // Make sure we trim BOM and NBSP
                    var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                    String.prototype.trim = function() {
                        return this.replace(rtrim, '');
                    };
                })();
            }
    
            [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
                // in case the input is already filled..
                if( inputEl.value.trim() !== '' ) {
                    classie.add( inputEl.parentNode, 'input--filled' );
                }
    
                // events:
                inputEl.addEventListener( 'focus', onInputFocus );
                inputEl.addEventListener( 'blur', onInputBlur );
            } );
    
            function onInputFocus( ev ) {
                classie.add( ev.target.parentNode, 'input--filled' );
            }
    
            function onInputBlur( ev ) {
                if( ev.target.value.trim() === '' ) {
                    classie.remove( ev.target.parentNode, 'input--filled' );
                }
            }
	
        })();
    </script> --}}
  
  </body>
</html>
