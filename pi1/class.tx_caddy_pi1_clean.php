<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2015 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 *   81: class tx_caddy_pi1_clean
 *  110:     public function main( )
 *
 *              SECTION: Database
 *  159:     private function database( )
 *  234:     private function databaseCancelWiEpaymentError( )
 *  265:     private function databaseCancelWoItems( )
 *  302:     private function databaseFieldsFiles( $sesArray )
 *  430:     private function databaseFieldsNumbers( $sesArray )
 *  452:     private function databaseFieldsSum( $sesArray )
 *
 *              SECTION: Get powermail fields
 *  482:     private function getPmFieldDeliveryorderAddress( )
 *  497:     private function getPmFieldDeliveryorderCity( )
 *  512:     private function getPmFieldDeliveryorderCompany( )
 *  527:     private function getPmFieldDeliveryorderCountry( )
 *  542:     private function getPmFieldDeliveryorderFirstname( )
 *  557:     private function getPmFieldDeliveryorderLastname( )
 *  572:     private function getPmFieldDeliveryorderZip( )
 *  587:     private function getPmFieldEmailCustomerEmail( )
 *  603:     private function getPmFieldInvoiceAddress( )
 *  618:     private function getPmFieldInvoiceCity( )
 *  633:     private function getPmFieldInvoiceCompany( )
 *  648:     private function getPmFieldInvoiceCountry( )
 *  663:     private function getPmFieldInvoiceFirstname( )
 *  678:     private function getPmFieldInvoiceLastname( )
 *  693:     private function getPmFieldInvoiceZip( )
 *
 *              SECTION: Init
 *  718:     public function initPidCaddy( $pidCaddy=null )
 *
 *              SECTION: Session
 *  744:     private function sessionUpdate( )
 *  758:     private function sessionUpdateDeliveryorder( )
 *  797:     private function sessionUpdateInvoice( )
 *
 * TOTAL FUNCTIONS: 26
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	4.0.7
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

  private $pidCaddy = null;

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
    $this->init( $this->pidCaddy );

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

      // #55726, 140207, dwildt, 1+
    $this->stock( );
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
  * @version    4.0.5
  * @since      2.0.0
  */
  private function database( )
  {
    $sesArray = null;
    $time     = time( );

    if( $this->databaseCancelWoItems( ) )
    {
      return;
    }

    if( $this->databaseCancelWiEpaymentError( ) )
    {
      return;
    }

    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
    $this->local_cObj->start( $sesArray, $this->pObj->conf['db.']['table'] );

      // DRS
    if( $this->pObj->drs->drsCobj )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // Set record
    $record = array(
      'pid'           => $this->pObj->pid,
      'tstamp'        => $time,
      'crdate'        => $time,
      'customerEmail' => $this->getPmFieldEmailCustomerEmail( ),
      'items'         => '',
      'quantity'      => count( $sesArray['products'] ),
    );
    $record = $record
            + $this->databaseFieldsFiles(   $sesArray )
            + $this->databaseFieldsNumbers( $sesArray )
            + $this->databaseFieldsSum(     $sesArray )
            ;

      // Insert record
    $GLOBALS['TYPO3_DB']->exec_INSERTquery( 'tx_caddy_order', $record );
    $error  = $GLOBALS['TYPO3_DB']->sql_error( );

      // exit in case of error
    if( ! empty( $error ) )
    {
      $query = $GLOBALS['TYPO3_DB']->INSERTquery( 'tx_caddy_order', $record );
      $prompt = '<h1>caddy: SQL-Error</h1>'
              . '<p>'.$error.'</p>'
              . '<p>'.$query.'</p>';
      die( $prompt );
    }
      // exit in case of error

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'The powermail form is sent, a tx_caddy_order record is inserted into the database.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS

  }

 /**
  * databaseCancelWiEpaymentError( )
  *
  * @return	boolen
  * @access private
  * @version    4.0.5
  * @since      4.0.5
  */
  private function databaseCancelWiEpaymentError( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );
      // RETURN : e-payment transaction was successful
    if( ! $sesArray['e-payment']['powermail']['error'] )
    {
      return false;
    }

    unset( $_GET[ 'tx_powermail_pi1' ] );
    unset( $_POST[ 'tx_powermail_pi1' ] );

    $prompt = 'E-payment error is set by powermail';
    t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
    $prompt = 'There won\'t added any new record to the database.';
    t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 2 );
    $prompt = 'GET/POST tx_powermail_pi1 will removed! This prevents another e-mail.';
    t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 2 );

    return true;
  }

 /**
  * databaseCancelWoItems( )
  *
  * @return	boolen
  * @access private
  * @version    4.0.5
  * @since      2.0.0
  */
  private function databaseCancelWoItems( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );

      // RETURN : any product, don't increase numbers!
    if( ! empty( $sesArray['products'] ) )
    {
      return false;
    }

    unset( $_GET[ 'tx_powermail_pi1' ] );
    unset( $_POST[ 'tx_powermail_pi1' ] );

    if( ! $this->pObj->drs->drsError )
    {
      return true;
    }

    $prompt = 'There isn\'t any product! Maybe powermail form is sent twice!';
    t3lib_div::devlog( '[ERROR/SESSION] ' . $prompt, $this->extKey, 3 );
    $prompt = 'There won\'t added any new record to the database.';
    t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 2 );
    $prompt = 'GET/POST tx_powermail_pi1 will removed! This prevents another e-mail.';
    t3lib_div::devlog( '[WARN/SESSION] ' . $prompt, $this->extKey, 2 );

    return true;
  }

 /**
  * databaseFieldsFiles( )
  *
  * @param	array		$sesArray :
  * @return	void
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function databaseFieldsFiles( $sesArray )
  {
      // Initiate files
    $fileDeliveryorder  = null;
    $fileInvoice        = null;
    $fileRevocation     = null;
    $fileTerms          = null;
      // Initiate files

      // Get pdf is sent to ...
    $pdfDeliveryorderToCustomer = false;
    $pdfDeliveryorderToVendor   = false;
    $pdfInvoiceToCustomer       = false;
    $pdfInvoiceToVendor         = false;
    $pdfRevocationToCustomer    = false;
    $pdfRevocationToVendor      = false;
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
    if( ! empty ( $sesArray['sendCustomerRevocation'] ) )
    {
      $pdfRevocationToCustomer = true;
    }
    if( ! empty ( $sesArray['sendVendorRevocation'] ) )
    {
      $pdfRevocationToVendor = true;
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
      case( $pdfRevocationToCustomer ):
      case( $pdfRevocationToVendor ):
        $fileRevocation  = $this->local_cObj->cObjGetSingle
                      (
                        $this->pObj->conf['pdf.']['revocation.']['filename'],
                        $this->pObj->conf['pdf.']['revocation.']['filename.']
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

    $record = array
    (
      'fileDeliveryorder'           => $fileDeliveryorder,
      'fileInvoice'                 => $fileInvoice,
      'fileRevocation'              => $fileRevocation,
      'fileTerms'                   => $fileTerms,
      'pdfDeliveryorderToCustomer'  => $pdfDeliveryorderToCustomer,
      'pdfDeliveryorderToVendor'    => $pdfDeliveryorderToVendor,
      'pdfInvoiceToCustomer'        => $pdfInvoiceToCustomer,
      'pdfInvoiceToVendor'          => $pdfInvoiceToVendor,
      'pdfRevocationToCustomer'     => $pdfRevocationToCustomer,
      'pdfRevocationToVendor'       => $pdfRevocationToVendor,
      'pdfTermsToCustomer'          => $pdfTermsToCustomer,
      'pdfTermsToVendor'            => $pdfTermsToVendor,
    );
      // Set record

    return $record;
  }

 /**
  * databaseFieldsNumbers( )
  *
  * @param	array		$sesArray :
  * @return	void
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function databaseFieldsNumbers( $sesArray )
  {
    $record = array
    (
      'numberDeliveryorder' => $sesArray['numberDeliveryorderCurrent'],
      'numberInvoice'       => $sesArray['numberInvoiceCurrent'],
      'numberOrder'         => $sesArray['numberOrderCurrent'],
    );
      // Set record

    return $record;
  }

 /**
  * databaseFieldsSum( )
  *
  * @param	array		$sesArray :
  * @return	void
  * @access private
  * @version    2.0.2
  * @since      2.0.2
  */
  private function databaseFieldsSum( $sesArray )
  {
    $record = array
    (
      'sumGross'      => $sesArray['sum']['sum']['gross'],
      'sumNet'        => $sesArray['sum']['sum']['net'],
      'sumTaxNormal'  => $sesArray['sum']['sum']['tax']['normal'],
      'sumTaxReduced' => $sesArray['sum']['sum']['tax']['reduced'],
    );
      // Set record

    return $record;
  }

 /***********************************************
  *
  * Getting methods
  *
  **********************************************/

/**
 * getCObj( ) : Get the content object
 *
 * @return	object    $cObj : content object
 * @access public
 * @internal   #55726
 * @version    4.0.7
 * @since      4.0.7
 */
  public function getCObj( )
  {
    return $this->cObj;
  }

/**
 * getConf( ) : Get the TypoScript configuration array
 *
 * @return	array   $conf : TypoScript configuration
 * @access public
 * @internal   #55726
 * @version    4.0.7
 * @since      4.0.7
 */
  public function getConf( )
  {
    return $this->conf;
  }


/**
 * getDrs( ) : Get the DRS object
 *
 * @return	object   $drs : DRS object
 * @access public
 * @internal   #55726
 * @version    4.0.7
 * @since      4.0.7
 */
  public function getDrs( )
  {
    return $this->drs;
  }

/**
 * getPid( )  : Returns the globlas tsfe id, if the given pid is null
 *
 * @param	integer		$pid  : given pid (may be null)
 * @return	integer		$pid  : id of the page with the caddy plugin
 * @internal    #54634
 * @access      public
 * @version     4.0.7
 * @since       4.0.7
 */
  public function getPid( $pid=null )
  {
    $this->initPidCaddy( $pid );
    return $this->pidCaddy;
  }



  /***********************************************
  *
  * Get powermail fields
  *
  **********************************************/

 /**
  * getDeliveryorderAddress( )  : Get the delivery order Address from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderAddress( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderAddress;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderCity( )  : Get the delivery order City from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderCity( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderCity;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderCompany( )  : Get the delivery order Company from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderCompany( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderCompany;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderCountry( )  : Get the delivery order Country from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderCountry( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderCountry;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderFirstname( )  : Get the delivery order Firstname from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderFirstname( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderFirstname;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderLastname( )  : Get the delivery order Lastname from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderLastname( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderLastname;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getDeliveryorderZip( )  : Get the delivery order Zip from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldDeliveryorderZip( )
  {
    $pmUid  = $this->pObj->flexform->deliveryorderZip;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getPmFieldEmailCustomerEmail( )  : Get the customer e-mail from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldEmailCustomerEmail( )
  {
    $pmUid  = $this->pObj->flexform->emailCustomerEmail;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceAddress( )  : Get the invoice Address from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceAddress( )
  {
    $pmUid  = $this->pObj->flexform->invoiceAddress;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceCity( )  : Get the invoice City from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceCity( )
  {
    $pmUid  = $this->pObj->flexform->invoiceCity;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceCompany( )  : Get the invoice Company from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceCompany( )
  {
    $pmUid  = $this->pObj->flexform->invoiceCompany;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceCountry( )  : Get the invoice Country from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceCountry( )
  {
    $pmUid  = $this->pObj->flexform->invoiceCountry;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceFirstname( )  : Get the invoice Firstname from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceFirstname( )
  {
    $pmUid  = $this->pObj->flexform->invoiceFirstname;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceLastname( )  : Get the invoice Lastname from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceLastname( )
  {
    $pmUid  = $this->pObj->flexform->invoiceLastname;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }

 /**
  * getInvoiceZip( )  : Get the invoice Zip from powermail POST params
  *
  * @return	string		$value  : the value
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function getPmFieldInvoiceZip( )
  {
    $pmUid  = $this->pObj->flexform->invoiceZip;
    $value  = $this->pObj->powermail->getFieldById( $pmUid );
    return $value;
  }



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( )
  *
  * @param	integer		$pidCaddy: pid of the page with the current Caddy plugin
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function init( $pidCaddy=null )
  {
    $this->initVars( );
    $this->initInstances( );
    $this->initPidCaddy( $pidCaddy );
  }

 /**
  * initInstances( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function initInstances( )
  {
    if( ! ( $this->initInstances === null ) )
    {
      return;
    }

    $this->initInstancesSession( );
    $this->initInstancesStockmanager( );

    $this->initInstances = true;
  }


 /**
  * initInstancesSession( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function initInstancesSession( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'class.tx_caddy_session.php' );
    $this->session = t3lib_div::makeInstance( 'tx_caddy_session' );
    $this->session->setParentObject( $this );
  }

 /**
  * initInstancesStockmanager( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function initInstancesStockmanager( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy_stockmanager.php' );
    $this->stockmanager = t3lib_div::makeInstance( 'tx_caddy_stockmanager' );
    $this->stockmanager->setParentObject( $this );
  }

 /**
  * initVars( )
  *
  * @return	void
  * @access private
  * @internal   #54628
  * @version    4.0.7
  * @since      4.0.7
  */
  private function initVars( )
  {
    $this->cObj       = $this->pObj->cObj;
    $this->conf       = $this->pObj->conf;
    $this->drs        = $this->pObj->drs;
    $this->local_cObj = $this->pObj->local_cObj;
  }

 /**
  * initPidCaddy( )
  *
  * @param	integer		$pidCaddy: pid of the page with the current Caddy plugin
  * @return	void
  * @access public
  * @internal   #54628
  * @version    4.0.3
  * @since      4.0.3
  */
  public function initPidCaddy( $pidCaddy=null )
  {
    $this->pidCaddy = ( int ) $pidCaddy;
    if( $pidCaddy === null )
    {
      $this->pidCaddy = ( int ) $GLOBALS["TSFE"]->id;
    }
//var_dump( __METHOD__, __LINE__, $this->pidCaddy );
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
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );

    $sesArray['deliveryorderAddress']   = $this->getPmFieldDeliveryorderAddress( );
    $sesArray['deliveryorderCity']      = $this->getPmFieldDeliveryorderCity( );
    $sesArray['deliveryorderCompany']   = $this->getPmFieldDeliveryorderCompany( );
    $sesArray['deliveryorderCountry']   = $this->getPmFieldDeliveryorderCountry( );
    $sesArray['deliveryorderFirstname'] = $this->getPmFieldDeliveryorderFirstname( );
    $sesArray['deliveryorderLastname']  = $this->getPmFieldDeliveryorderLastname( );
    $sesArray['deliveryorderZip']       = $this->getPmFieldDeliveryorderZip( );

    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
    // #54634, 131229, dwildt, 1+
    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $this->pidCaddy, $sesArray); // Generate new session
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
    // #54634, 131229, dwildt, 1-
    //$sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    // #54634, 131229, dwildt, 1+
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );

    $sesArray['invoiceAddress']   = $this->getPmFieldInvoiceAddress( );
    $sesArray['invoiceCity']      = $this->getPmFieldInvoiceCity( );
    $sesArray['invoiceCompany']   = $this->getPmFieldInvoiceCompany( );
    $sesArray['invoiceCountry']   = $this->getPmFieldInvoiceCountry( );
    $sesArray['invoiceFirstname'] = $this->getPmFieldInvoiceFirstname( );
    $sesArray['invoiceLastname']  = $this->getPmFieldInvoiceLastname( );
    $sesArray['invoiceZip']       = $this->getPmFieldInvoiceZip( );

    // #54634, 131229, dwildt, 1-
    //$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
    // #54634, 131229, dwildt, 1+
    $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $this->pidCaddy, $sesArray); // Generate new session
    $GLOBALS['TSFE']->storeSessionData(); // Save session

      // DRS
    if( $this->pObj->drs->drsClean )
    {
      $prompt = 'Invoice data are added to the session.';
      t3lib_div::devlog( '[INFO/CLEAN] ' . $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
  }



  /***********************************************
  *
  * Stock
  *
  **********************************************/

 /**
  * stock( )
  *
  * @return	void
  * @access private
  * @internal   #55726
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stock( )
  {
    $this->stockCheck( );
    $this->stockUpdate( );
//var_dump( __METHOD__, __LINE__ );
//die( );
  }

 /**
  * stockCheck( )
  *
  * @return	void
  * @access private
  * @internal   #55726
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stockCheck( )
  {
    $itemsSession = $this->session->productsGet( $this->pidCaddy );

    foreach( $itemsSession as $itemSession )
    {
      $uid          = $itemSession['uid'];
      $qtyByOrder   = $itemSession['qty'];
      $itemRealLive = $this->session->productGet( $uid );
      $qtyInStock   = $this->stockmanager->getItemQuantity( $itemRealLive );

      switch( true )
      {
        case( $qtyByOrder > $qtyInStock ):
        case( $qtyByOrder < 1 ):
          $content  = null;
          $conf     = null;
          $this->session->sessionDelete( $content, $conf, $this->pidCaddy );
          $title = $itemSession['title'];
          $this->stockmanager->dieQtyError( $title, $qtyByOrder, $qtyInStock );
          break;
        default:
            // follow the workflow
          break;
      }
    }
  }

 /**
  * stockUpdate( )
  *
  * @return	void
  * @access private
  * @internal   #55726
  * @version    4.0.7
  * @since      4.0.7
  */
  private function stockUpdate( )
  {
    $items = $this->session->productsGet( $this->pidCaddy );
    $this->stockmanager->itemsUpdate( $items );
//var_dump( __METHOD__, __LINE__, $items );
//die( );
  }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_clean.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_clean.php']);
}
?>
