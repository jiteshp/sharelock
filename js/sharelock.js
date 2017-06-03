/**
 * Plugin javascript functions.
 *
 * @since 1.0.0
 */
( function( $ ) {
	/**
	 * Wait for the DOM to load.
	 */
	$( document ).ready( function() {
		/**
		 * Initialize the Facebook SDK.
		 */
		$.ajaxSetup( { cache: false } );

		$.getScript( '//connect.facebook.net/en_US/sdk.js', function() {
			FB.init( {
				appId: sharelock.appId,
				version: 'v2.9'
			} );

			FB.AppEvents.logPageView();
		} );

		/**
		 * Handle the share button click.
		 */
		$( '.sharelock-button' ).on( 'click', function( e ) {
			var button = $( this ),
				shareUrl = button.attr( 'data-share-url' ),
				locker = button.parents( '.sharelock' ),
				content = locker.next( '.sharelock-content' );

			FB.ui(
				{
					display: 'popup',
					method: 'share',
					href: shareUrl,
				},
				function( response ) {
					if ( response && ! response.error_message ) {
						locker.hide();
						content.show();
					} else {
						alert( sharelock.error_message );
					}
				}
			);

			e.preventDefault();
		} );
	} );
} ( jQuery ) );
