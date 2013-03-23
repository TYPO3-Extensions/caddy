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
 *  980:     private function zz_checkOptionIsNotAvailable($optionType, $optionId)
 * 1014:     private function calcOptionCosts($optionType, $optionId)
 * 1064:     private function zz_price_format($value)
 * 1086:     private function optionList($optionType, $optionId)
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
 * @version	2.0.2
 * @since       2.0.0
 */
class tx_caddy extends tslib_pibase
{

  public $extKey        = 'caddy';
  public $prefixId      = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';


  private $numberOfItems              = 0;
  private $caddyServiceAttribute1Max  = 0;
  private $caddyServiceAttribute1Sum  = 0;
  private $caddyServiceAttribute2Max  = 0;
  private $caddyServiceAttribute2Sum  = 0;
  private $caddyServiceAttribute3Max  = 0;
  private $caddyServiceAttribute3Sum  = 0;
  private $productsGross              = 0;

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

    $this->init( );
    
      // get products from session
    $this->products = $this->session->productsGet( );
//var_dump( __METHOD__, __LINE__, $this->products );
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
    $contentItem      = $arrResult['contentItem'];
    $payment_option   = $arrResult['payment_options'];
    $paymentId        = $arrResult['paymentId'];
    $productsGross    = $arrResult['productsGross'];
    $productsNet      = $arrResult['productsNet'];
    $optionsNet       = $arrResult['optionsNet'];
    $optionsGross     = $arrResult['optionsGross'];
    $shipping_option  = $arrResult['shipping_options'];
    $shippingId       = $arrResult['shippingId'];
    $special_options  = $arrResult['special_options'];
    $specialIds       = $arrResult['specialIds'];
    $sumGross         = $arrResult['sumGross'];
    $sumNet           = $arrResult['sumNet'];
    $sumTaxNormal     = $arrResult['sumTaxNormal'];
    $sumTaxReduced    = $arrResult['sumTaxReduced'];

    unset( $arrResult );
    unset( $productsGross );
    $subpartArray['###CONTENT###'] = $this->caddyWiProductsItem( $contentItem );

       
      // session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $sesArray['payment_option']   = $payment_option;
    $sesArray['paymentId']        = $paymentId;
    $sesArray['productsGross']    = $this->productsGross;
    $sesArray['productsNet']      = $productsNet;
    $sesArray['optionsNet']       = $optionsNet;
    $sesArray['optionsGross']     = $optionsGross;
    $sesArray['shipping_option']  = $shipping_option;
    $sesArray['shippingId']       = $shippingId;
    $sesArray['special_options']  = $special_options;
    $sesArray['specialIds']       = $specialIds;
    $sesArray['sumGross']         = $sumGross;
    $sesArray['sumNet']           = $sumNet;
    $sesArray['sumTaxNormal']     = $sumTaxNormal;
    $sesArray['sumTaxReduced']    = $sumTaxReduced;
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
      // session

    $this->local_cObj->start( $sesArray, $this->conf['db.']['table'] );
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

    
    $minimumRate           = floatval( $this->conf['cart.']['cartmin.']['value'] );
    $minimumRateIsUndercut = ( $sesArray['productsGross'] < $minimumRate );

      // SWITCH : product gross is undercut minimum rate
    switch( $minimumRateIsUndercut )
    {
      case( true ):
          // Set min price error
        $caddyMinStr                            = $this->zz_price_format( $minimumRate );
        $minPriceArray['###ERROR_MINPRICE###']  = sprintf( $this->pi_getLL( 'minprice' ), $caddyMinStr );
        $subpartArray['###MINPRICE###']         = $this->cObj->substituteMarkerArrayCached
                                                  ( 
                                                    $this->tmpl['minprice'], 
                                                    $minPriceArray 
                                                  );
        break;
      case( false ):
      default:
          // Set shipping radio, payment radio and special checkbox
        $shippingArray['###CONTENT###'] = $this->optionList( 'shipping', $shippingId );
        $subpartArray['###SHIPPING_RADIO###'] = '';
        if( $shippingArray['###CONTENT###'] )
        {
          $subpartArray['###SHIPPING_RADIO###'] =
            $this->cObj->substituteMarkerArrayCached( $this->tmpl['shipping_all'], null, $shippingArray );
        }

        $paymentArray['###CONTENT###'] = $this->optionList('payment', $paymentId);
        $subpartArray['###PAYMENT_RADIO###'] = '';
        if( $paymentArray['###CONTENT###'] )
        {
          $subpartArray['###PAYMENT_RADIO###'] =
            $this->cObj->substituteMarkerArrayCached( $this->tmpl['payment_all'], null, $paymentArray );
        }

        $subpartArray['###SPECIAL_CHECKBOX###'] = '';
        $specialArray['###CONTENT###'] = $this->optionList('special', $specialIds);
        if( $specialArray['###CONTENT###'] )
        {
          $subpartArray['###SPECIAL_CHECKBOX###'] =
            $this->cObj->substituteMarkerArrayCached( $this->tmpl['special_all'], null, $specialArray );
        }
        break;
    }
      // SWITCH : product gross is undercut minimum rate

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
    $prompt = null;

      // FOREACH  : error messages per product
    foreach( ( array ) $product['error'] as $productError )
    {
      if( ! $productError )
      {
        continue;
      }

      $prompt = $prompt 
              . $this->cObj->substituteMarker( $this->tmpl['item_error'], '###ERROR_PROMPT###', $productError );
    }
      // FOREACH  : error messages per product
    
    if( $prompt )
    {
      $this->markerArray['###ITEM_ERROR###'] = $prompt;
    }
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
    $this->init( );
    
      // handle the current product
    $arrResult      = $this->calcProduct( );
    $contentItem    = $arrResult['contentItem'];
    $sumNet         = $arrResult['net'];
    $sumGross       = $arrResult['gross'];
    $sumTaxReduced  = $arrResult['taxReduced'];
    $sumTaxNormal   = $arrResult['taxNormal'];
    unset( $arrResult );
      // handle the current product

    $this->productsGross  = $sumGross;
    $productsGross        = $sumGross;
    $productsNet          = $sumNet;

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
                    'paymentId'         => $paymentId,
                    'payment_option'    => $payment_option,
                    'productsGross'     => $productsGross,
                    'productsNet'       => $productsNet,
                    'optionsNet'        => $optionsNet,
                    'optionsGross'      => $optionsGross,
                    'serviceattributes' => $serviceattributes,
                    'shippingId'        => $shippingId,
                    'shipping_option'   => $shipping_option,
                    'specialIds'        => $specialIds,
                    'special_options'   => $special_options,
                    'sumGross'          => $sumGross,
                    'sumNet'            => $sumNet,
                    'sumTaxNormal'      => $sumTaxNormal,
                    'sumTaxReduced'     => $sumTaxReduced
                  );
//var_dump( __METHOD__, __LINE__, $arrReturn );

    return $arrReturn;
  }
  


  /***********************************************
  *
  * Calc Options
  *
  **********************************************/

 /**
  * calcOptionCosts( )  : Gets the gross costs for the given option
  *
  * @param    string        $optionType   : payment, shipping, special
  * @param    integer       $optionId     : current option id
  * @return   array         $optionCosts  : gross and net
  * @access   private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcOptionCosts( $optionType, $optionId ) 
  {
    $optionCosts = array
    (
      'gross' => 0.00,
      'net'   => 0.00
    );
    
      // DRS
    if( $this->drs->drsFormula )
    {
      $prompt = $optionType . '.options.' . $optionId;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
      
    switch( $this->calcOptionCostsIsFree( $optionType, $optionId ) )
    {
      case( true ):
          // Return $optionsCosts with 0.00 / 0.00
        break;
      case( false ):
      default:
          // Get option gross costs
        $optionCosts = $this->calcOptionCostsGross( $optionType, $optionId );
        break;
    }
    
      // DRS
    if( $this->drs->drsFormula )
    {
      $gross  = $optionCosts['gross'];
      $net    = $optionCosts['net'];
      $prompt = 'Returns gross ' . $gross . ' and net ' . $net;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    return $optionCosts;
  }

 /**
  * calcOptionCostsGross( )  : Gets the gross costs for the given option
  *
  * @param    string        $optionType : payment, shipping, special
  * @param    integer       $optionId   : current option id
  * @return   double        $gross      : the gross costs
  * @access   private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcOptionCostsGross( $optionType, $optionId ) 
  {
    $gross  = 0.00;
    $net    = 0.00;
    
      // configuration of current options array
    $confOptions  = $this->conf[$optionType . '.']['options.'][$optionId . '.'];
    $extra        = $confOptions['extra'];
    $extras       = $confOptions['extra.'];
    $taxType      = $confOptions['tax'];


      // DRS
    if( $this->drs->drsFormula )
    {
      $prompt = 'extraType: ' . $extra;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      $prompt = 'taxType: ' . $taxType;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
      
      // SWITCH : extra costs
    switch( $extra )
    {
      case 'by_price':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->productsGross );
        break;
      case 'by_quantity':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->numberOfItems );
        break;
      case 'by_service_attribute_1_sum':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->caddyServiceAttribute1Sum );
        break;
      case 'by_service_attribute_1_max':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->caddyServiceAttribute1Max );
        break;
      case 'by_service_attribute_2_sum':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->caddyServiceAttribute2Sum );
        break;
      case 'by_service_attribute_2_max':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->caddyServiceAttribute2Max );
        break;
      case 'by_service_attribute_3_sum':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->caddyServiceAttribute3Sum );
        break;
      case 'by_service_attribute_3_max':
        $gross = $this->calcOptionCostsGrossByExtra( $extras, $this->caddyServiceAttribute3Max );
        break;
      case 'each':
        $gross  = floatval( $extras['1.']['extra'] ) 
                * $this->numberOfItems
                ;
        break;
      default:
        $gross = floatval( $extra );
        break;
    }
      // SWITCH : extra costs
    
      // get net
    $net = $this->zz_calcNet( $taxType, $gross );
    
      // result array
    $optionCosts  = array
    (
      'gross' => $gross,
      'net'   => $net
    );
    
    return $optionCosts;
  }

 /**
  * calcOptionCostsGrossByExtra( ) : Gets the gross costs for the given value.
  *
  * @param    string        $extras : the extra array of the current option id
  * @param    integer       $value  : could be the number of products or the current price 
  * @return   double        $gross  : the gross costs of the current option
  * @access   private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcOptionCostsGrossByExtra( $extras, $value ) 
  {
    $limit  = null;
    $gross  = null;
    $valueIsGreaterOrEqual  = false;
    $valueIsSmaller         = false;
    
    foreach( $extras as $extra )
    {
        // floatval, because value could be a double
      $limit = floatval( $extra['value'] );
      $value = floatval( $value );
      $valueIsGreaterOrEqual  = ( $limit <= $value );
      $valueIsSmaller         = ( $limit  > $value );
      
        // SWITCH : overrun limit
      switch( true )
      {
        case( $valueIsGreaterOrEqual ):
            // limit is overrun, take the gross costs of the current limit
          $gross = $extra['extra'];
            // DRS
          if( $this->drs->drsFormula )
          {
            $prompt = 'value ' . $value . ' is greater than or equal to the limit ' . $limit;
            t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
            $prompt = 'gross is set to ' . $gross;
            t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
          }
            // DRS
          break;
        case( $valueIsSmaller ):
        default:
            // limit is kept, return the latest gross costs from above
            // DRS
          if( $this->drs->drsFormula )
          {
            $prompt = 'value ' . $value . ' is smaller than the limit ' . $limit;
            t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
            $prompt = 'gross is left by ' . $gross;
            t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
          }
            // DRS
          continue 2;
      }
        // SWITCH : overrun limit
    }

    unset( $valueIsGreaterOrEqual );
    unset( $valueIsSmaller );
    
    return $gross;
  }
	
 /**
  * calcOptionCostsIsFree( )  : Returns true, if option costs are for free.
  *                               Costs are free if
  *                               * free_from or free_to is configured
  *                               * and if cartGrossNoService is within this limits
  *
  * @param    string        $optionType   : payment, shipping, special
  * @param    integer       $optionId    : current option id
  * @return   boolean       $optionIsFree : True, if free. False, if not free.
  * @access   private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcOptionCostsIsFree( $optionType, $optionId ) 
  {
    $optionIsFree = false; 

    $confOptions = $this->conf[$optionType . '.']['options.'][$optionId . '.'];

    $free_from  = $confOptions['free_from'];
    $free_to    = $confOptions['free_until'];

    $freeFromTo = $free_from . $free_to;
    
      // RETURN : there is neither a from nor a to
    if( empty( $freeFromTo ) )
    {
        // DRS
      if( $this->drs->drsFormula )
      {
        $prompt = 'free_from and free_until isn\'t set.';
        t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      }
        // DRS

      return $optionIsFree;
    }
      // RETURN : there is neither a from nor a to

    $limitFrom  = floatval( $free_from ); 
    $limitTo    = floatval( $free_to ); 
    
      // DRS
    if( $this->drs->drsFormula )
    {
      $prompt = 'Limit is from ' . $limitFrom . ' to ' . $limitTo;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Value is  ' . $this->productsGross;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    $valueIsGreaterOrEqualThanFrom  = ( $limitFrom  <= $this->productsGross );
    $valueIsSmallerOrEqualThanTo    = ( $limitTo    >= $this->productsGross );
    
    switch( true )
    {
      case( $valueIsGreaterOrEqualThanFrom && $valueIsSmallerOrEqualThanTo ):
        $optionIsFree = true; 
          // DRS
        if( $this->drs->drsFormula )
        {
          $prompt = 'value is keeping the limit.';
          t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
        }
          // DRS
        break;
      default;
          // DRS
        if( $this->drs->drsFormula )
        {
          $prompt = 'value is overrun the limit.';
          t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
        }
          // DRS
        $optionIsFree = false; 
        break;
    }
    
    unset( $valueIsGreaterOrEqualThanFrom );
    unset( $valueIsSmallerOrEqualThanTo );
    
    return $optionIsFree;
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

    $gross        = 0.00;
    $net          = 0.00;
    $taxReduced   = 0.00;
    $taxNormal    = 0.00;

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

    $arrResult  = $this->calcOptionCosts( 'payment', $paymentId );
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

    $option = $this->getPaymentOptionLabelBySessionId( );

    $arrReturn['id']          = $paymentId;
    $arrReturn['gross']       = $gross;
    $arrReturn['option']      = $option;
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

    $gross        = 0.00;
    $net          = 0.00;
    $taxReduced   = 0.00;
    $taxNormal    = 0.00;

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

    $arrResult  = $this->calcOptionCosts( 'shipping', $shippingId );
    $net        = $arrResult['net'];  
    $gross      = $arrResult['gross'];  

    if( $this->conf['shipping.']['options.'][$shippingId . '.']['tax'] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

    $option = $this->getShippingOptionLabelBySessionId( );

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

    $gross        = 0.00;
    $net          = 0.00;
    $sumGross     = 0.00;
    $sumNet       = 0.00;
    $taxReduced   = 0.00;
    $taxNormal    = 0.00;
    $options      = null;
    
    foreach( ( array ) $specialIds as $specialId )
    {
      $arrResult  = $this->calcOptionCosts( 'special', $specialId );
      $net        = $arrResult['net'];  
      $gross      = $arrResult['gross'];  
      
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

    $options = $this->getSpecialOptionLabelsBySessionId( );

    $arrReturn['ids']         = $specialIds;
    $arrReturn['net']         = $sumNet;
    $arrReturn['gross']       = $sumGross;
    $arrReturn['options']     = $options;
    $arrReturn['taxReduced']  = $taxReduced;
    $arrReturn['taxNormal']   = $taxNormal;

    return $arrReturn;
  }
  


  /***********************************************
  *
  * Calc Products
  *
  **********************************************/

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
      $this->numberOfItems  = $this->numberOfItems + $product['qty'];

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
  * Getting methods
  *
  **********************************************/

 /**
  * getPaymentOptionLabelBySessionId( )
  *
  * @return	array
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getPaymentOptionLabelBySessionId( )
  {
      // Get session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Get configuration
    $optionsConf = $this->conf['payment.']['options.'];
    
      // Get key for the option 
    $key    = $sesArray['paymentId'] . '.';

      // Render the option label
    $name  = $optionsConf[ $key ]['title'];
    $conf  = $optionsConf[ $key ]['title.'];
    $value = $this->zz_cObjGetSingle( $name, $conf );
    return $value;
  }

 /**
  * getShippingOptionLabelBySessionId( )
  *
  * @return	array
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getShippingOptionLabelBySessionId( )
  {
      // Get session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Get configuration
    $optionsConf = $this->conf['shipping.']['options.'];
    
      // Get key for option 
    $key    = $sesArray['shippingId'] . '.';

      // Render the option label
    $name   = $optionsConf[ $key ]['title'];
    $conf   = $optionsConf[ $key ]['title.'];
    $value  = $this->zz_cObjGetSingle( $name, $conf );
    return $value;
  }

 /**
  * getSpecialOptionLabelsBySessionId( )
  *
  * @return	array
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getSpecialOptionLabelsBySessionId( )
  {
      // Get session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Get configuration
    $optionsConf = $this->conf['special.']['options.'];
    
      // Get key for the option 

      // Render the option label
    $value = null;
    foreach( ( array ) $sesArray['specialIds'] as $key )
    {
      $name   = $optionsConf[ $key ]['title'];
      $conf   = $optionsConf[ $key ]['title.'];
      $value  = $value
              . $this->zz_cObjGetSingle( $name, $conf );
    }

    return $value;
  }

 /**
  * getServiceAttributes( )
  *
  * @return	array
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getServiceAttributes( )
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
  * init( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function init( )
  {
    $this->pi_loadLL();

      // DIE  : if pObj or row isn't initiated
    $this->initDie( );
    
    $this->initInstances( );
  }
  
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

//    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
//    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
//    $this->drs->pObj        = $this;
//    $this->drs->row         = $this->cObj->data;

    if( is_object ( $this->pObj->powermail ) )
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

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc         = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
    
    $this->session->setParentObject( $this );
  }
  


  /***********************************************
  *
  * Options
  *
  **********************************************/

 /**
  * optionList( )
  * 
  * @param    string        $optionType   : payment, shipping, special
  * @param    integer       $optionId     : current option id
  * @return	[type]		...
  */
  private function optionList( $optionType, $optionId ) 
  {
    $condition    = null;
    $optionList   = null;
    $optionItems  = ( array ) $this->conf[$optionType.'.']['options.'];
    
      // DRS
    if( $this->drs->drsOptions )
    {
      $prompt = $optionType . '.options.' . $optionId;
      t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
      
      // LOOP each option item
    foreach( $optionItems as $optionItemKey => $optionItemConf )
    {
      if( ! stristr( $optionItemKey, '.' ) )
      { 
        continue;
      }

        // DRS
      if( $this->drs->drsOptions )
      {
        $prompt = $optionType . '.options.' . $optionId . '.' . $optionItemKey;
        t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
      }
        // DRS

        // productsGross keeps the limit for available
      $keepingTheLimit = true;
      $keepingTheLimit = $this->optionListGrossIsKeepingTheLimit( $optionItemConf );
        // productsGross keeps the limit for available

        // SWITCH : display option list item
      switch( true )
      {
        case( $keepingTheLimit ):
        case( $this->conf[$optionType.'.']['show_all_disabled'] ):
          break;
        default:
            // DRS
          if( $this->drs->drsOptions )
          {
            $prompt = 'Don\'t display current item: product gross isn\'t keeping the limit and show_all_disabled isn\'t enabled.';
            t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
          }
            // DRS
          continue 2;
          break;
      }
        // SWITCH : display option list item

        // Get the content for met conditions
      $condition  = $this->optionListCondition( $keepingTheLimit, $optionType, $optionItemConf, $optionItemKey );
      
        // Get the gross costs
      $gross = $this->optionListConditionGross( $optionItemKey, $optionType, $optionItemConf ); 

        // Set the marker array
      $this->optionListMarker( $keepingTheLimit, $optionType, $optionItemKey, $optionId, $condition, $gross, $optionItemConf );

        // render the option item list
      $tmpl       = $this->tmpl[$optionType . '_item'];
      $optionList = $optionList
                  . $this->cObj->substituteMarkerArrayCached( $tmpl, $this->smarkerArray );
        // render the option item list
    }
      // LOOP each option item

    return $optionList;
  }

 /**
  * optionListCondition( )
  * 
  * @param    string        $keepingTheLimit  : 
  * @param    string        $optionType : payment, shipping, special
  * @param    array         $conf       : configuration of current option item
  * @param    string        $optionItemKey
  * @return   string        $content
  */
  private function optionListCondition( $keepingTheLimit, $optionType, $conf, $optionItemKey ) 
  {
    $condition = null;
    
    $condition  = $condition
                . $this->optionListConditionByType( 'free_from', $optionType, $conf );

    $condition  = $condition
                . $this->optionListConditionByType( 'free_until', $optionType, $conf );

    if ( ! $keepingTheLimit )
    {
      $condition  = $condition
                  . $this->optionListConditionByType( 'available_from', $optionType, $conf );
      $condition  = $condition
                  . $this->optionListConditionByType( 'available_until', $optionType, $conf );
    }
    
    if( $conf['extra'] != 'each' )
    {
      $condition  = $condition
                  . $this->optionListConditionNotEach( $optionItemKey, $optionType, $conf ); 
    }
    return $condition;
  }

 /**
  * optionListConditionByType( )
  * 
  * @param    string        $condition  : free_from, free_until, available_from, available_until
  * @param    string        $optionType : payment, shipping, special
  * @param    array         $conf       : configuration of current option item
  * @return   string        $content
  */
  private function optionListConditionByType( $condition, $optionType, $conf ) 
  {
    $content = null;
    
      // RETURN : no value
    if( ! isset( $conf[$condition] ) )
    {
        // DRS
      if( $this->drs->drsOptions )
      {
        $prompt = 'condition ' . $condition . ' isn\'t set.';
        t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      return $content;
    }
      // RETURN : no value
    
      // marker
    $gross    = $this->zz_price_format( $conf[$condition] );
    $llLabel  = $optionType . '_' . $condition;
    $llLabel  = $this->pi_getLL( $llLabel );
    $marker['###CONDITION###'] =  $llLabel . ' ' . $gross;
      // marker

      // content
    $content  = $this->tmpl[$optionType . '_condition_item'];
    $content  = $this->cObj->substituteMarkerArrayCached( $content, $marker );
      // content
    
    return $content;
  }

/**
  * optionListConditionGross( )
  * 
  * @param    string        $optionItemKey : 
  * @param    string        $optionType     : payment, shipping, special
  * @param    array         $conf           : configuration of current option item
  * @return   double        $gross
  */
  private function optionListConditionGross( $optionItemKey, $optionType, $conf ) 
  {
    $gross = null; 
    
    switch( true )
    {
      case( $conf['extra'] != 'each' ):
        $gross = $this->optionListConditionGrossOther( $optionItemKey, $optionType ); 
        break;
      case( $conf['extra'] == 'each' ):
      default:
        $gross = $this->optionListConditionGrossEach( $conf );
        break;
    }
    
    return $gross;
  }
  
/**
  * optionListConditionGrossEach( )
  * 
  * @param    array         $conf   : configuration of current option item
  * @return   double        $gross
  */
  private function optionListConditionGrossEach( $conf ) 
  {
    $gross    = $this->zz_price_format( $conf['extra.']['1.']['extra'] );
    $llLabel  = $this->pi_getLL( 'special_each' );

    $gross  = sprintf( $llLabel, $gross );
    
      // DRS
    if( $this->drs->drsOptions )
    {
      $prompt = 'gross for each: ' . $gross;
      t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    return $gross;
  }

 /**
  * optionListConditionGrossOther( )
  * 
  * @param    string        $optionItemKey        : 
  * @param    string        $optionType : payment, shipping, special
  * @return   double        $gross
  */
  private function optionListConditionGrossOther( $optionItemKey, $optionType ) 
  {
    $gross  = floatval( $this->calcOptionCosts( $optionType, intval( $optionItemKey ) ) );
    $gross  = $this->zz_price_format( $gross );
    
      // DRS
    if( $this->drs->drsOptions )
    {
      $prompt = 'gross for others than each: ' . $gross;
      t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
    return $gross;
  }

 /**
  * optionListConditionNotEach( )
  * 
  * @param    string        $optionItemKey        : 
  * @param    string        $optionType : payment, shipping, special
  * @param    array         $conf       : configuration of current option item
  * @return   array         $arrReturn  : content, gross
  */
  private function optionListConditionNotEach( $optionItemKey, $optionType, $conf ) 
  {
    $content  = null;
    $gross    = null;

    $unit   = $this->optionListSymbolByExtra( $conf['extra'] );
    $extras = ( array ) $conf['extra.'];

    foreach( $extras as $extra )
    {
      $gross    = $this->zz_price_format( $extra['extra'] );
      $llLabel  = $this->pi_getLL( 'service_from' );
      $tmpl     = $this->tmpl[$optionType . '_condition_item'];

      $marker['###CONDITION###'] =  $llLabel . ' ' . $extra['value'] . ' ' . $unit . ' : ' . $gross;
      $content  = $content
                . $this->cObj->substituteMarkerArrayCached( $tmpl, $marker );
    }

    $gross  = floatval( $this->calcOptionCosts( $optionType, intval( $optionItemKey ) ) );
    $gross  = $this->zz_price_format( $gross );
    
    $arrReturn = array(
      'content' => $content,
      'gross'   => $gross
    );
    
    return $arrReturn;
  }
  
 /**
  * optionListGrossIsKeepingTheLimit( ) : Checks, if price gross is keeping the limit for an option
  * 
  * @param    array         $confOption       : configuration of the current option
  * @return   boolean       $keepingTheLimit  : True, if price gross is keeping the limit, false, if not
  */
  private function optionListGrossIsKeepingTheLimit( $confOption ) 
  {
      // By default: option should displayed
    $keepingTheLimit  = true;
    $gross          = round( $this->productsGross, 2 );
    
      // By default: gross is keeping the limit
    $grossIsSmallerThanFrom = false;
    $grossIsGreaterThanTo   = false;
    
      // IF : available from
    if( isset( $confOption['available_from'] ) )
    {
      $limitFrom = round( floatval( $confOption['available_from'] ),   2 );
      if( $limitFrom > $gross )
      {
        $grossIsSmallerThanFrom = true;
      }
    }
      // IF : available from

      // IF : available to
    if( isset( $confOption['available_until'] ) )
    {
      $limitTo = round( floatval( $confOption['available_until'] ),   2 );
      if( $limitTo < $gross )
      {
        $grossIsGreaterThanTo = true;
      }
    }
      // IF : available to

      // SWITCH : keeping the limit
    switch( true )
    {
      case( $grossIsSmallerThanFrom ):
      case( $grossIsGreaterThanTo ):
        $keepingTheLimit = false;
        break;
      default:
        $keepingTheLimit = true;
        break;
    }
      // SWITCH : keeping the limit
  
    return $keepingTheLimit;
  }  

 /**
  * optionListMarker( )
  * 
  * @param    string        $keepingTheLimit  : 
  * @param    string        $optionType       : payment, shipping, special
  * @param    string        $optionItemKey    :
  * @param    integer       $optionId         : current option id
  * @param    string        $condition        :
  * @param    double        $gross            : 
  * @return   void
  */
  private function optionListMarker( $keepingTheLimit, $optionType, $optionItemKey, $optionId, $condition, $gross, $optionItemConf ) 
  {
    switch( true )
    {
      case( $optionType != 'special' ):
        $this->optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId );
        break;
      case( $optionType == 'special' ):
      default:
        $this->optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionId );
        break;
    }

    $this->optionListMarkerLabel( $optionType, $optionItemKey, $gross, $optionItemConf );

    $this->optionListMarkerCondition( $optionType, $condition );
  }

 /**
  * optionListMarkerCheckbox( )
  * 
  * @param    string        $keepingTheLimit  : 
  * @param    string        $optionType       : payment, shipping, special
  * @param    string        $optionItemKey    :
  * @param    integer       $optionId         : current option id
  * @return   void
  */
  private function optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionId )
  {
    $checked = null;
    if( intval( $optionItemKey ) == $optionId )
    {
      $checked = ' checked="checked"';
    }

    $disabled = null;
    if( ! $keepingTheLimit )
    {
      $disabled = ' disabled="disabled"';
    }
    
    $hashMarker = strtoupper( $optionType );
 
    $content  = '<input type="checkbox" onchange="this.form.submit()" name="tx_caddy_pi1[' . $optionType . '][]" ' 
              . 'id="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '" ' 
              . 'value="' . intval( $optionItemKey ) . '"' . $checked . $disabled . '/>';

    $this->smarkerArray['###' . $hashMarker . '_CHECKBOX###'] = $content; 

      // DRS
    if( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_CHECKBOX### is set to ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListMarkerLabel( )
  * 
  * @param    string        $optionType       : payment, shipping, special
  * @param    string        $condition        :
  * @return   void
  */
  private function optionListMarkerCondition( $optionType, $condition )
  {
    $hashMarker = strtoupper( $optionType );
    
      // RETURN : no condition content
    if( ! $condition )
    {
      $this->smarkerArray['###' . $hashMarker . '_CONDITION###'] = '';
      return;
    }
      // RETURN : no condition content

      // Get template and marker
    $tmpl                     = $this->tmpl[$optionType . '_condition_all'];
    $marker['###CONTENT###']  = $condition;

      // render the content
    $content = $this->cObj->substituteMarkerArrayCached( $tmpl, null, $marker );
    
      // set the marker
    $this->smarkerArray['###' . $hashMarker . '_CONDITION###'] = $content; 

      // DRS
    if( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_CONDITION### is set to ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListMarkerLabel( )
  * 
  * @param    string        $keepingTheLimit  : 
  * @param    string        $optionType       : payment, shipping, special
  * @param    string        $optionItemKey    :
  * @param    integer       $optionId         : current option id
  * @return   void
  */
  private function optionListMarkerLabel( $optionType, $optionItemKey, $gross, $optionItemConf )
  {
    // TODO: In braces the actual Price for Payment should be displayed, not the first one.

    $title = $this->zz_cObjGetSingle( $optionItemConf['title'], $optionItemConf['title.'] );

    $hashMarker = strtoupper( $optionType );
 
    $content  = '<label for="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '">' 
              . $title . ' (' . $gross . ')</label>';

    $this->smarkerArray['###' . $hashMarker . '_TITLE###'] = $content; 

      // DRS
    if( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_TITLE### is set to ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListMarkerRadio( )
  * 
  * @param    string        $keepingTheLimit  : 
  * @param    string        $optionType       : payment, shipping, special
  * @param    string        $optionItemKey    :
  * @param    integer       $optionId         : current option id
  * @return   void
  */
  private function optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId )
  {
    $checked = null;
    if( intval( $optionItemKey ) == $optionId )
    {
      $checked = ' checked="checked"';
    }

    $disabled = null;
    if( ! $keepingTheLimit )
    {
      $disabled = ' disabled="disabled"';
    }
    
    $hashMarker = strtoupper( $optionType );
 
    $content  = '<input type="radio" onchange="this.form.submit()" name="tx_caddy_pi1[' . $optionType . ']" ' 
              . 'id="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '"  ' 
              . 'value="' . intval( $optionItemKey ) . '"' . $checked . $disabled . '/>';

    $this->smarkerArray['###' . $hashMarker . '_RADIO###'] = $content; 

      // DRS
    if( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_RADIO### is set to ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListSymbolByExtra( )  : Returns the symbol depending on the extra type
  * 
  * @param    string        $extraType  : te extra type
  * @return   string        $symbol     : symbold depending on extra type
  */
  private function optionListSymbolByExtra( $extraType ) 
  {
    $symbol = null;

    switch( $extraType )
    {
      case 'by_price':
        $symbol = $this->conf['main.']['currencySymbol'];
        break;
      case 'by_quantity':
        $symbol = $this->conf['main.']['quantitySymbol'];
        break;
      case 'by_service_attribute_1_sum':
      case 'by_service_attribute_1_max':
        $symbol = $this->conf['main.']['service_attribute_1_symbol'];
        break;
      case 'by_service_attribute_2_sum':
      case 'by_service_attribute_2_max':
        $symbol = $this->conf['main.']['service_attribute_2_symbol'];
        break;
      case 'by_service_attribute_3_sum':
      case 'by_service_attribute_3_max':
        $symbol = $this->conf['main.']['service_attribute_3_symbol'];
        break;
      default:
        $symbol = '';
    }
    
    return $symbol;
  }

  

  /***********************************************
  *
  * Setting methods
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
    $this->cObj = $pObj->cObj;

    if( ! is_object( $pObj->drs ) )
    {
      $prompt = 'ERROR: no DRS!<br />' . PHP_EOL .
                'Sorry for the trouble.<br />' . PHP_EOL .
                'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
      
    }
    $this->drs = $pObj->drs;

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
 * @param	string		$optionType
 * @param	int		$optionId
 * @return	int
 */
  private function zz_checkOptionIsNotAvailable( $optionType, $optionId )
  {
    if
    ( 
      (
        isset( $this->conf[$optionType.'.']['options.'][$optionId.'.']['available_from'] ) 
        &&  
        ( 
          round( floatval( $this->conf[$optionType.'.']['options.'][$optionId.'.']['available_from'] ), 2 )
          > 
          round( $this->productsGross, 2 ) 
        ) 
      ) 
      ||
      (
        isset( $this->conf[$optionType.'.']['options.'][$optionId.'.']['available_until'] ) 
        &&  
        ( 
          round( floatval( $this->conf[$optionType.'.']['options.'][$optionId.'.']['available_until'] ), 2 ) 
          < 
          round( $this->productsGross, 2 ) 
        )
       )
     )
    {
      // check: fallback is given
      if (isset($this->conf[$optionType.'.']['options.'][$optionId.'.']['fallback']))
      {
        $fallback = $this->conf[$optionType.'.']['options.'][$optionId.'.']['fallback'];
        // check: fallback is defined; the availability of fallback will not tested yet
        if (isset($this->conf[$optionType.'.']['options.'][$fallback.'.']))
        {
          $newoption_id = intval($fallback);
        } else {
// 130227, dwildt, 1-
//                                  $shippingId = intval($this->conf[$optionType.'.']['preset']);
// 130227, dwildt, 1+
          $newoption_id = intval($this->conf[$optionType.'.']['preset']);
        }
      } else {
        $newoption_id = intval($this->conf[$optionType.'.']['preset']);
      }
      return $newoption_id;
    }

    return 0;
  }

 /**
  * zz_calcNet( ) : Get the net of the given gross
  *
  * @param	string		$taxType  : reduced, normal, (empty)
  * @param	double		$gross    : current gross
  * @return	double		$net      : calculated net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function zz_calcNet( $taxType, $gross )
  {
    $net        = 0.00;
    $taxDevider = 1.00;
    
    // if ($conf[$type.'.']['options.'][$option_id . '.']['tax'] == 'reduced') { // reduced tax
    switch( $taxType )
    {
      case( 'reduced' ):
        $taxDevider = $taxDevider
                    + $this->conf['tax.']['reducedCalc']
                    ;
        break;
      case( 'normal' ):
        $taxDevider = $taxDevider
                    + $this->conf['tax.']['normalCalc']
                    ;
        break;
      default:
          // gross == net
        $taxDevider = $taxDevider
                    + 0.00
                    ;
        break;
    }
    
    $net = $gross / $taxDevider;

    return $net;
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
 



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy.php']);
}
?>
