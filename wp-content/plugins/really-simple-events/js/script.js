//Javascript for the really-simple-events plugin
//Author: Huntly Cameron
//URL: http://www.huntlycameron.co.uk

//Init the jquery-ui-datepicker
function rse_setupDatePicker(){
    //Set up all date-pickers
    if(jQuery('.date_pick').size()>0){
        jQuery('.date_pick').datetimepicker({
            dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm',
            changeMonth: true,
            changeYear: true
        });
    }
}

//Checks to see if there's no events through ajax deletion and if so,
//updates the UI'
function updateEventCount(){
	var numEvents = 0;
	
	//Find num upcoming and past events
	if(jQuery('#upcoming-events') != undefined){
		numEvents += jQuery('table#upcoming-events tbody tr').size();
	}
	
	if(jQuery('#past-events') != undefined){
		numEvents += jQuery('table#past-events tbody tr').size();
	}
	
	if(numEvents === 0){
		jQuery('p#no-events-mgs').removeClass('hidden');
		jQuery('table#past-events, table#upcoming-events, div#table-switcher, p#no-upcoming').addClass('hidden');
	}
}

jQuery(document).on('click', 'a[href="#upcoming-events"]', function(event){
	event.preventDefault();
	//Hide past events table and show upcoming events table
	jQuery('h2#page-title').text(objectL10n.EventsUpcoming); //'Events (Upcoming)'
	jQuery('table#upcoming-events, #past-events').toggleClass('hidden');
	jQuery('div#table-switcher').html(objectL10n.EventsUpcoming + '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#past-events">' + objectL10n.PastEvents + '</a>');
	//Show no upcoming events msg
	if(jQuery('p#no-upcoming') != undefined) jQuery('p#no-upcoming').removeClass('hidden');
});

jQuery(document).on('click', 'a[href="#past-events"]', function(event){
	event.preventDefault();
	//Hide upcoming events table and show past events table
	jQuery('h2#page-title').text(objectL10n.EventsPast);
	jQuery('table#upcoming-events, #past-events').toggleClass('hidden');
	jQuery("div#table-switcher").html('<a href="#upcoming-events">' + objectL10n.UpcomingEvents + '</a>&nbsp;&nbsp;|&nbsp;&nbsp;' + objectL10n.PastEvents);
	//hide no upcoming events msg
	if(jQuery('p#no-upcoming') != undefined) jQuery('p#no-upcoming').addClass('hidden');
});


jQuery(document).ready(function(){
	rse_setupDatePicker();
		
	jQuery('a.hc_rse_delete').click(function(event){
		event.preventDefault()
		if(confirm(objectL10n.DeleteConfirm)){		
			//ajax remove the event and update the UI
			if(!jQuery('#msgbox').hasClass('hidden')){
				jQuery('#msgbox').addClass('hidden');
			}
			jQuery.get(jQuery(this).attr('href'));
			jQuery(this).parents('tr').remove();
			jQuery('#msgbox').removeClass('hidden');
			updateEventCount();
		}
	});
});

