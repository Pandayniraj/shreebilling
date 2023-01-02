<script type="text/javascript">
	




	$(document).on('click','.post-likes-unlike',function(){

    let type = $(this).attr('data-type');
    let pid = $(this).attr('data-pid');
    let tlikes = $(`span#${pid}-likes`);
    var totalLikes = Number(tlikes.text());
    if(type == 'likes'){
      $(this).attr('data-type','unlikes');
      $(this).html(`<i class="fa fa-thumbs-o-down margin-r-5"></i> Dislike`);
      totalLikes++;
    }else{
      $(this).attr('data-type','likes');
      $(this).html(`<i class="fa fa-thumbs-o-up margin-r-5"></i> Like`);
      totalLikes--;
    }
    tlikes.text(totalLikes);
    $.get(`/admin/newsfeeds_like_dislike/${pid}/${type}`,function(){

      console.log("liked");

    });




  });

  
	$(document).on('click','button.post-comment',function(e){

    
      let id = $(this).attr('data-id');
        
      var subject = $(`#${id}-postComment`);
      var paramObj = {};
      var pid = subject.attr('data-id');

      if(subject.val().trim() == ''){
        return 0;
      }

      paramObj['comment'] = subject.val().trim();
      paramObj['_token'] = $('meta[name="csrf-token"]').attr('content');
      $(subject).prop('disabled', true);

      $.post("/admin/newsfeeds_comment/"+pid, paramObj, function(result) {


        $(`#postCommentsList${pid}`).append(result.comment);
        subject.val('');
        let comment = $(`#${pid}-comment`);
        comment.text( Number(comment.text())+1 );
        $(subject).prop('disabled', false);
      });

   

  });




$(document).on('click','.view-all-comments',function(){

    var pid = $(this).attr('data-id');

    $.get(`/admin/all-news-feed-comments/${pid}`,function(response){

        $(`#postCommentsList${pid}`).html( response.html  );

    });

  });


$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();

  const infineScroll = {

     nextPageUrl : '/?page=2',
     canLoad : true,
  }



  function getMorePost(){


    infineScroll.canLoad = false;


    $.get(infineScroll.nextPageUrl,function(response){

      $('#newsfeedsPost').append(response.html);
      let next_page_url = response.newsfeeds.next_page_url;
  
    

      if(next_page_url){

      	infineScroll.nextPageUrl = next_page_url;
      	infineScroll.canLoad = true;

      }else{
      	$('#feedLoding').hide();
      }

      addIntercation();
      addenlargeclass();

    })


  }

  window.onscroll = function(ev) {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
       //User is currently at the bottom of the page
        
       if(infineScroll.canLoad){

          getMorePost();
       }

    }
};




$(document).on('click','.remove-comment',function(){

	let c = confirm('Are You sure You Want to remove');
	if(c){

		let id = $(this).attr('data-id');
		let pid = $(this).attr('data-pid');
		
		let comment = $(`#${pid}-comment`);
		console.log(comment);
		$('#commentbox-'+id).remove();
		comment.text( Number(comment.text()) - 1 );

		$.get(`/admin/remove-feed-comment/${id}`,function(response){
			if(response.success){

				console.log('comment deleted');
			}
		});


	}
});



$(document).on('click','.remove-news',function(){

	let c = confirm('Are You sure You Want to remove');
	if(c){

		let id = $(this).attr('data-id');

		$(`#newsfeedsList-${id}`).hide(300);

		$.get(`/admin/remove-news-feeds/${id}`,function(response){

			if(response.success){

				console.log('Post deleted');
			}
		});
	}


})



});



$('#attachment').change(function(){

  let total =  $(this, this)[0].files.length;

  $('#no_of_file_choosen').text(`${total} files choosen`);
   $('#no_of_file_choosen').show();

  $('i.remove_icons').show();

});

function removeAllFiles(ev){
  $('#attachment').val("");
  ev.style.display = 'none';
  $('#no_of_file_choosen').hide();
  return false;
}


 function addenlargeclass(){
  $('img[data-enlargable]').addClass('img-enlargable').click(function(){
    var src = $(this).attr('src');
    var modal;
    function removeModal(){ modal.remove(); $('body').off('keyup.modal-close'); }
    modal = $('<div>').css({
        background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
        backgroundSize: 'contain',
        width:'100%', height:'100%',
        position:'fixed',
        zIndex:'10000',
        top:'0', left:'0',
        cursor: 'zoom-out'
    }).click(function(){
        removeModal();
    }).appendTo('body');
    //handling ESC
    $('body').on('keyup.modal-close', function(e){
      if(e.key==='Escape'){ removeModal(); } 
    });
});
}

addenlargeclass();


function urlify(text){

	let urlRegex = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;

	let entityMap = {
	'<': '&lt;',
	'>': '&gt;',
	};
	let safehtml = String(text).replace(/[<>]/g, function (s) {
	return entityMap[s];
	});
	let html = safehtml.replace(urlRegex, function(url) {
	return '<a href="' + url + '" target="_blank">' + url + '</a>';
	});
	return html;
}

function addIntercation(){

$('.news_posts .interactive_post').each(function(){
	let text = $(this).text();
	let newtext = urlify(text);
	$(this).html(newtext);
	$(this).removeClass("interactive_post");
});


}
addIntercation();





</script>