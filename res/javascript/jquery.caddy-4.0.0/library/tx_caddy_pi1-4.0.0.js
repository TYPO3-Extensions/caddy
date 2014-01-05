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

var accordionApi            = undefined;
var accordionButtonId       = "#accordionPowermailSubmit";
var accordionNext           = "button.accordionNext";       // default: button.accordionNext
var accordionPrev           = "button.accordionPrev";       // default: button.accordionPrev
var accordionSelector       = "#c###UID###-accordion";
var currAccordionIndex      = undefined;
var indexAccordionOrdering  = 3; // Ordering
var indexAccordionPowermail = 4; // Powermail form
var lang                    = '###LANG###';                 // en (default), de
var powermailFormSelector   = "#c###UID###-accordion-powermail div form";
var powermailWallHtmlId     = "#c###UID###-powermail-prompt"
var powermailUid            = "#c###UID_POWERMAIL_FORM###";
var pmuidfieldemail         = parseInt( "###PMUIDFIELDEMAIL###" );
var pmuidfieldrevocation    = parseInt( "###PMUIDFIELDREVOCATION###" );
var pmuidfieldterms         = parseInt( "###PMUIDFIELDTERMS###" );
var t3caddyAlert            = parseInt( "###T3CADDYALERT###" );
var t3caddyConsoleDebug     = parseInt( "###T3CADDYCONSOLEDEBUG###" );

  // lang: if marker isn't replaced, set lang to default language'
switch( true )
{
  case( lang == "default" ):
  case( lang == '###' + 'LANG' + '###' ):
    lang = "en";  // default language
    break;
  default:
    // Do nothing;
    break;
}


function accordion( ) {
  //console.debug( currAccordionIndex );
  return $( this ).t3caddy( "accordion", {
    accordionSelector     : "#c###UID###-accordion",
    currAccordionIndex    : currAccordionIndex,
    lang                  : lang,
    powermailFormSelector : "#c###UID###-accordion-powermail div form",
    powermailWallHtmlId   : "#c###UID###-powermail-prompt",
    powermailUid          : "#c###UID_POWERMAIL_FORM###",
    pmuidfieldemail       : parseInt( "###PMUIDFIELDEMAIL###" ),
    pmuidfieldrevocation  : parseInt( "###PMUIDFIELDREVOCATION###" ),
    pmuidfieldterms       : parseInt( "###PMUIDFIELDTERMS###" ),
    t3caddyAlert          : parseInt( "###T3CADDYALERT###" ),
    t3caddyConsoleDebug   : parseInt( "###T3CADDYCONSOLEDEBUG###" )
  });
};

function powermailValidator( effect ) {
  return $( this ).t3caddy( "validator", {
    container       : "#c###UID###-powermail-prompt",
    effect          : "wall",       // default (default), wall, woPrompt
    errorInputEvent : "null",       // keyup (default), change, blur, null
    lang            : lang,
    selector        : "#c###UID###-accordion-powermail div form",
    validate        : "validate"
  });
};

/* Initiate Accordion */
$(function( ) {  
  accordionApi = accordion( );
}); /* Initiate Accordion */

/* AJAX begin */
var fnAjax = function( formAction, formData, e ) {
  currAccordionIndex = accordionApi.getIndex( );
//  console.debug( currAccordionIndex );
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
//    currAccordionIndex  : currAccordionIndex,
    url                 : formAction, 
    param               : "type=###TYPENUM###"
  });
  //console.debug( url );
//var html_element              = "#c###UID###";
  var html_element              = "#content";
  var html_element_wi_selector  = html_element + " > *";
  accordionApi = $( this ).t3caddy( "update", {
    accordion : {
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
    },
    update : {
      accordionApi              : accordionApi,
      currAccordionIndex        : currAccordionIndex,
      formData                  : formData,
      html_element              : html_element, 
      html_element_wi_selector  : html_element_wi_selector, 
      t3caddyAlert              : parseInt( "###T3CADDYALERT###" ),
      url                       : url
    }
  });

  // Update the content with the id #c###UID###-###VIEW###view
  // Reload functions after content is updated (after 2000 miliseconds)
  setTimeout( function( ) {
    accordionApi = accordion( );
    //console.debug( ".powermail_confirmation_form[type=submit]", $( ".powermail_confirmation_form[type=submit]").length );
    if( $( ".powermail_confirmation_form[type=submit]").length )
    {
      $( ".powermail_confirmation_form[type=submit]").attr( "value", "Ändern");
    }
    if( $( ".powermail_confirmation_submit[type=submit]").length )
    {
      $( ".powermail_confirmation_submit[type=submit]").css( "display", "none");
    }
  }, 2000 );
} 
/* AJAX end */

//accordionApi = $( settings.accordion.accordionSelector ).data( "tabs" );
$( ".powermail_form_30" ).submit( function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    accordionApi.click( indexAccordionPowermail );
    alert( "Hier würde jetzt der AJAX reload starten." );
//    formAction  = $( this ).closest( "form" ).attr( "action");
//    formData    = $( this ).closest( "form" ).serialize( );
//    fnAjax( formAction, formData, e );
  }
});
$( document ).on( "click", accordionNext, function( e ) {
  accordionApi.next();
});
$( document ).on( "click", accordionPrev, function( e ) {
  accordionApi.prev();
});      
$( document ).on( "change", ".onChangeloadCaddyByAjax", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); 
$( document ).on( "click", "a.onClickloadCaddyByAjax", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).attr( "href");
    formData    = null;
    fnAjax( formAction, formData, e );
  }
}); 
$( document ).on( "click", "input.powermail_confirmation_form", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); 
$( document ).on( "click", "input.powermail_confirmation_submit", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); 
$( document ).on( "click", "input.powermail_submit", function( e ) {
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    alert( "Ihre Daten werden zwischengespeichert." );
//    formAction  = $( this ).closest( "form" ).attr( "action");
//    formData    = $( this ).closest( "form" ).serialize( );
//    fnAjax( formAction, formData, e );
  }
});
$( document ).on( "click", accordionButtonId, function( e ) {
        alert( "Jetzt geht die Bestellung los" );
        switch( powermailValidator( "woPrompt" ) )
        {
          case( true ):
            $( accordionButtonId ).removeAttr( "disabled" );
            break;
          case( false ):
          default:
            $( accordionButtonId ).attr( "disabled", "disabled" );
            break;
        }
  selector    = $( "input.powermail_submit" );
//  selector    = $( "input.powermail_confirmation_submit" );
  formAction  = $( selector ).closest( "form" ).attr( "action");
  formData    = $( selector ).closest( "form" ).serialize( );
  fnAjax( formAction, formData, e );
});

/* AJAX end */

$( document ).ready( function( ) {
  if( $( ".powermail_confirmation_form[type=submit]").length )
  {
    $( ".powermail_confirmation_form[type=submit]").attr( "value", "Ändern");
  }
  if( $( ".powermail_confirmation_submit[type=submit]").length )
  {
    $( ".powermail_confirmation_submit[type=submit]").css( "display", "none");
  }
});