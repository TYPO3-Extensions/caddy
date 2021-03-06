/**
 * jquery.t3caddy-4.0.0.js
 *
 * Copyright (c) 2013-2015 - Dirk Wildt (Die Netzmacher)
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.0.3
 * http://docs.jquery.com/Plugins/t3caddy
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

;(function( $ )
{


  $.fn.t3caddy = function( method )
  {
    function addAccordion( ) {
      // The accordian panes of the caddy
      initialIndex = parseInt( settings.accordion.currAccordionIndex );
      if( isNaN( initialIndex ) )
      {
        initialIndex = 0;
      }
      //console.debug( initialIndex );
      $( settings.accordion.accordionSelector ).tabs( "div.pane",
      {
        tabs          : settings.accordion.tabs,
        effect        : settings.accordion.effect,
        initialIndex  : initialIndex,
        onBeforeClick : function( event, indexAccordionDest ) {
          accordionButtonId = settings.accordion.accordionButtonId;
          $( accordionButtonId ).attr( "disabled", "disabled" );
          //alert( "fnAccordion: onBeforeClick" );
          // Get index of the current accordion tab
          var indexAccordionSrce  = this.getIndex();
          settings.accordion.currAccordionIndex = indexAccordionSrce;

          switch( true )
          {
            // RETURN true, if destination accordion pane is the powermail pane
            case( indexAccordionDest == indexAccordionPowermail ): // Powermail form
              settings.accordion.currAccordionIndex = indexAccordionDest;
              return true;
              break;
            // Follow the workflow, if source accordion pane is the powermail pane
            case( indexAccordionSrce == indexAccordionPowermail ): // Powermail form
              // Follow the workflow
              break;
            default:
              // No evaluation is needed
              // RETURN and follow the users workflow
              //alert( "return true: indexAccordionSrce = " + indexAccordionSrce );
              settings.accordion.currAccordionIndex = indexAccordionDest;
//              console.debug( settings.accordion.currAccordionIndex );
              return true;
              break;
          }
          settings.validator.effect           = "woPrompt";
          settings.validator.errorInputEvent  = "null";
          settings.validator.inputEvent       = "change";
          settings.validator.lang             = settings.accordion.lang;
          settings.validator.selector         = settings.accordion.powermailFormSelector;
          settings.validator.validate         = "validate";
          switch( initValidator( ) )
          {
            case( true ):
              $( accordionButtonId ).removeAttr( "disabled" );
              break;
            case( false ):
            default:
              $( accordionButtonId ).attr( "disabled", "disabled" );
              break;
          }
        } // onBeforeClick ...
      }); // $(settings.accordion.accordionSelector).panes ...
    } /* accordion */

    // Add the powermail tabs to the caddy tab powermail
    function addPowermailTabsToCaddy( ) {
      // Get the URL
      urlWoSearch = $( location ).attr( "protocol" ) + "://" + $( location ).attr( "host" ) + $( location ).attr( "pathname" );
      urlSearch   = $( location ).attr( "search" );
      if( urlSearch )
      {
        urlSearch = "?" + urlSearch; 
      }
      tabs = "";
      // LOOP all powermail fieldsets
      $( settings.accordion.powermailUid + " div form > fieldset > legend" ).each( function( i ) {
        href  = urlWoSearch + "#tab-" + i + urlSearch;
        tabs  = tabs
              + '<li><a href="' + href + '">' + $( this ).text( ) + '</a></li>'
              ;
      }); // LOOP all powermail fieldsets
      tabs  = '<ul class="css-tabs">'
            + tabs
            + '</ul>'
            ;
      // Add the powermail tabs to the caddy tab powermail
      $( tabs ).appendTo( settings.accordion.accordionSelector + " div.caddy-powermail" );
    } // Add the powermail tabs to the caddy tab powermail

      // Fade out the loading *.gif, initiate buttons again
    function clean_up( html_element ) {
      $( "#tx-caddy-pi1-loader" ).hide( );
        // Initiate the ui button layout again
      $( "input:submit, input:button, a.backbutton", ".tx-caddy-pi1" ).button( );
    } // Fade out the loading *.gif, initiate buttons again
    
      // Cover the current html element with the loader *.gif
    function cover_wi_loader( html_element ) {
      $( "#tx-caddy-pi1-loader" ).css({
        height: $( "#tx-caddy-pi1-loader" ).parent( ).height( ),    
        width:  $( "#tx-caddy-pi1-loader" ).parent( ).width( )    
      });
      $( "#tx-caddy-pi1-loader" ).show( );
    }
      // Cover the current html element with the loader *.gif

      // Prompt errors
    function err_prompt( selector, label, prompt ) {
      if( !$( "#update-prompt" ).length ) {
        if( t3caddyAlert )
        {
          alert( label + " " + prompt);
        }
        return;
      }
      element = format( settings.templates.uiErr, label, prompt);
      $( selector ).append( element );
    } // Prompt errors

    // Replace vars in the source with the given params
    function format( source, params ) {
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
    }
      // Replace vars in the source with the given params

      // Prompt informations
    function inf_prompt( selector, label, prompt ) {
      if( !$( "#update-prompt" ).length ) {
        if( t3caddyAlert )
        {
          alert( label + " " + prompt);
        }
        return;
      }
      element = format( settings.templates.uiInf, label, prompt);
      $( selector ).append( element );
    }
      // Prompt informations

    /* Powermail tabs begin */
    function initPowermailTabs( ) {
      // Configure the tabs of the powermail form
      $( "ul.css-tabs" ).tabs( settings.accordion.powermailFormSelector + " > fieldset.powermail_fieldset",
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
          accordionButtonId = settings.accordion.accordionButtonId;
          // Get HTML id of the current tab
          var idTabSrce = "#tab-" + indexTabSrce + " :input";
          // Validate HTML input fields of the current tab
          //alert( idTabSrce );
          settings.validator.effect           = "woPrompt";
          settings.validator.errorInputEvent  = "null";
          settings.validator.inputEvent       = "keyup";
          settings.validator.lang             = settings.accordion.lang;
          settings.validator.selector         = settings.accordion.powermailFormSelector;
          settings.validator.validate         = "validate";
          //console.debug( "A" );
          valid = initValidator( );
          switch( valid )
          {
            case( true ):
              $( accordionButtonId ).removeAttr( "disabled" );
              break;
            case( false ):
            default:
              $( accordionButtonId ).attr( "disabled", "disabled" );
              break;
          }
          settings.validator.effect           = "wall";
          settings.validator.errorInputEvent  = "null";
          settings.validator.inputEvent       = "null";
          settings.validator.lang             = settings.accordion.lang;
          settings.validator.selector         = idTabSrce;
          settings.validator.validate         = "validate";
          //console.debug( "B" );
          valid = initValidator( );
          //console.debug( "C" );
          return valid;
        }
      });
    }  // $(function() ...
    /* Powermail tabs begin */

    function initToolsValidator( ) {
//      $.tools.validator.localize( "de", {
//        // "*" Isn't localised
//        //"*"           : "Der Wert wird nicht akzeptiert",
//        ":email"      : "Bitte eine korrekte E-Mail-Adresse eingeben",
//        ":number"     : "Bitte nur Zahlen eingeben",
//        ":url"        : "Bitte eine korrekte URL eingeben",
//        "[max]"       : "Maximal $1 ist erlaubt",
//        "[min]"       : "Mindestens $1 ist n&ouml;tig",
//        "[required]"  : "Bitte ausfüllen"
//      }); // $.tools.validator.localize ...
      $.tools.validator.addEffect( "wall", function( errors, event ) 
      {
        pmuidfieldterms       = settings.accordion.pmuidfieldterms;
        pmuidfieldrevocation  = settings.accordion.pmuidfieldrevocation;
        // get the message wall
        var wall = $( this.getConf( ).container ).fadeIn( );
        // remove all existing messages
        wall.html( null );
        wall.append( "<h3>" + settings.messages.powermail.fillFormCompletely + "</h3>" );
        // add new ones
        $.each( errors, function( index, error ) {
          selector = "input[name='" + error.input.attr( "name" ) + "']";
          switch( error.input.attr( "name" ) )
          {
            case( "tx_powermail_pi1[field][" + pmuidfieldterms + "][0]"):
            case( "tx_powermail_pi1[field][" + pmuidfieldrevocation + "][0]"):
              idPmFieldwrapCheck = $( selector ).closest( "div.powermail_fieldwrap_check" ).attr( "id" );
              idOfFieldset = $( "#" + idPmFieldwrapCheck ).closest( "fieldset" ).attr( "id" );
              legend       = $( "#" + idOfFieldset + " > legend" ).text( );
              strAppend = "<p>" + error.messages[0] + ": <strong>[" + legend + "] " + $( selector ).next( ).text( ) + "</strong></p>"
              break;
            default:
              idOfFieldset = $( selector ).closest( "fieldset" ).attr( "id" );
              legend       = $( "#" + idOfFieldset + " > legend" ).text( );
              strAppend = "<p>" + error.messages[0] + ": <strong>[" + legend + "] " + $( selector ).prev( ).text( ) + "</strong></p>";
              break;
          }
          wall.append( strAppend );
        });
      // the effect does nothing when all inputs are valid
      }, function( inputs ) // 140105, dwildt: never remove this function!
      {
        // remove all existing messages
        $( settings.accordion.powermailWallHtmlId ).html( "" );
      }); // adds the "wall" effect to the validator      
      // adds the "woPrompt" effect to the validator
      $.tools.validator.addEffect( "woPrompt", function( errors, event ) 
      {
        //alert( "woPrompt" );
        /* effect is needed for enabling and disabling submit button only */
      }, function( inputs ) // 140105, dwildt: never remove this function!
      {
      }); // adds the "woPrompt" effect to the validator      
    } // initToolsValifator

    function initValidator( ) {
      options = {
        container : settings.accordion.powermailWallHtmlId
      };
      valid =  methods.validator( options );
      return valid;
    }  // initValidator( )

    function movePowermailFieldsToHtml5( ) {
      pmuidfieldemail     = settings.accordion.pmuidfieldemail;
      t3caddyConsoleDebug = settings.accordion.t3caddyConsoleDebug;
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
              console.debug(    "The selector " + selectorEmailText + " isn't part of the DOM! " 
                              + "This is proper, if powermail form is loaded in confirmation mode.");
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
            console.debug(    "The selector " + selectorCheckbox + " isn't part of the DOM! "
                            + "This is proper, if powermail form is loaded in confirmation mode or " 
                            + "if there isn't any checkbox for terms and conditions.");
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
            console.debug(    "The selector " + selectorCheckbox + " isn't part of the DOM! " 
                            + "This is proper, if powermail form is loaded in confirmation mode or if there isn't any checkbox for revocation.");
          }
          break;
        default:
          // Add required to required checkboxes (PM 2.x didn't set the attribute!)'
          $( selectorCheckbox ).attr( "required", "required" );
          // Remove hidden fields, which are set by PM 2.x: name is double, validator doesn't wor proper'
          $( selectorHidden ).remove( );
          break;
      } // Make checkbox for revocation required and remove hidden field with the same name
    }  // move powermail fields to HTML 5

    // Move the powermail form into the caddy to the tab powermail
    function movePowermailFormToCaddy( ) {
      // Move the powermail form TYPO3 content element to the powermail accordian div
      $( settings.accordion.powermailUid + " > div" ).detach( ).appendTo(settings.accordion.accordionSelector + " div.caddy-powermail" );
      // Remove the default powermail-can't-move-error
      $( settings.accordion.powermailWallHtmlId ).css( "display", "none" );
      if( $( settings.accordion.accordionSelector + " div.caddy-powermail form" ).length )  
      {
        // Remove the powermail default h3-header
        $( settings.accordion.accordionSelector + " div.caddy-powermail form h3").remove( );
        // Add IDs to each fieldset
        $( settings.accordion.accordionSelector + " div.caddy-powermail form > fieldset" ).each( function( i ) {
          $( this ).attr("id", "tab-" + i );
        });
        initPowermailTabs( settings.accordion.powermailFormSelector, settings.accordion.powermailWallHtmlId );
      }
    }  // Move the powermail form into the caddy to the tab powermail
    
    // Move the powermail form into the caddy to the tab powermail
    function submitPowermail( ) {
      console.debug( "submitPowermail( )" )
      $( ".powermail_form_30" ).submit( );
    }

    var methods = {
      accordion   : function( options ) {
                      settings = $.fn.t3caddy.defaults;
                      settings.accordion = $.extend( true, {}, settings.accordion, options );
                      if( ! $( settings.accordion.accordionSelector ).length ) {
                        console.debug( "return: settings.accordion.accordionSelector isn't set." )
                        return null;
                      }
                      addPowermailTabsToCaddy( );
                      movePowermailFormToCaddy( );
                      movePowermailFieldsToHtml5( );
                      settings.validator.effect           = "wall";
                      settings.validator.errorInputEvent  = "null";
                      settings.validator.lang             = settings.accordion.lang;
                      settings.validator.selector         = settings.accordion.powermailFormSelector;
                      settings.validator.validate         = "noValidate";
                      initToolsValidator( );
                      initValidator( );
                      addAccordion( );
                      return $( settings.accordion.accordionSelector ).data( "tabs" );
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
      update      : function( options )
                    {
                      settings = $.fn.t3caddy.defaults;
                      settings = $.extend( true, {}, settings, options );
                        // ERROR html_element is missing. Don't use AJAX but forward
                      if( !$( settings.update.html_element ).length ) {
                        if( settings.update.t3caddyAlert )
                        {
                          prompt = format( settings.messages.update.errMissingTagPropertyPrmpt, settings.update.html_element);
                          alert( settings.messages.update.errMissingTagPropertyLabel + " " + prompt );
                          prompt = format( settings.messages.update.hlpMissingTagPropertyPrmpt, settings.update.html_element);
                          alert( settings.messages.update.hlpMissingTagPropertyLabel + " " + prompt );
                        }
                        fq_url = window.location.protocol + "//" + window.location.host + "/" + settings.update.url;
                        window.location.href = fq_url;
                        return false;
                      }
                        // ERROR html_elementis missing. Don't use AJAX but forward

                        // Fade out the update prompt
                      $("#update-prompt:visible").slideUp( 'fast' );
                      $("#update-prompt div").remove( );

                        // Cover the html_element with the loading *.gif
                      cover_wi_loader( settings.update.html_element );

                        // Send the AJAX request
                        // Replace the content of the html element with the delivered data
                      var url_wi_selector = settings.update.url + " " + settings.update.html_element_wi_selector;
                      //console.debug( url_wi_selector );
                      $( settings.update.html_element ).load( url_wi_selector, settings.update.formData, function( response, status, xhr )
                      {
                          // ERROR server has an error and has send a message
                        if (status == "error")
                        {
                            // Add error messages and helpful informations to the update prompt
                          err_prompt( "#update-prompt", xhr.statusText + " (" + xhr.status + "):", settings.messages.update.errServerErrorPrmpt);
                          inf_prompt( "#update-prompt", settings.messages.update.hlpPageObjectLabel, settings.messages.update.hlpPageObjectPrmpt );
                          fq_url = window.location.protocol + "//" + window.location.host + "/" + settings.update.url;
                          a_fq_url = '<a href="' + fq_url + '">' + fq_url + '</a>';
                          prompt = format( settings.messages.update.hlpUrlPrmpt, a_fq_url);
                          inf_prompt( "#update-prompt", settings.messages.update.hlpUrlLabel, prompt );
                          prompt = format( settings.messages.update.hlpUrlSelectorPrmpt, settings.update.html_element_wi_selector);
                          //inf_prompt( "#update-prompt", settings.messages.update.hlpUrlSelectorLabel, prompt );
                          inf_prompt( "#update-prompt", settings.messages.update.hlpForumLabel, settings.messages.update.hlpForumPrmpt );
                          inf_prompt( "#update-prompt", settings.messages.update.hlpGetRidOfLabel, settings.messages.update.hlpGetRidOfPrmpt );
                            // Add error messages and helpful informations to the update prompt

                            // Fade out the loading *.gif, initiate buttons again
                          clean_up( settings.update.html_element );
                            // Fade in the update prompt
                          $( "#update-prompt:hidden" ).slideDown( 'fast' );
                          $( "#update-prompt" ).append( response );
                          return false;
                        }
                          // ERROR server has an error and has send a message

                          // Fade out the loading *.gif, initiate buttons again
//                          accordionApi = $( settings.update.accordionApi );
//                          accordionApi = methods.accordion.call( settings.accordion );
                        //accordionApi = methods.accordion( settings.accordion );
                        //accordionApi.destroy( );
                        clean_up( settings.update.html_element );
                        return accordionApi;
                        // Send the AJAX request
                      });
                      return false;
                    }, /* update( ) */
      url_autoQm  : function( options )
                    {
                      settings = $.fn.t3caddy.defaults;
                      settings.url_autoQm = $.extend( true, {}, settings.url_autoQm, options );
                      //currAccordionIndex  = settings.url_autoQm.currAccordionIndex;
                      url                 = settings.url_autoQm.url;
                      param               = settings.url_autoQm.param;
                      
//                      if( currAccordionIndex > 0 )
//                      {
//                        param = param + "&tx_caddy_pi1[accordion]=" + currAccordionIndex;
//                      }
                      //console.debug( param );
                      
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
                    }, /* url_autoQm */
    validator       : function( options )
                    {
                      settings = $.fn.t3caddy.defaults;
                      settings.validator = $.extend( true, {}, settings.validator, options );
//                      console.debug( 
//                        settings.validator.container,
//                        settings.validator.effect, 
//                        settings.validator.errorClass,
//                        settings.validator.errorInputEvent, 
//                        settings.validator.inputEvent,
//                        settings.validator.lang,
//                        settings.validator.selector
//                      );
                      // woPrompt invalid null keyup #c2997-powermail-prompt default
                      //console.debug( 1 );
                      validateForm = $( settings.validator.selector ).validator(
                      {
                        container       : settings.validator.container,
                        effect          : settings.validator.effect,          // default (default), own custom effect
                        errorClass      : settings.validator.errorClass,
                        errorInputEvent : settings.validator.errorinputEvent,  // keyup (default), change, blur, null
                        inputEvent      : settings.validator.inputEvent,       // null (default), keyup, change, blur
                        lang            : settings.validator.lang
                      });
                      //console.debug( 2 );
                      if( settings.validator.validate != "validate" )
                      {
                        //console.debug( 3 );
                        return false;
                      }
                      valid = validateForm.data( "validator" ).checkValidity( );
                      //console.debug( 4 );
                      return valid;
                    }  /* validator */
                    
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

$.fn.t3caddy.defaults = {
      accordion : {
        accordionButtonId       : "#accordionPowermailSubmit",  // default: #accordionPowermailSubmit
        accordionNext           : "button.accordionNext",       // default: button.accordionNext
        accordionPrev           : "button.accordionPrev",       // default: button.accordionPrev
        accordionApi            : undefined,  // API of the accordion
        accordionSelector       : undefined,  // e.g.: "#c2997-accordion"
        currAccordionIndex      : undefined,  // index of the current accordion pane: [0-4]
        effect                  : 'slide',
        indexAccordionOrdering  : 3, // Ordering
        indexAccordionPowermail : 4, // Powermail form
        powermailFormSelector   : undefined,  // e.g.: "#c2997-accordion-powermail div form",
        powermailWallHtmlId     : undefined,  // e.g.: "#c2997-powermail-prompt",
        powermailUid            : undefined,  // e.g.: "#c2995",
        pmuidfieldemail         : undefined,  // e.g.: 624
        pmuidfieldrevocation    : undefined,  // e.g.: 629
        pmuidfieldterms         : undefined,  // e.g.: 628
        tabs                    : 'h2',
        t3caddyAlert            : 1,          // [e.g.:]0-1] 
        t3caddyConsoleDebug     : 1           // [e.g.:]0-1]
      },
//      messages  : {
//        update : {
//          errMissingTagPropertyLabel  : "Tag is missing:",
//          errMissingTagPropertyPrmpt  : "A HTML tag with an attribute {0} is missing. AJAX can't work proper!",
//          errServerErrorPrmpt         : "This is a message from the server. Maybe the server has some problems in general. " +
//                                        "If the server delivers content, you will see the content below this prompts.",
//          hlpForumLabel               : "Interactive support:",
//          hlpForumPrmpt               : "If you need interactive help, please visit the TYPO3-Caddy-Forum at " +
//                                        "<a href=\"http://typo3-browser-forum.de/\">typo3-browser-forum.de</a>. Posts are welcome " +
//                                        "in English and German.",
//          hlpMissingTagPropertyLabel  : "Be aware of a proper HTML template:",
//          hlpMissingTagPropertyPrmpt  : "Please add something like <div id=\"{0}\">...</div> to your template.",
//          hlpPageObjectLabel          : "You can check TYPO3:",
//          hlpPageObjectPrmpt          : "Is there a proper page object? Is there a proper typeNum?",
//          hlpUrlLabel                 : "You can check the URL manually:",
//          hlpUrlPrmpt                 : "Click on {0}",
//          hlpUrlSelectorLabel         : "Be aware of the jQuery selector:",
//          hlpUrlSelectorPrmpt         : "The request takes content into account only if this selector gets a result: {0}",
//          hlpGetRidOfLabel            : "Get rid of this messages?",
//          hlpGetRidOfPrmpt            : "Deactivate the jQuery plugin t3caddy. But you won't have any AJAX support."
//        }
//      },
      messages  : {
        powermail : {
          fillFormCompletely : "Please fill in the form completely."
        },
        update :  {
          errMissingTagPropertyLabel  : "Tag is missing:",
          errMissingTagPropertyPrmpt  : "A HTML tag with an attribute {0} is missing. AJAX can't work proper!",
          errServerErrorPrmpt         : "This is a message from the server. Maybe the server has some problems in general. " +
                                        "If the server delivers content, you will see the content below this prompts.",
          hlpForumLabel               : "Interactive support:",
          hlpForumPrmpt               : "If you need interactive help, please visit the TYPO3-Caddy-Forum at " +
                                        "<a href=\"http://typo3-browser-forum.de/\">typo3-browser-forum.de</a>. Posts are welcome " +
                                        "in English and German.",
          hlpMissingTagPropertyLabel  : "Be aware of a proper HTML template:",
          hlpMissingTagPropertyPrmpt  : "Please add something like <div id=\"{0}\">...</div> to your template.",
          hlpPageObjectLabel          : "You can check TYPO3:",
          hlpPageObjectPrmpt          : "Is there a proper page object? Is there a proper typeNum?",
          hlpUrlLabel                 : "You can check the URL manually:",
          hlpUrlPrmpt                 : "Click on {0}",
          hlpUrlSelectorLabel         : "Be aware of the jQuery selector:",
          hlpUrlSelectorPrmpt         : "The request takes content into account only if this selector gets a result: {0}",
          hlpGetRidOfLabel            : "Get rid of this messages?",
          hlpGetRidOfPrmpt            : "Deactivate the jQuery plugin t3caddy. But you won't have any AJAX support."
        }
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
      },
      update      : {
        currAccordionIndex        : undefined, // [0-4]
        formData                  : undefined, // serialized
        html_element              : undefined, 
        html_element_wi_selector  : undefined, 
        url                       : undefined
      },
      url_autoQm  : {
        currAccordionIndex  : undefined,
        url                 : undefined, 
        param               : undefined
      },
      validator   : {
        container       : "validatorWall",  
        effect          : "default",        // default (default), own custom effect
        errorClass      : "invalid",
        errorInputEvent : "keyup",          // keyup (default), change, blur, null
        inputEvent      : "null",           // null (default), keyup, change, blur
        lang            : "en", 
        selector        : undefined,
        validate        : "noValidate"      // noValidate (default), validate
      }
      
};

