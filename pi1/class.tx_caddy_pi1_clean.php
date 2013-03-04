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

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   53: class tx_caddy_pi1_clean
 *   78:     public function main( )
 *  115:     private function cleanDatabase( )
 *  143:     private function cleanNumbers( )
 *  157:     private function cleanNumbersInvoice( )
 *  176:     private function cleanNumbersDeliveryorder( )
 *  195:     private function cleanSession( )
 *
 * TOTAL FUNCTIONS: 6
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
 * @since       1.4.6
 */
class tx_caddy_pi1_clean
{

  public $prefixId = 'tx_caddy_pi1';

  // same as class name
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1_clean.php';

  // path to this script relative to the extension dir.
  public $extKey = 'caddy';

    // Parent object
  public $pObj = null;
    // Current row
  public $row = null;
  
  private $local_cObj = null;


 /**
  * main( )
  *
  * @return	void
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function main( )
  {
    $this->local_cObj = $this->pObj->local_cObj;

      // RETURN : powermail form isn't sent. Nothing to clean
    if( empty( $this->pObj->powermail->sent ) )
    {
        // DRS
      if( $this->pObj->drs->drsClean )
      {
        $prompt = 'The powermail form isn\'t sent, nothing to clean up.';
        t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
      }
        // DRS
      return;
    }
      // RETURN : powermail form isn't sent. Nothing to clean

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent. Database, numbers and session will cleaned up.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

    $this->cleanDatabase( );
    $this->cleanNumbers( );
  }

 /**
  * cleanDatabase( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanDatabase( )
  {
    $sesArray = null;
    $time     = time( );

      // Get the session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
    
    $this->local_cObj->start( $sesArray, $this->pObj->conf['db.']['table'] );
//var_dump( __METHOD__, __LINE__, $this->local_cObj->data );    

      // Initiate files
    $fileDeliveryorder  = null;
    $fileInvoice        = null;
    $fileTerms          = null;
      // Initiate files
    
      // Get numbers
    $numberDeliveryorder  = $sesArray['numberDeliveryorderCurrent'];
    $numberInvoice        = $sesArray['numberInvoiceCurrent'];
    $numberOrder          = $sesArray['numberOrderCurrent'];
      // Get numbers

      // Get quantity
    $quantity = count( $sesArray['products'] );
    
      // Get pdf is sent to ...
    $pdfDeliveryorderToCustomer = false;
    if( ! empty ( $sesArray['sendCustomerDeliveryorder'] ) ) 
    {
      $pdfDeliveryorderToCustomer = true;
    }
    $pdfDeliveryorderToVendor = false;
    if( ! empty ( $sesArray['sendVendorDeliveryorder'] ) ) 
    {
      $pdfDeliveryorderToVendor = true;
    }
    $pdfInvoiceToCustomer = false;
    if( ! empty ( $sesArray['sendCustomerInvoice'] ) ) 
    {
      $pdfInvoiceToCustomer = true;
    }
    $pdfInvoiceToVendor = false;
    if( ! empty ( $sesArray['sendVendorInvoice'] ) ) 
    {
      $pdfInvoiceToVendor = true;
    }
    $pdfTermsToCustomer = false;
    if( ! empty ( $sesArray['sendCustomerTerms'] ) ) 
    {
      $pdfTermsToCustomer = true;
    }
    $pdfTermsToVendor = false;
    if( ! empty ( $sesArray['sendVendorTerms'] ) ) 
    {
      $pdfTermsToVendor = true;
    }
      // Get pdf is sent to ...
    
      // Set files
    switch( true )
    {
      case( $pdfDeliveryorderToCustomer ):
      case( $pdfDeliveryorderToVendor ):
        $fileDeliveryorder  = $this->local_cObj->cObjGetSingle
                              (
                                $this->pObj->conf['pdf.']['deliveryorder.']['filename'],
                                $this->pObj->conf['pdf.']['deliveryorder.']['filename.']
                              );
var_dump( __METHOD__, __LINE__, $this->pObj->conf['pdf.']['deliveryorder.']['filename.'], $fileDeliveryorder );    
//        break;
      case( $pdfInvoiceToCustomer ):
      case( $pdfInvoiceToVendor ):
        $fileInvoice  = $this->local_cObj->cObjGetSingle
                        (
                          $this->pObj->conf['pdf.']['invoice.']['filename'],
                          $this->pObj->conf['pdf.']['invoice.']['filename.']
                        );
//        break;
      case( $pdfTermsToCustomer ):
      case( $pdfTermsToVendor ):
        $fileTerms  = $this->local_cObj->cObjGetSingle
                      (
                        $this->pObj->conf['pdf.']['terms.']['filename'],
                        $this->pObj->conf['pdf.']['terms.']['filename.']
                      );
        break;
    }
      // Set files

      // Get total sum
    $sumGross       = $sesArray['sumGross'];
    $sumNet         = $sesArray['sumNet'];
    $sumTaxNormal   = $sesArray['sumTaxNormal'];
    $sumTaxReduced  = $sesArray['sumTaxReduced'];
      // Get total sum

      // Set record
    $insertFields = array(
      'pid'                         => $this->pObj->pid,
      'tstamp'                      => $time,
      'crdate'                      => $time,
      'fileDeliveryorder'           => $fileDeliveryorder,
      'fileInvoice'                 => $fileInvoice,
      'fileTerms'                   => $fileTerms,
      'items'                       => '',
      'numberDeliveryorder'         => $numberDeliveryorder,
      'numberInvoice'               => $numberInvoice,
      'numberOrder'                 => $numberOrder,
      'pdfDeliveryorderToCustomer'  => $pdfDeliveryorderToCustomer,
      'pdfDeliveryorderToVendor'    => $pdfDeliveryorderToVendor,
      'pdfInvoiceToCustomer'        => $pdfInvoiceToCustomer,
      'pdfInvoiceToVendor'          => $pdfInvoiceToVendor,
      'pdfTermsToCustomer'          => $pdfTermsToCustomer,
      'pdfTermsToVendor'            => $pdfTermsToVendor,
      'quantity'                    => $quantity,
      'sumGross'                    => $sumGross,
      'sumNet'                      => $sumNet,
      'sumTaxNormal'                => $sumTaxNormal,
      'sumTaxReduced'               => $sumTaxReduced,
    );
      // Set record

      // Insert record
    $GLOBALS['TYPO3_DB']->exec_INSERTquery( 'tx_caddy_order', $insertFields );

var_dump( __METHOD__, __LINE__, $sesArray );    
die( );  

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, a tx_caddy_order record is inserted into the database.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
    
  }

 /**
  * cleanNumbers( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbers( )
  {
    $this->cleanNumbersInvoice( );
    $this->cleanNumbersDeliveryorder( );
  }

 /**
  * cleanNumbersDeliveryorder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbersDeliveryorder( )
  {
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, please clean up the delivery order number.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }

 /**
  * cleanNumbersInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbersInvoice( )
  {
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, please clean up the invoice number.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }

 /**
  * cleanNumbersOrder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function cleanNumbersOrder( )
  {
      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, please clean up the order number.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_clean.php'])
{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_clean.php']);
}
?>
