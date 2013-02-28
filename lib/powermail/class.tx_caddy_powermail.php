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
 *   97: class tx_caddy_pi1 extends tslib_pibase
 *
 *              SECTION: Main
 *  139:     public function main( $content, $conf )
 *
 *              SECTION: Cart
 *  201:     private function cart( )
 *  230:     private function cartWiProducts( )
 *  396:     private function cartWiProductsItem( $contentItem )
 *  427:     private function cartWiProductsPayment( )
 *  471:     private function cartWiProductsProduct( )
 *  535:     private function cartWiProductsProductErrorMsg( $product )
 *  559:     private function cartWiProductsProductServiceAttributes( $product )
 *  621:     private function cartWiProductsProductSettings( $product )
 *  661:     private function cartWiProductsProductTax( $product )
 *  714:     private function cartWiProductsShipping( )
 *  758:     private function cartWiProductsSpecial( )
 *  803:     private function cartWoProducts( )
 *
 *              SECTION: Debug
 *  835:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: Init
 *  872:     private function init( )
 *  892:     private function initDrs( )
 *  931:     private function initGpVar( )
 *  987:     private function initGpVarCid( )
 * 1037:     private function initHtmlTemplate( )
 * 1119:     private function initInstances( )
 * 1135:     private function initServiceAttributes( )
 *
 *              SECTION: Order
 * 1162:     private function orderUpdate( )
 *
 *              SECTION: Product
 * 1206:     private function productAdd( )
 * 1228:     private function productRemove( )
 *
 *              SECTION: ZZ
 * 1253:     private function zz_getPriceForOption($type, $option_id)
 * 1304:     private function zz_checkOptionIsNotAvailable($type, $option_id)
 * 1335:     private function zz_renderOptionList($type, $option_id)
 * 1445:     private function zz_price_format($value)
 *
 * TOTAL FUNCTIONS: 28
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * powermail controlling for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       2.0.0
 */
class tx_caddy_powermail
{

  public $prefixId = 'tx_caddy_powermail';

  // same as class name
  public $scriptRelPath = 'lib/powermail/class.tx_caddy_powermail.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

  public $powermailFormCss    = null;
  public $powermailUid        = null;
  public $powermailTitle      = null;
  public $powermailFfConfirm  = null;
  public $powermailVersionInt = null;
  public $powermailVersionStr = null;
  




  /***********************************************
  *
  * Powermail
  *
  **********************************************/

 /**
  * formCss( ):  Returns $this->powermailFormCss.
  *                       * The CSS will hide the powermail form
  *                       * CSS is empty, if powermail form should displayed
  *
  * @return	void
  * @access public
  * @internal #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function formCss( $content )
  {
      // RETURN : there isn't any CSS for powermail
    if( empty( $this->powermailFormCss ) )
    {
        // DRS
      if( $this->pObj->b_drs_powermail )
      {
        $prompt = 'Any CSS for powermail. The powermail form (uid ' . $this->powermailUid . ') is visible.';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return $content;
    }
      // RETURN : there isn't any CSS for powermail
    
      // DRS
    if( $this->b_drs_powermail )
    {
      $prompt = 'CSS for powermail. The display property of the powermail form ' . 
                '(uid ' . $this->powermailUid . ') is set to none.';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
    $content = $content . $this->powermailFormCss;
    return $content;
  }
  
 /**
  * formHide( ): Powermail form should be unvisible, CSS snippet is written to 
  *                       $this->powermailFormCss 
  *
  * @return	void
  * @access public
  * @internal #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function formHide( )
  {
    $this->powermailFormCss = '
      <style type="text/css">
        #c' . $this->powermailUid . ' {
          display: none;
        }
      </style>
      ';
    
  }
  
 /**
  * formShow( ): Powermail form should be visible, empty CSS snippet is written to 
  *                       $this->powermailFormCss 
  *
  * @return	void
  * @access public
  * @internal #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function formShow( )
  {
    $this->powermailFormCss = null;
  }


 /**
  * init( ): Global vars are initiated:
  *                   * powermailVersionInt
  *                   * powermailVersionStr
  *                   * powermailUid
  *                   * powermailTitle
  *                   * powermailFfConfirm
  *
  * @return	void
  * @access public
  * @internal   #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  public function init( )
  {
    $arrResult = $this->initVersion( );
    $this->powermailVersionInt = $arrResult['int'];
    $this->powermailVersionStr = $arrResult['str'];

    if( empty( $this->powermailVersionInt ) )
    {
        // DRS
      if( $this->pObj->b_drs_error )
      {
        $prompt = 'Powermail version is 0!';
        t3lib_div::devlog( '[ERROR/POWERMAIL] ' . $prompt, $this->pObj->extKey, 3 );
      }
        // DRS
      return;
    }
    
      // DRS
    if( $this->pObj->b_drs_powermail )
    {
      $prompt = 'Powermail version is ' . $this->powermailVersionStr . ' ' .
                '(internal ' . $this->powermailVersionInt . ')';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
    
    $arrResult = $this->initFields( );
    $this->powermailUid        = $arrResult['uid'];
    $this->powermailTitle      = $arrResult['title'];
    $this->powermailFfConfirm  = $arrResult['ffConfirm'];
    
      // DRS
    if( $this->pObj->b_drs_powermail )
    {
      $prompt = 'powermail.uid: "' . $this->powermailUid . '"';
      t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->pObj->extKey, 0 );
      $prompt = 'powermail.title: "' . $this->powermailTitle . '"';
      t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->pObj->extKey, 0 );
      $prompt = 'powermail.confirm: "' . $this->powermailConfirm . '"';
      t3lib_div::devlog(' [INFO/POWERMAIL] '. $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    return;

  }

 /**
  * initFields( ): Reads needed values of the powermail form from the database
  *                         and returns it
  *                         * uid
  *                         * title
  *                         * ffConfirm
  *
  * @return    array        $arr : uid, title, ffConfirm of the powermail form
  * @access private
  * @internal   #45915
  * @version 2.0.0
  * @since 2.0.0
  */
  private function initFields( )
  {
    $arrReturn = null; 
    
      // Page uid
    $pid = $this->pObj->cObj->data['pid'];
    
    if( ! $pid )
    {
      $prompt = 'ERROR: unexpected result<br />
        pid is empty<br />
        Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
        TYPO3 extension: powermail4dev';
      die( $prompt );
    }

      // Query
    $select_fields  = '*';
    $from_table     = 'tt_content';
      // Don't respect hidden!
    $where_clause   = "pid = " . $pid . " AND deleted = 0";
    switch( true )
    {
      case( $this->powermailVersionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->powermailVersionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: powermail4dev';
        die( $prompt );
        break;
      case( $this->powermailVersionInt < 2000000 ):
        $where_clause = $where_clause . " AND CType = 'powermail_pi1'";
        break;
      case( $this->powermailVersionInt < 3000000 ):
        $where_clause = $where_clause . " AND list_type = 'powermail_pi1'";
        break;
      case( $this->powermailVersionInt >= 3000000 ):
      default:
        $prompt = 'ERROR: unexpected result<br />
          powermail version is 3.x: ' . $this->powermailVersionInt . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: powermail4dev';
        die( $prompt );
        break;
    }
    $groupBy        = '';
    $orderBy        = 'sorting';
    $limit          = '1';
      // Query

      // DRS
    if( $this->pObj->b_drs_sql )
    {
      $query  = $GLOBALS['TYPO3_DB']->SELECTquery
                (
                  $select_fields,
                  $from_table,
                  $where_clause,
                  $groupBy,
                  $orderBy,
                  $limit
                );
      $prompt = $query;
      t3lib_div::devlog(' [INFO/SQL] '. $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
      
      // Execute SELECT
    $res =  $GLOBALS['TYPO3_DB']->exec_SELECTquery
            (
              $select_fields,
              $from_table,
              $where_clause,
              $groupBy,
              $orderBy,
              $limit
            );
      // Execute SELECT

      // Current powermail record
    $pmRecord =  $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

      // RETURN : no row
    if( empty( $pmRecord ) )
    {
      if( $this->pObj->b_drs_error )
      {
        $prompt = 'Abort. SQL query is empty!';
        t3lib_div::devlog(' [WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
      }
      return false;
    }
      // RETURN : no row
      
    $pmUid    = $pmRecord['uid'];  
    $pmTitle  = $pmRecord['header'];  
    switch( true )
    {
      case( $this->powermailVersionInt < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: powermail4dev';
        die( $prompt );
        break;
      case( $this->powermailVersionInt < 2000000 ):
        $pmFfConfirm  = $pmRecord['tx_powermail_confirm'];
        break;
      case( $this->powermailVersionInt < 3000000 ):
      default:
        $pmFlexform         = t3lib_div::xml2array( $pmRecord['pi_flexform'] );
        $pmFfConfirm  = $pmFlexform['data']['main']['lDEF']['settings.flexform.main.form']['vDEF'];
        break;
    }

    $arrReturn['uid']       = $pmUid;
    $arrReturn['title']     = $pmTitle;
    $arrReturn['ffConfirm'] = $pmFfConfirm;

    return $arrReturn;
  }

 /**
  * initVersion( ):  Returns the version of powermail as an interger and a string.
  *                           I.e
  *                           * int: 1006006
  *                           * str: 1.6.6
  *
  * @return    array          $arrReturn  : version as int (integer) and str (string)
  * @access private
  * @version 2.0.0
  * @since   2.0.0
  */
  private function initVersion( )
  {
    return $this->pObj->userfunc->extMgmVersion( 'powermail' );
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/powermail/class.tx_caddy_powermail.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/powermail/class.tx_caddy_powermail.php']);
}
?>
