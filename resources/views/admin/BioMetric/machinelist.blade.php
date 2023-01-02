@extends('layouts.master')
@section('content')
<style>
  #leads-table td:first-child{text-align: center !important;}
  #leads-table td:nth-child(2){font-weight: bold !important;}
  #leads-table td:last-child a {margin:0 2px;}
  tr { text-align:center; }

    #nameInput, #productInput, #statusInput, #ratingInput {
        background-image: url('/images/searchicon.png'); /* Add a search icon to input */
        background-position: 10px 12px; /* Position the search icon */
        background-repeat: no-repeat; /* Do not repeat the icon image */
        font-size: 16px; /* Increase font-size */
        padding: 12px 12px 12px 40px; /* Add some padding */
        border: 1px solid #ddd; /* Add a grey border */
        margin-bottom: 12px; /* Add some space below the input */
        margin-right: 5px;
    }

    tr {
            text-align: left !important;
        }

        h4{ margin: 0px !important; }

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                Biometric Device
                <small>{!! $page_description ?? "Add Devices" !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
<form action="/admin/ActivateDevice" id="ActivateDevice">
    <div class="box box-primary">
        <div class="box-header with-border">
<div class='row'>
        <div class='col-md-12'>
        	<a class="btn btn-success btn-sm"  title="Import/Export Leads" onclick="document.getElementById('ActivateDevice').submit()">
                            <i class="fa fa-check"></i>&nbsp;<strong> Activate Device </strong>
                        </a>
        </div>
    </div>
</div>
   <div class="">
                            <table class="table table-hover table-no-border" id="leads-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;width:20px !important">
                                            <a class="btn" href="#" onclick="toggleCheckbox(); return false;" title="{{ trans('general.button.toggle-select') }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                        </th>
                                        <th> S.N</th>
                                        
                                        <th>Device Name</th>
                                        <th>Ip Address</th>
                                        <th>Serial Number</th>
                                        <th>Descriptions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                	@foreach($availableMachine as $key=>$machine)
                                	<tr>
                                        @if($machine->isActive)
                                		<td class="bg-info">
                                			<input type="radio" name="device_id" value="{{$machine->id}}" checked>
                                        </td>
                                        <td class="bg-info">{{$key + 1 }}</td>
                                        <td class="bg-info">{{$machine->device_name}}</td>
                                        <td class="bg-info">{{$machine->ip_address}}</td>
                                        <td class="bg-info">{{$machine->serial_number}}</td>
                                        <td class="bg-info">{{$machine->description}}</td>
                                        @else
                                        <td>
                                            <input type="radio" name="device_id" value="{{$machine->id}}" >
                                        </td>
                                        <td >{{$key + 1 }}</td>
                                        <td >{{$machine->device_name}}</td>
                                        <td >{{$machine->ip_address}}</td>
                                        <td >{{$machine->serial_number}}</td>
                                        <td>{{$machine->description}}</td>
                                        @endif
                                        <td>
                                        @if ( $machine->isEditable()  )
                                            <a href="{!! route('editdevice', $machine->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
                                        @else
                                            <i class="fa fa-edit text-muted" title="{{ trans('admin/cases/general.error.cant-edit-this-document') }}"></i>
                                        @endif
                                        @if ( $machine->isDeletable() )
                                            <a href="{!! route('device.confirm-delete', $machine->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
                                        @else
                                            <i class="fa fa-trash text-muted" title="{{ trans('admin/cases/general.error.cant-delete-this-document') }}"></i>
                                        @endif
                                    </td>
                                    </tr>
                                    @endforeach
                               
                                </tbody>
                            </table>

                          

                        </div>
</div>
</form>

@endsection