<link href="{{ asset ("/bower_components/admin-lte/dist/css/jquery.Jcrop.min.css") }}">
<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/dist/js/jquery.Jcrop.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/dist/js/jquery.color.js") }}"></script>
<style>
#preview-pane {
    background-color: white;
    border: 1px solid rgba(0, 0, 0, 0.4);
    border-radius: 6px;
    box-shadow: 1px 1px 5px 2px rgba(0, 0, 0, 0.2);
    display: block;
    padding: 6px;
    position: absolute;
	bottom: 0;
	right: 0 ;
    z-index: 2000;
	height:160px;
	width:160px;
}
#preview-pane img { width:100%; }
.preview-container {
    height: 148px;
    overflow: hidden;
    width: 148px;
}
#targetdiv { height:400px; width:400px; position:relative; }
#target { height:400px; width:400px; }
</style>
<script type="text/javascript">
  jQuery(function($){
	  
	  <?php
	  	if(!Auth::user()->image)
		{
			echo '$(".jcrop-preview").attr("src", "/images/profiles/default.png"); $(".jcrop-preview").css("display", "block");';
		}
	  ?>

    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
        boundx,
        boundy,
		imgh,
		imgw,

        // Grab some information about the preview pane
        $preview = $('#preview-pane'),
        $pcnt = $('#preview-pane .preview-container'),
        $pimg = $('#preview-pane .preview-container img'),

        xsize = $pcnt.width(),
        ysize = $pcnt.height();
    
    $('#target').Jcrop({
      onChange: updatePreview,
      onSelect: updatePreview,
	  bgFade: true,
      bgOpacity: .5,
      aspectRatio: xsize / ysize
    },function(){
      // Use the API to get the real image size
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;

      // Move the preview into the jcrop container for css positioning
      $preview.appendTo(jcrop_api.ui.holder);
    });

    function updatePreview(c)
    {
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
		
		
		$rcnt = $('.jcrop-holder'),
		rxsize = $rcnt.width(),
		rysize = $rcnt.height();
		
		// If file is selected to upload, get the x, y, h, w according to the image dimension and the crop dimension
		if($("#imageFileInput").val())
		{
			var fileUpload = document.getElementById("imageFileInput");
			var reader = new FileReader();
			reader.readAsDataURL(fileUpload.files[0]);
			reader.onload = function (e) {
				var image = new Image();
				image.src = e.target.result;
				 //Validate the File Height and Width.
				image.onload = function () {
					var height = this.height;
					var width = this.width;
					
					var rrx = width / rxsize;
					var rry = height / rysize;
					
					$('#x').val(rrx * c.x);
					$('#y').val(rry * c.y);
					$('#w').val(rrx * c.w);
					$('#h').val(rry * c.h);
				};
			}
		}
		else
		{
			if($('#real_img').val() != '')
			{
				var image = new Image();
				image.src = $('#real_img').val();
				var height = image.height;
				var width = image.width;
				
				var rrx = width / rxsize;
				var rry = height / rysize;
				
				$('#x').val(rrx * c.x);
				$('#y').val(rry * c.y);
				$('#w').val(rrx * c.w);
				$('#h').val(rry * c.h);
			}
			else
			{
				//alert('No image selected');
			}
		}
      }
    };
	
	function readURL(input) {

		if (input.files && input.files[0]) {
			var reader = new FileReader();	
			reader.onload = function (e) {
				$('#target').attr('src', e.target.result);
				$('.jcrop-holder img').attr('src', e.target.result);
				$('#real_img').attr('value', '');
			}
	
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#imageFileInput").change(function(){
		//$('.crop-section').css('display', 'block');
		$('.jcrop-preview').css('display', 'block');
		$('#real_img').css('display', 'block');
		readURL(this);
	});

  });
</script>


