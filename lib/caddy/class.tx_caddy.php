<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 * ************************************************************* */

// #61634, 140916, dwildt, 1-
//require_once(PATH_tslib . 'class.tslib_pibase.php');
// #61634, 140916, dwildt, +
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
// Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
if ( $version < 6002000 )
{
  require_once(PATH_tslib . 'class.tslib_pibase.php');
}
// #61634, 140916, dwildt, +

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  165: class tx_caddy extends tslib_pibase
 *  243:     public function caddy( $pidCaddy=null )
 *  287:     private function caddyWiMinPriceUndercut( )
 *  308:     private function caddyWiItems( )
 *  380:     private function caddyWiItemsEval( $caddy )
 *  408:     private function caddyWiItemsEvalEpayment( )
 *  434:     private function caddyWiItemsEvalEpaymentWiPowermailCreate( )
 *  447:     private function caddyWiItemsEvalEpaymentWoPowermailCreate( )
 *  472:     private function caddyWiItemsEvalPayment( )
 *  492:     private function caddyWiItemsEvalShipping( )
 *  524:     private function caddyWiItemsEvalPrompt( $caddy, $prompts )
 *  558:     private function caddyWiItemsFieldHidden( )
 *
 *              SECTION: Caddy Marker
 *  583:     private function caddyWiItemsMarkerItems( $calcedCaddy )
 *  627:     private function caddyWiItemsMarkerItemsItem( $product )
 *  685:     private function caddyWiItemsMarkerItemsItemErrorMsg( $product )
 *  719:     private function caddyWiItemsMarkerSumLabels( )
 *  766:     private function caddyWiItemsMarkerSumTaxRates( )
 *  813:     private function caddyWiItemsMarkerSumValues( )
 *
 *              SECTION: Caddy Options
 *  871:     private function caddyWiItemsOptions( $calcedCaddy )
 *  898:     private function caddyWiItemsOptionsEpayment( $paymentId )
 *  924:     private function caddyWiItemsOptionsEpaymentWiPowermailCreate( )
 *  939:     private function caddyWiItemsOptionsEpaymentWiProvider( $paymentId )
 *  967:     private function caddyWiItemsOptionsEpaymentWoProvider( )
 *  986:     private function caddyWiItemsOptionsPayment( $paymentId )
 * 1025:     private function caddyWiItemsOptionsShipping( $shippingId )
 * 1056:     private function caddyWiItemsOptionsSpecials( $specialIds )
 * 1086:     private function caddyWiItemsItemServiceAttributes( $product )
 * 1153:     private function caddyWiItemsSetSession( $calcedCaddy )
 * 1194:     private function caddyWiItemsSetSubpartsToNull( $caddy )
 *
 *              SECTION: Caddy without items (empty)
 * 1234:     private function caddyWoItems( )
 *
 *              SECTION: Calculating
 * 1266:     private function calc( )
 *
 *              SECTION: Calculating Items
 * 1334:     private function calcItems( )
 * 1421:     private function calcItemsTax( $product )
 *
 *              SECTION: Calculating Minimum Rate
 * 1576:     private function calcMinRateUndercut( $calcedCaddy )
 *
 *              SECTION: Calculating Options
 * 1609:     private function calcOptionCosts( $optionType, $optionId )
 * 1660:     private function calcOptionCostsGross( $optionType, $optionId )
 * 1740:     private function calcOptionCostsGrossByExtra( $extras, $value )
 * 1807:     private function calcOptionCostsIsFree( $optionType, $optionId )
 * 1889:     private function calcOptions( )
 * 1921:     private function calcOptionsEpayment( $paymentId )
 * 1953:     private function calcOptionsPayment( )
 * 2010:     private function calcOptionsShipping( )
 * 2068:     private function calcOptionsSpecials( )
 *
 *              SECTION: Calculation Sum
 * 2132:     private function calcSum( $items, $options )
 * 2148:     private function calcSumInitInstance( )
 *
 *              SECTION: Getting methods
 * 2173:     private function getEpaymentProvider( )
 * 2208:     private function getPaymentOptionLabelById( $paymentId )
 * 2240:     private function getPid( $pid )
 * 2261:     private function getServiceAttributes( )
 * 2294:     private function getShippingOptionLabelById( $paymentId )
 * 2326:     private function getSpecialOptionLabelsById( $specialIds )
 *
 *              SECTION: Init
 * 2377:     private function init( )
 * 2396:     private function initDie( )
 * 2442:     private function initEpaymentMethods( )
 * 2469:     private function initInstances( )
 *
 *              SECTION: Options
 * 2516:     private function optionList( $optionType, $optionId )
 *
 *              SECTION: Options Conditions
 * 2619:     private function optionListCondition( $keepingTheLimit, $optionType, $conf )
 * 2653:     private function optionListConditionByType( $condition, $optionType, $conf )
 * 2694:     private function optionListConditionGross( $optionItemKey, $optionType, $conf )
 * 2720:     private function optionListConditionGrossEach( $conf )
 * 2747:     private function optionListConditionGrossOther( $optionItemKey, $optionType )
 * 2774:     private function optionListConditionNotEach( $optionType, $conf )
 * 2805:     private function optionListGrossIsKeepingTheLimit( $confOption )
 * 2859:     private function optionListLabel( $optionItemConf )
 *
 *              SECTION: Options Marker
 * 2887:     private function optionListMarker( $keepingTheLimit, $optionType, $optionItemKey, $optionId, $condition, $optionItemConf )
 * 2938:     private function optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionIds )
 * 2985:     private function optionListMarkerCondition( $optionType, $condition )
 * 3027:     private function optionListMarkerLabel( $optionType, $optionItemKey, $optionItemConf )
 * 3066:     private function optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId )
 * 3111:     private function optionListSymbolByExtra( $extraType )
 * 3150:     private function powermailInActionCreate( )
 *
 *              SECTION: Setting methods
 * 3191:     public function setContentRow( $row )
 * 3214:     public function setParentObject( $pObj )
 * 3244:     private function setParentObjectConf( )
 * 3267:     private function setParentObjectCObj( )
 * 3290:     private function setParentObjectDrs( )
 * 3315:     private function setParentObjectLocalCObj( )
 * 3338:     private function setParentObjectTemplate( )
 * 3362:     public function setProducts( $products )
 *
 *              SECTION: ZZ
 * 3400:     private function zz_addQtynameMarker($product, $markerArray, $pObj)
 * 3438:     private function zz_addVariantGpvarToImagelinkwrap($product, $ts_key, $ts_conf, $pObj)
 * 3473:     private function zz_checkOptionIsNotAvailable( $optionType, $optionId )
 * 3531:     private function zz_calcNet( $taxType, $gross )
 * 3577:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 * 3599:     private function zz_price_format( $value )
 * 3637:     private function zz_setData( $data, $table )
 * 3665:     private function zz_setDataBySession( )
 *
 * TOTAL FUNCTIONS: 86
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	6.0.0
 * @since       2.0.0
 */
class tx_caddy extends tslib_pibase
{

  public $extKey = 'caddy';
  public $prefixId = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  private $numberOfItems = 0;
  private $caddyServiceAttribute1Max = 0;
  private $caddyServiceAttribute1Sum = 0;
  private $caddyServiceAttribute2Max = 0;
  private $caddyServiceAttribute2Sum = 0;
  private $caddyServiceAttribute3Max = 0;
  private $caddyServiceAttribute3Sum = 0;
  private $productsGross = 0;
  // #55333, 140125, dwildt, 4+
  private $optionsEpaymentHtml = null;
  private $optionsPaymentAreEmpty = false;
  private $optionsShippingAreEmpty = false;
  private $optionsSpecialsAreEmpty = false;
  // [array] current typoscript configuration
  public $conf = null;
  private $markerArray = array();
  // [object] parent DRS object
  private $calc = null;
  public $drs = null;
  private $userfunc = null;
  // [object] parent object
  private $pObj = null;
  public $cObj = null;
  private $local_cObj = null;
  public $pidCaddy = null;
  // [object] parent powermail object
  private $powermail = null;
  // [object] parent session object
  private $session = null;
  private $products = array();
  private $outerMarkerArray = array();
  // [array] current tt_content row or current pi_flexform row
  private $row = null;
  private $tmpl = null;
  private $smarkerArray = null;

  /*   * *********************************************
   *
   * Caddy
   *
   * ******************************************** */

  /**
   * caddy( )  : Returns a caddy with HTML form and HTML options among others
   *
   * @param      integer : $pidCaddy   : uid of the page, which contains the caddy plugin
   * @return	array	: $arrReturn  : array with elements caddy, tmpl, outerMarkerArray
   * @access public
   * @version    3.0.1
   * @since      2.0.0
   */
  // #54634, 131128, dwildt, 1-
  //public function caddy( )
  // #54634, 131128, dwildt, 1+
  public function caddy( $pidCaddy = null )
  {
    $caddy = array(
      'marker' => null,
      'subparts' => null,
      'tmpl' => null
    );

    // #54628, 131229, dwildt, 1+
    $this->pidCaddy = $pidCaddy;

    $this->init();

    // get products from session
    // #54634, 131128, dwildt, 1-
    //$this->products = $this->session->productsGet( );
    // #54634, 131128, dwildt, 1+
    $this->products = $this->session->productsGet( $pidCaddy );

    switch ( true )
    {
      case( count( $this->products ) > 0 ):
//var_dump( __METHOD__, __LINE__, $this->products );
        $caddy = $this->caddyWiItems();
//var_dump( __METHOD__, __LINE__ , $caddy ) ;
        break;
      case(!( count( $this->products ) > 0 ) ):
      default:
        $caddy = $this->caddyWoItems();
        //$caddy = null;
        break;
    }

    return $caddy;
  }

  /**
   * caddyWiItemsCashdiscount( )  :
   *
   * @param	array		$calcedCaddy  :
   * @return	array		$marker       :
   * @access private
   * @version    4.0.8
   * @since      4.0.8
   */
  private function caddyWiItemsCashdiscount( $calcedCaddy )
  {
//var_dump( __METHOD__, __LINE__, $calcedCaddy );
    if ( ( double ) $calcedCaddy[ 'sum' ][ 'cashdiscount' ][ 'sum' ][ 'gross' ] < 0.00 )
    {
      return;
    }

    $marker = array(
      '###CASHDISCOUNT###' => null
    );

    return $marker;
  }

  /**
   * caddyWiMinPriceUndercut( )  :
   *
   * @return	string		: $content
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiMinPriceUndercut()
  {
    $minimumRate = floatval( $this->conf[ 'api.' ][ 'conditions.' ][ 'limits.' ][ 'items.' ][ 'gross.' ][ 'min' ] );
    $caddyMinStr = $this->zz_price_format( $minimumRate );

    $tmpl = $this->tmpl[ 'minprice' ];
    $llCaddyMinStr = sprintf( $this->pi_getLL( 'minprice' ), $caddyMinStr );
    $minPriceArray[ '###ERROR_MINPRICE###' ] = $llCaddyMinStr;

    $content = $this->cObj->substituteMarkerArrayCached( $tmpl, $minPriceArray );
    return $content;
  }

  /**
   * caddyWiItems( )  : Workflow for a caddy, which contains products
   *
   * @return	array		: $markerArray
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiItems()
  {
    $marker = null;
    $subparts = null;
    $caddy = array
      (
      'marker' => $marker,
      'subparts' => $subparts,
      'tmpl' => null
    );

    // #45915, 130228
    // Set the hidden field to false of the powermail form
    $this->powermail->formShow();

    // calculated caddy: content, items, options, serviceattributes, sum.
    $calcedCaddy = $this->calc();

    // Is min rate for gross undercut?
    $minRateUndercut = $this->calcMinRateUndercut( $calcedCaddy );

    // SWITCH : product gross is undercut minimum rate
    switch ( $minRateUndercut )
    {
      case( true ):
        // Set min price error
        $subparts[ '###MINPRICE###' ] = $this->caddyWiMinPriceUndercut();
        break;
      case( false ):
      default:
        // session  : new or update
        $this->caddyWiItemsSetSession( $calcedCaddy );

        // set data
        // set cObjData
        $this->zz_setDataBySession();
        // set marker
        $marker = ( array ) $this->caddyWiItemsMarkerSumLabels() + ( array ) $this->caddyWiItemsMarkerSumTaxRates() + ( array ) $this->caddyWiItemsMarkerSumValues()
        ;
        $subparts = ( array ) $this->caddyWiItemsMarkerItems( $calcedCaddy ) + ( array ) $this->caddyWiItemsOptions( $calcedCaddy ) + ( array ) $this->caddyWiItemsCashdiscount( $calcedCaddy )
        ;
        break;
    }
    // SWITCH : product gross is undercut minimum rate

    $caddy = array
      (
      'marker' => $marker,
      'subparts' => $subparts,
      'tmpl' => $this->tmpl[ 'all' ]
    );
//var_dump( __METHOD__, __LINE__, $caddy );
//die( );
    // Remove subparts, if they aren't needed
    $caddy = $this->caddyWiItemsSetSubpartsToNull( $caddy );
    // Hide the powermail form (by css), if caddy doesn't match the requirements for an order
    $caddy = $this->caddyWiItemsEval( $caddy );

    return $caddy;
  }

  /**
   * caddyWiItemsEval( )  : Hide the powermail form (by css), if caddy doesn't match the requirements for an order
   *
   * @param	array		$caddy    :
   * @return	array		$caddy    :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEval( $caddy )
  {
    $prompts = array();

    $prompt = $this->caddyWiItemsEvalPayment();
    if ( $prompt )
    {
      $prompts[] = $prompt;
    }
    $prompt = $this->caddyWiItemsEvalShipping();
    if ( $prompt )
    {
      $prompts[] = $prompt;
    }

    $caddy = $this->caddyWiItemsEvalPrompt( $caddy, $prompts );

    return $caddy;
  }

  /**
   * caddyWiItemsEvalEpayment( )  :
   *
   * @return	string		$prompt  :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEvalEpayment()
  {
    $prompt = null;

    switch ( true )
    {
      case( $this->powermailInActionCreate() ):
        $prompt = $this->caddyWiItemsEvalEpaymentWiPowermailCreate();
        break;
      default:
        $prompt = $this->caddyWiItemsEvalEpaymentWoPowermailCreate();
        break;
    }

    return $prompt;
  }

  /**
   * caddyWiItemsEvalEpaymentWiPowermailCreate( )  :
   *
   * @return	string		$prompt  :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEvalEpaymentWiPowermailCreate()
  {
    return;
  }

  /**
   * caddyWiItemsEvalEpaymentWoPowermailCreate( )  :
   *
   * @return	string		$prompt  :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEvalEpaymentWoPowermailCreate()
  {
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
    $paymentId = $sesArray[ 'payment' ];
    //var_dump( __METHOD__, __LINE__, $paymentId, $sesArray['payment'] );
    // RETURN : a payment method is selected
    if ( $paymentId > 0 )
    {
      return;
    }

    // RETURN : a prompt, because customer hasn't select any payment method
    return $this->pi_getLL( 'paymentEnterMethod' );
  }

  /**
   * caddyWiItemsEvalPayment( )  : Evaluate payment method. Returns a prompt,
   *                               if customer hasn't select any payment method
   *
   * @return	string		$prompt  :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEvalPayment()
  {
    // RETURN : there aren't any payment options
    if ( $this->optionsPaymentAreEmpty )
    {
      return;
    }

    return $this->caddyWiItemsEvalEpayment();
  }

  /**
   * caddyWiItemsEvalShipping( ) : Evaluate shipping method. Returns a prompt,
   *                               if customer hasn't select any shipping method
   *
   * @return	string		$prompt  :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEvalShipping()
  {
    // RETURN : there aren't any shipping options
    if ( $this->optionsShippingAreEmpty )
    {
      return;
    }

    // Get the current payment method
    $calcedCaddy = $this->calc();
    $shippingId = $calcedCaddy[ 'options' ][ 'shipping' ][ 'id' ];

    // RETURN : a shipping method is selected
    if ( $shippingId > 0 )
    {
      return;
    }

    // RETURN : a prompt, because customer hasn't select any shipping method
    return $this->pi_getLL( 'shippingEnterMethod' );
  }

  /**
   * caddyWiItemsEvalPrompt( )  :
   *
   * @param	array		$caddy    :
   * @param	array		$prompts  :
   * @return	array		$caddy    :
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsEvalPrompt( $caddy, $prompts )
  {
    // RETURN : Remove subpart CADDY_REQUIREMENTS
    if ( empty( $prompts ) )
    {
      $caddy[ 'tmpl' ] = $this->pObj->cObj->substituteSubpart( $caddy[ 'tmpl' ], '###CADDY_REQUIREMENTS###', null );
      return $caddy;
    }

    // Hide the powermail form
    $this->powermail->formHide();
    //var_dump( __METHOD__, __LINE__ );
    // Generate the prompt
    //$prompt = '<ul><li>' . implode( '</li><li>', $prompts ) . '</li></ul>';
    $prompt = implode( '<br />', $prompts );
    $marker[ '###PROMPT###' ] = $prompt;
    // Handle the subpart marker
    $subpart = $this->local_cObj->getSubpart( $caddy[ 'tmpl' ], '###CADDY_REQUIREMENTS###' );
    $subpart = $this->local_cObj->substituteMarkerArrayCached( $subpart, $marker );
    $caddy[ 'tmpl' ] = $this->pObj->cObj->substituteSubpart( $caddy[ 'tmpl' ], '###CADDY_REQUIREMENTS###', $subpart );

    // RETURN : caddy with perompt and without powermail form
    return $caddy;
  }

  /**
   * caddyWiItemsFieldHidden( )  :
   *
   * @return	string		$content : rendered hidden field
   * @access private
   * @version    2.0.2
   * @since      2.0.0
   */
  private function caddyWiItemsFieldHidden()
  {
    $content = '<input type="hidden" name="tx_caddy_pi1[updateByCaddy]" value="1">';

    return $content;
  }

  /*   * *********************************************
   *
   * Caddy Marker
   *
   * ******************************************** */

  /**
   * caddyWiItemsMarkerItems( )
   *
   * @param	array		$calcedCaddy  :
   * @param	array		$markerArray  :
   * @return	array		$markerArray  :
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiItemsMarkerItems( $calcedCaddy )
  {
    // FOREACH  : item
    foreach ( ( array ) $calcedCaddy[ 'items' ] as $item )
    {
      // cObject become current record
      //$this->zz_setData( $itemConf, $this->conf['db.']['table'] );
      $this->zz_setData( $item, $this->conf[ 'db.' ][ 'table' ] );

      // update product settings
      $markerArray = ( array ) null + ( array ) $this->caddyWiItemsMarkerItemsItem( $item ) + ( array ) $this->caddyWiItemsMarkerItemsItemErrorMsg( $item )
      ;

      // add inner html to variable
      $content = $content
              . $this->cObj->substituteMarkerArrayCached
                      (
                      $this->tmpl[ 'item' ], $markerArray
              )
      ;
    }
    // FOREACH  : item

    $marker = array
      (
      '###CONTENT###' => $content,
    );

    return $marker;
  }

  /**
   * caddyWiItemsMarkerItemsItem( )
   *
   * @param	array		$product :
   * @return	array		$markerArray
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiItemsMarkerItemsItem( $product )
  {

    $markerArray = null;

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = 'Configuration by marker.item. ...';
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 1 );
    }
    // DRS
//var_dump( __METHOD__, __LINE__, $this->local_cObj->data );
    // FOREACH  : settings property
    foreach ( array_keys( ( array ) $this->conf[ 'api.' ][ 'marker.' ][ 'item.' ] ) as $key )
    {
      if ( stristr( $key, '.' ) )
      {
        continue;
      }

      $name = $this->conf[ 'api.' ][ 'marker.' ][ 'item.' ][ $key ];
      $conf = $this->conf[ 'api.' ][ 'marker.' ][ 'item.' ][ $key . '.' ];

      if ( $key == 'delete' )
      {
        $conf = $this->zz_addVariantGpvarToImagelinkwrap( $product, $name, $conf, $this );
      }

      $marker = '###' . strtoupper( $key ) . '###';
      $value = $this->local_cObj->cObjGetSingle( $name, $conf );
      $markerArray[ $marker ] = $value;

      // DRS
      if ( $this->drs->drsMarker )
      {
        $prompt = 'Product - ' . $marker . ': "' . $value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      // adds the ###QTY_NAME### marker in case of variants
      $markerArray = $this->zz_addQtynameMarker( $product, $markerArray, $this );
    }
    // FOREACH  : settings property

    return $markerArray;
  }

  /**
   * caddyWiItemsMarkerItemsItemErrorMsg( ) :
   *
   * @param	array		$product      : the current item / product
   * @return	array		$markerArray  :
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiItemsMarkerItemsItemErrorMsg( $product )
  {
    $prompt = null;
    $markerArray = null;

    // FOREACH  : error messages per product
    foreach ( ( array ) $product[ 'error' ] as $productError )
    {
      if ( !$productError )
      {
        continue;
      }

      $prompt = $prompt
              . $this->cObj->substituteMarker( $this->tmpl[ 'item_error' ], '###ERROR_PROMPT###', $productError );
    }
    // FOREACH  : error messages per product

    if ( $prompt )
    {
      $markerArray[ '###ITEM_ERROR###' ] = $prompt;
    }

    return $markerArray;
  }

  /**
   * caddyWiItemsMarkerSumLabels( )  :
   *
   * @return	array		: $markerArray
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsMarkerSumLabels()
  {
    $markerArray = null;

    $sumConf = ( array ) $this->conf[ 'api.' ][ 'marker.' ][ 'sum.' ][ 'labels.' ];

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = 'Configuration by marker.sum.labels. ...';
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 1 );
    }
    // DRS

    foreach ( array_keys( $sumConf ) as $key )
    {
      if ( stristr( $key, '.' ) )
      {
        continue;
      }

      $marker = '###' . strtoupper( $key ) . '###';
      $name = $sumConf[ $key ];
      $conf = $sumConf[ $key . '.' ];
      $value = $this->local_cObj->cObjGetSingle( $name, $conf );
      $markerArray[ $marker ] = $value;

      // DRS
      if ( $this->drs->drsMarker )
      {
        $prompt = $marker . ': "' . $value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
    }

    return $markerArray;
  }

  /**
   * caddyWiItemsMarkerSumTaxRates( )  :
   *
   * @return	array		: $markerArray
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsMarkerSumTaxRates()
  {
    $markerArray = null;

    $sumConf = ( array ) $this->conf[ 'api.' ][ 'marker.' ][ 'sum.' ][ 'rates.' ];

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = 'Configuration by marker.sum.rates. ...';
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 1 );
    }
    // DRS

    foreach ( array_keys( $sumConf ) as $key )
    {
      if ( stristr( $key, '.' ) )
      {
        continue;
      }

      $marker = '###' . strtoupper( $key ) . '###';
      $name = $sumConf[ $key ];
      $conf = $sumConf[ $key . '.' ];
      $value = $this->local_cObj->cObjGetSingle( $name, $conf );
      $markerArray[ $marker ] = $value;

      // DRS
      if ( $this->drs->drsMarker )
      {
        $prompt = $marker . ': "' . $value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
    }

    return $markerArray;
  }

  /**
   * caddyWiItemsMarkerSumValues( )  :
   *
   * @return	array		: $markerArray
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsMarkerSumValues()
  {
    $markerArray = null;

    $sumConf = ( array ) $this->conf[ 'api.' ][ 'marker.' ][ 'sum.' ][ 'values.' ];

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = 'Configuration by marker.sum.values. ...';
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 1 );
    }
    // DRS

    foreach ( array_keys( $sumConf ) as $key )
    {
      if ( stristr( $key, '.' ) )
      {
        continue;
      }

      $marker = '###' . strtoupper( $key ) . '###';
      $name = $sumConf[ $key ];
      $conf = $sumConf[ $key . '.' ];
      $value = $this->local_cObj->cObjGetSingle( $name, $conf );
      $markerArray[ $marker ] = $value;

      // DRS
      if ( $this->drs->drsMarker )
      {
        $prompt = $marker . ': "' . $value . '"';
        t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
    }

    return $markerArray;
  }

  /*   * *********************************************
   *
   * Caddy Options
   *
   * ******************************************** */

  /**
   * caddyWiItemsOptions( )  :
   *
   * @param	integer		$calcedCaddy    :
   * @return	array		$marker :
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsOptions( $calcedCaddy )
  {
    $marker = array();

    $paymentId = $calcedCaddy[ 'options' ][ 'payment' ][ 'id' ];
    $paymentId = $this->session->paymentGet( $this->pidCaddy );
    $shippingId = $calcedCaddy[ 'options' ][ 'shipping' ][ 'id' ];
    $specialIds = $calcedCaddy[ 'options' ][ 'specials' ][ 'ids' ];

    $marker = $marker + ( array ) $this->caddyWiItemsOptionsPayment( $paymentId ) + ( array ) $this->caddyWiItemsOptionsShipping( $shippingId ) + ( array ) $this->caddyWiItemsOptionsSpecials( $specialIds )
    ;

    if ( ( double ) $calcedCaddy[ 'sum' ][ 'options' ][ 'gross' ] > 0.00 )
    {
      return $marker;
    }

    $marker[ '###OPTIONCOSTS###' ] = null;

    return $marker;
  }

  /**
   * caddyWiItemsOptionsEpayment( )  :
   *
   * @param	integer		$paymentId: ...
   * @return	boolean
   * @access private
   * @internal   #53678
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsOptionsEpayment( $paymentId )
  {
    if ( $this->caddyWiItemsOptionsEpaymentWoProvider() )
    {
      return false;
    }

    if ( $this->caddyWiItemsOptionsEpaymentWiPowermailCreate() )
    {
      return false;
    }

    $this->caddyWiItemsOptionsEpaymentWiProvider( $paymentId );

    return true;
  }

  /**
   * caddyWiItemsOptionsEpaymentWiPowermailCreate( )  :
   *
   * @return	void
   * @access private
   * @internal   #53678
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsOptionsEpaymentWiPowermailCreate()
  {
    return $this->powermailInActionCreate();
  }

  /**
   * caddyWiItemsOptionsEpaymentWiProvider( )  :
   *
   * @param	integer		$paymentId: ...
   * @return	void
   * @access private
   * @internal   #53678
   * @version    4.0.6
   * @since      4.0.5
   */
  private function caddyWiItemsOptionsEpaymentWiProvider( $paymentId )
  {
    $provider = $this->getEpaymentProvider();
    $content = null;

    switch ( $provider )
    {
      case( 'Paymill' ):
        $arrResult = $this->epaymentMethods->main( $paymentId, $this->pidCaddy );
        $content = $arrResult[ 'content' ];
        break;
      default:
        $content = 'e-payment by undefined: ' . $provider;
        break;
    }

    unset( $paymentId );
    $this->optionsEpaymentHtml = $content;
  }

  /**
   * caddyWiItemsOptionsEpaymentWoProvider( )  :
   *
   * @return	boolean
   * @access private
   * @internal   #53678
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsOptionsEpaymentWoProvider()
  {
    if ( !$this->getEpaymentProvider() )
    {
      return true;
    }

    return false;
  }

  /**
   * caddyWiItemsOptionsPayment( )  :
   *
   * @param	integer		$paymentId: ...
   * @return	array		$marker
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsOptionsPayment( $paymentId )
  {
    $marker = null;
    $paymentArray = null;

    // #53678, 140126, dwildt, 5+
    if ( $this->caddyWiItemsOptionsEpayment( $paymentId ) )
    {
      // Html will handled by $this->optionsEpaymentHtml
      return null;
    }

    $paymentArray[ '###CONTENT###' ] = $this->optionList( 'payment', $paymentId );
    $marker[ '###PAYMENT_RADIO###' ] = '';
    if ( $paymentArray[ '###CONTENT###' ] )
    {
      $marker[ '###PAYMENT_RADIO###' ] = $this->cObj->substituteMarkerArrayCached( $this->tmpl[ 'payment_all' ], null, $paymentArray );
    }

    // #55333, 140125, dwildt, 4+
    if ( !$paymentArray[ '###CONTENT###' ] )
    {
      $this->optionsPaymentAreEmpty = true;
    }


    return $marker;
  }

  /**
   * caddyWiItemsOptionsShipping( )  :
   *
   * @param	integer		$shippingId: ...
   * @return	array		$marker
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsOptionsShipping( $shippingId )
  {
    $marker = null;
    $shippingArray = null;

    // Set shipping radio, payment radio and special checkbox
    $shippingArray[ '###CONTENT###' ] = $this->optionList( 'shipping', $shippingId );
    $marker[ '###SHIPPING_RADIO###' ] = '';
    if ( $shippingArray[ '###CONTENT###' ] )
    {
      $marker[ '###SHIPPING_RADIO###' ] = $this->cObj->substituteMarkerArrayCached( $this->tmpl[ 'shipping_all' ], null, $shippingArray );
    }
    // #55333, 140125, dwildt, 4+
    if ( !$shippingArray[ '###CONTENT###' ] )
    {
      $this->optionsShippingAreEmpty = true;
    }

    return $marker;
  }

  /**
   * caddyWiItemsOptionsSpecials( )  :
   *
   * @param	integer		$specialIds: ...
   * @return	array		$marker
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function caddyWiItemsOptionsSpecials( $specialIds )
  {
    $marker = null;
    $specialArray = null;

    $marker[ '###SPECIALS_CHECKBOX###' ] = '';
    $specialArray[ '###CONTENT###' ] = $this->optionList( 'specials', $specialIds );
    if ( $specialArray[ '###CONTENT###' ] )
    {
      $marker[ '###SPECIALS_CHECKBOX###' ] = $this->cObj->substituteMarkerArrayCached( $this->tmpl[ 'specials_all' ], null, $specialArray );
    }
    // #55333, 140125, dwildt, 4+
    if ( !$specialArray[ '###CONTENT###' ] )
    {
      $this->optionsSpecialsAreEmpty = true;
    }

    return $marker;
  }

  /**
   * caddyWiItemsItemServiceAttributes( )
   *
   * @param	array		$product :
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiItemsItemServiceAttributes( $product )
  {
    // DRS
    if ( $this->drs->drsTodo )
    {
      $prompt = 'Unproper formula? In case of an exceeded maximum of service attributes, max will updated, sum won\'t!';
      t3lib_div::devlog( '[WARN/TODO] ' . $prompt, $this->extKey, 3 );
      $prompt = 'The developer has to check the formula.';
      t3lib_div::devlog( '[HELP/TODO] ' . $prompt, $this->extKey, 1 );
    }
    // DRS

    $this->caddyServiceAttribute1Sum = $this->caddyServiceAttribute1Sum + (
            $product[ 'service_attribute_1' ] * $product[ 'qty' ]
            )
    ;
    if ( $this->caddyServiceAttribute1Max > $product[ 'service_attribute_1' ] )
    {
      $this->caddyServiceAttribute1Max = $this->caddyServiceAttribute1Max;
    }
    else
    {
      $this->caddyServiceAttribute1Max = $product[ 'service_attribute_1' ];
    }

    $this->caddyServiceAttribute2Sum = $this->caddyServiceAttribute2Sum + (
            $product[ 'service_attribute_2' ] * $product[ 'qty' ]
            )
    ;
    if ( $this->caddyServiceAttribute2Max > $product[ 'service_attribute_2' ] )
    {
      $this->caddyServiceAttribute2Max = $this->caddyServiceAttribute2Max;
    }
    else
    {
      $this->caddyServiceAttribute2Max = $product[ 'service_attribute_2' ];
    }

    $this->caddyServiceAttribute3Sum = $this->caddyServiceAttribute3Sum + (
            $product[ 'service_attribute_3' ] * $product[ 'qty' ]
            )
    ;
    if ( $this->caddyServiceAttribute3Max > $product[ 'service_attribute_3' ] )
    {
      $this->caddyServiceAttribute3Max = $this->caddyServiceAttribute3Max;
    }
    else
    {
      $this->caddyServiceAttribute3Max = $product[ 'service_attribute_3' ];
    }
  }

  /**
   * caddyWiItemsSetSession( )  :
   *
   * @param	array		$calcedCaddy  :
   * @return	array		$sesArray     : the new or updated session array
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWiItemsSetSession( $calcedCaddy )
  {
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
    $sesArray = ( array ) $calcedCaddy + ( array ) $sesArray
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
    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray );
    // #54634, 131229, dwildt, 1+
//var_dump( __METHOD__, __LINE__, $sesArray['e-payment'] );
    $GLOBALS[ 'TSFE' ]->fe_user->setKey( 'ses', $this->extKey . '_' . $this->pidCaddy, $sesArray );

    return $sesArray;
  }

  /**
   * caddyWiItemsSetSubpartsToNull( )  : Remove subparts, if they aren't needed
   *
   * @param	array		$caddy  :
   * @return	array		$caddy  :
   * @access private
   * @internal   #55333
   * @version    4.0.5
   * @since      4.0.5
   */
  private function caddyWiItemsSetSubpartsToNull( $caddy )
  {
    // Remove ###OPTIONS###, if shipping options and special options are empty
    if ( $this->optionsShippingAreEmpty && $this->optionsSpecialsAreEmpty )
    {
      $caddy[ 'tmpl' ] = $this->pObj->cObj->substituteSubpart( $caddy[ 'tmpl' ], '###OPTIONS###', null );
    }

    // Remove ###PAYMENT_METHOD###, if payment options are empty
    if ( $this->optionsPaymentAreEmpty )
    {
      $caddy[ 'tmpl' ] = $this->pObj->cObj->substituteSubpart( $caddy[ 'tmpl' ], '###PAYMENT_METHOD###', null );
    }

    // Remove ###OPTIONS###, if payment options are empty
    if ( $this->optionsEpaymentHtml )
    {
      $caddy[ 'tmpl' ] = $this->pObj->cObj->substituteSubpart( $caddy[ 'tmpl' ], '###PAYMENT_METHOD_CONTENT###', $this->optionsEpaymentHtml );
    }

    return $caddy;
  }

  /*   * *********************************************
   *
   * Caddy without items (empty)
   *
   * ******************************************** */

  /**
   * caddyWoItems( )  : Render a caddy, which doesn't contain any product
   *
   * @return	arry
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function caddyWoItems()
  {
    // #45915, 130228
    // Set the hidden field to true of the powermail form
    $this->powermail->formHide();

    $caddy = array
      (
      'marker' => null,
      'subparts' => null,
      'tmpl' => $this->tmpl[ 'empty' ]
    );

    return $caddy;
  }

  /*   * *********************************************
   *
   * Calculating
   *
   * ******************************************** */

  /**
   * calc( )  :
   *
   * @return	array		$calc :
   * @access public
   * @version    4.0.5
   * @since      2.0.0
   */
  private function calc()
  {
    // 140128, dwildt, +
    if ( $this->calc !== null )
    {
      return $this->calc;
    }

    $this->init();

    $calc = null;
    $calcedItems = null;
    $options = null;

    // handle the current product
    $calcedItems = $this->calcItems();
//    $content      = $calcedItems['content'];
    // handle the current product

    $this->productsGross = $calcedItems[ 'sum' ][ 'gross' ];

    // option payment, shipping, specials
    $options = $this->calcOptions();

    // Get the values auf the service attributes
    $serviceattributes = $this->getServiceAttributes();

    // Get all sums (gross, net, tax.normal, tax.reduced for items, options and both (sum)
    $sum = $this->calcSum( $calcedItems, $options );

    $calc = array
      (
//      'content'           => $content,
      'items' => $calcedItems[ 'items' ],
      'options' => $options,
      'serviceattributes' => $serviceattributes,
      'sum' => $sum,
    );

    // 130628, dwildt, 2+
    $sumOneDim = t3lib_BEfunc::implodeTSParams( $sum, 'sum' );

    $calc = array_merge( $calc, $sumOneDim );
//var_dump( __METHOD__, __LINE__, $sumOneDim, $calc );
//exit;
    // 140128, dwildt, 1+
    $this->calc = $calc;

    return $calc;
  }

  /*   * *********************************************
   *
   * Calculating Items
   *
   * ******************************************** */

  /**
   * calcItems( )
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.0
   */
  private function calcItems()
  {
    // DIE  : $row is empty
    if ( empty( $this->products ) )
    {
      $prompt = 'ERROR: there isn\'t any product!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    // DIE  : $row is empty

    $calcedItems = null;
    $items = null;

    $productsNet = 0.00;
    $productsGross = 0.00;
    $productsTaxReduced = 0.00;
    $productsTaxNormal = 0.00;

    // FOREACH  : products
//var_dump( __METHOD__, __LINE__, $this->product );
    foreach ( ( array ) $this->products as $product )
    {
      // calculate tax
      $product = $this->calcItemsTax( $product );

//        // cObject become current record
//      $this->zz_setData( $product, $this->conf['db.']['table'] );
//
//        // update product settings
//      $markerArray  = ( array ) null
//                    + ( array ) $this->caddyWiItemsMarkerItemsItem( $product )
//                    + ( array ) $this->caddyWiItemsMarkerItemsItemErrorMsg( $product )
//                    ;
//
//         // add inner html to variable
//      $content  = $content
//                . $this->cObj->substituteMarkerArrayCached
//                  (
//                    $this->tmpl['item'], $markerArray
//                  )
//                ;
      // update product gross
      $productsGross = $productsGross + $product[ 'sumgross' ]
      ;
      $productsNet = $productsNet + $product[ 'sumnet' ]
      ;
      $productsTaxNormal = $productsTaxNormal + $product[ 'taxNormal' ]
      ;
      $productsTaxReduced = $productsTaxReduced + $product[ 'taxReduced' ]
      ;
      // update number of products
      $this->numberOfItems = $this->numberOfItems + $product[ 'qty' ];

      // update service attributes
      $this->caddyWiItemsItemServiceAttributes( $product );
      $items[] = $product;
    }
    // FOREACH  : products
//    $calcedItems['content']               = $content;
    $calcedItems[ 'items' ] = $items;
    $calcedItems[ 'sum' ][ 'gross' ] = $productsGross;
    $calcedItems[ 'sum' ][ 'net' ] = $productsNet;
    $calcedItems[ 'sum' ][ 'tax' ][ 'normal' ] = $productsTaxNormal;
    $calcedItems[ 'sum' ][ 'tax' ][ 'reduced' ] = $productsTaxReduced;

    return $calcedItems;
  }

  /**
   * calcItemsTax( )
   *
   * @param	array		$product  :
   * @return	array		$tax      : cartNet, cartTaxReduced, cartTaxNormal
   * @access private
   * @version    2.0.10
   * @since      2.0.0
   */
  private function calcItemsTax( $product )
  {
    // calculate gross total
    $product[ 'sumgross' ] = $product[ 'gross' ] * $product[ 'qty' ]
    ;
    // DRS
    if ( $this->drs->drsFormula )
    {
      $prompt = $product[ 'title' ] . ': ' . $product[ 'gross' ] . ' x ' . $product[ 'qty' ] . ' = ' . $product[ 'sumgross' ];
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // #49430, 130628, dwildt, +
    if ( $product[ 'tax' ] == 'reduced' )
    {
      $product[ 'tax' ] = 1;
      if ( $this->drs->drsError )
      {
        $prompt = 'tax == reduced. This is an error! tax ist set to 1.';
        t3lib_div::devlog( '[ERROR/DEVELOPMENT] ' . $prompt, $this->extKey, 3 );
      }
    }
    if ( $product[ 'tax' ] == 'normal' )
    {
      $product[ 'tax' ] = 2;
      if ( $this->drs->drsError )
      {
        $prompt = 'tax == normal. This is an error! tax ist set to 2.';
        t3lib_div::devlog( '[ERROR/DEVELOPMENT] ' . $prompt, $this->extKey, 3 );
      }
    }
    // #49430, 130628, dwildt, +

    switch ( $product[ 'tax' ] )
    {
      case( 0 ):
        $product[ 'taxrate' ] = 0.00;
        // #50045, dwildt, 2-
//        $product['sumnet']  = 0.00;
//        $product['sumtax']  = 0.00;
        break;
      case( 7.00 ):  // :TODO: 7.00
      case( 1 ):
      case( $this->conf[ 'api.' ][ 'tax.' ][ 'reducedCalc' ] ):
        $product[ 'taxrate' ] = $this->conf[ 'api.' ][ 'tax.' ][ 'reducedCalc' ];
        // #50045, dwildt, 3-
//        $product['sumnet']      = $product['sumgross'] / ( 1 + $product['taxrate'] );
//        $product['sumtax']      = $product['sumnet'] * $product['taxrate'];
//        $product['taxReduced']  = $product['sumtax'];
        break;
      case( 2 ):
      case( $this->conf[ 'api.' ][ 'tax.' ][ 'normalCalc' ] ):
        $product[ 'taxrate' ] = $this->conf[ 'api.' ][ 'tax.' ][ 'normalCalc' ];
        // #50045, dwildt, 3-
//        $product['sumnet']    = $product['sumgross'] / ( 1 + $product['taxrate'] );
//        $product['sumtax']    = $product['sumnet'] * $product['taxrate'];
//        $product['taxNormal'] = $product['sumtax'];
        break;
      default:
        echo '<div style="border:2em solid red;padding:2em;color:red;">
                <h1 style="color:red;">
                  caddy Error
                </h1>
                <p>
                  tax is "' . $product[ 'tax' ] . '".<br />
                    This is an undefined value in class.tx_caddy.php. ABORT!<br />
                    <br />
                    Are you sure, that you included the caddy static template?
                </p>
                <p>
                  Method: ' . __METHOD__ . ' at line #' . __LINE__ . '
                </p>
                <p>
                  Sorry for the trouble.<br />
                  TYPO3 Caddy
                </p>
              </div>';
        exit;
    }

    // #50045, dwildt, 7+
//    $net                  = $product['gross'] / ( 1 + $product['taxrate'] );
//    $product['net']       = round( $net, 2 );
//    $product['sumnet']    = $product['net'] * $product['qty'];
//    $product['sumgross']  = $product['sumnet'] * ( 1 + $product['taxrate'] );
//    $product['sumtax']    = $product['sumgross'] - $product['sumnet'];
    $product[ 'sumnet' ] = round( ( $product[ 'sumgross' ] / ( 1 + $product[ 'taxrate' ] ) ), 2 );
    $product[ 'sumtax' ] = $product[ 'sumgross' ] - $product[ 'sumnet' ];
    // #50045, dwildt, 7+
    // #50045, dwildt, +
    switch ( $product[ 'tax' ] )
    {
      case( 0 ):
        // Do nothing
        break;
      case( 7.00 ):  // :TODO: 7.00
      case( 1 ):
      case( $this->conf[ 'api.' ][ 'tax.' ][ 'reducedCalc' ] ):
        $product[ 'taxReduced' ] = $product[ 'sumtax' ];
        break;
      case( 2 ):
      case( $this->conf[ 'api.' ][ 'tax.' ][ 'normalCalc' ] ):
        $product[ 'taxNormal' ] = $product[ 'sumtax' ];
        break;
      default:
      default:
        echo '<div style="border:2em solid red;padding:2em;color:red;">
                <h1 style="color:red;">
                  caddy Error
                </h1>
                <p>
                  tax is "' . $product[ 'tax' ] . '".<br />
                    This is an undefined value in class.tx_caddy.php. ABORT!<br />
                    <br />
                    Are you sure, that you included the caddy static template?
                </p>
                <p>
                  Method: ' . __METHOD__ . ' at line #' . __LINE__ . '
                </p>
                <p>
                  Sorry for the trouble.<br />
                  TYPO3 Caddy
                </p>
              </div>';
        exit;
    }
    // #50045, dwildt, +

    $product[ 'net' ] = round( ( $product[ 'sumnet' ] / $product[ 'qty' ] ), 2 );

    // price netto

    return $product;
  }

  /*   * *********************************************
   *
   * Calculating Minimum Rate
   *
   * ******************************************** */

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
    $minimumRate = floatval( $this->conf[ 'api.' ][ 'conditions.' ][ 'limits.' ][ 'items.' ][ 'gross.' ][ 'min' ] );
    $minimumRateIsUndercut = false;
    // 130721, dwildt, 1-
    //if ( $minimumRate >= $calcedCaddy['sum']['items']['gross'] )
    // 130721, dwildt, 1+
    if ( $minimumRate > $calcedCaddy[ 'sum' ][ 'items' ][ 'gross' ] )
    {
      $minimumRateIsUndercut = true;
    }

    return $minimumRateIsUndercut;
  }

  /*   * *********************************************
   *
   * Calculating Options
   *
   * ******************************************** */

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
      'net' => 0.00
    );

    // DRS
    if ( $this->drs->drsFormula )
    {
      $prompt = $optionType . '.options.' . $optionId;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    switch ( $this->calcOptionCostsIsFree( $optionType, $optionId ) )
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
    if ( $this->drs->drsFormula )
    {
      $gross = $optionCosts[ 'gross' ];
      $net = $optionCosts[ 'net' ];
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
   * @version    4.0.5
   * @since      2.0.2
   */
  private function calcOptionCostsGross( $optionType, $optionId )
  {
    $gross = 0.00;

    // configuration of current options array
    $confOptions = $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ];
//      // #55333
//    $name         = $confOptions['extra'];
//    $conf         = $confOptions['extra.'];
//    $extra        = $this->zz_cObjGetSingle( $name, $conf );
//    $extras       = $confOptions['extras.'];
    $extra = $confOptions[ 'extra' ];
    $extras = $confOptions[ 'extra.' ];
    $taxType = $confOptions[ 'tax' ];


    // DRS
    if ( $this->drs->drsFormula )
    {
      $prompt = 'extraType: ' . $extras;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      $prompt = 'taxType: ' . $taxType;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // SWITCH : extra costs
    switch ( $extras )
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
        $gross = floatval( $extras[ '1.' ][ 'extra' ] ) * $this->numberOfItems
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
    $limit = null;
    $gross = null;
    $valueIsGreaterOrEqual = false;
    $valueIsSmaller = false;

    foreach ( $extras as $extra )
    {
      // floatval, because value could be a double
      $limit = floatval( $extra[ 'value' ] );
      $value = floatval( $value );
      $valueIsGreaterOrEqual = ( $limit <= $value );
      $valueIsSmaller = ( $limit > $value );

      // SWITCH : overrun limit
      switch ( true )
      {
        case( $valueIsGreaterOrEqual ):
          // limit is overrun, take the gross costs of the current limit
          $gross = $extra[ 'extra' ];
          // DRS
          if ( $this->drs->drsFormula )
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
          if ( $this->drs->drsFormula )
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

    $confOptions = $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ];

    $free_from = $confOptions[ 'free_from' ];
    $free_to = $confOptions[ 'free_until' ];

    $freeFromTo = $free_from . $free_to;

    // RETURN : there is neither a from nor a to
    if ( empty( $freeFromTo ) )
    {
      // DRS
      if ( $this->drs->drsFormula )
      {
        $prompt = 'free_from and free_until isn\'t set.';
        t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      }
      // DRS

      return $optionIsFree;
    }
    // RETURN : there is neither a from nor a to

    $limitFrom = floatval( $free_from );
    $limitTo = floatval( $free_to );

    // DRS
    if ( $this->drs->drsFormula )
    {
      $prompt = 'Limit is from ' . $limitFrom . ' to ' . $limitTo;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
      $prompt = 'Value is  ' . $this->productsGross;
      t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    $valueIsGreaterOrEqualThanFrom = ( $limitFrom <= $this->productsGross );
    $valueIsSmallerOrEqualThanTo = ( $limitTo >= $this->productsGross );
    // #i0051, 140108, dwildt, 4+
    if ( empty( $limitTo ) )
    {
      $valueIsSmallerOrEqualThanTo = true;
    }

    switch ( true )
    {
      case( $valueIsGreaterOrEqualThanFrom && $valueIsSmallerOrEqualThanTo ):
        $optionIsFree = true;
        // DRS
        if ( $this->drs->drsFormula )
        {
          $prompt = 'value is keeping the limit.';
          t3lib_div::devlog( '[INFO/FORMULA] ' . $prompt, $this->extKey, 0 );
        }
        // DRS
        break;
      default;
        // DRS
        if ( $this->drs->drsFormula )
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
   * @version    4.0.3
   * @since      2.0.0
   */
  private function calcOptions()
  {
    $options = array
      (
      'payment' => $this->calcOptionsPayment(),
      'shipping' => $this->calcOptionsShipping(),
      'specials' => $this->calcOptionsSpecials()
    );

    return $options;
  }

  /**
   * calcOptionsEpayment( ) :
   *
   * @param	[type]		$$paymentId: ...
   * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
   * @access private
   * @internal   #53678
   * @version    4.0.5
   * @since      4.0.5
   */
  private function calcOptionsEpayment( $paymentId )
  {
    $arrReturn = null;

    switch ( $provider = $this->getEpaymentProvider() )
    {
      case( 'Paymill' ):
        $label = 'e-payment by Paymill';
        break;
      default:
        $label = 'e-payment by undefined: ';
        break;
    }
    $arrReturn[ 'id' ] = $paymentId;
    $arrReturn[ 'label' ] = $label;
    $arrReturn[ 'sum' ][ 'gross' ] = 0;
    $arrReturn[ 'sum' ][ 'net' ] = 0;
    $arrReturn[ 'sum' ][ 'rate' ] = 0;
    $arrReturn[ 'sum' ][ 'tax' ][ 'normal' ] = 0;
    $arrReturn[ 'sum' ][ 'tax' ][ 'reduced' ] = 0;

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
  private function calcOptionsPayment()
  {
    $arrReturn = null;
    $paymentId = $this->session->paymentGet( $this->pidCaddy );

    $gross = 0.00;
    $net = 0.00;
    $taxReduced = 0.00;
    $taxNormal = 0.00;

    if ( !$paymentId )
    {
      $paymentId = intval( $this->conf[ 'api.' ][ 'options.' ][ 'payment.' ][ 'preset' ] );
      $this->session->paymentUpdate( $paymentId, $this->pidCaddy );
    }
    // check if selected payment option is available
    $newpaymentId = $this->zz_checkOptionIsNotAvailable( 'payment', $paymentId );
    if ( $newpaymentId )
    {
      $paymentId = $newpaymentId;
      $this->session->paymentUpdate( $newpaymentId, $this->pidCaddy );
    }

    $arrResult = $this->calcOptionCosts( 'payment', $paymentId );
    $gross = $arrResult[ 'gross' ];
    $net = $arrResult[ 'net' ];
    $rate = $arrResult[ 'rate' ];

    if ( $this->conf[ 'api.' ][ 'options.' ][ 'payment.' ][ 'options.' ][ $paymentId . '.' ][ 'tax' ] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

    $label = $this->getPaymentOptionLabelById( $paymentId );

    $arrReturn[ 'id' ] = $paymentId;
    $arrReturn[ 'label' ] = $label;
    $arrReturn[ 'sum' ][ 'gross' ] = $gross;
    $arrReturn[ 'sum' ][ 'net' ] = $net;
    $arrReturn[ 'sum' ][ 'rate' ] = $rate;
    $arrReturn[ 'sum' ][ 'tax' ][ 'normal' ] = $taxNormal;
    $arrReturn[ 'sum' ][ 'tax' ][ 'reduced' ] = $taxReduced;
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
  private function calcOptionsShipping()
  {
    $arrReturn = null;

    $shippingId = $this->session->shippingGet( $this->pidCaddy );

    $gross = 0.00;
    $net = 0.00;
    $taxReduced = 0.00;
    $taxNormal = 0.00;

    if ( !$shippingId )
    {
      $shippingId = intval( $this->conf[ 'api.' ][ 'options.' ][ 'shipping.' ][ 'preset' ] );
      $this->session->shippingUpdate( $shippingId, $this->pidCaddy );
    }
    // check if selected shipping option is available
    $newshippingId = $this->zz_checkOptionIsNotAvailable( 'shipping', $shippingId );
    if ( $newshippingId )
    {
      $shippingId = $newshippingId;
      $this->session->shippingUpdate( $newshippingId, $this->pidCaddy );
    }

    $arrResult = $this->calcOptionCosts( 'shipping', $shippingId );
    $gross = $arrResult[ 'gross' ];
    $net = $arrResult[ 'net' ];
    $rate = $arrResult[ 'rate' ];

    if ( $this->conf[ 'api.' ][ 'options.' ][ 'shipping.' ][ 'options.' ][ $shippingId . '.' ][ 'tax' ] == 'reduced' )
    {
      $taxReduced = $gross - $net;
    }
    else
    {
      $taxNormal = $gross - $net;
    }

    $label = $this->getShippingOptionLabelById( $shippingId );

    $arrReturn[ 'id' ] = $shippingId;
    $arrReturn[ 'label' ] = $label;
    $arrReturn[ 'sum' ][ 'gross' ] = $gross;
    $arrReturn[ 'sum' ][ 'net' ] = $net;
    $arrReturn[ 'sum' ][ 'rate' ] = $rate;
    $arrReturn[ 'sum' ][ 'tax' ][ 'reduced' ] = $taxReduced;
    $arrReturn[ 'sum' ][ 'tax' ][ 'normal' ] = $taxNormal;
    return $arrReturn;
  }

  /**
   * calcOptionsSpecials( ) : calculate tax, net and gross for the option special
   *
   * @return	array		$array : cartTaxReduced, cartTaxNormal, id, gross, net
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function calcOptionsSpecials()
  {
    $arrReturn = null;

    $specialIds = $this->session->specialGet( $this->pidCaddy );

    $gross = 0.00;
    $net = 0.00;
    $sumGross = 0.00;
    $sumNet = 0.00;
    $taxReduced = 0.00;
    $taxNormal = 0.00;
    $labels = null;

    foreach ( ( array ) $specialIds as $specialId )
    {
      $arrResult = $this->calcOptionCosts( 'specials', $specialId );
      $gross = $arrResult[ 'gross' ];
      $net = $arrResult[ 'net' ];
      $rate = $arrResult[ 'rate' ];

      $sumNet = $sumNet + $net;
      $sumGross = $sumGross + $arrResult[ 'gross' ];
      if ( $this->conf[ 'api.' ][ 'options.' ][ 'specials.' ][ 'options.' ][ $specialId . '.' ][ 'tax' ] == 'reduced' )
      {
        $taxReduced = $taxReduced + $gross - $net;
      }
      else
      {
        $taxNormal = $taxNormal + $gross - $net;
      }
    }

    $labels = $this->getSpecialOptionLabelsById( $specialIds );

    $arrReturn[ 'ids' ] = $specialIds;
    $arrReturn[ 'labels' ] = $labels;
    $arrReturn[ 'sum' ][ 'gross' ] = $gross;
    $arrReturn[ 'sum' ][ 'net' ] = $net;
    $arrReturn[ 'sum' ][ 'rate' ] = $rate;
    $arrReturn[ 'sum' ][ 'tax' ][ 'reduced' ] = $taxReduced;
    $arrReturn[ 'sum' ][ 'tax' ][ 'normal' ] = $taxNormal;

    return $arrReturn;
  }

  /*   * *********************************************
   *
   * Calculation Sum
   *
   * ******************************************** */

  /**
   * calcSum( )  :
   *
   * @param	[type]		$$items: ...
   * @param	[type]		$options: ...
   * @return	array		:
   * @access private
   * @version    4.08
   * @since      2.0.2
   */
  private function calcSum( $items, $options )
  {
    $this->calcSumInitInstance();
    $sum = $this->tx_caddy_calcsum->sum( $items, $options, $this->conf );

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
  private function calcSumInitInstance()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';
    require_once( $path2lib . 'caddy/class.tx_caddy_calcsum.php' );
    $this->tx_caddy_calcsum = t3lib_div::makeInstance( 'tx_caddy_calcsum' );
    $this->tx_caddy_calcsum->initPidCaddy( $this->pidCaddy );
  }

  /*   * *********************************************
   *
   * Getting methods
   *
   * ******************************************** */

  /**
   * getEpaymentProvider( ) :
   *
   * @return	string		$provider :
   * @access private
   * @internal   #53678
   * @version    4.0.5
   * @since      4.0.5
   */
  private function getEpaymentProvider()
  {
    $provider = $this->conf[ 'api.' ][ 'e-payment.' ][ 'provider' ];

    switch ( $provider )
    {
      case( 'Paymill' ):
        // Follow the workflow;
        break;
      case( null ):
      case( false ):
        // Don't do anything
        return;
      default:
        $prompt = 'Fatal error: undefined e-payment provider "' . $provider . '"<br />'
                . 'Method ' . __METHOD__ . ' at line ' . __LINE__ . ' <br />'
                . 'Sorry for the trouble<br />'
                . 'Caddy - your TYPO3 shopping cart<br />'
        ;
        die( $prompt );
        break;
    }

    return $provider;
  }

  /**
   * getPaymentOptionLabelById( )
   *
   * @param	[type]		$$paymentId: ...
   * @return	array
   * @access private
   * @version    4.0.3
   * @since      2.0.0
   */
  private function getPaymentOptionLabelById( $paymentId )
  {
//      // Get session array
//    // #54634, 131128, dwildt, 1-
//    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
//    // #54634, 131128, dwildt, 1+
//    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
    // Get configuration
    $optionsConf = $this->conf[ 'api.' ][ 'options.' ][ 'payment.' ][ 'options.' ];

//      // Get key for the option
//    $key    = $sesArray['options']['payment']['id'] . '.';
    // #i0043, 140103, dwildt, 1+
    $key = $paymentId . '.';

    // Render the option label
    $name = $optionsConf[ $key ][ 'title' ];
    $conf = $optionsConf[ $key ][ 'title.' ];
    $value = $this->zz_cObjGetSingle( $name, $conf );
    return $value;
  }

  /**
   * getPid( )  : Returns the globlas tsfe id, if the given pid is null
   *
   * @param	integer		$pid  : given pid (may be null)
   * @return	integer		$pid  : id of the page with the caddy plugin
   * @internal    #54634
   * @version     4.0.3
   * @since       3.0.1
   */
  private function getPid( $pid )
  {
    // #i0045, 140103, dwildt, 1-
    //if( $pid === null )
    // #i0045, 140103, dwildt, 1+
    if ( $pid === null || $pid === '' )
    {
      $pid = $GLOBALS[ "TSFE" ]->id;
    }

    return $pid;
  }

  /**
   * getServiceAttributes( )
   *
   * @return	array
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function getServiceAttributes()
  {
    $arrResult = array
      (
      '1' => array
        (
        'max' => $this->caddyServiceAttribute1Max,
        'sum' => $this->caddyServiceAttribute1Sum
      ),
      '2' => array
        (
        'max' => $this->caddyServiceAttribute2Max,
        'sum' => $this->caddyServiceAttribute2Sum
      ),
      '3' => array
        (
        'max' => $this->caddyServiceAttribute3Max,
        'sum' => $this->caddyServiceAttribute3Sum
      )
    );

    return $arrResult;
  }

  /**
   * getShippingOptionLabelById( )
   *
   * @param	integer		: $pid  : uid of the page, which contains the caddy plugin
   * @return	array
   * @access private
   * @version    4.0.3
   * @since      2.0.0
   */
  private function getShippingOptionLabelById( $shippingId )
  {
//      // Get session array
//    // #54634, 131128, dwildt, 1-
//    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
//    // #54634, 131128, dwildt, 1+
//    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
    // Get configuration
    $optionsConf = $this->conf[ 'api.' ][ 'options.' ][ 'shipping.' ][ 'options.' ];

//      // Get key for option
//    $key    = $sesArray['options']['shipping']['id'] . '.';
    // #i0043, 140103, dwildt, 1+
    $key = $shippingId . '.';

    // Render the option label
    $name = $optionsConf[ $key ][ 'title' ];
    $conf = $optionsConf[ $key ][ 'title.' ];
    $value = $this->zz_cObjGetSingle( $name, $conf );
    return $value;
  }

  /**
   * getSpecialOptionLabelsById( )
   *
   * @param	[type]		$$specialIds: ...
   * @return	array
   * @access private
   * @version    4.0.3
   * @since      2.0.0
   */
  private function getSpecialOptionLabelsById( $specialIds )
  {
    $value = null;
    $values = null;

//      // Get session array
//    // #54634, 131128, dwildt, 1-
//    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
//    // #54634, 131128, dwildt, 1+
//    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
    // Get the devider
    $name = $this->conf[ 'api.' ][ 'options.' ][ 'specials.' ][ 'devider' ];
    $conf = $this->conf[ 'api.' ][ 'options.' ][ 'specials.' ][ 'devider.' ];
    $devider = $this->zz_cObjGetSingle( $name, $conf );
    // Get the devider
    // Get options configuration
    $optionsConf = $this->conf[ 'api.' ][ 'options.' ][ 'specials.' ][ 'options.' ];

    // Render the option label
    // #i0043, 140103, dwildt, 1-
//    foreach( ( array ) $sesArray['options']['specials']['ids'] as $key )
    // #i0043, 140103, dwildt, 1+
    foreach ( ( array ) $specialIds as $key )
    {
      $name = $optionsConf[ $key . '.' ][ 'title' ];
      $conf = $optionsConf[ $key . '.' ][ 'title.' ];
      $values[] = $this->zz_cObjGetSingle( $name, $conf );
    }

    $value = implode( $devider, ( array ) $values );
    return $value;
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
   * @version    2.0.0
   * @since      2.0.0
   */
  private function init()
  {
    $this->pi_loadLL();

    // DIE  : if pObj or row isn't initiated
    $this->initDie();

    $this->initInstances();
    $this->initEpaymentMethods();
  }

  /**
   * initDie( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initDie()
  {
    // DIE  : $row is empty
    if ( !is_array( $this->row ) || empty( $this->row ) )
    {
      $prompt = 'ERROR: row is empty or isn\'t an array!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    // DIE  : $row is empty
    // DIE  : $pObj isn't initiated
    if ( !is_object( $this->pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    // DIE  : $pObj isn't initiated
    // DIE  : $local_cObj isn't initiated
    if ( !is_object( $this->local_cObj ) )
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
   * initEpaymentMethods( ) : Requires and initiate the class of the current e-payment provider.
   *                   If no e-payment provider is enabled, nothing will happen.
   *
   * @return	void
   * @access private
   * @internal   #53678
   * @version    4.0.5
   * @since      4.0.5
   */
  private function initEpaymentMethods()
  {
    $provider = $this->getEpaymentProvider();

    if ( !$provider )
    {
      return;
    }

    // Path to the provider class
    $provider = strtolower( $provider );
    $path2provider = t3lib_extMgm::extPath( 'caddy' ) . 'lib/e-payment/' . $provider . '/class.tx_caddy_' . $provider . '_paymentMethods.php';

    // Initiate the provider class
    require_once( $path2provider );
    $this->epaymentMethods = t3lib_div::makeInstance( 'tx_caddy_' . $provider . '_paymentMethods' );
    $this->epaymentMethods->setParentObject( $this );
  }

  /**
   * initInstances( )
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initInstances()
  {
    if ( !( $this->initInstances === null ) )
    {
      return;
    }

    $this->initInstances = true;

    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    if ( is_object( $this->pObj->powermail ) )
    {
      $this->powermail = $this->pObj->powermail;
    }
    else
    {
      // DANGEROUS: todo: powermail must init!
      require_once( $path2lib . 'powermail/class.tx_caddy_powermail.php' );
      $this->powermail = t3lib_div::makeInstance( 'tx_caddy_powermail' );
      $this->powermail->pObj = $this;
    }

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session = t3lib_div::makeInstance( 'tx_caddy_session' );
    $this->session->setParentObject( $this );

    require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
    $this->userfunc = t3lib_div::makeInstance( 'tx_caddy_userfunc' );
  }

  /*   * *********************************************
   *
   * Options
   *
   * ******************************************** */

  /**
   * optionList( )
   *
   * @param	string		$optionType   : payment, shipping, special
   * @param	integer		$optionId     : current option id
   * @return	[type]		...
   */
  private function optionList( $optionType, $optionId )
  {
    $condition = null;
    $optionList = null;
    $optionItems = ( array ) $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ];
    $marker = null;

    // DRS
    if ( $this->drs->drsOptions )
    {
      $prompt = $optionType . '.options.' . $optionId;
      t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // LOOP each option item
    foreach ( $optionItems as $optionItemKey => $optionItemConf )
    {
      if ( !stristr( $optionItemKey, '.' ) )
      {
        continue;
      }

      // #55333, 140125, dwildt, ~
      // Render enabled options only
      switch ( true )
      {
        case( $optionItemConf[ 'enabled' ] === null ):
        case( $optionItemConf[ 'enabled' ] == '1' ):
          // Follow the workflow
          break;
        case( $optionItemConf[ 'enabled' ] == '0' ):
        default:
          continue 2;
          break;
      }
      // Render enabled options only
      // DRS
      if ( $this->drs->drsOptions )
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
      switch ( true )
      {
        case( $keepingTheLimit ):
        case( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'show_all_disabled' ] ):
          break;
        default:
          // DRS
          if ( $this->drs->drsOptions )
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
      $condition = $this->optionListCondition( $keepingTheLimit, $optionType, $optionItemConf );

      // Set the marker array
      $marker = $this->optionListMarker( $keepingTheLimit, $optionType, $optionItemKey, $optionId, $condition, $optionItemConf );

      // render the option item list
      $tmpl = $this->tmpl[ $optionType . '_item' ];
      $optionList = $optionList
              . $this->cObj->substituteMarkerArrayCached( $tmpl, $marker );
      // render the option item list
    }
    // LOOP each option item

    return $optionList;
  }

  /*   * *********************************************
   *
   * Options Conditions
   *
   * ******************************************** */

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

    $condition = $condition
            . $this->optionListConditionByType( 'free_from', $optionType, $conf );

    $condition = $condition
            . $this->optionListConditionByType( 'free_until', $optionType, $conf );

    if ( !$keepingTheLimit )
    {
      $condition = $condition
              . $this->optionListConditionByType( 'available_from', $optionType, $conf );
      $condition = $condition
              . $this->optionListConditionByType( 'available_until', $optionType, $conf );
    }

    if ( $conf[ 'extras' ] != 'each' )
    {
      $condition = $condition
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
    if ( !isset( $conf[ $condition ] ) )
    {
      // DRS
      if ( $this->drs->drsOptions )
      {
        $prompt = 'condition ' . $condition . ' isn\'t set.';
        t3lib_div::devlog( '[INFO/OPTIONS] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
      return $content;
    }
    // RETURN : no value
    // marker
    $gross = $this->zz_price_format( $conf[ $condition ] );
    $llLabel = $optionType . '_' . $condition;
    $llLabel = $this->pi_getLL( $llLabel );
    $marker[ '###CONDITION###' ] = $llLabel . ' ' . $gross;
    // marker
    // content
    $content = $this->tmpl[ $optionType . '_condition_item' ];
    $content = $this->cObj->substituteMarkerArrayCached( $content, $marker );
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

    switch ( true )
    {
      case( $conf[ 'extras' ] != 'each' ):
        $gross = $this->optionListConditionGrossOther( $optionItemKey, $optionType );
        break;
      case( $conf[ 'extras' ] == 'each' ):
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
   * @version   4.0.5
   * @since     2.0.0
   */
  private function optionListConditionGrossEach( $conf )
  {
//      // #55333
//    $gross    = $this->zz_price_format( $conf['extras.']['1.']['extra'] );
    $gross = $this->zz_price_format( $conf[ 'extra.' ][ '1.' ][ 'extra' ] );
    $llLabel = $this->pi_getLL( 'specials_each' );

    $gross = sprintf( $llLabel, $gross );

    // DRS
    if ( $this->drs->drsOptions )
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
    $arrResult = $this->calcOptionCosts( $optionType, intval( $optionItemKey ) );
    $gross = $arrResult[ 'gross' ];

    $gross = $this->zz_price_format( $gross );

    // DRS
    if ( $this->drs->drsOptions )
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
   * @version    4.0.5
   * @since      2.0.0
   */
  private function optionListConditionNotEach( $optionType, $conf )
  {
    $content = null;
    $gross = null;

//      // #55333
//    $unit   = $this->optionListSymbolByExtra( $conf['extras'] );
//    $extras = ( array ) $conf['extras.'];
    $unit = $this->optionListSymbolByExtra( $conf[ 'extra' ] );
    $extras = ( array ) $conf[ 'extra.' ];

    foreach ( $extras as $extra )
    {
      $gross = $this->zz_price_format( $extra[ 'extra' ] );
      $llLabel = $this->pi_getLL( 'service_from' );
      $tmpl = $this->tmpl[ $optionType . '_condition_item' ];

      $marker[ '###CONDITION###' ] = $llLabel . ' ' . $extra[ 'value' ] . ' ' . $unit . ' : ' . $gross;
      $content = $content
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
    $keepingTheLimit = true;
    $gross = round( $this->productsGross, 2 );

    // By default: gross is keeping the limit
    $grossIsSmallerThanFrom = false;
    $grossIsGreaterThanTo = false;

    // IF : available from
    if ( isset( $confOption[ 'available_from' ] ) )
    {
      $limitFrom = round( floatval( $confOption[ 'available_from' ] ), 2 );
      if ( $limitFrom > $gross )
      {
        $grossIsSmallerThanFrom = true;
      }
    }
    // IF : available from
    // IF : available to
    if ( isset( $confOption[ 'available_until' ] ) )
    {
      $limitTo = round( floatval( $confOption[ 'available_until' ] ), 2 );
      if ( $limitTo < $gross )
      {
        $grossIsGreaterThanTo = true;
      }
    }
    // IF : available to
    // SWITCH : keeping the limit
    switch ( true )
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
   * optionListLabel( )
   *
   * @param	array		$optionItemConf :
   * @return	string		$label          :
   */
  private function optionListLabel( $optionItemConf )
  {
    $label = $this->zz_cObjGetSingle( $optionItemConf[ 'title' ], $optionItemConf[ 'title.' ] );

    return $label;
  }

  /*   * *********************************************
   *
   * Options Marker
   *
   * ******************************************** */

  /**
   * optionListMarker( )
   *
   * @param	string		$keepingTheLimit  :
   * @param	string		$optionType       : payment, shipping, special
   * @param	string		$optionItemKey    :
   * @param	integer		$optionId         : current option id
   * @param	string		$condition        :
   * @param	array		$optionItemConf   : ...
   * @return	array		$marker           :
   * @version  4.0.9
   * @since    2.0.0
   */
  private function optionListMarker( $keepingTheLimit, $optionType, $optionItemKey, $optionId, $condition, $optionItemConf )
  {
    $marker = null;

    switch ( true )
    {
      case( $optionType != 'specials' ):
        // #54832, 140108, dwildt, 1-
        //$marker = $this->optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId );
        // #54832, 140108, dwildt, 1+
        $input = $this->optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId );
        break;
      case( $optionType == 'specials' ):
      default:
        // #54832, 140108, dwildt, 1-
        //$marker = $this->optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionId );
        // #54832, 140108, dwildt, 1+
        $input = $this->optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionId );
        break;
    }

    // #54832, 140108, dwildt, 2+
    $label = $this->optionListMarkerLabel( $optionType, $optionItemKey, $optionItemConf );
    $element = str_replace( '|', $input, $label );
//var_dump( __METHOD__, __LINE__, $element );
    // #54832, 140108, dwildt, 4-
//    $marker = ( array ) $marker
//            + ( array ) $this->optionListMarkerLabel( $optionType, $optionItemKey, $optionItemConf )
//            + ( array ) $this->optionListMarkerCondition( $optionType, $condition )
//
//                    ;
    // #i0051, 140317, dwildt, 4-
//    // #54832, 140108, dwildt, 3+
//    $marker = (array) $element
//            + (array) $this->optionListMarkerCondition($optionType, $condition)
//    ;
    // #i0051, 140317, dwildt, 4+
    $condition = $this->optionListMarkerCondition( $optionType, $condition );
    $hash_key = '###' . strtoupper( $optionType ) . '_CONDITION###';
    $element = str_replace( '###CONDITION###', $condition[ $hash_key ], $element );
//var_dump( __METHOD__, __LINE__, $optionType, $condition, $element );

    $marker = ( array ) $element;

    return $marker;
  }

  /**
   * optionListMarkerCheckbox( )
   *
   * @param	string		$keepingTheLimit  :
   * @param	string		$optionType       : payment, shipping, special
   * @param	string		$optionItemKey    :
   * @param	integer		$optionIds        : current option ids
   * @return	string		$content          :
   * @version  4.0.4
   * @since    2.0.0
   */
  private function optionListMarkerCheckbox( $keepingTheLimit, $optionType, $optionItemKey, $optionIds )
  {
    $checked = null;

    // Enable checkbox, if id of current checkbox is part of the GP array
    if ( in_array( intval( $optionItemKey ), ( array ) $optionIds ) )
    {
      $checked = ' checked="checked"';
    }

    $disabled = null;
    if ( !$keepingTheLimit )
    {
      $disabled = ' disabled="disabled"';
    }

    $hashMarker = strtoupper( $optionType );

    $content = '<input class="onChangeloadCaddyByAjax" type="checkbox" onchange="this.form.submit()" name="tx_caddy_pi1[' . $optionType . '][]" '
            . 'id="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '" '
            . 'value="' . intval( $optionItemKey ) . '"' . $checked . $disabled . '/>';

    // #54832, 140108, dwildt, 1+
    return $content;

    $marker = array(
      '###' . $hashMarker . '_CHECKBOX###' => $content
    );

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_CHECKBOX###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $marker;
  }

  /**
   * optionListMarkerLabel( )
   *
   * @param	string		$optionType       : payment, shipping, special
   * @param	string		$condition        :
   * @return	array		$marker           :
   */
  private function optionListMarkerCondition( $optionType, $condition )
  {
    $marker = null;
    $hashMarker = strtoupper( $optionType );

    // RETURN : no condition content
    if ( !$condition )
    {
      $marker[ '###' . $hashMarker . '_CONDITION###' ] = '';
      return;
    }
    // RETURN : no condition content
    // Get template and marker
    $tmpl = $this->tmpl[ $optionType . '_condition_all' ];
    $marker[ '###CONTENT###' ] = $condition;

    // render the content
    $content = $this->cObj->substituteMarkerArrayCached( $tmpl, null, $marker );

    // set the marker
    $marker[ '###' . $hashMarker . '_CONDITION###' ] = $content;

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_CONDITION###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $marker;
  }

  /**
   * optionListMarkerLabel( )
   *
   * @param	string		$optionType       : payment, shipping, special
   * @param	string		$optionItemKey    :
   * @param	integer		$optionItemConf   :
   * @return	array		$marker           :
   * @version  4.0.9
   * @since    2.0.0
   */
  private function optionListMarkerLabel( $optionType, $optionItemKey, $optionItemConf )
  {
    $marker = null;

    $title = $this->optionListLabel( $optionItemConf );

    $hashMarker = strtoupper( $optionType );

    // #54832, 140108, dwildt, 1-
//    $content  = '<label for="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '">'
//              . $title . '</label>';
    // #54832, 140108, dwildt, 1+
    $content = '<label for="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '">| '
            . $title . '</label>';
    // #i0051, 140108, dwildt, 3-
//      // #54832, 140108, dwildt, 1+
//    $content  = '<label for="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '">| '
//              . $title . '</label>';
    // #i0051, 140108, dwildt, 3+
    // #54832, 140108, dwildt, 1+
    $content = '<label for="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '">| '
            . $title . '###CONDITION###</label>';

    $marker[ '###' . $hashMarker . '_TITLE###' ] = $content;

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_TITLE###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $marker;
  }

  /**
   * optionListMarkerRadio( )
   *
   * @param	string		$keepingTheLimit  :
   * @param	string		$optionType       : payment, shipping, special
   * @param	string		$optionItemKey    :
   * @param	integer		$optionId         : current option id
   * @return	array		$content          :
   * @version  4.0.4
   * @since    2.0.0
   */
  private function optionListMarkerRadio( $keepingTheLimit, $optionType, $optionItemKey, $optionId )
  {
    $checked = null;
    if ( intval( $optionItemKey ) == $optionId )
    {
      $checked = ' checked="checked"';
    }

    $disabled = null;
    if ( !$keepingTheLimit )
    {
      $disabled = ' disabled="disabled"';
    }

    $hashMarker = strtoupper( $optionType );

    $content = '<input class="onChangeloadCaddyByAjax" type="radio" onchange="this.form.submit()" name="tx_caddy_pi1[' . $optionType . ']" '
            . 'id="tx_caddy_pi1_' . $optionType . '_' . intval( $optionItemKey ) . '"  '
            . 'value="' . intval( $optionItemKey ) . '"' . $checked . $disabled . '/>';

    // #54832, 140108, dwildt, 1+
    return $content;

    $marker = array
      (
      '###' . $hashMarker . '_RADIO###' => $content
    );

    // DRS
    if ( $this->drs->drsMarker )
    {
      $prompt = '###' . $hashMarker . '_RADIO###: ' . $content;
      t3lib_div::devlog( '[INFO/MARKER] ' . $prompt, $this->extKey, 0 );
    }
    // DRS

    return $marker;
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

    switch ( $extraType )
    {
      case 'by_price':
        $symbol = $this->conf[ 'api.' ][ 'symbols.' ][ 'currencySymbol' ];
        break;
      case 'by_quantity':
        $symbol = $this->conf[ 'api.' ][ 'symbols.' ][ 'quantitySymbol' ];
        break;
      case 'by_service_attribute_1_sum':
      case 'by_service_attribute_1_max':
        $symbol = $this->conf[ 'api.' ][ 'symbols.' ][ 'service_attribute_1_symbol' ];
        break;
      case 'by_service_attribute_2_sum':
      case 'by_service_attribute_2_max':
        $symbol = $this->conf[ 'api.' ][ 'symbols.' ][ 'service_attribute_2_symbol' ];
        break;
      case 'by_service_attribute_3_sum':
      case 'by_service_attribute_3_max':
        $symbol = $this->conf[ 'api.' ][ 'symbols.' ][ 'service_attribute_3_symbol' ];
        break;
      default:
        $symbol = '';
    }

    return $symbol;
  }

  /**
   * powermailInActionCreate( )  :
   *
   * @return	boolean
   * @access private
   * @version    4.0.5
   * @since      4.0.5
   */
  private function powermailInActionCreate()
  {
    $inActionCreate = false;

    $GP = t3lib_div::_GET() + t3lib_div::_POST()
    ;

    switch ( true )
    {
      case( $GP[ 'tx_powermail_pi1' ][ 'action' ] == 'create' ):
        $inActionCreate = true;
        break;
      default:
        $inActionCreate = false;
        break;
    }

    unset( $GP );

    return $inActionCreate;
  }

  /*   * *********************************************
   *
   * Setting methods
   *
   * ******************************************** */

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
    if ( !is_array( $row ) || empty( $row ) )
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
   * setParentObject( )  :
   *
   * @param	object		$pObj: ...
   * @return	void
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function setParentObject( $pObj )
  {
    if ( !is_object( $pObj ) )
    {
      $prompt = 'ERROR: no object!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->pObj = $pObj;

    $this->setParentObjectCObj( );
    $this->setParentObjectConf( );
    $this->setParentObjectDrs( );
    $this->setParentObjectLocalCObj( );
    $this->setParentObjectTemplate( );
  }

  /**
   * setParentObjectConf( )  :
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function setParentObjectConf( )
  {
    if ( !is_array( $this->pObj->conf ) || empty( $this->pObj->conf ) )
    {
      $prompt = 'ERROR: no configuration!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->conf = $this->pObj->conf;
  }

  /**
   * setParentObjectCObj( )  :
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function setParentObjectCObj( )
  {
    if ( !is_object( $this->pObj->cObj ) )
    {
      $prompt = 'ERROR: no cObject!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->cObj = $this->pObj->cObj;
  }

  /**
   * setParentObjectDrs( )  :
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function setParentObjectDrs( )
  {
    if ( !is_object( $this->pObj->drs ) )
    {
      $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';
      require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
      $this->drs = t3lib_div::makeInstance( 'tx_caddy_drs' );
      // #i0063, 141128, dwildt 2-/1+
      //$this->drs->pObj = $this;
      //$this->drs->row = $this->cObj->data;
      $this->drs->cObj->data = $this->cObj->data;
    }
    else
    {
      $this->drs = $this->pObj->drs;
    }
  }

  /**
   * setParentObjectLocalCObj( )  :
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function setParentObjectLocalCObj( )
  {
    if ( !is_object( $this->pObj->local_cObj ) )
    {
      $prompt = 'ERROR: no local_cObj!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->local_cObj = $this->pObj->local_cObj;
  }

  /**
   * setParentObjectTemplate( )  :
   *
   * @return	void
   * @access private
   * @version    2.0.2
   * @since      2.0.2
   */
  private function setParentObjectTemplate( )
  {
    if ( !is_array( $this->pObj->tmpl ) || empty( $this->pObj->tmpl ) )
    {
      $prompt = 'ERROR: no template!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }

    $this->tmpl = $this->pObj->tmpl;
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
    if ( !is_array( $products ) || empty( $products ) )
    {
      $prompt = 'ERROR: there isn\'t any product!<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
    $this->products = $products;
  }

  /*   * *********************************************
   *
   * ZZ
   *
   * ******************************************** */

  /**
   * zz_addQtynameMarker():  Allocates to the global markerArray a value for ###QTY_NAME###
   *                          in case of variant
   *                          It returns in aray with hidden fields like
   *                          <input type="hidden"
   *                                 name="tx_caddy_pi1[uid][20][]"
   *                                 value="tx_caddy_pi1[tx_org_calentrance.uid]=4|tx_caddy_pi1[qty]=91" />
   *
   * @param	array		$products: array with products with elements uid, title, tax, etc...
   * @param	array		$markerArray: current marker array
   * @param	array		$pobj: Parent Object
   * @return	array		$markerArray: with added element ###VARIANTS### in case of variants
   * @access private
   * @version 6.0.0
   * @since 1.4.6
   */
  private function zz_addQtynameMarker( $product, $markerArray, $pObj )
  {
    // default name for QTY. It is compatible with version 1.2.1
    $markerArray[ '###QTY_NAME###' ] = 'tx_caddy_pi1[qty][' . $product[ 'uid' ] . ']';

    // return there isn't any variant
    if ( !is_array( $pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      return $markerArray;
    }

    // get all variant key/value pairs from the current product
    $array_add_gpvar = $this->session->productGetVariantTs( $product, $pObj );
    //// #61877, dwildt, 2-
    $array_add_gpvar[ 'uid' ] = $product[ 'uid' ];
    $str_marker = null;
    //// #61877, dwildt, 1+
    //$str_marker = '[' . $product['uid'] . ']';
    // generate the marker array
    foreach ( ( array ) $array_add_gpvar as $key => $value )
    {
      $str_marker = $str_marker . '[' . $key . '=' . $value . ']';
    }
    $markerArray[ '###QTY_NAME###' ] = 'tx_caddy_pi1[qty]' . $str_marker;
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
  private function zz_addVariantGpvarToImagelinkwrap( $product, $ts_key, $ts_conf, $pObj )
  {
    unset( $ts_key );

    // RETURN : there isn't any variant
    if ( !is_array( $pObj->conf[ 'settings.' ][ 'variant.' ] ) )
    {
      return $ts_conf;
    }
    // RETURN : there isn't any variant
    // get all variant key/value pairs from the current product
    $array_add_gpvar = $this->session->productGetVariantTs( $product, $pObj );

    // add variant key/value pairs to imageLinkWrap
    foreach ( ( array ) $array_add_gpvar as $key => $value )
    {
      $str_wrap = $ts_conf[ 'imageLinkWrap.' ][ 'typolink.' ][ 'additionalParams.' ][ 'wrap' ];
      $str_wrap = $str_wrap . '&' . $this->prefixId . '[' . $key . ']=' . $value;
      $ts_conf[ 'imageLinkWrap.' ][ 'typolink.' ][ 'additionalParams.' ][ 'wrap' ] = $str_wrap;
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
            isset( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ][ 'available_from' ] ) &&
            (
            round( floatval( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ][ 'available_from' ] ), 2 ) >
            round( $this->productsGross, 2 )
            )
            ) ||
            (
            isset( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ][ 'available_until' ] ) &&
            (
            round( floatval( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ][ 'available_until' ] ), 2 ) <
            round( $this->productsGross, 2 )
            )
            )
    )
    {
      // check: fallback is given
      if ( isset( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ][ 'fallback' ] ) )
      {
        $fallback = $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $optionId . '.' ][ 'fallback' ];
        // check: fallback is defined; the availability of fallback will not tested yet
        if ( isset( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'options.' ][ $fallback . '.' ] ) )
        {
          $newoption_id = intval( $fallback );
        }
        else
        {
// 130227, dwildt, 1-
//                                  $shippingId = intval($this->conf['api.']['options.'][$optionType . '.']['preset']);
// 130227, dwildt, 1+
          $newoption_id = intval( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'preset' ] );
        }
      }
      else
      {
        $newoption_id = intval( $this->conf[ 'api.' ][ 'options.' ][ $optionType . '.' ][ 'preset' ] );
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
    $net = 0.00;
    $rate = 0.00;
    $taxDevider = 1.00;

    // if ($conf[$type.'.']['options.'][$option_id . '.']['tax'] == 'reduced') { // reduced tax
    switch ( $taxType )
    {
      case( 'reduced' ):
        $rate = $this->conf[ 'api.' ][ 'tax.' ][ 'reducedCalc' ];
        break;
      case( 'normal' ):
        $rate = $this->conf[ 'api.' ][ 'tax.' ][ 'normalCalc' ];
        break;
      default:
        $rate = 0.00;
        break;
    }

    $taxDevider = $taxDevider + $rate
    ;
    $net = $gross / $taxDevider;

    $result = array
      (
      'gross' => $gross,
      'net' => $net,
      'rate' => $rate
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
    switch ( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case(!( is_array( $cObj_conf ) ) ):
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
    if ( empty( $this->conf ) )
    {
      $this->conf = $GLOBALS[ 'TSFE' ]->tmpl->setup[ 'plugin.' ][ 'tx_caddy_pi1.' ];
    }

    $currencySymbol = $this->conf[ 'api.' ][ 'symbols.' ][ 'currencySymbol' ];
    $price = number_format
            (
            $value, $this->conf[ 'api.' ][ 'symbols.' ][ 'decimal' ], $this->conf[ 'api.' ][ 'symbols.' ][ 'dec_point' ], $this->conf[ 'api.' ][ 'symbols.' ][ 'thousands_sep' ]
    );
    // print currency symbol before or after price
    if ( $this->conf[ 'api.' ][ 'symbols.' ][ 'currencySymbolBeforePrice' ] )
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
   * zz_setData( ) :
   *
   * @param	[type]		$$data: ...
   * @param	[type]		$table: ...
   * @return	void
   * @access private
   * @version 2.0.2
   * @since 2.0.2
   */
  private function zz_setData( $data, $table )
  {

    $this->local_cObj->start( $data, $table );
    // cObject becomes current record
    // RETURN : no DRS
    if ( !$this->drs->drsCobj )
    {
      return;
    }
    // RETURN : no DRS
    // DRS
    $cObjData = var_export( $this->local_cObj->data, true );
    $prompt = 'cObj->data: ' . $cObjData;
    t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    // DRS
  }

  /**
   * zz_setDataBySession( ) :
   *
   * @return	void
   * @access private
   * @version   2.0.2
   * @since     2.0.2
   */
  private function zz_setDataBySession()
  {
    // Get the current session array
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );

    // data: implode the array to a one dimensional array
    $data = t3lib_BEfunc::implodeTSParams( $sesArray );

    // set cObj->data
    $this->zz_setData( $data, $this->conf[ 'db.' ][ 'table' ] );
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/caddy/class.tx_caddy.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/caddy/class.tx_caddy.php' ]);
}
?>
