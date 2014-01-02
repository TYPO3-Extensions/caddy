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


$( document ).on( "click", "button.next", function( e ) {
  accordionApi.next();
});
$( document ).on( "click", "button.prev", function( e ) {
  accordionApi.prev();
});

var fnInit = function( ) {
  //fnAccordion( accordionSelector, powermailFormSelector );
  return $( this ).t3caddy( "accordion", {
    accordion : { 
      accordionSelector     : "#c###UID###-accordion",
      powermailFormSelector : "#c###UID###-accordion-powermail div form",
      powermailWallHtmlId   : "#c###UID###-powermail-prompt",
      powermailUid          : "#c###UID_POWERMAIL_FORM###",
      pmuidfieldemail       : parseInt( "###PMUIDFIELDEMAIL###" ),
      pmuidfieldrevocation  : parseInt( "###PMUIDFIELDREVOCATION###" ),
      pmuidfieldterms       : parseInt( "###PMUIDFIELDTERMS###" ),
      t3caddyAlert          : parseInt( "###T3CADDYALERT###" ),
      t3caddyConsoleDebug   : parseInt( "###T3CADDYCONSOLEDEBUG###" )
    }
  } );
};

/* Initiate Accordion */
$(function() {
  //accordionApi = fnInit( );
  accordionApi =  $( this ).t3caddy( "accordion", {
    accordion : { 
      accordionSelector     : "#c###UID###-accordion",
      powermailFormSelector : "#c###UID###-accordion-powermail div form",
      powermailWallHtmlId   : "#c###UID###-powermail-prompt",
      powermailUid          : "#c###UID_POWERMAIL_FORM###",
      pmuidfieldemail       : parseInt( "###PMUIDFIELDEMAIL###" ),
      pmuidfieldrevocation  : parseInt( "###PMUIDFIELDREVOCATION###" ),
      pmuidfieldterms       : parseInt( "###PMUIDFIELDTERMS###" ),
      t3caddyAlert          : parseInt( "###T3CADDYALERT###" ),
      t3caddyConsoleDebug   : parseInt( "###T3CADDYCONSOLEDEBUG###" )
    }
  } );
}); /* Initiate Accordion */

/* AJAX begin */
var fnAjax = function( formAction, formData, e ) {
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
  var url                       = $( this ).t3caddy( 'url_autoQm', formAction, "type=###TYPENUM###" );
//console.debug( url );
//var html_element              = "#c###UID###";
  var html_element              = "#content";
  var html_element_wi_selector  = html_element + " > *";
  $( this ).t3caddy( 'update', html_element, url, formData, html_element_wi_selector )
  // Update the content with the id #c###UID###-###VIEW###view
  // Reload functions after content is updated (after 2000 miliseconds)
  setTimeout( function( ) {
//    accordionIndex = currAccordionIndex;
    fnInit( ); /* Initiate Accordion */
//    alert( accordionIndex );
//    accordionApi.click( accordionIndex );
  }, 2000 );
} // User has clicked a tag with the cUID-step class
/* AJAX end */

$( document ).on( "change", ".onChangeloadCaddyByAjax", function( e ) {
  formAction  = $( this ).closest( "form" ).attr( "action");
  formData    = $( this ).closest( "form" ).serialize( );
  fnAjax( formAction, formData, e );
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



/* Validator begin */
$.tools.validator.localize("de", {
  // Isn't localised
  //"*"		: "Der Wert wird nicht akzeptiert",
  ":email"  	: "Bitte eine korrekte E-Mail-Adresse eingeben",
  ":number" 	: "Bitte nur Zahlen eingeben",
  ":url" 	: "Bitte eine korrekte URL eingeben",
  "[max]"	: "Maximal $1 ist erlaubt",
  "[min]"	: "Mindestens $1 ist n&ouml;tig",
  "[required]"	: "Bitte ausfüllen"
}); // $.tools.validator.localize ...
/* Validator end */

// adds an effect called "wall" to the validator
$.tools.validator.addEffect( "wall", function( errors, event ) 
{
  // get the message wall
  var wall = $( this.getConf( ).container ).fadeIn( );
  // remove all existing messages
  wall.html( null );
  wall.append( "<h3>Bitte füllen Sie das Formular vollständig aus.</h3>" );
  // add new ones
  $.each( errors, function( index, error ) {
    selector = "input[name='" + error.input.attr("name") + "']";
    switch( error.input.attr("name") )
    {
      case( "tx_powermail_pi1[field][" + pmuidfieldterms + "][0]"):
      case( "tx_powermail_pi1[field][" + pmuidfieldrevocation + "][0]"):
        strAppend = "<p>" + error.messages[0] + ": <strong>" + $( selector ).next( ).text( ) + "</strong></p>"
        break;
      default:
        strAppend = "<p>" + error.messages[0] + ": <strong>" + $( selector ).prev( ).text( ) + "</strong></p>";
        break;
    }
    wall.append( strAppend );
  });
// the effect does nothing when all inputs are valid
}, function( inputs ) 
{
  // remove all existing messages
  $( "#c###UID###-powermail-prompt" ).html( "" );
});