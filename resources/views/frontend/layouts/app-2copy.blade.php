<!DOCTYPE html>
<html lang="en">
<head>
    <title> {{env('APP_COMPANY')}}</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta charset="utf-8" />
    <link rel="icon" href="favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="/frontend/css/jquery-ui.css">
    <link rel="stylesheet" href="/frontend/css/owl.carousel.css">
    <link rel="stylesheet" href=/frontend/css/idangerous.swiper.css">
    <link rel="stylesheet" href="/frontend/css/style.css" />
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lora:400,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:300,400,500,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
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
<body>
    <!-- // authorize // -->
    <div class="overlay"></div>
    <div class="autorize-popup" style="height:auto;">
        <div class=" autorize-tabs">
            <a href="#" class="autorize-tab-a current">Sign in</a>
            <a href="#" class="autorize-tab-b">Register</a>
            <a href="#" class="autorize-close"></a>
            <div class="clear"></div>
        </div>
        {{-- <section class="autorize-tab-content">
            <div class="autorize-padding">
                <h6 class="autorize-lbl">Welocome! Login in to Your Accont</h6>
                <form method="POST" action="{{ route('login') }}">
        {!! csrf_field() !!}
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="password" name="password" value="" placeholder="Password" required>
        <footer class="autorize-bottom">
            <button class="authorize-btn" type="submit">Login</button>
            </form>
            <a href="/password/reset" class="authorize-forget-pass">Forgot your password?</a>
            <div class="clear"></div>
        </footer>

    </div>
    </section> --}}
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>
    <section class="autorize-tab-content">
        <div class="autorize-padding">
            <h6 class="autorize-lbl">Register for Your Account</h6>
            <form method="POST" action="{!! route('register') !!}">
                {!! csrf_field() !!}
                <input type="text" name="first_name" id="first_name" placeholder="First name" value="{{ old('first_name') }}" required>
                <input type="text" id="last_name" name="last_name" placeholder="Last name" value="{{ old('last_name') }}" required />
                <input type="text" id="username" name="username" placeholder="User name" value="{{ old('username') }}" required>
                <input type="text" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                <input type="password" id="password" name="password" placeholder="Password" required />
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Password" required />
                <footer class="autorize-bottom">
                    <button class="authorize-btn" type="submit">Registration</button>
            </form>
            <div class="clear"></div>
            </footer>
        </div>
    </section>
    </div>
    <!-- \\ authorize \\-->

    @include('frontend.partials.header')

    @yield('content')

    @include('frontend.partials.footer')


    <!-- // scripts // -->


    <script src="/frontend/js/idangerous.swiper.js"></script>
    <script src="/frontend/js/slideInit.js"></script>
    <script src="/frontend/js/owl.carousel.min.js"></script>
    <script src="/frontend/js/bxSlider.js"></script>
    <script src="/frontend/js/jqeury.appear.js"></script>
    <script src="/frontend/js/custom.select.js"></script>
    <script src="/frontend/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/frontend/js/twitterfeed.js"></script>
    <script src="/frontend/js/script.js"></script>
    <!-- \\ scripts \\ -->

</body>
</html>
