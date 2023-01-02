<link rel="stylesheet" type="text/css" href="/nepali-date-picker/nepali-date-picker.min.css">
<script src='/nepali-date-picker/nepali-date-picker.js'></script>
<script src='/nepali-date-picker/date-conveter.js'></script>
<script src='/nepali-date-picker/toggle-eng-nep-date.js'></script>


<script type="text/javascript">


@if(env('DEFAULT_DATE','NEPALI') == 'NEPALI')

$(function(){



	$('select#nep-eng-date-toogle').val('nep');
	$('select#nep-eng-date-toogle').trigger('change');

});


@endif


</script>

