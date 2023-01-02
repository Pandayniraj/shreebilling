@extends('layouts.master')

@section('content')
<style type="text/css">
/* * Hide from both screenreaders and browsers: h5bp.com/u */
.hidden {
  display: none !important;
  visibility: hidden;
}

/* * Hide only visually, but have it available for screenreaders: h5bp.com/v */
.visuallyhidden {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

/* * Extends the .visuallyhidden class to allow the element to be focusable * when navigated to via the keyboard: h5bp.com/p */
.visuallyhidden.focusable:active,
.visuallyhidden.focusable:focus {
  clip: auto;
  height: auto;
  margin: 0;
  overflow: visible;
  position: static;
  width: auto;
}

/* * Hide visually and from screenreaders, but maintain layout */
.invisible {
  visibility: hidden;
}

.clearfix:before,
.clearfix:after {
  content: " ";
  /* 1 */
  display: table;
  /* 2 */
}

.clearfix:after {
  clear: both;
}

.noflick, #board, .note, .button {
  -webkit-perspective: 1000;
          perspective: 1000;
  -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
  -webkit-transform: translate3d(0, 0, 0);
          transform: translate3d(0, 0, 0);
}

/* ==========================================================================
   Base styles: opinionated defaults
   ========================================================================== */
* {
  box-sizing: border-box;
}

html,
button,
input,
select,
textarea {
  color: #000000;
}

::-moz-selection {
  background: #B3D4FC;
  text-shadow: none;
}

::selection {
  background: #B3D4FC;
  text-shadow: none;
}

a:focus {
  outline: none;
}

::-webkit-input-placeholder {
  color: rgba(0, 0, 0, 0.7);
}

:placeholder {
  /* Firefox 18- */
  color: rgba(0, 0, 0, 0.7);
}

/* ==========================================================================
   Author's custom styles
   ========================================================================== */
#board {
  padding: 30px 30px 30px;
  overflow-y: visible;
}

.note {
  float: left;
  display: block;
  position: relative;
  padding: 1em;
  width: 300px;
  min-height: 300px;
  margin: 0 30px 30px 0;
 
  box-shadow: 5px 5px 10px -2px rgba(33, 33, 33, 0.3);
 
  transition: -webkit-transform .15s;
  transition: transform .15s;
  transition: transform .15s, -webkit-transform .15s;
  z-index: 1;
}
.modal-body .note{
	width: 600px !important;
}
.modal-body textarea.titlearea{
	 min-height: 90px;
}
.note:hover {
  cursor: move;
}
.note_footer i:hover{
	cursor: pointer;
}
.note.ui-draggable-dragging:nth-child(n) {
  box-shadow: 5px 5px 15px 0 rgba(0, 0, 0, 0.3);
  -webkit-transform: scale(1.125) !important;
          transform: scale(1.125) !important;
  z-index: 100;
  cursor: move;
  -webkit-transition: -webkit-transform .150s;
  transition: -webkit-transform .150s;
  transition: transform .150s;
  transition: transform .150s, -webkit-transform .150s;
}
.note textarea {
  background-color: transparent;
  border: none;
  resize: vertical;
 
  width: 100%;
  padding: 5px;
}
.note textarea:focus {
  outline: none;
  border: none;
  box-shadow: 0 0 5px 1px rgba(0, 0, 0, 0.2) inset;
}
.note textarea.title {
  font-size: 24px;
  line-height: 1.2;
  color: #000000;
   font-family: "Gloria Hallelujah", cursive;
  height: 64px;
  margin-top: 20px;
}
.note textarea.cnt {
  min-height: 200px;
}
.note:nth-child(2n) {
  background: #FAAACA;
}
.note:nth-child(3n) {
  background: #69F098;
}

/* Button style  */
.button {
  font: bold 16px Helvetica, Arial, sans-serif;
  color: #FFFFFF;
  padding: 1em 2em;
  background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, 0.15)), to(rgba(0, 0, 0, 0.3)));
  background: linear-gradient(top, rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.3));
  background-color: #00CC00;
  border-radius: 3px;
  box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3), inset 0 -1px 2px -1px rgba(0, 0, 0, 0.5), inset 0 1px 2px 1px rgba(255, 255, 255, 0.3);
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3);
  text-decoration: none;
  -webkit-transition: background .01s, -webkit-transform .150s;
  transition: background .01s, -webkit-transform .150s;
  transition: transform .150s, background .01s;
  transition: transform .150s, background .01s, -webkit-transform .150s;
}
.button:hover {
  background-color: #00EE00;
  box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.3), inset 0 -1px 2px -1px rgba(0, 0, 0, 0.5), inset 0 1px 2px 1px rgba(255, 255, 255, 0.3);
}
.button:active {
  background: -webkit-gradient(linear, left bottom, left top, from(rgba(0, 0, 0, 0.15)), to(rgba(0, 0, 0, 0.3)));
  background: linear-gradient(bottom, rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.3));
  background-color: #00CC00;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3), 0 -1px 0 rgba(255, 255, 255, 0.3);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.3), inset 0 -1px 2px rgba(255, 255, 255, 0.3);
  outline: none;
}
.button.remove {
  position: absolute;
  top: 5px;
  right: 5px;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #E01C12;
  text-align: center;
  line-height: 16px;
  padding: 10px;
  border-color: #B30000;
  font-style: 1.6em;
  font-weight: bolder;
  font-family: Helvetica, Arial, sans-serif;
}
.button.remove:hover {
  background-color: #EF0005;
}

#add_new {
  float: right;
  z-index: 100;
}

.author {
  position: absolute;
  top: 20px;
  left: 20px;
}
#board ::-webkit-scrollbar {
  width: 3px;
}

/* Track */
 #board::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
#board ::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
#board ::-webkit-scrollbar-thumb:hover {
  background: #555; 
}

</style>

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                &nbsp;&nbsp;&nbsp;Sticky Notes
                <small>{!! $page_description ?? "Page description" !!}</small>
            </h1>
   
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
            <div class="clearfix" style="margin-top: 10px;"></div>
            <div class="input-group pull-left col-sm-3" style="margin-left: 15px;">
              <input type="text" name="" class="form-control " placeholder="Search notes..." id='searchArea' value="{{ \Request::get('term')}}">
              <div class="input-group-addon">
                  <a href="javascript::void()" id='searchDoc'><i class="fa fa-search"></i></a>
              </div>
               <div class="input-group-addon">
                  <a href="#" id='clearSearch'><i class="fa fa-times-circle text-danger"></i></a>
              </div>
            </div>
          <div class="input-group pull-right">

   				<a href="javascript:;" class="btn btn-xs bg-maroon" id="add_new"><i class="fa fa-plus"></i> Add New Note</a>
         
        </div>
        </section>
   <div class='row'>
        <div class='col-md-12'>
	<link href='https://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
		
		<div class="row">
			<div class="col-md-12">
		<div id="board">
			@foreach($notes as $key=>$value)
			<div class="note ui-draggable ui-draggable-handle" @if($value->color) style="background-color: {{ $value->color }}" @else style="background-color: white"@endif data-id='{{ $value->id }}'>
				<span>{{ date('dS M Y h:i A',strtotime($value->created_at)) }}</span>
				<a href="javascript:;" class="button remove"><i class="fa fa-trash"></i></a><div class="note_cnt">
				<textarea class="title titlearea" placeholder="Enter note title" style="height: 64px;">{{ $value->title }}</textarea>
				<textarea class="cnt descriptionarea" placeholder="Enter note description here" style="height: 200px;">{{ $value->description }}</textarea>
				</div>
				<div class="note_footer">
				<ul class="fc-color-picker" id="color-chooser">
                  <li><a style="color: #ECF0F5;" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
               
                </ul>
            </div>
        </div>
        @endforeach
<div class="clearfix"></div>
<div align="center" >{!! $notes->render() !!} </div>

		</div>


@endsection
@section('body_bottom')

   <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">

<script type="text/javascript">
	


var noteTemp =  '<div class="note" style="background-color: white;">'
				+	'<a href="javascript:;" class="button remove">X</a>'
				+ 	'<div class="note_cnt">'
				+		'<textarea class="title titlearea" placeholder="Enter note title"></textarea>'
				+ 		'<textarea class="cnt descriptionarea" placeholder="Enter note description here"></textarea>'
				+	'</div>'
				+	'<div class="note_footer">'
				+   `<ul class="fc-color-picker" id="color-chooser">
                  <li><a style="color: #ECF0F5;" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                 
                </ul>`
				+	'</div>'
				+'</div>';

var noteZindex = 1;

function newNote() {


  $(noteTemp).hide().prependTo("#board").show("fade", 300).draggable({
  	start: function(e){
  			isFullscreen = true;
  		},
	stop: function(e){
		setTimeout(function(){
			isFullscreen = false;
		},100);
			
	}


  }).on('dragstart',
    function(){
       $(this).zIndex(++noteZindex);
    });
	return false; 
};


$(document).on('click','.remove',function(){
	let c = confirm("Are You Sure You Want To Delete Notes");
	var parent = $(this).parent();
	if(c){
		$(this).parent('.note').hide("puff",{ percent: 133}, 250);
		let id = parent.attr('data-id');
		if(id){
			$.post(`stickynote/destroy/${id}`,{},function(response){
				  $('#myModal').modal('hide');
			});
		}
	}
	return false;
})
$(document).ready(function() {


    
    $("#board").height($(document).height());
    
    $("#add_new").click(newNote);
    

    newNote();
	  
    return false;
});
$('.ui-draggable').draggable({
	start: function(e){
  			isFullscreen = true; //stop open modal on dragging
  		},
		stop: function(e){
			setTimeout(function(){
				isFullscreen = false;
			},100);
			
	}
});

function rgb2hex(rgb) {
    var hexDigits = ["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
      return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}
const _token =  $('meta[name="csrf-token"]').attr('content');
function createNote(paraObj,parent){
	paraObj['_token'] = _token;
	$.post('/admin/stickynote/store',paraObj,function(response){
		let data = response.note
		parent.attr('data-id',data.id)
	})
}
function updateNote(paraObj){
	paraObj['_token'] = _token;
	$.post('/admin/stickynote/store',paraObj,function(response){
		console.log("DONE");
	})
}

$(document).on('click','#color-chooser > li > a',function(e){
	e.preventDefault()
      //Save color
      let parent = $(this).parent().parent().parent().parent();
    var  currColor = $(this).css('color');
    currColor = rgb2hex(currColor);
    parent.css({ 'background-color': currColor, 'border-color': currColor })
    var id = parent.attr('data-id');
    if(id){
		paraObj= {
			data: currColor,
			type: 'color',
			id: id,
		}
		updateNote(paraObj)
	}else{
		paraObj= {
			color: currColor,
		}
		createNote(paraObj,parent);
	}
	e.preventDefault();
	return false;
     
})


$(document).on('change','.titlearea',function(){
	var parent = $(this).parent().parent();
	var id = parent.attr('data-id');
	console.log(id);
	var title = $(this).val();
	if(id){
		paraObj= {
			data: title,
			type: 'title',
			id: id,
		}
		console.log(id);
		updateNote(paraObj)
	}else{
		paraObj= {
			title: title,
		}
		createNote(paraObj,parent);
	}

});


$(document).on('change','.descriptionarea',function(){
	var parent = $(this).parent().parent();
	var id = parent.attr('data-id');
	var title = $(this).val();
	if(id){
		paraObj= {
			data: title,
			type: 'description',
			id: id,
		}
		updateNote(paraObj)
	}else{
		paraObj= {
			description: title,
		}
		createNote(paraObj,parent);
	}

});
var isFullscreen = false;
var openedmodal = undefined;
$(document).on('click','.ui-draggable',function(){
	if(!isFullscreen){
		isFullscreen = true;
		openedmodal = $(this);
		$('#myModal .modal-body').append($(this).clone());
		$('#myModal').modal('show');
	}

})
  $(document).on('hidden.bs.modal', '#myModal' , function(e){
  		let html =  $('#myModal .modal-body .note');
  		$('.ui-draggable').draggable({
			start: function(e){
				isFullscreen = true; //stop open modal on dragging
			},	
			stop: function(e){
				setTimeout(function(){
				isFullscreen = false;
				},100);
			}
		});
  		openedmodal.replaceWith(html)
        $('#myModal .modal-body').html('');    
        isFullscreen = false;
        openedmodal = undefined;

   });
  $('#searchDoc').click(function(){
    let term = $('#searchArea').val();
    location.href = `/admin/stickynote/?term=${term}`;
  });
  $('#clearSearch').click(function(){
    location.href = `/admin/stickynote`;
  });


  
</script>
<div id="myModal" class="modal fade" role="dialog">
	  <span style="right: 0;margin-right: 15px;zoom:2;position: absolute; color: white;cursor: pointer;"  data-dismiss="modal" aria-hidden="true">&times;</span>
  <div class="modal-dialog">

    <!-- Modal content-->
  
      
      <div class="modal-body">
      
      </div>
     
    </div>

  </div>
</div>


@endsection