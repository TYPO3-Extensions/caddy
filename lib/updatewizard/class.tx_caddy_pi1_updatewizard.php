<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 Dirk Wildt <wildt.at.die-netzmacher.de>
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

// #61634, 140916, dwildt, 1-
//require_once(PATH_tslib . 'class.tslib_pibase.php');
// #61634, 140916, dwildt, +
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
// Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
if ( $version < 6002002 )
{
  require_once(PATH_tslib . 'class.tslib_pibase.php');
}
// #61634, 140916, dwildt, +

/**
 * Plugin 'Browser' for the 'caddy' extension - the fastest way for your data into the TYPO3 frontend.
 *
 * @author    Dirk Wildt <dirk.wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  caddy
 * @version 6.0.0
 * @since 2.0.0
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_caddy_pi1_updatewizard extends tslib_pibase
 *
 *              SECTION: Main Process
 *  109:     function main($content, $conf, $pObj)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_caddy_pi1_updatewizard extends tslib_pibase {

  //////////////////////////////////////////////////////
  //
  // Variables set by this class

  var $prefixId = 'tx_caddy_pi1_updatewizard';
  // Same as class name
  var $scriptRelPath = 'lib/updatewizard/class.tx_caddy_pi1_updatewizard.php';
  // Path to this script relative to the extension dir.
  var $extKey = 'caddy';
  // The extension key.
  var $arr_tickets = FALSE;
  // [array] The Array with the Tiockets. Set by tx_caddy_pi1_updatewizard_tickets.
  // Variables set by this class


  //////////////////////////////////////////////////////
  //
  // Variables set by children classes

  // Variables set by children classes


/**
 * Constructor. The method initiate the parent object
 *
 * @param    object        The parent object
 * @return    void
 */
  function __construct( $parentObj, $conf )
  {
    $this->pObj = $parentObj;
    $this->conf = $parentObj->conf;
  }



  /***********************************************
   *
   * Main Process
   *
   **********************************************/




  /**
 * Main method of your PlugIn
 *
 * @param	string		$content: The content of the PlugIn
 * @return	string		The content that should be displayed on the website
 */
  function main( $content )
  {

    $this->pi_loadLL();



    //////////////////////////////////////////////////////////////////////
    //
    // Instantiate the pi2 classes

    require_once('class.tx_caddy_pi1_updatewizard_checker.php');
    // Class with the functions for checking TypoScript update
    $this->objCheck = new tx_caddy_pi1_updatewizard_checker($this);
    require_once('class.tx_caddy_pi1_updatewizard_tickets.php');
    // Class with the Ticket array and defined constants
    $this->objTickets = new tx_caddy_pi1_updatewizard_tickets($this);
    // Instantiate the pi2 classes


// 130227, dwildt, -
//    //////////////////////////////////////////////////////////////////////
//    //
//    // Make cObj instance
//
//    $this->local_cObj = t3lib_div::makeInstance('tslib_cObj');
//    // Make cObj instance
// 130227, dwildt, -



    //////////////////////////////////////////////////////////////////////
    //
    // Workflow

    // Get the tickets
    $this->arr_tickets = $this->objTickets->init_tickets();
    // Get the report
    $template          = $this->objCheck->loop_tickets();
    // Workflow


    //////////////////////////////////////////////////////////////////////
    //
    // Return the result (HTML string)

    return $content . $template;
    // Return the result (HTML string)
  }



}







if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi2/class.tx_caddy_pi1_updatewizard.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi2/class.tx_caddy_pi1_updatewizard.php']);
}

?>