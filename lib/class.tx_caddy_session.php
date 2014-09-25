<?php

/* * *************************************************************
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
 * ************************************************************* */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  117: class tx_caddy_session
 *
 *              SECTION: Getting methods - user methods
 *  147:     public function getNumberDeliveryorder( $content = '', $conf = array( ) )
 *  182:     public function getNumberInvoice( $content = '', $conf = array( ) )
 *  217:     public function getNumberOrder( $content = '', $conf = array( ) )
 *  260:     private function getQuantityItems( $pid=null )
 *  288:     private function getPid( $pid=null )
 *
 *              SECTION: Payment
 *  330:     public function paymentUpdate( $value, $pid=null )
 *  357:     public function paymentGet( $pid=null )
 *
 *              SECTION: Product
 *  396:     public function productAdd( $newProduct, $pid=null )
 *  467:     private function productAddRequirements( $product )
 *  507:     public function productDelete( $pid=null )
 *  588:     public function productGetDetails( $gpvar )
 *  609:     private function productsGetFirstKey( )
 *  627:     private function productGetDetailsSql( $gpvar )
 *  757:     private function productGetDetailsSqlIsNotNeeded( )
 *  813:     private function productGetDetailsTs( $gpvar )
 * 1003:     private function productGetVariantGpvar( )
 * 1037:     public function productGetVariantTs( $product )
 * 1073:     public function productResetErrorPrompt( $products )
 * 1095:     private function productSetQuantity( $quantity, $uid )
 * 1155:     public function productsGet( $pid = null )
 * 1172:     public function productsGetGross( $pid=null )
 *
 *              SECTION: Quantity
 * 1214:     private function quantityCheckMinMax( $product )
 * 1245:     private function quantityCheckMinMaxDrs( )
 * 1291:     private function quantityCheckMinMaxItemMax( $product )
 * 1357:     private function quantityCheckMinMaxItemMin( $product )
 * 1429:     private function quantityCheckMinMaxItemsMax( $product )
 * 1523:     private function quantityCheckMinMaxItemsMin( $product )
 * 1607:     private function quantityGet( )
 * 1643:     private function quantityGetAdd( )
 * 1702:     private function quantityGetDelete( )
 * 1726:     private function quantityGetUpdate( )
 * 1750:     private function quantityGetVariant( )
 * 1830:     public function quantityUpdate( $pid = null )
 *
 *              SECTION: Session
 * 2003:     public function sessionDelete( $content = '', $conf = array( ), $pid=null )
 * 2044:     private function sessionDeleteIncreaseNumbers( $drs, $pid )
 *
 *              SECTION: Setting methods
 * 2115:     public function setParentObject( $pObj )
 *
 *              SECTION: Shipping
 * 2209:     public function shippingUpdate( $value, $pid=null )
 * 2236:     public function shippingGet( $pid=null )
 *
 *              SECTION: Special
 * 2266:     public function specialUpdate( $specials_arr, $pid=null )
 * 2293:     public function specialGet( $pid=null )
 *
 *              SECTION: Variants
 * 2323:     private function variantsInit( $newProductUid )
 * 2403:     private function variantsMatched( $variants, $product )
 * 2450:     private function variantsSet( $variants, $product )
 *
 *              SECTION: ZZ
 * 2485:     private function zz_commaToDot( $strWiComma )
 * 2505:     private function zz_msg($str, $pos = 0, $die = 0, $prefix = 1, $id = '')
 * 2561:     private function zz_escapeStrForLike( $string )
 * 2601:     private function zz_sqlReplaceMarker( )
 *
 * TOTAL FUNCTIONS: 47
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @version     4.0.7
 * @since       1.4.6
 */
class tx_caddy_session
{

  public $extKey = 'caddy';
  public $prefixId = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  private $pObj = null;   // Object : the parent object
  private $conf = null;   // Array  : the TypoScript configuration of the parent object
  public $cObj = null;   // Object : the content object of the parent object
  private $drs = null;   // Object : the DRS object of the parent object

  /*   * *********************************************
   *
   * Getting methods - user methods
   *
   * ******************************************** */

  /**
   * getCObj( ) : Get the content object
   *
   * @return	object    $cObj : content object
   * @access public
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  public function getCObj()
  {
    return $this->cObj;
  }

  /**
   * getConf( ) : Get the TypoScript configuration array
   *
   * @return	array   $conf : TypoScript configuration
   * @access public
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  public function getConf()
  {
    return $this->conf;
  }

  /**
   * getDrs( ) : Get the DRS object
   *
   * @return	object   $drs : DRS object
   * @access public
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  public function getDrs()
  {
    return $this->drs;
  }

  /**
   * getNumberDeliveryorder( )  : Get the current order number
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @param	[type]		$conf: ...
   * @return	string
   * @access public
   * @internal    #54634
   * @version     4.0.3
   * @since       2.0.0
   */
  public function getNumberDeliveryorder( $content = '', $conf = array() )
  {
    unset( $content );
    $this->conf = $conf;

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    $pid = $this->getPid();

    // #54634, 131128, dwildt, 1-
    //$sesArray       = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
    $currentNumber = $sesArray[ 'numberDeliveryorderCurrent' ];
    return $currentNumber;
  }

  /**
   * getNumberInvoice( )  : Get the current order number
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @param	[type]		$conf: ...
   * @return	string
   * @access public
   * @internal    #54634
   * @version     4.0.3
   * @since       2.0.0
   */
  public function getNumberInvoice( $content = '', $conf = array() )
  {
    unset( $content );
    $this->conf = $conf;

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    $pid = $this->getPid();

    // #54634, 131128, dwildt, 1-
    //$sesArray       = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
    $currentNumber = $sesArray[ 'numberInvoiceCurrent' ];
    return $currentNumber;
  }

  /**
   * getNumberOrder( )  : Get the current order number
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @param	[type]		$conf: ...
   * @return	string
   * @access public
   * @internal    #54634
   * @version     4.0.3
   * @since       2.0.0
   */
  public function getNumberOrder( $content = '', $conf = array() )
  {
    unset( $content );
    $this->conf = $conf;

    // DRS
    if ( $this->conf[ 'userFunc.' ][ 'drs' ] )
    {
      $this->drsUserfunc = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    $pid = $this->getPid();

    // #54634, 131128, dwildt, 1-
    //$sesArray       = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
    $currentNumber = $sesArray[ 'numberOrderCurrent' ];
//    if( $this->drs->drsInfo )
//    {
//      $prompt = '$GLOBALS["TSFE"]->id = ' . $GLOBALS["TSFE"]->id;
//      t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
//      $prompt = 'pid = ' . $pid;
//      t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
//      $prompt = 'ordernumber = ' . $currentNumber;
//      t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
//      exit;
//    }
    return $currentNumber;
  }

  /**
   * getQuantityItems( )  : Get the amount of quantities
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @return	integer		$quantityItems :
   * @access private
   * @version     3.0.1
   * @since       2.0.0
   */
  private function getQuantityItems( $pid = null )
  {
    $quantityItems = 0;
    $pid = $this->getPid( $pid );

    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
    $products = $sesArray[ 'products' ];

    foreach ( $products as $product )
    {
      $quantityItems = $quantityItems + $product[ 'qty' ];
    }
    return ( int ) $quantityItems;
  }

  /**
   * getPid( )  : Returns the globlas tsfe id, if the given pid is null
   *
   * @param	integer		$pid  : given pid (may be null)
   * @return	integer		$pid  : id of the page with the caddy plugin
   * @internal    #54634
   * @access      public
   * @version     4.0.7
   * @since       3.0.1
   */
  public function getPid( $pid = null )
  {
    // #54634, 140103, dwildt, 9+
    if ( ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ] )
    {
      $pid = ( int ) $this->conf[ 'userFunc.' ][ 'caddyPid' ];
    }

    // #i0045, 140103, dwildt, 1-
    //if( $pid === null )
    // #i0045, 140103, dwildt, 1+
    if ( $pid === null || $pid === '' )
    {
      $pid = $GLOBALS[ "TSFE" ]->id;
      if ( $this->drs->drsError || $this->drsUserfunc )
      {
        $prompt = 'Given pid of the Caddy page is empty.';
        t3lib_div::devlog( '[INFO/SESSION] ' . $prompt, $this->extKey, 0 );
        $prompt = 'pid is set to the pid of the current page: #' . $pid;
        t3lib_div::devlog( '[HELP/SESSION] ' . $prompt, $this->extKey, 1 );
      }
    }

    return $pid;
  }

  /**
   * getItemFromDatabase( ) : Get the item data from database
   *
   * @param	integer		$uid  : uid of the current item
   * @return	array		$item : SQL row of the current item
   * @internal    #54634
   * @access      public
   * @version     4.0.7
   * @since       4.0.7
   */
  public function getItemFromDatabase( $uid )
  {
    $item = $this->productGetDetailsTs( $uid );
    return $item;
  }

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init( )
   *
   * @return	void
   * @access private
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  private function init()
  {
    $this->initVars();
    $this->initInstances();
  }

  /**
   * initInstances( )
   *
   * @return	void
   * @access private
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  private function initInstances()
  {
    if ( !( $this->initInstances === null ) )
    {
      return;
    }

    $this->initInstancesStockmanager();

    $this->initInstances = true;
  }

  /**
   * initInstancesStockmanager( )
   *
   * @return	void
   * @access private
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  private function initInstancesStockmanager()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy_stockmanager.php' );
    $this->stockmanager = t3lib_div::makeInstance( 'tx_caddy_stockmanager' );
    $this->stockmanager->setParentObject( $this );
  }

  /**
   * initVars( )
   *
   * @return	void
   * @access private
   * @internal   #55726
   * @version    4.0.7
   * @since      4.0.7
   */
  private function initVars()
  {
    $this->conf = $this->pObj->conf;
    $this->cObj = $this->pObj->cObj;
    $this->drs = $this->pObj->drs;
  }

  /*   * *********************************************
   *
   * Payment
   *
   * ******************************************** */

  /**
   * paymentUpdate( ) : Change the payment method in session
   *
   * @param	integer		: $value
   * @param	integer		: $pid   : uid of the page, which contains the caddy plugin
   * @return	void
   * @access public
   * @version     3.0.1
   * @since       1.4.6
   */
  public function paymentUpdate( $value, $pid = null )
  {
    $pid = $this->getPid( $pid );

    // get already exting products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray       = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
    $sesArray[ 'payment' ] = intval( $value );

    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray);
    // #54634, 131128, dwildt, 1+
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * paymentGet( )  : get the payment method from session
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @return	integer
   * @access public
   * @version     3.0.1
   * @since       1.4.6
   */
  public function paymentGet( $pid = null )
  {
    $pid = $this->getPid( $pid );

    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    return $sesArray[ 'payment' ];
  }

  /*   * *********************************************
   *
   * Product
   *
   * ******************************************** */

  /**
   * productAdd( )  : Add product to session
   *
   *    array (
   *      'title' => 'this is the title',
   *      'amount' => 2,
   *      'gross' => '1,49',
   *      'tax' => 1,
   *      'uid' => 234,
   *      'sku' => 'P234whatever'
   *    )
   *
   * @param	array		: $newProduct :
   * @param	integer		: $pid        : uid of the page, which contains the caddy plugin
   * @return	void
   * @access public
   * @version     4.0.7
   * @since       2.0.11
   */
  public function productAdd( $newProduct, $pid = null )
  {
    $variants = null;
    $sesArray = array();

    // RETURN : requirements aren't matched
    if ( !$this->productAddRequirements( $newProduct ) )
    {
      return false;
    }
    // RETURN : requirements aren't matched
    // #55726, 140206, dwildt, 1+
    $this->init();

    // Get current products
    $currProducts = $this->productsGet( $pid );
    // Reset error messages
    $currProducts = $this->productResetErrorPrompt( $currProducts );
    // Init variants
    $variants = $this->variantsInit( $newProduct[ 'uid' ] );

    // remove product from current products, sum quantity
    // FOREACH  : current product
    foreach ( ( array ) $currProducts as $key => $currProduct )
    {
      $matchedVariants = $this->variantsMatched( $variants, $currProduct );

      // all conditions fit
      if ( $matchedVariants == count( $variants ) )
      {
        $newProduct[ 'qty' ] = $currProducts[ $key ][ 'qty' ] + $newProduct[ 'qty' ];
        // remove product from current products
        unset( $currProducts[ $key ] );
      }
    }
    // FOREACH  : current product
    // remove product from current products, sum quantity
    // limit quantity
    $newProduct = $this->quantityCheckMinMax( $newProduct );

    // move comma to dot
    $newProduct[ 'gross' ] = $this->zz_commaToDot( $newProduct[ 'gross' ] );

    $newProduct = $this->variantsSet( $variants, $newProduct );

    // add product to the session array
    $currProducts[] = $newProduct;

    $pid = $this->getPid( $pid );
    // #i0044, 140206, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
    // Overwrite products
    $sesArray[ 'products' ] = $currProducts;

    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    // save session
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * productAddRequirements( ) :
   *
   * @param	array		$product:
   * @return	boolean		true, if requirements are matched
   * @access private
   * @internal    #i0024
   * @version     2.0.11
   * @since       2.0.11
   */
  private function productAddRequirements( $product )
  {
    // RETURN : without price or without title
    switch ( true )
    {
      case( empty( $product[ 'gross' ] ) ):
        // DRS
        if ( $this->drs->drsWarn )
        {
          $prompt = 'Aborted: Item record is without the element "gross"!';
          t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 3 );
        }
        return false;
      case( empty( $product[ 'title' ] ) ):
        // DRS
        if ( $this->drs->drsWarn )
        {
          $prompt = 'Aborted: Item record is without the element "title"!';
          t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 3 );
        }
        return false;
    }
    // RETURN : without price or without title

    unset( $product );

    return true;
  }

  /**
   * productDelete( )  : Remove product from session with given uid
   *
   * @param	integer		: $pid   : uid of the page, which contains the caddy plugin
   * @return	void
   * @version  4.0.7
   * @since    1.4.6
   */
  public function productDelete( $pid = null )
  {
    // #55726, 140206, dwildt, 1+
    $this->init();

    $pid = $this->getPid( $pid );

    // variants
    // add variant key/value pairs from piVars
    $arr_variant = $this->productGetVariantGpvar();
    // add product id to variant array
    $arr_variant[ 'uid' ] = $this->pObj->piVars[ 'del' ];
    // get already exting products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    // loop every product
    foreach ( array_keys( ( array ) $sesArray[ 'products' ] ) as $key )
    {
      // Reset error messages
      unset( $sesArray[ 'products' ][ $key ][ 'error' ] );

      // Counter for condition
      $int_counter = 0;

      // loop through conditions
      foreach ( $arr_variant as $key_variant => $value_variant )
      {
        // condition fits
        if ( $sesArray[ 'products' ][ $key ][ $key_variant ] == $value_variant )
        {
          $int_counter++;
        }
      }
      // loop through conditions
      // all conditions fit
      if ( $int_counter == count( $arr_variant ) )
      {
        // remove product from session
        unset( $sesArray[ 'products' ][ $key ] );
      }
    }
    // loop every product
    // generate new session
    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131128, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    // save session
    $GLOBALS[ 'TSFE' ]->storeSessionData();


    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
    $productId = $this->productsGetFirstKey();
    if ( empty( $productId ) )
    {
      return;
    }
    $sesArray[ 'products' ][ $productId ] = $this->quantityCheckMinMax( $sesArray[ 'products' ][ $productId ] );
    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131128, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    // save session
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * read product details (title, price from table)
   * the method productGetDetails of version 1.2.1 became productGetDetailsTs from version 1.2.2
   *
   * @param	array		$gpvar: array with product uid, title, tax, etc...
   * @return	array		$arr: array with title and price
   * @version 4.0.7
   * @since 1.2.2
   */
  public function productGetDetails( $gpvar )
  {
    $product = null;

    // build own sql query
    // handle query by db.sql
    if ( !empty( $this->pObj->conf[ 'db.' ][ 'sql' ] ) )
    {
      $product = $this->productGetDetailsSql( $gpvar );
      return $product;
    }

    // handle query by db.table and db.fields
    $product = $this->productGetDetailsTs( $gpvar[ 'uid' ] );
    return $product;
  }

  /**
   * productsGetFirstKey( ) :
   *
   * @return	integer		$uid: uid of the first item in the caddy
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function productsGetFirstKey()
  {
    $products = $this->productsGet();
    $productsKey = array_keys( $products );
    $uid = $productsKey[ 0 ];

    return $uid;
  }

  /**
   * productGetDetailsSql( )  :   read product details by a manually configured sql query
   *
   * @param	array		$gpvar: array with product uid, title, tax, etc...
   * @return	array		$arr: array with title and price
   * @access private
   * @version 2.0.11
   * @since 1.4.6
   */
  private function productGetDetailsSql( $gpvar )
  {
    // RETURN : any sql result isn't needed
    if ( $this->productGetDetailsSqlIsNotNeeded() )
    {
      return false;
    }
    // RETURN : any sql result isn't needed
    // replace gp:marker and enable_fields:marker in $pObj->conf['db.']['sql']
    $this->zz_sqlReplaceMarker();
    // #42154, 101218, dwildt, 1-
    //$query = $pObj->cObj->stdWrap($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
    // #42154, 101218, dwildt, 1+
    $name = $this->pObj->conf[ 'db.' ][ 'sql' ];
    $conf = $this->pObj->conf[ 'db.' ][ 'sql.' ];
    $query = $this->pObj->cObj->cObjGetSingle( $name, $conf );
    // execute the query
    $res = $GLOBALS[ 'TYPO3_DB' ]->sql_query( $query );
    $error = $GLOBALS[ 'TYPO3_DB' ]->sql_error();

    // exit in case of error
    if ( $error )
    {
      // DRS
      if ( $this->drs->drsError )
      {
        $prompt = $query;
        t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->extKey, 3 );
        $prompt = $error;
        t3lib_div::devlog( '[ERROR/SQL] ' . $prompt, $this->extKey, 2 );
      }

      $GP = t3lib_div::_GET() + t3lib_div::_POST()
      ;

      // DRS
      $prompt = '<h1>caddy: SQL-Error</h1>' . PHP_EOL
              . '<p>' . $error . '</p>' . PHP_EOL
              . '<p>' . $query . '</p>' . PHP_EOL
              . '<p>' . PHP_EOL
              . '<pre>' . PHP_EOL
              . var_export( $GP, true ) . PHP_EOL
              . '</pre>' . PHP_EOL
              . 'Please take care for a proper configuration at plugin.tx_caddy_pi1.db.sql<br />' . PHP_EOL
              . 'Sorry for the trouble.<br />' . PHP_EOL
              . 'TYPO3 Caddy<br />' . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')'
              . '</p>' . PHP_EOL
      ;
      die( $prompt );
    }
//var_dump( __METHOD__, __LINE__, $query );

    // DRS
    if ( $this->drs->drsSql )
    {
      $prompt = $query;
      t3lib_div::devlog( '[OK/SQL] ' . $prompt, $this->extKey, -1 );
    }
    // DRS
    // RETURN false : no SQL ressource
    if ( !$res )
    {
      return false;
    }
    // RETURN false : no SQL ressource
    // WHILE  : get a row with a title field
    while ( $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res ) )
    {
      if ( $row[ 'title' ] != null )
      {
        break;
      }
    }
    // WHILE  : get a row with a title field

    if ( empty( $row ) )
    {
      $GP = t3lib_div::_GET() + t3lib_div::_POST()
      ;

      // DRS
      $prompt = '<h1>caddy: SQL-Error</h1>' . PHP_EOL
              . '<p>SQL query has an unexpected result: no row with a title field!</p>' . PHP_EOL
              . '<p>Maybe the SQL result is empty.</p>' . PHP_EOL
              . '<p>' . $query . '</p>' . PHP_EOL
              . '<p>' . PHP_EOL
              . '<pre>' . PHP_EOL
              . var_export( $GP, true ) . PHP_EOL
              . '</pre>' . PHP_EOL
              . 'Please take care for a proper configuration at plugin.tx_caddy_pi1.db.sql<br />' . PHP_EOL
              . 'Sorry for the trouble.<br />' . PHP_EOL
              . 'TYPO3 Caddy<br />' . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')'
              . '</p>' . PHP_EOL
      ;
//var_dump( __METHOD__, __LINE__, t3lib_div::_GET( ), t3lib_div::_POST( ) );
      echo $prompt;
      //die( $prompt );
      return false;
    }

    // Add or overwrite uid field
    $row[ 'uid' ] = $gpvar[ 'uid' ];

    // DRS
    if ( $this->drs->drsSql )
    {
      $prompt = var_export( $row, true );
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $row;
  }

  /**
   * productGetDetailsSqlIsNotNeeded( )  :
   *
   * @return	boolean		true, if SQL result is not needed
   * @access private
   * @version 2.0.11
   * @since   2.0.11
   */
  private function productGetDetailsSqlIsNotNeeded()
  {
    // RETURN : there isn't any GET or POST parameter
//var_dump( __METHOD__, __LINE__, t3lib_div::_GET( ), t3lib_div::_POST( ) );
    if ( (!t3lib_div::_GET() ) && (!t3lib_div::_POST() ) )
    {
      return true;
    }
    // RETURN : there isn't any GET or POST parameter

    $GP = t3lib_div::_GET() + t3lib_div::_POST()
    ;
//var_dump( $GP, $this->pObj->piVars );
    // RETURN : no sql result needed
    switch ( true )
    {
      // caddy is called without the page id only
      case( count( $GP ) == 1 && empty( $this->pObj->piVars ) ):
      // case is update of quantity only
      case( intval( $this->pObj->piVars[ 'updateByCaddy' ] ) ):
      // case is removing the current item
      case( intval( $this->pObj->piVars[ 'del' ] ) ):
      // case is confirmation of ordering
      case( $GP[ 'tx_powermail_pi1' ][ 'action' ] == 'confirmation' ):
      // case is ordering is sent
      case( $GP[ 'tx_powermail_pi1' ][ 'action' ] == 'create' ):
      // ordering is canceled
      case( $GP[ 'tx_powermail_pi1' ][ 'action' ] == 'form' ):
        return true;
        break;
      // 130913, dwildt, 3+
      case( isset( $GP[ 'tx_powermail_pi1' ][ 'mailID' ] ) ):
        return true;
        break;
      default:
        // Follow the workflow
        break;
    }
    // RETURN : no sql result needed

    unset( $GP );

    return false;
  }

  /**
   * productGetDetailsTs( ) : read product details (title, price from table)
   *
   * @param	integer		$uid: uid of the current product
   * @return	array		$arr: array with title and price
   * @access private
   * @version 4.0.7
   * @since 1.4.6
   */
  private function productGetDetailsTs( $uid )
  {
// 140207, dwildt, -
//    switch( true )
//    {
//      case( empty( $gpvar['title']  ) ):
//      case( empty( $gpvar['gross']  ) ):
//      case( empty( $gpvar['tax']    ) ):
//          // follow the workflow
//        break;
//      default:
//        return $gpvar;
//    }
//    $uid = intval( $gpvar['uid'] );
    // 140207, dwildt, 1-
//    if( $uid === 0 )
    if ( empty( $uid ) )
    {
      return false;
    }

    $table = $this->pObj->conf[ 'db.' ][ 'table' ];
    $select = $this->productGetDetailsTsSqlSelect();

    // #49428, 130628, dwildt, +
    // Load the TCA
    if ( !is_array( $GLOBALS[ 'TCA' ][ $table ][ 'columns' ] ) )
    {
      t3lib_div::loadTCA( $table );
    }
    // Load the TCA
    // Load language fields
    $languageField = null;
    $transOrigPointerField = null;
    $boolIsTranslated = true;
    if ( isset( $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ] ) )
    {
      $languageField = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'languageField' ];
    }
    else
    {
      $boolIsTranslated = false;
    }
    if ( isset( $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'transOrigPointerField' ] ) )
    {
      $transOrigPointerField = $GLOBALS[ 'TCA' ][ $table ][ 'ctrl' ][ 'transOrigPointerField' ];
    }
    else
    {
      $boolIsTranslated = false;
    }
    // Load language fields
    // andWhere in dependence of localisation status
    switch ( $boolIsTranslated )
    {
      case( true ):
        $where = ' ( ' . $table . '.uid = ' . $uid . ' OR ' . $transOrigPointerField . '  = ' . $uid . ' ) '
                . 'AND ' . $languageField . ' = ' . $GLOBALS[ 'TSFE' ]->sys_language_uid . ' '
                . tslib_cObj::enableFields( $table )
        ;
        break;
      case( false ):
      default:
        $where = $table . '.uid = ' . $uid . ' '
                . tslib_cObj::enableFields( $table )
        ;
        break;
    }

    $groupBy = null;
    $orderBy = null;
    $limit = 1;

    $res = $GLOBALS[ 'TYPO3_DB' ]->exec_SELECTquery( $select, $table, $where, $groupBy, $orderBy, $limit );
    $error = $GLOBALS[ 'TYPO3_DB' ]->sql_error();

    if ( $error )
    {
      $query = $GLOBALS[ 'TYPO3_DB' ]->SELECTquery( $select, $table, $where, $groupBy, $orderBy, $limit );
      $prompt = '<h1>caddy: SQL-Error</h1>' . PHP_EOL
              . '<p>' . $error . '</p>' . PHP_EOL
              . '<p>' . $query . '</p>' . PHP_EOL
              . '<p>' . PHP_EOL
              . 'Please take care for a proper configuration at plugin.tx_caddy_pi1.db<br />' . PHP_EOL
              . 'Sorry for the trouble.<br />' . PHP_EOL
              . 'TYPO3 Caddy<br />' . PHP_EOL
              . __METHOD__ . ' (' . __LINE__ . ')';
      ;
      die( $prompt );
    }

    $row = $GLOBALS[ 'TYPO3_DB' ]->sql_fetch_assoc( $res );
//var_dump( __METHOD__, __LINE__, $row );
    return $row;
// 140207, dwildt, -
//    $arr = array (
//        'title' => $row[$this->pObj->conf['db.']['title']],
//        'gross' => $row[$this->pObj->conf['db.']['gross']],
//        'tax'   => $row[$this->pObj->conf['db.']['tax']],
//        'uid'   => $gpvar['uid']
//    );
//    if ($row[$sku])
//    {
//      $arr['sku'] = $row[$sku];
//    }
//    if ($row[$min])
//    {
//      $arr['min'] = $row[$min];
//    }
//    if ($row[$max])
//    {
//      $arr['max'] = $row[$max];
//    }
//    if ($row[$service_attribute_1])
//    {
//      $arr['service_attribute_1'] = $row[$service_attribute_1];
//    }
//    if ($row[$service_attribute_2])
//    {
//      $arr['service_attribute_2'] = $row[$service_attribute_2];
//    }
//    if ($row[$service_attribute_3])
//    {
//      $arr['service_attribute_3'] = $row[$service_attribute_3];
//    }
//var_dump( __METHOD__, __LINE__, $arr );
//    return $arr;
  }

  /**
   * productGetDetailsTsSqlSelect( ) : Get teh SQL select statement
   *
   * @return	string      $select :
   * @access private
   * @version 4.0.7
   * @since 4.0.7
   */
  private function productGetDetailsTsSqlSelect()
  {
    $table = $this->pObj->conf[ 'db.' ][ 'table' ];

    $select = null;

    foreach ( $this->pObj->conf[ 'db.' ] as $property => $label )
    {
      switch ( true )
      {
        case( $property == 'table' ):
        case( empty( $label ) ):
          continue 2;
        default:
          // follow the workflow
          break;
      }
      $fields[] = $table . '.' . $label . ' AS ' . $property;
      unset( $property );
    }
    $select = implode( ', ', $fields );

    return $select;
  }

  /**
   * productGetVariantGpvar(): Get variant values from piVars
   *                              variant values have to be content of
   *                              ts array variant and of piVars
   *
   * @return	array		$arr_variant: array with variant key/value pairs
   * @access private
   * @version 6.0.0
   * @since 1.4.6
   */
  private function productGetVariantGpvar()
  {
    $arr_variant = null;

    // return there isn't any variant
    if ( !is_array( $this->pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      return $arr_variant;
    }
    // return there isn't any variant
    // loop through ts variant array
    foreach ( $this->pObj->conf[ 'settings.' ][ 'variant.' ] as $tableField )
    {
      // #61632, 140916, dwildt, 1+
      list($table) = explode( '.', $tableField );
      // piVars contain variant key
      if ( !empty( $this->pObj->piVars[ $tableField ] ) )
      {
        // #61632, 140916, dwildt, 1-
        //$arr_variant[ $tableField ] = $this->zz_escapeStrForLike( $this->pObj->piVars[ $tableField ] );
        // #61632, 140916, dwildt, 1+
        $arr_variant[ $tableField ] = $this->zz_escapeStrForLike( $this->pObj->piVars[ $tableField ], $table );
      }
    }

    return $arr_variant;
  }

  /**
   * productGetVariantTs():  Get an array with the variant values
   *                                out of the current product
   *
   * @param	array		$product: array with product uid, title, tax, etc...
   * @return	array		$arr_variant: array with variant key/value pairs
   * @access public
   * @version 2.0.11
   * @since 1.4.6
   */
  public function productGetVariantTs( $product )
  {
    $arr_variant = null;

    // return there isn't any variant
    if ( !is_array( $this->pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      return null;
    }
    // return there isn't any variant
    // loop through ts array variant
    foreach ( $this->pObj->conf[ 'settings.' ][ 'variant.' ] as $key_variant )
    {
      // product contains variant key from ts
      if ( in_array( $key_variant, array_keys( $product ) ) )
      {
        $arr_variant[ $key_variant ] = $product[ $key_variant ];
        if ( empty( $arr_variant[ $key_variant ] ) )
        {
          unset( $arr_variant[ $key_variant ] );
        }
      }
    }

    return $arr_variant;
  }

  /**
   * productResetErrorPrompt( )  :
   *
   * @param	array		$products:
   * @return	array		$products:
   * @version     2.0.11
   * @since       1.4.6
   */
  public function productResetErrorPrompt( $products )
  {
    // FOREACH  : current product
    foreach ( array_keys( ( array ) $products ) as $product )
    {
      // Reset error messages
      unset( $products[ $product ][ 'error' ] );
    }

    return $products;
  }

  /**
   * productSetQuantity( )  : Returns the given quantity
   *
   * @param	integer		$quantity : current quantity of the current product
   * @param	integer		$uid      : uid of the current product
   * @return	integer		$quantity : current quantity of the current product
   * @access private
   * @version     2.0.0
   * @since       2.0.0
   */
  private function productSetQuantity( $quantity, $uid )
  {
    switch ( true )
    {
      case( $this->pObj->gpvar[ 'uid' ] ):
        // add an item
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Case: add an item';
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
          $prompt = 'Quantity of current product is set to ' . $quantity;
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        $this->pObj->gpvar[ 'qty' ] = $quantity;
        break;
      case( $this->pObj->piVars[ 'qty' ] ):
        // update items quantity
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Case: update quantity';
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
          $prompt = 'Quantity of current product is set to ' . $quantity;
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        $this->pObj->piVars[ 'qty' ][ $uid ] = $quantity;
        break;
      case( $this->pObj->piVars[ 'del' ] ):
        // update items quantity after delete
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Case: update quantity after delete';
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        break;
      default:
        $prompt = 'ERROR: no value for switch' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
                __METHOD__ . ' (' . __LINE__ . ')';
        die( $prompt );
        break;
    }

    return $quantity;
  }

  /**
   * productsGet( ) : Read products from session
   *
   * @param	integer		: $pidCaddy : uid of the page, which contains the caddy plugin
   * @return	array		: $arr      : array with all products from session
   * @version    3.0.1
   * @since      2.0.0
   */
  public function productsGet( $pid = null )
  {
    $pid = $this->getPid( $pid );
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    return $sesArray[ 'products' ];
  }

  /**
   * productsGetGross( )  : Count gross price of all products in a cart
   *                        Is used by pi3 only
   *
   * @param	integer		: $pidCaddy : uid of the page, which contains the caddy plugin
   * @return	integer
   * @version    3.0.1
   * @since      2.0.0
   */
  public function productsGetGross( $pid = null )
  {
    // #54634, 131128, dwildt, 1+
    $pid = $this->getPid( $pid );
    // get already exting products from session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    $gross = 0;
    foreach ( ( array ) $sesArray[ 'products' ] as $val )
    {
      $gross += $val[ 'gross' ] * $val[ 'qty' ];
    }

    return $gross;
  }

  /*   * ********************************************
   *
   * Quantity
   *
   * ******************************************** */

  /**
   * quantityCheckMinMax( )  : Checks
   *                           * min and max limits depending on an item (database)
   *                           * min and max limits depending on the caddy (plugin/flexform)
   *                           If a limit is passed over, quantities will updated and there will be
   *                           error prompts near the items.
   *                           min and max limits of the caddy have precedence!
   *                           Example:
   *                           * An item have a maximum limit of 2
   *                           * The caddy have a minimum limit of 4
   *                           * quantityCheckMinMax( ) will update the quantity to 4 of the product
   *
   * @param	array		$product  : the current product
   * @return	array		$product  : the current or the updated product
   * @access private
   * @version 4.0.7
   * @since 2.0.0
   */
  private function quantityCheckMinMax( $product )
  {
    // #61877, dwildt, 5+
    // RETURN without any check: variants are configured
    if ( is_array( $this->pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      return $product;
    }

    // Prompt case add, update or delete to DRS
    $this->quantityCheckMinMaxDrs();

    unset( $product[ 'error' ][ 'itemsMin' ] );
    unset( $product[ 'error' ][ 'itemsMax' ] );

    // Checks the min and max limit depending on an item (database)
    $product = $this->quantityCheckMinMaxItemMax( $product );
    $product = $this->quantityCheckMinMaxItemMin( $product );

    // Checks the min and max limit depending on the caddy (plugin/flexform)
    $product = $this->quantityCheckMinMaxItemsMax( $product );
    $product = $this->quantityCheckMinMaxItemsMin( $product );

    // #55726, 140206, dwildt, 2+
    // Checks the requirments of the stock management
    $product = $this->quantityCheckStock( $product );
    return $product;
  }

  /**
   * quantityCheckMinMaxDrs( ) : Prompt to the DRS the current case.
   *                             Possible case is
   *                             * add
   *                             * delete
   *                             * update
   *
   * @return	void
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityCheckMinMaxDrs()
  {
//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
//          'TYPO3 Caddy<br />' . PHP_EOL .
//        __METHOD__ . ' (' . __LINE__ . ')';
//$prompt = str_replace( '//', PHP_EOL . '//', $prompt );
//var_dump( __METHOD__, __LINE__, $prompt, $this->pObj->gpvar, $this->pObj->piVars );
    if ( !$this->drs->drsCalc )
    {
      return;
    }

    // SWITCH : add, update, delete
    switch ( true )
    {
      case( $this->pObj->gpvar[ 'uid' ] ):
        $prompt = 'Case: add an item';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        break;
      case( $this->pObj->piVars[ 'qty' ] ):
        $prompt = 'Case: update item quantity';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        break;
      case( $this->pObj->piVars[ 'del' ] ):
        $prompt = 'Case: delete an item';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        break;
      default:
        $prompt = 'ERROR: no value for switch' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
                __METHOD__ . ' (' . __LINE__ . ')';
        die( $prompt );
        break;
    }
    // SWITCH : add, update, delete

    return;
  }

  /**
   * quantityCheckMinMaxItemMax( ) : Checks, if the maximum quantity is within the limit.
   *                                 If not, quantity will decreased to the limit,
   *                                 and the item will get an error prompt
   *
   * @param	array		$product  : the current product
   * @return	array		$product  : the current or the updated product
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityCheckMinMaxItemMax( $product )
  {
    // RETURN : current item hasn't any max quantity limit
    if ( empty( $product[ 'max' ] ) )
    {
      // DRS
      if ( $this->drs->drsCalc )
      {
        $prompt = 'Current item (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') hasn\'t any maximum limit. Maximum limit won\'t checked.';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      unset( $product[ 'error' ][ 'max' ] );
      return $product;
    }
    // RETURN : current item hasn't any max quantity limit
    // SWITCH : limit is overrun or limit isn't overrun
    switch ( true )
    {
      case( $product[ 'qty' ] > $product[ 'max' ] ):
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Maximum limit of the current item (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') is overrun. Item #' . $product[ 'qty' ] . ', limit #' . $product[ 'max' ];
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
          $prompt = 'Quantity will setup to #' . $product[ 'max' ];
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        // limit is overrun
        $product[ 'qty' ] = $this->productSetQuantity( $product[ 'max' ], $product[ 'uid' ] );
        $llKey = 'error_max';
        $llAlt = 'No value for error_max in ' . __METHOD__ . ' (' . __LINE__ . ')';
        $llPrompt = $this->pObj->pi_getLL( $llKey, $llAlt );
        $llPrompt = sprintf( $llPrompt, $product[ 'max' ], $product[ 'max' ] );
        $product[ 'error' ][ 'max' ] = $llPrompt;
        break;
      case( $product[ 'qty' ] <= $product[ 'max' ] ):
      default:
        // limit isn't overrun
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Maximum limit of the current item (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') isn\'t overrun. Item #' . $product[ 'qty' ] . ', limit #' . $product[ 'max' ];
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        unset( $product[ 'error' ][ 'max' ] );
        break;
    }
    // SWITCH : limit is overrun or limit isn't overrun

    return $product;
  }

  /**
   * quantityCheckMinMaxItemMin( ) : Checks, if the minimum quantity is within the limit.
   *                                 If not, quantity will increased to the limit,
   *                                 and the item will get an error prompt
   *
   * @param	array		$product  : the current product
   * @return	array		$product  : the current or the updated product
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityCheckMinMaxItemMin( $product )
  {
    // RETURN : current item hasn't any min quantity limit
    if ( empty( $product[ 'min' ] ) )
    {
      // DRS
      if ( $this->drs->drsCalc )
      {
        $prompt = 'Current item (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') hasn\'t any minimum limit. Minimum limit won\'t checked.';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      unset( $product[ 'error' ][ 'min' ] );
      return $product;
    }
    // RETURN : current item hasn't any min quantity limit
    // SWITCH : limit is undercut or limit isn't undercut
    switch ( true )
    {
      case( $product[ 'qty' ] < $product[ 'min' ] ):
        // limit is undercut
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Minimum limit of the current item (' . $product[ 'title' ] . ': '
                  . $product[ 'uid' ] . ') is undercut. Item #' . $product[ 'qty' ] . ', limit #' . $product[ 'min' ];
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
          $prompt = 'Quantity will setup to #' . $product[ 'min' ];
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        // DRS

        $product[ 'qty' ] = $this->productSetQuantity( $product[ 'min' ], $product[ 'uid' ] );
        $llKey = 'error_min';
        $llAlt = 'No value for error_min in ' . __METHOD__ . ' (' . __LINE__ . ')';
        $llPrompt = $this->pObj->pi_getLL( $llKey, $llAlt );
        $llPrompt = sprintf( $llPrompt, $product[ 'min' ], $product[ 'min' ] );
        $product[ 'error' ][ 'min' ] = $llPrompt;
        break;
      case( $product[ 'qty' ] >= $product[ 'min' ] ):
      default:
        // limit isn't undercut
        // DRS
        if ( $this->drs->drsCalc )
        {
          $prompt = 'Minimum limit of the current item (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') '
                  . 'isn\'t undercut. Item #' . $product[ 'qty' ] . ', limit #' . $product[ 'min' ];
          t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        unset( $product[ 'error' ][ 'min' ] );
        break;
    }
    // SWITCH : limit is undercut or limit isn't undercut

    return $product;
  }

  /**
   * quantityCheckMinMaxItemsMax( )  : Checks max limit depending on the caddy (plugin/flexform)
   *                                         while items are updating
   *                                         If the limit is passed over, quantity will decreased and
   *                                         an error prompt will be near the item.
   *                                         It's possible, that the quantity of more than one item
   *                                         will decreased.
   *
   * @param	array		$product  : the current product
   * @return	array		$product  : the current or the updated product
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityCheckMinMaxItemsMax( $product )
  {
    $itemsQuantity = 0;

    // RETURN : max quantity for all items is unlimited
    $itemsQuantityMax = $this->pObj->flexform->originMax;
    if ( empty( $itemsQuantityMax ) )
    {
      // DRS
      if ( $this->drs->drsCalc )
      {
        $prompt = 'No maximum limit is given in the plugin/flexform. Maximum limit for all items won\'t checked.';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      unset( $product[ 'error' ][ 'itemsMax' ] );
      return $product;
    }
    // RETURN : max quantity for all items is unlimited
    // Get current quantity of all items
    $itemsQuantity = $this->quantityGet();

    // RETURN : limit for max quantity for all items isn't passed
    if ( $itemsQuantity <= $itemsQuantityMax )
    {
      // DRS
      if ( $this->drs->drsCalc )
      {
        $prompt = 'Limit for all items isn\'t overrun: Items #' . $itemsQuantity . ', limit #' . $itemsQuantityMax;
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      unset( $product[ 'error' ][ 'itemsMax' ] );
      return $product;
    }
    // RETURN : limit for max quantity for all items isn't passed
    // Limit for max quantity for all items is passed
    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Limit for all items is overrun: Items #' . $itemsQuantity . ', limit #' . $itemsQuantityMax;
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Get the overrun quantity
    $itemsQuantityOverrun = $itemsQuantity - $itemsQuantityMax
    ;

    // Decrease quantity of the current product
    $quantity = $product[ 'qty' ] - $itemsQuantityOverrun
    ;
    if ( $quantity < 1 )
    {
      $quantity = 1;
    }
    $product[ 'qty' ] = $this->productSetQuantity( $quantity, $product[ 'uid' ] );

    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Quantity for item  (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') '
              . 'will setup to #' . $product[ 'qty' ];
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Set the error prompt
    $llKey = 'error_itemsMax';
    $llAlt = 'No value for error_itemsMax in ' . __METHOD__ . ' (' . __LINE__ . ')';
    $llPrompt = $this->pObj->pi_getLL( $llKey, $llAlt );
    $llPrompt = sprintf( $llPrompt, $itemsQuantityMax, $itemsQuantityMax );
    $product[ 'error' ][ 'itemsMax' ] = $llPrompt;
    // Set the error prompt

    return $product;
  }

  /**
   * quantityCheckMinMaxItemsMin( )  : Checks min limit depending on the caddy (plugin/flexform)
   *                                         while items are updating
   *                                         If the limit is undercut, quantity will increased and
   *                                         an error prompt will be near the item.
   *                                         It's possible, that the quantity of more than one item
   *                                         will increased.
   *
   * @param	array		$product  : the current product
   * @return	array		$product  : the current or the updated product
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityCheckMinMaxItemsMin( $product )
  {
    $itemsQuantity = 0;

    // RETURN : min quantity for all items is unlimited
    $itemsQuantityMin = $this->pObj->flexform->originMin;
    if ( empty( $itemsQuantityMin ) )
    {
      // DRS
      if ( $this->drs->drsCalc )
      {
        $prompt = 'No minimum limit is given in the plugin/flexform. Minimum limit for all items won\'t checked.';
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      unset( $product[ 'error' ][ 'itemsMin' ] );
      return $product;
    }
    // RETURN : min quantity for all items is unlimited
    // Get current quantity of all items
    $itemsQuantity = $this->quantityGet();

    // RETURN : limit for min quantity for all items isn't passed
    if ( $itemsQuantity >= $itemsQuantityMin )
    {
      // DRS
      if ( $this->drs->drsCalc )
      {
        $prompt = 'Limit for all items isn\'t undercut: Items #' . $itemsQuantity . ', limit #' . $itemsQuantityMin;
        t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      unset( $product[ 'error' ][ 'itemsMin' ] );
      return $product;
    }
    // RETURN : limit for min quantity for all items isn't passed
    // Limit for min quantity for all items is passed
    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Limit for all items is undercut: Items #' . $itemsQuantity . ', limit #' . $itemsQuantityMin;
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Get the undercut quantity
    $itemsQuantityUndercut = $itemsQuantityMin - $itemsQuantity
    ;

    // INcrease quantity of the current product
    $quantity = $product[ 'qty' ] + $itemsQuantityUndercut
    ;
    $product[ 'qty' ] = $this->productSetQuantity( $quantity, $product[ 'uid' ] );

    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Quantity for item  (' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . ') '
              . 'will setup to #' . $product[ 'qty' ];
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Set the error prompt
    $llKey = 'error_itemsMin';
    $llAlt = 'No value for error_itemsMin in ' . __METHOD__ . ' (' . __LINE__ . ')';
    $llPrompt = $this->pObj->pi_getLL( $llKey, $llAlt );
    $llPrompt = sprintf( $llPrompt, $itemsQuantityMin, $itemsQuantityMin );
    $product[ 'error' ][ 'itemsMin' ] = $llPrompt;
    // Set the error prompt

    return $product;
  }

  /**
   * quantityCheckStock( )  :
   *
   * @param	array		$product  : the current product
   * @return	array		$product  : the current or the updated product
   * @access private
   * @version 4.0.7
   * @since 4.0.7
   */
  private function quantityCheckStock( $product )
  {
    $qtyByOrder = $product[ 'qty' ];
    $qtyInStock = $this->stockmanager->getItemQuantity( $product );

    if ( $qtyByOrder <= $qtyInStock )
    {
      unset( $product[ 'error' ][ 'itemStock' ] );
      return $product;
    }

    $product[ 'qty' ] = $qtyInStock;

    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Quantity by order #' . $qtyByOrder . ' is greater than quantity in stock #' . $qtyInStock . '. '
              . 'Quantity by order is set to  #' . $qtyInStock . '.'
              . 'Item: ' . $product[ 'title' ] . ': ' . $product[ 'uid' ] . '.'
      ;
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Set the error prompt
    $llKey = 'error_itemStockMax';
    if ( $qtyInStock == 1 )
    {
      $llKey = 'error_itemStockMaxOne';
    }
    $llAlt = 'No value for error_itemStock in ' . __METHOD__ . ' (' . __LINE__ . ')';
    $llPrompt = $this->pObj->pi_getLL( $llKey, $llAlt );
    $llPrompt = sprintf( $llPrompt, $qtyInStock, $qtyInStock );
    $product[ 'error' ][ 'itemStock' ] = $llPrompt;
    // Set the error prompt

    return $product;
  }

  /**
   * quantityGet( )  :
   *
   * @return	integer		$quantity : the quantity of the current items
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityGet()
  {
    $quantity = 0;

    // SWITCH : add an item or update items quantity
    switch ( true )
    {
      case( $this->pObj->gpvar[ 'uid' ] ):
        $quantity = $this->quantityGetAdd();
        break;
      case( $this->pObj->piVars[ 'qty' ] ):
        $quantity = $this->quantityGetUpdate();
        break;
      case( $this->pObj->piVars[ 'del' ] ):
        $quantity = $this->quantityGetDelete();
        break;
      default:
        $prompt = 'ERROR: no value for switch.<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
                __METHOD__ . ' (' . __LINE__ . ')';
        die( $prompt );
    }

    return $quantity;
  }

  /**
   * quantityGetAdd( )  :
   *
   * @return	integer		$quantity : the quantity of the current items
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityGetAdd()
  {
    // Default value
    $quantityAdd = ( int ) $this->pObj->gpvar[ 'qty' ];
    $quantityAll = 0;
    $quantitySum = 0;

    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Quantity of current product is: ' . $quantity;
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Get products
    $products = $this->productsGet();

    // SWITCH : products or any product
    switch ( true )
    {
      case(!empty( $products ) ):
        foreach ( ( array ) $products as $product )
        {
          $quantityAll = $quantityAll + $product[ 'qty' ]
          ;
        }
        break;
      case( empty( $products ) ):
      default:
        //$quantity = ( int ) $this->pObj->gpvar['qty'];
        break;
    }
    // SWITCH : products or any product

    $quantitySum = $quantityAll + $quantityAdd
    ;
    // DRS
    if ( $this->drs->drsCalc )
    {
      $prompt = 'Quantity: all #' . $quantityAll . ' + add #' . $quantityAdd . ' = sum #' . $quantitySum;
      t3lib_div::devlog( '[INFO/CALC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $quantitySum;
  }

  /**
   * quantityGetDelete( )  :
   *
   * @return	integer		$quantity : the quantity of the current items
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityGetDelete()
  {
    $quantity = 0;

    $products = $this->productsGet();

    foreach ( ( array ) $products as $product )
    {
      $quantity = $quantity + $product[ 'qty' ]
      ;
    }

    return $quantity;
  }

  /**
   * quantityGetUpdate( )  :
   *
   * @return	integer		$quantity : the quantity of the current items
   * @access private
   * @version 2.0.0
   * @since 2.0.0
   */
  private function quantityGetUpdate()
  {
    $quantity = 0;
var_dump(__METHOD__, __LINE__, $this->pObj->piVars[ 'qty' ] );

    foreach ( ( array ) $this->pObj->piVars[ 'qty' ] as $value )
    {
var_dump(__METHOD__, __LINE__, $value );
      $quantity = $quantity + ( int ) $value
      ;
    }

    return $quantity;
  }

  /**
   * quantityGetVariant(): Get variant values out of the name of the qty field
   *                              variant values have to be content of
   *                              ts array variant and of qty field
   *
   * @return	array		$arr_variant: array with variant key/value pairs
   * @access private
   * @version 2.0.0
   * @since 1.4.6
   */
  private function quantityGetVariant()
  {
    $arr_from_qty = null;
    $arr_qty = null;
    $arr_variant = null;

    // RETURN : there isn't any variant
    if ( !is_array( $this->pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      return $arr_variant;
    }
    // RETURN : there isn't any variant

    $int_counter = 0;
    foreach ( $this->pObj->piVars[ 'qty' ] as $key => $value )
    {
      $arr_qty[ $int_counter ][ 'qty' ][ $key ] = $value;
      $int_counter++;
    }

    foreach ( $arr_qty as $key => $piVarsQty )
    {
      // iterator object
      $data = new RecursiveArrayIterator( $piVarsQty[ 'qty' ] );
      $iterator = new RecursiveIteratorIterator( $data, true );
      // top level of ecursive array
      $iterator->rewind();

      // get all variant key/value pairs from qty name
      foreach ( $iterator as $key_iterator => $value_iterator )
      {
        // i.e for a key: tx_org_calentrance.uid=4
        list($key_variant, $value_variant) = explode( '=', $key_iterator );
        if ( $key_variant == 'uid' )
        {
          $arr_variant[ $key ][ 'uid' ] = $value_variant;
        }
        // i.e arr_var[tx_org_calentrance.uid] = 4
        $arr_from_qty[ $key ][ $key_variant ] = $value_variant;
        if ( is_array( $value_iterator ) )
        {
          list($key_variant, $value_variant) = explode( '=', key( $value_iterator ) );
          if ( $key_variant == 'uid' )
          {
            $arr_variant[ $key ][ 'uid' ] = $value_variant;
          }
          $arr_from_qty[ $key ][ $key_variant ] = $value_variant;
        }
        // value is the value of the field qty in every case
        if ( !is_array( $value_iterator ) )
        {
          $arr_variant[ $key ][ 'qty' ] = $value_iterator;
        }
      }

      // loop through ts variant array
      foreach ( $this->pObj->conf[ 'settings.' ][ 'variant.' ] as $key_variant => $tableField )
      {
        // piVars contain variant key
        if ( !empty( $arr_from_qty[ $key ][ $tableField ] ) )
        {
          $arr_variant[ $key ][ $tableField ] = $this->zz_escapeStrForLike( $arr_from_qty[ $key ][ $tableField ] );
        }
      }
    }

    return $arr_variant;
  }

  /**
   * quantityUpdate( )  : Change quantity of a product in session
   *
   * @param       integer : $pid   : uid of the page, which contains the caddy plugin
   * @return	void
   * @access private
   * @version 4.0.7
   * @since 1.4.6
   */
  // #54634, 131128, dwildt, 1-
  //public function quantityUpdate( )
  // #54634, 131128, dwildt, 1+
  public function quantityUpdate( $pid = null )
  {
    // #55726, 140206, dwildt, 1+
    $this->init();

    $pid = $this->getPid( $pid );
    // variants
    // add variant key/value pairs from piVars
    $arr_variant = $this->quantityGetVariant();

    // get products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );

    $is_cart = intval( $this->pObj->piVars[ 'updateByCaddy' ] );

    // LOOP : each product
    foreach ( array_keys( ( array ) $sesArray[ 'products' ] ) as $key_session )
    {
      // current product id
      $session_uid = $sesArray[ 'products' ][ $key_session ][ 'uid' ];

      if ( !is_array( $arr_variant ) )
      {
        // no variant, nothing to loop
        $int_qty = intval( $this->pObj->piVars[ 'qty' ][ $session_uid ] );

        if ( $int_qty > 0 )
        {
          // update quantity
          if ( $is_cart )
          {
            // update from cart then set new qty
            $sesArray[ 'products' ][ $key_session ][ 'qty' ] = $int_qty;
          }
          else
          {
            // update not from cart then add qty
            $sesArray[ 'products' ][ $key_session ][ 'qty' ] = $sesArray[ 'products' ][ $key_session ][ 'qty' ] + $int_qty;
          }
          $productId = $key_session;
        }
        else
        {
          // remove product from session
          // #54634, 131128, dwildt, 1-
          //$this->productDelete($sesArray['products'][$key_session]['uid']);
          // #54634, 131128, dwildt, 1+
          $this->productDelete( $pid );
          // remove product from current session array
          unset( $sesArray[ 'products' ][ $key_session ] );
          $productId = $this->productsGetFirstKey();
        }
        $sesArray[ 'products' ][ $productId ] = $this->quantityCheckMinMax( $sesArray[ 'products' ][ $productId ] );
      }
      else
      {
        // loop for every variant
        $arr_variant_backup = $arr_variant;
        foreach ( $arr_variant as $key_variant => $arr_condition )
        {
          if ( !isset( $arr_variant[ $key_variant ][ 'uid' ] ) )
          {
            // without variant
            $curr_uid = key( $this->pObj->piVars[ 'qty' ] );
          }
          if ( isset( $arr_variant[ $key_variant ][ 'uid' ] ) )
          {
            $curr_uid = $arr_variant[ $key_variant ][ 'uid' ];
          }
          if ( !isset( $arr_variant[ $key_variant ][ 'qty' ] ) )
          {
            // without variant
            $int_qty = intval( $this->pObj->piVars[ 'qty' ][ $curr_uid ] );
          }
          if ( isset( $arr_variant[ $key_variant ][ 'qty' ] ) )
          {
            $int_qty = intval( $arr_variant[ $key_variant ][ 'qty' ] );
          }

          // counter for condition
          $int_counter = 0;
          // uid: condition fits
          if ( $session_uid == $curr_uid )
          {
            $int_counter++;
          }

          // loop through conditions
          foreach ( $arr_condition as $key_condition => $value_condition )
          {
            // workaround (it would be better, if qty and uid won't be elements of $arr_condition
            if ( in_array( $key_condition, array( 'qty', 'uid' ) ) )
            {
              // workaround: uid and qty should fit in every case
              $int_counter++;
            }
            // workaround (it would be better, if qty and uid won't be elements of $arr_condition
            if ( !in_array( $key_condition, array( 'qty', 'uid' ) ) )
            {
              // variants: condition fits
              if ( $sesArray[ 'products' ][ $key_session ][ $key_condition ] == $value_condition )
              {
                //$prompt_315[] = 'div 315: true - session_key : ' . $key_session . ', ' . $key_condition . ' : ' . $value_condition;
                $int_counter++;
              }
            }
          }
          // loop through conditions
          // all conditions fit
          if ( $int_counter == ( count( $arr_condition ) + 1 ) )
          {
            if ( $int_qty > 0 )
            {
              if ( $is_cart )
              {
                // update from cart then set new qty
                $sesArray[ 'products' ][ $key_session ][ 'qty' ] = $int_qty;
              }
              else
              {
                // update not from cart then add qty
                $sesArray[ 'products' ][ $key_session ][ 'qty' ] = $sesArray[ 'products' ][ $key_session ][ 'qty' ] + $int_qty;
              }
//              // #61877, 140925, dwildt, 1+
//              $productId = $key_session;
            }
            else
            {
              // remove product from session
              // #54634, 131128, dwildt, 1-
              //$this->productDelete( $sesArray['products'][$key_session]['uid'] );
              // #54634, 131128, dwildt, 1+
              $this->productDelete( $pid );
              // remove product from current session array
              unset( $sesArray[ 'products' ][ $key_session ] );
//              // #61877, 140925, dwildt, 1+
//              $productId = $this->productsGetFirstKey();
            }
            // #61877, 140925, dwildt, 1+
//            $sesArray[ 'products' ][ $productId ] = $this->quantityCheckMinMax( $sesArray[ 'products' ][ $productId ] );
          }
        }
        $arr_variant = $arr_variant_backup;
        // loop every variant
      }
    }
    // LOOP : each product
    // generate new session
    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131128, dwildt, 1+
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    // save session
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /*   * *********************************************
   *
   * Session
   *
   * ******************************************** */

  /**
   * sessionDelete( )
   *
   * @param	string		: $content  : current content (will be empty)
   * @param	array		: $conf     : current TypoScript configuration
   * @param	integer		: $pid      : uid of the page, which contains the caddy plugin
   * @return	void
   * @access public
   * @version    3.0.1
   * @since      2.0.0
   */
  public function sessionDelete( $content = '', $conf = array(), $pid = null )
  {
    $pid = $this->getPid( $pid );

    // DRS
    unset( $content );
    $drs = false;
    if ( $conf[ 'userFunc.' ][ 'drs' ] )
    {
      $drs = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    if ( $this->drs->drsSession || $drs )
    {
      $prompt = 'Session is cleared.';
      t3lib_div::devlog( '[INFO/SESSION] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Increase numbers
    $this->sessionDeleteIncreaseNumbers( $drs, $pid );

    // Delete the session
    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, array( ) );
    // #54634, 131128, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, array() );
    $GLOBALS[ 'TSFE' ]->storeSessionData();
  }

  /**
   * sessionDeleteIncreaseNumbers( )
   *
   * @param	[type]		$$drs: ...
   * @param	[type]		$pid: ...
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function sessionDeleteIncreaseNumbers( $drs, $pid )
  {
    $products = $this->productsGet( $pid );

    // RETURN : any product, don't increase numbers!
    if ( empty( $products ) )
    {
      if ( $this->drs->drsSession || $drs )
      {
        $prompt = 'Session is empty! Maybe powermail form is sent twice!';
        t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
      }
      return;
    }
    // RETURN : any product, don't increase numbers!

    $registry = t3lib_div::makeInstance( 't3lib_Registry' );
    // #54634, 131128, dwildt, 1-
    //$prefix = 'page_' . $GLOBALS["TSFE"]->id . '_';
    // #54634, 131128, dwildt, 1+
    $prefix = 'page_' . $pid . '_';

    // Get current numbers
    $numberDeliveryorder = ( int ) $registry->get( 'tx_caddy', $prefix . 'deliveryorder' );
    $numberInvoice = ( int ) $registry->get( 'tx_caddy', $prefix . 'invoice' );
    $numberOrder = ( int ) $registry->get( 'tx_caddy', $prefix . 'order' );
    // Get current numbers
    // Increase current numbers
    $numberDeliveryorder = $numberDeliveryorder + 1;
    $numberInvoice = $numberInvoice + 1;
    $numberOrder = $numberOrder + 1;
    // Increase current numbers
    // Set current numbers
    $registry->set( 'tx_caddy', $prefix . 'deliveryorder', $numberDeliveryorder );
    $registry->set( 'tx_caddy', $prefix . 'invoice', $numberInvoice );
    $registry->set( 'tx_caddy', $prefix . 'order', $numberOrder );
    // Set current numbers
    // DRS
    if ( $drs )
    {
      $prompt = 'New delivery order number: ' . $numberDeliveryorder;
      t3lib_div::devlog( ' [INFO/SESSION] ' . $prompt, $this->extKey, 0 );
      $prompt = 'New invoice number: ' . $numberInvoice;
      t3lib_div::devlog( ' [INFO/SESSION] ' . $prompt, $this->extKey, 0 );
      $prompt = 'New order number: ' . $numberOrder;
      t3lib_div::devlog( ' [INFO/SESSION] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
  }

  /*   * *********************************************
   *
   * Setting methods
   *
   * ******************************************** */

  /**
   * setParentObject( )  : Returns a caddy with HTML form and HTML options among others
   *
   * @param	[type]		$$pObj: ...
   * @return	void
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function setParentObject( $pObj )
  {
    if ( !is_object( $pObj ) )
    {
      $prompt = 'ERROR: no parent object!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->pObj = $pObj;

//$prompt = 'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
//          'TYPO3 Caddy<br />' . PHP_EOL .
//        __METHOD__ . ' (' . __LINE__ . ')';
//echo $prompt;

    $this->setParentObjectDrs();
  }

  /**
   * setParentObjectDrs( )  :
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function setParentObjectDrs()
  {
    if ( !is_object( $this->pObj->drs ) )
    {
      $prompt = 'ERROR: no DRS object!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
//                'debug trail: ' . t3lib_utility_Debug::debugTrail( ) . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->drs = $this->pObj->drs;
  }

  /*   * *********************************************
   *
   * Shipping
   *
   * ******************************************** */

  /**
   * shippingUpdate( )  : Change the shipping method in session
   *
   * @param	integer		:
   * @param	integer		: $pid      : uid of the page, which contains the caddy plugin
   * @return	void
   * @version     3.0.1
   * @since       1.4.0
   */
  public function shippingUpdate( $value, $pid = null )
  {
    $pid = $this->getPid( $pid );

    // get products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    $sesArray[ 'shipping' ] = intval( $value ); // overwrite with new qty
    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray);
    // #54634, 131128, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData(); // Save session
  }

  /**
   * shippingGet( ) : Get the shipping method from session
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @return	integer
   * @version     3.0.1
   * @since       1.4.0
   */
  public function shippingGet( $pid = null )
  {
    $pid = $this->getPid( $pid );

    // get products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    return $sesArray[ 'shipping' ];
  }

  /*   * *********************************************
   *
   * Special
   *
   * ******************************************** */

  /**
   * specialUpdate( ) : Change the special method in session
   *
   * @param	array		: $arr  : array to change
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @return	void
   * @version     3.0.1
   * @since       1.4.0
   */
  public function specialUpdate( $specials_arr, $pid = null )
  {
    $pid = $this->getPid( $pid );

    // get products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    $sesArray[ 'specials' ] = $specials_arr; // overwrite with new qty
    // #54634, 131128, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray);
    // #54634, 131128, dwildt, 1+
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $pid, $sesArray );
    $GLOBALS[ 'TSFE' ]->storeSessionData(); // Save session
  }

  /**
   * specialGet( )  : get the special method from session
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @return	integer
   * @version     3.0.1
   * @since       1.4.0
   */
  public function specialGet( $pid = null )
  {
    $pid = $this->getPid( $pid );

    // get products from session
    // #54634, 131128, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);
    // #54634, 131128, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

    return $sesArray[ 'specials' ];
  }

  /*   * *********************************************
   *
   * Variants
   *
   * ******************************************** */

  /**
   * variantsInit( )  :
   *
   * @param	array		$newProduct :
   * @return	array		$variants :
   * @access private
   * @version     2.0.11
   * @since       2.0.11
   */
  private function variantsInit( $newProductUid )
  {
    $variants = array
      (
      'uid' => $newProductUid
    );

    // RETURN : variants aren't configured
    if ( !is_array( $this->pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      // DRS
      if ( $this->drs->drsVariants )
      {
        $prompt = 'settings.variant is empty: Variants aren\'t handled.';
        t3lib_div::devlog( '[INFO/VARIANTS] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return $variants;
    }
    // RETURN : variants aren't configured
    // POST has precedence!
    $GP = t3lib_div::_POST() + t3lib_div::_GET();

    // FOREACH variant
    foreach ( $this->pObj->conf[ 'settings.' ][ 'variant.' ] as $key => $tableField )
    {
      if ( stristr( $key, '.' ) )
      {
        continue;
      }

      // DRS
      if ( $this->drs->drsVariants )
      {
        $prompt = 'settings.variant. ' . $key . ' = ' . $tableField;
        t3lib_div::devlog( '[INFO/VARIANTS] ' . $prompt, $this->extKey, 0 );
      }
      // DRS

      list( $table, $field ) = explode( '.', $tableField );
      if ( !isset( $GP[ $table ][ $field ] ) )
      {
        // DRS
        if ( $this->drs->drsVariants )
        {
          $prompt = 'GET/POST[' . $table . '][' . $field . '] is not set.';
          t3lib_div::devlog( '[WARN/VARIANTS] ' . $prompt, $this->extKey, 2 );
        }
        // DRS
        continue;
      }

      $variant = $this->zz_escapeStrForLike( $GP[ $table ][ $field ] );

      // DRS
      if ( $this->drs->drsVariants )
      {
        $prompt = 'Variant: GET/POST[' . $table . '][' . $field . '] is set.';
        t3lib_div::devlog( '[OK/VARIANTS] ' . $prompt, $this->extKey, -1 );
      }
      // DRS

      $variants[ $tableField ] = $variant;
    }
    // FOREACH variant

    return $variants;
  }

  /**
   * variantsMatched( )
   *
   * @param	array		$variants :
   * @param	array		$product  :
   * @return	integer		$matchedVariants
   * @access private
   * @version     2.0.11
   * @since       2.0.11
   */
  private function variantsMatched( $variants, $product )
  {
    $matchedVariants = 0;

    // FOREACH variant
    foreach ( ( array ) $variants as $tableField => $variant )
    {
      // varaiant fits
      if ( $product[ $tableField ] == $variant )
      {
        $matchedVariants++;
      }
    }
    // FOREACH variant
    // DRS
    if ( $this->drs->drsVariants )
    {
      switch ( $matchedVariants )
      {
        case( 0 ):
          $prompt = 'Any variant doesn\'t match!';
          break;
        case( 1 ):
          $prompt = '#1 variant matchs!';
          break;
        default:
          $prompt = '#' . $matchedVariants . ' variants matchs!';
          break;
      }
      t3lib_div::devlog( '[INFO/VARIANTS] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $matchedVariants;
  }

  /**
   * variantsMatched( )
   *
   * @param	array		$variants :
   * @param	array		$product  :
   * @return	integer		$matchedVariants
   * @access private
   * @version     2.0.11
   * @since       2.0.11
   */
  private function variantsSet( $variants, $product )
  {
    if ( empty( $variants ) )
    {
      return $product;
    }

    // remove uid from variant array
    unset( $variants[ 'uid' ] );

    // add variant
    foreach ( $variants as $tableField => $variant )
    {
      $product[ $tableField ] = $variant;
    }

    return $product;
  }

  /*   * *********************************************
   *
   * ZZ
   *
   * ******************************************** */

  /**
   * zz_commaToDot( ) :
   *
   * @param	string		$strWiComma :
   * @return	string		$strWiDot   :
   * @access private
   * @version 2.0.0
   * @since 1.4.6
   */
  private function zz_commaToDot( $strWiComma )
  {
    $strWiDot = str_replace( ',', '.', $strWiComma );

    return $strWiDot;
  }

  /**
   * returns message with optical flair
   *
   * @param	string		$str: Message to show
   * @param	int		$pos: Is this a positive message? (0,1,2)
   * @param	boolean		$die: Process should be died now
   * @param	boolean		$prefix: Activate or deactivate prefix "$extKey: "
   * @param	string		$id: id to add to the message (maybe to do some javascript effects)
   * @return	string		$string: Manipulated string
   * @access private
   * @version 2.0.0
   * @since 1.4.6
   */
  private function zz_msg( $str, $pos = 0, $die = 0, $prefix = 1, $id = '' )
  {
    // config
    if ( $prefix )
    {
      $string = $this->extKey . ($pos != 1 && $pos != 2 ? ' Error' : '') . ': ';
    }
    $string .= $str; // add string
    $URLprefix = t3lib_div::getIndpEnv( 'TYPO3_SITE_URL' ) . '/'; // URLprefix with domain
    if ( t3lib_div::getIndpEnv( 'TYPO3_REQUEST_HOST' ) . '/' != t3lib_div::getIndpEnv( 'TYPO3_SITE_URL' ) )
    { // if request_host is different to site_url (TYPO3 runs in a subfolder)
      $URLprefix .= str_replace( t3lib_div::getIndpEnv( 'TYPO3_REQUEST_HOST' ) . '/', '', t3lib_div::getIndpEnv( 'TYPO3_SITE_URL' ) ); // add folder (like "subfolder/")
    }

    // let's go
    switch ( $pos )
    {
      default: // error
        $wrap = '<div class="' . $this->extKey . '_msg_error" style="background-color: #FBB19B; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/error.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #DC4C42; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
        if ( $id )
          $wrap .= ' id="' . $id . '"'; // add css id
        $wrap .= '>';
        break;

      case 1: // success
        $wrap = '<div class="' . $this->extKey . '_msg_status" style="background-color: #CDEACA; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/ok.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #58B548; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
        if ( $id )
          $wrap .= ' id="' . $id . '"'; // add css id
        $wrap .= '>';
        break;

      case 2: // note
        $wrap = '<div class="' . $this->extKey . '_msg_error" style="background-color: #DDEEF9; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/information.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #8AAFC4; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
        if ( $id )
          $wrap .= ' id="' . $id . '"'; // add css id
        $wrap .= '>';
        break;
    }

    if ( !$die )
    {
      return $wrap . $string . '</div>'; // return message
    }
    else
    {
      die( $string ); // die process and write message
    }
  }

  /**
   * zz_escapeStrForLike( )  :
   *
   * @param	string		$string :
   * @param	string		$table :
   * @return	string		$string :
   * @access private
   * @internal    #61632
   * @version     6.0.0
   * @since       2.0.11
   */
  private function zz_escapeStrForLike( $string, $table )
  {
    // #61632, 140916, dwildt, 1-
    //$mysqlEscapeString = mysql_escape_string( $string );
    // #61870, 140925, dwildt, 2-
//    // #61632, 140916, dwildt, 2+
//    $string = $GLOBALS['TYPO3_DB']->escapeStrForLike( $string, $table ) . "'";
    // #61870, 140925, dwildt, 2+
    // #61632, 140916, dwildt, 1+
    $string = $GLOBALS['TYPO3_DB']->escapeStrForLike( $string, $table );
    return $string;

    // #61632, 140916, dwildt, -
//    if ( $mysqlEscapeString )
//    {
//      return $mysqlEscapeString;
//    }
//
//    // DRS
//    if ( $this->drs->drsWarn )
//    {
//      $prompt = 'mysql_escape_string( ) returns false! Unproper connection to database!';
//      t3lib_div::devlog( '[WARN/PHP] ' . $prompt, $this->extKey, 2 );
//      $prompt = '", \' and \\ will replaced manually. Maybe this is a security risk!';
//      t3lib_div::devlog( '[WARN/PHP] ' . $prompt, $this->extKey, 2 );
//    }
//    // DRS
//
//    $string = str_replace( '"', null, $string );
//    $string = str_replace( "'", null, $string );
//    $string = str_replace( '\\', null, $string );
//
//    return $string;
  }

  /**
   * zz_sqlReplaceMarker(): Replace marker in the SQL query
   *                             MARKERS are
   *                             - GET/POST markers
   *                             - enable_field markers
   *                             SYNTAX is
   *                             - ###GP:TABLE###
   *                             - ###GP:TABLE.FIELD###
   *                             - ###ENABLE_FIELD:TABLE.FIELD###
   *
   * @return	void
   * @version 1.4.5
   * @since 1.2.2
   */
  private function zz_sqlReplaceMarker()
  {
    $arr_result = null;

    // set marker array with values from GET
    foreach ( t3lib_div::_GET() as $table => $arr_fields )
    {
      if ( is_array( $arr_fields ) )
      {
        foreach ( $arr_fields as $field => $value )
        {
          $tableField = strtoupper( $table . '.' . $field );
//            $marker['###GP:' . strtoupper($tableField) . '###'] = $this->zz_escapeStrForLike($value);
        }
      }
      if ( !is_array( $arr_fields ) )
      {
        // #61632, 140916, dwildt: +$table
        $marker[ '###GP:' . strtoupper( $table ) . '###' ] = $this->zz_escapeStrForLike( $arr_fields, $table );
      }
    }

    // set and overwrite marker array with values from POST
    foreach ( t3lib_div::_POST() as $table => $arr_fields )
    {
      if ( is_array( $arr_fields ) )
      {
        foreach ( $arr_fields as $field => $value )
        {
          $tableField = strtoupper( $table . '.' . $field );
                  // #61632, 140916, dwildt: +$table
          $marker[ '###GP:' . strtoupper( $tableField ) . '###' ] = $this->zz_escapeStrForLike( $value, $table );
        }
      }
      if ( !is_array( $arr_fields ) )
      {
                // #61632, 140916, dwildt: +$table
        $marker[ '###GP:' . strtoupper( $table ) . '###' ] = $this->zz_escapeStrForLike( $arr_fields, $table );
      }
    }

    // get the SQL query from ts, allow stdWrap
    // #42154, 101218, dwildt, 1-
    //$query = $pObj->cObj->stdWrap($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
    // #42154, 101218, dwildt, 1+
    $query = $this->pObj->cObj->cObjGetSingle( $this->pObj->conf[ 'db.' ][ 'sql' ], $this->pObj->conf[ 'db.' ][ 'sql.' ] );

    // get all gp:marker out of the query
    $arr_gpMarker = array();
    preg_match_all( '|###GP\:(.*)###|U', $query, $arr_result, PREG_PATTERN_ORDER );
    if ( isset( $arr_result[ 0 ] ) )
    {
      $arr_gpMarker = $arr_result[ 0 ];
    }

    // get all enable_fields:marker out of the query
    $arr_efMarker = array();
    preg_match_all( '|###ENABLE_FIELDS\:(.*)###|U', $query, $arr_result, PREG_PATTERN_ORDER );
    if ( isset( $arr_result[ 0 ] ) )
    {
      $arr_efMarker = $arr_result[ 0 ];
    }

    // replace gp:marker
    foreach ( $arr_gpMarker as $str_gpMarker )
    {
      $value = null;
      if ( isset( $marker[ $str_gpMarker ] ) )
      {
        $value = $marker[ $str_gpMarker ];
      }
      $query = str_replace( $str_gpMarker, $value, $query );
    }

    // replace enable_fields:marker
    foreach ( $arr_efMarker as $str_efMarker )
    {
      $str_efTable = trim( strtolower( $str_efMarker ), '#' );
      list( $dummy, $str_efTable ) = explode( ':', $str_efTable );
      unset( $dummy );
      $andWhere_ef = tslib_cObj::enableFields( $str_efTable );
      $query = str_replace( $str_efMarker, $andWhere_ef, $query );
    }

    // #42154, 121203, dwildt, 1-
//    $pObj->conf['db.']['sql'] = $query;
    // #42154, 121203, dwildt, 2+
    $this->pObj->conf[ 'db.' ][ 'sql' ] = 'TEXT';
    $this->pObj->conf[ 'db.' ][ 'sql.' ][ 'value' ] = $query;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/class.tx_caddy_session.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/class.tx_caddy_session.php' ]);
}
?>