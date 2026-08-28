( function( api ) {

	// Extends our custom "rev-digital-products" section.
	api.sectionConstructor['rev-digital-products'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );