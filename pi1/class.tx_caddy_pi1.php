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
 *  109: class tx_caddy_pi1 extends tslib_pibase
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
class tx_caddy_pi1 extends tslib_pibase
{

  public $prefixId = 'tx_caddy_pi1';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

  private $accessByIP = null;

  private $product = array( );

  private $newProduct = array( );

  private $template = array( );

  private $markerArray = array( );

  private $outerMarkerArray = array( );

  private $gpvar = array( );

  public $pid = null;






  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content: The PlugIn content
  * @param	array		$conf: The PlugIn configuration
  * @return	The		content that is displayed on the website
  * @version    2.0.0
  * @since      1.4.6
  */
  public function main( $content, $conf )
  {
      // page object
    $this->local_cObj = $GLOBALS['TSFE']->cObj;

    $this->conf = $conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
      // Init extension configuration array
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );
      // init flexform
    $this->pi_initPIflexForm();

      // 130227, dwildt, 1-
    //$this->pi_USER_INT_obj = 1;

      // Init DRS, flexform, gpvars, HTML template, service attributes
    $this->init( );

      // Prompt of the update wizard
    $content = $this->updateWizard( $content );

      // Output debugging prompts in debug mode
    $this->debugOutputBeforeRunning( );

      // Remove current product
    $this->productRemove( );
      // Update several order values
    $this->orderUpdate( );
      // Add a product
    $this->productAdd( );

      // Get the caddy
    $arrResult              = $this->caddy( );
    $caddy                  = $arrResult['caddy']; 
    $this->tmpl             = $arrResult['tmpl']; 
    $this->outerMarkerArray = $arrResult['outerMarkerArray']; 
    unset( $arrResult );

    $content = $this->powermail->formCss( $content );

    $this->send( );

    $this->clean( );

    $content = $content . $this->cObj->substituteMarkerArrayCached
                    (
                      $this->tmpl['all'],
                      $this->outerMarkerArray,
                      $caddy
                    );
    $content = $this->dynamicMarkers->main( $content, $this ); // Fill dynamic locallang or typoscript markers
    $content = preg_replace( '|###.*?###|i', '', $content ); // Finally clear not filled markers
    return $this->pi_wrapInBaseClass( $content );
  }
 


  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * caddy( )
  *
  * @return	array		$caddy : ...
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddy( )
  {
    $arrReturn = $this->caddy->caddy( );
    return $arrReturn;
    $arrReturn = null;

    $this->caddyCount = 0;

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

// /**
//  * caddyWiProducts( )
//  *
//  * @return	void
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProducts( )
//  {
//      // #45915, 130228
//      // Set the hidden field to false of the powermail form
//    $this->powermail->formShow( );
//
//    $subpartArray   = null;
//    $shippingArray  = null;
//    $paymentArray   = null;
//    $specialArray   = null;
//
//      // handle the current product
//    $arrResult        = $this->caddyWiProductsProduct( );
//    $contentItem      = $arrResult['contentItem'];
//    $caddyNet         = $arrResult['cartNet'];
//    $caddyGross       = $arrResult['cartNet'];
//    $caddyTaxReduced  = $arrResult['cartTaxReduced'];
//    $caddyTaxNormal   = $arrResult['cartTaxNormal'];
//    unset( $arrResult );
//      // handle the current product
//
//    $subpartArray['###CONTENT###'] = $this->caddyWiProductsItem( $contentItem );
//
//    $this->caddyGrossNoService = $caddyGross;
//    $caddyNetNoService         = $caddyNet;
//
//      // shipping
//    $arrResult      = $this->caddyWiProductsShipping( );
//    $shippingId     = $arrResult['id'];
//    $shippingNet    = $arrResult['net'];
//    $shippingGross  = $arrResult['gross'];
//    $caddyNet        = $caddyNet        + $shippingNet;
//    $caddyGross      = $caddyGross      + $shippingGross;
//    $caddyTaxReduced = $caddyTaxReduced + $arrResult['cartTaxReduced'];
//    $caddyTaxNormal  = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
//    unset( $arrResult );
//      // shipping
//
//      // payment
//    $arrResult      = $this->caddyWiProductsPayment( );
//    $paymentId      = $arrResult['id'];
//    $paymentNet     = $arrResult['net'];
//    $paymentGross   = $arrResult['gross'];
//    $caddyNet        = $caddyNet        + $paymentNet;
//    $caddyGross      = $caddyGross      + $paymentGross;
//    $caddyTaxReduced = $caddyTaxReduced + $arrResult['cartTaxReduced'];
//    $caddyTaxNormal  = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
//    unset( $arrResult );
//      // payment
//
//      // special
//    $arrResult            = $this->caddyWiProductsSpecial( );
//    $specialIds           = $arrResult['ids'];
//    $overall_specialNet   = $arrResult['net'];
//    $overall_specialGross = $arrResult['gross'];
//    $caddyNet              = $caddyNet        + $overall_specialNet;
//    $caddyGross            = $caddyGross      + $overall_specialGross;
//    $caddyTaxReduced       = $caddyTaxReduced + $arrResult['cartTaxReduced'];
//    $caddyTaxNormal        = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
//    unset( $arrResult );
//      // special
//
//      // service
//    $serviceNet   = $shippingNet    + $paymentNet   + $overall_specialNet;
//    $serviceGross = $shippingGross  + $paymentGross + $overall_specialGross;
//      // service
//
//      // session
//    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
//    $sesArray['service_cost_net']       = $serviceNet;
//    $sesArray['service_cost_gross']     = $serviceGross;
//    $sesArray['cart_gross']             = $caddyGross;
//    $sesArray['cart_gross_no_service']  = $this->caddyGrossNoService;
//    $sesArray['cart_net']               = $caddyNet;
//    $sesArray['cart_net_no_service']    = $caddyNetNoService;
//    $sesArray['cart_tax_reduced']       = $caddyTaxReduced;
//    $sesArray['cart_tax_normal']        = $caddyTaxNormal;
//    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
//      // session
//
//      // cObject becomes current record
//    $currRecord = array(
//      'service_cost_net'      => $serviceNet,
//      'service_cost_gross'    => $serviceGross,
//      'cart_gross'            => $caddyGross,
//      'cart_gross_no_service' => $this->caddyGrossNoService,
//      'cart_net'              => $caddyNet,
//      'cart_net_no_service'   => $caddyNetNoService,
//      'cart_tax_reduced'      => $caddyTaxReduced,
//      'cart_tax_normal'       => $caddyTaxNormal
//    );
//    $this->local_cObj->start( $currRecord, $this->conf['db.']['table'] ); // enable .field in typoscript
//      // cObject becomes current record
//
//      // FOREACH  : setting (cart_net, cart_gross, price_total, service_costs, odernumber, target, taxrates, tax)
//    foreach( array_keys( ( array ) $this->conf['settings.']['overall.'] ) as $key )
//    {
//      if( stristr( $key, '.' ) )
//      {
//        continue;
//      }
//
//      $marker = $this->local_cObj->cObjGetSingle
//                (
//                  $this->conf['settings.']['overall.'][$key],
//                  $this->conf['settings.']['overall.'][$key . '.']
//                );
//      $this->outerMarkerArray['###' . strtoupper( $key ) . '###'] = $marker;
//        // DRS
//      if( $this->drs->drsMarker )
//      {
//        $prompt = 'Overall - ###' . strtoupper( $key ) . '### : "' . $marker . '"';
//        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
//      }
//        // DRS
//    }
//      // FOREACH  : setting (cart_net, cart_gross, price_total, service_costs, odernumber, target, taxrates, tax)
//
//      // Set min price error
//    if( $sesArray['cart_gross_no_service'] < floatval( $this->conf['cart.']['cartmin.']['value'] ) )
//    {
//      $caddyMinStr                             = $this->zz_price_format($this->conf['cart.']['cartmin.']['value']);
//      $minPriceArray['###ERROR_MINPRICE###']  = sprintf($this->pi_getLL('caddy_ll_minprice'), $caddyMinStr);
//      $subpartArray['###MINPRICE###']         =
//        $this->cObj->substituteMarkerArrayCached($this->tmpl['minprice'], $minPriceArray);
//    }
//      // Set min price error
//
//      // Set shipping radio, payment radio and special checkbox
//    if
//    (
//      ! ( $sesArray['cart_gross_no_service'] < floatval( $this->conf['cart.']['cartmin.']['value'] ) )
//      ||
//      (
//        ( $sesArray['cart_gross_no_service'] < floatval($this->conf['cart.']['cartmin.']['value'] ) )
//        &&
//        ( ! $this->conf['cart.']['cartmin.']['hideifnotreached.']['service'] )
//      )
//    )
//    {
//      $shippingArray['###CONTENT###'] = $this->zz_renderOptionList( 'shipping', $shippingId );
//      $subpartArray['###SHIPPING_RADIO###'] = '';
//      if( $shippingArray['###CONTENT###'] )
//      {
//        $subpartArray['###SHIPPING_RADIO###'] =
//          $this->cObj->substituteMarkerArrayCached( $this->tmpl['shipping_all'], null, $shippingArray );
//      }
//
//      $paymentArray['###CONTENT###'] = $this->zz_renderOptionList('payment', $paymentId);
//      $subpartArray['###PAYMENT_RADIO###'] = '';
//      if( $paymentArray['###CONTENT###'] )
//      {
//        $subpartArray['###PAYMENT_RADIO###'] =
//          $this->cObj->substituteMarkerArrayCached( $this->tmpl['payment_all'], null, $paymentArray );
//      }
//
//      $subpartArray['###SPECIAL_CHECKBOX###'] = '';
//      $specialArray['###CONTENT###'] = $this->zz_renderOptionList('special', $specialIds);
//      if( $specialArray['###CONTENT###'] )
//      {
//        $subpartArray['###SPECIAL_CHECKBOX###'] =
//          $this->cObj->substituteMarkerArrayCached( $this->tmpl['special_all'], null, $specialArray );
//      }
//    }
//
//      // RESET cObj->data
//    return $subpartArray;
//  }
//
// /**
//  * caddyWiProductsItem( )
//  *
//  * @param	[type]		$$contentItem: ...
//  * @return	string		$contentItem  : current content
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsItem( $contentItem )
//  {
//      // item for payment
//    $paymentId = $this->session->paymentGet( );
//    if( $paymentId )
//    {
//      $this->markerArray['###QTY###']         = 1;
//      $this->markerArray['###TITLE###']       = $this->conf['payment.']['options.'][$paymentId . '.']['title'];
//      $this->markerArray['###PRICE###']       = $this->conf['payment.']['options.'][$paymentId . '.']['extra'];
//      $this->markerArray['###PRICE_TOTAL###'] = $this->conf['payment.']['options.'][$paymentId . '.']['extra'];
//         // add inner html to variable
//      $contentItem = $contentItem . $this->cObj->substituteMarkerArrayCached
//                                    (
//                                      $this->tmpl['special_item'],
//                                      $this->markerArray
//                                    );
//    }
//
//    $contentItem  = $contentItem . '<input type="hidden" name="tx_caddy_pi1[update_from_cart]" value="1">';
//
//    return $contentItem;
//  }
//
// /**
//  * caddyWiProductsPayment( )
//  *
//  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsPayment( )
//  {
//    $arrReturn = null;
//
//    $paymentId = $this->session->paymentGet();
//
//    if( ! $paymentId )
//    {
//      $paymentId = intval( $this->conf['payment.']['preset'] );
//      $this->session->paymentUpdate( $paymentId );
//    }
//      // check if selected payment option is available
//    $newpaymentId = $this->zz_checkOptionIsNotAvailable( 'payment', $paymentId );
//    if( $newpaymentId )
//    {
//      $paymentId = $newpaymentId;
//      $this->session->paymentUpdate( $newpaymentId );
//    }
//
//    list( $gross, $net ) = $this->calc->calculateOptionById( $this->conf, 'payment', $paymentId, $this );
//
//    if( $this->conf['payment.']['options.'][$paymentId . '.']['tax'] == 'reduced' )
//    {
//      $arrReturn['cartTaxReduced'] = $paymentGross - $paymentNet;
//    }
//    else
//    {
//      $arrReturn['cartTaxNormal'] = $paymentGross - $paymentNet;
//    }
//
//    $arrReturn['id']    = $paymentId;
//    $arrReturn['gross'] = $gross;
//    $arrReturn['net']   = $net;
//    return $arrReturn;
//  }
//
// /**
//  * caddyWiProductsProduct( )
//  *
//  * @return	void
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsProduct( )
//  {
//    $arrReturn      = null;
//    $contentItem    = '';
//    $caddyNet        = 0;
//    $caddyGross      = 0;
//    $caddyTaxReduced = 0;
//    $caddyTaxNormal  = 0;
//
//      // FOREACH  : product
//    foreach( ( array ) $this->product as $product )
//    {
//        // clear marker array to avoid problems with error msg etc.
//      unset( $this->markerArray );
//
//        // calculate price total
//      $product['price_total'] = $product['price'] * $product['qty'];
//        // DRS
//      if( $this->drs->drsFormula )
//      {
//        $prompt = $product['title'] . ': ' . $product['price'] . ' x ' . $product['qty'] . ' = ' . $product['price_total'];
//        t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
//      }
//        // DRS
//
//        // cObject become current record
//      $this->local_cObj->start( $product, $this->conf['db.']['table'] );
//
//        // update settings
//      $this->caddyWiProductsProductSettings( $product );
//
//        // update error prompts
//      $this->caddyWiProductsProductErrorMsg( $product );
//
//         // add inner html to variable
//      $contentItem = $contentItem . $this->cObj->substituteMarkerArrayCached
//                                    (
//                                      $this->tmpl['item'], $this->markerArray
//                                    );
//
//        // update cart gross
//      $caddyGross        = $caddyGross + $product['price_total'];
//        // update number of products
//      $this->caddyCount  = $this->caddyCount + $product['qty'];
//
//        // update service attributes
//      $this->caddyWiProductsProductServiceAttributes( $product );
//
//        // calculate tax
//      $arrResult      = $this->caddyWiProductsProductTax( $product );
//      $caddyNet        = $caddyNet        + $arrResult['cartNet'];
//      $caddyTaxReduced = $caddyTaxReduced + $arrResult['cartTaxReduced'];
//      $caddyTaxNormal  = $caddyTaxNormal  + $arrResult['cartTaxNormal'];
//
//    }
//      // FOREACH  : product
//
//    $arrReturn['contentItem']     = $contentItem;
//    $arrReturn['cartNet']         = $caddyNet;
//    $arrReturn['cartGross']       = $caddyGross;
//    $arrReturn['cartTaxReduced']  = $caddyTaxReduced;
//    $arrReturn['cartTaxNormal']   = $caddyTaxNormal;
//
//    return $arrReturn;
//  }
//
// /**
//  * caddyWiProductsProductErrorMsg( )
//  *
//  * @param	array		$product :
//  * @return	void
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsProductErrorMsg( $product )
//  {
//      // unset error msg
//    $this->markerArray['###ERROR_MSG###'] = '';
//
//      // FOREACH  : error messages per product
//    foreach( $product['error'] as $error )
//    {
//      $errMsg = sprintf( $this->pi_getLL( 'caddy_ll_error_' . $error ), $product[$error] );
//
//      $this->markerArray['###ERROR_MSG###'] = $this->markerArray['###ERROR_MSG###'] . $errMsg;
//    }
//      // FOREACH  : error messages per product
//  }
//
// /**
//  * caddyWiProductsProductServiceAttributes( )
//  *
//  * @param	array		$product :
//  * @return	void
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsProductServiceAttributes( $product )
//  {
//      // DRS
//    if( $this->drs->drsTodo )
//    {
//      $prompt = 'Unproper formula? In case of an exceeded maximum of service attributes, max will updated, sum won\'t!';
//      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->extKey, 3 );
//      $prompt = 'The developer has to check the formula.';
//      t3lib_div::devlog( '[HELP/TODO] ' . $prompt, $this->extKey, 1 );
//    }
//      // DRS
//
//    $this->caddyServiceAttribute1Sum = $this->caddyServiceAttribute1Sum +
//                                      $product['service_attribute_1'] * $product['qty'];
//    if( $this->caddyServiceAttribute1Max > $product['service_attribute_1'] )
//    {
//      $this->caddyServiceAttribute1Max = $this->caddyServiceAttribute1Max;
//    }
//    else
//    {
//      $this->caddyServiceAttribute1Max = $product['service_attribute_1'];
//    }
//
//    $this->caddyServiceAttribute2Sum = $this->caddyServiceAttribute2Sum +
//                                      $product['service_attribute_2'] * $product['qty'];
//    if( $this->caddyServiceAttribute2Max > $product['service_attribute_2'] )
//    {
//      $this->caddyServiceAttribute2Max = $this->caddyServiceAttribute2Max;
//    }
//    else
//    {
//      $this->caddyServiceAttribute2Max = $product['service_attribute_2'];
//    }
//
//    $this->caddyServiceAttribute3Sum = $this->caddyServiceAttribute3Sum +
//                                      $product['service_attribute_3'] * $product['qty'];
//    if( $this->caddyServiceAttribute3Max > $product['service_attribute_3'] )
//    {
//      $this->caddyServiceAttribute3Max = $this->caddyServiceAttribute3Max;
//    }
//    else
//    {
//      $this->caddyServiceAttribute3Max = $product['service_attribute_3'];
//    }
//
////    $this->caddyServiceAttribute1Sum += $product['service_attribute_1'] * $product['qty'];
////    $this->caddyServiceAttribute1Max = $this->caddyServiceAttribute1Max > $product['service_attribute_1'] ? $this->caddyServiceAttribute1Max : $product['service_attribute_1'];
////    $this->caddyServiceAttribute2Sum += $product['service_attribute_2'] * $product['qty'];
////    $this->caddyServiceAttribute2Max = $this->caddyServiceAttribute2Max > $product['service_attribute_2'] ? $this->caddyServiceAttribute2Max : $product['service_attribute_2'];
////    $this->caddyServiceAttribute3Sum += $product['service_attribute_3'] * $product['qty'];
////    $this->caddyServiceAttribute3Max = $this->caddyServiceAttribute3Max > $product['service_attribute_3'] ? $this->caddyServiceAttribute3Max : $product['service_attribute_3'];
//  }
//
// /**
//  * caddyWiProductsProductSettings( )
//  *
//  * @param	array		$product :
//  * @return	void
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsProductSettings( $product )
//  {
////var_dump( __METHOD__, __LINE__, $this->cObj->data, array_keys( ( array ) $this->conf['settings.']['fields.'] ) );
//      // FOREACH  : settings property
//    foreach( array_keys( ( array ) $this->conf['settings.']['fields.'] ) as $key )
//    {
//      if( stristr( $key, '.' ) )
//      {
//        continue;
//      }
//
//        // name of the current field in the TypoScript
//      $ts_key   = $this->conf['settings.']['fields.'][$key];
//        // configuration array of the current field in the TypoScript
//      $ts_conf  = $this->conf['settings.']['fields.'][$key . '.'];
//      switch( $key )
//      {
//        case('delete'):
//          $ts_conf = $this->add_variant_gpvar_to_imagelinkwrap( $product, $ts_key, $ts_conf, $this );
//          break;
//        default:
//          // nothing to do, there is no default now
//      }
//      $ts_rendered_value  = $this->local_cObj->cObjGetSingle( $ts_key, $ts_conf );
//      $this->markerArray['###' . strtoupper( $key ) . '###'] = $ts_rendered_value; // write to marker
//
//        // DRS
//      if( $this->drs->drsMarker )
//      {
//        $prompt = 'Product - ###' . strtoupper( $key ) . '### : "' . $ts_rendered_value . '"';
//        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
//      }
//        // DRS
//
//      // adds the ###QTY_NAME### marker in case of variants
//      $this->markerArray = $this->add_qtyname_marker($product, $this->markerArray, $this);
//    }
//      // FOREACH  : settings property
////var_dump( __METHOD__, __LINE__, $this->markerArray );
//  }
//
// /**
//  * caddyWiProductsProductTax( )
//  *
//  * @param	array		$product  :
//  * @return	array		$tax      : cartNet, cartTaxReduced, cartTaxNormal
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsProductTax( $product )
//  {
//    $arrReturn = null;
//
//      // Handle HTML snippet
//      // get the formular with the markers ###TAX## for calculating tax
//    $str_wrap = $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'];
//      // save the formular with marker, we need it later
//    $str_wrap_former = $str_wrap;
//      // replace the ###TAX### with current tax rate like 0.07 or 0.19
//    $str_wrap = str_replace( '###TAX###', $product['tax'], $str_wrap );
//      // assign the formular with tax rates to TypoScript
//    $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap;
//      // Handle HTML snippet
//
//      // tax within price grosso
//    $currTax = $this->local_cObj->cObjGetSingle
//           (
//              $this->conf['settings.']['fields.']['tax'],
//              $this->conf['settings.']['fields.']['tax.']
//           );
//      // price netto
//    $arrReturn['cartNet'] = $product['price_total'] - $currTax;
//
//    switch( $product['tax'] )
//    {
//      case( 0 ):
//        break;
//      case( 1 ):
//      case( $this->conf['tax.']['reducedCalc'] ):
//        $arrReturn['cartTaxReduced'] = $currTax;
//        break;
//      case( 2 ):
//      case( $this->conf['tax.']['normalCalc'] ):
//        $arrReturn['cartTaxNormal'] = $currTax;
//        break;
//      default:
//        echo '<div style="border:2em solid red;padding:2em;color:red;"><h1 style="color:red;">caddy Error</h1><p>tax is "' . $product['tax'] . '".<br />This is an undefined value in class.tx_caddy_pi1.php. ABORT!<br /><br />Are you sure, that you included the caddy static template?</p></div>';
//        exit;
//    }
//    $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap_former;
//
//    return $arrReturn;
//  }
//
// /**
//  * caddyWiProductsShipping( )
//  *
//  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsShipping( )
//  {
//    $arrReturn = null;
//
//    $shippingId = $this->session->shippingGet();
//
//    if( ! $shippingId )
//    {
//      $shippingId = intval( $this->conf['shipping.']['preset'] );
//      $this->session->shippingUpdate( $shippingId );
//    }
//      // check if selected shipping option is available
//    $newshippingId = $this->zz_checkOptionIsNotAvailable( 'shipping', $shippingId );
//    if( $newshippingId )
//    {
//      $shippingId = $newshippingId;
//      $this->session->shippingUpdate( $newshippingId );
//    }
//
//    list( $gross, $net ) = $this->calc->calculateOptionById( $this->conf, 'shipping', $shippingId, $this );
//
//    if( $this->conf['shipping.']['options.'][$shippingId . '.']['tax'] == 'reduced' )
//    {
//      $arrReturn['cartTaxReduced'] = $shippingGross - $shippingNet;
//    }
//    else
//    {
//      $arrReturn['cartTaxNormal'] = $shippingGross - $shippingNet;
//    }
//
//    $arrReturn['id']    = $shippingId;
//    $arrReturn['gross'] = $gross;
//    $arrReturn['net']   = $net;
//    return $arrReturn;
//  }
//
// /**
//  * caddyWiProductsSpecial( )
//  *
//  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWiProductsSpecial( )
//  {
//    $arrReturn = null;
//
//    $specialIds = $this->session->specialGet( );
//
//    $caddyTaxReduced       = 0.0;
//    $caddyTaxNormal        = 0.0;
//    $overall_specialGross = 0.0;
//    $overall_specialNet   = 0.0;
//
//    foreach( $specialIds as $specialId )
//    {
//      list( $specialGross, $specialNet ) = $this->calc->calculateOptionById( $this->conf, 'special', $specialId, $this );
//      $caddyNet    = $caddyNet    + $specialNet;
//      $caddyGross  = $caddyGross  + $specialGross;
//      if( $this->conf['special.']['options.'][$specialId . '.']['tax'] == 'reduced' )
//      {
//        $caddyTaxReduced = $caddyTaxReduced + $specialGross - $specialNet;
//      }
//      else
//      {
//        $caddyTaxNormal = $caddyTaxNormal + $specialGross - $specialNet;
//      }
//      $overall_specialGross = $overall_specialGross + $specialGross;
//      $overall_specialNet   = $overall_specialNet   + $specialNet;
//    }
//
//    $arrReturn['ids']             = $specialIds;
//    $arrReturn['net']             = $overall_specialNet;
//    $arrReturn['gross']           = $overall_specialGross;
//    $arrReturn['cartTaxReduced']  = $caddyTaxReduced;
//    $arrReturn['cartTaxNormal']   = $caddyTaxNormal;
//
//    return $arrReturn;
//  }
//
// /**
//  * caddyWoProducts( )
//  *
//  * @return	void
//  * @access private
//  * @version    2.0.0
//  * @since      2.0.0
//  */
//  private function caddyWoProducts( )
//  {
//      // #45915, 130228
//      // Set the hidden field to true of the powermail form
//    $css = $this->powermail->FormHide( );
//
//    $this->tmpl['all'] = $this->tmpl['empty']; // overwrite normal template with empty template
//
//    return $css;
//  }



  /***********************************************
  *
  * Clean
  *
  **********************************************/

 /**
  * clean( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function clean( )
  {
    $this->clean->main( );
  }




  /***********************************************
  *
  * Debug
  *
  **********************************************/

 /**
  * debugOutputBeforeRunning( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function debugOutputBeforeRunning( )
  {
      // RETURN : Don't output debug prompt
    if( ! $this->conf['debug'] )
    {
      return;
    }
      // RETURN : Don't output debug prompt

      // output debug prompt
    t3lib_div::debug
    (
      $this->session->productsGet(), $this->extKey . ': ' . 'Values in session at the beginning'
    );
    t3lib_div::debug($this->gpvar, $this->extKey . ': ' . 'Given params');
    t3lib_div::debug($this->conf, $this->extKey . ': ' . 'Typoscript configuration');
    t3lib_div::debug($_POST, $this->extKey . ': ' . 'All POST variables');
      // output debug prompt
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
  * @version    2.0.0
  * @since      2.0.0
  */
  private function init( )
  {
    $this->initInstances( );
    $this->drs->init( );
    $this->initFlexform( );
    $this->initPid( );
    $this->initAccessByIp( );
    $this->initHtmlTemplate( );
    $this->initServiceAttributes( );
    $this->initGpVar( );
    $this->initPowermail( );
    $this->initDatabase( );
  }

/**
 * initAccessByIp( ): Set the global $bool_accessByIP.
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function initAccessByIp( )
  {
      // No access by default
    $this->accessByIP = false;

      // Get list with allowed IPs
    $csvIP      = $this->flexform->sdefCsvallowedip;
    $currentIP  = t3lib_div :: getIndpEnv( 'REMOTE_ADDR' );

      // Current IP is an element in the list
    $pos = strpos( $csvIP, $currentIP );
    if( ! ( $pos === false ) )
    {
      $this->accessByIP = true;
    }
      // Current IP is an element in the list

      // DRS
    if( ! $this->drs->drsInit )
    {
      return;
    }

    switch( $this->accessByIP )
    {
      case( true ):
        $prompt = 'Access: current IP matchs the list of allowed IP. Result will prompt to the frontend.';
        t3lib_div::devlog(' [OK/INIT] '. $prompt, $this->extKey, -1 );
        break;
      case( false ):
      default:
        $prompt = 'No access: current IP doesn\'t match the list of allowed IP. Result won\'t prompt to the frontend.';
        t3lib_div::devlog(' [WARN/INIT] '. $prompt, $this->extKey, 2 );
        break;
    }
      // DRS
  }


/**
 * initPid( ) :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function initPid( )
  {
    $pid = ( int ) $this->conf['main']['pid'];

    switch( true )
    {
      case( ! empty( $pid ) ):
        $this->pid = $pid;
          // DRS
        if( $this->drs->drsInit )
        {
          $prompt = 'pid is taken from main.pid and is set to ' . $this->pid;
          t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
        }
          // DRS
        break;
      case( empty( $pid ) ):
      default:
        $this->pid = $GLOBALS['TSFE']->id;
          // DRS
        if( $this->drs->drsInit )
        {
          $prompt = 'main.pid is empty. pid is set to the id of the current page: ' . $this->pid;
          t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
        }
          // DRS
        break;
    }

  }

 /**
  * initDatabase( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDatabase( )
  {
    $this->initDatabaseTable( );
  }

 /**
  * initDatabaseTable( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDatabaseTable( )
  {
    $table = $this->conf['db.']['table'];

    //echo $GLOBALS['TCA'][$table]['columns'] ) )
    // RETURN : TCA is loaded
    
      // Load the TCA
    t3lib_div::loadTCA( $table );

    if( ! empty ( $GLOBALS['TCA'][$table] ) ) 
    { 
        // DRS
      if( $this->drs->drsInit )
      {
        $prompt = $table . ' is an element of the $TCA.';
        t3lib_div::devlog(' [INFO/INIT] '. $prompt, $this->extKey, 0 );
      }
        // DRS
      return;
    }

    $prompt = '
      <div style="border:1em solid red;color:red;padding:1em;text-align:center">
        <h1>
          ERROR: table isn\'t proper
        </h1>
        <p>
          The name of the given table isn\'t proper "' . $table . '".
        </p>
        <p>
          Please take care of a proper configuration of the typoscript property db.table.<br />
          See: constant editor > CADDY - DATABASE > table name
        </p>
        <p>
          Caddy - the Shopping Cart
        </p>
      </div>
      ';
    die( $prompt );

  }
  
 /**
  * initFlexform( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initFlexform( )
  {
    $this->flexform->main( );
  }

 /**
  * initGpVar( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initGpVar( )
  {
    $conf = $this->conf;

      // read variables
    $this->gpvar['title'] = $this->zz_cObjGetSingle( $conf['settings.']['title'], $conf['settings.']['title.'] );
    $this->gpvar['price'] = $this->zz_cObjGetSingle( $conf['settings.']['price'], $conf['settings.']['price.'] );
    $this->gpvar['qty']   = intval( $this->zz_cObjGetSingle( $conf['settings.']['qty'], $conf['settings.']['qty.'] ) );
    $this->gpvar['tax']   = $this->zz_cObjGetSingle( $conf['settings.']['tax'], $conf['settings.']['tax.'] );
    $this->gpvar['puid']  = intval( $this->zz_cObjGetSingle( $conf['settings.']['puid'], $conf['settings.']['puid.'] ) );
    $this->gpvar['cid']   = intval ($this->zz_cObjGetSingle( $conf['settings.']['cid'], $conf['settings.']['cid.'] ) );

    $this->gpvar['sku'] = $this->zz_cObjGetSingle( $conf['settings.']['sku'], $conf['settings.']['sku.'] );
    $this->gpvar['min'] = $this->zz_cObjGetSingle( $conf['settings.']['min'], $conf['settings.']['min.'] );
    $this->gpvar['max'] = $this->zz_cObjGetSingle( $conf['settings.']['max'], $conf['settings.']['max.'] );

    $this->gpvar['service_attribute_1'] = floatval
                                          (
                                            $this->zz_cObjGetSingle
                                            (
                                              $conf['settings.']['service_attribute_1'],
                                              $conf['settings.']['service_attribute_1.']
                                            )
                                          );
    $this->gpvar['service_attribute_2'] = floatval
                                          (
                                            $this->zz_cObjGetSingle
                                            (
                                              $conf['settings.']['service_attribute_2'],
                                              $conf['settings.']['service_attribute_2.']
                                            )
                                          );
    $this->gpvar['service_attribute_3'] = floatval
                                          (
                                            $this->zz_cObjGetSingle
                                            (
                                              $conf['settings.']['service_attribute_3'],
                                              $conf['settings.']['service_attribute_3.']
                                            )
                                          );
    $this->initGpVarCid( );

    if ($this->gpvar['qty'] === 0)
    {
      $this->gpvar['qty'] = 1;
    }
  }

 /**
  * initGpVarCid( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initGpVarCid( )
  {
    if( ! $this->gpvar['cid'] )
    {
      return;
    }

    $row=$this->pi_getRecord('tt_content', $this->gpvar['cid'] );
    $flexformData = t3lib_div::xml2array($row['pi_flexform'] );

    $this->gpvar['puid']  = $this->pi_getFFvalue( $flexformData, 'puid', 'sDEF' );
    $this->gpvar['title'] = $this->pi_getFFvalue( $flexformData, 'title', 'sDEF' );
    $this->gpvar['sku']   = $this->pi_getFFvalue( $flexformData, 'sku', 'sDEF' );
    $this->gpvar['price'] = $this->pi_getFFvalue( $flexformData, 'price', 'sDEF' );
    $this->gpvar['tax']   = $this->pi_getFFvalue( $flexformData, 'tax', 'sDEF' );

    $attributes = split( "\n", $this->pi_getFFvalue( $flexformData, 'attributes', 'sDEF' ) );

    foreach ( $attributes as $line )
    {
      list($key, $value) = explode("==", $line, 2);
      switch ( $key )
      {
        case 'service_attribute_1':
          $this->gpvar['service_attribute_1'] = floatval($value);
          break;
        case 'service_attribute_2':
          $this->gpvar['service_attribute_2'] = floatval($value);
          break;
        case 'service_attribute_3':
          $this->gpvar['service_attribute_3'] = floatval($value);
          break;
        case 'min':
          $this->gpvar['min'] = intval($value);
          break;
        case 'max':
          $this->gpvar['max'] = intval($value);
          break;
      }
    }
  }

 /**
  * initHtmlTemplate( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initHtmlTemplate( )
  {
    $htmlTemplate = $this->cObj->fileResource( $this->conf['main.']['template'] );

      // Die if there isn't any HTML template
    if( empty ( $htmlTemplate ) )
    {
        // DRS
      if( $this->drs->drsError )
      {
        if( empty ( $this->conf['main.']['template'] ) )
        {
          $prompt = 'The path to the HTML template is empty!';
          t3lib_div::devlog( '[ERROR/INIT] ' . $prompt, $this->extKey, 3 );
          $prompt = 'Please check, if you have included the static template.';
          t3lib_div::devlog( '[HELP/INIT] ' . $prompt, $this->extKey, 1 );
        }
        if( ! empty ( $this->conf['main.']['template'] ) )
        {
          $prompt = 'The path to your HTML template seem\'s to be unproper!';
          t3lib_div::devlog( '[ERROR/INIT] ' . $prompt, $this->extKey, 3 );
          $prompt = 'Path is ' . $this->conf['main.']['template'];
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

    $this->tmpl['all']      = $this->cObj->getSubpart( $htmlTemplate, '###CADDY###' );
    $this->tmpl['empty']    = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_EMPTY###' );
    $this->tmpl['minprice'] = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_MINPRICE###' );
    $this->tmpl['item']     = $this->cObj->getSubpart( $this->tmpl['all'], '###ITEM###' );

    // new for Shipping radiolist and Payment radiolist and Special checkboxlist
    $this->tmpl['shipping_all']   = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_SHIPPING###' );
    $this->tmpl['shipping_item']  = $this->cObj->getSubpart( $this->tmpl['shipping_all'], '###ITEM###' );

    $this->tmpl['shipping_condition_all']   = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_SHIPPING_CONDITIONS###' );
    $this->tmpl['shipping_condition_item']  = $this->cObj->getSubpart( $this->tmpl['shipping_condition_all'], '###ITEM###' );

    $this->tmpl['payment_all']  = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_PAYMENT###' );
    $this->tmpl['payment_item'] = $this->cObj->getSubpart( $this->tmpl['payment_all'], '###ITEM###' );

    $this->tmpl['payment_condition_all']  = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_PAYMENT_CONDITIONS###' );
    $this->tmpl['payment_condition_item'] = $this->cObj->getSubpart( $this->tmpl['payment_condition_all'], '###ITEM###' );

    $this->tmpl['special_all']  = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_SPECIAL###' );
    $this->tmpl['special_item'] = $this->cObj->getSubpart( $this->tmpl['special_all'], '###ITEM###' );

    $this->tmpl['special_condition_all']  = $this->cObj->getSubpart( $htmlTemplate, '###CADDY_SPECIAL_CONDITIONS###' );
    $this->tmpl['special_condition_item'] = $this->cObj->getSubpart( $this->tmpl['special_condition_all'], '###ITEM###' );
  }

 /**
  * initInstances( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initInstances( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'class.tx_caddy_calc.php' );
    $this->calc             = t3lib_div::makeInstance( 'tx_caddy_calc' );

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy            = t3lib_div::makeInstance( 'tx_caddy' );
    $this->caddy->pObj      = $this;
    $this->caddy->row       = $this->cObj->data;

      // Class with methods for get clean values
    require_once( 'class.tx_caddy_pi1_clean.php' );
    $this->clean            = t3lib_div::makeInstance( 'tx_caddy_pi1_clean' );
    $this->clean->pObj      = $this;
    $this->clean->row       = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers   = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;

      // Class with methods for get flexform values
    require_once( 'class.tx_caddy_pi1_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_caddy_pi1_flexform' );
    $this->flexform->pObj   = $this;
    $this->flexform->row    = $this->cObj->data;

    require_once( $path2lib . 'powermail/class.tx_caddy_powermail.php' );
    $this->powermail        = t3lib_div::makeInstance( 'tx_caddy_powermail' );
    $this->powermail->pObj  = $this;

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session          = t3lib_div::makeInstance( 'tx_caddy_session' );
    $this->session->pObj    = $this;

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc         = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
  }

 /**
  * initPowermail( )
  *
  * @return	void
  * @access private
  * @internal   #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initPowermail( )
  {
    $this->powermail->init( $this->cObj->data );
  }

 /**
  * initServiceAttributes( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initServiceAttributes( )
  {
    $this->caddyServiceAttribute1Sum = 0;
    $this->caddyServiceAttribute1Max = 0;
    $this->caddyServiceAttribute2Sum = 0;
    $this->caddyServiceAttribute2Max = 0;
    $this->caddyServiceAttribute3Sum = 0;
    $this->caddyServiceAttribute3Max = 0;
  }




  /***********************************************
  *
  * Order
  *
  **********************************************/

 /**
  * orderUpdate( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function orderUpdate( )
  {
      // change qty
    if( isset( $this->piVars['qty'] ) && is_array( $this->piVars['qty'] ) )
    {
      $this->session->quantityUpdate( ); // change qty
    }

      // change shipping
    if( isset( $this->piVars['shipping'] ) )
    {
      $this->session->shippingUpdate($this->piVars['shipping']); // change shipping
    }

    // change payment
    if( isset( $this->piVars['payment'] ) )
    {
      $this->session->paymentUpdate($this->piVars['payment']); // change payment
    }

      // change special
    if( isset( $this->piVars['special'] ) )
    {
      $this->session->specialUpdate($this->piVars['special']); // change payment
    }
  }





  /***********************************************
  *
  * Product
  *
  **********************************************/

 /**
  * productAdd( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function productAdd( )
  {
    // add further product to session
    $this->newProduct = $this->session->productGetDetails( $this->gpvar );
    if( $this->newProduct !== false )
    {
      $this->newProduct['qty']                  = $this->gpvar['qty'];
      $this->newProduct['service_attribute_1']  = $this->gpvar['service_attribute_1'];
      $this->newProduct['service_attribute_2']  = $this->gpvar['service_attribute_2'];
      $this->newProduct['service_attribute_3']  = $this->gpvar['service_attribute_3'];
      $this->session->productAdd( $this->newProduct );
    }
  }

 /**
  * productRemove( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function productRemove( )
  {
      // remove product from session
    if( isset( $this->piVars['del'] ) )
    {
      $this->session->productDelete( );
    }
  }



  /***********************************************
  *
  * Send
  *
  **********************************************/

 /**
  * send( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function send( )
  {
    $this->sendCustomer( );
    $this->sendVendor( );
  }

 /**
  * sendCustomer( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomer( )
  {
    $this->sendCustomerDeliveryorder( );
    $this->sendCustomerInvoice( );
    $this->sendCustomerTerms( );
  }

 /**
  * sendCustomerDeliveryorder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerDeliveryorder( )
  {
    switch( $this->flexform->emailDeliveryorderMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
        $sesArray['sendCustomerDeliveryorder'] = $this->flexform->emailDeliveryorderPath;
        $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
        $GLOBALS['TSFE']->storeSessionData( );
        break;
      default:
          // Nothing to do
        break;
    }
  }

 /**
  * sendCustomerInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerInvoice( )
  {
    switch( $this->flexform->emailInvoiceMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
        $sesArray['sendCustomerInvoice'] = $this->flexform->emailInvoicePath;
        $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
        $GLOBALS['TSFE']->storeSessionData( );
        break;
      default:
          // Nothing to do
        break;
    }
  }

 /**
  * sendCustomerTerms( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendCustomerTerms( )
  {
    switch( $this->flexform->emailTermsMode )
    {
      case( 'customer' ):
      case( 'all' ):
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
        $sesArray['sendCustomerTerms'] = $this->flexform->emailTermsPath;
        $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
        $GLOBALS['TSFE']->storeSessionData( );
        break;
      default:
          // Nothing to do
        break;
    }
  }

 /**
  * sendVendor( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendor( )
  {
    $this->sendVendorDeliveryorder( );
    $this->sendVendorInvoice( );
    $this->sendVendorTerms( );
  }

 /**
  * sendVendorDeliveryorder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorDeliveryorder( )
  {
    switch( $this->flexform->emailDeliveryorderMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
        $sesArray['sendVendorDeliveryorder'] = $this->flexform->emailDeliveryorderPath;
        $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
        $GLOBALS['TSFE']->storeSessionData( );
        break;
      default:
          // Nothing to do
        break;
    }
  }

 /**
  * sendVendorInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorInvoice( )
  {
    switch( $this->flexform->emailInvoiceMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
        $sesArray['sendVendorInvoice'] = $this->flexform->emailInvoicePath;
        $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
        $GLOBALS['TSFE']->storeSessionData( );
        break;
      default:
          // Nothing to do
        break;
    }
  }

 /**
  * sendVendorTerms( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sendVendorTerms( )
  {
    switch( $this->flexform->emailTermsMode )
    {
      case( 'vendor' ):
      case( 'all' ):
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
        $sesArray['sendVendorTerms'] = $this->flexform->emailTermsPath;
        $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, $sesArray );
        $GLOBALS['TSFE']->storeSessionData( );
        break;
      default:
          // Nothing to do
        break;
    }
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
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function sessionDelete( )
  {
return;
    // DRS
    if( $this->drs->drsSession )
    {
      $prompt = 'Session is cleared.';
      t3lib_div::devlog( '[INFO/SESSION] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id, array( ) );
    $GLOBALS['TSFE']->storeSessionData( );
  }




  /***********************************************
  *
  * Update Wizard
  *
  **********************************************/

 /**
  * updateWizard( )
  *
  * @param	string		$content  : current content
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function updateWizard( $content )
  {
      // RETURN : update wizard is disabled
    if( ! $this->flexform->sdefUpdatewizard )
    {
      return $content;
    }
      // RETURN : update wizard is disabled

      // RETURN : current IP isn't part of list with allowed IP
    if( ! $this->accessByIP )
    {
      return $content;
    }
      // RETURN : current IP isn't part of list with allowed IP

    require_once( PATH_typo3conf . 'ext/' . $this->extKey . '/lib/updatewizard/class.tx_caddy_pi1_updatewizard.php' );

      // Class with methods for Update Checking
    $updatewizard = new tx_caddy_pi1_updatewizard( $this );
    $content = $updatewizard->main( $content );
      // Current IP has access

    return $content;
  }




  /***********************************************
  *
  * ZZ
  *
  **********************************************/

  /**
 * Gets the price for a given type ('shipping', 'payment') method on the current cart
 *
 * @param	string		$type
 * @param	int		$option_id
 * @return	string
 */
  private function zz_getPriceForOption($type, $option_id) {
          $optionIds = $this->conf[$type.'.']['options.'][$option_id.'.'];

          $free_from = $optionIds['free_from'];
          $free_until = $optionIds['free_until'];

          if ((isset($free_from) && (floatval($free_from) <= $this->caddyGrossNoService)) ||
                  (isset($free_until) && (floatval($free_until) >= $this->caddyGrossNoService))) {
                  return '0.00';
          }

          $filterArr = array(
                  'by_price' => $this->caddyGrossNoService,
                  'by_quantity' => $this->caddyCount,
                  'by_service_attribute_1_sum' => $this->caddyServiceAttribute1Sum,
                  'by_service_attribute_1_max' => $this->caddyServiceAttribute1Max,
                  'by_service_attribute_2_sum' => $this->caddyServiceAttribute2Sum,
                  'by_service_attribute_2_max' => $this->caddyServiceAttribute2Max,
                  'by_service_attribute_3_sum' => $this->caddyServiceAttribute3Sum,
                  'by_service_attribute_3_max' => $this->caddyServiceAttribute3Max
          );

          if (array_key_exists($optionIds['extra'], $filterArr)) {
                  foreach ($optionIds['extra.'] as $extra) {
                          if (floatval($extra['value']) <= $filterArr[$optionIds['extra']]) {
                                  $price = $extra['extra'];
                          } else {
                                  break;
                          }
                  }
          } else {
                  switch ($optionIds['extra']) {
                          case 'each':
                                  $price = floatval($optionIds['extra.']['1.']['extra'])*$this->caddyCount;
                                  break;
                          default:
                                  $price = $optionIds['extra'];
                  }
          }

          return $price;
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
  * zz_cObjGetSingle( )
  *
  * @param	[type]		$$cObj_name: ...
  * @param	[type]		$cObj_conf: ...
  * @return	string
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function zz_cObjGetSingle( $cObj_name, $cObj_conf )
  {
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
  }

  /**
 * [Describe function...]
 *
 * @param	[type]		$type: ...
 * @param	[type]		$option_id: ...
 * @return	[type]		...
 */
  private function zz_renderOptionList($type, $option_id) {
          $radio_list = '';
          foreach ((array) $this->conf[$type.'.']['options.'] as $key => $value)
          {
                  // hide option if not available by cartGrossNoService
                  $show = true;
                  if ((isset($value['available_from']) && (round(floatval($value['available_from']),2) > round($this->caddyGrossNoService,2))) || (isset($value['available_until']) && (round(floatval($value['available_until']),2) < round($this->caddyGrossNoService,2))))
                  {
                          $show = false;
                  }

                  if ($show || $this->conf[$type.'.']['show_all_disabled'])
                  {
                          $disabled = $show ? '' : 'disabled="disabled"';

                          $condition_list = array();

                          if (isset($value['free_from']))
                          {
                                  $pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_free_from').' '.$this->zz_price_format($value['free_from']);
                                  $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
                          }
                          if (isset($value['free_until']))
                          {
                                  $pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_free_until').' '.$this->zz_price_format($value['free_until']);
                                  $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
                          }

                          if (!$show)
                          {
                                  if (isset($value['available_from']))
                                  {
                                          $pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_available_from').' '.$this->zz_price_format($value['available_from']);
                                          $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
                                  }
                                  if (isset($value['available_until']))
                                  {
                                          $pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_available_until').' '.$this->zz_price_format($value['available_until']);
                                          $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
                                  }
                          }

          switch ($value['extra']) {
                                  case 'by_price':
                                          $unit = $this->conf['main.']['currencySymbol'];
                                          break;
                                  case 'by_quantity':
                  $unit = $this->conf['main.']['quantitySymbol'];
                                          break;
                                  case 'by_service_attribute_1_sum':
                                  case 'by_service_attribute_1_max':
                                          $unit = $this->conf['main.']['service_attribute_1_symbol'];
                                          break;
                                  case 'by_service_attribute_2_sum':
                                  case 'by_service_attribute_2_max':
                                          $unit = $this->conf['main.']['service_attribute_2_symbol'];
                                          break;
                                  case 'by_service_attribute_3_sum':
                                  case 'by_service_attribute_3_max':
                                          $unit = $this->conf['main.']['service_attribute_3_symbol'];
                                          break;
                                  default:
                                          $unit = '';
                          }

                          if ($value['extra'] != 'each') {
                                  foreach ($value['extra.'] as $extra)
                                  {
                                          $pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_service_from') . ' ' . $extra['value'] . ' ' . $unit . ' : ' .$this->zz_price_format($extra['extra']);
                                          $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
                                  }

                                  $show_price = $this->zz_price_format(floatval($this->zz_getPriceForOption($type, intval($key))));
                          } else {
                                  $show_price = sprintf($this->pi_getLL('caddy_ll_special_each'), $this->zz_price_format($value['extra.']['1.']['extra']));
                          }

                          $upperType = strtoupper($type);

                          if ($type != 'special') {
                                  $checkradio = intval($key) == $option_id ? 'checked="checked"' : '';
                                  $this->smarkerArray['###'.$upperType.'_RADIO###'] = '<input type="radio" onchange="this.form.submit()" name="tx_caddy_pi1['.$type.']" id="tx_caddy_pi1_'.$type.'_' . intval($key) . '"  value="' . intval($key) . '"  ' . $checkradio . $disabled . '/>'; // write to marker
                          } else {
                                  $checkbox = in_array( intval($key) , $option_id ) ? 'checked="checked"' : '';
                                  $this->smarkerArray['###'.$upperType.'_CHECKBOX###'] = '<input type="checkbox" onchange="this.form.submit()" name="tx_caddy_pi1['.$type.'][]" id="tx_caddy_pi1_'.$type.'_' . intval($key) . '"  value="' . intval($key) . '"  ' . $checkbox . $disabled . '/>'; // write to marker
                          }

                          // TODO: In braces the actual Price for Payment should be displayed, not the first one.

                          $this->smarkerArray['###'.$upperType.'_TITLE###'] = '<label for="tx_caddy_pi1_'.$type.'_' . intval($key) . '">' . $value['title'] . ' (' . $show_price . ')</label>'; // write to marker

                          if (isset($condition_list['###CONTENT###']))
                          {
                                  $this->smarkerArray['###'.$upperType.'_CONDITION###'] = $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_all'], null, $condition_list);
                          } else {
                                  $this->smarkerArray['###'.$upperType.'_CONDITION###'] = '';
                          }
                          $radio_list .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_item'], $this->smarkerArray);
                  }
          }

          return $radio_list;
  }

  /**
 * [Describe function...]
 *
 * @param	[type]		$value: ...
 * @return	[type]		...
 */
  private function zz_price_format($value) {
          $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']; // get ts

          $currencySymbol = $this->conf['main.']['currencySymbol'];
          $price = number_format($value, $this->conf['main.']['decimal'], $this->conf['main.']['dec_point'], $this->conf['main.']['thousands_sep']);
          // print currency symbol before or after price
          if ($this->conf['main.']['currencySymbolBeforePrice']) {
                  $price = $currencySymbol . ' ' . $price;
          } else {
                  $price = $price . ' ' . $currencySymbol;
          }

          return $price;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1.php']);
}
?>
