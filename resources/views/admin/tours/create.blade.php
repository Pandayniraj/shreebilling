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
                <form method="post" action="{{route('admin.tours.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Title</label>
                            <div class="input-group">
                                <input type="text" name="tour_title" class="form-control" placeholder="Tour Title" required="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-navicon"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Stars</label>
                            <div class="input-group">
                                <input type="number" name="tour_stars" class="form-control" placeholder="Tour Stars" min="1" max="10">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-star"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Featured</label>
                            <div class="input-group">
                                <input type="checkbox" name="tour_is_featured" value="Yes">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Owned By</label>
                            <div class="input-group">
                                <select type="text" name="tour_owned_by" class="form-control select2" style="width:400px;">
                                    <option value="">Select Owner</option>
                                    @foreach($users as $tt)
                                    <option value="{{$tt->id}}">{{$tt->username}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Type</label>
                            <div class="input-group">
                                <select type="text" name="tour_type" class="form-control select2" style="width:400px;">
                                    <option value="">Select Type</option>
                                    @foreach($tourtype as $tt)
                                    <option value="{{$tt->id}}">{{$tt->sett_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Location</label>
                            <div class="input-group">
                                <select type="text" name="tour_location" class="form-control select2" style="width:400px;">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $tt)
                                    <option value="{{$tt->id}}">{{$tt->location}} ({{$tt->country}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Latitude</label>
                            <div class="input-group">
                                <input type="text" name="tour_latitude" class="form-control" placeholder="Tour Latitude">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-arrows"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Longitude</label>
                            <div class="input-group">
                                <input type="text" name="tour_longitude" class="form-control" placeholder="Tour Longitude">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa    fa-arrows-alt"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Mapaddress</label>
                            <div class="input-group">
                                <input type="text" name="tour_mapaddress" class="form-control" placeholder="Tour Mapaddress">
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
                                <input type="text" name="tour_basic_price" class="form-control" placeholder="Tour Basic Price">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa    fa-money"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Basic Discount</label>
                            <div class="input-group">
                                <input type="text" name="tour_basic_discount" class="form-control" placeholder="Tour Basic Discount">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-arrow-down"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Max Adults</label>
                            <div class="input-group">
                                <input type="text" name="tour_max_adults" class="form-control" placeholder="Tour Max Adults">
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
                                <input type="text" name="tour_max_child" class="form-control" placeholder="Tour Max Child">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa     fa-user"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Max Infant</label>
                            <div class="input-group">
                                <input type="text" name="tour_max_infant" class="form-control" placeholder="Tour Max Infant">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-smile-o"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Adult Price</label>
                            <div class="input-group">
                                <input type="text" name="tour_adult_price" class="form-control" placeholder="Tour Adult Price">
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
                                <input type="text" name="tour_child_price" class="form-control" placeholder="Tour Child Price">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-money"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Infant Price</label>
                            <div class="input-group">
                                <input type="text" name="tour_infant_price" class="form-control" placeholder="Tour Max Infant">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-money"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Thumbnail</label>
                            <div class="input-group">
                                <input type="file" name="thumbnail_image">

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Adult Status</label>
                            <div class="input-group">
                                <input type="checkbox" name="adult_status" value="1" checked>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Child Status</label>
                            <div class="input-group">
                                <input type="checkbox" name="child_status" value="1">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Infant Status</label>
                            <div class="input-group">
                                <input type="checkbox" name="infant_status" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Days</label>
                            <div class="input-group">
                                <input type="number" name="tour_days" class="form-control" placeholder="Tour Days">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa    fa-sun-o"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Nights</label>
                            <div class="input-group">
                                <input type="number" name="tour_nights" class="form-control" placeholder="Tour Nights">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-moon-o"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Meta Title</label>
                            <div class="input-group">
                                <input type="text" name="tour_meta_title" class="form-control" placeholder="Tour Meta Title">
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
                                <textarea type="text" name="tour_meta_keywords" class="form-control" cols="55" placeholder="Tour Meta Keywords"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Meta Desc</label>
                            <div class="input-group">
                                <textarea type="text" name="tour_meta_desc" class="form-control" cols="55" placeholder="Tour Meta Desc"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Amenities</label>
                            <div class="input-group">
                                <textarea type="text" name="tour_amenities" class="form-control" cols="55" placeholder="Tour Amenities"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Exclusions</label>
                            <div class="input-group">
                                <textarea type="text" name="tour_exclusions" class="form-control" cols="55" placeholder="Tour Exclusions"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Payment Opt</label>
                            <div class="input-group">
                                <textarea type="text" name="tour_payment_opt" class="form-control" cols="55" placeholder="Tour Payment Opt"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Privacy</label>
                            <div class="input-group">
                                <textarea type="text" name="tour_privacy" class="form-control" cols="55" placeholder="Tour Privacy"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Status</label>
                            <div class="input-group">
                                <input type="checkbox" name="tour_status" value="Yes">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Order</label>
                            <div class="input-group">
                                <input type="number" name="tour_order" class="form-control" placeholder="Tour Order">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-reorder"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Related</label>
                            <div class="input-group">
                                <input type="number" name="tour_related" class="form-control" placeholder="Tour Related">
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
                                <input type="number" name="tour_comm_fixed" class="form-control" placeholder="Tour Common Fixed">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-expeditedssl"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Common Percentage</label>
                            <div class="input-group">
                                <input type="number" name="tour_comm_percentage" class="form-control" placeholder="Tour Common Percentage">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-bar-chart-o"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Tax Fixed</label>
                            <div class="input-group">
                                <input type="number" name="tour_tax_fixed" class="form-control" placeholder="Tour Tax Fixed">
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
                                <input type="number" name="tour_tax_percentage" class="form-control" placeholder="Tour Tax Percentage">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-bar-chart-o"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Email</label>
                            <div class="input-group">
                                <input type="email" name="tour_email" class="form-control" placeholder="Tour Email">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-at"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Phone</label>
                            <div class="input-group">
                                <input type="number" name="tour_phone" class="form-control" placeholder="Tour Phone">
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
                                <input type="number" name="tour_website" class="form-control" placeholder="Tour Website">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Fulladdress</label>
                            <div class="input-group">
                                <input type="email" name="tour_fulladdress" class="form-control" placeholder="Tour Fulladdress">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-map-signs"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <label class="control-label">Tour Featured Forever</label>
                            <div class="input-group">
                                <input type="number" name="tour_featured_forever" class="form-control" placeholder="Tour Featured Forever">
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
                                <input type="number" name="module" class="form-control" placeholder="Module">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa  fa-database"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12 form-group">
                            <label class="control-label">Tour Description</label>
                            <div class="input-group">
                                <textarea name="tour_desc" id="body" class="form-control" placeholder="Tour Description"></textarea>
                            </div>
                        </div>

                    </div>
                    <h4>Tour Extra</h4>

                    <div class="col-md-12">
                        <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore" style="float: right;">
                            <i class="fa fa-plus"></i> <span>Add Extra Item</span>
                        </a>
                    </div>
                    <br>

                     <table class="table table-striped">
                        <thead>
                            <tr class="bg-gray">
                                <th>Extra Name *</th>
                                <th>Price *</th>
                                <th>Action *</th>
                            </tr>
                        </thead>
                        <tbody>
                         <tr class="multipleDiv">
                         </tr>
                     </tbody>
                 </table>


                    <h4>Sub Images</h4>
                    <div class="row">
                        <div class="col-md-12">
                            
                                <label for="image">Image</label>
                                <div class="field_image">
                                    <div> 
                                        <input type="file" name="sub_image[]" value="" class="border border-info p-5 rounded product-images w-75" style="display: inline-block;" />

                                        <a href=" javascript:void(0);" class="add_image btn btn-primary btn-xs" title="Add field"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>  
                           
                        </div>   
                    </div>
<br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.create'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.tours.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="orderFields" style="display: none;">
        <table class="table">
            <tbody id="more-tr">
                <tr>
                    <td>
                        <input type="text" class="form-control" name="extra_name[]" placeholder="Extra Name" required="required">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="extra_price[]" placeholder="Price" value="" required="required">
                    </td>
                    <td>
                        <a href="javascript::void(1);" style="width: 10%;">
                            <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

        $(document).ready(function() {
            $('textarea#body').wysihtml5();
        });

        $("#addMore").on("click", function() {
            $(".multipleDiv").after($('#orderFields #more-tr').html());
         });

        $(document).on('click', '.remove-this', function() {
            $(this).parent().parent().parent().remove();
       
        });
    </script>

    <script>
        $(document).ready(function() {
            var maxField = 10; //Input fields increment limitation
            var addImage = $('.add_image'); //Add button selector
            var imageWrapper = $('.field_image'); //Input field imageWrapper
            var imageFieldHTML = '<div class="p-10"><input type="file" name="sub_image[]" value="" class="border border-info p-5 rounded product-images w-75 mr-1" style="display: inline-block;"/><a href="javascript:void(0);" class="remove_image btn btn-danger" style="padding:1px 5px;"><i class="fa fa-trash"></i></a></div>'; //New input field html 
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addImage).click(function() {
                //Check maximum number of input fields
                if (x < maxField) {
                    x++; //Increment field counter
                    $(imageWrapper).append(imageFieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(imageWrapper).on('click', '.remove_image', function(e) {
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });

    </script>
    @endsection
