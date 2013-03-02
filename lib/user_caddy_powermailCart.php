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


/**
 * plugin 'Cart' for the 'powermail' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package  TYPO3
 * @subpackage  user_caddy_powermailCart
 * @version     2.0.0
 * @since       1.4.6
 */
class user_caddy_powermailCart 
{

  /**
  * read and return cart from session
  *
  * @return  string    cart content
  * @version 1.2.2
  */
  public function showCart( $content = '', $conf = array( ) )
  {
    $prompt = __METHOD__ . ' (' . __LINE__ . ')';
    t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    return 'Hallo';
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/user_caddy_powermailCart.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/user_caddy_powermailCart.php']);
}
?>
