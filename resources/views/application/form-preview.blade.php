<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>:: The British College - Application Form::</title>
    <meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="{{ asset ("/img/favicon.png") }}" rel="icon">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Application CSS-->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <!-- Body -->
  <body>
  
  <!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-K468FZ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K468FZ');</script>
<!-- End Google Tag Manager -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tbc-form">
                    <header class="row">
                        <div class="col-md-3">
                            <a href="http://www.thebritishcollege.edu.np"><img src="/images/app_logo.png" alt="" class="img-responsive"></a>
                        </div>
                        <h1 class="col-md-9">Online Application Form</h1>
                    </header> <!-- /header -->
                    <div class="clearfix"></div>
                    <br>
                    <br>
                    @foreach($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
        
                    @if(Session::has('msg'))
                    <br/>
                    <p id="ajax-success-msg" class="alert alert-success">{{Session::get('msg')}}</p>
                    @endif
                    <table class="table table-striped">
                        <tr>
                            <th colspan="4" style="text-align:right;">Submission Date:</th>
                            <td width="20%">{{ date('D, M, d, Y') }}</td>
                        </tr>
                    </table>
                    {!! Form::open( ['route' => 'post_enquiry', 'id' => 'form_edit_lead'] ) !!} 
                    <div class="top">
                    	<input type="hidden" name="lead_id" value="{{ $lead_id }}">
                        <label class="title bg-title">1. Course</label>
                        <table class="table table-striped">
                            <tr>
                                <th>Intake</th>
                                <td>{{ TaskHelper::getIntakeName($intake_id) }}</td>
                              	<input type="hidden" name="intake_id" value="{{ $intake_id }}">
                            </tr>
                            <tr>
                                <th>Course</th>
                                <td>{{ TaskHelper::getCourseName($course_id) }}</td>
                                <input type="hidden" name="course_id" value="{{ $course_id }}">
                            </tr>
                        </table>
                    </div> <!-- /top -->
                    <div class="personal">
                        <label class="title bg-title">2. Personal Details</label>
                        <table class=" table-condensed">
                            <tr>
                                <td>
                                    <strong>Mr.</strong>
                                </td>
                                <td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $title.' '.$name}}" readonly />
                                        <input type="hidden" name="title" value="{{ $title }}">
                                        <input type="hidden" name="name" value="{{ $name }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Full Name</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                                <td>
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $dob }}" readonly />
                                        <input type="hidden" name="dob" value="{{ $dob }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Date of Birth (A.D.)</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                                <td>
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $mob_phone }}" readonly />
                                        <input type="hidden" name="mob_phone" value="{{ $mob_phone }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Mobile Phone</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $email }}" readonly />
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Email</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $home_phone }}" readonly />
                                        <input type="hidden" name="home_phone" value="{{ $home_phone }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Residental No.</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $guardian_phone }}" readonly />
                                        <input type="hidden" name="guardian_phone" value="{{ $guardian_phone }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Guradian No.</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $address_line_1 }}" readonly />
                                        <input type="hidden" name="address_line_1" value="{{ $address_line_1 }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Resident Address</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $gender }}" readonly />
                                        <input type="hidden" name="gender" value="{{ $gender }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Gender (M/F)</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $nationality }}" readonly />
                                        <input type="hidden" name="nationality" value="{{ $nationality }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Nationality</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                                <td class="form-group">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" type="text" id="input-1" value="{{ $marital_status }}" readonly />
                                        <input type="hidden" name="marital_status" value="{{ $marital_status }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Marital Status</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                            
                                <td colspan="2" class="form-group">
                                        <label class="radio-wrap">
                                            <span>Handicapped : {{ $disability }}</span>
                                            <input type="hidden" name="disability" value="{{ $disability }}">
                                        </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span class="input input--nao">
                                        <input class="input__field input__field--nao" value="{{ $disability_detail }}" readonly>
                                        <input type="hidden" name="disability_detail" value="{{ $disability_detail }}">
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Do you consider yourself to have a disability? If yes, please specify: Yes/No</span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div> <!-- /Personal -->
                    <hr style="height:1px; margin:5px 0; border:none;">
                    <div class="academic">
                        <label class="title bg-title">3. Academic Qualification</label>
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Institution/Examination Body</th>
                                <th>Qualifications</th>
                                <th>Language of Instruction</th>
                                <th>Period Attended (From - To)</th>
                                <th>Grade Received</th>
                            </tr>
                            <tr>
                                <td>{{ $company_id }}</td>
                                <input type="hidden" name="company_id" value="{{ $company_id }}">
                                <td>{{ $qualification }}</td>
                                <input type="hidden" name="qualification" value="{{ $qualification }}">
                                <td>{{ $language_of_instruction }}</td>
                                <input type="hidden" name="language_of_instruction" value="{{ $language_of_instruction }}">
                                <td>{{ $peroid_attended }}</td>
                                <input type="hidden" name="peroid_attended" value="{{ $peroid_attended }}">
                                <td>{{ $grade }}</td>
                                <input type="hidden" name="grade" value="{{ $grade }}">
                            </tr>
                        </table>
                        </div>
                    </div> <!-- /Academic Qualification -->
                    <hr style="margin:5px 0; border:none;">
                    <div class="experience">
                        <label class="title bg-title">4. Experience</label>
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Organization Name</th>
                                <th>Job Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Job Descriptions</th>
                            </tr>
                            <tr>
                                <td>{{ $organization }}</td>
                                <input type="hidden" name="organization" value="{{ $organization }}">
                                <td>{{ $job_title }}</td>
                                <input type="hidden" name="job_title" value="{{ $job_title }}">
                                <td>{{ $job_start }}</td>
                                <input type="hidden" name="job_start" value="{{ $job_start }}">
                                <td>{{ $job_end }}</td>
                                <input type="hidden" name="job_end" value="{{ $job_end }}">
                                <td>{{ $job_desc }}</td>
                                <input type="hidden" name="job_desc" value="{{ $job_desc }}">
                            </tr>
                        </table>
                        </div>
                    </div> <!-- /Experience -->
                    <h3>Please confirm above details. </h3>
                    <p style="font-size:14px;">If the above details are correct, click on the confirm button below to submit your data. If you want to edit click on Edit button below to change/update your information.<br><br>
    Note : Candidates registering for multiple courses should be using different email id every time. The same email id cannot be used to register for multiple courses in The British College. </p><br>
    
                    <input type="button" class="btn btn-danger" value="Back to Edit" onClick="history.go(-1);return true;">
                    <input type="submit" class="btn btn-primary" value="Submit Now">
                </form>
                </div>
            </div>
        </div>
    </div>
  
    <!-- jQuery library -->
    <script src="{{ asset ("/appjs/jquery.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/appjs/bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/appjs/classie.js") }}" type="text/javascript"></script>
    
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>
    <script>
		$(function() {
			$('#dob').datetimepicker({
				//inline: true,
				format: 'DD-MM-YYYY',
				sideBySide: true
			});
		});
		
		$(document).ready(function() {
			$("#company_id").autocomplete({
				source: "/admin/getdata",
				minLength: 1
			});
			
			$("#username").keyup(function(){
				$('#auto-name').html(this.value);
			});
			
			$('#course_id').change(function() {
				$('#auto-course').html($("#course_id option:selected").text());
			});
			
			$('#accept').change(function(){
				if(this.checked)
					$('#submit').removeAttr('disabled');
				else
					$('#submit').attr('disabled', 'disabled');
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
    </script>
  
  </body>
</html>
