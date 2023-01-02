<style type="text/css">
    .ui-datepicker{
        z-index: 1000 !important;
    }
    .sectors{
        text-transform: uppercase;
    }
</style>
<br/>


  <div id="booking" class="section">
                <div class="section-center">
                    <div class="container">

                        {{-- <img src="/frontend/banner.gif"> --}}

                        <form method="get" action="/flights" id='flightSearch'>
                   
                            <div class="">
                                <div style="background-color: #EA2742; padding:10px;color:white !important" class="form-group">
                                    <div class="form-checkbox">
                                        <label for="roundtrip">
                                            <input type="radio" id="roundtrip" name="return" value="R" class="flighttype">
                                            <span></span>Round trip
                                        </label>
                                        <label for="one-way">
                                            <input type="radio" id="one-way" name="return" value="O" checked class="flighttype">
                                            <span></span>One way
                                        </label>
                                        <label for="multi-city">
                                            <input type="radio" id="multi-city" name="return" value="M" class="flighttype">
                                            <span></span>Multi-City
                                        </label>
                                    </div>
                                </div>
                                <div class="booking-form" style="margin-top:-22px">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <span class="form-label">Flying from</span>
                                                <input type="hidden" name="sector_form" class="locations" value="KTM">
                                                <input class="form-control sectors" type="text" value="KTM" placeholder="City or airport">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <span class="form-label">Flying to</span>
                                                <input type="hidden" name="sector_to" class="locations">
                                                <input class="form-control sectors" type="text" placeholder="City or airport">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <span class="form-label">Departing</span>
                                                <input class="form-control date-inpt-today" type="text" name="flight_date" required autocomplete="off" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <div class="form-group" id='returningDate' style="display: none;">
                                                <span class="form-label">Returning</span>
                                                <input class="form-control date-inpt-today" type="text" name="return_date" value="{{old('return_date')}}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <span class="form-label">Adults (12+)</span>
                                                <select class="form-control passenger" name="adult_no">
                                                    @foreach(range(1,9) as $i)
                                                    <option>{{$i}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <span class="form-label">Child (0-12)</span>
                                                <select class="form-control passenger" name="clild_no">
                                                    @foreach(range(0,9) as $i)
                                                    <option>{{$i}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <span class="form-label">Infant</span>
                                                <select class="form-control passenger" name="infant_no">
                                                    @foreach(range(0,9) as $i)
                                                    <option>{{$i}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <span class="form-label">Nationality</span>
                                                <select class="form-control" name="strnationality">
                                                    <option value="">Select Nation</option>
                                                    @foreach(FlightHelper::getNationality() as $np=>$nation)
                                                    <option value="{{ $nation->iso }}" @if($nation->iso =='NP' ) selected @endif>{{ $nation->nicename }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                       

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <span class="form-label">Travel class</span>
                                                <select class="form-control" name="cabin">
                                                    <option value="">All Class</option>
                                                    <option value="Y">Economy class</option>
                                                    <option value="C">Business class</option>
                                                    <option value="F">First class</option>
                                                </select>
                                                <span class="select-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-btn">
                                                <button class="submit-btn" id='flightSearchButton'>Show flights <i class="fa fa-plane"></i></button>
                                            </div>
                                        </div>

                                         <div class="col-md-2">
                                           <div class="form-group" style="display: inline-block;">
                                            <span class="form-label" style="display: inline-block;">Refundable</span>
                                            <input type="checkbox" name="is_refundable" class="form-control">
                                          
                                        </div>
                                        </div>
                                    </div>


                                    <div id='multi-city-search' style="display: none;">
                                        <div class="row multi-city-count">

                                    
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <span class="form-label">Flying from</span>
                                                    <input class="form-control sectors" type="text" name="sector_form_multi[]" placeholder="City or airport">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <span class="form-label">Flying to</span>
                                                    <input class="form-control sectors" type="text" name="sector_to_multi[]" placeholder="City or airport">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <span class="form-label">Departing</span>
                                                    <input class="form-control date-inpt-today" type="text" name="flight_date_multi[]"  autocomplete="off" >
                                                </div>
                                            </div>
                                          
                                        </div>
                                        <div id='more-city'></div>
                                    <div class="row">

                                        <div class="col-md-2">
                                            <div class="form-btn">
                                                <button class="submit-btn" type="button" style="background: red;" id='add-destination'>Add Destination <i class="fa fa-plus" ></i></button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id='multi-city-search-form' style="display: none;">
                <div class="row multi-city-count">
                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <span class="form-label">Flying from</span>
                                <input class="form-control sectors" type="text" name="sector_form_multi[]" placeholder="City or airport" required="">
                            </div>
                        </div>

                         <div class="col-md-3">
                            <div class="form-group">
                                <span class="form-label">Flying to</span>
                                <input class="form-control sectors" type="text" name="sector_to_multi[]" placeholder="City or airport" required="">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <span class="form-label">Departing</span>
                                <input class="form-control date-inpt-more" type="text" name="flight_date_multi[]" required autocomplete="off" required="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-btn">
                                <button class="submit-btn" type="button" style="background: red;" onclick='removeFlight(this)'><i class="fa fa-trash" style="zoom: 1.5;" ></i></button>
                            </div>
                        </div>
                    </div>
            </div>
<script type="text/javascript">

  
    $('input.flighttype').change(function(){
        let type = $(this).val();
        
        $('#returningDate').hide();
        $('#multi-city-search').hide();
        $('#multi-city-search input').prop('required',false);
        if(type == 'R'){
            $('#returningDate').show();
        }
        else if(type == 'M'){
            $('#multi-city-search input').prop('required',true);
            $('#multi-city-search').show();
        }

    });

    function validatePassengar() {
        let a = $("select[name=adult_no]").val();
        let c = $("select[name=clild_no]").val();
        let i = $("select[name=infant_no]").val();
        let total = Number(a) + Number(c) + Number(i);
        if (total > 9) return false;
        else return true;

    }


    $('#flightSearch').submit(function() {


        let departCity = $("input[name=sector_form]").val().toUpperCase();

        let arrivalCity = $("input[name=sector_to]").val().toUpperCase();


        if (departCity == arrivalCity.toUpperCase()) {
            toastr.error('Depart & Arrival Place cannot be same', 'Error!', {
                timeOut: 5000
                , closeButton: true
                , progressBar: true
            })
            return false;

        }
        if (!validatePassengar()) {
        swal({

            title: "Max Passenger Limit is 9",

            text: "Error",

            icon: "error",

            button: "OK",

            timer: '10000',

        });
            return false;
        }
        $('button#flightSearchButton').prop("disabled",true);
        $('button#flightSearchButton').html(`<i class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;Processing`);

        $("input[name=sector_form]").val(departCity);
        $("input[name=sector_to]").val(arrivalCity);
        return true;

    });
    const totalPassenger = {
        'adult_no': 1
        , 'clild_no': 0
        , 'infant_no': 0
    };

    $('select.passenger').change(function(event) {
        let name = $(this).attr('name');
        if(name == 'infant_no'){
            
            if($('select[name=adult_no]').val() < $('select[name=infant_no]').val()){

                 swal({

                    title: "Infant cannot Be Greater than Adults",

                    text: "Error",

                    icon: "error",

                    button: "OK",

                    timer: '10000',

                });


                totalPassenger[name] = $(this).val(totalPassenger[name]);
                return;



            }


        }
        if (!validatePassengar()) {
        swal({

            title: "Max Passenger Limit is 9",

            text: "Error",

            icon: "error",

            button: "OK",

            timer: '10000',

        });
            $(this).val(totalPassenger[name]);
        } else {
            totalPassenger[name] = $(this).val();
        }

    });




    $('#add-destination').click(function(){
        let counts = $('#multi-city-search .multi-city-count');
        if(counts.length >= 4){
            swal({

                title: "Only 4 City Are Allowed",

                text: "Error",

                icon: "error",

                button: "OK",

                timer: '10000',

            });
            return false;
        }
        let prvflight = counts[counts.length -1];
        let forms = $('#multi-city-search-form').html();
        
        $('#more-city').before(forms);

        let jqueryElemnt = $('#more-city').prev('div');

        let sectors = jqueryElemnt.find('.sectors');
        let dateinput = jqueryElemnt.find('.date-inpt-more');
        sectors.autocomplete({
            source: '/getSectors'
            , minLength: 1
        });
        dateinput.datetimepicker({
             format:'d-m-Y',
            timepicker:false,
            minDate: __todayDate,
        });


    });
    function removeFlight(ev){
        $(ev).parent().parent().parent().remove();
    }
    
</script>