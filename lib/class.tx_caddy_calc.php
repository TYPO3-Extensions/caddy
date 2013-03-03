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
 * Plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.0.0
 * @since       1.4.6
 */
class tx_caddy_calc extends tslib_pibase 
{
  public $prefixId      = 'tx_caddy_pi1';
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  public $extKey        = 'caddy';
	
 /**
  * calculateOptionById( )
  *
  * @return   array   $array : gross, net
  * @access public
  * @version    2.0.0
  * @since      1.4.6
  */
  public function calculateOptionById( $conf, $type, $option_id, &$obj ) 
  {
    $arrReturn = null; 
    
    $optionIds = $conf[$type.'.']['options.'][$option_id.'.'];

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
          list( $gross, $net ) = $this->calculateOption($conf, $type, $option_id, floatval($extra['extra']), $obj);
        }
        else
        {
          break;
        }
      }
    } 
    else 
    {
      switch( $optionIds['extra'] )
      {
        case 'each':
          $gross  = floatval($optionIds['extra.']['1.']['extra'])*$obj->cartCount;
          list( $gross, $net ) =  $this->calculateOption
                                  ( 
                                    $conf, $type, $option_id, $gross, $obj 
                                  );
          break;
        default:
          list( $gross, $net ) =  $this->calculateOption
                                  ( 
                                    $conf, $type, $option_id, floatval( $optionIds['extra'] ), $obj 
                                  );
      }
    }

    $arrReturn['gross'] = $gross;
    $arrReturn['net']   = $net;
    return $arrReturn;
  }
	
 /**
  * calculateOption( )
  *
  * @return   array   $array : gross, net
  * @access public
  * @version    2.0.0
  * @since      1.4.6
  */
  public function calculateOption( $conf, $type, $option_id, $gross, $obj ) 
  {
var_dump( __METHOD__, __LINE__, $gross );    
    $arrReturn = null; 

    $free_from  = $conf[$type.'.']['options.'][$option_id . '.']['free_from'];
    $free_until = $conf[$type.'.']['options.'][$option_id . '.']['free_until'];
    
    if( 
        ( isset( $free_from ) && ( floatval( $free_from ) <= $obj->cartGrossNoService ) )
        || 
        ( isset( $free_until ) && ( floatval( $free_until ) >= $obj->cartGrossNoService ) ) 
      ) 
    {
      $gross  = 0.0;
      $net    = 0.0;
    } 
    else 
    {
      if( $conf[$type.'.']['options.'][$option_id . '.']['tax'] == 'reduced' )
      { // reduced tax
        $net = $gross / ( 1.0 + $conf['tax.']['reducedCalc'] ); // add tax from this product to overall
      } 
      else 
      { // normal tax
        $net = $gross / ( 1.0 + $conf['tax.']['normalCalc'] ); // add tax from this product to overall
      }
    }

    $arrReturn['gross'] = $gross;
    $arrReturn['net']   = $net;
    return $arrReturn;
  }

  
}