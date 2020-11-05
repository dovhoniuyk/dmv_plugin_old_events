 jQuery(function($){ 
	 $('.load_more').click(function(){
 
		var button = $(this),
		    data = {
			'action': 'loadmore',
			'page' : loadajax.current_page,
			'max_page' : loadajax.max_page	
          
		};
 
		$.ajax({ 
			url : loadajax.ajaxurl, 
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...'); 
			},
			success : function( data ){
				if( data ) { 
					button.text( 'More posts' ).before(data); 
					loadajax.current_page++;
 
					if ( loadajax.current_page == loadajax.max_page ) 
						button.remove(); 

				} else {
					button.remove(); 
			}
		});
	});
});
