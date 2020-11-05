    jQuery(function($){ 
	 
		
		   var data = {
			action: 'calcul'
        };
        $( document ).ready(function() {
            $.ajax({ 
                url : calajax.ajaxurl, 
                data : data,
                type : 'POST',
                success:function(data1){

                    console.log(JSON.parse(data1));
                    $('#eventCalendar').eventCalendar({
                        jsonData: JSON.parse(data1),
                        jsonDateFormat: 'human',
                        openEventInNewWindow: true,
                    });
                }
            });
        });
 
        
});