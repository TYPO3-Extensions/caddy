/**
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

var pmuidfieldemail     = ###PMUIDFIELDEMAIL###;
var t3caddyAlert        = ###T3CADDYALERT###;
var t3caddyConsoleDebug = ###T3CADDYCONSOLEDEBUG###;
var currAccordionIndex  = undefined;
var accordionApi        = undefined;


/* Accordion begin */
var fnAccordion = function() {
  $(function() {
    // The accordian panes of the caddy
    $("#c###UID###-accordion").tabs( "#c###UID###-accordion div.pane",
    {
      tabs          : 'h2',
      effect        : 'slide',
      initialIndex  : 0,
      onBeforeClick : function( event, indexAccordionDest ) {
        //alert( "fnAccordion: onBeforeClick" );
        // Get index of the current accordion tab
        var indexAccordionSrce  = this.getIndex();
        currAccordionIndex      = indexAccordionSrce;

        // RETURN if current accordion isn't the powermail pane
        switch( indexAccordionSrce )
        {
          case( 2 ): // Powermail form
            // Follow the workflow
            break;
          default:
            // No evaluation is needed
            // RETURN and follow the users workflow
            //alert( "return true: indexAccordionSrce = " + indexAccordionSrce );
            currAccordionIndex = indexAccordionDest;
            return true;
            break;
        }
        // Are all values proper of the powermail form?
        if( initValidator( "#c###UID###-accordion-powermail form", "validate" ) )
        {
          //alert( "return true: success" );
          // RETURN : all values are proper
          currAccordionIndex = indexAccordionDest;
          return true;
        }
        alert( "Bitte füllen Sie erst das Formular vollständig aus." );
        return false;
      } // onBeforeClick ...
    }); // $("#c###UID###-accordion").panes ...
    accordionApi = $("#c###UID###-accordion").data( "tabs" );
  });
  //  alert( 1 );
}; /* Accordion end */

var fnInit = function( ) {
  fnAccordion( );
  addPowermailTabsToCaddy( );
  movePowermailFormToCaddy( );
};

/* Initiate Accordion */
$(function() {
  fnInit();
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
//$( "#c###UID_POWERMAIL_FORM### div form" ).submit( function( e )  
$( "#c###UID_POWERMAIL_FORM###" ).find( "form" ).submit( function( e )  
{
  formAction  = $( this ).attr( "action");
  formData    = $( this ).serialize( );
  if( !e.isDefaultPrevented( ) ) 
  {
    e.preventDefault( ); // Don't execute the click
    fnAjax( formAction, formData, e );
  }
});
/* AJAX end */

/* Overlay begin */
$(function() {
  // Workaround: Use buttons to initial overlays
  $("button[rel]").overlay({
    mask    : '#000',
    effect  : 'apple'
  });
}); /* Overlay end */

  // Add the powermail tabs to the caddy tab powermail
var addPowermailTabsToCaddy = function() {
  // Get the URL
  urlWoSearch = $(location).attr("protocol") + "://" + $(location).attr("host") + $(location).attr("pathname");
  urlSearch   = $(location).attr("search");
  if( urlSearch )
  {
    urlSerach = "?" + urlSearch; 
  }
  tabs = "";
  // LOOP all powermail fieldsets
  $("#c###UID_POWERMAIL_FORM### div form > fieldset > legend").each(function(i) {
    href  = urlWoSearch + "#tab-" + i + urlSearch;
    tabs  = tabs
          + '<li><a href="' + href + '">' + $(this).text() + '</a></li>'
          ;
  }); // LOOP all powermail fieldsets
  tabs  = '<ul class="css-tabs">'
        + tabs
        + '</ul>'
        ;
  //alert( tabs );
  // Add the powermail tabs to the caddy tab powermail
  $(tabs).appendTo('#c###UID###-accordion div.caddy-powermail');
}; // Add the powermail tabs to the caddy tab powermail

  // Move the powermail form into the caddy to the tab powermail
var movePowermailFormToCaddy = function() {
  // Move the powermail form TYPO3 content element to the powermail accordian div
  $('#c###UID_POWERMAIL_FORM### > div').detach().appendTo('#c###UID###-accordion div.caddy-powermail');
  // Remove the default powermail-can't-move-error
  $('#c###UID###-powermail-prompt').css( "display", "none" );
  if( $( "#c###UID###-accordion div.caddy-powermail form" ).length )  
  {
    // Remove the powermail default h3-header
    $('#c###UID###-accordion div.caddy-powermail form h3').remove();
    // Add IDs to each fieldset
    $("#c###UID###-accordion div.caddy-powermail form > fieldset").each(function(i) {
      $(this).attr("id", "tab-" + i );
    });
    initPowermailTabs();
  }
};  // Move the powermail form into the caddy to the tab powermail

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
  selectorCheckbox  = "input[name='tx_powermail_pi1[field][628][0]'][type=checkbox]";
  selectorHidden    = "input[name='tx_powermail_pi1[field][628][0]'][type=hidden]";
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
  selectorCheckbox  = "input[name='tx_powermail_pi1[field][629][0]'][type=checkbox]";
  selectorHidden    = "input[name='tx_powermail_pi1[field][629][0]'][type=hidden]";
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

/* Powermail tabs begin */
var initPowermailTabs = function() {
  // Configure the tabs of the powermail form
  $("ul.css-tabs").tabs(
  "#c###UID###-accordion-powermail form > fieldset.powermail_fieldset",
  {
    initialIndex  : 0, // first tab
    onBeforeClick : function( event, indexTabDest ) {
      // Get index of the current tab
      var indexTabSrce = this.getIndex();
      // If index is undefined, tab is the initial tab
      if( indexTabSrce == undefined )
      {
        // follow the workflow
        return true;
      }
      // Get HTML id of the current tab
      var idTabSrce = '#tab-' + indexTabSrce + ' :input';
      // Validate HTML input fields of the current tab
      //alert( idTabSrce );
      var success = initValidator( idTabSrce, "validate" );
      // RETURN true : values of the current tab (fieldset) are proper, user can left the current tab
      if( success )
      {
        return true;
      }
      // RETURN false : values of the current tab (fieldset) aren't proper, user can't left the current tab
      return false;
    }
  });
};  // $(function() ...
/* Powermail tabs begin */

/* Validator begin */
$.tools.validator.localize("de", {
  // Isn't localised
  //'*'		: 'Der Wert wird nicht akzeptiert',
  ':email'  	: 'Bitte eine korrekte E-Mail-Adresse eingeben',
  ':number' 	: 'Bitte nur Zahlen eingeben',
  ':url' 	: 'Bitte eine korrekte URL eingeben',
  '[max]'	: 'Maximal $1 ist erlaubt',
  '[min]'	: 'Mindestens $1 ist n&ouml;tig',
  '[required]'	: 'Bitte ausfüllen'
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
      case( "tx_powermail_pi1[field][628][0]"):
      case( "tx_powermail_pi1[field][629][0]"):
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
var initValidator = function( selector, validate ) {
  movePowermailFieldsToHtml5( );  
  success = false;
  validatePowermailForm = $( selector ).validator(
  {
    effect          : 'wall',
    container       : '#c###UID###-powermail-prompt',
    lang            : 'de',
    // do not validate inputs when they are edited
    errorInputEvent : null
  // custom form submission logic
  }).submit( function( e )  
  {
    // when data is valid
    if( !e.isDefaultPrevented( ) ) 
    {
      // tell user that everything is OK
      //$( "#c###UID###-powermail-prompt" ).html( "<h3>All good</h3>" );
      // prevent the form data being submitted to the server
      //e.preventDefault( );
    }
  }); // $("#c###UID###-accordion-powermail form").validator ...
  if( validate == "validate" )
  {
//    alert( $("input[name*='tx_powermail_pi1[field][628]'][type=checkbox]").attr( "type" ) );
//    alert( $("input[name*='tx_powermail_pi1[field][628]'][type=checkbox]").attr( "checked" ) );
    success = validatePowermailForm.data('validator').checkValidity( );
    //alert( success );
  }
  return success;
};  // initValidator

initValidator( "#c###UID###-accordion-powermail form" );

//$("input[name*='tx_powermail_pi1[field][628]'][type=checkbox]").oninvalid(function(event, errorMessage) {
//  alert( "input[name*='tx_powermail_pi1[field][628]'][type=checkbox]: " + errorMessage );
//  // get handle to the API
//  //var api = $(this).data("validator");
//});
//$("input[name*='tx_powermail_pi1[field][" + pmuidfieldemail + "]']").oninvalid(function(event, errorMessage) {
//  alert( "input[name*='tx_powermail_pi1[field][" + pmuidfieldemail + "]']: " + errorMessage );
//  // get handle to the API
//  //var api = $(this).data("validator");
//});