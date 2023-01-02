@extends('layouts.app')
@section('content')

<h1>Reservation Page ::#FlightId{{$reservation['FlightId']}}</h1>

<form method="post" action="/issueticket">
    {{ @csrf_field() }}

    <input type="hidden" name="FlightId" value="{{$reservation['FlightId']}}">

    @if($reservation['ReturnFlightId'])

    <input type="hidden" name="ReturnFlightId" value="{{$array['ReturnFlightId']}}">

    @endif

    <div class="row">
        <div class="col-md-4">
            <label for="inputEmail3" class="control-label">
                Contact Name
            </label>
            <div class="form-group">
                <input type="text" name="contact_name" placeholder="Contact Name" id="contact_name" class="form-control input-sm datepicker">
            </div>
        </div>
        <div class="col-md-4">
            <label for="inputEmail3" class="control-label">
                Email Address
            </label>
            <div class="form-group">
                <input type="text" name="email_aaddress" placeholder="Email Address" id="email_aaddress" class="form-control input-sm datepicker">
            </div>
        </div>
        <div class="col-md-4">
            <label for="inputEmail3" class="control-label">
                Contact No
            </label>
            <div class="form-group">
                <input type="text" name="contact_no" placeholder="Contact No" id="contact_no" class="form-control input-sm datepicker">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" style="margin-top: 10px;">
            <a href="javascript::void(0)" class="btn btn-primary btn-xs" id="addMore" style="float: left;">
                <i class="fa fa-plus"></i> <span>Add More Contact Details</span>
            </a>
        </div>
    </div>

    <div class="row InputsWrapper" style="margin: 5px;">

    </div>


    <div id="orderFields" style="display: none;">
        <table class="table">
            <tbody id="more-tr">
                <tr>
                    <td style="padding-right:5px;">
                        <label class="control-label">Pax Type</label>

                        <select type="text" name="pax_type[]" id="mobile1" value="{{ old('pax_type') }}" class="form-control input-sm" style="width:100px;">
                            <option value="">Select</option>
                            <option value="ADULT">Adult</option>
                            <option value="CHILD">Child</option>
                        </select>
                    </td>
                    <td style="padding-right:5px;">
                        <label class="control-label">Title</label>
                        <select type="text" name="title[]" id="title" value="{{ old('title') }}" class="form-control input-sm">
                            <option value="">Select</option>
                            <option value="MR">Mr</option>
                            <option value="MRS">Mrs</option>
                        </select>
                    </td>

                    <td style="padding-right:5px;">
                        <label class="control-label">Gender</label>
                        <select type="text" name="gender[]" id="title" value="{{ old('title') }}" class="form-control input-sm">
                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </td>

                    <td style="padding-right:5px;">
                        <label class="control-label">Last Name</label>
                        <input type="text" name="last_name[]" id="last_name" value="{{ old('last_name') }}" class="form-control input-sm" placeholder="Last Name">
                    </td>

                    <td style="padding-right:5px;">
                        <label class="control-label">First Name</label>
                        <input type="text" name="first_name[]" id="first_name" value="{{ old('first_name') }}" class="form-control input-sm" placeholder="First Name">
                    </td>

                    <td style="padding-right:5px;">
                        <label class="control-label">Nationality</label>
                        <select type="text" name="nationality[]" id="title" value="{{ old('nationality') }}" class="form-control input-sm">
                            <option value="">Select</option>
                            <option value="NP">Nepal</option>
                        </select>
                    </td>

                    <td style="padding-right:5px;">
                        <label class="control-label">Remarks</label>
                        <input type="text" name="remarks[]" id="remarks" value="{{ old('remarks') }}" class="form-control input-sm" placeholder="Remarks">
                    </td>

                    <td style="position: relative;"><a href="javascript::void(1);" style="position:absolute; top:26px;">
                            <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                        </a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row">

    </div>

    <button class="btn btn-success">Issue Ticket</button>

</form>

<script>
    $("#addMore").on("click", function() {
        $(".InputsWrapper").after($('#orderFields #more-tr').html());
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
    });

</script>

@endsection
