/**
 *
 * Copyright (c) 2011-2013 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 3.0.1
 *
 * jquery.t3caddy-x.x.x.js is needed
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

var indexOfPowermailAccordion = ###T3CADDYALERT###;
var t3caddyAlert              = ###T3CADDYALERT###;


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
        // Get index of the current accordion tab
        var indexAccordionSrce = this.getIndex();
        // If index is undefined, accordion tab is the initial tab
        if( indexAccordionSrce == undefined )
        {
          // RETURN : follow the users workflow
          return true;
        }
        // Hide possible error prompts of accordion tab 2, if destination accordion tab isn't the 2. tab - the powermail form
        if ( indexAccordionDest != 2 )
        {
          $( "div.error" ).hide();
        }
        // RETURN : follow the users workflow, if destination accordion tab is 'before' the 2. tab or is the 2. tab - the powermail form
        if ( indexAccordionDest <= 2 )
        {
          return true;
        }
        // Are all values proper of the powermail form?
        var success = $("#c###UID###-accordion-powermail form").validator({ inputEvent: 'blur', lang: 'de' }).data('validator').checkValidity();
        if( success )
        {
          // RETURN : all values are proper
          return true;
        }
        // Hide possible error prompts
        $( "div.error" ).hide();
        // RETURN : current tab is the tab of the powermail form
        if ( indexAccordionDest == 2 )
        {
          return false;
        }
        // Select the second tab - the powermail form
        this.click( 2 );
        // Get the current tab (fieldset) of the powermail form
        var indexTabSrce  = $("ul.css-tabs").data("tabs").getIndex();
        var idTabSrce     = '#tabs-' + indexTabSrce + ' :input';
        // Validate HTML input fields of the current tab (fieldset)
        $(idTabSrce).validator({ lang : 'de' }).data('validator').checkValidity();
        // Prompt a message in a lightbox (overflow)
        $("#promptPowermailInvalid").click();
        return false;
      } // onBeforeClick ...
    }); // $("#c###UID###-accordion").panes ...
  });
  //  alert( 1 );
}; /* Accordion end */

var fnInit = function() {
  fnAccordion();
  movePowermailFormToCaddy();
};

/* Initiate Accordion */
$(function() {
  fnInit();
}); /* Initiate Accordion */

/* AJAX begin */
$(document).on("click", ".c###UID###-step", function(e) {
  // User has clicked a tag with the cUID-step class
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
  var url                       = $( this ).t3caddy( 'url_autoQm', $( this ).attr( "href" ), "type=###TYPENUM###" );
  //var html_element              = "#c###UID###";
  var html_element              = "#content";
  var html_element_wi_selector  = html_element + " > *";
  $( this ).t3caddy( 'update', html_element, url, html_element_wi_selector )
  // Update the content with the id #c###UID###-###VIEW###view
  // Reload functions after content is updated (after 2000 miliseconds)
  setTimeout(function() {
    fnInit(); /* Initiate Accordion */
  }, 2000 );
}) // User has clicked a tag with the cUID-step class
/* AJAX end */

/* Overlay begin */
$(function() {
  // Workaround: Use buttons to initial overlays
  $("button[rel]").overlay({
    mask    : '#000',
    effect  : 'apple'
  });
}); /* Overlay end */

var movePowermailFormToCaddy = function() {
  // Move the powermail form TYPO3 content element to the powermail accordian div
  $('#c2995>div').detach().appendTo('#c###UID###-accordion div.caddy-powermail');
  // Remove the default powermail-can't-move-error
  $('#c###UID###-powermail-alert').remove();
  // Remove the powermail default h3-header
  $('#c###UID###-accordion div.caddy-powermail form h3').remove();
  // Add IDs to each fieldset
  $("#c###UID###-accordion div.caddy-powermail form > fieldset").each(function(i) {
    $(this).attr("id", "tab-" + i );
  });
  initPowermailTabs();
};

/* Powermail tabs begin */
var initPowermailTabs = function() {
  // Configure the tabs of the powermail form
  $("#c###UID###-accordion div.caddy-powermail form > fieldset").each(function(i) {
    alert( $(this).("legend") );
  });
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
      var success = $(idTabSrce).validator({ lang : 'de' }).data('validator').checkValidity();
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
  ':email'  	: 'Gib eine korrekte E-Mail-Adresse ab',
  ':number' 	: 'Gib eine Nummer an',
  ':url' 	: 'Gib eine URL an',
  '[max]'	: 'Maximal $1 ist erlaubt',
  '[min]'	: 'Mindestens $1 ist n&ouml;tig',
  '[required]'	: 'Gib einen Wert an'
}); // $.tools.validator.localize ...
/* Validator end */

$("#c###UID###-accordion-powermail form").validator(
{
  inputEvent  : 'blur',
  lang        : 'de'
  //singleError : true // No effect!
}); // $("#c###UID###-accordion-powermail form").validator ...