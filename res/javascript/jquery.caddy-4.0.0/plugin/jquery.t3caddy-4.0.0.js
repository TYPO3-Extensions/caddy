/**
 * jquery.t3caddy-4.0.0.js
 *
 * Copyright (c) 2013-2014 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.0.3
 * http://docs.jquery.com/Plugins/t3caddy
 *
 * Copyright (c) 2013-2014 - Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

;(function( $ )
{


  $.fn.t3caddy = function( method )
  {
      var addAccordion = function( accordionSelector, powermailFormSelector ) {
        // The accordian panes of the caddy
        $(accordionSelector).tabs( "div.pane",
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
            switch( true )
            {
              case( indexAccordionDest == indexAccordionOrdering ): // Ordering
              case( indexAccordionSrce == indexAccordionPowermail ): // Powermail form
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
            if( initValidator( powermailFormSelector, "validate" ) )
            {
              //alert( "return true: success" );
              // RETURN : all values are proper
              currAccordionIndex = indexAccordionDest;
              return true;
            }
            this.click( indexAccordionPowermail );
            alert( "Bitte füllen Sie erst das Formular vollständig aus." );
            return false;
          } // onBeforeClick ...
        }
      ); // $(accordionSelector).panes ...
    }; /* accordion */
      // Add the powermail tabs to the caddy tab powermail
    var addPowermailTabsToCaddy = function( accordionSelector, powermailUid ) {
      // Get the URL
      urlWoSearch = $( location ).attr( "protocol" ) + "://" + $( location ).attr( "host" ) + $( location ).attr( "pathname" );
      urlSearch   = $( location ).attr( "search" );
      if( urlSearch )
      {
        urlSearch = "?" + urlSearch; 
      }
      tabs = "";
      // LOOP all powermail fieldsets
      console.debug( powermailUid + " div form > fieldset > legend" );
      $( powermailUid + " div form > fieldset > legend" ).each( function( i ) {
        href  = urlWoSearch + "#tab-" + i + urlSearch;
        tabs  = tabs
              + '<li><a href="' + href + '">' + $( this ).text( ) + '</a></li>'
              ;
      }); // LOOP all powermail fieldsets
      tabs  = '<ul class="css-tabs">'
            + tabs
            + '</ul>'
            ;
      console.debug( tabs );
      // Add the powermail tabs to the caddy tab powermail
      $( tabs ).appendTo( accordionSelector + " div.caddy-powermail" );
    }; // Add the powermail tabs to the caddy tab powermail

    var indexAccordionOrdering  = 4; // Ordering
    var indexAccordionPowermail = 2; // Powermail form
    
      // Fade out the loading *.gif, initiate buttons again
    var clean_up = function( html_element ) {
      $( "#tx-caddy-pi1-loader" ).hide( );
        // Initiate the ui button layout again
      $( "input:submit, input:button, a.backbutton", ".tx-caddy-pi1" ).button( );
    };
      // Fade out the loading *.gif, initiate buttons again
      // Cover the current html element with the loader *.gif
    var cover_wi_loader = function( html_element ) {
      $( "#tx-caddy-pi1-loader" ).css({
        height: $( "#tx-caddy-pi1-loader" ).parent( ).height( ),    
        width:  $( "#tx-caddy-pi1-loader" ).parent( ).width( )    
      });
      $( "#tx-caddy-pi1-loader" ).show( );
    };
      // Cover the current html element with the loader *.gif

      // Prompt errors
    var err_prompt = function( selector, label, prompt ) {
      if( !$( "#update-prompt" ).length ) {
        if( t3caddyAlert )
        {
          alert( label + " " + prompt);
        }
        return;
      }
      element = format( settings.templates.uiErr, label, prompt);
      $( selector ).append( element );
    };
      // Prompt errors

      // Prompt informations
    var inf_prompt = function( selector, label, prompt ) {
      if( !$( "#update-prompt" ).length ) {
        if( t3caddyAlert )
        {
          alert( label + " " + prompt);
        }
        return;
      }
      element = format( settings.templates.uiInf, label, prompt);
      $( selector ).append( element );
    };
      // Prompt informations

      // Replace vars in the source with the given params
    var format = function( source, params ) {
        // ERROR with source
      if( typeof source == "undefined" )
      {
        source =  'ERROR in jquery.t3caddy-4.0.0.js: source is undefined. It seems that there is ' +
                  'a problem with a not defined variable. Function format( source, params ).';
      }
        // ERROR with params
      if( typeof params == "undefined" )
      {
        params = new Array();
        params[0] = 'ERROR in jquery.t3caddy-4.0.0.js:';
        params[1] = 'params are undefined. It seems that there is a problem with a not defined variable. ' +
                    'Function format( source, params ). Please check settings { ... }.';
      }
      if ( arguments.length == 1 )
      {
        return function() {
          var args = $.makeArray(arguments);
          args.unshift(source);
          return $.t3caddy.format.apply( this, args );
        };
      }
      if ( arguments.length > 2 && params.constructor != Array  )
      {
        params = $.makeArray(arguments).slice(1);
      }
      if ( params.constructor != Array )
      {
        params = [ params ];
      }
      $.each(params, function(i, n)
      {
        source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
      });
      return source;
    };
      // Replace vars in the source with the given params

    var settings = {
      messages: {
        errMissingTagPropertyLabel: "Tag is missing:",
        errMissingTagPropertyPrmpt: "A HTML tag with an attribute {0} is missing. AJAX can't work proper!",
        errServerErrorPrmpt:        "This is a message from the server. Maybe the server has some problems in general. " +
                                    "If the server delivers content, you will see the content below this prompts.",
        hlpForumLabel:              "Interactive support:",
        hlpForumPrmpt:              "If you need interactive help, please visit the TYPO3-Caddy-Forum at " +
                                    "<a href=\"http://typo3-browser-forum.de/\">typo3-browser-forum.de</a>. Posts are welcome " +
                                    "in English and German.",
        hlpMissingTagPropertyLabel: "Be aware of a proper HTML template:",
        hlpMissingTagPropertyPrmpt: "Please add something like <div id=\"{0}\">...</div> to your template.",
        hlpPageObjectLabel:         "You can check TYPO3:",
        hlpPageObjectPrmpt:         "Is there a proper page object? Is there a proper typeNum?",
        //hlpAjaxConflictLabel: "Maybe there is a conflict:",
        //hlpAjaxConflictPrmpt: "Don't use AJAX in the single view. See flexform/plugin sheet [jQuery] field [AJAX].",
        hlpUrlLabel:                "You can check the URL manually:",
        hlpUrlPrmpt:                "Click on {0}",
        hlpUrlSelectorLabel:        "Be aware of the jQuery selector:",
        hlpUrlSelectorPrmpt:        "The request takes content into account only if this selector gets a result: {0}",
        hlpGetRidOfLabel:           "Get rid of this messages?",
        hlpGetRidOfPrmpt:           "Deactivate the jQuery plugin t3caddy. But you won't have any AJAX support."
      },
      templates: {
        uiErr : '<div class="ui-widget">' +
                  '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">' +
                    '<p>' +
                      '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' +
                      '<strong>{0}</strong> ' +
                      '{1}' +
                    '</p>' +
                  '</div>' +
                '</div>',
        uiInf : '<div class="ui-widget">' +
                  '<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">' +
                    '<p>' +
                      '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' +
                      '<strong>{0}</strong> ' +
                      '{1}' +
                    '</p>' +
                  '</div>' +
                '</div>'
      }
    };

    var methods = {
      accordion   : function( accordionSelector, powermailUid, powermailFormSelector ) {
                      addAccordion( accordionSelector, powermailFormSelector );
                      addPowermailTabsToCaddy( accordionSelector, powermailUid );
                    }, /* accordion */
      init        : function( settings_ )
                    {
                      return this.each( function( ) {
                          // If settings_ exist, lets merge them with our default settings
                        if ( settings_ ) {
                          $.extend( settings, settings_ );
                        }
                      });
                    }, /* init */
      update      : function( html_element, url, data, html_element_wi_selector )
                    {
                        // update():  replace the content of the given html element with the content
                        //            of the requested url. The content is the content of the html element with selector.

                      return this.each( function ( )
                      {
                          // ERROR html_element is missing. Don't use AJAX but forward
                        if( !$( html_element ).length ) {
                          if( t3caddyAlert )
                          {
                            prompt = format( settings.messages.errMissingTagPropertyPrmpt, html_element);
                            alert( settings.messages.errMissingTagPropertyLabel + " " + prompt );
                            prompt = format( settings.messages.hlpMissingTagPropertyPrmpt, html_element);
                            alert( settings.messages.hlpMissingTagPropertyLabel + " " + prompt );
                            //alert(settings.messages.hlpAjaxConflictLabel + " " + settings.messages.hlpAjaxConflictPrmpt );
                          }
                          fq_url = window.location.protocol + "//" + window.location.host + "/" + url;
                          window.location.href = fq_url;
                          return;
                        }
                          // ERROR html_elementis missing. Don't use AJAX but forward

                          // Fade out the update prompt
                        $("#update-prompt:visible").slideUp( 'fast' );
                        $("#update-prompt div").remove( );

                          // Cover the html_element with the loading *.gif
                        cover_wi_loader( html_element );

                          // Send the AJAX request
                          // Replace the content of the html element with the delivered data
                        var url_wi_selector = url + " " + html_element_wi_selector;
                        $( html_element ).load( url_wi_selector, data, function( response, status, xhr )
                        {
                            // ERROR server has an error and has send a message
                          if (status == "error")
                          {
                              // Add error messages and helpful informations to the update prompt
                            err_prompt( "#update-prompt", xhr.statusText + " (" + xhr.status + "):", settings.messages.errServerErrorPrmpt);
                            inf_prompt( "#update-prompt", settings.messages.hlpPageObjectLabel, settings.messages.hlpPageObjectPrmpt );
                            fq_url = window.location.protocol + "//" + window.location.host + "/" + url;
                            a_fq_url = '<a href="' + fq_url + '">' + fq_url + '</a>';
                            prompt = format( settings.messages.hlpUrlPrmpt, a_fq_url);
                            inf_prompt( "#update-prompt", settings.messages.hlpUrlLabel, prompt );
                            prompt = format( settings.messages.hlpUrlSelectorPrmpt, html_element_wi_selector);
                            //inf_prompt( "#update-prompt", settings.messages.hlpUrlSelectorLabel, prompt );
                            inf_prompt( "#update-prompt", settings.messages.hlpForumLabel, settings.messages.hlpForumPrmpt );
                            inf_prompt( "#update-prompt", settings.messages.hlpGetRidOfLabel, settings.messages.hlpGetRidOfPrmpt );
                              // Add error messages and helpful informations to the update prompt

                              // Fade out the loading *.gif, initiate buttons again
                            clean_up( html_element );
                              // Fade in the update prompt
                            $( "#update-prompt:hidden" ).slideDown( 'fast' );
                            $( "#update-prompt" ).append( response );
                            return;
                          }
                            // ERROR server has an error and has send a message

                            // Fade out the loading *.gif, initiate buttons again
                          clean_up( html_element );
                        });
                          // Send the AJAX request
                      });
                    }, /* update( ) */
      url_autoQm  : function( url, param )
                    {
                        // Concatenate the url and the param in dependence of a question mark.
                        // If url contains a question mark, param will added with ?param
                        // otherwise with &param
                      switch( true )
                      {
                        case( url.indexOf( "?" ) >= 0 ):
                          url = url + "&" + param;
                          break;
                        case( ! ( url.indexOf( "?" ) >= 0 ) ):
                          url = url + "?" + param;
                          break;
                        case( typeof url == "undefined" ):
                        default:
                          url   = "";
                          param = "";
                          break;
                      }
                      return url;
                    } /* url_autoQm */
    }; /* methods */
    
      // Method calling logic
      // See http://docs.jquery.com/Plugins/Authoring#Plugin_Methods
      // Return executed method
    if ( methods[method] )
    {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    }

      // Set default values
    if ( typeof method === "object" || ! method )
    {
      return methods.init.apply( this, arguments );
    }

      // Error: No proper method, no arguments
    $.error( "Method " +  method + " does not exist on jquery.t3caddy-4.0.0.js" );
      // Method calling logic
  };

})( jQuery );

