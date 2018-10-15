(function($) {

	// Clone social form field
	var form = $('#form-edit'),
		addMore = form.find('.form-add-more a');

	addMore.on('click', function( event ) {
		event.preventDefault();

		form.find('.form-social-wrap:first')
			.clone()
			.insertBefore(
				form.find('.form-add-more')
			)
			.find('.form-control')
			.val('');
	});

	// Show page status and hide
	// We want to see the error message until the user corrects the data
	// But we want to hide success message almost immediately
	$('#page-status-content').delay(500).fadeIn('normal', function() {
		if ( $(this).hasClass('page-status-success') ) $(this).delay(2500).fadeOut();    
	 });

}(jQuery));