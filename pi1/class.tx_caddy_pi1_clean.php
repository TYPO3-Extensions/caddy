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
 *  115:     private function database( )
 *  143:     private function numbers( )
 *  157:     private function numbersInvoice( )
 *  176:     private function numbersDeliveryorder( )
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

    $this->sessionUpdate( );
    $this->database( );
  }



  /***********************************************
  *
  * Database
  *
  **********************************************/

 /**
  * database( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function database( )
  {
    $sesArray = null;
    $time     = time( );

      // Get the session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
      // RETURN : any product, don't increase numbers!
    if( empty( $sesArray['products'] ) )
    {
      if( $this->pObj->drs->drsError )
      {
        $prompt = 'There isn\'t any product! Maybe powermail form is sent twice!';
        t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
        $prompt = 'There won\'t added any new record to the database.';
        t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 2 );
        $prompt = 'GET/POST tx_powermail_pi1 will removed! This prevents another e-mail.';
        t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 2 );
      }
      unset( $_GET[ 'tx_powermail_pi1' ] );
      unset( $_POST[ 'tx_powermail_pi1' ] );
      return;
    }
      // RETURN : any product, don't increase numbers!
    
    
    $this->local_cObj->start( $sesArray, $this->pObj->conf['db.']['table'] );
    
      // DRS
    if( $this->pObj->drs->drsCobj )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    $customerEmail = $this->getPmFieldEmailCustomerEmail( );

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
    $pdfDeliveryorderToVendor   = false;
    $pdfInvoiceToCustomer       = false;
    $pdfInvoiceToVendor         = false;
    $pdfTermsToCustomer         = false;
    $pdfTermsToVendor           = false;

    if( ! empty ( $sesArray['sendCustomerDeliveryorder'] ) ) 
    {
      $pdfDeliveryorderToCustomer = true;
    }
    if( ! empty ( $sesArray['sendVendorDeliveryorder'] ) ) 
    {
      $pdfDeliveryorderToVendor = true;
    }
    if( ! empty ( $sesArray['sendCustomerInvoice'] ) ) 
    {
      $pdfInvoiceToCustomer = true;
    }
    if( ! empty ( $sesArray['sendVendorInvoice'] ) ) 
    {
      $pdfInvoiceToVendor = true;
    }
    if( ! empty ( $sesArray['sendCustomerTerms'] ) ) 
    {
      $pdfTermsToCustomer = true;
    }
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
        break;
    }
    switch( true )
    {
      case( $pdfInvoiceToCustomer ):
      case( $pdfInvoiceToVendor ):
        $fileInvoice  = $this->local_cObj->cObjGetSingle
                        (
                          $this->pObj->conf['pdf.']['invoice.']['filename'],
                          $this->pObj->conf['pdf.']['invoice.']['filename.']
                        );
        break;
    }
    switch( true )
    {
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
      'customerEmail'               => $customerEmail,
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

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, a tx_caddy_order record is inserted into the database.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
    
  }



  /***********************************************
  *
  * Get powermail fields
  *
  **********************************************/

 /**
  * getDeliveryorderAddress( )  : Get the delivery order Address from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderAddress( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderAddress;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderCity( )  : Get the delivery order City from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderCity( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderCity;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderCompany( )  : Get the delivery order Company from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderCompany( )
  {                                  
    $pmUid  = $this->pObj->flexform->deliveryorderCompany;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderCountry( )  : Get the delivery order Country from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderCountry( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderCountry;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderFirstname( )  : Get the delivery order Firstname from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderFirstname( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderFirstname;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderLastname( )  : Get the delivery order Lastname from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderLastname( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderLastname;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderZip( )  : Get the delivery order Zip from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderZip( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderZip;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getPmFieldEmailCustomerEmail( )  : Get the customer e-mail from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldEmailCustomerEmail( )
  {
    $pmUid  = $this->pObj->flexform->emailCustomerEmail;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceAddress( )  : Get the invoice Address from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceAddress( )
  {
    $pmUid  = $this->pObj->flexform->invoiceAddress;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceCity( )  : Get the invoice City from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceCity( )
  {
    $pmUid  = $this->pObj->flexform->invoiceCity;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceCompany( )  : Get the invoice Company from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceCompany( )
  {                                  
    $pmUid  = $this->pObj->flexform->invoiceCompany;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceCountry( )  : Get the invoice Country from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceCountry( )
  {
    $pmUid  = $this->pObj->flexform->invoiceCountry;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceFirstname( )  : Get the invoice Firstname from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceFirstname( )
  {
    $pmUid  = $this->pObj->flexform->invoiceFirstname;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceLastname( )  : Get the invoice Lastname from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceLastname( )
  {
    $pmUid  = $this->pObj->flexform->invoiceLastname;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceZip( )  : Get the invoice Zip from powermail POST params
  *
  * @return	string          $value  : the value
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceZip( )
  {
    $pmUid  = $this->pObj->flexform->invoiceZip;
    $value  = $this->pObj->powermail->paramPostById( $pmUid );
    return $value;
  }
    


  /***********************************************
  *
  * Session
  *
  **********************************************/

 /**
  * sessionUpdate( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sessionUpdate( )
  {
    $this->sessionUpdateDeliveryorder( );
    $this->sessionUpdateInvoice( );
  }

 /**
  * sessionUpdateDeliveryorder( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sessionUpdateDeliveryorder( )
  {
      // Get the session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    
    $sesArray['deliveryorderAddress']   = $this->getPmFieldDeliveryorderAddress( );
    $sesArray['deliveryorderCity']      = $this->getPmFieldDeliveryorderCity( );
    $sesArray['deliveryorderCompany']   = $this->getPmFieldDeliveryorderCompany( );
    $sesArray['deliveryorderCountry']   = $this->getPmFieldDeliveryorderCountry( );
    $sesArray['deliveryorderFirstname'] = $this->getPmFieldDeliveryorderFirstname( );
    $sesArray['deliveryorderLastname']  = $this->getPmFieldDeliveryorderLastname( );
    $sesArray['deliveryorderZip']       = $this->getPmFieldDeliveryorderZip( );
    
    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
    $GLOBALS['TSFE']->storeSessionData(); // Save session

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'Delivery order data are added to the session.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }

 /**
  * sessionUpdateInvoice( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function sessionUpdateInvoice( )
  {
      // Get the session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    
    $sesArray['invoiceAddress']   = $this->getPmFieldInvoiceAddress( );
    $sesArray['invoiceCity']      = $this->getPmFieldInvoiceCity( );
    $sesArray['invoiceCompany']   = $this->getPmFieldInvoiceCompany( );
    $sesArray['invoiceCountry']   = $this->getPmFieldInvoiceCountry( );
    $sesArray['invoiceFirstname'] = $this->getPmFieldInvoiceFirstname( );
    $sesArray['invoiceLastname']  = $this->getPmFieldInvoiceLastname( );
    $sesArray['invoiceZip']       = $this->getPmFieldInvoiceZip( );
    
    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
    $GLOBALS['TSFE']->storeSessionData(); // Save session

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'Invoice data are added to the session.';
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
