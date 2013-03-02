<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   99: class tx_caddy_pi1_clean extends tslib_pibase
 *
 *              SECTION: Main
 *  144:     public function main( $content, $conf )
 *
 *              SECTION: Cart
 *  205:     private function caddy( )
 *  242:     private function caddyWiProducts( )
 *  419:     private function caddyWiProductsItem( $contentItem )
 *  450:     private function caddyWiProductsPayment( )
 *  494:     private function caddyWiProductsProduct( )
 *  565:     private function caddyWiProductsProductErrorMsg( $product )
 *  589:     private function caddyWiProductsProductServiceAttributes( $product )
 *  651:     private function caddyWiProductsProductSettings( $product )
 *  691:     private function caddyWiProductsProductTax( $product )
 *  744:     private function caddyWiProductsShipping( )
 *  788:     private function caddyWiProductsSpecial( )
 *  833:     private function caddyWoProducts( )
 *
 *              SECTION: Debug
 *  860:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: Init
 *  897:     private function init( )
 *  916:     private function initAccessByIp( )
 * 1078:     private function initFlexform( )
 * 1091:     private function initGpVar( )
 * 1147:     private function initGpVarCid( )
 * 1197:     private function initHtmlTemplate( )
 * 1279:     private function initInstances( )
 * 1320:     private function initPowermail( )
 * 1333:     private function initServiceAttributes( )
 *
 *              SECTION: Order
 * 1360:     private function orderUpdate( )
 *
 *              SECTION: Product
 * 1405:     private function productAdd( )
 * 1427:     private function productRemove( )
 *
 *              SECTION: Update Wizard
 * 1454:     private function updateWizard( $content )
 *
 *              SECTION: ZZ
 * 1496:     private function zz_getPriceForOption($type, $option_id)
 * 1547:     private function zz_checkOptionIsNotAvailable($type, $option_id)
 * 1581:     private function zz_renderOptionList($type, $option_id)
 * 1691:     private function zz_price_format($value)
 *
 * TOTAL FUNCTIONS: 31
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       1.4.6
 */
class tx_caddy_pi1_clean
{

  public $prefixId = 'tx_caddy_pi1';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1_clean.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

    // Parent object
  public $pObj = null;
    // Current row
  public $row = null;

  
 /**
  * main( )
  *
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function main( )
  {
      // RETURN : powermail form isn't sent. Nothing to clean
    if( empty( $this->pObj->powermail->sent ) )
    {
        // DRS
      if( $this->pObj->drs->drsClean )
      {
        $prompt = 'The powermail form isn\'t sent, nothing to clean up.';
        t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return;
    }
      // RETURN : powermail form isn't sent. Nothing to clean
        
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent. Database, numbers and session will cleaned up.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    $this->cleanDatabase( );
    $this->cleanNumbers( );
    $this->cleanSession( );
  }

 /**
  * cleanDatabase( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanDatabase( )
  {
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, please clean up the caddy database.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    $insertFields = array( 
      'pid'       => $this->pObj->pid,
      'net'       => '100.00',
      'tax'       => '19.00',
      'gross'     => '119.00',
      'quantity'  => '7',
    );
    $GLOBALS['TYPO3_DB']->exec_INSERTquery( 'tx_caddy_order', $insertFields );
  }

 /**
  * cleanNumbers( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbers( )
  {
    $this->cleanNumbersInvoice( );
    $this->cleanNumbersShippingticket( );
  }

 /**
  * cleanNumbersInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbersInvoice( )
  {
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, please clean up the invoice number.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }
  
 /**
  * cleanNumbersShippingticket( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbersShippingticket( )
  {
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, please clean up the shipping-ticket-number.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }

 /**
  * cleanSession( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanSession( )
  {
    $this->pObj->sessionDelete( );
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_clean.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_clean.php']);
}
?>
