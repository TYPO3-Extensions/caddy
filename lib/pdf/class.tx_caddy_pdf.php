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
 *   99: class tx_caddy_pdf extends tslib_pibase
 *
 *              SECTION: Caddy
 *  135:     private function caddy( $invoice = false )
 *  168:     private function caddyProduct( $product )
 *  219:     private function  caddySum( $subpartArray )
 *  321:     private function caddyTablebody( $subpartArray )
 *  351:     private function caddyTablehead( $subpartArray )
 *
 *              SECTION: Delivery Order
 *  390:     public function deliveryorder( )
 *  497:     private function deliveryorderAdditionalTextblocks( )
 *  524:     private function deliveryorderAddress( $fallBackToInvoiceAddress=false )
 *  564:     private function deliveryorderDate( )
 *  579:     private function deliveryorderInit( )
 *  622:     private function deliveryorderNumbers( )
 *
 *              SECTION: Init
 *  658:     private function init( )
 *  686:     private function initCheckDir( )
 *  706:     private function initCheckProducts( )
 *
 *              SECTION: Invoice
 *  738:     public function invoice( )
 *  846:     private function invoiceAdditionalTextblocks( )
 *  872:     private function invoiceAddress( )
 *  897:     private function invoiceDate( )
 *  911:     private function invoiceInit( )
 *  954:     private function invoiceNumbers( )
 *
 *              SECTION: TCPDF
 *  989:     private function tcpdfInit( $srceFile )
 * 1021:     private function tcpdfOutput( $destPath )
 * 1056:     private function tcpdfSetFont( $font )
 * 1083:     private function tcpdfSetTextColor( $properties )
 * 1137:     private function tcpdfWriteHtmlCell( $properties, $htmlContent, $drsLabel )
 *
 *              SECTION: Terms
 * 1189:     public function terms( )
 * 1287:     private function termsAdditionalTextblocks( )
 * 1313:     private function termsAddress( )
 * 1327:     private function termsDate( )
 * 1341:     private function termsInit( )
 *
 *              SECTION: Write
 * 1394:     private function writeTextblock( $textBlock, $drsLabel )
 * 1426:     private function writeTextblockAddHeader( $header )
 *
 *              SECTION: ZZ
 * 1460:     private function zz_hexToRgb( $hex )
 * 1560:     private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
 *
 * TOTAL FUNCTIONS: 34
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once( PATH_tslib . 'class.tslib_pibase.php' );

/**
 * Class 'pdf' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version     2.0.0
 * @since       2.0.0
 */
class tx_caddy_pdf extends tslib_pibase
{

    // path for localisation file
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  public $extKey        = 'caddy';

  public $drsUserfunc = null;


  public  $conf         = null;
  private $confSettings = null;
  private $confPdf      = null;

  private $local_cObj = null;
    // [Object]
  private $tcpdf      = null;
  private $tmpl       = null;




  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * caddy( ) : Generates the HTML caddy and write it to tcpdf
  *
  * @param	boolean		$invoice  : true: render caddy for the invoice. false: render caddy for the delivery order
  * @return	void
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddy( $invoice = false )
  {
    $subpartArray = null;
    $subpartArray = $this->caddyTablehead( $subpartArray );
    $subpartArray = $this->caddyTablebody( $subpartArray );

    $outerMarkerArray = null;
    if( $invoice )
    {
      $outerMarkerArray = $this->caddySum( $subpartArray );
    }
      // render the marker
    $htmlContent = $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached
                  (
                    $this->tmpl['all'],
                    $outerMarkerArray,
                    $subpartArray
                  );
      // render the marker

      // write the HTML content
    $body = $this->confPdf['deliveryorder.']['content.']['caddy.']['body.'];
    $this->tcpdfWriteHtmlCell( $body['properties.'], $htmlContent, 'caddy' );
  }

 /**
  * caddyProduct( ) :
  *
  * @param	array		$product : current product
  * @return	string		$content : the rendered product
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyProduct( $product )
  {
    $product['price_total'] = $product['price'] * $product['qty'];

    $this->local_cObj->start( $product, $this->conf['db.']['table'] );

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

    $fields = $this->confSettings['powermailCaddy.']['fields.'];

      // LOOP : fields, the elements of a product
    foreach( array_keys ( ( array ) $fields ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }

      $currProduct = $this->local_cObj->cObjGetSingle( $fields[$key], $fields[$key . '.'] );
      $currProduct = str_replace( '&euro;', '€', $currProduct );
      $currProduct = str_replace( '&nbsp;', ' ', $currProduct );

      $marker = '###' . strtoupper($key) . '###';
      $value  = $currProduct;
      $markerArray[$marker] = $value;
    }
      // LOOP : fields, the elements of a product

    $content =  $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached
                (
                  $this->tmpl['item'],
                  $markerArray
                );
    return $content;
  }

 /**
  * caddySum( ) : Calculate the caddy sum for products, options, tax
  *
  * @param	array		$subpartArray :
  * @return	array		$subpartArray :
  * @version    2.0.0
  * @since      2.0.0
  */
  private function  caddySum( $subpartArray )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

    $outerArr = array
                (
                  'productsGross' => $sesArray['productsGross'],
                  'productsNet'   => $sesArray['productsNet'],
                  'optionsGross'  => $sesArray['optionsGross'],
                  'optionsNet'    => $sesArray['optionsNet'],
                  'sumGross'      => $sesArray['sumGross'],
                  'sumNet'        => $sesArray['sumNet'],
                  'sumTaxReduced' => $sesArray['sumTaxReduced'],
                  'sumTaxNormal'  => $sesArray['sumTaxNormal']
                );

    $local_cObj = $GLOBALS['TSFE']->cObj;
    $local_cObj->start($outerArr, $this->conf['db.']['table']);

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $data   = var_export( $local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // FOREACH  : overall item
    foreach( array_keys( ( array ) $this->confSettings['powermailCaddy.']['overall.'] ) as $key )
    {
      if( stristr( $key, '.') )
      {
        continue;
      }

      $name = $this->confSettings['powermailCaddy.']['overall.'][$key];
      $conf = $this->confSettings['powermailCaddy.']['overall.'][$key . '.'];

      $value = $local_cObj->cObjGetSingle( $name, $conf );
      $value = str_replace('&euro;', '€', $value);
      $value = str_replace('&nbsp;', ' ', $value);

      $marker = '###' . strtoupper($key) . '###';

      $subpartArray[$marker] = $value;
    }
      // FOREACH  : overall item

    $subpartArray['###CADDY_LL_PRODUCTSNET###'] = $this->pi_getLL('caddy_ll_productsnet');
    $subpartArray['###CADDY_LL_OPTIONSNET###']  = $this->pi_getLL('caddy_ll_optionsnet');
    $subpartArray['###CADDY_LL_TAX###']         = $this->pi_getLL('caddy_ll_tax');
    $subpartArray['###CADDY_LL_SUMGROSS###']    = $this->pi_getLL('caddy_ll_sumgross');

      // Payment label
    $subpartArray['###CADDY_LL_PAYMENTNET###'] = $this->pi_getLL('caddy_ll_paymentnet');

      // Payment option label
//    $confPaymentOptions = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'];
//    $key    = $sesArray['payment'] . '.';
//    $name   = $confPaymentOptions[ $key ]['title'];
//    $conf   = $confPaymentOptions[ $key ]['title.'];
//    $value  = $this->zz_cObjGetSingle( $name, $conf );
//    $subpartArray['###PAYMENTNET###'] = $value;
    $subpartArray['###PAYMENTNET###'] = $this->caddy->getPaymentOptionLabelBySessionId( );
      // Payment option label

      // Shipping label
    $subpartArray['###CADDY_LL_SHIPPINGNET###']  = $this->pi_getLL('caddy_ll_shippingnet');

      // Shipping option label
//    $confShippingOptions  = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['shipping.']['options.'];
//    $key    = $sesArray['shipping'] . '.';
//    $name   = $confShippingOptions[ $key ]['title'];
//    $conf   = $confShippingOptions[ $key ]['title.'];
//    $value  = $this->zz_cObjGetSingle( $name, $conf );
//    $subpartArray['###SHIPPINGNET###'] = $value;
    $subpartArray['###SHIPPINGNET###'] = $this->caddy->getShippingOptionLabelBySessionId( );
      // Shipping option label

      // Special label
    $subpartArray['###CADDY_LL_SPECIALNET###'] = $this->pi_getLL('caddy_ll_specialnet');

//      // Special option label
//    $specialOptions = null;
//    $confSpecialOptions = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['special.']['options.'];
//    foreach( ( array ) $sesArray['special'] as $special_id )
//    {
//      $specialOptions = $specialOptions . $confSpecialOptions[$special_id.'.']['title'];
//    }
//    $subpartArray['###SPECIALNET###'] = $specialOptions;
    $subpartArray['###SPECIALNET###'] = $this->caddy->getSpecialOptionLabelsBySessionId( );
      // Special option label
var_dump( __METHOD__, __LINE__, $subpartArray );
die( );
    return $subpartArray;
  }

 /**
  * caddyTablebody( ) : HTML table body of the caddy
  *
  * @param	array		$subpartArray :
  * @return	array		$subpartArray :
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyTablebody( $subpartArray )
  {
    $content = null;

      // Get products
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );
    $products = $sesArray['products'];

      // LOOP : products
    foreach( $products as $product )
    {
      $content =  $content . $this->caddyProduct( $product );
    }
      // LOOP : products

      // Update the marker content
    $subpartArray['###CONTENT###'] = $subpartArray['###CONTENT###'] . $content;

      // Return the marker content
    return $subpartArray;
  }

 /**
  * caddyTablehead( ) : HTML table head of the caddy
  *
  * @param	array		$subpartArray :
  * @return	array		$subpartArray :
  * @version    2.0.0
  * @since      2.0.0
  */
  private function caddyTablehead( $subpartArray )
  {
    $fields = $this->confSettings['powermailCaddy.']['fields.'];

      // LOOP : fields, the elements of a product
    foreach( array_keys ( ( array ) $fields ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        $marker = '###CADDY_LL_' . strtoupper($key) . '###';
        $value  = $this->pi_getLL( 'caddy_ll_' . $key );
        $subpartArray[$marker] = $value;
      }
    }
      // LOOP : fields, the elements of a product

    $marker = '###CADDY_LL_ITEM###';
    $value  = $this->pi_getLL( 'caddy_ll_item' );
    $subpartArray[$marker] = $value;

    return $subpartArray;
  }



  /***********************************************
  *
  * Delivery Order
  *
  **********************************************/

 /**
  * deliveryorder( ) : Generates the PDF delivery order and returns the path to the PDF file
  *
  * @return	string		$path : path to the rendered pdf file
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function deliveryorder( )
  {
    $destFile = null;
    $destPath = null;

      // Init caddy pdf
    $this->init( );

      // RETURN : any pdf is requested
    if( ! $this->deliveryorderInit( ) )
    {
      return;
    }
      // RETURN : any pdf is requested

      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf['db.']['table'] );

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
                (
                  $this->confPdf['deliveryorder.']['filename'],
                  $this->confPdf['deliveryorder.']['filename.']
                );
    $destPath = 'uploads/tx_caddy/' . $destFile;
      // Get the path of the destination file

      // RETURN : destination file already exists
    if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    {
        // DRS
      if( $this->pObj->drsUserfunc )
      {
        $prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
        // DRS
      return $destPath;
    }
      // RETURN : destination file already exists

      // HTML template
    $tmplFile = $GLOBALS['TSFE']->cObj->fileResource( $this->confPdf['deliveryorder.']['template'] );
    $this->tmpl['all']  = $GLOBALS['TSFE']->cObj->getSubpart( $tmplFile, '###CADDY_DELIVERYORDER###' );
    $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart( $this->tmpl['all'], '###ITEM###' );

      // PDF source file
    $srceFile = $sesArray['sendVendorDeliveryorder'];
    if( empty ( $srceFile ) )
    {
      $srceFile = $sesArray['sendCustomerDeliveryorder'];
    }
    if( empty ( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
                'sendCustomerDeliveryorder/sendVendorDeliveryorder<br />' . PHP .
                __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
      // PDF source file

      // Init tcpdf
    $this->tcpdf = $this->tcpdfInit( $srceFile );

      // Write the delivery order address
    $fallBackToInvoiceAddress = true;
    $this->deliveryorderAddress( $fallBackToInvoiceAddress );

      // Write the delivery order date
    $this->deliveryorderDate( );

      // Write the delivery order number
    $this->deliveryorderNumbers( );

      // Write additional textblocks
    $this->deliveryorderAdditionalTextblocks( );

      // Write the caddy
    $this->caddy( );

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
  private function deliveryorderAdditionalTextblocks( )
  {
    $additionalTextblocks = $this->confPdf['deliveryorder.']['content.']['additionaltextblocks.'];

      // LOOP : additional textblocks
    foreach( array_keys ( ( array ) $additionalTextblocks ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[$key], 'deliveryorder.additionaltextblocks.' . $key );
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
  private function deliveryorderAddress( $fallBackToInvoiceAddress=false )
  {
      // Get the body content
    $body         = $this->confPdf['deliveryorder.']['content.']['address.']['deliveryorder.']['body.'];
    $htmlContent  = $GLOBALS['TSFE']->cObj->cObjGetSingle( $body['content'], $body['content.'] );
    $content      = strip_tags( $htmlContent );
    $content      = trim( $content );
    $content      = str_replace( ' ', null, $content );
      // Get the body content

    switch( true )
    {
      case( ! empty( $content ) ):
        $deliveryorderaddress = $this->confPdf['deliveryorder.']['content.']['address.']['deliveryorder.'];
        $this->writeTextblock( $deliveryorderaddress, 'deliveryorderaddress' );
        break;
      case( empty( $content ) ):
      default:
          // FALLBACK : take the invoice address
        if( $fallBackToInvoiceAddress )
        {
          $invoiceaddress = $this->confPdf['deliveryorder.']['content.']['address.']['invoice.'];
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
  private function deliveryorderDate( )
  {
    $date = $this->confPdf['deliveryorder.']['content.']['date.'];
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
  private function deliveryorderInit( )
  {
    $deliveryorderInit = null;

      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // RETURN : any pdf is requested
    switch( true )
    {
      case( ! empty( $sesArray['sendCustomerDeliveryorder'] ) ):
      case( ! empty( $sesArray['sendVendorDeliveryorder'] ) ):
        $deliveryorderInit = true;
        break;
      default:
        $deliveryorderInit = false;
        break;
    }
    unset( $sesArray );

    if( ! $deliveryorderInit )
    {
        // DRS
      if( $this->pObj->drsUserfunc )
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
  private function deliveryorderNumbers( )
  {
    $numbers = $this->confPdf['deliveryorder.']['content.']['numbers.'];

      // LOOP : fields, the elements of a product
    foreach( array_keys ( ( array ) $numbers ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $numbers[$key], 'deliveryorder.numbers.' . $key );
    }

  }



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( ) : Init some global variables and some classes
  *           Dies in case
  *           * of an unproper dir
  *           * of no products
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function init( )
  {
    $this->conf         = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    $this->confSettings = $this->conf['settings.'];
    $this->confPdf      = $this->conf['pdf.'];

    $this->cObj         = $GLOBALS['TSFE']->cObj;
    $this->local_cObj   = $GLOBALS['TSFE']->cObj;


    $this->pi_loadLL();

      // DIE  : if there is an unproper directory
    $this->initCheckDir( );

      // DIE  : if there isn't any product
    $this->initCheckProducts( );
    
    $this->initInstances( );

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
  private function initCheckDir( )
  {
      // CHECK: Is directory for PDF available?
    if( ! is_dir( 'uploads/tx_caddy' ) )
    {
      $prompt = 'FATAL ERROR: uploads/tx_caddy not found!<br />
        ' .__METHOD__. ' (' . __LINE__ . ')';
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
  private function initCheckProducts( )
  {
      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

    if( empty( $sesArray['products'] ) )
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
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initInstances( )
  {
    $path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'lib/';

    require_once( $path2lib . 'caddy/class.tx_caddy.php' );
    $this->caddy        = t3lib_div::makeInstance( 'tx_caddy' );
    $this->caddy->conf  = $this->conf;
    $this->caddy->cObj  = $this->cObj;

    require_once( $path2lib . 'pdf/tcpdf/tcpdf.php' );
    require_once( $path2lib . 'pdf/fpdi/fpdi.php' );
  }



  /***********************************************
  *
  * Invoice
  *
  **********************************************/

 /**
  * invoice( ) : Generates the PDF invoice and returns the path to the PDF file
  *
  * @return	string		$path : path to the rendered pdf file
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function invoice( )
  {
    $destFile = null;
    $destPath = null;

      // Init caddy pdf
    $this->init( );

      // RETURN : any pdf is requested
    if( ! $this->invoiceInit( ) )
    {
      return;
    }
      // RETURN : any pdf is requested

      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf['db.']['table'] );

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
                (
                  $this->confPdf['invoice.']['filename'],
                  $this->confPdf['invoice.']['filename.']
                );
    $destPath = 'uploads/tx_caddy/' . $destFile;
      // Get the path of the destination file

      // RETURN : destination file already exists
    if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    {
        // DRS
      if( $this->pObj->drsUserfunc )
      {
        $prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
        // DRS
      return $destPath;
    }
      // RETURN : destination file already exists

      // HTML template
    $tmplFile = $GLOBALS['TSFE']->cObj->fileResource( $this->confPdf['invoice.']['template'] );
    $this->tmpl['all']  = $GLOBALS['TSFE']->cObj->getSubpart( $tmplFile, '###CADDY_INVOICE###' );
    $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart( $this->tmpl['all'], '###ITEM###' );

      // PDF source file
    $srceFile = $sesArray['sendVendorInvoice'];
    if( empty ( $srceFile ) )
    {
      $srceFile = $sesArray['sendCustomerInvoice'];
    }
    if( empty ( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
                'sendCustomerInvoice/sendVendorInvoice<br />' . PHP .
                __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
      // PDF source file

      // Init tcpdf
    $this->tcpdf = $this->tcpdfInit( $srceFile );

      // Write the delivery order address
    $fallBackToInvoiceAddress = false;
    $this->invoiceAddress( $fallBackToInvoiceAddress );

      // Write the delivery order date
    $this->invoiceDate( );

      // Write the delivery order number
    $this->invoiceNumbers( );

      // Write additional textblocks
    $this->invoiceAdditionalTextblocks( );

      // Write the caddy
    $invoice = true;
    $this->caddy( $invoice );

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
  private function invoiceAdditionalTextblocks( )
  {
    $additionalTextblocks = $this->confPdf['invoice.']['content.']['additionaltextblocks.'];

      // LOOP : additional textblocks
    foreach( array_keys ( ( array ) $additionalTextblocks ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[$key], 'invoice.additionaltextblocks.' . $key );
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
  private function invoiceAddress( )
  {
    $invoiceaddress = $this->confPdf['invoice.']['content.']['address.'];

      // LOOP : additional textblocks
    foreach( array_keys ( ( array ) $invoiceaddress ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $invoiceaddress[$key], 'invoice.address.' . $key );
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
  private function invoiceDate( )
  {
    $date = $this->confPdf['invoice.']['content.']['date.'];
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
  private function invoiceInit( )
  {
    $invoiceInit = null;

      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // RETURN : any pdf is requested
    switch( true )
    {
      case( ! empty( $sesArray['sendCustomerInvoice'] ) ):
      case( ! empty( $sesArray['sendVendorInvoice'] ) ):
        $invoiceInit = true;
        break;
      default:
        $invoiceInit = false;
        break;
    }
    unset( $sesArray );

    if( ! $invoiceInit )
    {
        // DRS
      if( $this->pObj->drsUserfunc )
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
  private function invoiceNumbers( )
  {
    $numbers = $this->confPdf['invoice.']['content.']['numbers.'];

      // LOOP : fields, the elements of a product
    foreach( array_keys ( ( array ) $numbers ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $numbers[$key], 'invoice.numbers.' . $key );
    }

  }



  /***********************************************
  *
  * TCPDF
  *
  **********************************************/

 /**
  * tcpdfInit( ) : Init a TCPDF object and returns ist
  *
  * @param	string		$filename : current filename
  * @return	object		$tcpdf    : TCPDF object
  * @access private
  * @internal   http://www.tcpdf.org/doc/code/classTCPDF.html
  * @version    2.0.0
  * @since      2.0.0
  */
  private function tcpdfInit( $srceFile )
  {
    $tcpdf = new FPDI( );
    $tcpdf->AddPage( );

    $tcpdf->setSourceFile( $srceFile );
    $tmplId = $tcpdf->importPage( 1 );
    $tcpdf->useTemplate( $tmplId, 0, 0, 210 );

    $author = $this->pi_getLL( 'caddy_ll_docauthor' );
    $title  = $this->pi_getLL( 'caddy_ll_doctitle' );

    $tcpdf->SetAuthor( $author );
    $tcpdf->SetTitle( $title );
//    $tcpdf->SetSubject('TYPO3 Caddy Order Subject');
//    $tcpdf->SetKeywords('TYPO3, caddy');

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
    if( ! file_exists( $destPath ) )
    {
      $prompt = $destPath . ' could not written!<br />
        ' .__METHOD__. ' (' . __LINE__ . ')';
      die( $prompt );
    }
      // DIE  : PDF could not written

      // DRS
    if( file_exists( $destPath ) )
    {
      if( $this->pObj->drsUserfunc )
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
    $family = $this->zz_cObjGetSingle( $font['family'], $font['family.'] );
    $size   = $this->zz_cObjGetSingle( $font['size'],   $font['size.'] );
    $style  = $this->zz_cObjGetSingle( $font['style'],  $font['style.'] );
    $this->tcpdf->SetFont( $family, $style , $size );

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $prompt = 'SetFont( "' . $family . '", "' . $style . '", ' . $size . ' )';
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
    $textColor  = $this->zz_cObjGetSingle( $properties['textColor'], $properties['textColor.'] );
    $textColor  = $this->zz_hexToRgb( $textColor );
    $colors     = explode( ' ', $textColor );

    switch( true )
    {
      case( count( $colors ) == 1 ):
          // grey
        $this->tcpdf->SetTextColor( $colors[0] );
        $prompt = 'SetTextColor( ' . $colors[0] . ' )';
        break;
      case( count( $colors ) == 3 ):
          // rgb
        $this->tcpdf->SetTextColor( $colors[0], $colors[1], $colors[2] );
        $prompt = 'SetTextColor( ' . $colors[0] . ', ' . $colors[1] . ', ' . $colors[2] . ' )';
        break;
      case( count( $colors ) == 4 ):
          // cmyk
        $this->tcpdf->SetTextColor( $colors[0], $colors[1], $colors[2], $colors[3] );
        $prompt = 'SetTextColor( ' . $colors[0] . ', ' . $colors[1] . ', ' . $colors[2] . ', '. $colors[3] . ' )';
        break;
      default:
        $prompt = 'FATAL ERROR: textColor<br />
          ' . __METHOD__ . ' (line ' . __LINE__ . ')';
        break;
    }

      // DRS
    if( $this->pObj->drsUserfunc )
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
  * @version    2.0.0
  * @since      2.0.0
  */
  private function tcpdfWriteHtmlCell( $properties, $htmlContent, $drsLabel )
  {
      // Set textColor
    $this->tcpdfSetTextColor( $properties );

      // Set font
    $this->tcpdfSetFont( $properties['font.'] );

      // short variable
    $cell = $properties['cell.'];

      // Get properties for the HTML cell
    $w            = $this->zz_cObjGetSingle( $cell['width'], $cell['width.'] );
    $h            = $this->zz_cObjGetSingle( $cell['height'], $cell['height.'] );
    $x            = $this->zz_cObjGetSingle( $cell['x'], $cell['x.'] );
    $y            = $this->zz_cObjGetSingle( $cell['y'], $cell['y.'] );
    $border       = 0;
    $ln           = 0;
    $fill         = false;
    $reseth       = true;
    $align        = $this->zz_cObjGetSingle( $cell['align'], $cell['align.'] );
    $autopadding  = true;
      // Get properties for the HTML cell

      // Write HTML cell
    $this->tcpdf->writeHtmlCell( $w, $h, $x, $y, $htmlContent, $border, $ln, $fill, $reseth, $align, $autopadding );

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $prompt = 'writeHtmlCell( ' . $w . ', ' . $h . ', ' . $x . ', '. $y . ', ' . $drsLabel . ' )';
      t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
    }
      // DRS
  }



  /***********************************************
  *
  * Terms
  *
  **********************************************/

 /**
  * terms( ) : Generates the PDF terms and returns the path to the PDF file
  *
  * @return	string		$path : path to the rendered pdf file
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function terms( )
  {
    $destFile = null;
    $destPath = null;

      // Init caddy pdf
    $this->init( );

      // RETURN : any pdf is requested
    if( ! $this->termsInit( ) )
    {
      return;
    }
      // RETURN : any pdf is requested

      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // Add session data to the local cObj
    $this->local_cObj->start( $sesArray, $this->pObj->conf['db.']['table'] );

      // DRS
    if( $this->pObj->drsUserfunc )
    {
      $data   = var_export( $this->local_cObj->data, true );
      $prompt = 'cObj->data: ' . $data;
      t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
    }
      // DRS

      // Get the path of the destination file
    $destFile = $this->local_cObj->cObjGetSingle
                (
                  $this->confPdf['terms.']['filename'],
                  $this->confPdf['terms.']['filename.']
                );
    $destPath = 'uploads/tx_caddy/' . $destFile;
      // Get the path of the destination file

      // RETURN : destination file already exists
    if( file_exists( 'uploads/tx_caddy' . '/' . $destFile ) )
    {
        // DRS
      if( $this->pObj->drsUserfunc )
      {
        $prompt = 'RETURN : uploads/tx_caddy' . '/' . $destFile . ' exists!';
        t3lib_div::devlog( '[WARN/USERFUNC] ' . $prompt, $this->extKey, 2 );
      }
        // DRS
      return $destPath;
    }
      // RETURN : destination file already exists

      // PDF source file
    $srceFile = $sesArray['sendVendorTerms'];
    if( empty ( $srceFile ) )
    {
      $srceFile = $sesArray['sendCustomerTerms'];
    }
    if( empty ( $srceFile ) )
    {
      $prompt = 'Can\'t get source file from session data ' .
                'sendCustomerTerms/sendVendorTerms<br />' . PHP .
                __METHOD__ . '(line ' . __LINE__ . ')';
      die( $prompt );
    }
      // PDF source file

      // Init tcpdf
    $this->tcpdf = $this->tcpdfInit( $srceFile );

      // Write the delivery order address
    $this->termsAddress( );

      // Write the delivery order date
    $this->termsDate( );

      // Write additional textblocks
    $this->termsAdditionalTextblocks( );

      // Write the caddy
//    $this->caddy( );

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
  private function termsAdditionalTextblocks( )
  {
    $additionalTextblocks = $this->confPdf['terms.']['content.']['additionaltextblocks.'];

      // LOOP : additional textblocks
    foreach( array_keys ( ( array ) $additionalTextblocks ) as $key )
    {
      if( ! stristr( $key, '.' ) )
      {
        continue;
      }

      $this->writeTextblock( $additionalTextblocks[$key], 'terms.additionaltextblocks.' . $key );
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
  private function termsAddress( )
  {
    $invoiceaddress = $this->confPdf['terms.']['content.']['address.']['invoice.'];
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
  private function termsDate( )
  {
    $date = $this->confPdf['terms.']['content.']['date.'];
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
  private function termsInit( )
  {
    $termsInit = null;

      // Get the caddy session
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $GLOBALS["TSFE"]->id );

      // RETURN : any pdf is requested
    switch( true )
    {
      case( ! empty( $sesArray['sendCustomerTerms'] ) ):
      case( ! empty( $sesArray['sendVendorTerms'] ) ):
        $termsInit = true;
        break;
      default:
        $termsInit = false;
        break;
    }
    unset( $sesArray );

    if( ! $termsInit )
    {
        // DRS
      if( $this->pObj->drsUserfunc )
      {
        $prompt = __METHOD__ . ' returns null.';
        t3lib_div::devlog( '[INFO/USERFUNC] ' . $prompt, $this->extKey, 0 );
      }
        // DRS
    }
      // RETURN : any pdf is requested

    return $termsInit;
  }



  /***********************************************
  *
  * Write
  *
  **********************************************/

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
    $body         = $textBlock['body.'];
    $htmlContent  = $GLOBALS['TSFE']->cObj->cObjGetSingle( $body['content'], $body['content.'] );

    $content      = strip_tags( $htmlContent );
    $content      = trim( $content );
    $content      = str_replace( ' ', null, $content );

      // RETURN : HTML content is empty
    if( empty( $content ) )
    {
      return;
    }
      // RETURN : HTML content is empty

    $header       = $textBlock['header.'];
    $htmlContent  = $this->writeTextblockAddHeader( $header ) . $htmlContent;
    $this->tcpdfWriteHtmlCell( $body['properties.'], $htmlContent, $drsLabel );
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
    if( empty ( $header ) )
    {
      return;
    }
        // RETURN : there is no header

      // Get the header content
    $htmlContent  = $GLOBALS['TSFE']->cObj->cObjGetSingle( $header['content'], $header['content.'] );

    return $htmlContent;
  }




  /***********************************************
  *
  * ZZ
  *
  **********************************************/

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
    switch( true )
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

    if( strlen( $hex ) == 3 )
    {
      $r = hexdec( substr( $hex, 0, 1) . substr( $hex, 0, 1 ) );
      $g = hexdec( substr( $hex, 1, 1) . substr( $hex, 1, 1 ) );
      $b = hexdec( substr( $hex, 2, 1) . substr( $hex, 2, 1 ) );
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
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $GLOBALS['TSFE']->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }

    return $value;
  }

}

?>