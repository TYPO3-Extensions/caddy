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

require_once( PATH_tslib . 'class.tslib_pibase.php');

/**
 * plugin 'Cart' for the 'powermail' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package  TYPO3
 * @subpackage  user_caddy_powermailCaddy
 * @version     2.0.0
 * @since       1.4.6
 */
class user_caddy_powermailCaddy extends tslib_pibase
{
  public $prefixId = 'tx_caddy_pi1';

  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';

  public $extKey = 'caddy';

  private $content;

  private $tmpl = array();

  private $outerMarkerArray = array();

  private $markerArray = array();

 /**
  * read and return cart from session
  *
  * @return  string    cart content
  * @access public
  * @version 2.0.0
  * @since  1.4.6
  */
  public function showCaddy( $content = '', $conf = array( ) )
  {
    global $TSFE;
    $local_cObj = $TSFE->cObj; // cObject
    
    unset( $content );
    
    $this->pi_loadLL();
    
    $this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    $this->conf = array_merge( ( array ) $this->conf, ( array ) $conf );
    
    $this->tmpl['all']  = $this->cObj->getSubpart( $this->cObj->fileResource( $this->conf['main.']['template'] ), '###CADDY_POWERMAIL###' );
    $this->tmpl['item'] = $this->cObj->getSubpart($this->tmpl['all'], '###ITEM###'); // work on subpart 2

    require_once( t3lib_extMgm::extPath( 'caddy' ) . 'lib/class.tx_caddy_calc.php' );
    $this->calc           = t3lib_div::makeInstance( 'tx_caddy_calc' );
    require_once( t3lib_extMgm::extPath( 'caddy' ) . 'lib/class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );
    require_once( t3lib_extMgm::extPath( 'caddy' ) . 'lib/class.tx_caddy_render.php' );
    $this->render         = t3lib_div::makeInstance( 'tx_caddy_render' );
    require_once( t3lib_extMgm::extPath( 'caddy' ) . 'lib/class.tx_caddy_session.php' );
    $this->session        = t3lib_div::makeInstance( 'tx_caddy_session' );
    
    $content_item     = '';
    $shipping_option  = '';
    $payment_option   = '';
    $cartNet                        = 0; 
    $cartGross                      = 0;
    $cartTaxReduced                 = 0;
    $cartTaxNormal                  = 0;
    $this->cartCount                = 0;
    $this->cartServiceAttribute1Sum = 0;
    $this->cartServiceAttribute1Max = 0;
    $this->cartServiceAttribute2Sum = 0;
    $this->cartServiceAttribute2Max = 0; 
    $this->cartServiceAttribute3Sum = 0;
    $this->cartServiceAttribute3Max = 0;


      // read all products from session
    $this->product = $this->session->productsGet( );
    
      // DRS
    unset( $content );
    $drs = false;
    if( $conf['userFunc.']['drs'] )
    {
      $drs = true;
      $prompt = 'DRS is enabled by userfunc ' . __METHOD__ . '[userFunc.][drs].';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // RETURN : empty content, no product in session
    if( count( $this->product ) < 1 )
    {
        // DRS
      if( $this->drs->drsSession || $drs )
      {
        $prompt = __METHOD__ . ' returns null[userFunc.][drs].';
        t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
      $this->content = ''; // clear content
      return $this->content;
    }
      // RETURN : empty content, no product in session

    foreach( ( array ) $this->product as $product )
    { // one loop for every product in session
      $product['price_total'] = $product['price'] * $product['qty']; // price total
      $local_cObj->start($product, $this->conf['db.']['table']); // enable .field in typoscript
      foreach ((array) $this->conf['settings.']['powermailCaddy.']['fields.'] as $key => $value)
      { // one loop for every param of the current product
        if (!stristr($key, '.'))
        { // no .
          $this->markerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['powermailCaddy.']['fields.'][$key], $this->conf['settings.']['powermailCaddy.']['fields.'][$key . '.']); // write to marker
        }
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

      $cartNet += ( $product['price_total'] - $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']));

      if ($product['tax'] == 1)
      { // reduced tax
        $cartTaxReduced += $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']); // add tax from this product to overall
      } else { // normal tax
        $cartTaxNormal += $local_cObj->cObjGetSingle($this->conf['settings.']['fields.']['tax'], $this->conf['settings.']['fields.']['tax.']); // add tax from this product to overall
      }
    }

    $subpartArray['###CONTENT###'] = $content_item; // work on subpart 3

    $cartGrossNoService = $cartGross;
    $cartNetNoService = $cartNet;

    // calculate pice incl. shipping
    $shipping_id = $this->session->shippingGet();

    if ($shipping_id) 
    {
      $shipping_values	= $this->calc->calculateOptionById($this->conf, 'shipping', $shipping_id, $this);
      $shipping_net		= $shipping_values['net'];
      $shipping_gross		= $shipping_values['gross'];
      $cartNet			+= $shipping_values['net'];
      $cartGross			+= $shipping_values['gross'];
      if ($this->conf['shipping.']['options.'][$shipping_id . '.']['tax'] == 'reduced') {
        $cartTaxReduced += $shipping_gross - $shipping_net;
      } else {
        $cartTaxNormal	+= $shipping_gross - $shipping_net;	
      }

      $shipping_option	= $this->render->renderOptionById($this->conf, 'shipping', $shipping_id, $this);
    }

    // calculate pice incl. payment
    $payment_id = $this->session->paymentGet();

    if ($payment_id)
    {
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

      $payment_option 	= $this->render->renderOptionById($this->conf, 'payment', $payment_id, $this);

      // display notes for payment method
      $paymentNote = $this->conf['payment.']['options.'][$payment_id . '.']['note'];
    }

    // calculate pice incl. specials
    $special_ids = $this->session->specialGet();

    $overall_special_gross = 0.0;
    $overall_special_net = 0.0;
    $overall_special_option = '';

    foreach( ( array ) $special_ids as $special_id )
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

      $overall_special_option .= $this->render->renderOptionById($this->conf, 'special', $special_id, $this);
    }

    $outerArr = array
                (
                  'optionsNet'      => $shipping_net + $payment_net + $overall_special_net,
                  'optionsGross'    => $shipping_gross + $payment_gross+ $overall_special_gross,
                  'sumGross'        => $cartGross,
                  'productsGross'   => $cartGrossNoService,
                  'sumNet'          => $cartNet,
                  'productsNet'     => $cartNetNoService,
                  'sumTaxReduced'   => $cartTaxReduced,
                  'sumTaxNormal'    => $cartTaxNormal,
                  'payment_note'    => $paymentNote,
                  'shipping_option' => $shipping_option,
                  'payment_option'  => $payment_option,
                  'special_option'  => $overall_special_option,
                  'ordernumber'     => $this->session->getNumberOrder()
                );
    $local_cObj->start( $outerArr, $this->conf['db.']['table'] );

    foreach ((array) $this->conf['settings.']['powermailCaddy.']['overall.'] as $key => $value)
    {
      if (!stristr($key, '.'))
      { // no .
        $this->outerMarkerArray['###' . strtoupper($key) . '###'] = $local_cObj->cObjGetSingle($this->conf['settings.']['powermailCaddy.']['overall.'][$key], $this->conf['settings.']['powermailCaddy.']['overall.'][$key . '.']);
      }
    }

    $this->content = $this->cObj->substituteMarkerArrayCached($this->tmpl['all'], $this->outerMarkerArray, $subpartArray); // Get html template
    $this->content = $this->dynamicMarkers->main($this->content, $this); // Fill dynamic locallang or typoscript markers
    $this->content = preg_replace('|###.*?###|i', '', $this->content); // Finally clear not filled markers

      // DRS
    if( $this->drs->drsSession || $drs )
    {
      $prompt = __METHOD__ . ' returns the caddy with products and calculation.';
      t3lib_div::devlog( '[INFO/POWERMAIL] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    return $this->content;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/user_caddy_powermailCaddy.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/user_caddy_powermailCaddy.php']);
}
?>
