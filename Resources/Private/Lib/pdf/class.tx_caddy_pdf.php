<?php

/* * *************************************************************
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
 * ************************************************************* */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  111: class tx_caddy_pdf extends tslib_pibase
 *
 *              SECTION: Caddy
 *  147:     private function caddy( )
 *
 *              SECTION: Delivery Order
 *  188:     public function deliveryorder( )
 *  297:     private function deliveryorderAdditionalTextblocks( )
 *  324:     private function deliveryorderAddress( $fallBackToInvoiceAddress=false )
 *  364:     private function deliveryorderDate( )
 *  379:     private function deliveryorderInit( )
 *  422:     private function deliveryorderNumbers( )
 *  448:     private function deliveryorderTermOfCredit( )
 *
 *              SECTION: Init
 *  474:     private function init( $case )
 *  509:     private function initCheckDir( )
 *  529:     private function initCheckProducts( )
 *  553:     private function initInstances( )
 *  582:     private function initTemplate( $case='invoice')
 *  627:     private function initTemplateTable( $case )
 *
 *              SECTION: Invoice
 *  677:     public function invoice( )
 *  786:     private function invoiceAdditionalTextblocks( )
 *  812:     private function invoiceAddress( )
 *  837:     private function invoiceDate( )
 *  851:     private function invoiceInit( )
 *  894:     private function invoiceNumbers( )
 *  920:     private function invoiceTermOfCredit( )
 *
 *              SECTION: Set
 *  943:     public function setParentObject( $pObj )
 *
 *              SECTION: TCPDF
 *  978:     private function tcpdfInit( $srceFile )
 * 1024:     private function tcpdfOutput( $destPath )
 * 1059:     private function tcpdfSetFont( $font )
 * 1086:     private function tcpdfSetFontStretching( $font )
 * 1115:     private function tcpdfSetTextColor( $properties )
 * 1169:     private function tcpdfWriteHtmlCell( $properties, $htmlContent, $drsLabel )
 *
 *              SECTION: Revocation
 * 1221:     public function revocation( )
 * 1323:     private function revocationAdditionalTextblocks( )
 * 1349:     private function revocationAddress( )
 * 1363:     private function revocationDate( )
 * 1377:     private function revocationInit( )
 *
 *              SECTION: Terms
 * 1428:     public function terms( )
 * 1530:     private function termsAdditionalTextblocks( )
 * 1556:     private function termsAddress( )
 * 1570:     private function termsDate( )
 * 1584:     private function termsInit( )
 *
 *              SECTION: Write
 * 1637:     private function writeTextblock( $textBlock, $drsLabel )
 * 1669:     private function writeTextblockAddHeader( $header )
 *
 *              SECTION: ZZ
 * 1703:     private function zz_hexToRgb( $hex )
 * 1803:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 42
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
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
 * Class 'pdf' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version     6.1.2
 * @since       2.0.0
 */
class tx_caddy_pdf extends tslib_pibase
{

// path for localisation file
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  public $extKey = 'caddy';
  public $drsUserfunc = null;
  public $conf = null;
  private $confPdf = null;
  private $currentAttachment = null; // deliveryorder || invoice || revocation || terms
  public $local_cObj = null;
// [Object]
  private $pObj = null;
  private $pdfTemplateLastPage = 0;
  private $pdfPageCurrent = 1;
// [Object]
  private $tcpdf = null;
  public $tmpl = null;

  /*   * *********************************************
   *
   * Caddy
   *
   * ******************************************** */

  /**
   * caddy( ) : Generates the HTML caddy and write it to tcpdf
   *
   * @param string $attachment: delveryorder, invoice
   * @return	void
   * @version    6.2.0
   * @since      2.0.0
   */
  private function caddy( $attachment = 'deliveryorder' )
  {
    $caddy = $this->caddy->caddy();
    //var_dump( __METHOD__, __LINE__, $caddy );
    $marker = $caddy[ 'marker' ];
    $subparts = $caddy[ 'subparts' ];
    $tmpl = $caddy[ 'tmpl' ];
    unset( $caddy );

    $content = $content . $this->cObj->substituteMarkerArrayCached
                    (
                    $tmpl, $marker, $subparts
    );

    $content = $this->dynamicMarkers->main( $content, $this ); // Fill dynamic locallang or typoscript markers
    $content = preg_replace( '|###.*?###|i', '', $content ); // Finally clear not filled markers
    //var_dump( __METHOD__, __LINE__, $content );
    //die( );
    // write the HTML content
    $body = $this->confPdf[ $attachment . '.' ][ 'content.' ][ 'caddy.' ][ 'body.' ];
    $this->tcpdfWriteHtmlCell( $body[ 'properties.' ], $content, 'caddy' );
  }

  /*   * *********************************************
   *
   * Delivery Order
   *
   * ******************************************** */

  /**
   * deliveryorder( ) : Generates the PDF delivery order and returns the path to the PDF file
   *
   * @return	string		$path : path to the rendered pdf file
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function deliveryorder()
  {
    $destFile = null;
    $destPath = null;

// Init caddy pdf
    $this->init( 'deliveryorder' );

// RETURN : any pdf is requested
    if ( !$this->deliveryorderInit() )
    {
      return;
    }
// RETURN : any pdf is requested
// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

// Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf[ 'db.' ][ 'table' ] );

// DRS
    if ( $this->pObj->drsUserfunc )
    {
      $data = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
// DRS
// Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
            (
            $this->confPdf[ 'deliveryorder.' ][ 'filename' ], $this->confPdf[ 'deliveryorder.' ][ 'filename.' ]
    );
// #i0079, 150416, dwildt, 1+/-
//$destPath = 'uploads/tx_caddy/' . $destFile;
    $destPath = PATH_site . 'uploads/tx_caddy/' . $destFile;
// Get the path of the destination file
// RETURN : destination file already exists
// #i0079, 150416, dwildt, 1+/-
//if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    if ( file_exists( $destPath ) )
    {
// DRS
      if ( $this->pObj->drsUserfunc )
      {
// #i0015, 130611, dwildt, 1-
//$prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
// #i0015, 130611, dwildt, 1+
        $prompt = 'uploads/tx_caddy' . '/' . $destFile . ' exists and will overridden!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
// DRS
// #i0015, 130611, dwildt, 1-
//return $destPath;
    }
// RETURN : destination file already exists
// PDF source file
    $srceFile = $sesArray[ 'sendVendorDeliveryorder' ];
    if ( empty( $srceFile ) )
    {
      $srceFile = $sesArray[ 'sendCustomerDeliveryorder' ];
    }
    if ( empty( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
              'sendCustomerDeliveryorder/sendVendorDeliveryorder<br />' . PHP .
              __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
// PDF source file
// Init tcpdf
    $this->tcpdfInit( $srceFile );
    $this->tcpdfAddPage();

// Write the delivery order address
    $fallBackToInvoiceAddress = true;
    $this->deliveryorderAddress( $fallBackToInvoiceAddress );

// Write the delivery order date
    $this->deliveryorderDate();

// Write the delivery order number
    $this->deliveryorderNumbers();

// Write the delivery order term of credit
    $this->deliveryorderTermOfCredit();

// Write additional textblocks
    $this->deliveryorderAdditionalTextblocks();

// Write the caddy
    $this->caddy( 'deliveryorder' );

// Create the PDF
    $this->tcpdfOutput( $destPath );

// RETURN :
    return $destPath;
  }

  /**
   * deliveryorderAdditionalTextblocks( ) : Write addional text blocks
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function deliveryorderAdditionalTextblocks()
  {
    $additionalTextblocks = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'additionaltextblocks.' ];

// LOOP : additional textblocks
    foreach ( array_keys( ( array ) $additionalTextblocks ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[ $key ], 'deliveryorder.additionaltextblocks.' . $key );
    }
// LOOP : additional textblocks
  }

  /**
   * deliveryorderAddress( ) : Write the delivery address. If delivery address isn't given,
   *                           take the invoice address
   *
   * @param	[type]		$$fallBackToInvoiceAddress: ...
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function deliveryorderAddress( $fallBackToInvoiceAddress = false )
  {
// Get the body content
    $body = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'address.' ][ 'deliveryorder.' ][ 'body.' ];
    $htmlContent = $GLOBALS[ 'TSFE' ]->cObj->cObjGetSingle( $body[ 'content' ], $body[ 'content.' ] );
    $content = strip_tags( $htmlContent );
    $content = trim( $content );
    $content = str_replace( ' ', null, $content );
// Get the body content

    switch ( true )
    {
      case(!empty( $content ) ):
        $deliveryorderaddress = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'address.' ][ 'deliveryorder.' ];
        $this->writeTextblock( $deliveryorderaddress, 'deliveryorderaddress' );
        break;
      case( empty( $content ) ):
      default:
// FALLBACK : take the invoice address
        if ( $fallBackToInvoiceAddress )
        {
          $invoiceaddress = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'address.' ][ 'invoice.' ];
          $this->writeTextblock( $invoiceaddress, 'invoiceAddress' );
        }
// FALLBACK : take the invoice address
        break;
    }
    unset( $htmlContent );

    return;
  }

  /**
   * deliveryorderDate( ) : Write the current date
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function deliveryorderDate()
  {
    $date = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'date.' ];
    $this->writeTextblock( $date, 'deliveryorderDate' );
  }

  /**
   * deliveryorderInit( ) :  Return false, if delivery order should not sent to the customer or
   *                         the vendor
   *
   * @return	boolean		$deliveryorderInit: false, if delivery order should not sent
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function deliveryorderInit()
  {
    $deliveryorderInit = null;

// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

// RETURN : any pdf is requested
    switch ( true )
    {
      case(!empty( $sesArray[ 'sendCustomerDeliveryorder' ] ) ):
      case(!empty( $sesArray[ 'sendVendorDeliveryorder' ] ) ):
        $deliveryorderInit = true;
        break;
      default:
        $deliveryorderInit = false;
        break;
    }
    unset( $sesArray );

    if ( !$deliveryorderInit )
    {
// DRS
      if ( $this->pObj->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
// DRS
    }
// RETURN : any pdf is requested

    return $deliveryorderInit;
  }

  /**
   * deliveryorderNumbers( ) : Write the delivery order number and maybe numbers for invoice and order
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function deliveryorderNumbers()
  {
    $numbers = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'numbers.' ];

// LOOP : fields, the elements of a product
    foreach ( array_keys( ( array ) $numbers ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $numbers[ $key ], 'deliveryorder.numbers.' . $key );
    }
  }

  /**
   * deliveryorderTermOfCredit( ) : Write the current date
   *
   * @return	void
   * @access private
   * @version    2.0.8
   * @since      2.0.8
   * @internal: #i0021
   */
  private function deliveryorderTermOfCredit()
  {
    $termOfCredit = $this->confPdf[ 'deliveryorder.' ][ 'content.' ][ 'termOfCredit.' ];
    $this->writeTextblock( $termOfCredit, 'deliveryorderTermOfCredit' );
  }

  /*   * *********************************************
   *
   * Init
   *
   * ******************************************** */

  /**
   * init( ) : Init some global variables and some classes
   *           Dies in case
   *           * of an unproper dir
   *           * of no products
   *
   * @param	$string		$case : deliveryorder || invoice || revocation || terms
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function init( $case )
  {
    $this->conf = $GLOBALS[ 'TSFE' ]->tmpl->setup[ 'plugin.' ][ 'tx_caddy_pi1.' ];
    $this->confPdf = $this->conf[ 'pdf.' ];

    $this->cObj = $GLOBALS[ 'TSFE' ]->cObj;
    $this->local_cObj = $GLOBALS[ 'TSFE' ]->cObj;


    $this->pi_loadLL();

    // DIE  : if there is an unproper directory
    $this->initCheckDir();

    // DIE  : if there isn't any product
    $this->initCheckProducts();

    $this->currentAttachment = $case;

    $this->initInstances();
    $this->initTemplate( $case );

    $this->caddy->setParentObject( $this );
    $this->caddy->setContentRow( $this->cObj->data );
  }

  /**
   * initCheckDir( ) : Checks if uploads/tx_caddy is a directory.
   *                   And dies, if not,
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initCheckDir()
  {
// CHECK: Is directory for PDF available?
    if ( !is_dir( 'uploads/tx_caddy' ) )
    {
      $prompt = 'FATAL ERROR: uploads/tx_caddy not found!<br />
        ' . __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
  }

  /**
   * initCheckProducts( ) :  Checks, if there is one product at least.
   *                         And dies, if not,
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initCheckProducts()
  {
// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

    if ( empty( $sesArray[ 'products' ] ) )
    {
      $prompt = 'ERROR: products are empty!<br />' . PHP_EOL .
              'Please go back to the page you visited before.<br />' . PHP_EOL .
              'Sorry for the trouble.<br />' . PHP_EOL .
              'TYPO3 Caddy<br />' . PHP_EOL .
              __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
  }

  /**
   * initInstances( )
   *
   * @return	void
   * @access private
   * @version    6.1.2
   * @since      2.0.0
   */
  private function initInstances()
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'Resources/Private/Lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy = t3lib_div::makeInstance( 'tx_caddy' );

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );

// #52313, 130926, dwildt, 3-
// #52165, 130921, dwildt
//    require_once( $path2lib . 'pdf/tcpdf_6.0.031/tcpdf.php' );
//    require_once( $path2lib . 'pdf/fpdi/fpdi.php' );
// #52313, 130926, dwildt, 2+
    require_once( t3lib_extMgm::extPath( 't3_tcpdf' ) . 'class.tx_t3_tcpdf.php' );
// #i0089, 150612, dwildt, 1-/+
//require_once( $path2lib . 'pdf/fpdi/fpdi.php' );
    require_once( $path2lib . 'pdf/FPDI-1.5.4/fpdi.php' );
  }

  /**
   * initTemplate( )
   *
   * @param	$string		$case : deliveryorder || invoice || revocation || terms
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initTemplate( $case = 'invoice' )
  {
    switch ( $case )
    {
      case( 'deliveryorder' ):
      case( 'invoice' ):
        $file = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'file' ];
        $markerAll = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'marker.' ][ 'all' ];
        $markerItem = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'marker.' ][ 'item' ];
        break;
      case( 'revocation' ):
      case( 'terms' ):
// #i0013, 130604, dwildt, 2-
//// Template for caddy isn't needed
//return;
// #i0013, 130604, dwildt, 3+
        $file = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'file' ];
        $markerAll = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'marker.' ][ 'all' ];
        $markerItem = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'marker.' ][ 'item' ];
        break;
      default:
        $prompt = 'Unproper value in switch. Case is "' . $case . '"'
                . PHP
                . __METHOD__ . '(line ' . __LINE__ . ')';
        die( $prompt );
        break;
    }

    $tmplFile = $GLOBALS[ 'TSFE' ]->cObj->fileResource( $file );
    $this->tmpl = array();
    $this->tmpl[ 'all' ] = $GLOBALS[ 'TSFE' ]->cObj->getSubpart( $tmplFile, $markerAll );
    $this->tmpl[ 'item' ] = $GLOBALS[ 'TSFE' ]->cObj->getSubpart( $this->tmpl[ 'all' ], $markerItem );

    $this->initTemplateTable( $case );
  }

  /**
   * initTemplate( )
   *
   * @param	$string		$case : deliveryorder || invoice || revocation || terms
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initTemplateTable( $case )
  {
    switch ( $case )
    {
      case( 'deliveryorder' ):
      case( 'invoice' ):
// #i0014, 130604, dwildt, 1-
//$table = $this->conf['templates.']['pdf.'][$case . '.']['marker.']['table.'];
// #i0014, 130604, dwildt, 1+
        $table = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'table.' ];
        break;
      case( 'revocation' ):
      case( 'terms' ):
// #i0013, 130604, dwildt, 2-
//          // Template for caddy isn't needed
//        return;
// #i0013, #i0014, 130604, dwildt, 1+
        $table = $this->conf[ 'templates.' ][ 'pdf.' ][ $case . '.' ][ 'table.' ];
        break;
      default:
        $prompt = 'Unproper value in switch. Case is "' . $case . '"'
                . PHP
                . __METHOD__ . '(line ' . __LINE__ . ')';
        die( $prompt );
        break;
    }

    foreach ( ( array ) $table as $property => $value )
    {
      $marker = '###' . strtoupper( $property ) . '###';
      $this->tmpl[ 'all' ] = str_replace( $marker, $value, $this->tmpl[ 'all' ] );
      $this->tmpl[ 'item' ] = str_replace( $marker, $value, $this->tmpl[ 'item' ] );
    }
  }

  /*   * *********************************************
   *
   * Invoice
   *
   * ******************************************** */

  /**
   * invoice( ) : Generates the PDF invoice and returns the path to the PDF file
   *
   * @return	string		$path : path to the rendered pdf file
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function invoice()
  {
    $destFile = null;
    $destPath = null;

    // Init caddy pdf
    $this->init( 'invoice' );

    // RETURN : any pdf is requested
    if ( !$this->invoiceInit() )
    {
      return;
    }
    // RETURN : any pdf is requested
    // Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

    // Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf[ 'db.' ][ 'table' ] );

    // DRS
    if ( $this->pObj->drsUserfunc )
    {
      $data = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
    // Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
            (
            $this->confPdf[ 'invoice.' ][ 'filename' ], $this->confPdf[ 'invoice.' ][ 'filename.' ]
    );
    // #i0079, 150416, dwildt, 1+/-
    //$destPath = 'uploads/tx_caddy/' . $destFile;
    $destPath = PATH_site . 'uploads/tx_caddy/' . $destFile;
    // Get the path of the destination file
    // RETURN : destination file already exists
    // #i0079, 150416, dwildt, 1+/-
    //if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    if ( file_exists( $destPath ) )
    {
      // DRS
      if ( $this->pObj->drsUserfunc )
      {
        // #i0015, 130611, dwildt, 1-
        //$prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
        // #i0015, 130611, dwildt, 1+
        $prompt = 'uploads/tx_caddy' . '/' . $destFile . ' exists and will overridden!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
      // DRS
      // #i0015, 130611, dwildt, 1-
      //return $destPath;
    }
    // RETURN : destination file already exists
    // PDF source file
    $srceFile = $sesArray[ 'sendVendorInvoice' ];
//var_dump(__METHOD__, __LINE__, $srceFile );
//exit();
    if ( empty( $srceFile ) )
    {
      $srceFile = $sesArray[ 'sendCustomerInvoice' ];
    }
    if ( empty( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
              'sendCustomerInvoice/sendVendorInvoice<br />' . PHP .
              __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
    // PDF source file
    // Init tcpdf
    $this->tcpdfInit( $srceFile );
    $this->tcpdfAddPage();

    // Write the invoice order address
    $fallBackToInvoiceAddress = false;
    $this->invoiceAddress( $fallBackToInvoiceAddress );

    // Write the invoice order date
    $this->invoiceDate();

    // Write the invoice order number
    $this->invoiceNumbers();

    // Write the caddy
    $this->caddy( 'invoice' );

    // Write the invoice order number
    $this->invoiceTermOfCredit();

    // Write additional textblocks
    $this->invoiceAdditionalTextblocks();

    // Create the PDF
    $this->tcpdfOutput( $destPath );

    // RETURN :
    return $destPath;
  }

  /**
   * invoiceAdditionalTextblocks( ) : Write addional text blocks
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function invoiceAdditionalTextblocks()
  {
    $additionalTextblocks = $this->confPdf[ 'invoice.' ][ 'content.' ][ 'additionaltextblocks.' ];

// LOOP : additional textblocks
    foreach ( array_keys( ( array ) $additionalTextblocks ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[ $key ], 'invoice.additionaltextblocks.' . $key );
    }
// LOOP : additional textblocks
  }

  /**
   * invoiceAddress( ) : Write the invoice address
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function invoiceAddress()
  {
    $invoiceaddress = $this->confPdf[ 'invoice.' ][ 'content.' ][ 'address.' ];

// LOOP : additional textblocks
    foreach ( array_keys( ( array ) $invoiceaddress ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $invoiceaddress[ $key ], 'invoice.address.' . $key );
    }
// LOOP : additional textblocks
  }

  /**
   * invoiceDate( ) : Write the current date
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function invoiceDate()
  {
    $date = $this->confPdf[ 'invoice.' ][ 'content.' ][ 'date.' ];
    $this->writeTextblock( $date, 'invoiceDate' );
  }

  /**
   * invoiceInit( ) : Return false, if invoice should not sent to the customer or the vendor
   *
   * @return	boolean		$invoiceInit  : false, if invoice should not sent
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function invoiceInit()
  {
    $invoiceInit = null;

    // Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

    // RETURN : any pdf is requested
    switch ( true )
    {
      case(!empty( $sesArray[ 'sendCustomerInvoice' ] ) ):
      case(!empty( $sesArray[ 'sendVendorInvoice' ] ) ):
        $invoiceInit = true;
        break;
      default:
        $invoiceInit = false;
        break;
    }
    unset( $sesArray );

    if ( !$invoiceInit )
    {
      // DRS
      if ( $this->pObj->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
      // DRS
    }
    // RETURN : any pdf is requested

    return $invoiceInit;
  }

  /**
   * invoiceNumbers( ) : Write the delivery order number and maybe numbers for invoice and order
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function invoiceNumbers()
  {
    $numbers = $this->confPdf[ 'invoice.' ][ 'content.' ][ 'numbers.' ];

// LOOP : fields, the elements of a product
    foreach ( array_keys( ( array ) $numbers ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $numbers[ $key ], 'invoice.numbers.' . $key );
    }
  }

  /**
   * invoiceTermOfCredit( ) : Write the current date
   *
   * @return	void
   * @access private
   * @version    2.0.8
   * @since      2.0.8
   * @internal: #i0021
   */
  private function invoiceTermOfCredit()
  {
    $termOfCredit = $this->confPdf[ 'invoice.' ][ 'content.' ][ 'termOfCredit.' ];
    $this->writeTextblock( $termOfCredit, 'invoiceTermOfCredit' );
  }

  /*   * *********************************************
   *
   * Set
   *
   * ******************************************** */

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
  }

  /*   * *********************************************
   *
   * TCPDF
   *
   * ******************************************** */

  /**
   * tcpdfPageConf( )
   *
   * @return	array
   * @access private
   * @version    6.2.0
   * @since      6.2.0
   */
  private function tcpdfPageConf()
  {
    $confPages = $this->confPdf[ $this->currentAttachment . '.' ][ 'page.' ];

    // Return current array
    if ( is_array( $confPages[ $this->pdfPageCurrent . '.' ] ) )
    {
      $confPage = $confPages[ $this->pdfPageCurrent . '.' ];
      return $confPage;
    }

    // Return last array
    for ( $pageCurrent = $this->pdfPageCurrent; $pageCurrent >= 1; $pageCurrent = $pageCurrent - 1 )
    {
      if ( is_array( $confPages[ $pageCurrent . '.' ] ) )
      {
        $confPage = $confPages[ $pageCurrent . '.' ];
        return $confPage;
      }
    }

    // Return default array
    $confPage = array(
      'autopagebreak' => 40,
      'margin.' => array(
        'top' => 20,
        'right' => 10,
        'bottom' => 20,
        'left' => 20,
      )
    );
    return $confPage;
  }

  /**
   * tcpdfInit( ) : Init a TCPDF object
   *
   *             http://www.setasign.de/support/manuals/fpdi/
   *             http://www.setasign.de/products/pdf-php-solutions/fpdi/demos/
   *
   * @param	string		$filename : current filename
   * @return	void
   * @access private
   * @internal   http://www.tcpdf.org/doc/code/classTCPDF.html
   * @version    6.2.0
   * @since      2.0.0
   */
  private function tcpdfInit( $srceFile )
  {
// #52313
    $this->tcpdf = new FPDI( );

    $author = $this->pi_getLL( 'docauthor' );
    $title = $this->pi_getLL( 'doctitle' );

    $this->tcpdf->SetAutoPageBreak( false );
    $this->tcpdf->SetAuthor( $author );
    $this->tcpdf->SetTitle( $title );
    // $this->tcpdf->SetSubject('TYPO3 Caddy Order Subject');
    // $this->tcpdf->SetKeywords('TYPO3, caddy');
    // remove default header/footer
    $this->tcpdf->setPrintHeader( false );
    $this->tcpdf->setPrintFooter( false );

    // get the page count
    $this->pdfTemplateLastPage = $this->tcpdf->setSourceFile( $srceFile );
  }

  /**
   * tcpdfAddPage( ) :
   *
   * @return	void
   * @version    6.2.0
   * @since      6.2.0
   */
  private function tcpdfAddPage()
  {
    $this->tcpdfAddPageWiTemplate();
    $this->tcpdfAddPageWoTemplate();
  }

  /**
   * tcpdfAddPageWiTemplate( ) :
   *
   * @param	object $tcpdf: TCPDF object
   * @return	void
   * @version    6.2.0
   * @since      6.2.0
   */
  private function tcpdfAddPageWiTemplate()
  {
    if ( $this->pdfTemplateLastPage < 1 )
    {
      return;
    }

    $nextTemplatePage = $this->tcpdf->getPage() + 1;
    if ( $nextTemplatePage > $this->pdfTemplateLastPage )
    {
      $nextTemplatePage = $this->pdfTemplateLastPage;
    }

    // get id of the next template page
    $templateId = $this->tcpdf->importPage( $nextTemplatePage );
    // get the size of the template
    $size = $this->tcpdf->getTemplateSize( $templateId );

    // create a page (landscape or portrait depending on the imported template size)
    if ( $size[ 'w' ] > $size[ 'h' ] )
    {
      $this->tcpdf->AddPage( 'L', array( $size[ 'w' ], $size[ 'h' ] ) );
    }
    else
    {
      $this->tcpdf->AddPage( 'P', array( $size[ 'w' ], $size[ 'h' ] ) );
    }

    // use the template
    // Abscissa of the upper-left corner.
    $x = 0;
    // Ordinate of the upper-left corner.
    $y = 0;
    // Width of the template in the page. If not specified or equal to zero, it is automatically calculated.
    $w = 210;
    // Height of the template in the page. If not specified or equal to zero, it is automatically calculated.
    $h = 297;
    // If this parameter is set to true the page size will be adjusted to the size of the imported page.
    $adjustPageSize = true;
    $this->tcpdf->useTemplate( $templateId, $x, $y, $w, $h, $adjustPageSize );
  }

  /**
   * tcpdfAddPageWoTemplate( ) :
   *
   * @param	object $tcpdf: TCPDF object
   * @return	void
   * @version    6.2.0
   * @since      6.2.0
   */
  private function tcpdfAddPageWoTemplate()
  {
    if ( $this->pdfTemplateLastPage > 0 )
    {
      return;
    }

    $this->tcpdf->AddPage();
  }

  /**
   * tcpdfInitBak( ) : Init a TCPDF object and returns ist
   *
   *             http://www.setasign.de/support/manuals/fpdi/
   *             http://www.setasign.de/products/pdf-php-solutions/fpdi/demos/
   *
   * @param	string		$filename : current filename
   * @return	object		$tcpdf    : TCPDF object
   * @access private
   * @internal   http://www.tcpdf.org/doc/code/classTCPDF.html
   * @version    2.0.0
   * @since      2.0.0
   */
  private function tcpdfInitBak( $srceFile )
  {
// #52313
    $tcpdf = new FPDI( );

    $author = $this->pi_getLL( 'docauthor' );
    $title = $this->pi_getLL( 'doctitle' );

    $tcpdf->SetAuthor( $author );
    $tcpdf->SetTitle( $title );
//    $tcpdf->SetSubject('TYPO3 Caddy Order Subject');
//    $tcpdf->SetKeywords('TYPO3, caddy');
// remove default header/footer
    $tcpdf->setPrintHeader( false );
    $tcpdf->setPrintFooter( false );

    $tcpdf->AddPage();
    $numbersOfPages = $tcpdf->setSourceFile( $srceFile );
    unset( $numbersOfPages );
    $tmplId = $tcpdf->importPage( 1 );
// Abscissa of the upper-left corner.
    $x = 0;
// Ordinate of the upper-left corner.
    $y = 0;
// Width of the template in the page. If not specified or equal to zero, it is automatically calculated.
    $w = 210;
// Height of the template in the page. If not specified or equal to zero, it is automatically calculated.
    $h = 297;
// If this parameter is set to true the page size will be adjusted to the size of the imported page.
    $adjustPageSize = true;
    $tcpdf->useTemplate( $tmplId, $x, $y, $w, $h, $adjustPageSize );

    return $tcpdf;
  }

  /**
   * tcpdfOutput( ) :  Write the PDF file to the given path.
   *                   Dies, if file could written.
   *
   * @param	string		$destPath : path of the destination file
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function tcpdfOutput( $destPath )
  {

    $this->tcpdf->Output( $destPath, 'F' );

// DIE  : PDF could not written
    if ( !file_exists( $destPath ) )
    {
      $prompt = $destPath . ' could not written!<br />
        ' . __METHOD__ . ' (' . __LINE__ . ')';
      die( $prompt );
    }
// DIE  : PDF could not written
// DRS
    if ( file_exists( $destPath ) )
    {
      if ( $this->pObj->drsUserfunc )
      {
        $prompt = $destPath . ' is written.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
    }
// DRS
  }

  /**
   * tcpdfSetFont( ) : Set the font properties family, size and style
   *
   * @param	[type]		$$font: ...
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function tcpdfSetFont( $font )
  {
    $family = $this->zz_cObjGetSingle( $font[ 'family' ], $font[ 'family.' ] );
    $size = $this->zz_cObjGetSingle( $font[ 'size' ], $font[ 'size.' ] );
    $style = $this->zz_cObjGetSingle( $font[ 'style' ], $font[ 'style.' ] );
    $this->tcpdf->SetFont( $family, $style, $size );

// DRS
    if ( $this->pObj->drsUserfunc )
    {
      $prompt = 'SetFont( "' . $family . '", "' . $style . '", ' . $size . ' )';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
// DRS

    $this->tcpdfSetFontStretching( $font );
  }

  /**
   * tcpdfSetFontStretching( ) : Set the font properties family, size and style
   *
   * @param	[type]		$$font: ...
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function tcpdfSetFontStretching( $font )
  {
    $perc = ( int ) $this->zz_cObjGetSingle( $font[ 'stretching' ], $font[ 'stretching.' ] );
    if ( $perc <= 0 )
    {
      $perc = 100;
    }
    $this->tcpdf->setFontStretching( $perc );

// DRS
    if ( $this->pObj->drsUserfunc )
    {
      $prompt = 'SetFontStretching( "' . $perc . ' )';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
// DRS
  }

  /**
   * tcpdfSetTextColor( ) :  Set the text color.
   *                         Given color must be in a hex notation like #FFF or #1200A3
   *                         Hex format becomes a rgb format like "255 255 255"
   *
   * @param	[type]		$$properties: ...
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function tcpdfSetTextColor( $properties )
  {
    $textColor = $this->zz_cObjGetSingle( $properties[ 'textColor' ], $properties[ 'textColor.' ] );
    $textColor = $this->zz_hexToRgb( $textColor );
    $colors = explode( ' ', $textColor );

    switch ( true )
    {
      case( count( $colors ) == 1 ):
// grey
        $this->tcpdf->SetTextColor( $colors[ 0 ] );
        $prompt = 'SetTextColor( ' . $colors[ 0 ] . ' )';
        break;
      case( count( $colors ) == 3 ):
// rgb
        $this->tcpdf->SetTextColor( $colors[ 0 ], $colors[ 1 ], $colors[ 2 ] );
        $prompt = 'SetTextColor( ' . $colors[ 0 ] . ', ' . $colors[ 1 ] . ', ' . $colors[ 2 ] . ' )';
        break;
      case( count( $colors ) == 4 ):
// cmyk
        $this->tcpdf->SetTextColor( $colors[ 0 ], $colors[ 1 ], $colors[ 2 ], $colors[ 3 ] );
        $prompt = 'SetTextColor( ' . $colors[ 0 ] . ', ' . $colors[ 1 ] . ', ' . $colors[ 2 ] . ', ' . $colors[ 3 ] . ' )';
        break;
      default:
        $prompt = 'FATAL ERROR: textColor<br />
          ' . __METHOD__ . ' (line ' . __LINE__ . ')';
        break;
    }

// DRS
    if ( $this->pObj->drsUserfunc )
    {
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
// DRS
  }

  /**
   * tcpdfWriteHtmlCell( ) : Writes the content to the PDF. Dimensions, font and textColor are taken
   *                         from the properties
   *
   *                                 hr, i, img, li, ol, p, pre, small, span, strong, sub, sup, table, tcpdf,
   *                                 td, th, thead, tr, tt, u, ul
   *             NOTE: all the HTML attributes must be enclosed in double-quote
   *
   * @param	array		$properties   : Array with the properties font, textColor and cell
   * @param	string		$htmlContent  : Current HTML content
   * @param	string		$drsLabel     : Label for teh DRS prompt. Usually the path of the used typoscript
   * @return	void
   * @access private
   * @internal   Supported tags are: a, b, blockquote, br, dd, del, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6,
   * @version    6.2.0
   * @since      2.0.0
   */
  private function tcpdfWriteHtmlCell( $properties, $htmlContent, $drsLabel )
  {
    // Get cursor position
    $cursor = $this->tcpdfWriteHtmlCellCursor( $properties );
    $x = $cursor[ 'x' ];
    $y = $cursor[ 'y' ];

    // Get the current page number
    $pageNumber = $this->tcpdf->getPage();

    // Short variable
    $cell = $properties[ 'cell.' ];

    // Set some properties for writeHtmlCell()
    $this->tcpdfWriteHtmlCellProperties( $properties );
    // Set some more properties for writeHtmlCell()
    $align = $this->zz_cObjGetSingle( $cell[ 'align' ], $cell[ 'align.' ] ); // Allows to center or align the text. Possible values are: L, C, R, ''
    $autopadding = true; // if true, uses internal padding and automatically adjust it to account for line width.
    $border = 0; // Indicates if borders must be drawn around the cell. The value can be a number, a string or an array. 0: no border.
    $fill = false;  // Indicates if the cell background must be painted (true) or transparent (false).
    $h = $this->zz_cObjGetSingle( $cell[ 'height' ], $cell[ 'height.' ] ); // Cell minimum height. The cell extends automatically if needed.
    $ln = 1; // 1: to the beginning of the next line [DEFAULT]
    $reseth = true; // if true reset the last cell height (default true).
    $w = $this->zz_cObjGetSingle( $cell[ 'width' ], $cell[ 'width.' ] ); // Cell width. If 0, the cell extends up to the right margin.
    // ---------- START TRANSACTION ----------
    $this->tcpdf->startTransaction();

    $confPage = $this->tcpdfPageConf();
    $this->tcpdf->SetAutoPageBreak( true, $confPage[ 'autopagebreak' ] );

    // Write HTML cell
    $this->tcpdf->writeHtmlCell( $w, $h, $cursor[ 'x' ], $cursor[ 'y' ], $htmlContent, $border, $ln, $fill, $reseth, $align, $autopadding );
    $pageNumberAfterTransaction = $this->tcpdf->getPage();
    $this->tcpdf = $this->tcpdf->rollbackTransaction();
    // ---------- ROLLBACK TRANSACTION ----------
    // Add a page, if content will placed on next page
    if ( $pageNumberAfterTransaction > $pageNumber )
    {
      $this->tcpdfAddPage();
    }

    // Get cursor position from before the transaction
    $x = $cursor[ 'x' ];
    $y = $cursor[ 'y' ];
    $this->tcpdf->writeHtmlCell( $w, $h, $x, $y, $htmlContent, $border, $ln, $fill, $reseth, $align, $autopadding );

    // DRS
    if ( $this->pObj->drsUserfunc )
    {
      $prompt = 'writeHtmlCell( ' . $w . ', ' . $h . ', ' . $cursor[ 'x' ] . ', ' . $cursor[ 'y' ] . ', ' . $drsLabel . ' )';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
    // DRS
  }

  /**
   * tcpdfWriteHtmlCellCursor( ) :
   *
   * @param	array		$properties   : Array with the properties font, textColor and cell
   * @return	array $cursor: x, y
   * @access private
   * @version    6.2.0
   * @since      6.2.0
   */
  private function tcpdfWriteHtmlCellCursor( $properties )
  {
    $cursor = array();

    $cell = $properties[ 'cell.' ];
    $x = ( float ) $this->zz_cObjGetSingle( $cell[ 'x' ], $cell[ 'x.' ] );
    if ( empty( $x ) )
    {
      $x = $this->tcpdf->GetX();
    }
    $y = ( float ) $this->zz_cObjGetSingle( $cell[ 'y' ], $cell[ 'y.' ] );
    if ( empty( $y ) )
    {
      $y = $this->tcpdf->GetY();
    }

    $cursor = array(
      'x' => $x,
      'y' => $y
    );

    return $cursor;
  }

  /**
   * tcpdfWriteHtmlCellProperties( ) :
   *
   * @param	array		$properties   : Array with the properties font, textColor and cell
   * @return	void
   * @access private
   * @version    6.2.0
   * @since      2.0.0
   */
  private function tcpdfWriteHtmlCellProperties( $properties )
  {
    // Set textColor
    $this->tcpdfSetTextColor( $properties );

    // Set font
    $this->tcpdfSetFont( $properties[ 'font.' ] );
  }

  /*   * *********************************************
   *
   * Revocation
   *
   * ******************************************** */

  /**
   * revocation( ) : Generates the PDF revocation and returns the path to the PDF file
   *
   * @return	string		$path : path to the rendered pdf file
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function revocation()
  {
    $destFile = null;
    $destPath = null;

// Init caddy pdf
    $this->init( 'revocation' );

// RETURN : any pdf is requested
    if ( !$this->revocationInit() )
    {
      return;
    }
// RETURN : any pdf is requested
// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

// Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf[ 'db.' ][ 'table' ] );

// DRS
    if ( $this->pObj->drsUserfunc )
    {
      $data = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
// DRS
// Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
            (
            $this->confPdf[ 'revocation.' ][ 'filename' ], $this->confPdf[ 'revocation.' ][ 'filename.' ]
    );
// #i0079, 150416, dwildt, 1+/-
//$destPath = 'uploads/tx_caddy/' . $destFile;
    $destPath = PATH_site . 'uploads/tx_caddy/' . $destFile;
// Get the path of the destination file
// RETURN : destination file already exists
// #i0079, 150416, dwildt, 1+/-
//if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    if ( file_exists( $destPath ) )
    {
// DRS
      if ( $this->pObj->drsUserfunc )
      {
// #i0015, 130611, dwildt, 1-
//$prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
// #i0015, 130611, dwildt, 1+
        $prompt = 'uploads/tx_caddy' . '/' . $destFile . ' exists and will overridden!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
// DRS
// #i0015, 130611, dwildt, 1-
//return $destPath;
    }
// RETURN : destination file already exists
// PDF source file
    $srceFile = $sesArray[ 'sendVendorRevocation' ];
    if ( empty( $srceFile ) )
    {
      $srceFile = $sesArray[ 'sendCustomerRevocation' ];
    }
    if ( empty( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
              'sendCustomerRevocation/sendVendorRevocation<br />' . PHP .
              __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
// PDF source file
// Init tcpdf
    $this->tcpdfInit( $srceFile );
    $this->tcpdfAddPage();

// Write the delivery order address
    $this->revocationAddress();

// Write the delivery order date
    $this->revocationDate();

// Write additional textblocks
    $this->revocationAdditionalTextblocks();

// Write the caddy
    $this->caddy( 'invoice' );
// Create the PDF
    $this->tcpdfOutput( $destPath );

// RETURN :
    return $destPath;
  }

  /**
   * revocationAdditionalTextblocks( ) : Write addional text blocks
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function revocationAdditionalTextblocks()
  {
    $additionalTextblocks = $this->confPdf[ 'revocation.' ][ 'content.' ][ 'additionaltextblocks.' ];

// LOOP : additional textblocks
    foreach ( array_keys( ( array ) $additionalTextblocks ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[ $key ], 'revocation.additionaltextblocks.' . $key );
    }
// LOOP : additional textblocks
  }

  /**
   * revocationAddress( ) :  Write the invoice address
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function revocationAddress()
  {
    $invoiceaddress = $this->confPdf[ 'revocation.' ][ 'content.' ][ 'address.' ][ 'invoice.' ];
    $this->writeTextblock( $invoiceaddress, 'invoiceAddress' );
  }

  /**
   * revocationDate( ) : Write the current date
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function revocationDate()
  {
    $date = $this->confPdf[ 'revocation.' ][ 'content.' ][ 'date.' ];
    $this->writeTextblock( $date, 'revocationDate' );
  }

  /**
   * revocationInit( ) : Return false, if revocation should not sent to the customer or the vendor
   *
   * @return	boolean		$revocationInit  : false, if invoice should not sent
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function revocationInit()
  {
    $revocationInit = null;

// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

// RETURN : any pdf is requested
    switch ( true )
    {
      case(!empty( $sesArray[ 'sendCustomerRevocation' ] ) ):
      case(!empty( $sesArray[ 'sendVendorRevocation' ] ) ):
        $revocationInit = true;
        break;
      default:
        $revocationInit = false;
        break;
    }
    unset( $sesArray );

    if ( !$revocationInit )
    {
// DRS
      if ( $this->pObj->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
// DRS
    }
// RETURN : any pdf is requested

    return $revocationInit;
  }

  /*   * *********************************************
   *
   * Terms
   *
   * ******************************************** */

  /**
   * terms( ) : Generates the PDF terms and returns the path to the PDF file
   *
   * @return	string		$path : path to the rendered pdf file
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function terms()
  {
    $destFile = null;
    $destPath = null;

// Init caddy pdf
    $this->init( 'terms' );

// RETURN : any pdf is requested
    if ( !$this->termsInit() )
    {
      return;
    }
// RETURN : any pdf is requested
// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

// Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf[ 'db.' ][ 'table' ] );

// DRS
    if ( $this->pObj->drsUserfunc )
    {
      $data = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
// DRS
// Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
            (
            $this->confPdf[ 'terms.' ][ 'filename' ], $this->confPdf[ 'terms.' ][ 'filename.' ]
    );
// #i0079, 150416, dwildt, 1+/-
//$destPath = 'uploads/tx_caddy/' . $destFile;
    $destPath = PATH_site . 'uploads/tx_caddy/' . $destFile;
// Get the path of the destination file
// RETURN : destination file already exists
// #i0079, 150416, dwildt, 1+/-
//if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    if ( file_exists( $destPath ) )
    {
// DRS
      if ( $this->pObj->drsUserfunc )
      {
// #i0015, 130611, dwildt, 1-
//$prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
// #i0015, 130611, dwildt, 1+
        $prompt = 'uploads/tx_caddy' . '/' . $destFile . ' exists and will overridden!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
// DRS
// #i0015, 130611, dwildt, 1-
//return $destPath;
    }
// RETURN : destination file already exists
// PDF source file
    $srceFile = $sesArray[ 'sendVendorTerms' ];
    if ( empty( $srceFile ) )
    {
      $srceFile = $sesArray[ 'sendCustomerTerms' ];
    }
    if ( empty( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
              'sendCustomerTerms/sendVendorTerms<br />' . PHP .
              __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
// PDF source file
// Init tcpdf
    $this->tcpdfInit( $srceFile );
    $this->tcpdfAddPage();

// Write the delivery order address
    $this->termsAddress();

// Write the delivery order date
    $this->termsDate();

// Write additional textblocks
    $this->termsAdditionalTextblocks();

// Write the caddy
    $this->caddy( 'invoice' );
// Create the PDF
    $this->tcpdfOutput( $destPath );

// RETURN :
    return $destPath;
  }

  /**
   * termsAdditionalTextblocks( ) : Write addional text blocks
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function termsAdditionalTextblocks()
  {
    $additionalTextblocks = $this->confPdf[ 'terms.' ][ 'content.' ][ 'additionaltextblocks.' ];

// LOOP : additional textblocks
    foreach ( array_keys( ( array ) $additionalTextblocks ) as $key )
    {
      if ( !stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[ $key ], 'terms.additionaltextblocks.' . $key );
    }
// LOOP : additional textblocks
  }

  /**
   * termsAddress( ) :  Write the invoice address
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function termsAddress()
  {
    $invoiceaddress = $this->confPdf[ 'terms.' ][ 'content.' ][ 'address.' ][ 'invoice.' ];
    $this->writeTextblock( $invoiceaddress, 'invoiceAddress' );
  }

  /**
   * termsDate( ) : Write the current date
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function termsDate()
  {
    $date = $this->confPdf[ 'terms.' ][ 'content.' ][ 'date.' ];
    $this->writeTextblock( $date, 'termsDate' );
  }

  /**
   * termsInit( ) : Return false, if terms should not sent to the customer or the vendor
   *
   * @return	boolean		$termsInit  : false, if invoice should not sent
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function termsInit()
  {
    $termsInit = null;

// Get the caddy session
    $sesArray = $GLOBALS[ 'TSFE' ]->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS[ "TSFE" ]->id );

// RETURN : any pdf is requested
    switch ( true )
    {
      case(!empty( $sesArray[ 'sendCustomerTerms' ] ) ):
      case(!empty( $sesArray[ 'sendVendorTerms' ] ) ):
        $termsInit = true;
        break;
      default:
        $termsInit = false;
        break;
    }
    unset( $sesArray );

    if ( !$termsInit )
    {
// DRS
      if ( $this->pObj->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
// DRS
    }
// RETURN : any pdf is requested

    return $termsInit;
  }

  /*   * *********************************************
   *
   * Write
   *
   * ******************************************** */

  /**
   * writeTextblock( ) : Write a texblock. If textblock has a header, prepend it to the body / content
   *
   * @param	array		$textBlock  : the additional text block
   * @param	string		$drsLabel   : label for the drs prompt. Usually name of the calling function.
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function writeTextblock( $textBlock, $drsLabel )
  {
    $body = $textBlock[ 'body.' ];
    $htmlContent = $GLOBALS[ 'TSFE' ]->cObj->cObjGetSingle( $body[ 'content' ], $body[ 'content.' ] );

    $content = strip_tags( $htmlContent );
    $content = trim( $content );
    $content = str_replace( ' ', null, $content );

    // RETURN : HTML content is empty
    if ( empty( $content ) )
    {
      return;
    }
    // RETURN : HTML content is empty

    $header = $textBlock[ 'header.' ];
    $htmlContent = $this->writeTextblockAddHeader( $header ) . $htmlContent;
    $this->tcpdfWriteHtmlCell( $body[ 'properties.' ], $htmlContent, $drsLabel );
  }

  /**
   * writeTextblockAddHeader( ) : Returns a HTML rendered header
   *
   * @param	[type]		$$header: ...
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function writeTextblockAddHeader( $header )
  {
// RETURN : there is no header
    if ( empty( $header ) )
    {
      return;
    }
// RETURN : there is no header
// Get the header content
    $htmlContent = $GLOBALS[ 'TSFE' ]->cObj->cObjGetSingle( $header[ 'content' ], $header[ 'content.' ] );

    return $htmlContent;
  }

  /*   * *********************************************
   *
   * ZZ
   *
   * ******************************************** */

  /**
   * zz_hexToRgb( )  : Returns the rgb notation for the given value - a CSS color name or
   *                   a color in a hex notation.
   *
   * @param	string		$hex        : color in HTML notation like #FFF or #CC00CC or CSS color name
   * @return	string		$textColor  : color in RGB format like "255 255 255" or "144 0 144"
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function zz_hexToRgb( $hex )
  {
    switch ( true )
    {
      case( $hex == 'aqua' ):
        $hex = '#00FFFF';
        break;
      case( $hex == 'beige' ):
        $hex = '#F5F5DC';
        break;
      case( $hex == 'black' ):
        $hex = '#000000';
        break;
      case( $hex == 'blue' ):
        $hex = '#0000FF';
        break;
      case( $hex == 'brown' ):
        $hex = '#A52A2A';
        break;
      case( $hex == 'fuchsia' ):
        $hex = '#FF00FF';
        break;
      case( $hex == 'gold' ):
        $hex = '#FFD700';
        break;
      case( $hex == 'gray' ):
        $hex = '#808080';
        break;
      case( $hex == 'green' ):
        $hex = '#008000';
        break;
      case( $hex == 'lime' ):
        $hex = '#00FF00';
        break;
      case( $hex == 'maroon' ):
        $hex = '#800000';
        break;
      case( $hex == 'navy' ):
        $hex = '#000080';
        break;
      case( $hex == 'olive' ):
        $hex = '#808000';
        break;
      case( $hex == 'orange' ):
        $hex = '#FFA500';
        break;
      case( $hex == 'purple' ):
        $hex = '#800080';
        break;
      case( $hex == 'red' ):
        $hex = '#FF0000';
        break;
      case( $hex == 'silver' ):
        $hex = '#C0C0C0';
        break;
      case( $hex == 'tan' ):
        $hex = '#D2B48C';
        break;
      case( $hex == 'teal' ):
        $hex = '#008080';
        break;
      case( $hex == 'white' ):
        $hex = '#FFFFFF';
        break;
      case( $hex == 'yellow' ):
        $hex = '#FFFF00';
        break;
    }

    $hex = str_replace( '#', null, $hex );

    if ( strlen( $hex ) == 3 )
    {
      $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
      $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
      $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    }
    else
    {
      $r = hexdec( substr( $hex, 0, 2 ) );
      $g = hexdec( substr( $hex, 2, 2 ) );
      $b = hexdec( substr( $hex, 4, 2 ) );
    }
    $rgb = array( $r, $g, $b );

    $textColor = implode( ' ', $rgb );
    return $textColor;
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
        $value = $GLOBALS[ 'TSFE' ]->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case(!( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
  }

}

?>