<div class="modal-content">
      <div class="modal-header bg-olive">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title ">Modal Header</h4>
      </div>

      <form action="{{ route('expenses.advanced.report') }}" method="POST">
        @csrf
      <div class="modal-body">

        <div class="row">
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="usr">Start Date:</label>
              <input type="text" class="form-control datepicker" placeholder="Start Date.." name="start_date" >
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="usr">End Date:</label>
              <input type="text" class="form-control datepicker" placeholder="Start Date.." name="end_date">
            </div>
          </div>

        </div>



        <div class="form-group">
          <label for="sel1">Classification: </label>
          <select class="form-control searchable" id='exp_classifications' name="classifications" >
            <option value="">Select Classification</option>
            @foreach($expenseField['classifications'] as $key=>$value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Category: </label>
          <select class="form-control searchable"  id='exp_category' name="category" >
            <option value="">Any</option>
          </select>
        </div>
         <div class="form-group">
          <label for="sel1">CDP: </label>
          <select class="form-control searchable" id='exp_cdp' name="cdp">
            <option value="">Select CDP</option>
           @foreach($expenseField['cdp'] as $key=>$value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Cost Center: </label>
          <select class="form-control searchable"  id='exp_cost_center' name="cost_center">
            <option value="">Any</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Sector: </label>
          <select class="form-control searchable" id='exp_sector'  name="sector">
            <option value="">Select Sector</option>
             @foreach($expenseField['sector'] as $key=>$value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Classes: </label>
          <select class="form-control searchable" id='exp_classes'  name="classes">
           <option value="">Any</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Theme: </label>
          <select class="form-control searchable" id='exp_theme'  name="theme">
            <option value="">Any</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">Pa Activities: </label>
          <select class="form-control searchable" id='exp_pa_activities'  name="pa_activities" >
            <option value="">Select Pa Activities</option>
            @foreach($expenseField['pa_activities'] as $key=>$value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
          </select>
        </div>
         <div class="form-group">
          <label for="sel1">Activities: </label>
          <select class="form-control searchable"  id='exp_activities'  name="activities">
            <option value="">Any</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel1">SOF's: </label>
          <select class="form-control searchable" id='exp_sofs'  name="sofs">
            <option value="">Select SOF's</option>
            @foreach($expenseField['sofs'] as $key=>$value)
            <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
          </select>
        </div>
      
      



      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary" value="csv"><i class="fa   fa-cloud-download"></i> Download CSV</button>
        <button type="submit" class="btn btn-success" value="xlsx"><i class="fa  fa-file-excel-o"></i> Download xlsx</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>


      </form>
    
    </div>



<script type="text/javascript">
  
  $('.searchable').select2();

   $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
    });


   const linkage = {

    'exp_classifications': ['exp_category'],
    'exp_cdp': ['exp_cost_center'],
    'exp_sector': ['exp_classes','exp_theme'],
    'exp_pa_activities': ['exp_activities'],

   }



   $(document).on('change','#exp_classifications , #exp_cdp , #exp_sector , #exp_pa_activities',function(){

    var type = $(this).attr('id');

    let id = $(this).val();

    $.get(`/admin/expenses_get_category/${type}?code=${id}`,function(response){


      for(let c of linkage[type]){

        let cat = response[c];
        var option = '<option value="">Any</option>';
        for(let t of cat){
          option += `<option value="${t.id}">${t.name}</option>`;
        }
        $(`#${c}`).html(option);





      }




    });



   });






</script>