

<td>
	
 {{ $expenseField['category']->code  }}

 <input type="hidden" name="exp_category[]" value="{{ $expenseField['category']->id  }}">

</td>

<td>
	
 {{ $expenseField['cost_center']->code  }}

 <input type="hidden" name="exp_cost_center[]" value="{{ $expenseField['cost_center']->id  }}">

</td>


<td>
	
 {{ $expenseField['class']->code  }}

 <input type="hidden" name="exp_class[]" value="{{ $expenseField['class']->id  }}">

</td>



<td>
	
 {{ $expenseField['theme']->name  }}

 <input type="hidden" name="exp_theme[]" value="{{ $expenseField['theme']->id  }}">

</td>


<td>
	
 {{ $expenseField['activities']->name  }}

 <input type="hidden" name="exp_activities[]" value="{{ $expenseField['activities']->id  }}">

</td>


<td>
	
 {{ $expenseField['sof']->name  }}

 <input type="hidden" name="exp_sof[]" value="{{ $expenseField['sof']->id  }}">

</td>