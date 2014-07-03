/**
 *  Runs the tour on page load introducing the app and its functionality.
 */
$(document).ready( function() {

	var tour = new Tour({
	  steps: [
		{
			orphan: true,
			title: "Hi There!",
			content: "This is a little guide to help you get going and making your own epub3 files."
		},
		{
			element: "#chapter-panel",
			placement: 'left',
			title: "Add a Chapter!",
			content: "Click and hold the drag handle and drag a blank chapter to the panel below."
		},	
		{
			element: "#main-editor",
			placement: 'top',
			title: "Edit the Chapter!",
			content: "Once a blank chapter has been dragged into this pane clicking on either 'Chapter Title' or 'Chapter Body' will open an editor."
		},
		{
			element: "#main-editor",
			placement: 'top',
			title: "Move or Delete Chapter!",
			content: "The drag-handle can be used to drag the chapter to a different position (assuming you have more than one chapter in the pane), a chapter can also be deleted by clicking the bin icon."
		},	
		{
			element: "#epub-form-trig",
			placement: 'bottom',
			title: "Create!",
			content: "Clicking this button will open a pop-up asking for the title of the eBook, author name and a cover image (jpeg only)."
		},
		{
			element: "#epub-data-clear",
			placement: 'bottom',
			title: "Clear!",
			content: "Clicking this button will clear all the chapters in the pane below."
		},
		{
			orphan: true,
			title: "Download!",
			content: "After successfully creating your epub3 eBook it should automatically download in your broswer."
		},	
		{
			element: "#more-info-trig",
			placement: 'bottom',
			title: "If Your Curious!",
			content: "You may be the curious sort, if you are you can click here to read more about Epub3."
		},			
		{
			element: "#tour-trig",
			placement: 'bottom',
			title: "Don't Worry!",
			content: "You can view this tour again by clicking this link!"
		},			
	]});

	// Initialize the tour
	tour.init();

	// Start the tour
	tour.start();
	
	$('#tour-trig').click( function() {
		tour.restart(true);
	});	
	
});
