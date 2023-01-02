<script type="text/javascript">
	var _spinningformsubmit = false;
	var _spinningformsubmit_el = false;
	var is_modal_active = false;
	$(function(){
		$(document).on('submit','form',function(){
			_spinningformsubmit = true;
			
			if(!is_modal_active){
				var __submit =   $('section.content').find(':submit');
			}	
			if( __submit && __submit.html() == undefined ){
				if(!is_modal_active){
					var	__submit = $('section.content').find('#btn-submit-edit');
				}
				if(__submit.html() == undefined){
					var	__submit = $('#modal_dialog').find('*[type=submit]');

					if(__submit.html() == undefined){
						var __submit  = $('*[type=submit]');
					}

				}

			}
			_spinningformsubmit_el = __submit;

		})
		$(document).on('click','*[type=submit]',function(){
			_spinningformsubmit = true;
			_spinningformsubmit_el = $(this);

		});
		
	});

window.onbeforeunload = function (e) {
    if(_spinningformsubmit){

    	var __submit = _spinningformsubmit_el;
    	if(__submit.hasClass('no-loading')){
    		return;
    	}

		let submittext =`<i class='fa fa-spinner fa-spin'></i>&nbsp;${__submit.html()}`;			
		__submit.prop('disabled', true);
		__submit.html(submittext);
	}
}


$(document).on('hidden.bs.modal', '#modal_dialog' , function(e){
        is_modal_active = false;
});
$(document).on('shown.bs.modal', '#modal_dialog' , function(e){
        is_modal_active = true;
});

</script>