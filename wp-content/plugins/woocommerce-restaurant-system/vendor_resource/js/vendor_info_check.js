( function ( $ ) {
	$( document ).ready( function () {

		//Require post title when adding/editing Project Summaries
		$( 'body' ).on( 'submit.edit-post', '#post', function () {
      var k=0;
			var email=jQuery('#vendor_email').val();
      
			if ( $( "#title" ).val().replace( / /g, '' ).length === 0 ) {
				window.alert( 'Title is required.' );				
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#title" ).focus();
				return false;
			}
      if ( $( "#vendor_name" ).val().replace( / /g, '' ).length === 0 ) {
				window.alert( 'Please enter Restaurant Name.' );				
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#vendor_name" ).focus();
				return false;
			}
      if ( $( "#vendor_email" ).val().replace( / /g, '' ).length === 0 ) {
				window.alert( 'Please enter valid email.' );				
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#vendor_email" ).focus();
				return false;
			}
      /*var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      if(!emailReg.test($email)) {
        window.alert( 'Invalid Email.' );			
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#vendor_email" ).focus();
				return false;
      }  */    
      
      if ( $( "#vendor_address" ).val().replace( / /g, '' ).length === 0 ) {
				window.alert( 'Please enter Restaurant Address.' );				
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#vendor_address" ).focus();
				return false;
			}
      if ( $( "#vendor_country" ).val().replace( / /g, '' ).length === 0 ) {
				window.alert( 'Please enter Country.');				
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#vendor_country" ).focus();
				return false;
			}
      if ( $( "#vendor_percentage" ).val().replace( / /g, '' ).length === 0 ) {
				window.alert( 'Please enter Percentage.');
				// Hide the spinner
				$( '#major-publishing-actions .spinner' ).hide();				
				$( '#major-publishing-actions' ).find( ':button, :submit, a.submitdelete, #post-preview' ).removeClass( 'disabled' );
				$( "#vendor_percentage" ).focus();
				return false;
			}      
		});
	});
}( jQuery ) );