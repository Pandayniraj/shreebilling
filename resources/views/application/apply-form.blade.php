<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Job Apply Form::</title>
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
    <style>
        .required { color: red; }
    </style>

  </head>

  <!-- Body -->
  <body>
  	
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="tbc-form">
                    <header class="row">
                        <div class="col-md-12">

                           <div class="col-md-6"> <a href="/"><img src="/images/logo.png" alt="" class="img-responsive"></a> </div>
                           <div class="col-md-6">  <h2 class="pull-right"> </h2> </div>
                        </div>
                        
                    </header> <!-- /header -->
                    <div class="clearfix"></div>
                    
                    
                    @foreach($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
        
                    @if(Session::has('msg'))
                    <br/>
                    <p id="ajax-success-msg" class="alert alert-success">{{Session::get('msg')}}</p>
                    @endif
            
                    {!! Form::open( ['route' => 'post_job', 'id' => 'form_edit_lead', 'enctype' => 'multipart/form-data'] ) !!} 
                    <div class="top">
                        
                     
                    </div> <!-- /top -->
                    <div class="personal">
                        <label class="title bg-title">Application For: {{ PayrollHelper::getDesignation(Request::segment(2))}}</label>
                        <table class=" table-condensed">
                           
                            <tr>
                                <td class="form-group">
                                    <span class="input input--nao">
                                    	{!! Form::text('name', null, ['class' => 'input__field input__field--nao', 'id'=>'username', 'required'=>'required']) !!}
                                        <label class="input__label input__label--nao" for="username">
                                            <span class="input__label-content input__label-content--nao">Full Name <span class="required">*</span></span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                               
                                <td>
                                    <span class="input input--nao">
                                    	{!! Form::text('mobile', null, ['class' => 'input__field input__field--nao', 'id'=>'input-1','required'=>'required']) !!}
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Mobile Phone <span class="required">*</span></span>
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
                                    	{!! Form::email('email', null, ['class' => 'input__field input__field--nao', 'id'=>'input-1','required'=>'required']) !!}
                                        <label class="input__label input__label--nao" for="input-1">
                                            <span class="input__label-content input__label-content--nao">Email <span class="required">*</span></span>
                                        </label>
                                        <svg class="graphic graphic--nao" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
                                            <path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"/>
                                        </svg>
                                    </span>
                                </td>
                             
                                
                            </tr>
                           
                        </table>
                    </div> <!-- /Personal -->
                 
                    <hr style="margin:10px 0; border:none;">
                    <div class="experience">                        
                        <div class="table-responsive">
                            <div class="form-group">
                              <label for="comment">Cover Letter</label>
                              <textarea class="form-control" rows="9" name="cover_letter" id="cover_letter"></textarea>
                            </div>                       
                        </div>
                    </div> <!-- /Experience -->
                    <tr>
                        <td class="form-group">
                            <span class="input input--nao">
                            Attach CV: <span class="required">*</span>
                            </span>
                            <input type="file" name="resume" required>
                        </td>
                </tr>

                    <br/></br/>
                    <!-- <input type="checkbox" id="accept" name="accept"> Send me New Offers and Newsletters.<br><br> -->
                    
                    {!! Form::hidden('job_circular_id', Request::segment(3)) !!}
                    <input type="submit" class="btn btn-success btn-lg" name="submit" id="submit" value="Submit Enquiry">
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
