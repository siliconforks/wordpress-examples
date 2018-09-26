( function ( $ ) {
	var settings = {};
	settings.type = 'POST';
	settings.url = EXAMPLE_AJAX.ajax_url;
	settings.data = {
		action: 'example_ajax',
		x: 123
	};
	settings.dataType = 'text';
	settings.success = function ( data, textStatus, jqXHR ) {
		console.log( 'AJAX SUCCESS' );
		console.log( data );
	};
	settings.error = function () {
		console.log( 'AJAX ERROR' );
	};
	$.ajax( settings );
} )( jQuery );
