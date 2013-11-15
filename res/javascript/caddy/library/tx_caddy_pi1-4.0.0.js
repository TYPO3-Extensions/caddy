/**
 *
 * Copyright (c) 2011-2013 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.0.0
 *
 * jquery.t3caddy-x.x.x.js is needed:
 *   http://docs.jquery.com/Plugins/t3caddy
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

var t3caddyAlert = ###T3CADDYALERT###;
  
$( document ).ready( function( )
{
    // User has clicked the record browser
  $( ".c###TT_CONTENT.UID###-recordBrowser" ).live(
    'click',
    function( e ) {
      e.preventDefault( ); // Don't execute the click

        // RETURN : current id isn't part of the DOM
      if( ! $( "#c###TT_CONTENT.UID###-singleview-###MODE###" ).length )
      {
        if( t3caddyAlert )
        {
          alert( "ERROR: The selector \"#c###TT_CONTENT.UID###-singleview-###MODE###\" isn't part of the DOM!");
        }
        return;
      }
        // RETURN : current id isn't part of the DOM
        
        // Update the content with the id #c###TT_CONTENT.UID###-###VIEW###view-###MODE###
      var url                       = $( this ).t3caddy( 'url_autoQm', $( this ).attr( "href" ), "type=###TYPENUM###" );
      var html_element              = "#c###TT_CONTENT.UID###-singleview-###MODE###";
      var html_element_wi_selector  = html_element + " > *";
      $( this ).t3caddy( 'update', html_element, url, html_element_wi_selector );
        // Update the content with the id #c###TT_CONTENT.UID###-###VIEW###view-###MODE###
    }
  );
    // User has clicked the record browser
});