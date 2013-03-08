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
 *   80: class tx_caddy extends tslib_pibase
 *
 *              SECTION: Caddy
 *  145:     public function caddy( )
 *  191:     public function caddyByUserfunc( $content = '', $conf = array( ) )
 *  223:     private function caddyWiProducts( )
 *  401:     private function caddyWiProductsItem( $contentItem )
 *  432:     private function calcProduct( )
 *  507:     private function caddyWiProductsProductErrorMsg( $product )
 *  531:     private function caddyWiProductsProductServiceAttributes( $product )
 *  593:     private function caddyWiProductsProductSettings( $product )
 *  643:     private function calcProductTax( $product )
 *  696:     private function caddyWoProducts( )
 *
 *              SECTION: Caddy
 *  723:     private function calcOptionsPayment( )
 *  767:     private function calcOptionsShipping( )
 *  811:     private function calcOptionsSpecial( )
 *
 *              SECTION: Init
 *  864:     private function initInstances( )
 *
 *              SECTION: ZZ
 *  912:     private function zz_addQtynameMarker($product, $markerArray, $pObj)
 *  950:     private function zz_addVariantGpvarToImagelinkwrap($product, $ts_key, $ts_conf, $pObj)
 *  980:     private function zz_checkOptionIsNotAvailable($type, $option_id)
 * 1014:     private function zz_getPriceForOption($type, $option_id)
 * 1064:     private function zz_price_format($value)
 * 1086:     private function zz_renderOptionList($type, $option_id)
 *
 * TOTAL FUNCTIONS: 20
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
 * @since       2.0.0
 */
class tx_caddy extends tslib_pibase
{

  public $extKey = 'caddy';
  public $prefixId = 'tx_caddy_pi1';
  public $scriptRelPath = 'lib/caddy/class.tx_caddy.php';


  private $caddyCount                 = 0;
  private $productsGross              = 0;
  private $caddyServiceAttribute1Max  = 0;
  private $caddyServiceAttribute1Sum  = 0;
  private $caddyServiceAttribute2Max  = 0;
  private $caddyServiceAttribute2Sum  = 0;
  private $caddyServiceAttribute3Max  = 0;
  private $caddyServiceAttribute3Sum  = 0;

    // [array] current typoscript configuration
  public $conf = null;

  private $markerArray = array( );

  // [object] parent DRS object
  private $calc     = null;
  public  $drs      = null;
  private $userfunc = null;

  // [object] parent object
  private $pObj       = null;
  public  $cObj       = null;
  private $local_cObj = null;

  // [object] parent powermail object
  private $powermail = null;

  // [object] parent session object
  private $session = null;

  private $products = array( );


  private $outerMarkerArray = array( );

    // [array] current tt_content row or current pi_flexform row
  private $row  = null;

  private $tmpl         = null;
  private $smarkerArray = null;



  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * caddy( )  : Returns a caddy with HTML form and HTML options among others
  *
  * @return	array		$arrReturn  : array with elements caddy, tmpl, outerMarkerArray
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function caddy( )
  {
    $arrReturn = null;

      // DIE  : if pObj or row isn't initiated
    $this->initDie( );
    
    $this->initInstances( );

      // get products from session
    $this->products = $this->session->productsGet( );
    switch( true )
    {
      case( count( $this->products ) > 0 ):
        $caddy = $this->caddyWiProducts( );
        break;
      case( ! ( count( $this->products ) > 0 ) ):
      default:
        $this->caddyWoProducts( );
        $caddy = null;
        break;
    }

    $arrReturn['caddy']             = $caddy;
    $arrReturn['tmpl']              = $this->tmpl;
    $arrReturn['outerMarkerArray']  = $this->outerMarkerArray;
    return $arrReturn;
  }

 /**
  * caddyWiProducts( )  : Workflow for a caddy, whoch contains products
  *
  * @return	array   : $subpartArray
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

    $arrResult = $this->calc( );
    $contentItem    = $arrResult['contentItem']; // <- !!!!!!!!!!!!!!!!!!!!!
    $productsGross  = $arrResult['productsGross'];
    $productsNet    = $arrResult['productsNet'];
    $optionsNet     = $arrResult['optionsNet'];
    $optionsGross   = $arrResult['optionsGross'];
    $paymentId      = $arrResult['paymentId'];
    $shippingId     = $arrResult['shippingId'];
    $specialIds     = $arrResult['specialIds'];
    $sumGross       = $arrResult['sumGross'];
    $sumNet         = $arrResult['sumNet'];
    $sumTaxNormal   = $arrResult['sumTaxNormal'];
    $sumTaxReduced  = $arrResult['sumTaxReduced'];

    unset( $arrResult );
    unset( $productsGross );
    $subpartArray['###CONTENT###'] = $this->caddyWiProductsItem( $contentItem );

    
//      // handle the current product
//    $arrResult      = $this->calcProduct( );
//    $contentItem    = $arrResult['contentItem'];
//    $sumNet         = $arrResult['net'];
//    $sumGross       = $arrResult['gross'];
//    $sumTaxReduced  = $arrResult['taxReduced'];
//    $sumTaxNormal   = $arrResult['taxNormal'];
//    unset( $arrResult );
//      // handle the current product
//
//    $subpartArray['###CONTENT###'] = $this->caddyWiProductsItem( $contentItem );
//
//    $this->productsGross = $sumGross;
//    $productsNet        = $sumNet;
//
//      // option shipping : calculate tax, net and gross
//    $arrResult      = $this->calcOptionsShipping( );
//    $shippingId     = $arrResult['id'];
//    $shippingNet    = $arrResult['net'];
//    $shippingGross  = $arrResult['gross'];
//    $sumNet         = $sumNet        + $shippingNet;
//    $sumGross       = $sumGross      + $shippingGross;
//    $sumTaxReduced  = $sumTaxReduced + $arrResult['taxReduced'];
//    $sumTaxNormal   = $sumTaxNormal  + $arrResult['taxNormal'];
//    unset( $arrResult );
//      // option shipping : calculate tax, net and gross
//
//      // option payment : calculate tax, net and gross
//    $arrResult      = $this->calcOptionsPayment( );
//    $paymentId      = $arrResult['id'];
//    $paymentNet     = $arrResult['net'];
//    $paymentGross   = $arrResult['gross'];
//    $sumNet         = $sumNet        + $paymentNet;
//    $sumGross       = $sumGross      + $paymentGross;
//    $sumTaxReduced  = $sumTaxReduced + $arrResult['taxReduced'];
//    $sumTaxNormal   = $sumTaxNormal  + $arrResult['taxNormal'];
//    unset( $arrResult );
//      // option payment : calculate tax, net and gross
//
//      // option special : calculate tax, net and gross
//    $arrResult      = $this->calcOptionsSpecial( );
//    $specialIds     = $arrResult['ids'];
//    $specialNet     = $arrResult['net'];
//    $specialGross   = $arrResult['gross'];
//    $sumNet         = $sumNet        + $specialNet;
//    $sumGross       = $sumGross      + $specialGross;
//    $sumTaxReduced  = $sumTaxReduced + $arrResult['taxReduced'];
//    $sumTaxNormal   = $sumTaxNormal  + $arrResult['taxNormal'];
//    unset( $arrResult );
//      // option special : calculate tax, net and gross
//
//      // sum of options
//    $optionsNet   = $shippingNet    + $paymentNet   + $specialNet;
//    $optionsGross = $shippingGross  + $paymentGross + $specialGross;
//      // sum of options

########################################################
    
      // session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $sesArray['payment_option']   = $payment_option;
    $sesArray['productsGross']    = $this->productsGross;
    $sesArray['productsNet']      = $productsNet;
    $sesArray['optionsNet']       = $optionsNet;
    $sesArray['optionsGross']     = $optionsGross;
    $sesArray['shipping_option']  = $shipping_option;
    $sesArray['special_options']  = $special_options;
    $sesArray['sumGross']         = $sumGross;
    $sesArray['sumNet']           = $sumNet;
    $sesArray['sumTaxNormal']     = $sumTaxNormal;
    $sesArray['sumTaxReduced']    = $sumTaxReduced;
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
      // session

      // cObject becomes current record
    $currRecord = array
                  (
                    'payment_option'  => $payment_option,
                    'productsGross'   => $this->productsGross,
                    'productsNet'     => $productsNet,
                    'optionsNet'      => $optionsNet,
                    'optionsGross'    => $optionsGross,
                    'shipping_option' => $shipping_option,
                    'special_option'  => $special_options,
                    'sumGross'        => $sumGross,
                    'sumNet'          => $sumNet,
                    'sumTaxNormal'    => $sumTaxNormal,
                    'sumTaxReduced'   => $sumTaxReduced
                  );
    $this->local_cObj->start( $currRecord, $this->conf['db.']['table'] );
      // cObject becomes current record
    
      // DRS
    if( $this->drs->drsCobj )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // FOREACH  : setting (sumNet, sumGross, price_total, service_costs, odernumber, target, taxrates, tax)
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
//var_dump( __METHOD__, __LINE__, $this->outerMarkerArray );    
      // FOREACH  : setting (sumNet, sumGross, price_total, service_costs, odernumber, target, taxrates, tax)

      // Set min price error
    if( $sesArray['productsGross'] < floatval( $this->conf['cart.']['cartmin.']['value'] ) )
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
      ! ( $sesArray['productsGross'] < floatval( $this->conf['cart.']['cartmin.']['value'] ) )
      ||
      (
        ( $sesArray['productsGross'] < floatval($this->conf['cart.']['cartmin.']['value'] ) )
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
  * caddyWiProductsItem( )  : Render the item (product)
  *
  * @param	string		$contentItem : current item
  * @return	string		$contentItem : rendered item
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
      $name   = $this->conf['payment.']['options.'][$paymentId . '.']['title'];
      $conf   = $this->conf['payment.']['options.'][$paymentId . '.']['title.'];
      $title  = $this->zz_cObjGetSingle( $name, $conf );
      $this->markerArray['###TITLE###']       = $title;
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
  * caddyWiProductsProductErrorMsg( ) : 
  *
  * @param	array		$product : the current item / product
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

    $this->caddyServiceAttribute1Sum  = $this->caddyServiceAttribute1Sum 
                                      + (
                                            $product['service_attribute_1'] 
                                          * $product['qty']
                                        )
                                      ;
    if( $this->caddyServiceAttribute1Max > $product['service_attribute_1'] )
    {
      $this->caddyServiceAttribute1Max = $this->caddyServiceAttribute1Max;
    }
    else
    {
      $this->caddyServiceAttribute1Max = $product['service_attribute_1'];
    }

    $this->caddyServiceAttribute2Sum  = $this->caddyServiceAttribute2Sum 
                                      + (
                                            $product['service_attribute_2'] 
                                          * $product['qty']
                                        )
                                      ;
    if( $this->caddyServiceAttribute2Max > $product['service_attribute_2'] )
    {
      $this->caddyServiceAttribute2Max = $this->caddyServiceAttribute2Max;
    }
    else
    {
      $this->caddyServiceAttribute2Max = $product['service_attribute_2'];
    }

    $this->caddyServiceAttribute3Sum  = $this->caddyServiceAttribute3Sum 
                                      + (
                                            $product['service_attribute_3'] 
                                          * $product['qty']
                                        )
                                      ;
    if( $this->caddyServiceAttribute3Max > $product['service_attribute_3'] )
    {
      $this->caddyServiceAttribute3Max = $this->caddyServiceAttribute3Max;
    }
    else
    {
      $this->caddyServiceAttribute3Max = $product['service_attribute_3'];
    }
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
          $ts_conf = $this->zz_addVariantGpvarToImagelinkwrap( $product, $ts_key, $ts_conf, $this );
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
      $this->markerArray = $this->zz_addQtynameMarker($product, $this->markerArray, $this);
    }
      // FOREACH  : settings property
  }

 /**
  * caddyWoProducts( )  : Render a caddy, which doesn't contain any product
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
    $this->powermail->formHide( );

      // overwrite default template with empty template
    $this->tmpl['all'] = $this->tmpl['empty'];

  }



  /***********************************************
  *
  * Calc
  *
  **********************************************/

 /**
  * calc( )  : 
  *
  * @return	array   : 
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function calc( )
  {
    $this->initDie( );
    
    $this->initInstances( );

      // handle the current product
    $arrResult      = $this->calcProduct( );
    $contentItem    = $arrResult['contentItem'];
    $sumNet         = $arrResult['net'];
    $sumGross       = $arrResult['gross'];
    $sumTaxReduced  = $arrResult['taxReduced'];
    $sumTaxNormal   = $arrResult['taxNormal'];
    unset( $arrResult );
      // handle the current product

    $this->productsGross = $sumGross;
    $productsNet        = $sumNet;

      // option shipping : calculate tax, net and gross
    $arrResult        = $this->calcOptionsShipping( );
    $shippingId       = $arrResult['id'];
    $shippingNet      = $arrResult['net'];
    $shippingGross    = $arrResult['gross'];
    $shipping_option  = $arrResult['option'];
    $sumNet           = $sumNet        + $shippingNet;
    $sumGross         = $sumGross      + $shippingGross;
    $sumTaxReduced    = $sumTaxReduced + $arrResult['taxReduced'];
    $sumTaxNormal     = $sumTaxNormal  + $arrResult['taxNormal'];
    unset( $arrResult );
      // option shipping : calculate tax, net and gross

      // option payment : calculate tax, net and gross
    $arrResult      = $this->calcOptionsPayment( );
    $paymentId      = $arrResult['id'];
    $paymentNet     = $arrResult['net'];
    $paymentGross   = $arrResult['gross'];
    $payment_option = $arrResult['option'];
    $sumNet         = $sumNet        + $paymentNet;
    $sumGross       = $sumGross      + $paymentGross;
    $sumTaxReduced  = $sumTaxReduced + $arrResult['taxReduced'];
    $sumTaxNormal   = $sumTaxNormal  + $arrResult['taxNormal'];
    unset( $arrResult );
      // option payment : calculate tax, net and gross

      // option special : calculate tax, net and gross
    $arrResult        = $this->calcOptionsSpecial( );
    $specialIds       = $arrResult['ids'];
    $specialNet       = $arrResult['net'];
    $specialGross     = $arrResult['gross'];
    $special_options  = $arrResult['options'];
    $sumNet           = $sumNet        + $specialNet;
    $sumGross         = $sumGross      + $specialGross;
    $sumTaxReduced    = $sumTaxReduced + $arrResult['taxReduced'];
    $sumTaxNormal     = $sumTaxNormal  + $arrResult['taxNormal'];
    unset( $arrResult );
      // option special : calculate tax, net and gross

      // sum of options
    $optionsNet   = $shippingNet    + $paymentNet   + $specialNet;
    $optionsGross = $shippingGross  + $paymentGross + $specialGross;
      // sum of options

    $serviceattributes = $this->getServiceAttributes( );

    $arrReturn =  array
                  ( 
                    'contentItem'       => $contentItem,
                    'productsGross'     => $this->productsGross,
                    'productsNet'       => $productsNet,
                    'optionsNet'        => $optionsNet,
                    'optionsGross'      => $optionsGross,
                    'paymentId'         => $paymentId,
                    'payment_option'    => $payment_option,
                    'serviceattributes' => $serviceattributes,
                    'shippingId'        => $shippingId,
                    'shipping_options'  => $shipping_option,
                    'specialIds'        => $specialIds,
                    'special_options'   => $special_options,
                    'sumGross'          => $sumGross,
                    'sumNet'            => $sumNet,
                    'sumTaxNormal'      => $sumTaxNormal,
                    'sumTaxReduced'     => $sumTaxReduced
                  );

    return $arrReturn;
  }
	
 /**
  * calcOptionById( )
  *
  * @return   array   $array : gross, net
  * @access     private
  * @version    2.0.0
  * @since      1.4.6
  */
  private function calcOptionById( $conf, $type, $option_id, &$obj ) 
  {
    $arrReturn = null; 
    
    $optionIds = $conf[$type . '.']['options.'][$option_id . '.'];

    $filterArr = array(
      'by_price'                    => $obj->cartGrossNoService,
      'by_quantity'                 => $obj->cartCount,
      'by_service_attribute_1_sum'  => $obj->cartServiceAttribute1Sum,
      'by_service_attribute_1_max'  => $obj->cartServiceAttribute1Max,
      'by_service_attribute_2_sum'  => $obj->cartServiceAttribute2Sum,
      'by_service_attribute_2_max'  => $obj->cartServiceAttribute2Max,
      'by_service_attribute_3_sum'  => $obj->cartServiceAttribute3Sum,
      'by_service_attribute_3_max'  => $obj->cartServiceAttribute3Max
    );

    if( array_key_exists( $optionIds['extra'], $filterArr ) ) 
    {
      foreach( $optionIds['extra.'] as $extra ) 
      {
        if( floatval( $extra['value'] ) <= $filterArr[$optionIds['extra']] ) 
        {
          $arrReturn = $this->calcOption($conf, $type, $option_id, floatval($extra['extra']), $obj);
        }
        else
        {
          break;
        }
      }
      return $arrReturn;
    } 

    switch( $optionIds['extra'] )
    {
      case 'each':
        $gross  = floatval($optionIds['extra.']['1.']['extra'])*$obj->cartCount;
        $arrReturn =  $this->calcOption
                      ( 
                        $conf, $type, $option_id, $gross, $obj 
                      );
        break;
      default:
        $arrReturn =  $this->calcOption
                      ( 
                        $conf, $type, $option_id, floatval( $optionIds['extra'] ), $obj 
                      );
    }

    return $arrReturn;
  }
	
 /**
  * calcOption( )
  *
  * @return   array   $array : gross, net
  * @access   private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcOption( $conf, $type, $option_id, $gross, $obj ) 
  {
    $arrReturn = null; 

    $free_from  = $conf[$type.'.']['options.'][$option_id . '.']['free_from'];
    $free_to    = $conf[$type.'.']['options.'][$option_id . '.']['free_until'];
    
    switch( true )
    {
      case( isset( $free_from ) && ( floatval( $free_from ) <= $obj->cartGrossNoService ) ):
      case( isset( $free_to ) && ( floatval( $free_to ) >= $obj->cartGrossNoService ) ):
        $arrReturn['gross'] = 0.00;
        $arrReturn['net']   = 0.00;
        return $arrReturn;
        break;
      default;
        break;
    }
    
    unset( $free_from );
    unset( $free_to );
    
      // calc net
    if( $conf[$type.'.']['options.'][$option_id . '.']['tax'] == 'reduced' )
    {
      $net = $gross / ( 1.0 + $conf['tax.']['reducedCalc'] );
    } 
    else 
    {
      $net = $gross / ( 1.0 + $conf['tax.']['normalCalc'] );
    }
      // calc net

    $arrReturn['gross'] = $gross;
    $arrReturn['net']   = $net;
    return $arrReturn;
  }

 /**
  * calcOptionsPayment( ) : calculate tax, net and gross for the option payment
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcOptionsPayment( )
  {
    $arrReturn = null;

    $paymentId = $this->session->paymentGet();

    $gross        = 0.0;
    $net          = 0.0;
    $taxReduced   = 0.0;
    $taxNormal    = 0.0;

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

    $arrResult  = $this->calcOptionById( $this->conf, 'payment', $paymentId, $this );
    $net        = $arrResult['net'];  
    $gross      = $arrResult['gross'];  

    if( $this->conf['payment.']['options.'][$paymentId . '.']['tax'] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

//    $payment_option = $this->render->renderOptionById( $this->conf, 'payment', $payment_id, $this );

    $arrReturn['id']          = $paymentId;
    $arrReturn['gross']       = $gross;
    $arrReturn['option']      = $payment_option;
    $arrReturn['net']         = $net;
    $arrReturn['taxReduced']  = $taxReduced;
    $arrReturn['taxNormal']   = $taxNormal;
    return $arrReturn;
  }

 /**
  * calcOptionsShipping( ) : calculate tax, net and gross for the option shipping
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcOptionsShipping( )
  {
    $arrReturn = null;

    $shippingId = $this->session->shippingGet();

    $gross        = 0.0;
    $net          = 0.0;
    $taxReduced   = 0.0;
    $taxNormal    = 0.0;

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

    $arrResult = $this->calcOptionById( $this->conf, 'shipping', $shippingId, $this );
    $net      = $arrResult['net'];  
    $gross    = $arrResult['gross'];  

    if( $this->conf['shipping.']['options.'][$shippingId . '.']['tax'] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

//    $option = $this->render->renderOptionById( $this->conf, 'shipping', $shippingId, $this );

    $arrReturn['id']          = $shippingId;
    $arrReturn['net']         = $net;
    $arrReturn['gross']       = $gross;
    $arrReturn['option']      = $option;
    $arrReturn['taxReduced']  = $taxReduced;
    $arrReturn['taxNormal']   = $taxNormal;
    return $arrReturn;
  }

 /**
  * calcOptionsSpecial( ) : calculate tax, net and gross for the option special
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcOptionsSpecial( )
  {
    $arrReturn = null;

    $specialIds = $this->session->specialGet( );

    $gross        = 0.0;
    $net          = 0.0;
    $sumGross     = 0.0;
    $sumNet       = 0.0;
    $taxReduced   = 0.0;
    $taxNormal    = 0.0;
    $special_options = null;
    
    foreach( ( array ) $specialIds as $specialId )
    {
      $arrResult = $this->calcOptionById( $this->conf, 'special', $specialId, $this );
      $net      = $arrResult['net'];  
      $gross    = $arrResult['gross'];  
      $sumNet   = $sumNet    + $net;
      $sumGross = $sumGross  + $arrResult['gross'];
      if( $this->conf['special.']['options.'][$specialId . '.']['tax'] == 'reduced' )
      {
        $taxReduced = $taxReduced + $gross - $net;
      }
      else
      {
        $taxNormal = $taxNormal + $gross - $net;
      }
//      $special_options  = $special_options
//                        . $this->render->renderOptionById( $this->conf, 'special', $special_id, $this );
    }

    $arrReturn['ids']         = $specialIds;
    $arrReturn['net']         = $sumNet;
    $arrReturn['gross']       = $sumGross;
    $arrReturn['options']     = $special_options;
    $arrReturn['taxReduced']  = $taxReduced;
    $arrReturn['taxNormal']   = $taxNormal;

    return $arrReturn;
  }

 /**
  * calcProduct( )
  *
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcProduct( )
  {   
      // DIE  : $row is empty
    if( empty( $this->products ) )
    {
      $prompt = 'ERROR: there isn\'t any product!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
      // DIE  : $row is empty

    $arrReturn    = null;
    $contentItem  = '';
    
    $productsNet        = 0;
    $productsGross      = 0;
    $productsTaxReduced = 0;
    $productsTaxNormal  = 0;

      // FOREACH  : products
    foreach( ( array ) $this->products as $product )
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

        // DRS
      if( $this->drs->drsCobj )
      {
        $data   = var_export( $this->local_cObj->data, true );
        $prompt = 'cObj->data: ' . $data;
        t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
        
        // update product settings
      $this->caddyWiProductsProductSettings( $product );

        // update error prompts
      $this->caddyWiProductsProductErrorMsg( $product );

         // add inner html to variable
      $contentItem = $contentItem . $this->cObj->substituteMarkerArrayCached
                                    (
                                      $this->tmpl['item'], $this->markerArray
                                    );

        // update product gross
      $productsGross        = $productsGross + $product['price_total'];
        // update number of products
      $this->caddyCount  = $this->caddyCount + $product['qty'];

        // update service attributes
      $this->caddyWiProductsProductServiceAttributes( $product );

        // calculate tax
      $arrResult          = $this->calcProductTax( $product );
      $productsNet        = $productsNet        + $arrResult['cartNet'];
      $productsTaxReduced = $productsTaxReduced + $arrResult['taxReduced'];
      $productsTaxNormal  = $productsTaxNormal  + $arrResult['taxNormal'];

    }
      // FOREACH  : products

    $arrReturn['contentItem'] = $contentItem;
    $arrReturn['net']         = $productsNet;
    $arrReturn['gross']       = $productsGross;
    $arrReturn['taxReduced']  = $productsTaxReduced;
    $arrReturn['taxNormal']   = $productsTaxNormal;
//var_dump( __METHOD__, __LINE__, $contentItem, $this->tmpl['item'] );

    return $arrReturn;
  }

 /**
  * calcProductTax( )
  *
  * @param	array		$product  :
  * @return	array		$tax      : cartNet, cartTaxReduced, cartTaxNormal
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcProductTax( $product )
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
        $arrReturn['taxReduced'] = $currTax;
        break;
      case( 2 ):
      case( $this->conf['tax.']['normalCalc'] ):
        $arrReturn['taxNormal'] = $currTax;
        break;
      default:
        echo '<div style="border:2em solid red;padding:2em;color:red;"><h1 style="color:red;">caddy Error</h1><p>tax is "' . $product['tax'] . '".<br />This is an undefined value in class.tx_caddy.php. ABORT!<br /><br />Are you sure, that you included the caddy static template?</p></div>';
        exit;
    }
    $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap_former;

    return $arrReturn;
  }



  /***********************************************
  *
  * Getter
  *
  **********************************************/

 /**
  * getServiceAttributes( )
  *
  * @return	array
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getServiceAttributes( )
  {
    $arrResult =  array
                  (
                    '1' =>  array
                            (
                              'max' => $this->caddyServiceAttribute1Max,
                              'sum' => $this->caddyServiceAttribute1Sum
                            ),
                    '2' =>  array
                            (
                              'max' => $this->caddyServiceAttribute2Max,
                              'sum' => $this->caddyServiceAttribute2Sum
                            ),
                    '3' =>  array
                            (
                              'max' => $this->caddyServiceAttribute3Max,
                              'sum' => $this->caddyServiceAttribute3Sum
                            )
                  );
    
    return $arrResult;
  }



  /***********************************************
  *
  * Init
  *
  **********************************************/
  
 /**
  * initDie( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDie( )
  {
      // DIE  : $row is empty
    if( ! is_array( $this->row ) || empty( $this->row ) )
    {
      $prompt = 'ERROR: row is empty or isn\'t an array!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
      // DIE  : $row is empty

      // DIE  : $pObj isn't initiated
    if( ! is_object( $this->pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
                __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
      // DIE  : $pObj isn't initiated

      // DIE  : $local_cObj isn't initiated
    if( ! is_object( $this->local_cObj ) )
    {
      $prompt = 'ERROR: no local_cObj!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );     
    }
      // DIE  : $local_cObj isn't initiated
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
    if( ! ( $this->initInstances === null ) )
    {
      return;
    }
    
    $this->initInstances = true;
    
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;

    if(is_object ( $this->pObj->powermail ) )
    {
      $this->powermail = $this->pObj->powermail;
    }
    else
    {
        // DANGEROUS: todo: powermail must init!
      require_once( $path2lib . 'powermail/class.tx_caddy_powermail.php' );
      $this->powermail        = t3lib_div::makeInstance( 'tx_caddy_powermail' );
      $this->powermail->pObj  = $this;
    }
    
    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session          = t3lib_div::makeInstance( 'tx_caddy_session' );
    $this->session->pObj    = $this;

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc         = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
  }
  


  /***********************************************
  *
  * Setter
  *
  **********************************************/

 /**
  * setContentRow( )  : Set $row with cObj->data
  *
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function setContentRow( $row )
  {
    if( ! is_array( $row ) || empty( $row ) )
    {
      $prompt = 'ERROR: row is empty or isn\'t an array!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }
    $this->row = $row;
  }

 /**
  * setParentObject( )  : Returns a caddy with HTML form and HTML options among others
  *
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
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

    if( ! is_array( $pObj->conf ) || empty( $pObj->conf ) )
    {
      $prompt = 'ERROR: no configuration!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }
    $this->conf = $pObj->conf;

    if( ! is_object( $pObj->cObj ) )
    {
      $prompt = 'ERROR: no cObject!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }
    $this->cObj       = $pObj->cObj;

    if( ! is_object( $pObj->local_cObj ) )
    {
      $prompt = 'ERROR: no local_cObj!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }
    $this->local_cObj = $pObj->local_cObj;

    if( ! is_array( $pObj->tmpl ) || empty( $pObj->tmpl ) )
    {
      $prompt = 'ERROR: no template!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }

    $this->tmpl = $pObj->tmpl;
  }
  
 /**
  * setProducts( )  :
  *
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function setProducts( $products )
  {
    if( ! is_array( $products ) || empty( $products ) )
    {
      $prompt = 'ERROR: there isn\'t any product!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }
    $this->products = $products;
  }
  


  /***********************************************
  *
  * ZZ
  *
  **********************************************/

 /**
  * zz_addQtynameMarker():  Allocates to the global markerArray a value for ###QTY_NAME###
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
  private function zz_addQtynameMarker($product, $markerArray, $pObj)
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
      $array_add_gpvar = $this->productsGetVariantTs($product, $pObj);
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
  * zz_addVariantGpvarToImagelinkwrap():  Adds all table.field of the variant to
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
  private function zz_addVariantGpvarToImagelinkwrap($product, $ts_key, $ts_conf, $pObj)
  {
    unset( $ts_key );
    
      // RETURN : there isn't any variant
    if( ! is_array($pObj->conf['settings.']['variant.'] ) )
    {
      return $ts_conf;
    }
      // RETURN : there isn't any variant

      // get all variant key/value pairs from the current product
    $array_add_gpvar = $this->productsGetVariantTs( $product, $pObj );

      // add variant key/value pairs to imageLinkWrap
    foreach( ( array ) $array_add_gpvar as $key => $value )
    {
      $str_wrap = $ts_conf['imageLinkWrap.']['typolink.']['additionalParams.']['wrap'];
      $str_wrap = $str_wrap . '&' . $this->prefixId . '[' . $key . ']=' . $value;
      $ts_conf['imageLinkWrap.']['typolink.']['additionalParams.']['wrap'] = $str_wrap;
    }

    return $ts_conf;
  }

/**
 * zz_checkOptionIsNotAvailable( )  : Gets the option_id for a given type ('shipping', 'payment') 
 *                                    method on the current cart and checks the availability. 
 *                                    If available, return is 0. If not available the given fallback 
 *                                    or preset will returns.
 *
 * @param	string		$type
 * @param	int		$option_id
 * @return	int
 */
  private function zz_checkOptionIsNotAvailable( $type, $option_id )
  {
    if
    ( 
      (
        isset( $this->conf[$type.'.']['options.'][$option_id.'.']['available_from'] ) 
        &&  
        ( 
          round( floatval( $this->conf[$type.'.']['options.'][$option_id.'.']['available_from'] ), 2 )
          > 
          round( $this->productsGross, 2 ) 
        ) 
      ) 
      ||
      (
        isset( $this->conf[$type.'.']['options.'][$option_id.'.']['available_until'] ) 
        &&  
        ( 
          round( floatval( $this->conf[$type.'.']['options.'][$option_id.'.']['available_until'] ), 2 ) 
          < 
          round( $this->productsGross, 2 ) 
        )
       )
     )
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
  * zz_cObjGetSingle( ) : Renders a typoscript property with cObjGetSingle, if it is an array.
  *                       Otherwise returns the property unchanged.
  *
  * @param	string		$cObj_name  : value or the name of the property like TEXT, COA, IMAGE, ...
  * @param	array		$cObj_conf  : null or the configuration of the property
  * @return	string		$value      : unchanged value or rendered typoscript property
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
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
 * Gets the price for a given type ('shipping', 'payment') method on the current cart
 *
 * @param	string		$type
 * @param	int		$option_id
 * @return	string
 */
  private function zz_getPriceForOption($type, $option_id) 
  {
    $optionIds = $this->conf[$type.'.']['options.'][$option_id.'.'];

    $free_from  = $optionIds['free_from'];
    $free_until = $optionIds['free_until'];

    switch( true )
    {
      case( isset(  $free_from ) && ( floatval( $free_from ) <= $this->productsGross ) ):
      case( isset( $free_until ) && ( floatval( $free_until ) >= $this->productsGross ) ):
        return '0.00';
        break;
      default:
          // Follow the workflow
        break;
    }
    
    unset( $free_from );
    unset( $free_until );

    $filterArr = array(
                        'by_price' => $this->productsGross,
                        'by_quantity' => $this->caddyCount,
                        'by_service_attribute_1_sum' => $this->caddyServiceAttribute1Sum,
                        'by_service_attribute_1_max' => $this->caddyServiceAttribute1Max,
                        'by_service_attribute_2_sum' => $this->caddyServiceAttribute2Sum,
                        'by_service_attribute_2_max' => $this->caddyServiceAttribute2Max,
                        'by_service_attribute_3_sum' => $this->caddyServiceAttribute3Sum,
                        'by_service_attribute_3_max' => $this->caddyServiceAttribute3Max
                      );

    if( array_key_exists( $optionIds['extra'], $filterArr ) ) 
    {
      foreach( $optionIds['extra.'] as $extra )
      {
        if( floatval($extra['value'] ) <= $filterArr[$optionIds['extra']] )
        {
          $price = $extra['extra'];
        } else {
          return $price;
          break;
        }
      }
    } 
    
    switch( $optionIds['extra'] )
    {
      case 'each':
        $price = floatval( $optionIds['extra.']['1.']['extra'] ) * $this->caddyCount;
        break;
      default:
        $price = $optionIds['extra'];
    }

    return $price;
  }


  /**
 * [Describe function...]
 *
 * @param	[type]		$value: ...
 * @return	[type]		...
 */
  private function zz_price_format( $value )
  {
    $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']; // get ts

    $currencySymbol = $this->conf['main.']['currencySymbol'];
    $price          = number_format
                      (
                        $value, 
                        $this->conf['main.']['decimal'], 
                        $this->conf['main.']['dec_point'], 
                        $this->conf['main.']['thousands_sep']
                      );

    // print currency symbol before or after price
    if( $this->conf['main.']['currencySymbolBeforePrice'] ) 
    {
      $price = $currencySymbol . ' ' . $price;
    } 
    else 
    {
      $price = $price . ' ' . $currencySymbol;
    }

    return $price;
  }

  /**
 * [Describe function...]
 *
 * @param	[type]		$type: ...
 * @param	[type]		$option_id: ...
 * @return	[type]		...
 */
  private function zz_renderOptionList( $type, $option_id ) 
  {
    $radio_list = '';
    
      // LOOP each option
    foreach( ( array ) $this->conf[$type.'.']['options.'] as $key => $value )
    {
      if( ! stristr( $key, '.' ) )
      { 
        continue;
      }

        // hide option if not available by cartGrossNoService
      $show = true;
      if
      ( 
        ( 
          isset( $value['available_from'] ) 
          && 
          (
            round( floatval( $value['available_from'] ), 2 ) > round( $this->productsGross, 2 ) 
          ) 
        )
        ||
        (
          isset( $value['available_until'] ) 
          && ( round( floatval( $value['available_until'] ), 2 ) < round( $this->productsGross, 2 ) )
        )
      )
      {
        $show = false;
      }

      if( ! ( $show || $this->conf[$type.'.']['show_all_disabled'] ) )
      {
        continue;
      }

      if( $show )
      {
        $disabled = null;
      }
      else
      {
        $disabled = 'disabled="disabled"';
      }

      $condition_list = array( );

      if( isset( $value['free_from'] ) )
      {
        $pmarkerArray['###CONDITION###'] =  $this->pi_getLL( 'caddy_ll_' . $type . '_free_from' ) .
                                            ' ' . $this->zz_price_format( $value['free_from'] );
        $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached
                                            (
                                              $this->tmpl[$type . '_condition_item'], $pmarkerArray
                                            );
      }
      if (isset($value['free_until']))
      {
        $pmarkerArray['###CONDITION###'] =  $this->pi_getLL( 'caddy_ll_' . $type . '_free_until' ) . 
                                            ' ' . $this->zz_price_format($value['free_until'] );
        $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached
                                            (
                                              $this->tmpl[$type . '_condition_item'], $pmarkerArray
                                            );
      }

      if ( ! $show )
      {
        if( isset($value['available_from'] ) )
        {
          $pmarkerArray['###CONDITION###'] =  $this->pi_getLL( 'caddy_ll_'.$type.'_available_from' ) .
                                              ' ' . $this->zz_price_format( $value['available_from'] );
          $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached
                                              (
                                                $this->tmpl[$type . '_condition_item'], $pmarkerArray
                                              );
        }
        if( isset( $value['available_until'] ) )
        {
          $pmarkerArray['###CONDITION###'] =  $this->pi_getLL( 'caddy_ll_' . $type . '_available_until' ) .
                                              ' ' . $this->zz_price_format( $value['available_until'] );
          $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached
                                              (
                                                $this->tmpl[$type . '_condition_item'], $pmarkerArray
                                              );
        }
      }

      switch( $value['extra'] )
      {
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

      if( $value['extra'] != 'each' )
      {
        foreach( ( array ) $value['extra.'] as $extra )
        {
          $pmarkerArray['###CONDITION###'] =  $this->pi_getLL( 'caddy_ll_service_from' ) . 
                                              ' ' . $extra['value'] . ' ' . $unit . ' : ' .
                                              $this->zz_price_format($extra['extra']);
          $condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached
                                              (
                                                $this->tmpl[$type . '_condition_item'], $pmarkerArray
                                              );
        }

        $show_price = $this->zz_price_format( floatval( $this->zz_getPriceForOption( $type, intval( $key ) ) ) );
      } 
      else
      {
        $show_price = sprintf
                      ( 
                        $this->pi_getLL( 'caddy_ll_special_each' ), 
                        $this->zz_price_format( $value['extra.']['1.']['extra'] ) 
                      );
      }

      $upperType = strtoupper( $type );

      if( $type != 'special' ) 
      {
        $checkradio = intval( $key ) == $option_id ? 'checked="checked"' : '';
        $this->smarkerArray['###' . $upperType . '_RADIO###'] = 
          '<input type="radio" onchange="this.form.submit()" name="tx_caddy_pi1[' . $type . ']" ' . 
          'id="tx_caddy_pi1_' . $type . '_' . intval( $key ) . '"  value="' . intval( $key ) . '"  ' . 
          $checkradio . $disabled . '/>';
      } 
      else
      {
        $checkbox = in_array( intval( $key ) , $option_id ) ? 'checked="checked"' : '';
        $this->smarkerArray['###' . $upperType . '_CHECKBOX###'] = 
          '<input type="checkbox" onchange="this.form.submit()" name="tx_caddy_pi1[' . $type . '][]" '. 
          'id="tx_caddy_pi1_' . $type . '_' . intval( $key ) . '"  value="' . intval( $key ) . '"  ' . 
          $checkbox . $disabled . '/>';
      }

      // TODO: In braces the actual Price for Payment should be displayed, not the first one.

      $title = $this->zz_cObjGetSingle( $value['title'], $value['title.'] );
      $this->smarkerArray['###'.$upperType.'_TITLE###'] = 
        '<label for="tx_caddy_pi1_' . $type . '_' . intval( $key ) . '">' . 
        $title . ' (' . $show_price . ')</label>';

      if( isset( $condition_list['###CONTENT###'] ) )
      {
        $this->smarkerArray['###'.$upperType.'_CONDITION###'] = 
          $this->cObj->substituteMarkerArrayCached( $this->tmpl[$type . '_condition_all'], null, $condition_list );
      } else {
        $this->smarkerArray['###'.$upperType.'_CONDITION###'] = '';
      }
      $radio_list .= $this->cObj->substituteMarkerArrayCached( $this->tmpl[$type . '_item'], $this->smarkerArray );
    }
      // LOOP each option

    return $radio_list;
  }




}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy.php']);
}
?>
