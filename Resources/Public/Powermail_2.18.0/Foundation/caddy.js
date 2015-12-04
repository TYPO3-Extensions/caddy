
// caddy/Resources/Public/Powermail_2.18.0/Foundation/caddy.js

jQuery( document ).ready( function( $ ) {
  // Open the first tab: set the class of the first section to active
  idOfFirstTab = $( '#caddy_powermail > dl.tabs > dd' ).attr( 'id' );
  idOfFirstTab = '#' + idOfFirstTab;
  $( idOfFirstTab ).addClass( 'active' );
  // Open the fieldset of the first tab: set the class of the first a-tag in the fieldset to active
  idOfFirstFieldset = $( idOfFirstTab + ' > a' ).attr( 'href' );
  $( idOfFirstFieldset ).addClass( 'active' );
  // Reinit foundation abide
  //$( document ).foundation( 'abide', 'events' );          // foundation 4.x
  //$( '#your_form_id' ).foundation( { bindings: 'events' } ); // example foundation 5.x
  // Validate the form
  $( '#caddy_powermail' ).on( 'invalid', function( ) {
    // Remove error class from all dd-tags
    $( 'dl.tabs dd' ).removeClass( 'tab-error' );
    // Get an array of all invalid fields
    var invalid_fields = $( this ).find( '[data-invalid]' );
    // LOOP the array of invalid fields
    $.each( invalid_fields, function( index, invalid_field ) {
      var fieldId = invalid_field.id;
      console.debug( field.id );
      tabId = $( '#' + fieldId ).closest( 'div.content' ).attr( 'name' );
      //console.debug( tabId );
      // tabs with invalid fields get the tab-error class
      $( '#' + tabId ).addClass( 'tab-error' );
    } )
  } ).on( 'valid', function( ) {
    console.debug( 'valid!' );
  } );
} );