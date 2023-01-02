
$.fn.nepalidatetoggle = function(){ 
   const adbs = myExtDateConvetorFunction();
   var rootelement = $(this);
   var $selector = rootelement.selector;
    $(document).on('change','#nep-eng-date-toogle',function(){
         let type = $(this).val();
         $($selector).each(function(){

                var toogleParents =$(this).parent();
               
                if(type == 'nep'){
                  // if(toogleParents.find('.nep-date-toggle').length > 0){
                  //   toogleParents.find('.nep-date-toggle').attr('type','text');
                  // }else{
                  $("<input class='nep-date-toggle form-control' type='text' data-single='true'>").insertAfter($(this));
                    
                    
                  $('.nep-date-toggle').nepaliDatePicker();
                  
                   var engdate = $(this).val();
                    
                   if(engdate.length > 0){

                     var translate_date = new Date(engdate);
                     translate_date = translate_date.getFullYear() + '/' +(translate_date.getMonth()+1) + '/' + translate_date.getDate();
                     translate_date = adbs.ad2bs(translate_date);
                     translate_date = translate_date.en;
                     translate_date = translate_date.year + '-' +  translate_date.month + '-' + translate_date.day; 
                     toogleParents.find('.nep-date-toggle').val(translate_date);
       
                   }
                  
                  toogleParents.find($selector).attr('type','hidden');
             
                }else{
                  toogleParents.find('.nep-date-toggle').attr('type','hidden');
                  toogleParents.find($selector).attr('type','text');
                }

         });
                
       });

    $(document).on('change','.nep-date-toggle',function(){
      var parents = $(this).parent();
      let nepdate= $(this).val();

      var translate_date = new Date(nepdate);
      translate_date = translate_date.getFullYear() + '/' +(translate_date.getMonth()+1) + '/' + translate_date.getDate();
      translate_date = adbs.bs2ad(translate_date);
      translate_date = translate_date.year + '-' +  translate_date.month + '-' + translate_date.day; 
      parents.find($selector).val(translate_date);
       
    });

    $('section.content-header:first').append(`<p>
            <div class="form-group">
                <label class="control-label">Select Date Type</label>
            <select id='nep-eng-date-toogle' class="bg-green">
                <option value="eng">English</option>
                <option value="nep">Nepali</option>
            </select>
        </div>
        </p>`);
  

  // var today = new Date('2020-6-26');
  // today = today.getFullYear() + '/' +(today.getMonth()+1) + '/' + today.getDate();

  // console.log(adbs.ad2bs(today));
}



   

