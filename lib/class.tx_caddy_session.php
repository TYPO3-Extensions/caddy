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
 *   89: class tx_caddy_session
 *
 *              SECTION: Get numbers
 *  116:     public function getNumberDeliveryorder( )
 *  131:     public function getNumberInvoice( )
 *  146:     public function getNumberOrder( )
 *
 *              SECTION: Payment
 *  167:     public function paymentUpdate($value)
 *  182:     public function paymentGet()
 *
 *              SECTION: Product
 *  212:     public function productAdd( $product )
 *  317:     public function productDelete( )
 *  372:     public function productGetDetails($gpvar)
 *  393:     private function productGetDetailsSql($gpvar)
 *  446:     private function productGetDetailsTs($gpvar)
 *  543:     private function productGetVariantGpvar( )
 *  576:     private function productGetVariantTs( $product )
 *  609:     public function productsGet()
 *  623:     public function productsGetGross( $pid )
 *  654:     private function quantityCheckMinMax( $product )
 *  689:     private function quantityGetVariant( )
 *  763:     public function quantityUpdate( )
 *
 *              SECTION: Session
 *  908:     public function sessionDelete( $content = '', $conf = array( ) )
 *  943:     private function sessionDeleteIncreaseNumbers( $drs )
 *
 *              SECTION: Shipping
 * 1008:     public function shippingUpdate($value)
 * 1023:     public function shippingGet( )
 *
 *              SECTION: Special
 * 1044:     public function specialUpdate($special_arr)
 * 1059:     public function specialGet()
 *
 *              SECTION: ZZ
 * 1087:     private function zz_msg($str, $pos = 0, $die = 0, $prefix = 1, $id = '')
 * 1148:     private function zz_sqlReplaceMarker( )
 *
 * TOTAL FUNCTIONS: 25
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
class tx_caddy_session
{

  public $extKey        = 'caddy';
  public $prefixId      = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';

    // Object: the parent object
  public $pObj = null;




 /***********************************************
  *
  * Getting methods
  *
  **********************************************/

/**
 * getQuantityItems( )  : Get the amount of quantities
 *
 * @return	integer   $quantityItems : 
 * @access private
 * @version     2.0.0
 * @since       2.0.0
 */
  private function getQuantityItems( )
  {
    $quantityItems = 0; 
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $products = $sesArray['products'];
    
    foreach( $products as $product )
    {
      $quantityItems  = $quantityItems
                      + $product['qty'];
    }
    return ( int ) $quantityItems;
  }

/**
 * getNumberDeliveryorder( )  : Get the current order number
 *
 * @return	string
 * @access public
 * @version     2.0.0
 * @since       2.0.0
 */
  public function getNumberDeliveryorder( )
  {
    $sesArray       = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $currentNumber  = $sesArray['numberDeliveryorderCurrent'];
    return $currentNumber;
  }

/**
 * getNumberInvoice( )  : Get the current order number
 *
 * @return	string
 * @access public
 * @version     2.0.0
 * @since       2.0.0
 */
  public function getNumberInvoice( )
  {
    $sesArray       = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $currentNumber  = $sesArray['numberInvoiceCurrent'];
    return $currentNumber;
  }

/**
 * getNumberOrder( )  : Get the current order number
 *
 * @return	string
 * @access public
 * @version     2.0.0
 * @since       2.0.0
 */
  public function getNumberOrder( )
  {
    $sesArray       = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $currentNumber  = $sesArray['numberOrderCurrent'];
    return $currentNumber;
  }



 /***********************************************
  *
  * Payment
  *
  **********************************************/

/**
 * paymentUpdate( ) : Change the payment method in session
 *
 * @param	integer		$value
 * @return	void
 * @access public
 * @version     2.0.0
 * @since       1.4.6
 */
  public function paymentUpdate( $value )
  {
      // get already exting products from session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id); 

    $sesArray['payment'] = intval( $value ); 

    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray);
    $GLOBALS['TSFE']->storeSessionData();
  }

/**
 * paymentGet( )  : get the payment method from session
 *
 * @return	integer
 * @access public
 * @version     2.0.0
 * @since       1.4.6
 */
  public function paymentGet( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id); // get already exting products from session

    return $sesArray['payment'];
  }



 /***********************************************
  *
  * Product
  *
  **********************************************/

/**
 * productAdd( )  : Add product to session
 *
 *    array (
 *      'title' => 'this is the title',
 *      'amount' => 2,
 *      'price' => '1,49',
 *      'tax' => 1,
 *      'puid' => 234,
 *      'sku' => 'P234whatever'
 *    )
 *
 * @param	array		$product: 
 * @return	void
 * @version     2.0.0
 * @since       1.4.6
 */
  public function productAdd( $product )
  {
    $arr_variant = null;

      // RETURN : without price or without title
    if( empty( $product['price'] ) || empty( $product['title'] ) )
    {
      return false;
    }
      // RETURN : without price or without title

    // variants
    $arr_variant['puid'] = $product['puid'];
    // add variant keys from ts settings.variants array,
    //  if there is a corresponding key in GET or POST
    if( is_array( $this->pObj->conf['settings.']['variant.'] ) )
    {
      $arr_get  = t3lib_div::_GET( );
      $arr_post = t3lib_div::_POST( );
      foreach( $this->pObj->conf['settings.']['variant.'] as $key => $tableField )
      {
        list( $table, $field ) = explode( '.', $tableField );
        if( isset( $arr_get[$table][$field] ) )
        {
          $arr_variant[$tableField] = mysql_escape_string( $arr_get[$table][$field] );
        }
        if( isset( $arr_post[$table][$field] ) )
        {
          $arr_variant[$tableField] = mysql_escape_string( $arr_post[$table][$field] );
        }
      }
      // add variant keys from ts settings.variants array,
    }
    // variants

    $sesArray = array( );
    // get already exting products from session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

    // check if this puid already exists and when delete it
    foreach( ( array ) $sesArray['products'] as $key => $value )
    { // one loop for every product
      if( is_array( $value ) )
      {
        // counter for condition. Every condition has to be true
        $int_counter = 0;

        // loop every condition
        foreach( $arr_variant as $key_variant => $value_variant )
        {
          // condition fits
          if( $value[$key_variant] == $value_variant )
          {
            $int_counter++;
          }
        }
        // loop every condition

        // all conditions fit
        if( $int_counter == count( $arr_variant ) )
        {
          // remove product
          $product['qty'] = $sesArray['products'][$key]['qty'] + $product['qty'];
          unset( $sesArray['products'][$key] );
        }
      }
    }

    $product = $this->quantityCheckMinMax( $product );

    if( isset( $product['price'] ) )
    {
      $product['price'] = str_replace( ',', '.', $product['price'] ); // comma to point
    }

      // remove puid from variant array
    unset( $arr_variant[0] );

    // add variant key/value pairs to the current product
    if( ! empty( $arr_variant ) )
    {
      foreach( $arr_variant as $key_variant => $value_variant )
      {
        $product[$key_variant] = $value_variant;
      }
    }
    // add variant key/value pairs to the current product

    // add product to the session array
    $sesArray['products'][ ] = $product;

    // generate session with session array
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // save session
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * Remove product from session with given uid
  *
  * @return	void
  * @version  2.0.0
  * @since    1.4.6
  */
  public function productDelete( )
  {
    // variants
    // add variant key/value pairs from piVars
    $arr_variant = $this->productGetVariantGpvar( );
    // add product id to variant array
    $arr_variant['puid'] = $this->pObj->piVars['del'];

    // get products from session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

    // loop every product
    foreach( array_keys( ( array ) $sesArray['products'] ) as $key )
    {
      // Counter for condition
      $int_counter = 0;

      // loop through conditions
      foreach( $arr_variant as $key_variant => $value_variant )
      {
        // condition fits
        if ( $sesArray['products'][$key][$key_variant] == $value_variant )
        {
          $int_counter++;
        }
      }
      // loop through conditions

      // all conditions fit
      if( $int_counter == count($arr_variant ) )
      {
        // remove product from session
        unset($sesArray['products'][$key]);
      }
    }
    // loop every product

    // generate new session
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // save session
    $GLOBALS['TSFE']->storeSessionData( );
  }

/**
 * read product details (title, price from table)
 * the method productGetDetails of version 1.2.1 became productGetDetailsTs from version 1.2.2
 *
 * @param	array		$gpvar: array with product uid, title, tax, etc...
 * @return	array		$arr: array with title and price
 * @version 1.2.2
 * @since 1.2.2
 */
  public function productGetDetails( $gpvar )
  {
    // build own sql query
    // handle query by db.sql
    if( ! empty( $this->pObj->conf['db.']['sql'] ) ) 
    {
      return $this->productGetDetailsSql( $gpvar );
    }

    // handle query by db.table and db.fields
    return $this->productGetDetailsTs( $gpvar );
  }

   /**
 * read product details by a manually configured sql query
 *
 * @param	array		$gpvar: array with product uid, title, tax, etc...
 * @return	array		$arr: array with title and price
 * @access private
 * @version 2.0.0
 * @since 1.4.6
 */
    private function productGetDetailsSql($gpvar)
    {
      if( ( ! t3lib_div::_GET( ) ) && ( ! t3lib_div::_POST( ) ) )
      {
        return false;
      }

      // replace gp:marker and enable_fields:marker in $pObj->conf['db.']['sql']
      $this->zz_sqlReplaceMarker( );
        // #42154, 101218, dwildt, 1-
      //$query = $pObj->cObj->stdWrap($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
        // #42154, 101218, dwildt, 1+
      $query  = $this->pObj->cObj->cObjGetSingle( $this->pObj->conf['db.']['sql'], $this->pObj->conf['db.']['sql.'] );
      // execute the query
      $res    = $GLOBALS['TYPO3_DB']->sql_query( $query );
      $error  = $GLOBALS['TYPO3_DB']->sql_error( );

      // exit in case of error
      if( ! empty( $error ) )
      {
        $str = '<h1>caddy: SQL-Error</h1>';
        $str .= '<p>'.$error.'</p>';
        $str .= '<p>'.$query.'</p>';
        $this->zz_msg($str, 0, 1, 1);
      }

      // ToDo: @dwildt: optimization highly needed
      if( $res )
      {
        while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
        {
          if($row['title'] != null)
          {
            break;
          }
        }
        $row['puid']  = $gpvar['puid'];

        return $row;
      }

      return false;
    }

   /**
 * read product details (title, price from table)
 *
 * @param	array		$gpvar: array with product uid, title, tax, etc...
 * @return	array		$arr: array with title and price
 * @access private
 * @version 2.0.0
 * @since 1.4.6
 */
  private function productGetDetailsTs( $gpvar )
  {
    if( ! empty( $gpvar['title'] ) && ! empty( $gpvar['price'] ) &&  ! empty( $gpvar['tax'] ) )
    { // all values already filled via POST or GET param
        return $gpvar;
    }

    $puid = intval($gpvar['puid']);
    if ($puid === 0)
    { // stop if no puid given
        return false;
    }

    $table    = $this->pObj->conf['db.']['table'];
    $select = $table . '.' . $this->pObj->conf['db.']['title'] . ', ' . $table . '.' . $this->pObj->conf['db.']['price'] . ', ' . $table . '.' . $this->pObj->conf['db.']['tax'];
    if ($this->pObj->conf['db.']['sku'] != '' && $this->pObj->conf['db.']['sku'] != '{$plugin.caddy.db.sku}')
    {
        $select .= ', ' . $table . '.' . $this->pObj->conf['db.']['sku'];
    }
    if ($this->pObj->conf['db.']['min'] != '' && $this->pObj->conf['db.']['min'] != '{$plugin.caddy.db.min}')
    {
        $select .= ', ' . $table . '.' . $this->pObj->conf['db.']['min'];
    }
    if ($this->pObj->conf['db.']['max'] != '' && $this->pObj->conf['db.']['max'] != '{$plugin.caddy.db.max}')
    {
        $select .= ', ' . $table . '.' . $this->pObj->conf['db.']['max'];
    }
    if ($this->pObj->conf['db.']['service_attribute_1'] != '' && $this->pObj->conf['db.']['service_attribute_1'] != '{$plugin.caddy.db.service_attribute_1}')
    {
        $select .= ', ' . $table . '.' . $this->pObj->conf['db.']['service_attribute_1'];
    }
    if ($this->pObj->conf['db.']['service_attribute_2'] != '' && $this->pObj->conf['db.']['service_attribute_2'] != '{$plugin.caddy.db.service_attribute_2}')
    {
        $select .= ', ' . $table . '.' . $this->pObj->conf['db.']['service_attribute_2'];
    }
    if ($this->pObj->conf['db.']['service_attribute_3'] != '' && $this->pObj->conf['db.']['service_attribute_3'] != '{$plugin.caddy.db.service_attribute_3}')
    {
        $select .= ', ' . $table . '.' . $this->pObj->conf['db.']['service_attribute_3'];
    }
    $where = ' ( ' . $table . '.uid = ' . $puid . ' OR l10n_parent = '.$puid . ' ) AND sys_language_uid = ' .$GLOBALS['TSFE']->sys_language_uid;
    $where .= tslib_cObj::enableFields($table);
    $groupBy = '';
    $orderBy = '';
    $limit = 1;

    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $groupBy, $orderBy, $limit);

    if ($res)
    {
        $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $arr = array (
            'title' => $row[$this->pObj->conf['db.']['title']],
            'price' => $row[$this->pObj->conf['db.']['price']],
            'tax'   => $row[$this->pObj->conf['db.']['tax']],
            'puid'  => $gpvar['puid']
        );
        if ($row[$this->pObj->conf['db.']['sku']])
        {
            $arr['sku'] = $row[$this->pObj->conf['db.']['sku']];
        }
        if ($row[$this->pObj->conf['db.']['min']])
        {
            $arr['min'] = $row[$this->pObj->conf['db.']['min']];
        }
        if ($row[$this->pObj->conf['db.']['max']])
        {
            $arr['max'] = $row[$this->pObj->conf['db.']['max']];
        }
        if ($row[$this->pObj->conf['db.']['service_attribute_1']])
        {
            $arr['service_attribute_1'] = $row[$this->pObj->conf['db.']['service_attribute_1']];
        }
        if ($row[$this->pObj->conf['db.']['service_attribute_2']])
        {
            $arr['service_attribute_2'] = $row[$this->pObj->conf['db.']['service_attribute_2']];
        }
        if ($row[$this->pObj->conf['db.']['service_attribute_3']])
        {
            $arr['service_attribute_3'] = $row[$this->pObj->conf['db.']['service_attribute_3']];
        }

        return $arr;
    } else {
        // ToDo: include error handling -> only needed for admin, let's use devlog()
    }
  }

   /**
 * productGetVariantGpvar(): Get variant values from piVars
 *                              variant values have to be content of
 *                              ts array variant and of piVars
 *
 * @return	array		$arr_variants: array with variant key/value pairs
 * @access private
 * @version 2.0.0
 * @since 1.4.6
 */
    private function productGetVariantGpvar( )
    {
      $arr_variant = null;

      // return there isn't any variant
      if (!is_array($this->pObj->conf['settings.']['variant.']))
      {
        return $arr_variant;
      }
      // return there isn't any variant

      // loop through ts variant array
      foreach( $this->pObj->conf['settings.']['variant.'] as $tableField )
      {
        // piVars contain variant key
        if (!empty($this->pObj->piVars[$tableField]))
        {
          $arr_variant[$tableField] = mysql_escape_string($this->pObj->piVars[$tableField]);
        }
      }

      return $arr_variant;
    }

    /**
 * productGetVariantTs():  Get an array with the variant values
 *                                out of the current product
 *
 * @param	array		$product: array with product uid, title, tax, etc...
 * @return	array		$arr_variants: array with variant key/value pairs
 * @version 2.0.0
 * @since 1.4.6
 */
    private function productGetVariantTs( $product )
    {
        $arr_variants = null;

        // return there isn't any variant
        if (!is_array($this->pObj->conf['settings.']['variant.']))
        {
            return $arr_variants;
        }
        // return there isn't any variant

        // loop through ts array variant
        foreach ($this->pObj->conf['settings.']['variant.'] as $key_variant)
        {
            // product contains variant key from ts
            if (in_array($key_variant, array_keys($product)))
            {
                $arr_variants[$key_variant] = $product[$key_variant];
                if (empty($arr_variants[$key_variant]))
                {
                    unset($arr_variants[$key_variant]);
                }
            }
        }

        return $arr_variants;
    }

    /**
 * Read products from session
 *
 * @return	array		$arr: array with all products from session
 */
    public function productsGet()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

        return $sesArray['products'];
    }

   /**
 * Count gross price of all products in a cart
 * Is used by pi3 only
 *
 * @param	[type]		$pid: ...
 * @return	integer
 */
    public function productsGetGross( $pid )
    {
        // get already exting products from session
      $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $pid );

      $gross = 0;
      foreach( ( array ) $sesArray['products'] as  $val )
      {
          $gross += $val['price'] * $val['qty'];
      }

      return $gross;
    }



 /**
  * ********************************************
  *
  * Quantity
  *
  * *********************************************/
  
 /* quantityCheckMinMax( )  : check if min and max in quantity range and set error message
  *
  * @param	array
  * @return	array
  * @access private
  * @version 2.0.0
  * @since 2.0.0
  */
  private function quantityCheckMinMax( $product )
  {
    $product = $this->quantityCheckMinMaxMin( $product );
    $product = $this->quantityCheckMinMaxMax( $product );

    $product = $this->quantityCheckMinMaxItems( $product );

    return $product;
  }
  
  
 /* quantityCheckMinMaxItems( )  : 
  *
  * @param	array
  * @return	array
  * @access private
  * @version 2.0.0
  * @since 2.0.0
  */
  private function quantityCheckMinMaxItems( $product )
  {
    unset( $product['error']['itemsMin'] );
    unset( $product['error']['itemsMax'] );

    switch( true )
    {
      case( $this->pObj->gpvar['puid'] ):
        $product = $this->quantityCheckMinMaxItemsMin( $product );
        $product = $this->quantityCheckMinMaxItemsMax( $product );
        break;
      case( $this->pObj->piVars['qty'] ):
        break;
      default:
        $prompt = 'ERROR: no vavlue for switch' . PHP_EOL .
                  'Sorry for the trouble.<br />' . PHP_EOL .
                  'TYPO3 Caddy<br />' . PHP_EOL .
                __METHOD__ . ' (' . __LINE__ . ')';
        die( $prompt );
        break;
        
    }
    

    return $product;
  }
  
 /* quantityCheckMinMaxItemsMin( )  :
  *
  * @param	array
  * @return	array
  * @access private
  * @version 2.0.0
  * @since 2.0.0
  */
  private function quantityCheckMinMaxItemsMin( $product )
  { 
    unset( $product['error']['itemsMin'] );
    return $product;

    if( empty( $product['min'] ) )
    {
      return $product;
    }

    switch( true )
    {
      case( $product['qty'] < $product['min'] ):
        $product['qty']           = $product['min'];
        $product['error']['min']  = true;
        break;    
      case( $product['qty'] >= $product['min'] ):
      default:
        unset( $product['error']['min'] );
        break;    
    }

    return $product;
  }
  
 /* quantityCheckMinMaxItemsMax( )  :
  *
  * @param	array
  * @return	array
  * @access private
  * @version 2.0.0
  * @since 2.0.0
  */
  private function quantityCheckMinMaxItemsMax( $product )
  { 
    unset( $product['error']['itemsMax'] );

      // RETURN : any item is added
    if( empty( $this->pObj->gpvar['puid'] ) )
    {
      return $product;
    }
      // RETURN : any item is added
    
      // RETURN : max quantity for all items is unlimited
    $itemsQuantityMax = $this->pObj->flexform->originMax;   
    if( empty( $itemsQuantityMax ) )
    {
      return $product;
    }
      // RETURN : max quantity for all items is unlimited
  
      // RETURN : max quantity for all items isn't overrun
    $itemsQuantity = $this->getQuantityItems( );
    if( $itemsQuantity <= $itemsQuantityMax )
    {
      return $product;
    }
      // RETURN : max quantity for all items isn't overrun

    $itemsQuantityOverrun = $itemsQuantity
                          - $itemsQuantityMax
                          + 1;
    
var_dump( __METHOD__, __LINE__, $product['qty'] );
    $product['qty'] = $product['qty']
                    - $itemsQuantityOverrun;

    $llKey    = 'caddy_ll_error_itemsMax';
    $llAlt    = 'No value for caddy_ll_error_itemsMax in ' . __METHOD__ . ' (' . __LINE__ .')';
    $llPrompt = $this->pObj->pi_getLL( $llKey, $llAlt );
    $llPrompt = sprintf( $llPrompt, $itemsQuantityMax );
    $product['error']['itemsMax'] = $llPrompt;

var_dump( __METHOD__, __LINE__, $this->pObj->gpvar, $itemsQuantity, $itemsQuantityMax );

    return $product;
  }
  
 /* quantityCheckMinMaxMin( )  :
  *
  * @param	array
  * @return	array
  * @access private
  * @version 2.0.0
  * @since 2.0.0
  */
  private function quantityCheckMinMaxMin( $product )
  { 
    if( empty( $product['min'] ) )
    {
      return $product;
    }

    switch( true )
    {
      case( $product['qty'] < $product['min'] ):
        $product['qty'] = $product['min'];
        $llKey          = 'caddy_ll_error_min';
        $llAlt          = 'No value for caddy_ll_error_min in ' . __METHOD__ . ' (' . __LINE__ .')';
        $llPrompt       = $this->pObj->pi_getLL( $llKey, $llAlt );
        $llPrompt       = sprintf( $llPrompt, $product['min'] );
        $product['error']['min'] = $llPrompt;
        $product['error']['min']  = true;
        break;    
      case( $product['qty'] >= $product['min'] ):
      default:
        unset( $product['error']['min'] );
        break;    
    }

    return $product;
  }
  
 /* quantityCheckMinMaxMax( )  :
  *
  * @param	array
  * @return	array
  * @access private
  * @version 2.0.0
  * @since 2.0.0
  */
  private function quantityCheckMinMaxMax( $product )
  { 
    if( empty( $product['max'] ) )
    {
      return $product;
    }

    switch( true )
    {
      case( $product['qty'] > $product['max'] ):
        $product['qty'] = $product['max'];
        $llKey          = 'caddy_ll_error_max';
        $llAlt          = 'No value for caddy_ll_error_max in ' . __METHOD__ . ' (' . __LINE__ .')';
        $llPrompt       = $this->pObj->pi_getLL( $llKey, $llAlt );
        $llPrompt       = sprintf( $llPrompt, $product['max'] );
        $product['error']['max'] = $llPrompt;
        break;    
      case( $product['qty'] <= $product['max'] ):
      default:
        unset( $product['error']['max'] );
        break;    
    }

    return $product;
  }

/**
 * quantityGetVariant(): Get variant values out of the name of the qty field
 *                              variant values have to be content of
 *                              ts array variant and of qty field
 *
 * @return	array		$arr_variants: array with variant key/value pairs
 * @access private
 * @version 2.0.0
 * @since 1.4.6
 */
    private function quantityGetVariant( )
    {
      $arr_variant  = null;
      $arr_qty      = null;

        // RETURN : there isn't any variant
      if( ! is_array($this->pObj->conf['settings.']['variant.'] ) )
      {
        return $arr_variant;
      }
        // RETURN : there isn't any variant

      $int_counter = 0;
      foreach( $this->pObj->piVars['qty'] as $key => $value )
      {
        $arr_qty[$int_counter]['qty'][$key] = $value;
        $int_counter++;
      }

      foreach ( $arr_qty as $key => $piVarsQty )
      {
        // iterator object
        $data     = new RecursiveArrayIterator( $piVarsQty['qty'] );
        $iterator = new RecursiveIteratorIterator( $data, true );
        // top level of ecursive array
        $iterator->rewind();

        // get all variant key/value pairs from qty name
        foreach ($iterator as $key_iterator => $value_iterator)
        {
          // i.e for a key: tx_org_calentrance.uid=4
          list($key_variant, $value_variant) = explode('=', $key_iterator);
          if ($key_variant == 'puid')
          {
            $arr_variant[$key]['puid'] = $value_variant;
          }
          // i.e arr_var[tx_org_calentrance.uid] = 4
          $arr_from_qty[$key][$key_variant] = $value_variant;
          if (is_array($value_iterator))
          {
            list($key_variant, $value_variant) = explode('=', key($value_iterator));
            if ($key_variant == 'puid')
            {
              $arr_variant[$key]['puid'] = $value_variant;
            }
            $arr_from_qty[$key][$key_variant] = $value_variant;
          }
          // value is the value of the field qty in every case
          if (!is_array($value_iterator))
          {
            $arr_variant[$key]['qty'] = $value_iterator;
          }
        }

        // loop through ts variant array
        foreach( $this->pObj->conf['settings.']['variant.'] as $key_variant => $tableField )
        {
          // piVars contain variant key
          if( ! empty($arr_from_qty[$key][$tableField] ) )
          {
            $arr_variant[$key][$tableField] = mysql_escape_string( $arr_from_qty[$key][$tableField] );
          }
        }
      }

      return $arr_variant;
    }

/**
 * quantityUpdate( )  : Change quantity of a product in session
 *
 * @return	void
 * @access private
 * @version 2.0.0
 * @since 1.4.6
 */
  public function quantityUpdate( )
  {
    // variants
    // add variant key/value pairs from piVars
    $arr_variant = $this->quantityGetVariant( );

    // get products from session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id);

    $is_cart = intval($this->pObj->piVars['update_from_cart']);

      // LOOP : each product
    foreach( array_keys( ( array ) $sesArray['products'] ) as $key_session )
    {
      // current product id
      $session_puid = $sesArray['products'][$key_session]['puid'];

      if( ! is_array( $arr_variant ) )
      {
        // no variant, nothing to loop
        $int_qty = intval( $this->pObj->piVars['qty'][$session_puid] );

        if( $int_qty > 0 )
        {
          // update quantity
          if( $is_cart ) 
          {
            // update from cart then set new qty
            $sesArray['products'][$key_session]['qty']  = $int_qty;
          }
          else 
          {
            // update not from cart then add qty
            $sesArray['products'][$key_session]['qty']  = $sesArray['products'][$key_session]['qty']
                                                        + $int_qty;
          }
          $sesArray['products'][$key_session] = $this->quantityCheckMinMax( $sesArray['products'][$key_session] );
        }
        else
        {
          // remove product from session
          $this->productDelete($sesArray['products'][$key_session]['puid']);
          // remove product from current session array
          unset($sesArray['products'][$key_session]);
        }
      } 
      else 
      {
        // loop for every variant
        $arr_variant_backup = $arr_variant;
        foreach( $arr_variant as $key_variant => $arr_condition )
        {
          if( ! isset( $arr_variant[$key_variant]['puid'] ) )
          {
            // without variant
            $curr_puid = key( $this->pObj->piVars['qty'] );
          }
          if( isset( $arr_variant[$key_variant]['puid'] ) )
          {
            $curr_puid = $arr_variant[$key_variant]['puid'];
          }
          if( ! isset( $arr_variant[$key_variant]['qty'] ) )
          {
            // without variant
            $int_qty = intval( $this->pObj->piVars['qty'][$curr_puid] );
          }
          if (isset($arr_variant[$key_variant]['qty']))
          {
            $int_qty = intval($arr_variant[$key_variant]['qty']);
          }

          // counter for condition
          $int_counter = 0;
          // puid: condition fits
          if( $session_puid == $curr_puid )
          {
            $int_counter++;
          }

          // loop through conditions
          foreach( $arr_condition as $key_condition => $value_condition )
          {
            // workaround (it would be better, if qty and puid won't be elements of $arr_condition
            if( in_array( $key_condition, array( 'qty', 'puid' ) ) )
            {
              // workaround: puid and qty should fit in every case
              $int_counter++;
            }
            // workaround (it would be better, if qty and puid won't be elements of $arr_condition
            if( ! in_array( $key_condition, array( 'qty', 'puid' ) ) )
            {
              // variants: condition fits
              if( $sesArray['products'][$key_session][$key_condition] == $value_condition )
              {
                //$prompt_315[] = 'div 315: true - session_key : ' . $key_session . ', ' . $key_condition . ' : ' . $value_condition;
                $int_counter++;
              }
            }
          }
          // loop through conditions

          // all conditions fit
          if( $int_counter == ( count( $arr_condition ) + 1 ) )
          {
            if( $int_qty > 0 )
            {
              if( $is_cart )
              {
                // update from cart then set new qty
                $sesArray['products'][$key_session]['qty'] = $int_qty;
              }
              else
              {
                // update not from cart then add qty
                $sesArray['products'][$key_session]['qty']  = $sesArray['products'][$key_session]['qty']
                                                            + $int_qty;
              }
            } 
            else 
            {
              // remove product from session
              $this->productDelete( $sesArray['products'][$key_session]['puid'] );
              // remove product from current session array
              unset( $sesArray['products'][$key_session] );
            }
          }
        }
        $arr_variant = $arr_variant_backup;
        // loop every variant
      }
    }
      // LOOP : each product

    // generate new session
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // save session
    $GLOBALS['TSFE']->storeSessionData( );
  }



 /***********************************************
  *
  * Session
  *
  **********************************************/

 /**
  * sessionDelete( )
  *
  * @param	string		$content  : current content
  * @param	[type]		$conf: ...
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function sessionDelete( $content = '', $conf = array( ) )
  {
      // DRS
    unset( $content );
    $drs = false;
    if( $conf['userFunc.']['drs'] )
    {
      $drs = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    if( $this->drs->drsSession || $drs )
    {
      $prompt = 'Session is cleared.';
      t3lib_div::devlog( '[INFO/SESSION] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // Increase numbers
    $this->sessionDeleteIncreaseNumbers( $drs );

      // Delete the session
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, array( ) );
    $GLOBALS['TSFE']->storeSessionData( );
  }

 /**
  * sessionDeleteIncreaseNumbers( )
  *
  * @param	[type]		$$drs: ...
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sessionDeleteIncreaseNumbers( $drs )
  {
    $products = $this->productsGet( );

      // RETURN : any product, don't increase numbers!
    if( empty( $products ) )
    {
      if( $this->drs->drsSession || $drs )
      {
        $prompt = 'Session is empty! Maybe powermail form is sent twice!';
        t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
      }
      return;
    }
      // RETURN : any product, don't increase numbers!

    $registry =  t3lib_div::makeInstance('t3lib_Registry');
    $prefix = 'page_' . $GLOBALS["TSFE"]->id . '_';

      // Get current numbers
    $numberDeliveryorder  = ( int ) $registry->get( 'tx_caddy', $prefix . 'deliveryorder' );
    $numberInvoice        = ( int ) $registry->get( 'tx_caddy', $prefix . 'invoice' );
    $numberOrder          = ( int ) $registry->get( 'tx_caddy', $prefix . 'order' );
      // Get current numbers

      // Increase current numbers
    $numberDeliveryorder  = $numberDeliveryorder  + 1;
    $numberInvoice        = $numberInvoice        + 1;
    $numberOrder          = $numberOrder          + 1;
      // Increase current numbers

      // Set current numbers
    $registry->set('tx_caddy', $prefix . 'deliveryorder', $numberDeliveryorder );
    $registry->set('tx_caddy', $prefix . 'invoice',       $numberInvoice );
    $registry->set('tx_caddy', $prefix . 'order',         $numberOrder );
      // Set current numbers

      // DRS
    if( $drs )
    {
      $prompt = 'New delivery order number: ' . $numberDeliveryorder;
      t3lib_div::devlog(' [INFO/SESSION] '. $prompt, $this->extKey, 0 );
      $prompt = 'New invoice number: ' .        $numberInvoice;
      t3lib_div::devlog(' [INFO/SESSION] '. $prompt, $this->extKey, 0 );
      $prompt = 'New order number: ' .          $numberOrder;
      t3lib_div::devlog(' [INFO/SESSION] '. $prompt, $this->extKey, 0 );
    }
      // DRS

}



 /***********************************************
  *
  * Shipping
  *
  **********************************************/

    /**
 * Change the shipping method in session
 *
 * @param	array		$arr: array to change
 * @return	void
 */
    public function shippingUpdate($value)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        $sesArray['shipping'] = intval($value); // overwrite with new qty

        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
        $GLOBALS['TSFE']->storeSessionData(); // Save session
    }

    /**
 * get the shipping method from session
 *
 * @return	integer
 */
    public function shippingGet( )
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['shipping'];
    }



 /***********************************************
  *
  * Special
  *
  **********************************************/

    /**
 * Change the special method in session
 *
 * @param	array		$arr: array to change
 * @return	void
 */
    public function specialUpdate($special_arr)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        $sesArray['special'] = $special_arr; // overwrite with new qty

        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
        $GLOBALS['TSFE']->storeSessionData(); // Save session
    }

    /**
 * get the special method from session
 *
 * @return	integer
 */
    public function specialGet()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['special'];
    }



 /***********************************************
  *
  * ZZ
  *
  **********************************************/

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
    private function zz_msg($str, $pos = 0, $die = 0, $prefix = 1, $id = '')
    {
        // config
        if ($prefix)
        {
            $string = $this->extKey . ($pos != 1 && $pos != 2 ? ' Error' : '') . ': ';
        }
        $string .= $str; // add string
        $URLprefix = t3lib_div::getIndpEnv('TYPO3_SITE_URL') . '/'; // URLprefix with domain
        if (t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST') . '/' != t3lib_div::getIndpEnv('TYPO3_SITE_URL'))
        { // if request_host is different to site_url (TYPO3 runs in a subfolder)
            $URLprefix .= str_replace(t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST') . '/', '', t3lib_div::getIndpEnv('TYPO3_SITE_URL')); // add folder (like "subfolder/")
        }

        // let's go
        switch ($pos)
        {
            default: // error
                $wrap = '<div class="' . $this->extKey . '_msg_error" style="background-color: #FBB19B; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/error.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #DC4C42; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
                if ($id)
                    $wrap .= ' id="' . $id . '"'; // add css id
                $wrap .= '>';
                break;

            case 1: // success
                $wrap = '<div class="' . $this->extKey . '_msg_status" style="background-color: #CDEACA; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/ok.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #58B548; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
                if ($id)
                    $wrap .= ' id="' . $id . '"'; // add css id
                $wrap .= '>';
                break;

            case 2: // note
                $wrap = '<div class="' . $this->extKey . '_msg_error" style="background-color: #DDEEF9; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/information.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #8AAFC4; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
                if ($id)
                    $wrap .= ' id="' . $id . '"'; // add css id
                $wrap .= '>';
                break;
        }

        if (!$die)
        {
            return $wrap . $string . '</div>'; // return message
        } else {
            die($string); // die process and write message
        }
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
    private function zz_sqlReplaceMarker( )
    {
      $arr_result = null;

      // set marker array with values from GET
      foreach (t3lib_div::_GET() as $table => $arr_fields)
      {
        if (is_array($arr_fields))
        {
          foreach($arr_fields as $field => $value)
          {
            $tableField = strtoupper($table . '.' . $field);
            $marker['###GP:' . strtoupper($tableField) . '###'] = mysql_escape_string($value);
          }
        }
        if (!is_array($arr_fields))
        {
          $marker['###GP:' . strtoupper($table) . '###'] = mysql_escape_string($arr_fields);
        }
      }

      // set and overwrite marker array with values from POST
      foreach (t3lib_div::_POST() as $table => $arr_fields)
      {
        if (is_array($arr_fields))
        {
          foreach ($arr_fields as $field => $value)
          {
            $tableField = strtoupper($table . '.' . $field);
            $marker['###GP:' . strtoupper($tableField) . '###'] = mysql_escape_string($value);
          }
        }
        if (!is_array($arr_fields))
        {
          $marker['###GP:' . strtoupper($table) . '###'] = mysql_escape_string($arr_fields);
        }
      }

      // get the SQL query from ts, allow stdWrap
        // #42154, 101218, dwildt, 1-
      //$query = $pObj->cObj->stdWrap($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
        // #42154, 101218, dwildt, 1+
      $query = $this->pObj->cObj->cObjGetSingle($this->pObj->conf['db.']['sql'], $this->pObj->conf['db.']['sql.']);

      // get all gp:marker out of the query
      $arr_gpMarker = array();
      preg_match_all('|###GP\:(.*)###|U', $query, $arr_result, PREG_PATTERN_ORDER);
      if (isset($arr_result[0]))
      {
        $arr_gpMarker = $arr_result[0];
      }

      // get all enable_fields:marker out of the query
      $arr_efMarker = array();
      preg_match_all('|###ENABLE_FIELDS\:(.*)###|U', $query, $arr_result, PREG_PATTERN_ORDER);
      if (isset($arr_result[0]))
      {
        $arr_efMarker = $arr_result[0];
      }

      // replace gp:marker
      foreach($arr_gpMarker as $str_gpMarker)
      {
        $value = null;
        if (isset($marker[$str_gpMarker]))
        {
          $value = $marker[$str_gpMarker];
        }
        $query = str_replace($str_gpMarker, $value, $query);
      }

      // replace enable_fields:marker
      foreach($arr_efMarker as $str_efMarker)
      {
        $str_efTable = trim(strtolower($str_efMarker), '#');
        list( $dummy, $str_efTable ) = explode(':', $str_efTable);
        unset( $dummy );
        $andWhere_ef = tslib_cObj::enableFields($str_efTable);
        $query = str_replace($str_efMarker, $andWhere_ef, $query);
      }

      // #42154, 121203, dwildt, 1-
//    $pObj->conf['db.']['sql'] = $query;
      // #42154, 121203, dwildt, 2+
      $this->pObj->conf['db.']['sql'] = 'TEXT';
      $this->pObj->conf['db.']['sql.']['value'] = $query;
    }




}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_session.php'])
{
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_session.php']);
}
?>
