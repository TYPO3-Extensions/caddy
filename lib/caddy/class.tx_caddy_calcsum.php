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
class tx_caddy_calcsum
{

  public $extKey        = 'caddy';
  public $prefixId      = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';



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
  * @param      array   $items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @param      array   $options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	array   $sum (see above) 
  * @access public
  * @version    2.0.2
  * @since      2.0.2
  */
  public function sum( $items, $options )
  {
    $items    = $this->sumItems( $arrItems );
    $options  = $this->sumOptions( $arrOptions );

    $sum = array( 
      'items'   => $items,
      'options' => $options,
      'sum'     => $this->sumSum( $items, $options ),
    );
    
    return $sum;
  }



  /***********************************************
  *
  * Calculation
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
  * @param      array   $items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @return	$array   $sum (see above) 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumItems( $arrItems )
  {
    $sum = $arrItems;
    
    return $sum;
  }

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
  * @param      array   $options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	array   $sum (see above) 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptions( $arrOptions )
  {
    $sum = array
    ( 
      'gross' =>  $this->sumOptionsGross( $arrOptions ),
      'net'   =>  $this->sumOptionsNet(   $arrOptions ),
      'tax'   =>  $this->sumOptionsTax(   $arrOptions ),
    );
    
    return $sum;
  }

 /**
  * sumOptionsGross( )  : Returns sum for options gross
  *
  * @param      array   $options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	double    $sum      : sum for options gross 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsGross( $arrOptions )
  {
    $sum  = $arrOptions['payment']['sum']['gross']
          + $arrOptions['shipping']['sum']['gross']
          + $arrOptions['specials']['sum']['gross']
          ;

    return $sum;
  }

 /**
  * sumOptionsNet( )  : Returns sum for options net
  *
  * @param      array   $options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	double    $sum      : sum for options gross 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsNet( $arrOptions )
  {
    $sum  = $arrOptions['payment']['sum']['net']
          + $arrOptions['shipping']['sum']['net']
          + $arrOptions['specials']['sum']['net']
          ;

    return $sum;
  }

 /**
  * sumOptionsTax( )  : 
  *
  * @return	array   : 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsTax( $arrOptions )
  {
    $sum = array
    ( 
      'normal'  => $this->sumOptionsTaxReduced( $arrOptions ),
      'reduced' => $this->sumOptionsTaxNormal(  $arrOptions ),
    );
    
    return $sum;
  }

 /**
  * sumOptionsTaxNormal( )  : 
  *
  * @return	array   : 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsTaxNormal( $arrOptions )
  {
    $sum  = $arrOptions['payment']['sum']['tax']['normal']
          + $arrOptions['shipping']['sum']['tax']['normal']
          + $arrOptions['specials']['sum']['tax']['normal']
          ;
    
    return $sum;
  }

 /**
  * sumOptionsTaxReduced( )  : 
  *
  * @return	array   : 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumOptionsTaxReduced( $arrOptions )
  {
    $sum  = $arrOptions['payment']['sum']['tax']['reduced']
          + $arrOptions['shipping']['sum']['tax']['reduced']
          + $arrOptions['specials']['sum']['tax']['reduced']
          ;
    
    return $sum;
  }

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
  * @param      array   $items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @param      array   $options  : array with element sum with gross, net, tax.normal, tax.reduced
  * @return	array   $sum (see above) 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSum( $items, $options )
  {
    $sum = array
    ( 
      'gross' =>  $this->sumSumGross( $items, $options ),
      'net'   =>  $this->sumSumNet(   $items, $options ),
      'tax'   =>  $this->sumSumTax(   $items, $options ),
    );
    
    return $sum;
  }

 /**
  * sumSumGross( )  : 
  *
  * @return	array   : 
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
  * @return	array   : 
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
  * sumSumTax( )  : 
  *
  * @return	array   : 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumTax( $items, $options )
  {
    $sum = array
    ( 
      'normal'  => $this->sumSumTaxReduced( $items, $options ),
      'reduced' => $this->sumSumTaxNormal(  $items, $options ),
    );
    
    return $sum;
  }

 /**
  * sumSumTaxNormal( )  : 
  *
  * @return	array   : 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumTaxNormal( $items, $options )
  {
    $sum  = $items['tax']['normal']
          + $options['tax']['normal']
          ;
    
    return $sum;
  }

 /**
  * sumSumTaxReduced( )  : 
  *
  * @return	array   : 
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function sumSumTaxReduced( $items, $options )
  {
    $sum  = $items['tax']['normal']
          + $options['tax']['normal']
          ;
    
    return $sum;
  }

  
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/caddy/class.tx_caddy_calcsum.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/caddy/class.tx_caddy_calcsum.php']);
}
?>
