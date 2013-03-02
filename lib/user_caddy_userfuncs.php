<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  Caddy is a fork of wt_cart (version 1.4.6)
 *  (c) wt_cart 2010-2012 - wt_cart Development Team <info@wt-cart.com>
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

require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_div.php'); // file for div functions

/**
 * plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version     2.0.0
 * @since       1.4.6
 */
class user_caddy_userfuncs extends tslib_pibase
{

  public $prefixId = 'tx_caddy_pi1';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';

  // path to any file in pi1 for locallang
  public $extKey = 'caddy'; // The extension key.

  /**
    * number Format for typoscript
    *
    * @return	string		formatted number
    */
  public function user_caddy_numberFormat( $content = '', $conf = array( ) )
  {
    global $TSFE;
    $local_cObj = $TSFE->cObj; // cObject

    if( ! $content )
    {
      $conf     = $conf['userFunc.']; // TS configuration
      $content  = $local_cObj->cObjGetSingle($conf['number'], $conf['number.']); // get number
    }

    $numberFormat =  number_format( $content, $conf['decimal'], $conf['dec_point'], $conf['thousands_sep'] );
    return $numberFormat;
  }

//  /**
//    * clear cart
//    *
//    * @return	void
//    */
//  public function user_caddy_clearCart($content = '', $conf = array())
//  {
//    $div = t3lib_div::makeInstance('tx_caddy_div'); // Create new instance for div functions
//    $div->removeAllProductsFromSession(); // clear cart now
//  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/user_caddy_userfuncs.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/user_caddy_userfuncs.php']);
}
?>