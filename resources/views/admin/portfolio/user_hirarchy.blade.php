@extends('layouts.master')

@section('head_extra')

<style type="text/css">
	#chart-container {
  font-family: Arial;

  border: 2px dashed #aaa;
  border-radius: 5px;
  overflow: auto;
  text-align: center;
}

#github-link {
  position: fixed;
  right: 10px;
  font-size: 3em;
}
</style>
@section('content')

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
  <h1>
               User hierarchy
                <small>User hirearchy for {{ $user->first_name }} {{ $user->last_name }}</small>
            </h1>

        

</section>
<link rel="stylesheet" type="text/css" href="/jorg/jorg.css">
<div class="row row-xs">
			<div class="panel">
						<div id="chart-container"></div>
				
		</div>
	</div>


<script src="/jorg/jorg.js"></script>


	<script type="text/javascript">
		$(function () {
		var nodeTemplate = function(data) {
		    return `<a href="#">
						<div align="center">
							<img class="media-object img-circle" height="40" width="40" src="${data.image}">
						</div>
						<div class="title">${data.name}</div>
						<div class="content" style='max-height: 50px !important'>
							<div >
							<b></b>${data.designation}<br>
							<b></b>${data.department}<br>
							</div>
						</div>
					</a>`;
		};
		var orgChart = $('#chart-container').orgchart({
			data : get_data(null),
			depth: 10,
			nodeContent: 'title',
			nodeTemplate: nodeTemplate,
			pan: true,
			zoom: true
		});

		function get_data(dept_id){
		    return $.ajax({
			url: "/admin/get_emp_hirarchy/{{ $user->id }}",
			type:'GET',
			data: {'dept_id': dept_id},
			dataType: "json",
			cache: false,
			async: false}).responseJSON;
		}
	});
	</script>


@endsection