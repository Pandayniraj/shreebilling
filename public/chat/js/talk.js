// let objDiv = document.getElementsByClassName("chat-history")[0];

// objDiv.scrollTop = objDiv.scrollHeight;
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').on('click', '#message-data', function (e) {
        var tag, url, id, request;
        tag = $(this);
        if(tag.val().trim() == ''){
            cid= tag.data('conversation-id');
            url = __baseUrl + '/ajax/message/seen/' + cid;
            request = $.ajax({
            method: "post",
            url: url,
            });
            request.done(function(response) {

            });
        }
       
    });

    $('.msg_history').on('scroll', function(e) {
    let  scroll = $(this).scrollTop();
    if(scroll == 0){
        var tag, url, id, request;
        tag = $(this);
        var top=0,start=0;
        let total_message=($(this).attr('data-total-message'));
        let uid=tag.data('receiver');
        let t1 = total_message-21;
        // console.log(t1);
        if(t1>0){
            top = t1
            s1 = t1-20;
            if(s1>0)
                start=s1;
            url = __baseUrl + '/ajax/message/more/' + uid + '/' + start + '/' + top;
            request = $.ajax({
            method: "post",
            url: url,
            });
            request.done(function(response) {

            if (response.status == 'success') {
                $('#chathistory').scrollTop(5);
                $('#chathistory').prepend(response.html);
                console.log(t1);
                $("#chathistory").attr("data-total-message",t1);
                
            }
            });
           } 
        console.log(top,start);
    }
});
});