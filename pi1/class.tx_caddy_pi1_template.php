<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 *   52: class tx_caddy_pi1_template
 *   71:     public function main( )
 *  181:     private function templateTable( $tmplSubparts )
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @version     6.0.3
 * @since       1.4.6
 */
class tx_caddy_pi1_template extends tx_caddy_pi1
{
//    public $extKey = 'caddy';
//
//    public $prefixId = 'tx_caddy_pi1_template';
//
//    public $scriptRelPath = 'pi1/class.tx_caddy_pi1_template.php';
//
//    public $pObj = null;


 /**
  * templateMain( ): Returns template subparts
  *
  * @return	array		$tmplSubparts : array with template subparts;
  * @access public
  * @version    6.0.3
  * @since      2.0.0
  */
  public function templateMain( )
  {
    $cObj = $this->cObj;
    $conf = $this->conf;
    $template     = $cObj->fileResource( $conf['templates.']['html.']['caddy.']['file'] );

      // Die if there isn't any HTML template
    if( empty ( $template ) )
    {
        // DRS
      if( $this->drs->drsError )
      {
        if( empty ( $conf['templates.']['html.']['file'] ) )
        {
          $prompt = 'The path to the HTML template is empty!';
          t3lib_div::devlog( '[ERROR/INIT] ' . $prompt, $this->extKey, 3 );
          $prompt = 'Please check, if you have included the static template.';
          t3lib_div::devlog( '[HELP/INIT] ' . $prompt, $this->extKey, 1 );
        }
        if( ! empty ( $conf['templates.']['html.']['file'] ) )
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

        // #i0025, 130913, dwildt
      $prompt = '
        <div style="border:1em solid red;color:red;padding:1em;text-align:center">
          <h1>
            ERROR: No HTML Template
          </h1>
          <p>
            There isn\'t any HTML template.
          </p>
          <ul>
            <li>
              Please check the current configuration with the Caddy plugin -> tab [Check it!]
            </li>
            <li>
              Please check your template configuration. See TypoScript Constant Editor > CADDY - TEMPLATES
            </li>
            <li>
              Please enable the DRS - the Development Reporting System. It tries to offer a fix.
            </li>
          </ul>
          <p>
            ' . __METHOD__ . ' (' . __LINE__ . ')
          </p>
          <p>
            Caddy - the Shopping Cart
          </p>
        </div>
        ';
      die( $prompt );
    }
      // Die if there isn't any HTML template

    $markerAll  = $conf['templates.']['html.']['caddy.']['marker.']['all'];
    $markerItem = $conf['templates.']['html.']['caddy.']['marker.']['item'];

    $tmplSubparts['all']      = $cObj->getSubpart( $template, $markerAll );
    $tmplSubparts['empty']    = $cObj->getSubpart( $template, '###CADDY_EMPTY###' );
    $tmplSubparts['minprice'] = $cObj->getSubpart( $template, '###CADDY_MINPRICE###' );
    $tmplSubparts['item']     = $cObj->getSubpart( $tmplSubparts['all'], $markerItem );

    $tmplSubparts['item_error'] = $cObj->getSubpart( $template, '###CADDY_ITEM_ERROR###' );

//      // #55333, 140125, dwildt, 2+
//    $tmplSubparts['options']  = $cObj->getSubpart( $template, '###OPTIONS###' );
//    $tmplSubparts['payment_method']  = $cObj->getSubpart( $template, '###PAYMENT_METHOD###' );

    $tmplSubparts['payment_all']  = $cObj->getSubpart( $template, '###CADDY_PAYMENT###' );
    $tmplSubparts['payment_item'] = $cObj->getSubpart( $tmplSubparts['payment_all'], '###ITEM###' );

    $tmplSubparts['payment_condition_all']  = $cObj->getSubpart( $template, '###CADDY_PAYMENT_CONDITIONS###' );
    $tmplSubparts['payment_condition_item'] = $cObj->getSubpart( $tmplSubparts['payment_condition_all'], '###ITEM###' );

    // new for Shipping radiolist and Payment radiolist and Special checkboxlist
    $tmplSubparts['shipping_all']   = $cObj->getSubpart( $template, '###CADDY_SHIPPING###' );
    $tmplSubparts['shipping_item']  = $cObj->getSubpart( $tmplSubparts['shipping_all'], '###ITEM###' );

    $tmplSubparts['shipping_condition_all']   = $cObj->getSubpart( $template, '###CADDY_SHIPPING_CONDITIONS###' );
    $tmplSubparts['shipping_condition_item']  = $cObj->getSubpart( $tmplSubparts['shipping_condition_all'], '###ITEM###' );

    $tmplSubparts['specials_all']  = $cObj->getSubpart( $template, '###CADDY_SPECIALS###' );
    $tmplSubparts['specials_item'] = $cObj->getSubpart( $tmplSubparts['specials_all'], '###ITEM###' );

    $tmplSubparts['specials_condition_all']  = $cObj->getSubpart( $template, '###CADDY_SPECIALS_CONDITIONS###' );
    $tmplSubparts['specials_condition_item'] = $cObj->getSubpart( $tmplSubparts['specials_condition_all'], '###ITEM###' );

    $tmplSubparts = $this->templateTable( $tmplSubparts );

    return $tmplSubparts;
  }

 /**
  * templateTable( )
  *
  * @param	string		$tmplSubparts :
  * @return	string		$tmplSubparts :
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function templateTable( $tmplSubparts )
  {
    $table = $this->conf['templates.']['html.']['caddy.']['table.'];

    foreach( ( array ) $table as $property => $value )
    {
      $marker               = '###' . strtoupper( $property ) . '###';
      $tmplSubparts['all']  = str_replace( $marker, $value, $tmplSubparts['all'] );
      $tmplSubparts['item'] = str_replace( $marker, $value, $tmplSubparts['item'] );
    }

    return $tmplSubparts;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_template.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_template.php']);
}
?>