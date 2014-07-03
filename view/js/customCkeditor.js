/**
 *  Config & Scripting specific to ckeditor 4
 */
 
 //NOTE: CKEDITOT does not load for mobile browsers!
 
// Disables auto inline for all elements but the main editor
CKEDITOR.disableAutoInline = true;

callCKAll =  function() {
	CKEDITOR.instances = new Array(); // destroys all existing instances in prep for regen
	var elements = $( '#main-editor .editable' );
	elements.each( function() {
		CKEDITOR.inline( this, { 
			// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
			 toolbar : [
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ] },
					'/',
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					'/',
					{ name: 'styles', items: [ 'Font', 'FontSize' ] },        
		]});       
      
	});
}

callCK =  function( editableElem ) {
	var element = $(editableElem).children('.editable');
    element.each( function() {
		CKEDITOR.inline( this, { 
			// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
			 toolbar : [
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ] },
					'/',
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					'/',
					{ name: 'styles', items: [ 'Font', 'FontSize' ] },        
		]});      
    });
     
      
}



