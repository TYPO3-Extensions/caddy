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
 *   72: class tx_caddy_calcsum
 *
 *              SECTION: Main
 *  106:     public function sum( $items, $options )
 *
 *              SECTION: Calculating Items
 *  144:     private function sumItems( $items )
 *
 *              SECTION: Calculating Options
 *  176:     private function sumOptions( $options )
 *  197:     private function sumOptionsGross( $options )
 *  216:     private function sumOptionsNet( $options )
 *  235:     private function sumOptionsTax( $options )
 *  255:     private function sumOptionsTaxNormal( $options )
 *  274:     private function sumOptionsTaxReduced( $options )
 *
 *              SECTION: Calculating Sum
 *  310:     private function sumSum( $items, $options )
 *  332:     private function sumSumGross( $items, $options )
 *  351:     private function sumSumNet( $items, $options )
 *  370:     private function sumSumTax( $items, $options )
 *  391:     private function sumSumTaxNormal( $items, $options )
 *  410:     private function sumSumTaxReduced( $items, $options )
 *
 * TOTAL FUNCTIONS: 14
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
  * @param	array		$items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @param	array		$options  : array with options payment, shipping, specials and sum with gross, net, tax.normal, tax.reduced
  * @return	array		$sum (see above)
  * @access public
  * @version    2.0.2
  * @since      2.0.2
  */
  public function sum( $items, $options )
  {
    $items    = $this->sumItems( $items );
    $options  = $this->sumOptions( $options );

    $sum = array(
      'items'   => $items,
      'options' => $options,
      'sum'     => $this->sumSum( $items, $options ),
    );

    return $sum;
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
      'normal'  => $this->sumOptionsTaxReduced( $options ),
      'reduced' => $this->sumOptionsTaxNormal(  $options ),
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
  * @param	array		$items    : array with element sum with gross, net, tax.normal, tax.reduced
  * @param	array		$options  : array with element sum with gross, net, tax.normal, tax.reduced
  * @return	array		$sum (see above)
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
  * sumSumTax( )  :
  *
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
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
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
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
  * @param	[type]		$$items: ...
  * @param	[type]		$options: ...
  * @return	array		:
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
