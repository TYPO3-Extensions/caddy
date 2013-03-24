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

require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  122: class tx_caddy extends tslib_pibase
 *
 *              SECTION: Caddy
 *  187:     public function caddy( )
 *  221:     private function caddyWiProducts( )
 *  343:     private function caddyWiProductsInCaseOfPaymentDEPRECATED( $contentItem )
 *  381:     private function caddyWiProductsOptions( $subpartArray, $paymentId, $shippingId, $specialIds )
 *  400:     private function caddyWiProductsOptionsPayment( $subpartArray, $paymentId )
 *  425:     private function caddyWiProductsOptionsShipping( $subpartArray, $shippingId )
 *  451:     private function caddyWiProductsOptionsSpecials( $subpartArray, $specialIds )
 *  475:     private function caddyWiProductsProductErrorMsg( $product )
 *  507:     private function caddyWiProductsProductServiceAttributes( $product )
 *  574:     private function caddyWiProductsProductSettings( $product )
 *  621:     private function caddyWoProducts( )
 *
 *              SECTION: Calc
 *  648:     public function calc( )
 *
 *              SECTION: Calculating Items
 *  708:     private function calcItems( )
 *  805:     private function calcItemsTax( $product )
 *
 *              SECTION: Calculating Options
 *  868:     private function calcOptionCosts( $optionType, $optionId )
 *  919:     private function calcOptionCostsGross( $optionType, $optionId )
 * 1002:     private function calcOptionCostsGrossByExtra( $extras, $value )
 * 1069:     private function calcOptionCostsIsFree( $optionType, $optionId )
 * 1151:     private function calcOptionsPayment( )
 * 1207:     private function calcOptionsShipping( )
 * 1263:     private function calcOptionsSpecial( )
 *
 *              SECTION: Calculation Sum
 * 1325:     private function calcSum( $items, $options )
 * 1341:     private function calcSumInitInstance( )
 *
 *              SECTION: Getting methods
 * 1364:     public function getPaymentOptionLabelBySessionId( )
 * 1390:     public function getShippingOptionLabelBySessionId( )
 * 1416:     public function getSpecialOptionLabelsBySessionId( )
 * 1447:     private function getServiceAttributes( )
 *
 *              SECTION: Init
 * 1487:     private function init( )
 * 1505:     private function initDie( )
 * 1549:     private function initInstances( )
 *
 *              SECTION: Options
 * 1596:     private function optionList( $optionType, $optionId )
 * 1678:     private function optionListCondition( $keepingTheLimit, $optionType, $conf )
 * 1712:     private function optionListConditionByType( $condition, $optionType, $conf )
 * 1753:     private function optionListConditionGross( $optionItemKey, $optionType, $conf )
 * 1777:     private function optionListConditionGrossEach( $conf )
 * 1802:     private function optionListConditionGrossOther( $optionItemKey, $optionType )
 * 1827:     private function optionListConditionNotEach( $optionType, $conf )
 * 1855:     private function optionListGrossIsKeepingTheLimit( $confOption )
 * 1915:     private function optionListMarker( $keepingTheLimit, $optionType, $optionItemKey, $optionId, $condition, $gross, $optionItemConf )
 * 1942:     private function optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionIds )
 * 1982:     private function optionListMarkerCondition( $optionType, $condition )
 * 2022:     private function optionListMarkerLabel( $optionType, $optionItemKey, $gross, $optionItemConf )
 * 2053:     private function optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId )
 * 2090:     private function optionListSymbolByExtra( $extraType )
 *
 *              SECTION: Setting methods
 * 2138:     public function setContentRow( $row )
 * 2161:     public function setParentObject( $pObj )
 * 2247:     public function setProducts( $products )
 *
 *              SECTION: ZZ
 * 2285:     private function zz_addQtynameMarker($product, $markerArray, $pObj)
 * 2323:     private function zz_addVariantGpvarToImagelinkwrap($product, $ts_key, $ts_conf, $pObj)
 * 2358:     private function zz_checkOptionIsNotAvailable( $optionType, $optionId )
 * 2416:     private function zz_calcNet( $taxType, $gross )
 * 2458:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 * 2480:     private function zz_price_format( $value )
 *
 * TOTAL FUNCTIONS: 53
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
    $caddy = array(
      'marker'    => null,
      'subparts'  => null,
      'tmpl'      => $this->tmpl
    );

    $this->init( );

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
        //$caddy = null;
        break;
    }

    return $caddy;
  }

 /**
  * caddyWiMinPriceUndercut( )  : 
  *
  * @return	string		: $content
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiMinPriceUndercut( )
  {
    $minimumRate  = floatval( $this->conf['cart.']['cartmin.']['value'] );
    $caddyMinStr  = $this->zz_price_format( $minimumRate );
    
    $tmpl           = $this->tmpl['minprice'];
    $llCaddyMinStr  = sprintf( $this->pi_getLL( 'minprice' ), $caddyMinStr );
    $minPriceArray['###ERROR_MINPRICE###']  = $llCaddyMinStr;

    $content = $this->cObj->substituteMarkerArrayCached( $tmpl, $minPriceArray );
    return $content;
  }
  
 /**
  * caddyWiProducts( )  : Workflow for a caddy, which contains products
  *
  * @return	array		: $markerArray
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProducts( )
  {
    $marker   = null;
    $subparts = null;
    $caddy    = array
    (
      'marker'    => $marker,
      'subparts'  => $subparts,
      'tmpl'      => $this->tmpl
    );

      // #45915, 130228
      // Set the hidden field to false of the powermail form
    $this->powermail->formShow( );

      // calculated caddy: content, items, options, serviceattributes, sum. 
    $calcedCaddy = $this->calc( );


//    $sesArray['paymentLabel']   = $paymentLabel;
//    $sesArray['paymentId']        = $paymentId;
//    $sesArray['productsGross']    = $productsGross;
//    $sesArray['productsNet']      = $productsNet;
//    $sesArray['optionsNet']       = $optionsNet;
//    $sesArray['optionsGross']     = $optionsGross;
//    $sesArray['shippingLabel']  = $shippingLabel;
//    $sesArray['shippingId']       = $shippingId;
//    $sesArray['specialLabels']  = $specialLabels;
//    $sesArray['specialIds']       = $specialIds;
//    $sesArray['sumGross']         = $sumGross;
//    $sesArray['sumNet']           = $sumNet;
//    $sesArray['sumTaxNormal']     = $sumTaxNormal;
//    $sesArray['sumTaxReduced']    = $sumTaxReduced;
      // session

    $minRateUndercut = $this->calcMinRateUndercut( $calcedCaddy );

      // SWITCH : product gross is undercut minimum rate
    switch( $minRateUndercut )
    {
      case( true ):
          // Set min price error
        $subparts['###MINPRICE###'] = $this->caddyWiMinPriceUndercut( );
        break;
      case( false ):
      default:
          // content. here: items
        $content                    = $calcedCaddy['content'];
        $subparts['###CONTENT###']  = $this->caddyWiProductsContent( $content );
        unset( $calcedCaddy['content'] );

          // session  : new or update 
        $this->caddyWiProductsSession( $calcedCaddy );

          // set data
          // set cObjData
        $this->zz_setDataBySession( );
          // set marker
        $marker   = ( array ) $this->caddyWiProductsSumMarkerLabels( )
                  + ( array ) $this->caddyWiProductsSumMarkerValues( )
                  ;
        $subparts = $this->caddyWiProductsOptions( $paymentId, $shippingId, $specialIds );
        break;
    }
      // SWITCH : product gross is undercut minimum rate

      // RESET cObj->data
    $caddy    = array
    (
      'marker'    => $marker,
      'subparts'  => $subparts,
      'tmpl'      => $this->tmpl
    );
var_dump( __METHOD__, __LINE__, $caddy );
die( );
    return $caddy;
  }
    
 /**
  * caddyWiProductsContent( )  : 
  *
  * @param	string		$content : current content
  * @return	string		$content : handeld content
  * @access private
  * @version    2.0.2
  * @since      2.0.0
  */
  private function caddyWiProductsContent( $content )
  {
    $content  = $content
              . $this->caddyWiProductsInCaseOfPaymentDEPRECATED( )
              . $this->caddyWiProductsFieldHidden( )
              ;
    
    return $content;
  }

 /**
  * caddyWiProductsFieldHidden( )  : 
  *
  * @return	string		$content : rendered hidden field
  * @access private
  * @version    2.0.2
  * @since      2.0.0
  */
  private function caddyWiProductsFieldHidden( )
  {
    $content = '<input type="hidden" name="tx_caddy_pi1[updateByCaddy]" value="1">';
    
    return $content;
  }

  /**
  * caddyWiProductsInCaseOfPaymentDEPRECATED( )  : Render the item (product)
  *
  * @return	string		$content : rendered item
  * @access private
  * @version    2.0.2
  * @since      2.0.0
  */
  private function caddyWiProductsInCaseOfPaymentDEPRECATED( )
  {
    
     /**
      * DEPRECATED!caddyWiProductsInCaseOfPaymentDEPRECATED( )  : Render the item (product)
      *
      * Seems to be deorecated:
      *   * $this->tmpl['special_item'] is ###CADDY_SPECIAL### ###ITEM###
      *   * Subpart ###ITEM### doesnt't contain any marke from below.
      *   
      * dwildt, 130324 
      */

    $content = null;
    
      // item for payment
    $paymentId = $this->session->paymentGet( );
    if( ! $paymentId )
    {
      return $content;
    }

      // quantity
//    $this->markerArray['###QTY###'] = 1;
    $markerArray['###QTY###'] = 1;
    
      // title
    $name   = $this->conf['options.']['payment.']['options.'][$paymentId . '.']['title'];
    $conf   = $this->conf['options.']['payment.']['options.'][$paymentId . '.']['title.'];
    $title  = $this->zz_cObjGetSingle( $name, $conf );
//    $this->markerArray['###TITLE###'] = $title;
    $markerArray['###TITLE###'] = $title;
    
      // price and prce_total
    $price  = $this->conf['options.']['payment.']['options.'][$paymentId . '.']['extra'];
//    $this->markerArray['###PRICE###']       = $price;
//    $this->markerArray['###PRICE_TOTAL###'] = $price;
    $markerArray['###PRICE###']       = $price;
    $markerArray['###PRICE_TOTAL###'] = $price;

    // add inner html to variable
    $tmpl = $this->tmpl['special_item'];
//    $this->markerArray = $markerArray + $this->markerArray;
    $content  = $this->cObj->substituteMarkerArrayCached( $tmpl, $markerArray );

    return $content;
  }

 /**
  * caddyWiProductsSumMarkerLabels( )  :
  *
  * @return	array		: $markerArray
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function caddyWiProductsSumMarkerLabels( )
  {
    $markerArray = null;
    
    $sumConf = ( array ) $this->conf['output.']['sum.']['labels.'];

    foreach( array_keys( $sumConf ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }

      $marker = '###' . strtoupper( $key ) . '###';
      $name   = $sumConf[$key];
      $conf   = $sumConf[$key . '.'];
      $value  = $this->local_cObj->cObjGetSingle( $name, $conf );
      $markerArray[$marker] = $value;

        // DRS
      if( $this->drs->drsMarker )
      {
        $prompt = $marker . ': "' . $value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
    }
    
    return $markerArray;
  }

 /**
  * caddyWiProductsSumMarkerValues( )  :
  *
  * @return	array		: $markerArray
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function caddyWiProductsSumMarkerValues( )
  {
    $markerArray = null;
    
    $sumConf = ( array ) $this->conf['output.']['sum.']['values.'];

    foreach( array_keys( $sumConf ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }

      $marker = '###' . strtoupper( $key ) . '###';
      $name   = $sumConf[$key];
      $conf   = $sumConf[$key . '.'];
      $value  = $this->local_cObj->cObjGetSingle( $name, $conf );
      $markerArray[$marker] = $value;

        // DRS
      if( $this->drs->drsMarker )
      {
        $prompt = $marker . ': "' . $value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
    }
    
    return $markerArray;
  }
  
 /**
  * caddyWiProductsOptions( )  :
  *
  * @param	integer		$paymentId    :
  * @param	integer		$shippingId   :
  * @param	integer		$specialIds   :
  * @return	array		$marker :
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function caddyWiProductsOptions( $paymentId, $shippingId, $specialIds )
  {
    $marker = array( );
    
    $marker = $marker
            + ( array ) $this->caddyWiProductsOptionsPayment(   $paymentId  )
            + ( array ) $this->caddyWiProductsOptionsShipping(  $shippingId )
            + ( array ) $this->caddyWiProductsOptionsSpecials(  $specialIds )
            ;

    return $marker;
  }

 /**
  * caddyWiProductsOptionsPayment( )  :
  *
  * @param	integer		$paymentId: ...
  * @return	array		$marker
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function caddyWiProductsOptionsPayment( $paymentId )
  {
    $marker = null;
    $paymentArray = null;

    $paymentArray['###CONTENT###'] = $this->optionList( 'payment', $paymentId );
    $marker['###PAYMENT_RADIO###'] = '';
    if( $paymentArray['###CONTENT###'] )
    {
      $marker['###PAYMENT_RADIO###'] =
        $this->cObj->substituteMarkerArrayCached( $this->tmpl['payment_all'], null, $paymentArray );
    }

    return $marker;
  }

 /**
  * caddyWiProductsOptionsShipping( )  :
  *
  * @param	integer		$shippingId: ...
  * @return	array		$marker
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function caddyWiProductsOptionsShipping( $shippingId )
  {
    $marker   = null;
    $shippingArray  = null;

      // Set shipping radio, payment radio and special checkbox
    $shippingArray['###CONTENT###'] = $this->optionList( 'shipping', $shippingId );
    $marker['###SHIPPING_RADIO###'] = '';
    if( $shippingArray['###CONTENT###'] )
    {
      $marker['###SHIPPING_RADIO###'] =
        $this->cObj->substituteMarkerArrayCached( $this->tmpl['shipping_all'], null, $shippingArray );
    }

    return $marker;
  }

 /**
  * caddyWiProductsOptionsSpecials( )  :
  *
  * @param	integer		$specialIds: ...
  * @return	array		$marker
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function caddyWiProductsOptionsSpecials( $specialIds )
  {
    $marker = null;
    $specialArray = null;

    $marker['###SPECIAL_CHECKBOX###'] = '';
    $specialArray['###CONTENT###'] = $this->optionList( 'special', $specialIds );
    if( $specialArray['###CONTENT###'] )
    {
      $marker['###SPECIAL_CHECKBOX###'] =
        $this->cObj->substituteMarkerArrayCached( $this->tmpl['special_all'], null, $specialArray );
    }

    return $marker;
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
  * caddyWiProductsSession( )  : 
  *
  * @param      array       $calcedCaddy  : 
  * @return	array       $sesArray     : the new or updated session array
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyWiProductsSession( $calcedCaddy )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $sesArray = $calcedCaddy
              + $sesArray
              ;
//    $sesArray['paymentLabel']   = $paymentLabel;
//    $sesArray['paymentId']        = $paymentId;
//    $sesArray['productsGross']    = $productsGross;
//    $sesArray['productsNet']      = $productsNet;
//    $sesArray['optionsNet']       = $optionsNet;
//    $sesArray['optionsGross']     = $optionsGross;
//    $sesArray['shippingLabel']  = $shippingLabel;
//    $sesArray['shippingId']       = $shippingId;
//    $sesArray['specialLabels']  = $specialLabels;
//    $sesArray['specialIds']       = $specialIds;
//    $sesArray['sumGross']         = $sumGross;
//    $sesArray['sumNet']           = $sumNet;
//    $sesArray['sumTaxNormal']     = $sumTaxNormal;
//    $sesArray['sumTaxReduced']    = $sumTaxReduced;
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    
    return $sesArray;
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
  * @return	array		$calc : 
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function calc( )
  {
    $this->init( );

    $calc     = null;
    $items    = null;
    $options  = null;

      // handle the current product
    $items    = $this->calcItems( );
    $content  = $items['content'];
      // handle the current product

    $this->productsGross  = $items['sum']['gross'];

      // option payment, shipping, specials
    $options = $this->calcOptions( );

      // Get the values auf the service attributes
    $serviceattributes = $this->getServiceAttributes( );

      // Get all sums (gross, net, tax.normal, tax.reduced for items, options and both (sum)
    $sum = $this->calcSum( $items, $options );

    $calc = array
    (
      'content'           => $content,
      'options'           => $options,
      'serviceattributes' => $serviceattributes,
      'sum'               => $sum,
    );

    return $calc;
  }



  /***********************************************
  *
  * Calculating Items
  *
  **********************************************/

 /**
  * calcItems( )
  *
  * @return	void
  * @access private
  * @version    2.0.2
  * @since      2.0.0
  */
  private function calcItems( )
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
    $content  = '';

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
      $content = $content . $this->cObj->substituteMarkerArrayCached
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
      $arrResult          = $this->calcItemsTax( $product );
      $productsNet        = $productsNet        + $arrResult['cartNet'];
      $productsTaxReduced = $productsTaxReduced + $arrResult['taxReduced'];
      $productsTaxNormal  = $productsTaxNormal  + $arrResult['taxNormal'];

    }
      // FOREACH  : products

    $arrReturn['content']               = $content;
    $arrReturn['sum']['net']            = $productsNet;
    $arrReturn['sum']['gross']          = $productsGross;
    $arrReturn['sum']['tax']['normal']  = $productsTaxNormal;
    $arrReturn['sum']['tax']['reduced'] = $productsTaxReduced;

    return $arrReturn;
  }

 /**
  * calcItemsTax( )
  *
  * @param	array		$product  :
  * @return	array		$tax      : cartNet, cartTaxReduced, cartTaxNormal
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcItemsTax( $product )
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
  * Calculating Minimum Rate
  *
  **********************************************/

 /**
  * calcMinRateUndercut( )  :
  *
  * @param	aray		$calcedCaddy            :
  * @return	boolean		$minimumRateIsUndercut  :
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcMinRateUndercut( $calcedCaddy )
  {
    $minimumRate            = floatval( $this->conf['cart.']['cartmin.']['value'] );
    $minimumRateIsUndercut  = false;
    if ( $minimumRate >= $calcedCaddy['sum']['items']['gross'] )
    {
      $minimumRateIsUndercut = true;
    }
    
    return $minimumRateIsUndercut;
  }
 


  /***********************************************
  *
  * Calculating Options
  *
  **********************************************/

 /**
  * calcOptionCosts( )  : Gets the gross costs for the given option
  *
  * @param	string		$optionType   : payment, shipping, special
  * @param	integer		$optionId     : current option id
  * @return	array		$optionCosts  : gross, net, rate
  * @access private
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
  * @param	string		$optionType   : payment, shipping, special
  * @param	integer		$optionId     : current option id
  * @return	array		$optionCosts  : gross, net, rate
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcOptionCostsGross( $optionType, $optionId )
  {
    $gross  = 0.00;

      // configuration of current options array
    $confOptions  = $this->conf['options.'][$optionType . '.']['options.'][$optionId . '.'];
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
    $optionCosts = $this->zz_calcNet( $taxType, $gross );

    return $optionCosts;
  }

 /**
  * calcOptionCostsGrossByExtra( ) : Gets the gross costs for the given value.
  *
  * @param	string		$extras : the extra array of the current option id
  * @param	integer		$value  : could be the number of products or the current price
  * @return	double		$gross  : the gross costs of the current option
  * @access private
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
  * @param	string		$optionType   : payment, shipping, special
  * @param	integer		$optionId    : current option id
  * @return	boolean		$optionIsFree : True, if free. False, if not free.
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcOptionCostsIsFree( $optionType, $optionId )
  {
    $optionIsFree = false;

    $confOptions = $this->conf['options.'][$optionType . '.']['options.'][$optionId . '.'];

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
  * calcOptions( ) : calculate tax, net and gross for the option payment
  *
  * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function calcOptions( )
  {
    $options = array
    (
      'payment'  => $this->calcOptionsPayment( ),
      'shipping' => $this->calcOptionsShipping( ),
      'specials' => $this->calcOptionsSpecial( )
    );
    
    return $options;
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
      $paymentId = intval( $this->conf['options.']['payment.']['preset'] );
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
    $gross      = $arrResult['gross'];
    $net        = $arrResult['net'];
    $rate       = $arrResult['rate'];

    if( $this->conf['options.']['payment.']['options.'][$paymentId . '.']['tax'] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

    $label = $this->getPaymentOptionLabelBySessionId( );

    $arrReturn['id']                    = $paymentId;
    $arrReturn['label']                 = $label;
    $arrReturn['sum']['gross']          = $gross;
    $arrReturn['sum']['net']            = $net;
    $arrReturn['sum']['rate']           = $rate;
    $arrReturn['sum']['tax']['normal']  = $taxNormal;
    $arrReturn['sum']['tax']['reduced'] = $taxReduced;
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
      $shippingId = intval( $this->conf['options.']['shipping.']['preset'] );
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
    $gross      = $arrResult['gross'];
    $net        = $arrResult['net'];
    $rate       = $arrResult['rate'];

    if( $this->conf['options.']['shipping.']['options.'][$shippingId . '.']['tax'] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

    $label = $this->getShippingOptionLabelBySessionId( );

    $arrReturn['id']                    = $shippingId;
    $arrReturn['label']                 = $label;
    $arrReturn['sum']['gross']          = $gross;
    $arrReturn['sum']['net']            = $net;
    $arrReturn['sum']['rate']           = $rate;
    $arrReturn['sum']['tax']['reduced'] = $taxReduced;
    $arrReturn['sum']['tax']['normal']  = $taxNormal;
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
    $labels      = null;

    foreach( ( array ) $specialIds as $specialId )
    {
      $arrResult  = $this->calcOptionCosts( 'special', $specialId );
      $gross      = $arrResult['gross'];
      $net        = $arrResult['net'];
      $rate       = $arrResult['rate'];

      $sumNet   = $sumNet    + $net;
      $sumGross = $sumGross  + $arrResult['gross'];
      if( $this->conf['options.']['special.']['options.'][$specialId . '.']['tax'] == 'reduced' )
      {
        $taxReduced = $taxReduced + $gross - $net;
      }
      else
      {
        $taxNormal = $taxNormal + $gross - $net;
      }
    }

    $labels = $this->getSpecialOptionLabelsBySessionId( );

    $arrReturn['ids']                   = $specialIds;
    $arrReturn['labels']                = $labels;
    $arrReturn['sum']['gross']          = $gross;
    $arrReturn['sum']['net']            = $net;
    $arrReturn['sum']['rate']           = $rate;
    $arrReturn['sum']['tax']['reduced'] = $taxReduced;
    $arrReturn['sum']['tax']['normal']  = $taxNormal;

    return $arrReturn;
  }



  /***********************************************
  *
  * Calculation Sum
  *
  **********************************************/

 /**
  * calcSum( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcSum( $items, $options )
  {
    $this->calcSumInitInstance( );
    $sum = $this->tx_caddy_calcsum->sum( $items, $options);

    return $sum;
  }

 /**
  * calcSumInitInstance( )  :
  *
  * @return	void
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function calcSumInitInstance( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';
    require_once( $path2lib . 'caddy/class.tx_caddy_calcsum.php' );
    $this->tx_caddy_calcsum = t3lib_div::makeInstance( 'tx_caddy_calcsum' );
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
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getPaymentOptionLabelBySessionId( )
  {
      // Get session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Get configuration
    $optionsConf = $this->conf['options.']['payment.']['options.'];

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
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getShippingOptionLabelBySessionId( )
  {
      // Get session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Get configuration
    $optionsConf = $this->conf['options.']['shipping.']['options.'];

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
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function getSpecialOptionLabelsBySessionId( )
  {
      // Get session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Get configuration
    $optionsConf = $this->conf['options.']['special.']['options.'];

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
  * @access private
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
    $this->session->setParentObject( $this );

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc         = t3lib_div::makeInstance( 'tx_caddy_userfunc' );

  }



  /***********************************************
  *
  * Options
  *
  **********************************************/

 /**
  * optionList( )
  *
  * @param	string		$optionType   : payment, shipping, special
  * @param	integer		$optionId     : current option id
  * @return	[type]		...
  */
  private function optionList( $optionType, $optionId )
  {
    $condition    = null;
    $optionList   = null;
    $optionItems  = ( array ) $this->conf['options.'][$optionType . '.']['options.'];

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
        case( $this->conf['options.'][$optionType . '.']['show_all_disabled'] ):
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
      $condition  = $this->optionListCondition( $keepingTheLimit, $optionType, $optionItemConf );

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
  * @param	string		$keepingTheLimit  :
  * @param	string		$optionType : payment, shipping, special
  * @param	array		$conf       : configuration of current option item
  * @return	string		$content
  */
  private function optionListCondition( $keepingTheLimit, $optionType, $conf )
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
                  . $this->optionListConditionNotEach( $optionType, $conf );
    }
    return $condition;
  }

 /**
  * optionListConditionByType( )
  *
  * @param	string		$condition  : free_from, free_until, available_from, available_until
  * @param	string		$optionType : payment, shipping, special
  * @param	array		$conf       : configuration of current option item
  * @return	string		$content
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
 * @param	string		$optionItemKey :
 * @param	string		$optionType     : payment, shipping, special
 * @param	array		$conf           : configuration of current option item
 * @return	double		$gross
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
 * @param	array		$conf   : configuration of current option item
 * @return	double		$gross
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
  * @param	string		$optionItemKey        :
  * @param	string		$optionType : payment, shipping, special
  * @return	double		$gross
  */
  private function optionListConditionGrossOther( $optionItemKey, $optionType )
  {
    $arrResult  = $this->calcOptionCosts( $optionType, intval( $optionItemKey ) );
    $gross      = $arrResult['gross'];

    $gross      = $this->zz_price_format( $gross );

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
  * @param	string		$optionItemKey        :
  * @param	string		$optionType : payment, shipping, special
  * @param	array		$conf       : configuration of current option item
  * @return	array		$arrReturn  : content, gross
  */
  private function optionListConditionNotEach( $optionType, $conf )
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

    return $content;
  }

 /**
  * optionListGrossIsKeepingTheLimit( ) : Checks, if price gross is keeping the limit for an option
  *
  * @param	array		$confOption       : configuration of the current option
  * @return	boolean		$keepingTheLimit  : True, if price gross is keeping the limit, false, if not
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
  * @param	string		$keepingTheLimit  :
  * @param	string		$optionType       : payment, shipping, special
  * @param	string		$optionItemKey    :
  * @param	integer		$optionId         : current option id
  * @param	string		$condition        :
  * @param	double		$gross            :
  * @param	[type]		$optionItemConf: ...
  * @return	void
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
  * @param	string		$keepingTheLimit  :
  * @param	string		$optionType       : payment, shipping, special
  * @param	string		$optionItemKey    :
  * @param	integer		$optionIds        : current option ids
  * @return	void
  */
  private function optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionIds )
  {
    $checked = null;

      // Enable checkbox, if id of current checkbox is part of the GP array
    if( in_array( intval( $optionItemKey ), ( array ) $optionIds ) )
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
      $prompt = '###' . $hashMarker . '_CHECKBOX###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListMarkerLabel( )
  *
  * @param	string		$optionType       : payment, shipping, special
  * @param	string		$condition        :
  * @return	void
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
      $prompt = '###' . $hashMarker . '_CONDITION###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListMarkerLabel( )
  *
  * @param	string		$keepingTheLimit  :
  * @param	string		$optionType       : payment, shipping, special
  * @param	string		$optionItemKey    :
  * @param	integer		$optionId         : current option id
  * @return	void
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
      $prompt = '###' . $hashMarker . '_TITLE###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListMarkerRadio( )
  *
  * @param	string		$keepingTheLimit  :
  * @param	string		$optionType       : payment, shipping, special
  * @param	string		$optionItemKey    :
  * @param	integer		$optionId         : current option id
  * @return	void
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
      $prompt = '###' . $hashMarker . '_RADIO###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }

 /**
  * optionListSymbolByExtra( )  : Returns the symbol depending on the extra type
  *
  * @param	string		$extraType  : te extra type
  * @return	string		$symbol     : symbold depending on extra type
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
  * @param	[type]		$$row: ...
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
  * @param	[type]		$$pObj: ...
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
      $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';
      require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
      $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
      $this->drs->pObj        = $this;
      $this->drs->row         = $this->cObj->data;
//      $prompt = 'ERROR: no DRS!<br />' . PHP_EOL .
//                'Sorry for the trouble.<br />' . PHP_EOL .
//                'TYPO3 Caddy<br />' . PHP_EOL .
//              __METHOD__ . ' (' . __LINE__ . ')';
//      die( $prompt );
    }
    else
    {
      $this->drs = $pObj->drs;
    }

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
  * @param	[type]		$$products: ...
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
        isset( $this->conf['options.'][$optionType . '.']['options.'][$optionId.'.']['available_from'] )
        &&
        (
          round( floatval( $this->conf['options.'][$optionType . '.']['options.'][$optionId.'.']['available_from'] ), 2 )
          >
          round( $this->productsGross, 2 )
        )
      )
      ||
      (
        isset( $this->conf['options.'][$optionType . '.']['options.'][$optionId.'.']['available_until'] )
        &&
        (
          round( floatval( $this->conf['options.'][$optionType . '.']['options.'][$optionId.'.']['available_until'] ), 2 )
          <
          round( $this->productsGross, 2 )
        )
       )
     )
    {
      // check: fallback is given
      if (isset($this->conf['options.'][$optionType . '.']['options.'][$optionId.'.']['fallback']))
      {
        $fallback = $this->conf['options.'][$optionType . '.']['options.'][$optionId.'.']['fallback'];
        // check: fallback is defined; the availability of fallback will not tested yet
        if (isset($this->conf['options.'][$optionType . '.']['options.'][$fallback.'.']))
        {
          $newoption_id = intval($fallback);
        } else {
// 130227, dwildt, 1-
//                                  $shippingId = intval($this->conf['options.'][$optionType . '.']['preset']);
// 130227, dwildt, 1+
          $newoption_id = intval($this->conf['options.'][$optionType . '.']['preset']);
        }
      } else {
        $newoption_id = intval($this->conf['options.'][$optionType . '.']['preset']);
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
  * @return	array		$result   : calculated net, gross, rate
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function zz_calcNet( $taxType, $gross )
  {
    $net        = 0.00;
    $rate       = 0.00;
    $taxDevider = 1.00;

    // if ($conf[$type.'.']['options.'][$option_id . '.']['tax'] == 'reduced') { // reduced tax
    switch( $taxType )
    {
      case( 'reduced' ):
        $rate = $this->conf['tax.']['reducedCalc'];
        break;
      case( 'normal' ):
        $rate = $this->conf['tax.']['normalCalc'];
        break;
      default:
        $rate = 0.00;
        break;
    }

    $taxDevider = $taxDevider
                + $rate
                ;
    $net = $gross / $taxDevider;

    $result = array
    (
      'gross' => $gross,
      'net'   => $net,
      'rate'  => $rate
    );

    return $result;
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
    if( empty ( $this->conf ) )
    {
      $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    }

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
 * zz_setDataBySession( ) : 
 *
 * @return	void
 * @access    private
 * @version 2.0.2
 * @since 2.0.2
 */
  private function zz_setDataBySession( )
  {
      // Get the current session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    
      // Implode the array to a one dimensional array
    $data  = t3lib_BEfunc::implodeTSParams( $sesArray );

    $this->local_cObj->start( $data, $this->conf['db.']['table'] );
      // cObject becomes current record

var_dump( __METHOD__, __LINE__, $this->local_cObj->data );
      // RETURN : no DRS
    if( ! $this->drs->drsCobj )
    {
      return;
    }
      // RETURN : no DRS

      // DRS
    $cObjData = var_export( $this->local_cObj->data, true );
    $prompt   = 'cObj->data: ' . $cObjData;
    t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
      // DRS
  }




}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/caddy/class.tx_caddy.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/caddy/class.tx_caddy.php']);
}
?>
