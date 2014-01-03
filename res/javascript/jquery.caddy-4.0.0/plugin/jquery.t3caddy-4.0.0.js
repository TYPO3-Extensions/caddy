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
    function addAccordion( ) {
      initialIndex = parseInt( settings.accordion.currAccordionIndex );
      if( isNaN( initialIndex ) )
      {
        initialIndex = 0;
      }
      //console.debug( initialIndex );

      // The accordian panes of the caddy
      $( settings.accordion.accordionSelector ).tabs( "div.pane",
      {
        tabs          : 'h2',
        effect        : 'slide',
        initialIndex  : initialIndex,
        onBeforeClick : function( event, indexAccordionDest ) {
          accordionButtonId = settings.accordion.accordionButtonId;
          //alert( "fnAccordion: onBeforeClick" );
          // Get index of the current accordion tab
          var indexAccordionSrce  = this.getIndex();
          settings.accordion.currAccordionIndex = indexAccordionSrce;

          // RETURN if current accordion isn't the powermail pane
          switch( true )
          {
            //case( indexAccordionDest == indexAccordionOrdering  ): // Ordering
            case( indexAccordionDest == indexAccordionPowermail ): // Powermail form
              settings.accordion.currAccordionIndex = indexAccordionDest;
              return true;
              break;
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
          lang      = settings.accordion.lang;
          selector  = settings.accordion.powermailFormSelector;
          effect    = "woPrompt";
          validate  = "validate";
          switch( initValidator( lang, selector, effect, validate ) )
          {
            case( true ):
              $( accordionButtonId ).removeAttr( "disabled" );
              break;
            case( false ):
            default:
              $( accordionButtonId ).attr( "disabled", "disabled" );
              break;
          }
//          // Are all values proper of the powermail form?
//          effect    = "wall";
//          if( initValidator( lang, selector, effect, validate ) )
//          {
//            //alert( "return true: success" );
//            // RETURN : all values are proper
//            settings.accordion.currAccordionIndex = indexAccordionDest;
////            console.debug( settings.accordion.currAccordionIndex );
//            if( indexAccordionSrce == indexAccordionPowermail )
//            {
//              submitPowermail( );
//            }
//            return true;
//          }
//          this.click( indexAccordionPowermail );
////          console.debug( settings.accordion.currAccordionIndex );
////          alert( "Bitte füllen Sie erst das Formular vollständig aus." );
//          $( accordionButtonId ).attr( "disabled", "disabled" );
//          return true;
//          return false;
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

    function initEvents( ) {
      $( document ).on( "click", "#accordionPowermailSubmit", function( e ) {
        lang      = settings.accordion.lang;
        selector  = idTabSrce;
        effect    = "woPrompt";
        validate  = "validate";
        switch( initValidator( lang, selector, effect, validate ) )
        {
          case( true ):
            $( accordionButtonId ).removeAttr( "disabled" );
            break;
          case( false ):
          default:
            $( accordionButtonId ).attr( "disabled", "disabled" );
            break;
        }
      }); 
//      accordionApi = $( settings.accordion.accordionSelector ).data( "tabs" );
//      $( document ).on( "click", settings.accordion.accordionNext, function( e ) {
//        accordionApi.next();
//      });
//      $( document ).on( "click", settings.accordion.accordionPrev, function( e ) {
//        accordionApi.prev();
//      });      
//      $( document ).on( "change", ".onChangeloadCaddyByAjax", function( e ) {
//        formAction  = $( this ).closest( "form" ).attr( "action");
//        formData    = $( this ).closest( "form" ).serialize( );
//        runAjax( formAction, formData, e );
//      }); 
//      $( document ).on( "click", "a.onClickloadCaddyByAjax", function( e ) {
//        if( !e.isDefaultPrevented( ) ) 
//        {
//          e.preventDefault( ); // Don't execute the click
//          formAction  = $( this ).attr( "href");
//          formData    = null;
//          runAjax( formAction, formData, e );
//        }
//      }); 
//      $( document ).on( "click", "input.powermail_confirmation_form", function( e ) {
//        if( !e.isDefaultPrevented( ) ) 
//        {
//          e.preventDefault( ); // Don't execute the click
//          formAction  = $( this ).closest( "form" ).attr( "action");
//          formData    = $( this ).closest( "form" ).serialize( );
//          runAjax( formAction, formData, e );
//        }
//      }); 
//      $( document ).on( "click", "input.powermail_confirmation_submit", function( e ) {
//        if( !e.isDefaultPrevented( ) ) 
//        {
//          e.preventDefault( ); // Don't execute the click
//          formAction  = $( this ).closest( "form" ).attr( "action");
//          formData    = $( this ).closest( "form" ).serialize( );
//          runAjax( formAction, formData, e );
//        }
//      }); 
//      $( document ).on( "click", "input.powermail_submit", function( e ) {
//        if( !e.isDefaultPrevented( ) ) 
//        {
//          e.preventDefault( ); // Don't execute the click
//          lang      = settings.accordion.lang;
//          selector  = settings.accordion.powermailFormSelector;
//          effect    = "wall";
//          validate  = "validate";
//          if( ! initValidator( lang, selector, effect, validate ) )
//          {
//            return;
//          }
//          formAction  = $( this ).closest( "form" ).attr( "action");
//          formData    = $( this ).closest( "form" ).serialize( );
//          console.debug( $( this ).attr( "class" ) );
//          console.debug( formAction );
//          console.debug( formData );
//          runAjax( formAction, formData, e );
//        }
//      }); 
    }
    
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
          // Get HTML id of the current tab
          var idTabSrce = "#tab-" + indexTabSrce + " :input";
          // Validate HTML input fields of the current tab
          //alert( idTabSrce );
          lang      = settings.accordion.lang;
          selector  = idTabSrce;
          effect    = "woPrompt";
          validate  = "validate";
          switch( initValidator( lang, selector, effect, validate ) )
          {
            case( true ):
              $( accordionButtonId ).removeAttr( "disabled" );
              break;
            case( false ):
            default:
              $( accordionButtonId ).attr( "disabled", "disabled" );
              break;
          }
          effect = "wall";
          switch( initValidator( lang, selector, effect, validate ) )
          {
            case( true ):
              //alert( "OK" );
              return true;
              break;
            case( false ):
            default:
              //alert( "UNPROPER" );
              return false;
              break;
          }
        }
      });
    }  // $(function() ...
    /* Powermail tabs begin */

    function initToolsValidator( ) {
      $.tools.validator.localize( "de", {
        // "*" Isn't localised
        //"*"           : "Der Wert wird nicht akzeptiert",
        ":email"      : "Bitte eine korrekte E-Mail-Adresse eingeben",
        ":number"     : "Bitte nur Zahlen eingeben",
        ":url"        : "Bitte eine korrekte URL eingeben",
        "[max]"       : "Maximal $1 ist erlaubt",
        "[min]"       : "Mindestens $1 ist n&ouml;tig",
        "[required]"  : "Bitte ausfüllen"
      }); // $.tools.validator.localize ...
      $.tools.validator.addEffect( "wall", function( errors, event ) 
      {
        pmuidfieldterms       = settings.accordion.pmuidfieldterms;
        pmuidfieldrevocation  = settings.accordion.pmuidfieldrevocation;
        // get the message wall
        var wall = $( this.getConf( ).container ).fadeIn( );
        // remove all existing messages
        wall.html( null );
        wall.append( "<h3>Bitte füllen Sie das Formular vollständig aus.</h3>" );
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
      }, function( inputs ) 
      {
        // remove all existing messages
        $( settings.accordion.powermailWallHtmlId ).html( "" );
      }); // adds the "wall" effect to the validator      
      // adds the "woPrompt" effect to the validator
      $.tools.validator.addEffect( "woPrompt", function( errors, event ) 
      {
        // prompt nothing
        alert( "woPrompt" );
      // the effect does nothing when all inputs are valid
      }, function( inputs ) 
      {
        // remove nothing
      }); // adds the "woPrompt" effect to the validator      
    } // initToolsValifator

    function initValidator( lang, selector, effect, validate ) {
      errorInputEvent = "null";
      if( effect == "woPrompt" )
      {
        errorInputEvent = "blur";
      }
      if( lang == '###LANG###' )
      {
        lang = "en";
      }
      success = false;
      validatePowermailForm = $( selector ).validator(
      {
        effect          : effect,           // default (default), own custom effect
        errorClass      : "invalid",
        container       : settings.accordion.powermailWallHtmlId,
        lang            : lang,
        // input validation for a single field
        errorInputEvent : errorInputEvent   // keyup (default), change, blur, null
      // custom form submission logic
      }).submit( function( e )  
      {
        // if data is valid
        if( !e.isDefaultPrevented( ) ) 
        {
          alert ( "Submit: Aber Hallo" );
          // tell user that everything is OK
          //$( settings.accordion.powermailWallHtmlId ).html( "<h3>All good</h3>" );
          // prevent the form data being submitted to the server
          e.preventDefault( );
        } // submit
      }); // $( selector ).validator
      if( validate == "validate" )
      {
        success = validatePowermailForm.data('validator').checkValidity( );
        //alert( success );
      }
      return success;
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
//    /* AJAX begin */
//    function runAjax( formAction, formData, e ) {
//      currAccordionIndex = accordionApi.getIndex( );
//      //console.debug( currAccordionIndex );
//      // User has clicked a tag with the class onChangeloadCaddyByAjax
//      e.preventDefault( ); // Don't execute the click
//      // RETURN : current id isn't part of the DOM
//      //if( ! $( "#c###UID###" ).length )
//      if( ! $( "#content" ).length )
//      {
//        if( t3caddyAlert )
//        {
//          //alert( "ERROR: The selector \"#c###UID###\" isn't part of the DOM!");
//          alert( "ERROR: The selector \"#content\" isn't part of the DOM!");
//        }
//        return;
//      } // RETURN : current id isn't part of the DOM
//
//      // Update the content with the id #c###UID###-###VIEW###view
//      var url = $( this ).t3caddy( 'url_autoQm', {
//        currAccordionIndex  : currAccordionIndex,
//        url                 : formAction, 
//        param               : "type=###TYPENUM###"
//      });
//      console.debug( url );
//    //var html_element              = "#c###UID###";
//      var html_element              = "#content";
//      var html_element_wi_selector  = html_element + " > *";
//      //$( this ).t3caddy( "update", {
//      $( this).update( {
//        accordionApi              : accordionApi,
//        currAccordionIndex        : currAccordionIndex,
//        formData                  : formData,
//        html_element              : html_element, 
//        html_element_wi_selector  : html_element_wi_selector, 
//        t3caddyAlert              : parseInt( "###T3CADDYALERT###" ),
//        url                       : url
//      });
//      // Update the content with the id #c###UID###-###VIEW###view
//      // Reload functions after content is updated (after 2000 miliseconds)
//    //  setTimeout( function( ) {
//    ////    accordionIndex = currAccordionIndex;
//    //    fnInit( ); /* Initiate Accordion */
//    ////    alert( accordionIndex );
//    ////    accordionApi.click( accordionIndex );
//    //  }, 2000 );
//    } 
//    /* AJAX end */

    var settings = {
      accordion : {
        accordionButtonId       : "#accordionPowermailSubmit",  // default: #accordionPowermailSubmit
        accordionNext           : "button.accordionNext",       // default: button.accordionNext
        accordionPrev           : "button.accordionPrev",       // default: button.accordionPrev
        accordionApi            : undefined,  // API of the accordion
        accordionSelector       : undefined,  // e.g.: "#c2997-accordion"
        currAccordionIndex      : undefined,  // index of the current accordion pane: [0-4]
        indexAccordionOrdering  : 3, // Ordering
        indexAccordionPowermail : 4, // Powermail form
        powermailFormSelector   : undefined,  // e.g.: "#c2997-accordion-powermail div form",
        powermailWallHtmlId     : undefined,  // e.g.: "#c2997-powermail-prompt",
        powermailUid            : undefined,  // e.g.: "#c2995",
        pmuidfieldemail         : undefined,  // e.g.: 624
        pmuidfieldrevocation    : undefined,  // e.g.: 629
        pmuidfieldterms         : undefined,  // e.g.: 628
        t3caddyAlert            : 1,          // [e.g.:]0-1] 
        t3caddyConsoleDebug     : 1           // [e.g.:]0-1]
      },
      messages  : {
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
      },
      update  : {
        currAccordionIndex        : undefined, // [0-4]
        formData                  : undefined, // serialized
        html_element              : undefined, 
        html_element_wi_selector  : undefined, 
        url                       : undefined
      },
      url_autoQm : {
        currAccordionIndex  : undefined,
        url                 : undefined, 
        param               : undefined
      }
    };

    var methods = {
      accordion   : function( options ) {
                      //console.debug( "XXX" );
                      //options = $.extend({}, settings, options);
                      options = $.extend( settings.accordion, options );
                      if( ! $( settings.accordion.accordionSelector ).length ) {
                        console.debug( "return: settings.accordion.accordionSelector isn't set." )
                        return null;
//                        return {
//                          accordionApi        : null,
//                          currAccordionIndex  : null
//                        }
                      }
                      addPowermailTabsToCaddy( );
                      movePowermailFormToCaddy( );
                      movePowermailFieldsToHtml5( );
                      lang      = settings.accordion.lang;
                      selector  = settings.accordion.powermailFormSelector;
                      effect    = "wall";
                      validate  = "noValidate";
                      initValidator( lang, selector, effect, validate );
                      initToolsValidator( );
                      addAccordion( );
                      initEvents( );
                      return $( settings.accordion.accordionSelector ).data( "tabs" );
//                      return {
//                        accordionApi        : $( settings.accordion.accordionSelector ).data( "tabs" ),
//                        currAccordionIndex  : settings.accordion.currAccordionIndex
//                      }

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
                      options = $.extend( settings, options );
                        // update():  replace the content of the given html element with the content
                        //            of the requested url. The content is the content of the html element with selector.

//                      return this.each( function ( )
//                      {
                          // ERROR html_element is missing. Don't use AJAX but forward
                        if( !$( settings.update.html_element ).length ) {
                          if( settings.update.t3caddyAlert )
                          {
                            prompt = format( settings.messages.errMissingTagPropertyPrmpt, settings.update.html_element);
                            alert( settings.messages.errMissingTagPropertyLabel + " " + prompt );
                            prompt = format( settings.messages.hlpMissingTagPropertyPrmpt, settings.update.html_element);
                            alert( settings.messages.hlpMissingTagPropertyLabel + " " + prompt );
                            //alert(settings.messages.hlpAjaxConflictLabel + " " + settings.messages.hlpAjaxConflictPrmpt );
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
                            err_prompt( "#update-prompt", xhr.statusText + " (" + xhr.status + "):", settings.messages.errServerErrorPrmpt);
                            inf_prompt( "#update-prompt", settings.messages.hlpPageObjectLabel, settings.messages.hlpPageObjectPrmpt );
                            fq_url = window.location.protocol + "//" + window.location.host + "/" + settings.update.url;
                            a_fq_url = '<a href="' + fq_url + '">' + fq_url + '</a>';
                            prompt = format( settings.messages.hlpUrlPrmpt, a_fq_url);
                            inf_prompt( "#update-prompt", settings.messages.hlpUrlLabel, prompt );
                            prompt = format( settings.messages.hlpUrlSelectorPrmpt, settings.update.html_element_wi_selector);
                            //inf_prompt( "#update-prompt", settings.messages.hlpUrlSelectorLabel, prompt );
                            inf_prompt( "#update-prompt", settings.messages.hlpForumLabel, settings.messages.hlpForumPrmpt );
                            inf_prompt( "#update-prompt", settings.messages.hlpGetRidOfLabel, settings.messages.hlpGetRidOfPrmpt );
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
//                        });
                          // Send the AJAX request
                      });
                      return false;
                    }, /* update( ) */
      url_autoQm  : function( options )
                    {
                      options = $.extend( settings.url_autoQm, options );
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

