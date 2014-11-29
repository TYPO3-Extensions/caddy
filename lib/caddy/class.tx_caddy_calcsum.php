<?php

/***************************************************************
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
 ***************************************************************/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   86: class tx_caddy_calcsum
 *
 *              SECTION: Init
 *  114:     private function init( )
 *  128:     private function initInstances( )
 *  160:     public function initPidCaddy( $pidCaddy )
 *
 *              SECTION: Main
 *  192:     public function sum( $items, $options )
 *
 *              SECTION: Calculating Items
 *  238:     private function sumItems( $items )
 *
 *              SECTION: Calculating Options
 *  270:     private function sumOptions( $options )
 *  291:     private function sumOptionsGross( $options )
 *  310:     private function sumOptionsNet( $options )
 *  329:     private function sumOptionsTax( $options )
 *  352:     private function sumOptionsTaxNormal( $options )
 *  371:     private function sumOptionsTaxReduced( $options )
 *
 *              SECTION: Calculating OptionsWoPayment
 *  406:     private function sumOptionsWoPayment( $options )
 *  427:     private function sumOptionsWoPaymentGross( $options )
 *  445:     private function sumOptionsWoPaymentNet( $options )
 *  463:     private function sumOptionsWoPaymentTax( $options )
 *  486:     private function sumOptionsWoPaymentTaxNormal( $options )
 *  504:     private function sumOptionsWoPaymentTaxReduced( $options )
 *
 *              SECTION: Calculating Sum
 *  539:     private function sumSum( $items, $options )
 *  563:     private function sumSumGross( $items, $options )
 *  582:     private function sumSumNet( $items, $options )
 *  600:     private function sumSumQty( )
 *  625:     private function sumSumTax( $items, $options )
 *  649:     private function sumSumTaxNormal( $items, $options )
 *  668:     private function sumSumTaxReduced( $items, $options )
 *
 * TOTAL FUNCTIONS: 24
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	4.0.8
 * @since       2.0.0
 */
class tx_caddy_calcsum
{

  public  $extKey         = 'caddy';
  public  $prefixId       = 'tx_caddy_pi1';
  public  $scriptRelPath  = 'pi1/class.tx_caddy_pi1.php';

  private $conf           = null; // Current typoscript configuration
  public  $drs            = null;
  private $initInstances  = null;
  private $pidCaddy       = null;



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( )
  *
  * @param	array		$conf     : current typoscript configuration
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.3
  * @since      4.0.3
  */
  private function init( $conf= null )
  {
    $this->initInstances( );
    $this->initVars( $conf );
  }

 /**
  * initInstances( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.3
  * @since      4.0.3
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

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session          = t3lib_div::makeInstance( 'tx_caddy_session' );
    $this->session->setParentObject( $this );

  }

 /**
  * initPidCaddy( )
  *
  * @param	[type]		$$pidCaddy: ...
  * @return	void
  * @access public
  * @internal   #54628
  * @version    4.0.3
  * @since      4.0.3
  */
  public function initPidCaddy( $pidCaddy )
  {
    $this->pidCaddy = $pidCaddy;
  }

 /**
  * initVars( )
  *
  * @param	array		$conf     : current typoscript configuration
  * @return	void
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function initVars( $conf=null )
  {
    $this->conf = $conf;

  }



  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * sum( )  : Returns sum for
  *           * items
  *           * options
  *           * both (sum)
  *           for
  *           * gross
  *           * net
  *           * tax
  *             * normal
  *             * reduced
  *
  * @param	array		$items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @param	array		$conf     : current typoscript configuration
  * @return	array		$sum (see above)
  * @access public
  * @version    4.0.8
  * @since      2.0.2
  */
  public function sum( $items, $options, $conf=null )
  {
      // #54628, 131229, dwildt, 1+
    $this->init( $conf );

    $sumItems             = $this->sumItems( $items );
    $sumOptions           = $this->sumOptions( $options );
      // #i0039, 131230, dwildt, 1+
    $sumOptionsWoPayment  = $this->sumOptionsWoPayment( $options );
      // #i0047, 140302, dwildt, 1+
    $sumCashdiscount      = $this->sumCashdiscount( $options, $sumItems );

    $sum = array(
        // #i0047, 140302, dwildt, 1+
      'cashdiscount'      => $sumCashdiscount,
      'items'             => $sumItems,
      'options'           => $sumOptions,
        // #i0039, 131230, dwildt, 1+
      'optionswopayment'  => $sumOptionsWoPayment,
      'sum'               => $this->sumSum( $sumItems, $sumOptions, $sumCashdiscount ),
    );
//var_dump( __METHOD__, __LINE__, $sum );

    return $sum;
  }



  /***********************************************
  *
  * Calculating cash discount
  *
  **********************************************/

 /**
  * sumCashdiscount( )  : Returns sum for
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	array		$sum (see above)
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscount( $options, $sumItems )
  {
    $paymentId = $options['payment']['id'];

    $defArray = array(
      'gross' =>  0.00,
      'net'   =>  0.00,
      'tax'   => array(
        'normal'  =>  0.00,
        'reduced' =>  0.00,
        'sum'     =>  0.00
      )
    );

    $sumCashdiscount = array(
      'items' => $defArray,
      'options' => array(
        'payment'   =>  $defArray,
        'shipping'  =>  $defArray,
        'specials'  =>  $defArray
      ),
      'sum'   => $defArray
    );

    // RETURN : cash discount isn't configured
    if( ! $this->sumCashdiscountRequirements( $paymentId ) )
    {
      return $sumCashdiscount;
    }

    $confCashdiscount = $this->conf['api.']['options.']['payment.']['options.'][$paymentId . '.']['cash-discount.'];

    $sumCashdiscount = array(
      'items' => $this->sumCashdiscountItems( $confCashdiscount, $sumItems ),
      'options' => array(
        'payment'   =>  $this->sumCashdiscountOptionsPayment( $confCashdiscount, $options ),
        'shipping'  =>  $this->sumCashdiscountOptionsShipping( $confCashdiscount, $options ),
        'specials'  =>  $this->sumCashdiscountOptionsSpecials( $confCashdiscount, $options )
      ),
      'sum'   => $defArray
    );

    $sumCashdiscount = $this->sumCashdiscountSum( $sumCashdiscount );
    //var_dump( __METHOD__, __LINE__, $sumCashdiscount, $sumItems, $options );
//var_dump( __METHOD__, __LINE__, $sumCashdiscount );
//die( );

    return $sumCashdiscount;
  }

 /**
  * sumCashdiscountItems( ) : Returns cash discount for the items
  *
  * @param	array
  * @return	array
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscountItems( $confCashdiscount, $sumItems )
  {
      // RETURN : No cash discount for items
    if( ! $confCashdiscount['on.']['items'] )
    {
      return array(
        'gross' =>  0.00,
        'net'   =>  0.00,
        'tax'   => array(
          'normal'  =>  0.00,
          'reduced' =>  0.00
        )
      );
    }

    $percent  = ( double ) $confCashdiscount['percent'];

    return array(
      'gross' =>  ( double ) ($sumItems['gross']  * ( $percent / 100 ) ),
      'net'   =>  ( double ) ($sumItems['net']    * ( $percent / 100 ) ),
      'tax'   => array(
        'normal'  =>  ( double ) ($sumItems['tax']['normal']  * ( $percent / 100 ) ),
        'reduced' =>  ( double ) ($sumItems['tax']['reduced'] * ( $percent / 100 ) )
      )
    );
  }

 /**
  * sumCashdiscountOptionsPayment( ) : Returns cash discount for payment options
  *
  * @param	array
  * @return	array
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscountOptionsPayment( $confCashdiscount, $options )
  {
      // RETURN : No cash discount for items
    if( ! $confCashdiscount['on.']['payment'] )
    {
      return array(
        'gross' =>  0.00,
        'net'   =>  0.00,
        'tax'   => array(
          'normal'  =>  0.00,
          'reduced' =>  0.00
        )
      );
    }

    $percent  = ( double ) $confCashdiscount['percent'];

    return array(
      'gross' =>  ( double ) ($options['payment']['sum']['gross']  * ( $percent / 100 ) ),
      'net'   =>  ( double ) ($options['payment']['sum']['net']    * ( $percent / 100 ) ),
      'tax'   => array(
        'normal'  =>  ( double ) ($options['payment']['sum']['tax']['normal']  * ( $percent / 100 ) ),
        'reduced' =>  ( double ) ($options['payment']['sum']['tax']['reduced'] * ( $percent / 100 ) )
      )
    );
  }

 /**
  * sumCashdiscountOptionsShipping( ) : Returns cash discount for shipping
  *
  * @param	array
  * @return	array
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscountOptionsShipping( $confCashdiscount, $options )
  {
      // RETURN : No cash discount for items
    if( ! $confCashdiscount['on.']['shipping'] )
    {
      return array(
        'gross' =>  0.00,
        'net'   =>  0.00,
        'tax'   => array(
          'normal'  =>  0.00,
          'reduced' =>  0.00
        )
      );
    }

    $percent  = ( double ) $confCashdiscount['percent'];

    return array(
      'gross' =>  ( double ) ($options['shipping']['sum']['gross']  * ( $percent / 100 ) ),
      'net'   =>  ( double ) ($options['shipping']['sum']['net']    * ( $percent / 100 ) ),
      'tax'   => array(
        'normal'  =>  ( double ) ($options['shipping']['sum']['tax']['normal']  * ( $percent / 100 ) ),
        'reduced' =>  ( double ) ($options['shipping']['sum']['tax']['reduced'] * ( $percent / 100 ) )
      )
    );
  }

 /**
  * sumCashdiscountOptionsSpecials( ) : Returns cash discount for specials
  *
  * @param	array
  * @return	array
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscountOptionsSpecials( $confCashdiscount, $options )
  {
      // RETURN : No cash discount for items
    if( ! $confCashdiscount['on.']['specials'] )
    {
      return array(
        'gross' =>  0.00,
        'net'   =>  0.00,
        'tax'   => array(
          'normal'  =>  0.00,
          'reduced' =>  0.00
        )
      );
    }

    $percent  = ( double ) $confCashdiscount['percent'];

    return array(
      'gross' =>  ( double ) ($options['specials']['sum']['gross']  * ( $percent / 100 ) ),
      'net'   =>  ( double ) ($options['specials']['sum']['net']    * ( $percent / 100 ) ),
      'tax'   => array(
        'normal'  =>  ( double ) ($options['specials']['sum']['tax']['normal']  * ( $percent / 100 ) ),
        'reduced' =>  ( double ) ($options['specials']['sum']['tax']['reduced'] * ( $percent / 100 ) )
      )
    );
  }

 /**
  * sumCashdiscountRequirements( )  :
  *
  * @param	array		$paymentId  :
  * @return	boolean
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscountRequirements( $paymentId )
  {
    $confCashdiscount  = $this->conf['api.']['options.']['payment.']['options.'][$paymentId . '.']['cash-discount.'];

      // RETURN : cash discount isn't configured
    if( ! is_array( $confCashdiscount ) )
    {
      return false;
    }

      // RETURN : cash discount array on isn't configured
    if( ! is_array( $confCashdiscount['on.'] ) )
    {
      return false;
    }

      // RETURN : cash discount is 0.00
    if( ( double ) $confCashdiscount['percent'] <= ( double ) 0 )
    {
      return false;
    }

    return true;
  }

 /**
  * sumCashdiscountSum( ) : Sum the cash discount of items and the options payment, shipping and specials
  *
  * @param	array
  * @return	array
  * @access private
  * @version    4.0.8
  * @since      4.0.8
  */
  private function sumCashdiscountSum( $sumCashdiscount )
  {
      // Sum gross
    $sumCashdiscount['sum']['gross']  = $sumCashdiscount['items']['gross']
                                      + $sumCashdiscount['options']['payment']['gross']
                                      + $sumCashdiscount['options']['shipping']['gross']
                                      + $sumCashdiscount['options']['specials']['gross']
                                      ;
    $sumCashdiscount['sum']['gross']  = $sumCashdiscount['sum']['gross']
                                      * -1
                                      ;

      // Sum net
    $sumCashdiscount['sum']['net']  = $sumCashdiscount['items']['net']
                                    + $sumCashdiscount['options']['payment']['net']
                                    + $sumCashdiscount['options']['shipping']['net']
                                    + $sumCashdiscount['options']['specials']['net']
                                    ;
    $sumCashdiscount['sum']['net']  = $sumCashdiscount['sum']['net']
                                      * -1
                                      ;

    $sumCashdiscount['sum']['tax']['normal']  = $sumCashdiscount['items']['tax']['normal']
                                              + $sumCashdiscount['options']['payment']['tax']['normal']
                                              + $sumCashdiscount['options']['shipping']['tax']['normal']
                                              + $sumCashdiscount['options']['specials']['tax']['normal']
                                              ;
    $sumCashdiscount['sum']['tax']['normal']  = $sumCashdiscount['sum']['tax']['normal']
                                              * -1
                                              ;

    $sumCashdiscount['sum']['tax']['reduced'] = $sumCashdiscount['items']['tax']['reduced']
                                              + $sumCashdiscount['options']['payment']['tax']['reduced']
                                              + $sumCashdiscount['options']['shipping']['tax']['reduced']
                                              + $sumCashdiscount['options']['specials']['tax']['reduced']
                                              ;
    $sumCashdiscount['sum']['tax']['reduced'] = $sumCashdiscount['sum']['tax']['reduced']
                                              * -1
                                              ;

    $sumCashdiscount['sum']['tax']['sum'] = $sumCashdiscount['sum']['tax']['normal']
                                          + $sumCashdiscount['sum']['tax']['reduced']
                                          ;

    return $sumCashdiscount;
  }



  /***********************************************
  *
  * Calculating Items
  *
  **********************************************/

 /**
  * sum( )  : Returns sum for
  *           * items
  *           for
  *           * gross
  *           * net
  *           * tax
  *             * normal
  *             * reduced
  *
  * @param	array		$items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @return	$array		$sum (see above)
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumItems( $items )
  {
    $sum = $items['sum'];

    return $sum;
  }



  /***********************************************
  *
  * Calculating Options
  *
  **********************************************/


 /**
  * sumOptions( ) : Returns sum for
  *                 * options
  *                 for
  *                 * gross
  *                 * net
  *                 * tax
  *                 * normal
  *                 * reduced
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	array		$sum (see above)
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptions( $options )
  {
    $sum = array
    (
      'gross' =>  $this->sumOptionsGross( $options ),
      'net'   =>  $this->sumOptionsNet(   $options ),
      'tax'   =>  $this->sumOptionsTax(   $options ),
    );

    return $sum;
  }

 /**
  * sumOptionsGross( )  : Returns sum for options gross
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	double		$sum      : sum for options gross
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsGross( $options )
  {
    $sum  = $options['payment']['sum']['gross']
          + $options['shipping']['sum']['gross']
          + $options['specials']['sum']['gross']
          ;

    return $sum;
  }

 /**
  * sumOptionsNet( )  : Returns sum for options net
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	double		$sum      : sum for options gross
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsNet( $options )
  {
    $sum  = $options['payment']['sum']['net']
          + $options['shipping']['sum']['net']
          + $options['specials']['sum']['net']
          ;

    return $sum;
  }

 /**
  * sumOptionsTax( )  :
  *
  * @param	[type]		$$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsTax( $options )
  {
    $sum = array
    (
      'normal'  => $this->sumOptionsTaxNormal(  $options ),
      'reduced' => $this->sumOptionsTaxReduced( $options ),
      'sum'     => $this->sumOptionsTaxNormal(  $options )
                +  $this->sumOptionsTaxReduced( $options )
                ,
    );

    return $sum;
  }

 /**
  * sumOptionsTaxNormal( )  :
  *
  * @param	[type]		$$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsTaxNormal( $options )
  {
    $sum  = $options['payment']['sum']['tax']['normal']
          + $options['shipping']['sum']['tax']['normal']
          + $options['specials']['sum']['tax']['normal']
          ;

    return $sum;
  }

 /**
  * sumOptionsTaxReduced( )  :
  *
  * @param	[type]		$$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsTaxReduced( $options )
  {
    $sum  = $options['payment']['sum']['tax']['reduced']
          + $options['shipping']['sum']['tax']['reduced']
          + $options['specials']['sum']['tax']['reduced']
          ;

    return $sum;
  }



  /***********************************************
  *
  * Calculating OptionsWoPayment
  *
  **********************************************/


 /**
  * sumOptionsWoPayment( )  : Returns sum for
  *                           * shipping and specials (without payment)
  *                           for
  *                           * gross
  *                           * net
  *                           * tax
  *                           * normal
  *                           * reduced
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	array		$sum (see above)
  * @access private
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumOptionsWoPayment( $options )
  {
    $sum = array
    (
      'gross' =>  $this->sumOptionsWoPaymentGross( $options ),
      'net'   =>  $this->sumOptionsWoPaymentNet(   $options ),
      'tax'   =>  $this->sumOptionsWoPaymentTax(   $options ),
    );

    return $sum;
  }

 /**
  * sumOptionsWoPaymentGross( )  : Returns sum gross for shipping and specials
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	double		$sum      : sum gross for shipping and specials
  * @access private
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumOptionsWoPaymentGross( $options )
  {
    $sum  = $options['shipping']['sum']['gross']
          + $options['specials']['sum']['gross']
          ;

    return $sum;
  }

 /**
  * sumOptionsWoPaymentNet( )  : Returns sum net for shipping and specials
  *
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	double		$sum      : sum net for shipping and specials
  * @access private
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumOptionsWoPaymentNet( $options )
  {
    $sum  = $options['shipping']['sum']['net']
          + $options['specials']['sum']['net']
          ;

    return $sum;
  }

 /**
  * sumOptionsWoPaymentTax( )  :
  *
  * @param	[type]		$$options: ...
  * @return	array		:
  * @access private
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumOptionsWoPaymentTax( $options )
  {
    $sum = array
    (
      'normal'  => $this->sumOptionsWoPaymentTaxNormal(  $options ),
      'reduced' => $this->sumOptionsWoPaymentTaxReduced( $options ),
      'sum'     => $this->sumOptionsWoPaymentTaxNormal(  $options )
                +  $this->sumOptionsWoPaymentTaxReduced( $options )
                ,
    );

    return $sum;
  }

 /**
  * sumOptionsWoPaymentTaxNormal( )  :
  *
  * @param	[type]		$$options: ...
  * @return	array		:
  * @access private
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumOptionsWoPaymentTaxNormal( $options )
  {
    $sum  = $options['shipping']['sum']['tax']['normal']
          + $options['specials']['sum']['tax']['normal']
          ;

    return $sum;
  }

 /**
  * sumOptionsWoPaymentTaxReduced( )  :
  *
  * @param	[type]		$$options: ...
  * @return	array		:
  * @access private
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumOptionsWoPaymentTaxReduced( $options )
  {
    $sum  = $options['shipping']['sum']['tax']['reduced']
          + $options['specials']['sum']['tax']['reduced']
          ;

    return $sum;
  }



  /***********************************************
  *
  * Calculating Sum
  *
  **********************************************/


 /**
  * sumSum( ) : Returns sum for
  *             * both (sum): $items + $options
  *             for
  *             * gross
  *             * net
  *             * tax
  *               * normal
  *               * reduced
  *
  * @param	array		$items            : array with element sum with gross, net, tax.normal, tax.reduced
  * @param	array		$options          : array with element sum with gross, net, tax.normal, tax.reduced
  * @param	array		$sumCashdiscount  :
  * @return	array		$sum (see above)
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSum( $items, $options, $sumCashdiscount )
  {
    $sum = array
    (
        // #54628, 131229, dwildt, 1+
      'qty'   =>  $this->sumSumQty( ),
      'gross' =>  $this->sumSumGross( $items, $options )
              +   $sumCashdiscount['sum']['gross'],
      'net'   =>  $this->sumSumNet(   $items, $options )
              +   $sumCashdiscount['sum']['net'],
      'tax'   =>  $this->sumSumTax(   $items, $options, $sumCashdiscount['sum'] ),
    );

    return $sum;
  }

 /**
  * sumSumGross( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumGross( $items, $options )
  {
    $sum  = $items['gross']
          + $options['gross']
          ;

    return $sum;
  }

 /**
  * sumSumNet( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumNet( $items, $options )
  {
    $sum  = $items['net']
          + $options['net']
          ;

    return $sum;
  }

 /**
  * sumSumQty( )  :
  *
  * @return	integer
  * @access private
  * @internal   #54628
  * @version    4.0.3
  * @since      4.0.3
  */
  private function sumSumQty( )
  {
    $qty      = 0;
    $products = $this->session->productsGet( $this->pidCaddy );

    foreach( ( array ) $products as  $product )
    {
        $qty  = $qty
              + $product['qty']
              ;
    }

    return $qty;
  }

 /**
  * sumSumTax( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumTax( $items, $options, $cashdiscount )
  {
    $sum = array
    (
      'normal'  => $this->sumSumTaxNormal(  $items, $options, $cashdiscount ),
      'reduced' => $this->sumSumTaxReduced( $items, $options, $cashdiscount )
    );

    $sum['sum'] = $sum['normal']
                + $sum['reduced']
                 ;
    return $sum;
  }

 /**
  * sumSumTaxNormal( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumTaxNormal( $items, $options, $cashdiscount )
  {
    $sum  = $items['tax']['normal']
          + $options['tax']['normal']
          + $cashdiscount['tax']['normal']
          ;

    return $sum;
  }

 /**
  * sumSumTaxReduced( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumTaxReduced( $items, $options, $cashdiscount )
  {
    $sum  = $items['tax']['reduced']
          + $options['tax']['reduced']
          + $cashdiscount['tax']['reduced']
          ;

    return $sum;
  }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/caddy/class.tx_caddy_calcsum.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/caddy/class.tx_caddy_calcsum.php']);
}
?>
