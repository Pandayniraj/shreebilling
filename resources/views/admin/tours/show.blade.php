@extends('layouts.master')

@section('content')
<link href="{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css" />
<script src='{{ asset("/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}'></script>

@include('partials._head_extra_select2_css')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Title</label>
                        <div class="input-group">
                            <input type="text" name="tour_title" class="form-control" placeholder="Tour Title" required="" value="{{$tours->tour_title}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-navicon"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Stars</label>
                        <div class="input-group">
                            <input type="number" name="tour_stars" class="form-control" placeholder="Tour Stars" min="1" max="10" value="{{$tours->tour_stars}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-star"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Featured</label>
                        <div class="input-group">
                            <input type="checkbox" name="tour_is_featured" value="Yes" @if($tours->tour_is_featured == 'yes') checked @endif>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Owned By</label>
                        <div class="input-group">
                            <select type="text" name="tour_owned_by" class="form-control" style="width:400px;" readonly>
                                <option value="">Select Owner</option>
                                @foreach($users as $tt)
                                <option value="{{$tt->id}}" @if($tours->tour_owned_by == $tt->id) selected @endif>{{$tt->username}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Type</label>
                        <div class="input-group">
                            <select type="text" name="tour_type" class="form-control" style="width:400px;" readonly>
                                <option value="">Select Type</option>
                                @foreach($tourtype as $tt)
                                <option value="{{$tt->id}}" @if($tours->tour_type == $tt->id) selected @endif>{{$tt->sett_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Location</label>
                        <div class="input-group">
                            <select type="text" name="tour_location" class="form-control" style="width:400px;" readonly>
                                <option value="">Select Location</option>
                                @foreach($locations as $tt)
                                <option value="{{$tt->id}}" @if($tours->tour_location == $tt->id) selected @endif>{{$tt->location}} ({{$tt->country}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Latitude</label>
                        <div class="input-group">
                            <input type="text" name="tour_latitude" class="form-control" placeholder="Tour Latitude" value="{{$tours->tour_latitude}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-arrows"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Longitude</label>
                        <div class="input-group">
                            <input type="text" name="tour_longitude" class="form-control" placeholder="Tour Longitude" value="{{$tours->tour_longitude}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-arrows-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Mapaddress</label>
                        <div class="input-group">
                            <input type="text" name="tour_mapaddress" class="form-control" placeholder="Tour Mapaddress" value="{{$tours->tour_mapaddress}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-map"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Basic Price</label>
                        <div class="input-group">
                            <input type="text" name="tour_basic_price" class="form-control" placeholder="Tour Basic Price" value="{{$tours->tour_basic_price}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-money"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Basic Discount</label>
                        <div class="input-group">
                            <input type="text" name="tour_basic_discount" class="form-control" placeholder="Tour Basic Discount" value="{{$tours->tour_basic_discount}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-arrow-down"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Max Adults</label>
                        <div class="input-group">
                            <input type="text" name="tour_max_adults" class="form-control" placeholder="Tour Max Adults" value="{{$tours->tour_max_adults}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-users"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Max Child</label>
                        <div class="input-group">
                            <input type="text" name="tour_max_child" class="form-control" placeholder="Tour Max Child" value="{{$tours->tour_max_child}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-user"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Max Infant</label>
                        <div class="input-group">
                            <input type="text" name="tour_max_infant" class="form-control" placeholder="Tour Max Infant" value="{{$tours->tour_max_infant}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-smile-o"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Adult Price</label>
                        <div class="input-group">
                            <input type="text" name="tour_adult_price" class="form-control" placeholder="Tour Adult Price" value="{{$tours->tour_adult_price}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-money"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Child Price</label>
                        <div class="input-group">
                            <input type="text" name="tour_child_price" class="form-control" placeholder="Tour Child Price" value="{{$tours->tour_child_price}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-money"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Infant Price</label>
                        <div class="input-group">
                            <input type="text" name="tour_infant_price" class="form-control" placeholder="Tour Max Infant" value="{{$tours->tour_infant_price}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-money"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Thumbnail</label>
                        <div class="input-group">
                            @if($tours->thumbnail_image && ($tours->thumbnail_image != 'blank.jpg'))
                            <img src="/tours/{{$tours->thumbnail_image}}">
                            @endif
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Adult Status</label>
                        <div class="input-group">
                            <input type="checkbox" name="adult_status" value="1" @if($tours->adult_status) checked @endif>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Child Status</label>
                        <div class="input-group">
                            <input type="checkbox" name="child_status" value="1" @if($tours->child_status) checked @endif>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Infant Status</label>
                        <div class="input-group">
                            <input type="checkbox" name="infant_status" value="1" @if($tours->infant_status) checked @endif>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Days</label>
                        <div class="input-group">
                            <input type="number" name="tour_days" class="form-control" placeholder="Tour Days" value="{{$tours->tour_days}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-sun-o"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Nights</label>
                        <div class="input-group">
                            <input type="number" name="tour_nights" class="form-control" placeholder="Tour Nights" value="{{$tours->tour_nights}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-moon-o"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Meta Title</label>
                        <div class="input-group">
                            <input type="text" name="tour_meta_title" class="form-control" placeholder="Tour Meta Title" value="{{$tours->tour_meta_title}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-navicon"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Meta Keywords</label>
                        <div class="input-group">
                            <textarea type="text" name="tour_meta_keywords" class="form-control" cols="55" placeholder="Tour Meta Keywords" readonly>{!! $tours->tour_meta_keywords !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Meta Desc</label>
                        <div class="input-group">
                            <textarea type="text" name="tour_meta_desc" class="form-control" cols="55" placeholder="Tour Meta Desc" readonly>{!! $tours->tour_meta_desc !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Amenities</label>
                        <div class="input-group">
                            <textarea type="text" name="tour_amenities" class="form-control" cols="55" placeholder="Tour Amenities" readonly>{!! $tours->tour_amenities !!}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Exclusions</label>
                        <div class="input-group">
                            <textarea type="text" name="tour_exclusions" class="form-control" cols="55" placeholder="Tour Exclusions" readonly>{!! $tours->tour_exclusions !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Payment Opt</label>
                        <div class="input-group">
                            <textarea type="text" name="tour_payment_opt" class="form-control" cols="55" placeholder="Tour Payment Opt" readonly>{!! $tours->tour_payment_opt !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Privacy</label>
                        <div class="input-group">
                            <textarea type="text" name="tour_privacy" class="form-control" cols="55" placeholder="Tour Privacy" readonly>{!! $tours->tour_privacy !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Status</label>
                        <div class="input-group">
                            <input type="checkbox" name="tour_status" value="Yes" @if($tours->tour_status == 'Yes') checked @endif>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Order</label>
                        <div class="input-group">
                            <input type="number" name="tour_order" class="form-control" placeholder="Tour Order" value="{{$tours->tour_order}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-reorder"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Related</label>
                        <div class="input-group">
                            <input type="number" name="tour_related" class="form-control" placeholder="Tour Related" value="{{$tours->tour_related}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-send-o "></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Common Fixed</label>
                        <div class="input-group">
                            <input type="number" name="tour_comm_fixed" class="form-control" placeholder="Tour Common Fixed" value="{{$tours->tour_comm_fixed}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-expeditedssl"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Common Percentage</label>
                        <div class="input-group">
                            <input type="number" name="tour_comm_percentage" class="form-control" placeholder="Tour Common Percentage" value="{{$tours->tour_comm_percentage}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-bar-chart-o"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Tax Fixed</label>
                        <div class="input-group">
                            <input type="number" name="tour_tax_fixed" class="form-control" placeholder="Tour Tax Fixed" value="{{$tours->tour_tax_fixed}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-credit-card"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Tax Percentage</label>
                        <div class="input-group">
                            <input type="number" name="tour_tax_percentage" class="form-control" placeholder="Tour Tax Percentage" value="{{$tours->tour_tax_percentage}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-bar-chart-o"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Email</label>
                        <div class="input-group">
                            <input type="email" name="tour_email" class="form-control" placeholder="Tour Email" value="{{$tours->tour_email}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-at"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Phone</label>
                        <div class="input-group">
                            <input type="number" name="tour_phone" class="form-control" placeholder="Tour Phone" value="{{$tours->tour_phone}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa    fa-phone"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Website</label>
                        <div class="input-group">
                            <input type="number" name="tour_website" class="form-control" placeholder="Tour Website" value="{{$tours->tour_website}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-database"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Fulladdress</label>
                        <div class="input-group">
                            <input type="email" name="tour_fulladdress" class="form-control" placeholder="Tour Fulladdress" value="{{$tours->tour_fulladdress}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-map-signs"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Tour Featured Forever</label>
                        <div class="input-group">
                            <input type="number" name="tour_featured_forever" class="form-control" placeholder="Tour Featured Forever" value="{{$tours->tour_featured_forever}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa   fa-database"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 form-group">
                        <label class="control-label">Module</label>
                        <div class="input-group">
                            <input type="number" name="module" class="form-control" placeholder="Module" value="{{$tours->module}}" readonly>
                            <div class="input-group-addon">
                                <a href="#"><i class="fa  fa-database"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-12 form-group">
                        <label class="control-label">Tour Description</label>
                        <div class="input-group">
                            <textarea name="tour_desc" id="body" class="form-control" placeholder="Tour Description" readonly>{!! $tours->tour_desc !!}</textarea>

                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="{!! route('admin.tours.edit',$tours->id) !!}" class='btn btn-primary'>{{ trans('general.button.edit') }}</a>
                            <a href="{!! route('admin.tours.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

        $(document).ready(function() {
            $('textarea#body').wysihtml5();
        });

    </script>
    @endsection
