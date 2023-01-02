<!DOCTYPE html>

<html>

  <head>

    <meta charset="UTF-8">

    <title>:: {{ env('APP_NAME') }} - Thanks::</title>
    

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

                        <h1 style="font-size:30px; padding:15px; margin:0;">Recruitment
                    </header> <!-- /header -->

                    <div class="clearfix"></div>

                    <br>

                    <table class="table table-striped">

                        

                        <tr class="thankyou">

                            <td colspan="6" style="text-align:center;">

                                <h3>Your Job Application has been submitted successfully. </h3><br>

                               <p> We will contact you soon. Thanks</p>

                            </td>

                        </tr>


                    </table>


                </div>

            </div>

        </div>

    </div>

  </body>

</html>

