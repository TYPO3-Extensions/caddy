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
var pmuidfieldemail       = ###PMUIDFIELDEMAIL###;
var pmuidfieldrevocation  = ###PMUIDFIELDREVOCATION###;
var pmuidfieldterms       = ###PMUIDFIELDTERMS###;
var t3caddyAlert          = ###T3CADDYALERT###;
var t3caddyConsoleDebug   = ###T3CADDYCONSOLEDEBUG###;


$( document ).on( "click", "button.next", function( e ) {
  accordionApi.next();
});
$( document ).on( "click", "button.prev", function( e ) {
  accordionApi.prev();
});

var fnInit = function( accordionSelector, powermailUid, powermailFormSelector, powermailWallHtmlId ) {
  //fnAccordion( accordionSelector, powermailFormSelector );
  $( this ).t3caddy( 'accordion', accordionSelector, powermailUid, powermailFormSelector, powermailWallHtmlId )
  //addPowermailTabsToCaddy( accordionSelector, powermailUid );
  //movePowermailFormToCaddy( powermailUid );
};

/* Initiate Accordion */
$(function() {
  fnInit( accordionSelector, powermailUid, powermailFormSelector, powermailWallHtmlId );
  accordionApi = $( accordionSelector ).data( "tabs" );
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
console.debug( url );
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
    if( ! initValidator( powermailFormSelector, "validate" ) )
    {
      return;
    }
    formAction  = $( this ).closest( "form" ).attr( "action");
    formData    = $( this ).closest( "form" ).serialize( );
    fnAjax( formAction, formData, e );
  }
}); // User has clicked a tag with the cUID-step class
/* AJAX end */

//  // Add the powermail tabs to the caddy tab powermail
//var addPowermailTabsToCaddy = function( accordionSelector, powermailUid ) {
//  // Get the URL
//  urlWoSearch = $( location ).attr( "protocol" ) + "://" + $( location ).attr( "host" ) + $( location ).attr( "pathname" );
//  urlSearch   = $( location ).attr( "search" );
//  if( urlSearch )
//  {
//    urlSearch = "?" + urlSearch; 
//  }
//  tabs = "";
//  // LOOP all powermail fieldsets
//  $( powermailUid + " div form > fieldset > legend" ).each( function( i ) {
//    href  = urlWoSearch + "#tab-" + i + urlSearch;
//    tabs  = tabs
//          + '<li><a href="' + href + '">' + $( this ).text( ) + '</a></li>'
//          ;
//  }); // LOOP all powermail fieldsets
//  tabs  = '<ul class="css-tabs">'
//        + tabs
//        + '</ul>'
//        ;
//  console.debug( tabs );
//  // Add the powermail tabs to the caddy tab powermail
//  $( tabs ).appendTo( accordionSelector + " div.caddy-powermail" );
//}; // Add the powermail tabs to the caddy tab powermail

//  // Move the powermail form into the caddy to the tab powermail
//var movePowermailFormToCaddy = function( powermailUid, powermailWallHtmlId ) {
//  // Move the powermail form TYPO3 content element to the powermail accordian div
//  $( powermailUid + " > div" ).detach( ).appendTo(accordionSelector + " div.caddy-powermail" );
//  // Remove the default powermail-can't-move-error
//  $( powermailWallHtmlId ).css( "display", "none" );
//  if( $( accordionSelector + " div.caddy-powermail form" ).length )  
//  {
//    // Remove the powermail default h3-header
//    $( accordionSelector + " div.caddy-powermail form h3").remove( );
//    // Add IDs to each fieldset
//    $( accordionSelector + " div.caddy-powermail form > fieldset" ).each( function( i ) {
//      $( this ).attr("id", "tab-" + i );
//    });
//    initPowermailTabs( );
//  }
//};  // Move the powermail form into the caddy to the tab powermail

  // move powermail fields to HTML 5
var movePowermailFieldsToHtml5 = function() {
  // Move e-mail from type=text to type=email
  selectorEmailText   = "input[name='tx_powermail_pi1[field][" + pmuidfieldemail + "]'][type=text]";
  selectorEmailEmail  = "input[name='tx_powermail_pi1[field][" + pmuidfieldemail + "]'][type=email]";
  switch( $( selectorEmailText ).length )
  {
    case( 0 ):
    case( false ):
    case( undefined ):
      if( t3caddyConsoleDebug )
      {
        if( $( selectorEmailEmail ).length != true )
        {
          //alert( "WARNING: The selector " + selectorEmailText + " isn't part of the DOM!");
          console.debug( "The selector " + selectorEmailText + " isn't part of the DOM! This is proper, if powermail form is loaded in confirmation mode.");
        }
      }
      break;
    default:
      marker = $("<span />").insertBefore( selectorEmailText );
      // BE AWARE: Internet Explorer from 6 to 8 will not accept the attr changing!
      $( selectorEmailText ).detach( ).attr( "type","email").insertAfter( marker );
      marker.remove( );
      break;
  } // Move e-mail from type=text to type=email
  // Make checkbox for terms and conditions required and remove hidden field with the same name
  selectorCheckbox  = "input[name='tx_powermail_pi1[field][" + pmuidfieldterms + "][0]'][type=checkbox]";
  selectorHidden    = "input[name='tx_powermail_pi1[field][" + pmuidfieldterms + "][0]'][type=hidden]";
  switch( $( selectorCheckbox ).length )
  {
    case( 0 ):
    case( false ):
    case( undefined ):
      if( t3caddyConsoleDebug )
      {
        //alert( "WARNING: The selector " + selectorCheckbox + " isn't part of the DOM!");
        console.debug( "The selector " + selectorCheckbox + " isn't part of the DOM! This is proper, if powermail form is loaded in confirmation mode or if there isn't any checkbox for terms and conditions.");
      }
      break;
    default:
      // Add required to required checkboxes (PM 2.x didn't set the attribute!)'
      $( selectorCheckbox ).attr( "required", "required" );
      // Remove hidden fields, which are set by PM 2.x: name is double, validator doesn't wor proper'
      $( selectorHidden ).remove( );
      break;
  } // Make checkbox for terms and conditions required and remove hidden field with the same name
  // Make checkbox for revocation required and remove hidden field with the same name
  selectorCheckbox  = "input[name='tx_powermail_pi1[field][" + pmuidfieldrevocation + "][0]'][type=checkbox]";
  selectorHidden    = "input[name='tx_powermail_pi1[field][" + pmuidfieldrevocation + "][0]'][type=hidden]";
  switch( $( selectorCheckbox ).length )
  {
    case( 0 ):
    case( false ):
    case( undefined ):
      if( t3caddyConsoleDebug )
      {
        //alert( "WARNING: The selector " + selectorCheckbox + " isn't part of the DOM!");
        console.debug( "The selector " + selectorCheckbox + " isn't part of the DOM! This is proper, if powermail form is loaded in confirmation mode or if there isn't any checkbox for revocation.");
      }
      break;
    default:
      // Add required to required checkboxes (PM 2.x didn't set the attribute!)'
      $( selectorCheckbox ).attr( "required", "required" );
      // Remove hidden fields, which are set by PM 2.x: name is double, validator doesn't wor proper'
      $( selectorHidden ).remove( );
      break;
  } // Make checkbox for revocation required and remove hidden field with the same name
};  // move powermail fields to HTML 5

///* Powermail tabs begin */
//var initPowermailTabs = function( powermailFormSelector ) {
//  // Configure the tabs of the powermail form
//  $( "ul.css-tabs" ).tabs(
//  powermailFormSelector + " > fieldset.powermail_fieldset",
//  {
//    initialIndex  : 0, // first tab
//    onBeforeClick : function( event, indexTabDest ) {
//      // Get index of the current tab
//      var indexTabSrce = this.getIndex();
//      // If index is undefined, tab is the initial tab
//      if( indexTabSrce == undefined )
//      {
//        // follow the workflow
//        return true;
//      }
//      // Get HTML id of the current tab
//      var idTabSrce = "#tab-" + indexTabSrce + " :input";
//      // Validate HTML input fields of the current tab
//      //alert( idTabSrce );
//      var success = initValidator( idTabSrce, "validate" );
//      // RETURN true : values of the current tab (fieldset) are proper, user can left the current tab
//      if( success )
//      {
//        return true;
//      }
//      // RETURN false : values of the current tab (fieldset) aren't proper, user can't left the current tab
//      return false;
//    }
//  });
//};  // $(function() ...
///* Powermail tabs begin */

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

// initValidator
var initValidator = function( selector, validate, powermailWallHtmlId ) {
  movePowermailFieldsToHtml5( );  
  success = false;
  validatePowermailForm = $( selector ).validator(
  {
    effect          : "wall",
    container       : powermailWallHtmlId,
    lang            : "de",
    // do not validate inputs when they are edited
    errorInputEvent : null
  // custom form submission logic
  }).submit( function( e )  
  {
    // when data is valid
    if( !e.isDefaultPrevented( ) ) 
    {
      // tell user that everything is OK
      //$( powermailWallHtmlId ).html( "<h3>All good</h3>" );
      // prevent the form data being submitted to the server
      //e.preventDefault( );
    }
  }); // $(powermailFormSelector).validator ...
  if( validate == "validate" )
  {
//    alert( $("input[name*='tx_powermail_pi1[field][" + pmuidfieldterms + "]'][type=checkbox]").attr( "type" ) );
//    alert( $("input[name*='tx_powermail_pi1[field][" + pmuidfieldterms + "]'][type=checkbox]").attr( "checked" ) );
    success = validatePowermailForm.data('validator').checkValidity( );
    //alert( success );
  }
  return success;
};  // initValidator

initValidator( powermailFormSelector );

//$("input[name*='tx_powermail_pi1[field][" + pmuidfieldterms + "]'][type=checkbox]").oninvalid(function(event, errorMessage) {
//  alert( "input[name*='tx_powermail_pi1[field][" + pmuidfieldterms + "]'][type=checkbox]: " + errorMessage );
//  // get handle to the API
//  //var api = $(this).data("validator");
//});
//$("input[name*='tx_powermail_pi1[field][" + pmuidfieldemail + "]']").oninvalid(function(event, errorMessage) {
//  alert( "input[name*='tx_powermail_pi1[field][" + pmuidfieldemail + "]']: " + errorMessage );
//  // get handle to the API
//  //var api = $(this).data("validator");
//});