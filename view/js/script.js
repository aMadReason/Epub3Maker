/**
 *  All code included within the ready function below will be triggere don page load or when an event occurs.
 */
$(document).ready( function() {

	// ensures the bootstrap blocks are resized uniformly on page load.
    resizer('.resize-src', '.resize-target', true);	
	
    // enables draggable functionality for blank chapter.
    $( ".element > *" ).draggable({ 
		containment: "body",
		cancel: false,
        helper: "clone", 
        snap: "#main-editor", 
        zIndex: 999, 
        appendTo: "#main-editor", 
        connectToSortable: ".contents", 
        revert: "invalid" ,
        handle: "button.drag-handle"
    });
        
	// enables sortable of chapters added to the main-editor	
    $(".contents").sortable({
            handle: 'button.drag-handle',
            revert: true,
			cancel: false,
            receive: function (event, ui) {      
                if( checkContents() == true) {
                    enableBin();                    
                } 
                callCK( $(this).data().uiSortable.currentItem ); // only rebuild editors on new drop - not on sort      
            }
    }).droppable({ });
	
	// Deletes the chapter when it's bin icon has been clicked
    $("#main-editor").on("click", ".del-handle", function(){
        var check = confirm("Are you sure you want to remove this chapter?");
        if(check === true) {
            $(this).parents('section').remove();
            if( checkContents() == false) {
                $('#epub-form-trig').attr('disabled','disabled');
                $('#epub-data-clear').attr('disabled','disabled');                
            }
        }
    }); 
  
  // deletes all chapters in the main editor on  click
    $(".app-controls").on("click", "#epub-data-clear", function(){
        var check = confirm("Are you sure you want to remove all chapters?");
        if(check === true) {
            $('#main-editor').html('');
            if( checkContents() == false) {
                $('#epub-form-trig').attr('disabled','disabled');
                $('#epub-data-clear').attr('disabled','disabled');                
            }
        }
    });      
    
	
	/**
	 *  This function is fired when the epub data form is 'submitted' it interrupts the submit process to validate the form and 
	 *  add chapter data to the form.
	 */
	var valiidator = $("#epub-data").validate({
		focusInvalid: false,
		rules: {
			epubTitle: { 
				required: true,
				minlength: 1,
				maxlength: 100
			},
			epubAuthor: { 
				required: true,
				minlength: 2,
				maxlength: 100
			},
			epubImage: {
				required: "#epubCustomImage:blank",
				accept: "image/jpg, image/jpeg"
			}
		},
		submitHandler: function(form) {
			// removes appended chapter input ensuring they are not duplicated in subsequent creations	
			$("input.input-chapter").remove(); 
			$("#main-editor").children('section').each( function( index ) {				
				 chapterRaw = $(this).clone().wrap('<section>').parent();					
				 chapter = chapterRaw.find('.controls').remove().end().find('section').removeClass('ui-draggable').addClass('chapter').removeAttr('style').end().html().replace(/'/g, "&#39;");
				$('#epub-data').append("<input name='chapters[]' class='input-chapter' type='hidden' value='"+ chapter +"' /> ");	
			});	
			document.forms["epub-data"].submit();			
			$('#epubForm').modal('hide');
			$('#epubFormSuccess').modal('show');
		},		
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		errorClass: 'help-block'		
	});    
});

/**
 *  Simple function to check some chapter contents has been added
 */
var checkContents = function(  ) {
    if( $('#main-editor section').length!== 0 ) {
        return true;
    } else {
        return false;
    }
};

/**
 *  Simple function to enable the bin icon for each chapter and enable the 'Clear Epub' button
 */
var enableBin = function() {
    $('#epub-form-trig').removeAttr('disabled');
    $('#epub-data-clear').removeAttr('disabled');
    $('#main-editor').find('.del-handle').each( function() {
        $(this).removeAttr('disabled');
    });
};

/**
 *  Simple function for resizing the bootstrap blocks at the top of the page on page load
 */
var resizer = function(targetContainer, sizeTarget, resizeTargetChild ) { 
	// if param not defined set deafult
	if(typeof(resizeTargetChild)==='undefined') resizeTargetChild = false;        
	
	$( targetContainer ).each( function() {
		maxheight = 0; // var to hol max height of targets
		// Loop to find max height
		$( this ).find( sizeTarget ).each( function() {
			var maxheight = ( $(this).height() > maxheight ? $(this).height(): maxheight);
		});

		// apply maxheight to all targets
		$( this ).find( sizeTarget ).each( function() {
			$(this).css('min-height', maxheight-20);
			if (resizeTargetChild === true) {
				// resizes targets immediate child to fit
				$(this).children().css('height', '100%');
			}
		});              
	});            
};
	
/**
 *  The following code has been added in order to ensure the editor will display in-line correctly 
 *   as well as having a dbl click listener
 */
window.onload = function() {
	// Listen to the double click event.
	if ( window.addEventListener )
		document.body.addEventListener( 'dblclick', onDoubleClick, false );
	else if ( window.attachEvent )
		document.body.attachEvent( 'ondblclick', onDoubleClick );

};
		
function onDoubleClick( ev ) {
	// Get the element which fired the event. This is not necessarily the
	// element to which the event has been attached.
	var element = ev.target || ev.srcElement;

	// Find out the div that holds this element.
	var name;

	do {
		element = element.parentNode;
	}
	while ( element && ( name = element.nodeName.toLowerCase() ) &&
		( name != 'div' || element.className.indexOf( 'editable' ) == -1 ) && name != 'body' );

	if ( name == 'div' && element.className.indexOf( 'editable' ) != -1 )
		replaceDiv( element );
}

var editor;
function replaceDiv( div ) {
	if ( editor )
		editor.destroy();
	editor = CKEDITOR.replace( div );
}



