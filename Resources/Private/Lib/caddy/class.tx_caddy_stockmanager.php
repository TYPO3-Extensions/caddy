<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 *   64: class tx_caddy_stockmanager
 *
 *              SECTION: Init
 *   92:     private function init( )
 *  106:     private function initInstances( )
 *  138:     public function initPidCaddy( $pidCaddy )
 *
 *              SECTION: Main
 *  163:     public function itemDecrease( $table, $field, $uid, $quantity )
 *
 *              SECTION: State
 *  193:     private function stateIsDisabled( )
 *  223:     private function stateIsDisabledByCaddy( )
 *  244:     private function stateIsDisabledByShop( )
 *  266:     private function stateIsDisabledByItem( )
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	4.0.7
 * @since       4.0.7
 */
class tx_caddy_stockmanager
{

  public  $extKey         = 'caddy';
  public  $prefixId       = 'tx_caddy_pi1';
  public  $scriptRelPath  = 'pi1/class.tx_caddy_pi1.php';

  private $quantityIndefinitely = 9999999;

  private $drs            = null;   // Object   : Caddy DRS
  private $initInstances  = null;   // Boolean  : true, if instances are init
  private $session        = null;   // Object   : Caddy session

  private $conf           = null;   // Array    : TypoScript configuration of the parent object
  private $pObj           = null;   // Object   : parent object
  private $pid            = null;   // Integer  : pid of the page with the current Caddy plugin



 /***********************************************
  *
  * Get Methods
  *
  **********************************************/

 /**
  * getItemQuantity( )  : Get the quantity of the given item in stock.
  *
  * @param	string		$table    : label of the SQL items table
  * @param	string		$field    : label of the SQL stock quantity field
  * @param	integer		$uid      : uid of the item
  * @return	integer		$quantity : quantity in stock after decreasing
  * @access public
  * @version    4.0.7
  * @since      4.0.7
  */
  public function getItemQuantity( $item )
  {
      // RETURN indefinitely quantity : stockmanagement isn't enabled
    if( $this->stateIsDisabled( $item ) )
    {
      return $this->quantityIndefinitely;
    }

      // RETURN : quantity of the item in stock
    $stockquantity = intval( $item['stockquantity'] );

    if( $stockquantity < 0 )
    {
      $stockquantity = 0;
    }
    return intval( $stockquantity );
  }

 /**
  * getStateIsDisabled( )  : Returns a prompt, if inventory control is disabled
  *
  * @return	string		$prompt : Cause of the disabling
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  public function getStateIsDisabled( )
  {
    return $this->stateIsDisabled( );
  }



 /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function init( )
  {
    $this->initInstances( );
  }

 /**
  * initInstances( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function initInstances( )
  {
    if( ! ( $this->initInstances === null ) )
    {
      return;
    }

    $this->initInstancesSession( );

    $this->initInstances = true;
  }

 /**
  * initInstancesSession( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function initInstancesSession( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'Resources/Private/Lib/';

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session          = t3lib_div::makeInstance( 'tx_caddy_session' );
    $this->session->setParentObject( $this );
  }



 /***********************************************
  *
  * Prompts
  *
  **********************************************/

 /**
  * dieQtyError( )  :
  *
  * @param	string		$title      : item title
  * @param	integer		$qtyByOrder : quantity of the item for ordering
  * @param	integer		$qtyInStock : quantity of the item in the stock
  * @access public
  * @version    4.0.7
  * @since      4.0.7
  */
  public function dieQtyError( $title, $qtyByOrder, $qtyInStock )
  {
    switch( true )
    {
      case( $qtyByOrder > 0 ):
        $prompt = 'Sorry, but the item "' . $title . '" isn\'t available ' . $qtyByOrder . ' times any longer. '
                . 'Please go back to the order form, decrease the wanted quantity to ' . $qtyInStock . ' and send the form again.'
                ;
        break;
      case( $qtyByOrder == 0 ):
      default:
        $prompt = 'Sorry, but the item "' . $title . '" isn\'t available any longer. '
                . 'Please go back to the order form, remove the item and send the form again.'
                ;
        break;
    }

    die( $prompt );
  }



 /***********************************************
  *
  * Items
  *
  **********************************************/

 /**
  * itemsUpdate( ) :
  *
  * @param	array		$items :
  * @return	void
  * @access public
  * @version    4.0.7
  * @since      4.0.7
  */
  public function itemsUpdate( $items )
  {
    foreach( $items as $item )
    {
      $this->itemsUpdateItem( $item );
    }
  }

 /**
  * itemsUpdateItem( ) :
  *
  * @param	array		$item :
  * @return	void
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function itemsUpdateItem( $item )
  {
    if( $this->stateIsDisabled( $item ) )
    {
      return;
    }

    $table          = $this->conf['db.']['table'];
    $stockquantity  = $this->conf['db.']['stockquantity'];
    $orderquantity  = $item['qty'];
    $uid            = $item['uid'];

      // Build the query
    $query  = 'UPDATE ' . $table . ' '
            . 'SET ' . $stockquantity . ' = ' . $stockquantity . ' - ' . $orderquantity . ' '
            . 'WHERE  uid = ' . $uid ;

      // DRS - Development Reporting System
    if( ! $this->drs->drsStockmanager )
    {
      t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $query, $this->pObj->extKey, 0 );
    }
      // DRS - Development Reporting System

      // Execute the query
    $GLOBALS['TYPO3_DB']->sql_query( $query );

      // Evaluate the query
    $affected_rows  = $GLOBALS['TYPO3_DB']->sql_affected_rows( );
    $error          = $GLOBALS['TYPO3_DB']->sql_error( );


      ///////////////////////////////////////////////////////////////////////////////
      //
      // ERROR: debug report in the frontend

    if( $error )
    {
      die( );
    }
      // ERROR: debug report in the frontend

    if( $affected_rows )
    {
      $TCE = t3lib_div::makeInstance('t3lib_TCEmain');
      $TCE->admin = 1;
      $TCE->clear_cacheCmd('pages');
    }

    return $affected_rows;
  }



 /***********************************************
  *
  * Set Methods
  *
  **********************************************/

 /**
  * setItemQuantity( )  : Changes the quantity of the given item in stock.
  *                       If given quantity is minus, quantity in stock will decreased.
  *
  * @param	string		$table    : label of the SQL items table
  * @param	string		$field    : label of the SQL stock quantity field
  * @param	integer		$uid      : uid of the item
  * @param	integer		$quantity : the quantity for decreasing
  * @return	integer		$quantity : quantity in stock after decreasing
  * @access public
  * @version    4.0.7
  * @since      4.0.7
  */
  public function setItemQuantity( $table, $field, $uid, $quantity )
  {
    if( $this->stateIsDisabled ( ) )
    {
      return $quantity;
    }
      // #54628, 131229, dwildt, 1+
    $this->init( );

var_dump( __METHOD__, __LINE__ );

    return $quantity;
  }

 /**
  * setItemsDecrease( )  : Changes the quantity of the given item in stock.
  *                       If given quantity is minus, quantity in stock will decreased.
  *
  * @param	array		$table    : label of the SQL items table
  * @return	integer		$quantity : quantity in stock after decreasing
  * @access public
  * @version    4.0.7
  * @since      4.0.7
  */
  public function setItemsDecrease( $pid )
  {
    if( $this->stateIsDisabled( ) )
    {
      $prompt = ':TODO: stateisdisabled( )';
      var_dump( __METHOD__, __LINE__, $prompt );
      die( );
    }

    $this->initPidCaddy( $pid );
    $items = $this->session->productsGet( $this->pidCaddy );
// 141010: Returns noting ????
var_dump( __METHOD__, __LINE__, $items );
die( );


    return;
  }

 /**
  * setParentObject( )  : Set the parent object
  *
  * @param	object		$pObj: parent object
  * @return	void
  * @access public
  * @version    4.0.7
  * @since      4.0.7
  */
  public function setParentObject( $pObj )
  {
    if( ! is_object( $pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->pObj = $pObj;

    $this->setParentObjectConf( );
    $this->setParentObjectDrs( );
  }

 /**
  * setParentObjectConf( )  : Set the TypoScript configuration
  *
  * @return	void
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function setParentObjectConf( )
  {
    $conf = $this->pObj->getConf( );

    if( ! is_array( $conf ) || empty( $conf ) )
    {
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
//          'TYPO3 Caddy<br />' . PHP_EOL .
//        __METHOD__ . ' (' . __LINE__ . ')';
//$prompt = str_replace( '//', PHP_EOL . '//', $prompt );
//var_dump( __METHOD__, __LINE__, $prompt );
      $prompt = 'ERROR: no configuration!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->conf = $conf;
  }

 /**
  * setParentObjectDrs( )  : Set the DRS object
  *
  * @return	void
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function setParentObjectDrs( )
  {
    $drs = $this->pObj->getDrs( );

    if( ! is_object( $drs ) || empty( $drs ) )
    {
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
//          'TYPO3 Caddy<br />' . PHP_EOL .
//        __METHOD__ . ' (' . __LINE__ . ')';
//$prompt = str_replace( '//', PHP_EOL . '//', $prompt );
//var_dump( __METHOD__, __LINE__, $prompt );
      $prompt = 'ERROR: no DRS!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->drs = $drs;
  }

 /**
  * setParentObjectPid( )  : Set the page id of the page with the current Caddy plugin
  *
  * @return	void
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function setParentObjectPid( )
  {
    $pid = $this->pObj->getPid( );

    if( ! empty( $pid ) )
    {
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
//          'TYPO3 Caddy<br />' . PHP_EOL .
//        __METHOD__ . ' (' . __LINE__ . ')';
//$prompt = str_replace( '//', PHP_EOL . '//', $prompt );
//var_dump( __METHOD__, __LINE__, $prompt );
      $prompt = 'ERROR: no pid!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );

    }
    $this->pid = $pid;
  }



  /***********************************************
  *
  * State
  *
  **********************************************/

 /**
  * stateIsDisabled( ) : Returns a prompt, if inventory control is disabled
  *
  * @return	boolean		        $stateIsDisabled  : true;
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stateIsDisabled( $item )
  {
    $stateIsDisabled = false;

    switch( true )
    {
      case( $this->stateIsDisabledByCaddy( ) ):
        $stateIsDisabled = true;
        break;
      case( $this->stateIsDisabledByShop( ) ):
        $stateIsDisabled = true;
        break;
      case( $this->stateIsDisabledByItem( $item ) ):
        $stateIsDisabled = true;
        break;
      default:
        break;
    }

    unset( $item );

    return $stateIsDisabled;
  }

 /**
  * stateIsDisabledByCaddy( ) : Returns true, if inventory control is disabled by Caddy
  *
  * @return	boolean
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stateIsDisabledByCaddy( )
  {
    $disabled = ! $this->conf['api.']['inventorycontrol.']['enabled'];

      // RETURN : no DRS
    if( ! $this->drs->drsStockmanager )
    {
      return ! $disabled;
    }
      // RETURN : no DRS

      // DRS
    switch( $disabled )
    {
      case( false ):
        $prompt = 'Inventory control is enabled by Caddy.';
        t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $prompt, $this->extKey, 0 );
        break;
      case( true ):
      default:
        $prompt = 'Inventory control is disabled by Caddy.';
        t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $prompt, $this->extKey, 0 );
        break;
    }
    $prompt = 'See Constant Editor > Category [CADDY - INVENTORY CONTROL]';
    t3lib_div::devlog( '[HELP/STOCKMANAGER] ' . $prompt, $this->extKey, 1 );
      // DRS

    return $disabled;
  }

 /**
  * stateIsDisabledByItem( ) : Returns true, if inventory control is disabled by teh current item
  *
  * @return	boolean
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stateIsDisabledByItem( $item )
  {
    $disabled = ! $item['stockmanagement'];

      // RETURN : no DRS
    if( ! $this->drs->drsStockmanager )
    {
      return $disabled;
    }
      // RETURN : no DRS

      // DRS
    switch( $disabled )
    {
      case( false ):
        $prompt = 'Inventory control is enabled by the item.';
        t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $prompt, $this->extKey, 0 );
        break;
      case( true ):
      default:
        $prompt = 'Inventory control is disabled by the item.';
        t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $prompt, $this->extKey, 0 );
        break;
    }
    $prompt = 'See the stockmanager field of the item.';
    t3lib_div::devlog( '[HELP/STOCKMANAGER] ' . $prompt, $this->extKey, 1 );
      // DRS

    return $disabled;
  }

 /**
  * stateIsDisabledByShop( ) : Returns true, if inventory control is disabled by the shop
  *
  * @return	boolean
  * @access private
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stateIsDisabledByShop( )
  {
    $disabled = false;

    $stockquantity    = $this->conf['db.']['stockquantity'];
    $stockmanagement  = $this->conf['db.']['stockmanagement'];

    switch( true )
    {
      case( ! $stockquantity    ):
      case( ! $stockmanagement  ):
        $disabled = true;
        break;
      default:
        $disabled = false;
        break;
    }

    unset( $stockquantity   );
    unset( $stockmanagement );

    // RETURN : no DRS
    if( ! $this->drs->drsStockmanager )
    {
      return $disabled;
    }
      // RETURN : no DRS

      // DRS
    switch( $disabled )
    {
      case( false ):
        $prompt = 'Inventory control is enabled by the shop.';
        t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $prompt, $this->extKey, 0 );
        break;
      case( true ):
      default:
        $prompt = 'Inventory control isn\'t enabled by the shop.';
        t3lib_div::devlog( '[INFO/STOCKMANAGER] ' . $prompt, $this->extKey, 0 );
        break;
    }
    $prompt = 'Inventory control is enabled by the shop, if the TypoScript properties db.stockquantity and db.stockmanagement are set.';
    t3lib_div::devlog( '[HELP/STOCKMANAGER] ' . $prompt, $this->extKey, 1 );
      // DRS

    return $disabled;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/Resources/Private/Lib/caddy/class.tx_caddy_stockmanager.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/Resources/Private/Lib/caddy/class.tx_caddy_stockmanager.php']);
}
?>
