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
require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_div.php'); // file for div functions
require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_calc.php'); // file for calculation functions
require_once(t3lib_extMgm::extPath('caddy') . 'lib/class.tx_caddy_dynamicmarkers.php'); // file for dynamicmarker functions

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

  // extension key
  private $product = array();

  private $newProduct = array();

  private $template = array();

  private $markerArray = array();

  private $outerMarkerArray = array();

  private $gpvar = array();

 /**
  * the main method of the PlugIn
  *
  * @param string    $content: The PlugIn content
  * @param array   $conf: The PlugIn configuration
  * @return  The content that is displayed on the website
  * @version    2.0.0
  * @since      1.4.6
  */
  public function main( $content, $conf )
  {
      // #45775, dwildt, 1+
    unset( $content );
    
    // config
    global $TSFE;

    $local_cObj = $TSFE->cObj; // cObject
    $this->conf = $conf;
    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    $this->pi_USER_INT_obj = 1;

      // Init callses, DRS, gpvars, HTML template, service attributes
    $this->init( );

    $content_item   = '';
    $cartNet        = 0;
    $cartGross      = 0;
    $cartTaxReduced = 0;
    $cartTaxNormal  = 0;
    
    $this->cartCount  = 0;
    
      // Output debugging prompts in debug mode
    $this->debugOutputBeforeRunning( );

      // Remove current product
    $this->productRemove( );
    
      // Update several order values
    $this->orderUpdate( );

    // add further product to session
    $this->newProduct = $this->div->getProductDetails($this->gpvar, $this); // get details from product
    if ($this->newProduct !== false)
    {
      $this->newProduct['qty'] = $this->gpvar['qty'];
      $this->newProduct['service_attribute_1'] = $this->gpvar['service_attribute_1'];
      $this->newProduct['service_attribute_2'] = $this->gpvar['service_attribute_2'];
      $this->newProduct['service_attribute_3'] = $this->gpvar['service_attribute_3'];
      $this->div->addProduct2Session($this->newProduct, $this);
    }

    // read all products from session
    $this->product = $this->div->getProductsFromSession();

      // there are products in the session
    if (count($this->product) > 0) 
    {
      // loop for every product in the session
      foreach ((array) $this->product as $product) 
      {
              // clear marker array to avoid problems with error msg etc.
              unset($this->markerArray);
              // price total
              $product['price_total'] = $product['price'] * $product['qty']; 
              // enable .field in typoscript
              $local_cObj->start($product, $this->conf['db.']['table']); 

              foreach ((array) $this->conf['settings.']['fields.'] as $key => $value)
              {
                      if (!stristr($key, '.'))
                      { // no .
                              //$this->markerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['fields.'][$key], $this->conf['settings.']['fields.'][$key . '.']); // write to marker
                              // name of the current field in the TypoScript
                              $ts_key   = $this->conf['settings.']['fields.'][$key];
                              // configuration array of the current field in the TypoScript
                              $ts_conf  = $this->conf['settings.']['fields.'][$key . '.'];
                              switch($key)
                              {
                                      case('delete'):
                                              $ts_conf = $this->div->add_variant_gpvar_to_imagelinkwrap($product, $ts_key, $ts_conf, $this);
                                              break;

                                      default:
                                              // nothing to do, there is no default now
                              }
                              $ts_rendered_value  = $local_cObj->cObjGetSingle($ts_key, $ts_conf);
                              $this->markerArray['###' . strtoupper($key) . '###'] = $ts_rendered_value; // write to marker

                              // adds the ###QTY_NAME### marker in case of variants
                              $this->markerArray = $this->div->add_qtyname_marker($product, $this->markerArray, $this);
                      }
              }

              // replace error msg
              $this->markerArray['###ERROR_MSG###'] = '';
              foreach ($product['error'] as $error) {
                      $this->markerArray['###ERROR_MSG###'] .= sprintf($this->pi_getLL('caddy_ll_error_'.$error), $product[$error]);
              }

              $content_item .= $this->cObj->substituteMarkerArrayCached($this->tmpl['item'], $this->markerArray); // add inner html to variable

              $cartGross += $product['price_total'];
              $this->cartCount += $product['qty'];

              $this->cartServiceAttribute1Sum += $product['service_attribute_1'] * $product['qty'];
              $this->cartServiceAttribute1Max = $this->cartServiceAttribute1Max > $product['service_attribute_1'] ? $this->cartServiceAttribute1Max : $product['service_attribute_1'];
              $this->cartServiceAttribute2Sum += $product['service_attribute_2'] * $product['qty'];
              $this->cartServiceAttribute2Max = $this->cartServiceAttribute2Max > $product['service_attribute_2'] ? $this->cartServiceAttribute2Max : $product['service_attribute_2'];
              $this->cartServiceAttribute3Sum += $product['service_attribute_3'] * $product['qty'];
              $this->cartServiceAttribute3Max = $this->cartServiceAttribute3Max > $product['service_attribute_3'] ? $this->cartServiceAttribute3Max : $product['service_attribute_3'];

              // get the formular with the markers ###TAX## for calculating tax
              $str_wrap = $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'];
              // save the formular with marker, we need it later
              $str_wrap_former = $str_wrap;
              // replace the ###TAX### with current tax rate like 0.07 or 0.19
              $str_wrap = str_replace('###TAX###', $product['tax'], $str_wrap);
              // assign the forular with tax rates to TypoScript
              $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap;

              $cartNet += ( $product['price_total'] - $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']));

              $curr_tax = $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']);

              switch($product['tax'])
              {
                      case(0):
                              break;
                      case(1):
                      case($this->conf['tax.']['reducedCalc']):
                              $cartTaxReduced += $curr_tax; // add tax from this product to overall
                              break;
                      case(2):
                      case($this->conf['tax.']['normalCalc']):
                              $cartTaxNormal += $curr_tax; // add tax from this product to overall
                              break;
                      default:
                              echo '<div style="border:2em solid red;padding:2em;color:red;"><h1 style="color:red;">caddy Error</h1><p>tax is "' . $product['tax'] . '".<br />This is an undefined value in class.tx_caddy_pi1.php. ABORT!<br /><br />Are you sure, that you included the caddy static template?</p></div>';
                              exit;
              }
              $this->conf['settings.']['fields.']['tax.']['default.']['setCurrent.']['wrap'] = $str_wrap_former;
      }
      // loop for every product in the session

      // item for payment
      $payment_id = $this->div->getPaymentFromSession();
      if ($payment_id)
      {
              $this->markerArray['###QTY###'] = 1;
              $this->markerArray['###TITLE###'] = $this->conf['payment.']['options.'][$payment_id . '.']['title'];
              $this->markerArray['###PRICE###'] = $this->conf['payment.']['options.'][$payment_id . '.']['extra'];
              $this->markerArray['###PRICE_TOTAL###'] = $this->conf['payment.']['options.'][$payment_id . '.']['extra'];
              $content_item .= $this->cObj->substituteMarkerArrayCached($this->tmpl['special_item'], $this->markerArray); // add inner html to variable
      }

      $subpartArray['###CONTENT###'] = $content_item; // work on subpart 3
      $subpartArray['###CONTENT###'] .= '<input type="hidden" name="tx_caddy_pi1[update_from_cart]" value="1">';

      $this->cartGrossNoService = $cartGross;
      $cartNetNoService = $cartNet;

      // SHIPPING
      $shipping_id = $this->div->getShippingFromSession();

      if (!$shipping_id)
      {
              $shipping_id = intval($this->conf['shipping.']['preset']);
              $this->div->changeShippingInSession($shipping_id);
      }
      // check if selected shipping option is available
      if ($newshipping_id = $this->checkOptionIsNotAvailable('shipping', $shipping_id))
      {
              $shipping_id = $newshipping_id;
              $this->div->changeShippingInSession($newshipping_id);
      }

      $shipping_values	= $this->calc->calculateOptionById($this->conf, 'shipping', $shipping_id, $this);
      $shipping_net		= $shipping_values['net'];
      $shipping_gross		= $shipping_values['gross'];
      $cartNet			+= $shipping_values['net'];
      $cartGross			+= $shipping_values['gross'];
      if ($this->conf['shipping.']['options.'][$shipping_id . '.']['tax'] == 'reduced') {
              $cartTaxReduced += $shipping_gross - $shipping_net;
      } else {
              $cartTaxNormal += $shipping_gross - $shipping_net;	
      }

      // PAYMENT
      $payment_id = $this->div->getPaymentFromSession();

      if (!$payment_id)
      {
              $payment_id = intval($this->conf['payment.']['preset']);
              $this->div->changePaymentInSession($payment_id);
      }
      // check if selected payment option is available
      if ($newpayment_id = $this->checkOptionIsNotAvailable('payment', $payment_id))
      {
              $payment_id = $newpayment_id;
              $this->div->changePaymentInSession($newpayment_id);
      }

      $payment_values		= $this->calc->calculateOptionById($this->conf, 'payment', $payment_id, $this);
      $payment_net		= $payment_values['net'];
      $payment_gross		= $payment_values['gross'];
      $cartNet			+= $payment_values['net'];
      $cartGross			+= $payment_values['gross'];
      if ($this->conf['payment.']['options.'][$payment_id . '.']['tax'] == 'reduced') {
              $cartTaxReduced += $payment_gross - $payment_net;
      } else {
              $cartTaxNormal += $payment_gross - $payment_net;	
      }

      // SPECIAL
      $special_ids = $this->div->getSpecialFromSession();

      $overall_special_gross = 0.0;
      $overall_special_net = 0.0;

      foreach ($special_ids as $special_id)
      {
              $special_values		= $this->calc->calculateOptionById($this->conf, 'special', $special_id, $this);
              $special_net		= $special_values['net'];
              $special_gross		= $special_values['gross'];
              $cartNet			+= $special_values['net'];
              $cartGross			+= $special_values['gross'];
              if ($this->conf['special.']['options.'][$special_id . '.']['tax'] == 'reduced') {
                      $cartTaxReduced += $special_gross - $special_net;
              } else {
                      $cartTaxNormal += $special_gross - $special_net;	
              }
              $overall_special_gross += $special_gross;
              $overall_special_net   += $special_net;
      }

      $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id);
      $sesArray['service_cost_net'] = $shipping_net + $payment_net + $overall_special_net;
      $sesArray['service_cost_gross'] = $shipping_gross + $payment_gross + $overall_special_gross;
      $sesArray['cart_gross'] = $cartGross;
      $sesArray['cart_gross_no_service'] = $this->cartGrossNoService;
      $sesArray['cart_net'] = $cartNet;
      $sesArray['cart_net_no_service'] = $cartNetNoService;
      $sesArray['cart_tax_reduced'] = $cartTaxReduced;
      $sesArray['cart_tax_normal'] = $cartTaxNormal;
      $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray);

      $outerArr = array(
              'service_cost_net' => $sesArray['service_cost_net'], 
              'service_cost_gross' => $sesArray['service_cost_gross'],
              'cart_gross' => $cartGross,
              'cart_gross_no_service' => $this->cartGrossNoService,
              'cart_net' => $cartNet,
              'cart_net_no_service' => $cartNetNoService,
              'cart_tax_reduced' => $cartTaxReduced,
              'cart_tax_normal' => $cartTaxNormal
      );
      $local_cObj->start($outerArr, $this->conf['db.']['table']); // enable .field in typoscript
      foreach ((array) $this->conf['settings.']['overall.'] as $key => $value)
      {
              if (!stristr($key, '.'))
              { // no .
                      $this->outerMarkerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['overall.'][$key], $this->conf['settings.']['overall.'][$key . '.']);
              }
      }

      if ($sesArray['cart_gross_no_service'] < floatval($this->conf['cart.']['cartmin.']['value']))
      {
              $cartMinStr = $this->price_format($this->conf['cart.']['cartmin.']['value']);
              $minPriceArray['###ERROR_MINPRICE###'] = sprintf($this->pi_getLL('caddy_ll_minprice'), $cartMinStr);
              $subpartArray['###MINPRICE###'] = $this->cObj->substituteMarkerArrayCached($this->tmpl['minprice'], $minPriceArray);
      }
      if (!($sesArray['cart_gross_no_service'] < floatval($this->conf['cart.']['cartmin.']['value'])) || (($sesArray['cart_gross_no_service'] < floatval($this->conf['cart.']['cartmin.']['value'])) && (!$this->conf['cart.']['cartmin.']['hideifnotreached.']['service'])))
      {
              $shippingArray['###CONTENT###'] = $this->renderOptionList('shipping', $shipping_id);
              if ($shippingArray['###CONTENT###'])
              {
                      $subpartArray['###SHIPPING_RADIO###'] = $this->cObj->substituteMarkerArrayCached($this->tmpl['shipping_all'], null, $shippingArray);
              } else {
                      $subpartArray['###SHIPPING_RADIO###'] = '';
              }

              $paymentArray['###CONTENT###'] = $this->renderOptionList('payment', $payment_id);
              if ($paymentArray['###CONTENT###'])
              {
                      $subpartArray['###PAYMENT_RADIO###'] = $this->cObj->substituteMarkerArrayCached($this->tmpl['payment_all'], null, $paymentArray);
              } else {
                      $subpartArray['###PAYMENT_RADIO###'] = '';
              }

              $specialArray['###CONTENT###'] = $this->renderOptionList('special', $special_ids);
              if ($specialArray['###CONTENT###'])
              {
                      $subpartArray['###SPECIAL_CHECKBOX###'] = $this->cObj->substituteMarkerArrayCached($this->tmpl['special_all'], null, $specialArray);
              } else {
                      $subpartArray['###SPECIAL_CHECKBOX###'] = '';
              }
      }
    }
      // there are products in the session


      // there isn't any product in the session
    if( ! ( count( $this->product ) > 0 ) )
    { 
      if( ! empty ( $this->tmpl['all'] ) )
      { // if template found
        $this->tmpl['all'] = $this->tmpl['empty']; // overwrite normal template with empty template
      }
      else
      { // no template - show error
        $this->tmpl['all'] = $this->div->msg( $this->pi_getLL( 'error_noTemplate', 'No Template found' ) );
      }
    }

    $this->content = $this->cObj->substituteMarkerArrayCached($this->tmpl['all'], $this->outerMarkerArray, $subpartArray); // Get html template
    $this->content = $this->dynamicMarkers->main($this->content, $this); // Fill dynamic locallang or typoscript markers
    $this->content = preg_replace('|###.*?###|i', '', $this->content); // Finally clear not filled markers
    return $this->pi_wrapInBaseClass($this->content);
  }
  
  
  
  /***********************************************
  *
  * Debug
  *
  **********************************************/

 /**
  * debugOutputBeforeRunning( )
  *
  * @return  void
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
      $this->div->getProductsFromSession(), $this->extKey . ': ' . 'Values in session at the beginning'
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
  * @return  void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function init( )
  {
      // Init extension configuration array
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );

    $this->initDRS( );
    $this->initInstances( );
    $this->initHtmlTemplate( );
    $this->initServiceAttributes( );
    $this->initGpVar( );
  }
  
 /**
  * initDrs( ): Init the DRS - Development Reportinmg System
  *
  * @return    void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDrs( )
  {
    
      // Enable the DRS by TypoScript
    switch( $this->arr_extConf['debuggingDrs'] )
    {
      case( 'Disabled' ):
      case( null ):
        return;
        break;
      case( 'Enabled (for debugging only!)' ):
          // Follow the workflow
        break;
      default:
        $prompt = 'Error: debuggingDrs is undefined.<br />
          value is ' . $this->arr_extConf['debuggingDrs'] . '<br />
          <br />
          ' . __METHOD__ . ' line(' . __LINE__. ')';
        die( $prompt );
    }

    $this->b_drs_error  = true;
    $this->b_drs_warn   = true;
    $this->b_drs_info   = true;
    $this->b_drs_ok     = true;
    $this->b_drs_init   = true;
    $this->b_drs_todo   = true;
    $prompt = 'The DRS - Development Reporting System is enabled: ' . $this->arr_extConf['debuggingDrs'];
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
  }

 /**
  * initGpVar( )
  *
  * @return  void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initGpVar( )
  {
    $conf = $this->conf;
    
      // read variables
    $this->gpvar['title'] = $this->div->cObjGetSingle( $conf['settings.']['title'], $conf['settings.']['title.'] );
    $this->gpvar['price'] = $this->div->cObjGetSingle( $conf['settings.']['price'], $conf['settings.']['price.'] );
    $this->gpvar['qty']   = intval( $this->div->cObjGetSingle( $conf['settings.']['qty'], $conf['settings.']['qty.'] ) );
    $this->gpvar['tax']   = $this->div->cObjGetSingle( $conf['settings.']['tax'], $conf['settings.']['tax.'] );
    $this->gpvar['puid']  = intval( $this->div->cObjGetSingle( $conf['settings.']['puid'], $conf['settings.']['puid.'] ) );
    $this->gpvar['cid']   = intval ($this->div->cObjGetSingle( $conf['settings.']['cid'], $conf['settings.']['cid.'] ) );

    $this->gpvar['sku'] = $this->div->cObjGetSingle( $conf['settings.']['sku'], $conf['settings.']['sku.'] );
    $this->gpvar['min'] = $this->div->cObjGetSingle( $conf['settings.']['min'], $conf['settings.']['min.'] );
    $this->gpvar['max'] = $this->div->cObjGetSingle( $conf['settings.']['max'], $conf['settings.']['max.'] );

    $this->gpvar['service_attribute_1'] = floatval
                                          ( 
                                            $this->div->cObjGetSingle
                                            ( 
                                              $conf['settings.']['service_attribute_1'], 
                                              $conf['settings.']['service_attribute_1.']
                                            )
                                          );
    $this->gpvar['service_attribute_2'] = floatval
                                          ( 
                                            $this->div->cObjGetSingle
                                            ( 
                                              $conf['settings.']['service_attribute_2'], 
                                              $conf['settings.']['service_attribute_2.']
                                            )
                                          );
    $this->gpvar['service_attribute_3'] = floatval
                                          ( 
                                            $this->div->cObjGetSingle
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
  * @return  void
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
  * @return  void
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
      if( $this->b_drs_error )
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
  * @return  void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initInstances( )
  {
    $this->div            = t3lib_div::makeInstance('tx_caddy_div'); // Create new instance for div functions
    $this->div->cObj      = $this->cObj;
    $this->calc           = t3lib_div::makeInstance('tx_caddy_calc'); // Create new instance for calculation functions
    $this->dynamicMarkers = t3lib_div::makeInstance('tx_caddy_dynamicmarkers'); // Create new instance for dynamicmarker function
  }

 /**
  * initServiceAttributes( )
  *
  * @return  void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initServiceAttributes( )
  {
    $this->cartServiceAttribute1Sum = 0;
    $this->cartServiceAttribute1Max = 0;
    $this->cartServiceAttribute2Sum = 0;
    $this->cartServiceAttribute2Max = 0;
    $this->cartServiceAttribute3Sum = 0;
    $this->cartServiceAttribute3Max = 0;
  }
  
  
  
  
  /***********************************************
  *
  * Order
  *
  **********************************************/

 /**
  * orderUpdate( )
  *
  * @return  void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function orderUpdate( )
  {
      // change qty
    if( isset( $this->piVars['qty'] ) && is_array( $this->piVars['qty'] ) )
    {
      $this->div->changeQtyInSession($this); // change qty
    }

      // change shipping
    if( isset( $this->piVars['shipping'] ) )
    {
      $this->div->changeShippingInSession($this->piVars['shipping']); // change shipping
    }

    // change payment
    if( isset( $this->piVars['payment'] ) )
    {
      $this->div->changePaymentInSession($this->piVars['payment']); // change payment
    }

      // change special
    if( isset( $this->piVars['special'] ) )
    {
      $this->div->changeSpecialInSession($this->piVars['special']); // change payment
    }
  }
  
  
  
  
  /***********************************************
  *
  * Product
  *
  **********************************************/

 /**
  * productRemove( )
  *
  * @return  void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function productRemove( )
  {
      // remove product from session
    if( isset( $this->piVars['del'] ) )
    {
      $this->div->removeProductFromSession( $this );
    }
  }
  
  
  
  

  

	/** 
	 * Gets the price for a given type ('shipping', 'payment') method on the current cart
	 * 
	 * @param string $type
	 * @param int $option_id
	 * @return string
	 */
	private function getPriceForOption($type, $option_id) {
		$optionIds = $this->conf[$type.'.']['options.'][$option_id.'.'];
		
		$free_from = $optionIds['free_from'];
		$free_until = $optionIds['free_until'];
		
		if ((isset($free_from) && (floatval($free_from) <= $this->cartGrossNoService)) ||
			(isset($free_until) && (floatval($free_until) >= $this->cartGrossNoService))) {
			return '0.00';
		}
		
		$filterArr = array(
			'by_price' => $this->cartGrossNoService,
			'by_quantity' => $this->cartCount,
			'by_service_attribute_1_sum' => $this->cartServiceAttribute1Sum,
			'by_service_attribute_1_max' => $this->cartServiceAttribute1Max,
			'by_service_attribute_2_sum' => $this->cartServiceAttribute2Sum,
			'by_service_attribute_2_max' => $this->cartServiceAttribute2Max,
			'by_service_attribute_3_sum' => $this->cartServiceAttribute3Sum,
			'by_service_attribute_3_max' => $this->cartServiceAttribute3Max
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
					$price = floatval($optionIds['extra.']['1.']['extra'])*$this->cartCount;
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
	 * @param string $type
	 * @param int $option_id
	 * @return int
	 */	
	private function checkOptionIsNotAvailable($type, $option_id)
	{
		if ((isset($this->conf[$type.'.']['options.'][$option_id.'.']['available_from']) && (round(floatval($this->conf[$type.'.']['options.'][$option_id.'.']['available_from']),2) > round($this->cartGrossNoService,2))) || (isset($this->conf[$type.'.']['options.'][$option_id.'.']['available_until']) && (round(floatval($this->conf[$type.'.']['options.'][$option_id.'.']['available_until']),2) < round($this->cartGrossNoService,2))))
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
					$shipping_id = intval($this->conf[$type.'.']['preset']);
				}
			} else {
				$newoption_id = intval($this->conf[$type.'.']['preset']);
			}
			return $newoption_id;
		}
		
		return 0;
	}

	private function renderOptionList($type, $option_id) {
		$radio_list = '';
		foreach ((array) $this->conf[$type.'.']['options.'] as $key => $value)
		{	
			// hide option if not available by cartGrossNoService
			$show = true;
			if ((isset($value['available_from']) && (round(floatval($value['available_from']),2) > round($this->cartGrossNoService,2))) || (isset($value['available_until']) && (round(floatval($value['available_until']),2) < round($this->cartGrossNoService,2))))
			{
				$show = false;
			}
			
			if ($show || $this->conf[$type.'.']['show_all_disabled'])
			{
				$disabled = $show ? '' : 'disabled="disabled"';
				
				$condition_list = array();
				
				if (isset($value['free_from']))
				{
					$pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_free_from').' '.$this->price_format($value['free_from']);
					$condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
				}
				if (isset($value['free_until']))
				{
					$pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_free_until').' '.$this->price_format($value['free_until']);
					$condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
				}
				
				if (!$show)
				{
					if (isset($value['available_from']))
					{
						$pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_available_from').' '.$this->price_format($value['available_from']);
						$condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);
					}
					if (isset($value['available_until']))
					{
						$pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_'.$type.'_available_until').' '.$this->price_format($value['available_until']);
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
						$pmarkerArray['###CONDITION###'] = $this->pi_getLL('caddy_ll_service_from') . ' ' . $extra['value'] . ' ' . $unit . ' : ' .$this->price_format($extra['extra']);
						$condition_list['###CONTENT###'] .= $this->cObj->substituteMarkerArrayCached($this->tmpl[$type.'_condition_item'], $pmarkerArray);					
					}
					
					$show_price = $this->price_format(floatval($this->getPriceForOption($type, intval($key))));
				} else {
					$show_price = sprintf($this->pi_getLL('caddy_ll_special_each'), $this->price_format($value['extra.']['1.']['extra']));
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

	private function price_format($value) {
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
