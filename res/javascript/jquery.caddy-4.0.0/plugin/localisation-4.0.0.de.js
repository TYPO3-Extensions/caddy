/**
 *
 * Localisation file for jquery.t3caddy

 * Copyright (c) 2013-2014 - Dirk Wildt (Die Netzmacher)
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.0.0
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */



  $.fn.t3caddy.settings.messages.update = {
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
  };
  
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

