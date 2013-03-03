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

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  109: class tx_caddy extends tslib_pibase
 *
 *              SECTION: Main
 *  156:     public function main( $content, $conf )
 *
 *              SECTION: Cart
 *  215:     private function caddy( )
 *  252:     private function caddyWiProducts( )
 *  431:     private function caddyWiProductsItem( $contentItem )
 *  462:     private function caddyWiProductsPayment( )
 *  506:     private function caddyWiProductsProduct( )
 *  581:     private function caddyWiProductsProductErrorMsg( $product )
 *  605:     private function caddyWiProductsProductServiceAttributes( $product )
 *  667:     private function caddyWiProductsProductSettings( $product )
 *  717:     private function caddyWiProductsProductTax( $product )
 *  770:     private function caddyWiProductsShipping( )
 *  814:     private function caddyWiProductsSpecial( )
 *  859:     private function caddyWoProducts( )
 *
 *              SECTION: Clean
 *  886:     private function clean( )
 *
 *              SECTION: Debug
 *  908:     private function debugOutputBeforeRunning( )
 *
 *              SECTION: Init
 *  945:     private function init( )
 *  965:     private function initAccessByIp( )
 * 1011:     private function initPid( )
 * 1050:     private function initFlexform( )
 * 1063:     private function initGpVar( )
 * 1119:     private function initGpVarCid( )
 * 1169:     private function initHtmlTemplate( )
 * 1251:     private function initInstances( )
 * 1298:     private function initPowermail( )
 * 1311:     private function initServiceAttributes( )
 *
 *              SECTION: Order
 * 1338:     private function orderUpdate( )
 *
 *              SECTION: Product
 * 1383:     private function productAdd( )
 * 1405:     private function productRemove( )
 *
 *              SECTION: Session
 * 1429:     public function sessionDelete( )
 *
 *              SECTION: Update Wizard
 * 1461:     private function updateWizard( $content )
 *
 *              SECTION: ZZ
 * 1503:     private function zz_getPriceForOption($type, $option_id)
 * 1554:     private function zz_checkOptionIsNotAvailable($type, $option_id)
 * 1597:     private function add_qtyname_marker($product, $markerArray, $pObj)
 * 1635:     private function add_variant_gpvar_to_imagelinkwrap($product, $ts_key, $ts_conf, $pObj)
 * 1667:     public function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 * 1690:     private function zz_renderOptionList($type, $option_id)
 * 1800:     private function zz_price_format($value)
 *
 * TOTAL FUNCTIONS: 37
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
class tx_caddy extends tslib_pibase
{

  public $extKey = 'caddy';
  public $prefixId = 'tx_caddy_pi1';
  public $scriptRelPath = 'lib/caddy/class.tx_caddy.php';

  
  private $caddyCount                 = 0;
  private $caddyGrossNoService        = null;
  private $caddyServiceAttribute1Max  = null;
  private $caddyServiceAttribute1Sum  = null;
  private $caddyServiceAttribute2Max  = null;
  private $caddyServiceAttribute2Sum  = null;
  private $caddyServiceAttribute3Max  = null;
  private $caddyServiceAttribute3Sum  = null;

    // [array] current typoscript configuration
  public $conf = null;

  private $markerArray = array( );

  // [object] parent DRS object
  private $drs = null;

  // [object] parent object
  public $pObj = null;

  // [object] parent powermail object
  private $powermail = null;

  // [object] parent session object
  private $session = null;

  private $product = array( );

  
  private $outerMarkerArray = array( );
  
    // [array] current tt_content row or current pi_flexform row
  public $row  = null;
  
  private $tmpl;
  





  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * caddy( )
  *
  * @return	string    $caddy  : HTML caddy
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function caddy( )
  {
    $arrReturn = null;

      // Set the current typoscript configuration
    $this->conf       = $this->pObj->conf;
    $this->cObj       = $this->pObj->cObj;
    $this->local_cObj = $this->pObj->local_cObj;
    
    $this->calc       = $this->pObj->calc;
    $this->drs        = $this->pObj->drs;
    $this->powermail  = $this->pObj->powermail;
    $this->session    = $this->pObj->session;
    $this->tmpl       = $this->pObj->tmpl;
    
      // read all products from session
    $this->product = $this->session->productsGet( );

    switch( true )
    {
      case( count( $this->product ) > 0 ):
        $caddy = $this->caddyWiProducts( );
        break;
      case( ! ( count( $this->product ) > 0 ) ):
      default:
        $caddy = $this->caddyWoProducts( );
        break;
    }

    $arrReturn['caddy']             = $caddy;
    $arrReturn['tmpl']              = $this->tmpl; 
    $arrReturn['outerMarkerArray']  = $this->outerMarkerArray; 
    return $arrReturn;
  }

  
 /**
  * caddyByUserfunc( )
  *
  * @return	string    $caddy  : HTML caddy
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function caddyByUserfunc( $content = '', $conf = array( ) )
  {
    $caddy = null;

      // Set the current typoscript configuration
    $this->conf = $this->pObj->conf;
    
      // read all products from session
    $this->product = $this->session->productsGet( );

    switch( true )
    {
      case( count( $this->product ) > 0 ):
        $caddy = $this->caddyWiProducts( );
        break;
      case( ! ( count( $this->product ) > 0 ) ):
      default:
        $caddy = $this->caddyWoProducts( );
        break;
    }

    return $caddy;
  }

 /**
  * caddyWiProducts( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProducts( )
  {
      // #45915, 130228
      // Set the hidden field to false of the powermail form
    $this->powermail->formShow( );

    $subpartArray   = null;
    $shippingArray  = null;
    $paymentArray   = null;
    $specialArray   = null;

      // handle the current product
    $arrResult      = $this->caddyWiProductsProduct( );
    $contentItem    = $arrResult['contentItem'];
    $caddyNet        = $arrResult['cartNet'];
    $caddyGross      = $arrResult['cartNet'];
    $caddyTaxReduced = $arrResult['cartTaxReduced'];
    $caddyTaxNormal  = $arrResult['cartTaxNormal'];
    unset( $arrResult );
      // handle the current product

    $subpartArray['###CONTENT###'] = $this->caddyWiProductsItem( $contentItem );

    $this->caddyGrossNoService = $caddyGross;
    $caddyNetNoService         = $caddyNet;

      // shipping
    $arrResult      = $this->caddyWiProductsShipping( );
    $shippingId     = $arrResult['id'];
    $shippingNet    = $arrResult['net'];
    $shippingGross  = $arrResult['gross'];
    $caddyNet        = $caddyNet        + $shippingNet;
    $caddyGross      = $caddyGross      + $shippingGross;
    $caddyTaxReduced = $caddyTaxReduced + $arrResult['cartTaxReduced'];
    $caddyTaxNormal  = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
    unset( $arrResult );
      // shipping

      // payment
    $arrResult      = $this->caddyWiProductsPayment( );
    $paymentId      = $arrResult['id'];
    $paymentNet     = $arrResult['net'];
    $paymentGross   = $arrResult['gross'];
    $caddyNet        = $caddyNet        + $paymentNet;
    $caddyGross      = $caddyGross      + $paymentGross;
    $caddyTaxReduced = $caddyTaxReduced + $arrResult['cartTaxReduced'];
    $caddyTaxNormal  = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
    unset( $arrResult );
      // payment

      // special
    $arrResult            = $this->caddyWiProductsSpecial( );
    $specialIds           = $arrResult['ids'];
    $overall_specialNet   = $arrResult['net'];
    $overall_specialGross = $arrResult['gross'];
    $caddyNet              = $caddyNet        + $overall_specialNet;
    $caddyGross            = $caddyGross      + $overall_specialGross;
    $caddyTaxReduced       = $caddyTaxReduced + $arrResult['cartTaxReduced'];
    $caddyTaxNormal        = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
    unset( $arrResult );
      // special

      // service
    $serviceNet   = $shippingNet    + $paymentNet   + $overall_specialNet;
    $serviceGross = $shippingGross  + $paymentGross + $overall_specialGross;
      // service

      // session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
    $sesArray['service_cost_net']       = $serviceNet;
    $sesArray['service_cost_gross']     = $serviceGross;
    $sesArray['cart_gross']             = $caddyGross;
    $sesArray['cart_gross_no_service']  = $this->caddyGrossNoService;
    $sesArray['cart_net']               = $caddyNet;
    $sesArray['cart_net_no_service']    = $caddyNetNoService;
    $sesArray['cart_tax_reduced']       = $caddyTaxReduced;
    $sesArray['cart_tax_normal']        = $caddyTaxNormal;
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
      // session

      // cObject becomes current record
    $currRecord = array(
      'service_cost_net'      => $serviceNet,
      'service_cost_gross'    => $serviceGross,
      'cart_gross'            => $caddyGross,
      'cart_gross_no_service' => $this->caddyGrossNoService,
      'cart_net'              => $caddyNet,
      'cart_net_no_service'   => $caddyNetNoService,
      'cart_tax_reduced'      => $caddyTaxReduced,
      'cart_tax_normal'       => $caddyTaxNormal
    );
    $this->local_cObj->start( $currRecord, $this->conf['db.']['table'] ); // enable .field in typoscript
      // cObject becomes current record

      // FOREACH  : setting (cart_net, cart_gross, price_total, service_costs, odernumber, target, taxrates, tax)
    foreach( array_keys( ( array ) $this->conf['settings.']['overall.'] ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }

      $marker = $this->local_cObj->cObjGetSingle
                (
                  $this->conf['settings.']['overall.'][$key],
                  $this->conf['settings.']['overall.'][$key . '.']
                );
      $this->outerMarkerArray['###' . strtoupper( $key ) . '###'] = $marker;
        // DRS
      if( $this->drs->drsMarker )
      {
        $prompt = 'Overall - ###' . strtoupper( $key ) . '### : "' . $marker . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
    }
      // FOREACH  : setting (cart_net, cart_gross, price_total, service_costs, odernumber, target, taxrates, tax)

      // Set min price error
    if( $sesArray['cart_gross_no_service'] < floatval( $this->conf['cart.']['cartmin.']['value'] ) )
    {
      $caddyMinStr                             = $this->zz_price_format($this->conf['cart.']['cartmin.']['value']);
      $minPriceArray['###ERROR_MINPRICE###']  = sprintf($this->pi_getLL('caddy_ll_minprice'), $caddyMinStr);
      $subpartArray['###MINPRICE###']         =
        $this->cObj->substituteMarkerArrayCached($this->tmpl['minprice'], $minPriceArray);
    }
      // Set min price error

      // Set shipping radio, payment radio and special checkbox
    if
    (
      ! ( $sesArray['cart_gross_no_service'] < floatval( $this->conf['cart.']['cartmin.']['value'] ) )
      ||
      (
        ( $sesArray['cart_gross_no_service'] < floatval($this->conf['cart.']['cartmin.']['value'] ) )
        &&
        ( ! $this->conf['cart.']['cartmin.']['hideifnotreached.']['service'] )
      )
    )
    {
      $shippingArray['###CONTENT###'] = $this->zz_renderOptionList( 'shipping', $shippingId );
      $subpartArray['###SHIPPING_RADIO###'] = '';
      if( $shippingArray['###CONTENT###'] )
      {
        $subpartArray['###SHIPPING_RADIO###'] =
          $this->cObj->substituteMarkerArrayCached( $this->tmpl['shipping_all'], null, $shippingArray );
      }

      $paymentArray['###CONTENT###'] = $this->zz_renderOptionList('payment', $paymentId);
      $subpartArray['###PAYMENT_RADIO###'] = '';
      if( $paymentArray['###CONTENT###'] )
      {
        $subpartArray['###PAYMENT_RADIO###'] =
          $this->cObj->substituteMarkerArrayCached( $this->tmpl['payment_all'], null, $paymentArray );
      }

      $subpartArray['###SPECIAL_CHECKBOX###'] = '';
      $specialArray['###CONTENT###'] = $this->zz_renderOptionList('special', $specialIds);
      if( $specialArray['###CONTENT###'] )
      {
        $subpartArray['###SPECIAL_CHECKBOX###'] =
          $this->cObj->substituteMarkerArrayCached( $this->tmpl['special_all'], null, $specialArray );
      }
    }

      // RESET cObj->data
    return $subpartArray;
  }

 /**
  * caddyWiProductsItem( )
  *
  * @param	[type]		$$contentItem: ...
  * @return	string		$contentItem  : current content
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsItem( $contentItem )
  {
      // item for payment
    $paymentId = $this->session->paymentGet( );
    if( $paymentId )
    {
      $this->markerArray['###QTY###']         = 1;
      $this->markerArray['###TITLE###']       = $this->conf['payment.']['options.'][$paymentId . '.']['title'];
      $this->markerArray['###PRICE###']       = $this->conf['payment.']['options.'][$paymentId . '.']['extra'];
      $this->markerArray['###PRICE_TOTAL###'] = $this->conf['payment.']['options.'][$paymentId . '.']['extra'];
         // add inner html to variable
      $contentItem = $contentItem . $this->cObj->substituteMarkerArrayCached
                                    (
                                      $this->tmpl['special_item'],
                                      $this->markerArray
                                    );
    }

    $contentItem  = $contentItem . '<input type="hidden" name="tx_caddy_pi1[update_from_cart]" value="1">';

    return $contentItem;
  }

 /**
  * caddyWiProductsPayment( )
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsPayment( )
  {
    $arrReturn = null;

    $paymentId = $this->session->paymentGet();

    if( ! $paymentId )
    {
      $paymentId = intval( $this->conf['payment.']['preset'] );
      $this->session->paymentUpdate( $paymentId );
    }
      // check if selected payment option is available
    $newpaymentId = $this->zz_checkOptionIsNotAvailable( 'payment', $paymentId );
    if( $newpaymentId )
    {
      $paymentId = $newpaymentId;
      $this->session->paymentUpdate( $newpaymentId );
    }

    list( $gross, $net ) = $this->calc->calculateOptionById( $this->conf, 'payment', $paymentId, $this );

    if( $this->conf['payment.']['options.'][$paymentId . '.']['tax'] == 'reduced' )
    {
      $arrReturn['cartTaxReduced'] = $paymentGross - $paymentNet;
    }
    else
    {
      $arrReturn['cartTaxNormal'] = $paymentGross - $paymentNet;
    }

    $arrReturn['id']    = $paymentId;
    $arrReturn['gross'] = $gross;
    $arrReturn['net']   = $net;
    return $arrReturn;
  }

 /**
  * caddyWiProductsProduct( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsProduct( )
  {
    $arrReturn      = null;
    $contentItem    = '';
    $caddyNet        = 0;
    $caddyGross      = 0;
    $caddyTaxReduced = 0;
    $caddyTaxNormal  = 0;

      // FOREACH  : product
    foreach( ( array ) $this->product as $product )
    {
        // clear marker array to avoid problems with error msg etc.
      unset( $this->markerArray );

        // calculate price total
      $product['price_total'] = $product['price'] * $product['qty'];
        // DRS
      if( $this->drs->drsFormula )
      {
        $prompt = $product['title'] . ': ' . $product['price'] . ' x ' . $product['qty'] . ' = ' . $product['price_total'];
        t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      }
        // DRS

        // cObject become current record
      $this->local_cObj->start( $product, $this->conf['db.']['table'] );

        // update settings
      $this->caddyWiProductsProductSettings( $product );

        // update error prompts
      $this->caddyWiProductsProductErrorMsg( $product );

         // add inner html to variable
      $contentItem = $contentItem . $this->cObj->substituteMarkerArrayCached
                                    (
                                      $this->tmpl['item'], $this->markerArray
                                    );

        // update cart gross
      $caddyGross        = $caddyGross + $product['price_total'];
        // update number of products
      $this->caddyCount  = $this->caddyCount + $product['qty'];

        // update service attributes
      $this->caddyWiProductsProductServiceAttributes( $product );

        // calculate tax
      $arrResult      = $this->caddyWiProductsProductTax( $product );
      $caddyNet        = $caddyNet        + $arrResult['cartNet'];
      $caddyTaxReduced = $caddyTaxReduced + $arrResult['cartTaxReduced'];
      $caddyTaxNormal  = $caddyTaxNormal  + $arrResult['cartTaxNormal'];

    }
      // FOREACH  : product

    $arrReturn['contentItem']     = $contentItem;
    $arrReturn['cartNet']         = $caddyNet;
    $arrReturn['cartGross']       = $caddyGross;
    $arrReturn['cartTaxReduced']  = $caddyTaxReduced;
    $arrReturn['cartTaxNormal']   = $caddyTaxNormal;

    return $arrReturn;
  }

 /**
  * caddyWiProductsProductErrorMsg( )
  *
  * @param	array		$product :
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsProductErrorMsg( $product )
  {
      // unset error msg
    $this->markerArray['###ERROR_MSG###'] = '';

      // FOREACH  : error messages per product
    foreach( $product['error'] as $error )
    {
      $errMsg = sprintf( $this->pi_getLL( 'caddy_ll_error_' . $error ), $product[$error] );

      $this->markerArray['###ERROR_MSG###'] = $this->markerArray['###ERROR_MSG###'] . $errMsg;
    }
      // FOREACH  : error messages per product
  }

 /**
  * caddyWiProductsProductServiceAttributes( )
  *
  * @param	array		$product :
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsProductServiceAttributes( $product )
  {
      // DRS
    if( $this->drs->drsTodo )
    {
      $prompt = 'Unproper formula? In case of an exceeded maximum of service attributes, max will updated, sum won\'t!';
      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->extKey, 3 );
      $prompt = 'The developer has to check the formula.';
      t3lib_div::devlog( '[HELP/TODO] ' . $prompt, $this->extKey, 1 );
    }
      // DRS

    $this->caddyServiceAttribute1Sum = $this->caddyServiceAttribute1Sum +
                                      $product['service_attribute_1'] * $product['qty'];
    if( $this->caddyServiceAttribute1Max > $product['service_attribute_1'] )
    {
      $this->caddyServiceAttribute1Max = $this->caddyServiceAttribute1Max;
    }
    else
    {
      $this->caddyServiceAttribute1Max = $product['service_attribute_1'];
    }

    $this->caddyServiceAttribute2Sum = $this->caddyServiceAttribute2Sum +
                                      $product['service_attribute_2'] * $product['qty'];
    if( $this->caddyServiceAttribute2Max > $product['service_attribute_2'] )
    {
      $this->caddyServiceAttribute2Max = $this->caddyServiceAttribute2Max;
    }
    else
    {
      $this->caddyServiceAttribute2Max = $product['service_attribute_2'];
    }

    $this->caddyServiceAttribute3Sum = $this->caddyServiceAttribute3Sum +
                                      $product['service_attribute_3'] * $product['qty'];
    if( $this->caddyServiceAttribute3Max > $product['service_attribute_3'] )
    {
      $this->caddyServiceAttribute3Max = $this->caddyServiceAttribute3Max;
    }
    else
    {
      $this->caddyServiceAttribute3Max = $product['service_attribute_3'];
    }

//    $this->caddyServiceAttribute1Sum += $product['service_attribute_1'] * $product['qty'];
//    $this->caddyServiceAttribute1Max = $this->caddyServiceAttribute1Max > $product['service_attribute_1'] ? $this->caddyServiceAttribute1Max : $product['service_attribute_1'];
//    $this->caddyServiceAttribute2Sum += $product['service_attribute_2'] * $product['qty'];
//    $this->caddyServiceAttribute2Max = $this->caddyServiceAttribute2Max > $product['service_attribute_2'] ? $this->caddyServiceAttribute2Max : $product['service_attribute_2'];
//    $this->caddyServiceAttribute3Sum += $product['service_attribute_3'] * $product['qty'];
//    $this->caddyServiceAttribute3Max = $this->caddyServiceAttribute3Max > $product['service_attribute_3'] ? $this->caddyServiceAttribute3Max : $product['service_attribute_3'];
  }

 /**
  * caddyWiProductsProductSettings( )
  *
  * @param	array		$product :
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsProductSettings( $product )
  {
//var_dump( __METHOD__, __LINE__, $this->cObj->data, array_keys( ( array ) $this->conf['settings.']['fields.'] ) );
      // FOREACH  : settings property
    foreach( array_keys( ( array ) $this->conf['settings.']['fields.'] ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }

        // name of the current field in the TypoScript
      $ts_key   = $this->conf['settings.']['fields.'][$key];
        // configuration array of the current field in the TypoScript
      $ts_conf  = $this->conf['settings.']['fields.'][$key . '.'];
      switch( $key )
      {
        case('delete'):
          $ts_conf = $this->add_variant_gpvar_to_imagelinkwrap( $product, $ts_key, $ts_conf, $this );
          break;
        default:
          // nothing to do, there is no default now
      }
      $ts_rendered_value  = $this->local_cObj->cObjGetSingle( $ts_key, $ts_conf );
      $this->markerArray['###' . strtoupper( $key ) . '###'] = $ts_rendered_value; // write to marker

        // DRS
      if( $this->drs->drsMarker )
      {
        $prompt = 'Product - ###' . strtoupper( $key ) . '### : "' . $ts_rendered_value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
        // DRS

      // adds the ###QTY_NAME### marker in case of variants
      $this->markerArray = $this->add_qtyname_marker($product, $this->markerArray, $this);
    }
      // FOREACH  : settings property
//var_dump( __METHOD__, __LINE__, $this->markerArray );
  }

 /**
  * caddyWiProductsProductTax( )
  *
  * @param	array		$product  :
  * @return	array		$tax      : cartNet, cartTaxReduced, cartTaxNormal
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsProductTax( $product )
  {
    $arrReturn = null;

      // Handle HTML snippet
      // get the formular with the markers ###TAX## for calculating tax
    $str_wrap = $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'];
      // save the formular with marker, we need it later
    $str_wrap_former = $str_wrap;
      // replace the ###TAX### with current tax rate like 0.07 or 0.19
    $str_wrap = str_replace( '###TAX###', $product['tax'], $str_wrap );
      // assign the formular with tax rates to TypoScript
    $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap;
      // Handle HTML snippet

      // tax within price grosso
    $currTax = $this->local_cObj->cObjGetSingle
           (
              $this->conf['settings.']['fields.']['tax'],
              $this->conf['settings.']['fields.']['tax.']
           );
      // price netto
    $arrReturn['cartNet'] = $product['price_total'] - $currTax;

    switch( $product['tax'] )
    {
      case( 0 ):
        break;
      case( 1 ):
      case( $this->conf['tax.']['reducedCalc'] ):
        $arrReturn['cartTaxReduced'] = $currTax;
        break;
      case( 2 ):
      case( $this->conf['tax.']['normalCalc'] ):
        $arrReturn['cartTaxNormal'] = $currTax;
        break;
      default:
        echo '<div style="border:2em solid red;padding:2em;color:red;"><h1 style="color:red;">caddy Error</h1><p>tax is "' . $product['tax'] . '".<br />This is an undefined value in class.tx_caddy.php. ABORT!<br /><br />Are you sure, that you included the caddy static template?</p></div>';
        exit;
    }
    $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap_former;

    return $arrReturn;
  }

 /**
  * caddyWiProductsShipping( )
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsShipping( )
  {
    $arrReturn = null;

    $shippingId = $this->session->shippingGet();

    if( ! $shippingId )
    {
      $shippingId = intval( $this->conf['shipping.']['preset'] );
      $this->session->shippingUpdate( $shippingId );
    }
      // check if selected shipping option is available
    $newshippingId = $this->zz_checkOptionIsNotAvailable( 'shipping', $shippingId );
    if( $newshippingId )
    {
      $shippingId = $newshippingId;
      $this->session->shippingUpdate( $newshippingId );
    }

    list( $gross, $net ) = $this->calc->calculateOptionById( $this->conf, 'shipping', $shippingId, $this );

    if( $this->conf['shipping.']['options.'][$shippingId . '.']['tax'] == 'reduced' )
    {
      $arrReturn['cartTaxReduced'] = $shippingGross - $shippingNet;
    }
    else
    {
      $arrReturn['cartTaxNormal'] = $shippingGross - $shippingNet;
    }

    $arrReturn['id']    = $shippingId;
    $arrReturn['gross'] = $gross;
    $arrReturn['net']   = $net;
    return $arrReturn;
  }

 /**
  * caddyWiProductsSpecial( )
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsSpecial( )
  {
    $arrReturn = null;

    $specialIds = $this->session->specialGet( );

    $caddyTaxReduced       = 0.0;
    $caddyTaxNormal        = 0.0;
    $overall_specialGross = 0.0;
    $overall_specialNet   = 0.0;

    foreach( $specialIds as $specialId )
    {
      list( $specialGross, $specialNet ) = $this->calc->calculateOptionById( $this->conf, 'special', $specialId, $this );
      $caddyNet    = $caddyNet    + $specialNet;
      $caddyGross  = $caddyGross  + $specialGross;
      if( $this->conf['special.']['options.'][$specialId . '.']['tax'] == 'reduced' )
      {
        $caddyTaxReduced = $caddyTaxReduced + $specialGross - $specialNet;
      }
      else
      {
        $caddyTaxNormal = $caddyTaxNormal + $specialGross - $specialNet;
      }
      $overall_specialGross = $overall_specialGross + $specialGross;
      $overall_specialNet   = $overall_specialNet   + $specialNet;
    }

    $arrReturn['ids']             = $specialIds;
    $arrReturn['net']             = $overall_specialNet;
    $arrReturn['gross']           = $overall_specialGross;
    $arrReturn['cartTaxReduced']  = $caddyTaxReduced;
    $arrReturn['cartTaxNormal']   = $caddyTaxNormal;

    return $arrReturn;
  }

 /**
  * caddyWoProducts( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWoProducts( )
  {
      // #45915, 130228
      // Set the hidden field to true of the powermail form
    $css = $this->powermail->FormHide( );

    $this->tmpl['all'] = $this->tmpl['empty']; // overwrite normal template with empty template

    return $css;
  }

  
  
 /**
  * add_qty_marker():  Allocates to the global markerArray a value for ###QTY_NAME###
  *                          in case of variant
  *                          It returns in aray with hidden fields like
  *                          <input type="hidden"
  *                                 name="tx_caddy_pi1[puid][20][]"
  *                                 value="tx_caddy_pi1[tx_org_calentrance.uid]=4|tx_caddy_pi1[qty]=91" />
  *
  * @param	array		$products: array with products with elements uid, title, tax, etc...
  * @param	array		$markerArray: current marker array
  * @param	array		$pobj: Parent Object
  * @return	array		$markerArray: with added element ###VARIANTS### in case of variants
  * @access private
  * @version 2.0.0
  * @since 1.4.6
  */
  private function add_qtyname_marker($product, $markerArray, $pObj)
  {
      // default name for QTY. It is compatible with version 1.2.1
      $markerArray['###QTY_NAME###'] = 'tx_caddy_pi1[qty][' . $product['puid'] . ']';

      // return there isn't any variant
      if (!is_array($pObj->conf['settings.']['variant.']))
      {
          return $markerArray;
      }

      $str_marker = null;
      // get all variant key/value pairs from the current product
      $array_add_gpvar = $this->productGetVariantTs($product, $pObj);
      $array_add_gpvar['puid']  = $product['puid'];
      // generate the marker array
      foreach ((array) $array_add_gpvar as $key => $value)
      {
          $str_marker = $str_marker . '[' . $key . '=' . $value . ']';
      }
      $markerArray['###QTY_NAME###'] = 'tx_caddy_pi1[qty]'. $str_marker;

      return $markerArray;
  }

 /**
  * add_variant_gpvar_to_imagelinkwrap():  Adds all table.field of the variant to
  *                                          imageLinkWrap.typolink.additionalParams.wrap
  *
  * @param	array		$product: array with product uid, title, tax, etc...
  * @param	string		$ts_key: key of the current TypoScript configuration array
  * @param	array		$ts_conf: the current TypoScript configuration array
  * @param	array		$pobj: Parent Object
  * @return	array		$ts_conf: configuration array added with the varaition gpvars
  * @access private
  * @version 2.0.0
  * @since 1.4.6
  */
  private function add_variant_gpvar_to_imagelinkwrap($product, $ts_key, $ts_conf, $pObj)
  {
      // return there isn't any variant
      if (!is_array($pObj->conf['settings.']['variant.']))
      {
          return $ts_conf;
      }

      // get all variant key/value pairs from the current product
      $array_add_gpvar = $this->productGetVariantTs($product, $pObj);

      // add variant key/value pairs to imageLinkWrap
      foreach ((array) $array_add_gpvar as $key => $value)
      {
          $str_wrap = $ts_conf['imageLinkWrap.']['typolink.']['additionalParams.']['wrap'];
          $str_wrap = $str_wrap . '&' . $this->prefixId . '[' . $key . ']=' . $value;
          $ts_conf['imageLinkWrap.']['typolink.']['additionalParams.']['wrap'] = $str_wrap;
      }

      return $ts_conf;
  }

  /**
 * Gets the option_id for a given type ('shipping', 'payment') method on the current cart and checks the
 * availability. If available, return is 0. If not available the given fallback or preset will returns.
 *
 * @param	string		$type
 * @param	int		$option_id
 * @return	int
 */
  private function zz_checkOptionIsNotAvailable($type, $option_id)
  {
    if ((isset($this->conf[$type.'.']['options.'][$option_id.'.']['available_from']) && (round(floatval($this->conf[$type.'.']['options.'][$option_id.'.']['available_from']),2) > round($this->caddyGrossNoService,2))) || (isset($this->conf[$type.'.']['options.'][$option_id.'.']['available_until']) && (round(floatval($this->conf[$type.'.']['options.'][$option_id.'.']['available_until']),2) < round($this->caddyGrossNoService,2))))
    {
      // check: fallback is given
      if (isset($this->conf[$type.'.']['options.'][$option_id.'.']['fallback']))
      {
        $fallback = $this->conf[$type.'.']['options.'][$option_id.'.']['fallback'];
        // check: fallback is defined; the availability of fallback will not tested yet
        if (isset($this->conf[$type.'.']['options.'][$fallback.'.']))
        {
          $newoption_id = intval($fallback);
        } else {
// 130227, dwildt, 1-
//                                  $shippingId = intval($this->conf[$type.'.']['preset']);
// 130227, dwildt, 1+
          $newoption_id = intval($this->conf[$type.'.']['preset']);
        }
      } else {
        $newoption_id = intval($this->conf[$type.'.']['preset']);
      }
      return $newoption_id;
    }

    return 0;
  }


  
  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy.php']);
}
?>
