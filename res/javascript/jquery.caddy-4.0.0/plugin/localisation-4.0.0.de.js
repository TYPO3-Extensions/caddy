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



  $.fn.t3caddy.defaults.messages.powermail = {
    fillFormCompletely          : "Bitte füllen Sie das Formular vollständig aus."
  };
  $.fn.t3caddy.defaults.messages.update = {
    errMissingTagPropertyLabel  : "HTML-Tag fehlt:",
    errMissingTagPropertyPrmpt  : "Ein HTML-Tag mit dem Attribut \"{0}\" fehlt. AJAX kann nicht richtig funktionieren!",
    errServerErrorPrmpt         : "Dies ist eine Nachricht vom Server. Vielleicht hat der Server ein grundsätzliches Problem. " +
                                  "Wenn der Server eine Antwort mit Inhalt (HTML-Code) liefert, siehst Du das Ergebnis in der nächsten Meldung.",
    hlpForumLabel               : "Interaktive Hilfe:",
    hlpForumPrmpt               : "Wenn Du nicht weiter weißt, besuche das TYPO3-Caddy-Forum: " +
                                  "<a href=\"http://typo3-browser-forum.de/\">typo3-browser-forum.de</a>. " +
                                  "Beiträge sind Willkommen auf Deutsch und Englisch.",
    hlpMissingTagPropertyLabel  : "Kümmer Dich bitte um ein korrektes HTML-Template:",
    hlpMissingTagPropertyPrmpt  : "Bitte füge Deinem Template etwas wie <div id=\"{0}\">...</div> hinzu.",
    hlpPageObjectLabel          : "Du kannst TYPO3 überprüfen:",
    hlpPageObjectPrmpt          : "Gibt es ein korrektes Page-Objekt? Hat es einen korrekten typeNum?",
    hlpUrlLabel                 : "Du kannst die URL manuell überprüfen:",
    hlpUrlPrmpt                 : "klicke auf {0}",
    hlpUrlSelectorLabel         : "Überprüfe bitte den jQuery-Selektor:",
    hlpUrlSelectorPrmpt         : "Die Anfrage übernimmt Inhalt vom Server nur von innerhalb dieses Selektors: {0}",
    hlpGetRidOfLabel            : "Hast Du genug von diesen Nachrichten?",
    hlpGetRidOfPrmpt            : "Deaktiviere das jQuery Plugin t3caddy. Allerdings hast Du dann auch keinen AJAX-Funktionalität."
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

