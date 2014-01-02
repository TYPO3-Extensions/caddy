/**
 *
 * tx_caddy_pi1-4.0.0.js
 * 
 * Copyright (c) 2013-2014 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.0.3
 *
 * jquery.t3caddy-x.x.x.js is needed
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

var accordionApi          = undefined;
var accordionSelector     = "#c###UID###-accordion";
var currAccordionIndex    = undefined;
var powermailFormSelector = "#c###UID###-accordion-powermail div form";
var powermailWallHtmlId   = "#c###UID###-powermail-prompt"
var powermailUid          = "#c###UID_POWERMAIL_FORM###";
var pmuidfieldemail       = parseInt( "###PMUIDFIELDEMAIL###" );
var pmuidfieldrevocation  = parseInt( "###PMUIDFIELDREVOCATION###" );
var pmuidfieldterms       = parseInt( "###PMUIDFIELDTERMS###" );
var t3caddyAlert          = parseInt( "###T3CADDYALERT###" );
var t3caddyConsoleDebug   = parseInt( "###T3CADDYCONSOLEDEBUG###" );


/* Initiate Accordion */
$(function( ) {  
  accordionJSONobject =  $( this ).t3caddy( "accordion", {
    accordionSelector     : "#c###UID###-accordion",
    currAccordionIndex    : currAccordionIndex,
    powermailFormSelector : "#c###UID###-accordion-powermail div form",
    powermailWallHtmlId   : "#c###UID###-powermail-prompt",
    powermailUid          : "#c###UID_POWERMAIL_FORM###",
    pmuidfieldemail       : parseInt( "###PMUIDFIELDEMAIL###" ),
    pmuidfieldrevocation  : parseInt( "###PMUIDFIELDREVOCATION###" ),
    pmuidfieldterms       : parseInt( "###PMUIDFIELDTERMS###" ),
    t3caddyAlert          : parseInt( "###T3CADDYALERT###" ),
    t3caddyConsoleDebug   : parseInt( "###T3CADDYCONSOLEDEBUG###" )
  });
  accordionApi        = accordionJSONobject.accordionApi;
  currAccordionIndex  = accordionJSONobject.currAccordionIndex;
}); /* Initiate Accordion */

$( document ).on( "click", "button.next", function( e ) {
  accordionApi.next();
});
$( document ).on( "click", "button.prev", function( e ) {
  accordionApi.prev();
});

/* AJAX begin */
var fnAjax = function( formAction, formData, e ) {
  currAccordionIndex = accordionApi.getIndex( );
  console.debug( currAccordionIndex );
  // User has clicked a tag with the class onChangeloadCaddyByAjax
  e.preventDefault( ); // Don't execute the click
  // RETURN : current id isn't part of the DOM
  //if( ! $( "#c###UID###" ).length )
  if( ! $( "#content" ).length )
  {
    if( t3caddyAlert )
    {
      //alert( "ERROR: The selector \"#c###UID###\" isn't part of the DOM!");
      alert( "ERROR: The selector \"#content\" isn't part of the DOM!");
    }
    return;
  } // RETURN : current id isn't part of the DOM

  // Update the content with the id #c###UID###-###VIEW###view
  var url = $( this ).t3caddy( 'url_autoQm', {
    currAccordionIndex  : currAccordionIndex,
    url                 : formAction, 
    param               : "type=###TYPENUM###"
  });
  console.debug( url );
//var html_element              = "#c###UID###";
  var html_element              = "#content";
  var html_element_wi_selector  = html_element + " > *";
  $( this ).t3caddy( "update", {
    accordionApi              : accordionApi,
    currAccordionIndex        : currAccordionIndex,
    formData                  : formData,
    html_element              : html_element, 
    html_element_wi_selector  : html_element_wi_selector, 
    t3caddyAlert              : parseInt( "###T3CADDYALERT###" ),
    url                       : url
  });
  // Update the content with the id #c###UID###-###VIEW###view
  // Reload functions after content is updated (after 2000 miliseconds)
//  setTimeout( function( ) {
////    accordionIndex = currAccordionIndex;
//    fnInit( ); /* Initiate Accordion */
////    alert( accordionIndex );
////    accordionApi.click( accordionIndex );
//  }, 2000 );
} // User has clicked a tag with the cUID-step class
/* AJAX end */

$( document ).on( "change", ".onChangeloadCaddyByAjax", function( e ) {
  formAction  = $( this ).closest( "form" ).attr( "action");
  formData    = $( this ).closest( "form" ).serialize( );
  fnAjax( formAction, formData, e );
}); // User has clicked a tag with the cUID-step class
$( document ).on( "click", "a.onClickloadCaddyByAjax", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).attr( "href");
    formData    = null;
    fnAjax( formAction, formData, e );
  }
}); // User has clicked a tag with the cUID-step class
$( document ).on( "click", "input.powermail_confirmation_form", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); // User has clicked a tag with the cUID-step class
$( document ).on( "click", "input.powermail_confirmation_submit", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); // User has clicked a tag with the cUID-step class
$( document ).on( "click", "input.powermail_submit", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    if( ! initValidator( powermailFormSelector, "validate", powermailWallHtmlId ) )
    {
      return;
    }
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); // User has clicked a tag with the cUID-step class
/* AJAX end */