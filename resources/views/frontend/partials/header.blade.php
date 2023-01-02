<style>
    body.modal-open {
        overflow: visible;
    }

    .modal-login h4 {
        text-align: center;
        font-size: 26px;
        /* margin: 30px 0 -15px; */
    }

    .modal-login {
        color: #e82426;
        width: 350px;
    }

    /* Important part */
    .modal-lg {
        overflow-y: initial !important
    }

    .modal-lg .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }


    .signup-upload-doc {
        border: 1px solid #888888;
        font-size: 14px;
        cursor: pointer;
        text-align: center;
        font-weight: 400;
        padding: 7px;
        display: block;
        color: #555555;
    }

    .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }

    .panel-default>.panel-heading {
        color: #333;
        background-color: #f5f5f5;
        border-color: #ddd;
    }

    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
    }

    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    }

    .panel-title {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 16px;
        color: inherit;
    }

    .panel-body {
        padding: 15px;
    }

</style>

<header id="top">

    <div class="header-a" style="background-color: #EA2742 !important">

        <div class="wrapper-padding">
            <div class="header-phone"><span>{{$organization->phone}}</span></div>
            @guest
            <div class="header-account">
                <a href="#" id="myBtn" data-toggle="modal" data-target="#exampleModalCenter">Login</a>
            </div>
            <div class="header-account">
                <a href="#" id="myBtnRegister" data-toggle="modal" data-target=".bd-example-modal-lg">Register</a>
            </div>

            @include('frontend.models.userlogin')

            @include('frontend.models.userregister')

            @else

            <div style="float: right;position: relative;">
                <a class="header-viewed-btn" href="{{ route('logout') }}" 
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <div class="header-curency">
                <a href="/admin/wallet/{{Auth::user()->id}}/view" class="header-viewed-btn">
                Balance  {{env('APP_CURRENCY')}}  <strong > {{ Auth::user()->wallet()->balance }}</strong>
                </a>
            </div>
            {{-- <div class="header-curency">
               
            </div>
              <div class="header-curency">
                <a href="#">{{env('APP_CURRENCY')}}</a>
            </div> --}}
           
           
            @endguest
            <div class="header-social">
                <a href="#" class="social-twitter"></a>
                <a href="#" class="social-facebook"></a>
                <a href="#" class="social-instagram"></a>
            </div>
            <div class="header-viewed">
                @auth
                <div class="header-curency">
                    <a href="/home" class="header">DashBoard</a>
                </div>
                @endauth
                <!-- // viewed drop // -->
               {{--  <div class="viewed-drop">
                    <div class="viewed-drop-a">
                        <!-- // -->

                        @foreach($tours_all as $ta)
                        <div class="viewed-item">
                            <div class="viewed-item-l">
                                <a href="/tours/{{$ta->id}}/{{$ta->tour_slug}}"><img alt="" src="/tours-img/{{$ta->thumbnail_image}}" width="100%" /></a>
                            </div>
                            <div class=" viewed-item-r">
                                <div class="viewed-item-lbl"><a href="/tours/{{$ta->id}}/{{$ta->tour_slug}}">Andrassy Thai Hotel</a></div>
                                <div class="viewed-item-cat">location: {{$ta->location->location}}</div>
                                <div class="viewed-price">{{$ta->tour_basic_price}}$</div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        @endforeach
                        <!-- \\ -->


                    </div>
                </div> --}}
                <!-- \\ viewed drop \\ -->
            </div>


            <div class="clear"></div>
        </div>
    </div>

    <div class="header-b">
        <!-- // mobile menu // -->
        <div class="mobile-menu">
            <nav>
                <ul>
                    @foreach($menus as $menu)
                    <li><a  href="{{$menu->url}}">{{$menu->name}}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <!-- \\ mobile menu \\ -->

        <div class="wrapper-padding">
            <div class="header-logo"><a href="/">
                    {{-- <img alt="{{env('APP_COMPANY')}}" src="/frontend/img/logo.png" /> --}}
                    <img style='height: 80px;width:auto;margin-top:-20px' src="{{env('APP_URL')}}/{{ '/org/'.$organization->logo }}">
                </a>
                <time class='countdown'></time>
            </div>
            <div class="header-right">
                
                <div class="hdr-srch-devider"></div>
                <a href="#" class="menu-btn"></a>
                <nav class="header-nav">
                    <ul>
                        @foreach($menus as $menu)
                        <li><a href="{{$menu->url}}">{{$menu->name}}</a></li>
                        @endforeach
                    </ul>
                </nav>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</header>
<script>
    document.getElementById("myBtn").onclick = function() {
        document.getElementById("myModal").style.display = "block";
    }

    document.getElementById("myBtnRegister").onclick = function() {
        document.getElementById("myModalLarge").style.display = "block";
    }

    document.getElementsByClassName("close")[0].onclick = function() {
        document.getElementById("myModal").style.display = "none";
    }
    document.getElementsByClassName("close")[1].onclick = function() {
        document.getElementById("myModalLarge").style.display = "none";
    }

    document.getElementsByClassName("cross").onclick = function() {
        document.getElementById("myModal").style.display = "none";
    }

    // When the user clicks on <span> (x), close the modal
    // document.getElementsByClassName("cross")[1].onclick = function() {
    //     document.getElementById("myModalLarge").style.display = "none";
    // }


    window.onclick = function(event) {
        if (event.target == document.getElementById("myModal")) {
            document.getElementById("myModal").style.display = "none";
        }

    }
    $(function() {
        $(document).on('click', '.guestlogin', function() {
            let key  = $(this).attr('key');
            
            let formid = `${key}-bookform`;

            document.getElementById("myModal").style.display = "block";

            if(key != undefined){
               $('#guest_booking').show();
               $('button#guest_booking').attr('key',formid); 
            }else{
                $('#guest_booking').hide();
            }
            


        });
        $(document).on('click', '.guestregister', function() {
            document.getElementById("myModal").style.display = "none";
            document.getElementById("myModalLarge").style.display = "block";

        });

        $(document).on('click','#guest_booking',function(){

            let formid = $(this).attr('key');
            
            $(`#${formid}`).attr('action','/guestreservation');

            setTimeout(function(){

                $(`#${formid}`).submit();

            });

           


        });


    });








</script>
