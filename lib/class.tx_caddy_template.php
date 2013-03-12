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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   51: class tx_caddy_template
 *   70:     public function main( )
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @version     2.0.0
 * @since       1.4.6
 */
class tx_caddy_template
{
    public $extKey = 'caddy';

    public $prefixId = 'tx_caddy_template';

    public $scriptRelPath = 'lib/class.tx_caddy_template.php';

    public $pObj = null;


 /**
  * main( ): Returns template subparts
  *
  * @return	array		$tmplSubparts : array with template subparts;
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function main( )
  {
    $cObj = $this->pObj->cObj;
    $conf = $this->pObj->conf;

    $tmplSubparts = null;
    $template     = $cObj->fileResource( $conf['main.']['template'] );

      // Die if there isn't any HTML template
    if( empty ( $template ) )
    {
        // DRS
      if( $this->pObj->drs->drsError )
      {
        if( empty ( $conf['main.']['template'] ) )
        {
          $prompt = 'The path to the HTML template is empty!';
          t3lib_div::devlog( '[ERROR/INIT] ' . $prompt, $this->extKey, 3 );
          $prompt = 'Please check, if you have included the static template.';
          t3lib_div::devlog( '[HELP/INIT] ' . $prompt, $this->extKey, 1 );
        }
        if( ! empty ( $conf['main.']['template'] ) )
        {
          $prompt = 'The path to your HTML template seem\'s to be unproper!';
          t3lib_div::devlog( '[ERROR/INIT] ' . $prompt, $this->extKey, 3 );
          $prompt = 'Path is ' . $conf['main.']['template'];
          t3lib_div::devlog( '[WARN/INIT] ' . $prompt, $this->extKey, 2 );
          $prompt = 'Please check your TypoScript: plugin.caddy.main.template';
          t3lib_div::devlog( '[HELP/INIT] ' . $prompt, $this->extKey, 1 );
        }
      }
        // DRS

      $prompt = '
        <div style="border:1em solid red;color:red;padding:1em;text-align:center">
          <h1>
            ERROR: No HTML Template
          </h1>
          <p>
            There isn\'t any HTML template.
          </p>
          <p>
            Please enable the DRS - the Development Reporting System. It tries to offer a fix.
          </p>
          <p>
            Caddy - the Shopping Cart
          </p>
        </div>
        ';
      die( $prompt );
    }
      // Die if there isn't any HTML template

    $tmplSubparts['all']      = $cObj->getSubpart( $template, '###CADDY###' );
    $tmplSubparts['empty']    = $cObj->getSubpart( $template, '###CADDY_EMPTY###' );
    $tmplSubparts['minprice'] = $cObj->getSubpart( $template, '###CADDY_MINPRICE###' );
    $tmplSubparts['item']     = $cObj->getSubpart( $tmplSubparts['all'], '###ITEM###' );

    $tmplSubparts['item_error'] = $cObj->getSubpart( $template, '###CADDY_ITEM_ERROR###' );

    // new for Shipping radiolist and Payment radiolist and Special checkboxlist
    $tmplSubparts['shipping_all']   = $cObj->getSubpart( $template, '###CADDY_SHIPPING###' );
    $tmplSubparts['shipping_item']  = $cObj->getSubpart( $tmplSubparts['shipping_all'], '###ITEM###' );

    $tmplSubparts['shipping_condition_all']   = $cObj->getSubpart( $template, '###CADDY_SHIPPING_CONDITIONS###' );
    $tmplSubparts['shipping_condition_item']  = $cObj->getSubpart( $tmplSubparts['shipping_condition_all'], '###ITEM###' );

    $tmplSubparts['payment_all']  = $cObj->getSubpart( $template, '###CADDY_PAYMENT###' );
    $tmplSubparts['payment_item'] = $cObj->getSubpart( $tmplSubparts['payment_all'], '###ITEM###' );

    $tmplSubparts['payment_condition_all']  = $cObj->getSubpart( $template, '###CADDY_PAYMENT_CONDITIONS###' );
    $tmplSubparts['payment_condition_item'] = $cObj->getSubpart( $tmplSubparts['payment_condition_all'], '###ITEM###' );

    $tmplSubparts['special_all']  = $cObj->getSubpart( $template, '###CADDY_SPECIAL###' );
    $tmplSubparts['special_item'] = $cObj->getSubpart( $tmplSubparts['special_all'], '###ITEM###' );

    $tmplSubparts['special_condition_all']  = $cObj->getSubpart( $template, '###CADDY_SPECIAL_CONDITIONS###' );
    $tmplSubparts['special_condition_item'] = $cObj->getSubpart( $tmplSubparts['special_condition_all'], '###ITEM###' );

    return $tmplSubparts;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_template.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_template.php']);
}
?>
