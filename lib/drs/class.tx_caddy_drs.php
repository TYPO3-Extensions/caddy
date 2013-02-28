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
 *  103: class tx_caddy_drs extends tslib_pibase
 *
 *              SECTION: Main
 *  148:     public function main( $content, $conf )
 *
 *              SECTION: Cart
 *  209:     private function cart( )
 *  246:     private function cartWiProducts( )
 *  423:     private function cartWiProductsItem( $contentItem )
 *  454:     private function cartWiProductsPayment( )
 *  498:     private function cartWiProductsProduct( )
 *  569:     private function cartWiProductsProductErrorMsg( $product )
 *  593:     private function cartWiProductsProductServiceAttributes( $product )
 *  655:     private function cartWiProductsProductSettings( $product )
 *  695:     private function cartWiProductsProductTax( $product )
 *  748:     private function cartWiProductsShipping( )
 *  792:     private function cartWiProductsSpecial( )
 *  837:     private function cartWoProducts( )
 *
 *              SECTION: Debug
 *  864:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: Init
 *  901:     private function init( )
 *  921:     private function initAccessByIp( )
 *  967:     private function init( )
 *  989:     private function initByExtmngr( )
 * 1036:     private function initByFlexform( )
 * 1083:     private function initFlexform( )
 * 1096:     private function initGpVar( )
 * 1152:     private function initGpVarCid( )
 * 1202:     private function initHtmlTemplate( )
 * 1284:     private function initInstances( )
 * 1312:     private function initPowermail( )
 * 1325:     private function initRequireClasses( )
 * 1340:     private function initServiceAttributes( )
 *
 *              SECTION: Order
 * 1367:     private function orderUpdate( )
 *
 *              SECTION: Product
 * 1412:     private function productAdd( )
 * 1434:     private function productRemove( )
 *
 *              SECTION: Update Wizard
 * 1461:     private function updateWizard( $content )
 *
 *              SECTION: ZZ
 * 1503:     private function zz_getPriceForOption($type, $option_id)
 * 1554:     private function zz_checkOptionIsNotAvailable($type, $option_id)
 * 1588:     private function zz_renderOptionList($type, $option_id)
 * 1698:     private function zz_price_format($value)
 *
 * TOTAL FUNCTIONS: 35
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
class tx_caddy_drs
{

  public $prefixId = 'tx_caddy_drs';

  // same as class name
  public $scriptRelPath = 'lib/drs/class.tx_caddy_drs.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

    // Parent object
  public $pObj = null;

    // Current row
  public $row = null;

  public $drsError      = false;
  public $drsWarn       = false;
  public $drsInfo       = false;
  public $drsOk         = false;
  public $drsFlexform   = false;
  public $drsInit       = false;
  public $drsPowermail  = false;
  public $drsSql        = false;
  public $drsTodo       = false;







 /**
  * init( ): Init the DRS - Development Reportinmg System
  *
  * @return	void
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function init( )
  {
    $this->initByExtmngr( );

      // RETURN : DRS is enabled by the extension manager
    if( $this->drsOk )
    {
      return;
    }
      // RETURN : DRS is enabled by the extension manager

    $this->initByFlexform( );
  }

 /**
  * initByExtmngr( ): Init the DRS - Development Reportinmg System
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initByExtmngr( )
  {
    switch( $this->pObj->arr_extConf['debuggingDrs'] )
    {
      case( 'Disabled' ):
      case( null ):
        return;
        break;
      case( 'Enabled (for debugging only!)' ):
          // Follow the workflow
        break;
      default:
        $prompt = 'Error: debuggingDrs is undefined.<br />
          value is ' . $this->pObj->arr_extConf['debuggingDrs'] . '<br />
          <br />
          ' . __METHOD__ . ' line(' . __LINE__. ')';
        die( $prompt );
    }

    $this->drsError      = true;
    $this->drsWarn       = true;
    $this->drsInfo       = true;
    $this->drsOk         = true;
    $this->drsFlexform   = true;
    $this->drsInit       = true;
    $this->drsPowermail  = true;
    $this->drsSql        = true;
    $this->drsTodo       = true;
    $prompt = 'The DRS - Development Reporting System is enabled: ' . $this->pObj->arr_extConf['debuggingDrs'];
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $prompt = 'The DRS is enabled by the extension manager.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $str_header = $this->row['header'];
    $int_uid    = $this->row['uid'];
    $int_pid    = $this->row['pid'];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog('[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0);
  }

 /**
  * initByFlexform( ): Init the DRS - Development Reportinmg System
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initByFlexform( )
  {

      // sdefDrs
    $sheet = 'sDEF';
    $field = 'sdefDrs';
    $this->pObj->flexform->sdefDrs = $this->pObj->flexform->zzFfValue( $sheet, $field, false );
      // sdefDrs

      // Enable the DRS by TypoScript
    switch( $this->pObj->flexform->sdefDrs )
    {
      case( false ):
      case( null ):
        return;
        break;
      case( true ):
      default:
        break;
    }

    $this->drsError      = true;
    $this->drsWarn       = true;
    $this->drsInfo       = true;
    $this->drsOk         = true;
    $this->drsFlexform   = true;
    $this->drsInit       = true;
    $this->drsPowermail  = true;
    $this->drsSql        = true;
    $this->drsTodo       = true;
    $prompt = 'The DRS - Development Reporting System is enabled by the flexform.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0 );
    $str_header = $this->row['header'];
    $int_uid    = $this->row['uid'];
    $int_pid    = $this->row['pid'];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog('[INFO/DRS] ' . $prompt, $this->pObj->extKey, 0);
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/drs/class.tx_caddy_drs.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/drs/class.tx_caddy_drs.php']);
}
?>
