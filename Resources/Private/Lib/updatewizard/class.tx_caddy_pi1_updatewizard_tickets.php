<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2015 -  Dirk Wildt http://wildt.at.die-netzmacher.de
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The class tx_caddy_pi1_updatewizard_tickets contains the array with the tickets
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package    TYPO3
 * @subpackage  caddy
 * @version   2.0.0
 * @since     2.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_caddy_pi1_updatewizard_tickets
 *   63:     function __construct($parentObj)
 *
 *              SECTION: Array with all Tickets
 *  128:     function init_tickets()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_caddy_pi1_updatewizard_tickets
{






  /**
 * Constructor. The method initiate the parent object
 *
 * @param	object		The parent object
 * @return	void
 * @version   2.0.0
 * @since     2.0.0
 */
  function __construct( $parentObj )
  {
    $this->pObj = $parentObj;

      // Define constants for the Ticket array
    define('PI1_CHECK_TYPE',          1);
    define('PI1_CHECK_PATH',          2);
    define('PI1_CHECK_VALUE',         3);

    define('PI1_STATUS_OK',           1);
    define('PI1_STATUS_INFO',         2);
    define('PI1_STATUS_HELP',         3);
    define('PI1_STATUS_WARN',         4);
    define('PI1_STATUS_ERROR',        5);

    define('PI1_TODO_NOTHING',        1);
    define('PI1_TODO_UPDATE_PLEASE',  2);
    define('PI1_TODO_UPDATE_MUST',    3);
    define('PI1_TODO_REMOVE_PLEASE',  4);
    define('PI1_TODO_CONFIG_PLUGIN',  5);

    define('PI1_TYPE_INT',            1);
    define('PI1_TYPE_STR',            2);

    define('PI1_VIEW_LIST',           1);
    define('PI1_VIEW_SINGLE',         2);
    define('PI1_VIEW_LIST_SINGLE',    3);

    define('PI1_GLOBAL',              1);
    define('PI1_LOCAL',               2);
    define('PI1_GLOBAL_LOCAL',        3);
      // Define constants for the Ticket array

  }










  /***********************************************
  *
  * Array with all Tickets
  *
  **********************************************/








/**
 * Initiate an array with the tickets
 *
 * @return	array		Array with the Tickets
 */
  function init_tickets( ) 
  {
    $arr_release = null;

    /////////////////////////////////////////////////////
    //
    // Set the Ticket array

    // Syntax:  Release|TicketNo|Properties
    // Example: 3      |123     |The Ticket Array
    $int_v = 2;
    // Version
    $int_t = 0;
    //Ticket

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'A-Z-Browser Default Tab';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'The A-Z-Browser Default Tab couldn\'t translate to another language until %version%. We changed the type from string to integer. The value has to be an id of a tab instead of the name. Edit your code like in the example below.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'A-Z-Browser Standard-Tab';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Der A-Z-Browser Standard-Tab konnte bis zur Version %version% nicht &uuml;bersetzt werden. Wir haben den Typ von String auf Integer umgestellt. Der Wert ist jetzt die ID eines Tabs statt wie bisher eines Namens. &Auml;ndere den Code wie im Beispiel unten erl&auml;utert.';
    $arr_release[$int_v][$int_t]['status']                          = PI1_STATUS_ERROR;
    $arr_release[$int_v][$int_t]['todo']                            = PI1_TODO_UPDATE_MUST;
    $arr_release[$int_v][$int_t]['function']                        = 'str_to_int';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.2';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'a-z_Browser.defaultTab';
    $arr_release[$int_v][$int_t]['expl']['prompt']['default']       = 'Example: If you like the first tab as the default tab and the first tab has the id 0 (see a-z_Browser.tabs):';
    $arr_release[$int_v][$int_t]['expl']['prompt']['de']            = 'Beispiel: Wenn der erste Tab der Standard-Tab sein soll und dieser die ID 0 hat (siehe auch a-z_Browser.tabs):';
    $arr_release[$int_v][$int_t]['expl']['code']                    = 'a-z_Browser.defaultTab = 0';

    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Display Searchbox';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'This porperty is configured by the Plugin since version %version%. The TypoScript property is deleted.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Suchfeld anzeigen';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Die Eigenschaft wird seit Version %version% im Plugin gepflegt und nicht mehr in TypoScript.';
    $arr_release[$int_v][$int_t]['status']                          = PI1_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI1_TODO_REMOVE_PLEASE;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_ts_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '2.6.7';
    $arr_release[$int_v][$int_t]['srce']['typoscript']['code']      = 'displayList.display.searchform';

    $int_v++;
    $int_t++;
    $arr_release[$int_v][$int_t]['header']['default']               = 'Plugin: a field is moved';
    $arr_release[$int_v][$int_t]['prompt']['default']               = 'The field [Views: This plugin handles the views (ids from TypoScript '.
      'comma seperated like: 0,1,4)] in the devider [General] is deprecated. Please configure now [Views: Enabled list views, which are handled by this'.
      'plugin. The (number) is the number from TypoScript.].<br />'.
      'Former value was : %viewsIds% <br />'.
      '<br />'.
      'Please maintain the new field.';
    $arr_release[$int_v][$int_t]['header']['de']                    = 'Plugin: Feld wurde geändert';
    $arr_release[$int_v][$int_t]['prompt']['de']                    = 'Das Feld [Views: Dieses Plugin verarbeitet die Views (IDs aus TypoScript '.
      'mit Komma getrennt - z.B.: 0,1,4)] im Reiter [Allgemein] ist veraltet. Die Werte werden jetzt im Feld gepflegt [Views: Listen, die von '.
      'diesem Plugin verarbeitet werden.].<br />'.
      'Deine bisherigen Werte waren: %viewsIds% <br />'.
      '<br />'.
      'Pflege bitte die Werte in das neue Feld ein.';
    $arr_release[$int_v][$int_t]['status']                          = PI1_STATUS_WARN;
    $arr_release[$int_v][$int_t]['todo']                            = PI1_TODO_CONFIG_PLUGIN;
    $arr_release[$int_v][$int_t]['function']                        = 'moved_from_plugin_to_plugin';
    $arr_release[$int_v][$int_t]['version']                         = '3.4.2';
    $arr_release[$int_v][$int_t]['srce']                            = 'sDEF.viewsIds';
    $arr_release[$int_v][$int_t]['dest']                            = 'sDEF.viewslist';


    // Set the Ticket array


    /////////////////////////////////////////////////////
    //
    // Return the Ticket array

    return $arr_release;
    // Return the Ticket array
  }








  }

  if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi2/class.tx_caddy_pi1_updatewizard_tickets.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi2/class.tx_caddy_pi1_updatewizard_tickets.php']);
  }

?>