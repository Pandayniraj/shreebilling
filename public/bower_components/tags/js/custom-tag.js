jQuery(document).ready(function() {



    jQuery("#tags").tagit({
        singleField: true,
        singleFieldNode: $('#mySingleField'),
        allowSpaces: true,
        minLength: 2,
        tagLimit: 5,
        placeholderText: 'type something',

        removeConfirmation: true,
        tagSource: function( request, response ) {
            //console.log("1");
            $.ajax({
                url: "/admin/get_tags_json",
                data: { term:request.term },
                dataType: "json",
                success: function( data ) {
                    response( $.map( data, function( item ) {
                        return {
                            label: item.tag ,
                            value: item.value
                        }
                    }));
                }
            });
        }



    });

});