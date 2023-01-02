@extends('layouts.reportmaster')
@section('content')

<h2>Customers List</h2>
      <table class="table table-striped">
        <thead>
          <tr>
          <th class="no">id</th>
          <th class="no">Name</th>
          <th class="no">Phone</th>
          <th class="no">Location</th>
          <th class="no">Email</th>
          <th class="no">Website</th>
          <th class="no">Active</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $n= 0;
          ?>
          @foreach($orders as $odk => $o)
          <tr>
              <td>{!! $o->id !!}</td>
              <td>{!! $o->name !!}</td>
              <td>{!! $o->phone !!}</td>
              <td>{!! $o->location !!}</td>
              <td>{!! $o->email !!}</td>
              <td>{!! $o->website !!}</td>
              <td>{!! $o->enabled !!}</td>

          </tr>
         @endforeach
          
        </tbody> 
       
      </table>
@endsection