<!DOCTYPE html>
<html lang="en">
<head>
    <title>{!! env('APP_COMPANY') !!}  | {{ $page_title ?? "Page Title" }}</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta charset="utf-8" />
    <link rel="icon" href="favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/frontend/css/jquery-ui.css">
    <link rel="stylesheet" href="/frontend/css/owl.carousel.css">
    <link rel="stylesheet" href="/frontend/css/idangerous.swiper.css">
    <link rel="stylesheet" href="/frontend/css/style.css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lora:400,400italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:300,400,500,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/frontend/js/jquery.min.js"></script>
    <style>
        .autorize-tab-content input[type='password'] {
            border: 1px solid #ebebeb;
            background: #fff;
            width: 381px;
            border-radius: 3px;
            font-size: 11px;
            padding: 10px 8px 10px 8px;
            text-transform: uppercase;
            font-family: 'Raleway';
            font-weight: 600;
            color: #8a8a8a;
            margin-bottom: 15px;
        }

    </style>


</head>
<body style='background: #fff  @if($background_image)url(/frontend/img/world-map.png)@endif top center no-repeat fixed; background-size: cover;'>
    <!-- // authorize // -->
    <!-- \\ authorize \\-->

    @include(' frontend.partials.header') @yield('content') @include('frontend.partials.footer')
    <!-- // scripts // -->

    <script src=" https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <script src="/frontend/js/idangerous.swiper.js"></script>
    <script src="/frontend/js/slideInit.js"></script>
    <script src="/frontend/js/owl.carousel.min.js"></script>
    <script src="/frontend/js/bxSlider.js"></script>
    <script src="/frontend/js/jqeury.appear.js"></script>
    <script src="/frontend/js/custom.select.js"></script>
    <script src="/frontend/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/frontend/js/twitterfeed.js"></script>
    <script>
        const _appLogo = `{{env('APP_URL')}}{{ '/org/'.$organization->logo }}`;
        const __todayDate = "{{date('Y-m-d')}}";

    </script>
     <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="/datetime-picker/jquery.datetimepicker.full.min.js" ></script>
    <link rel="stylesheet" href="datetime-picker/jquery.datetimepicker.css"  />
    <script src="/frontend/js/script.js"></script>

    <script src='/sweetalert/sweetalert.js'></script>
    <script src='/toastr/toastr.min.js'></script>
    <link rel="stylesheet" type="text/css" href="/toastr/toastr.min.css">
    <!-- \\ scripts \\ -->
    <script type="text/javascript">
        $.ui.autocomplete.prototype._renderItem = function (ul, item) {
            var re = new RegExp($.trim(this.term.toLowerCase()));
    
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + item.label + "</a>")
            .appendTo(ul);
        };
        @foreach(session('flash_notification', collect())->toArray() as $message)

        @if(!$message['overlay'])

            @php $__flashLevel = ['success' => 'success', 'warning' => 'warning', 'danger' => 'error', 'info' => 'info']; @endphp

        swal({

            title: "{{ucfirst($__flashLevel[$message->level]) }}",

            text: "{{$message->message}}",

            icon: "{{ $__flashLevel[$message->level] }}",

            button: "OK",

            timer: '10000',

        });



        @endif

        {{ session()->forget('flash_notification') }}

        @endforeach



        @if(count($errors) > 0)

        @foreach($errors->all() as $error)

        swal({

            title: "Error",

            text: "{{ $error }}",

            icon: "error",

            button: "OK",

            timer: '10000',

        });

        @endforeach



        @endif

    </script>
</body>
</html>
