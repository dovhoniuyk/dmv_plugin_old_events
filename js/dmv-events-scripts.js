jQuery(document).ready(function($) {

	$('.like_btn a').click(function(e){
		var action = $(this).data('action');
		$.ajax({
			type: 'POST',
			url: myajax.url,
			data: {
				security: myajax.nonce,
				action: 'dmv_like_' + action,
				postID: myajax.postID,
			},
			success: function(res) {
				$('.like_btn').html(res);
			},
			error: function() {
				alert('Error');
			}

		});
		e.preventDefault();
	});
});





