<!DOCTYPE html>

<html>

  <head>

    <meta charset="UTF-8">

    <title>:: {{ env('APP_NAME') }} - Ticket::</title>
    

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

    <div class="container">

        <div class="row">

            <div class="col-md-12">

            	<div class="tbc-form">

                    <header class="row" style="text-align:center;">

                        <a href="{{ env('APP_URL') }}"><img src="/images/app_logo.png" alt="" class="img-responsive" style="width:200px; margin:0 auto;"></a>

                        <h1 style="font-size:30px; padding:15px; margin:0;">Ticket
                    </header> <!-- /header -->

                    <div class="clearfix"></div>

                    <br>

                    <table class="table table-striped">

                        <tr>

                            <th width="10%">ID:</th>

                            <td width="20%">TKT-{{Session::get('id')}}</td>

                            <td></td>

                            <td></td>

                            <th width="20%" style="text-align:right;">Submission Date:</th>

                            <td width="20%">{{ date('D, M, d, Y') }}</td>

                        </tr>

                        <tr class="thankyou">

                            <td colspan="6" style="text-align:center;">

                                <h3>Your Ticket has been submitted successfully. </h3><br>

                                <p>Please quote this application number for future reference.</p><br>

                                <h4>Your Registered No is : TKT-{{Session::get('id')}} </h4>

                            </td>

                        </tr>

                                             

                     

                        <tr>

                            <th width="50%" colspan="3" style="text-align:right;">Subject:</th>

                            <td width="50%" colspan="3">{!! Session::get('subject') !!}</td>

                        </tr>

                    </table>
                    <div class="row">
                        <a class="btn btn-default" href="/ticket"><i class="fa fa-home"></i>  Home</a>
                    </div>
                    
<!-- 
                    <div class="social">
                        <p>Stay Connected</p>
                        <ul>
                            <li><a target="_blank" href="{{ env('APP_FACEBOOK') }}"><i class="fa fa-facebook"></i></a></li>
                            <li><a target="_blank" href="{{ env('APP_TWITTER') }}"><i class="fa fa-twitter"></i></a></li>
                            <li><a target="_blank" href="{{ env('APP_LINKEDIN') }}"><i class="fa fa-linkedin"></i></a></li>
                            <li><a target="_blank" href="{{ env('APP_YOUTUBE') }}"><i class="fa fa-youtube"></i></a></li>
                            <li><a target="_blank" href="{{ env('APP_GPLUS') }}"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div> -->

                </div>

            </div>

        </div>

    </div>

  </body>

</html>

