<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  (c) 2012 - Version 1.4.6 - Daniel Lorenz <info@capsicum-ug.de>
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
 * Hint: use extdeveval to insert/update function index above.
 */

require_once( PATH_tslib . 'class.tslib_pibase.php' );
require_once( t3lib_extMgm::extPath( 'caddy' ) . 'lib/pdf/tcpdf/tcpdf.php' );
require_once( t3lib_extMgm::extPath( 'caddy' ) . 'lib/pdf/fpdi/fpdi.php' );

/**
 * Class 'pdf' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version     2.0.0
 * @since       1.4.6
 */

class tx_caddy_pdf extends tslib_pibase 
{

    // path for localisation file
  public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';
  public $extKey        = 'caddy';
  
  public $drsUserfunc = null;
  
  
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
  * caddy( ) :
  * 
  * @param      boolean   $invoice  : true: render caddy for the invoice. false: render caddy for the delivery order
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

    
    //$this->renderPaymentOption($fpdi, $session['payment']);

  }

 /**
  * caddyProduct( ) :
  *
  * @param	array		$subpartArray : 
  * @return	string		$content      : the rendered product 
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
  * caddySum( ) :
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
 
    $subpartArray['###CADDY_LL_SUMNET###']        = $this->pi_getLL('caddy_ll_cart_net');
    $subpartArray['###CADDY_LL_SERVICE_COST###']  = $this->pi_getLL('caddy_ll_service_cost');
    $subpartArray['###CADDY_LL_TAX###']           = $this->pi_getLL('caddy_ll_tax');
    $subpartArray['###CADDY_LL_SUMGROSS###']      = $this->pi_getLL('caddy_ll_gross_total');
    $subpartArray['###CADDY_LL_SHIPPING###']      = $this->pi_getLL('caddy_ll_shipping');
    $subpartArray['###CADDY_LL_PAYMENT###']       = $this->pi_getLL('caddy_ll_payment');
    $subpartArray['###CADDY_LL_SPECIAL###']       = $this->pi_getLL('caddy_ll_special');

    $subpartArray['###SHIPPING_OPTION###'] = 
    $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['shipping.']['options.'][$session['shipping'].'.']['title'];
    $subpartArray['###PAYMENT_OPTION###'] = 
    $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'][$session['payment'].'.']['title'];

    $subpartArray['###SPECIAL_OPTION###'] = '';
    if (isset($session['special'])) 
    {
      foreach ($session['special'] as $special_id) 
      {
        $subpartArray['###SPECIAL_OPTION###'] = $subpartArray['###SPECIAL_OPTION###'] .
          $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['special.']['options.'][$special_id.'.']['title'];
      }
    }
    
    return $subpartArray;
  }


 /**
  * caddyTablebody( ) :
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
  * caddyTablehead( ) :
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
    
    return $subpartArray;

  }

  private function renderInvoice( &$session ) {
          $filename = $this->concatFileName($this->ofilename);

          $this->tmpl['all'] = $GLOBALS['TSFE']->cObj->getSubpart($GLOBALS['TSFE']->cObj->fileResource($this->conf['template']), '###CADDY_ORDERPDF###'); // Load HTML Template
          $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart($this->tmpl['all'], '###ITEM###'); // work on subpart 2

          // CHECK: Is directory for PDF available?
          if (!is_dir('uploads/tx_caddy')) {
                  $session['error'][] = 'directory for order pdf is not valid';

                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('error.mailheader.invoice');
                          $mailbody = $this->pi_getLL('error.mailbody.cannotcreate');
                          $mailbody .= $this->pi_getLL('error.mailbody.dirnotfound');
                          if ($abortonerror) {
                                  $mailbody .= $this->pi_getLL('error.mailbody.abort');
                          }
                          t3lib_div::plainMailEncoded($erroremailaddress, $mailheader, $mailbody ,$headers='',$enc='',$charset='',$dontEncodeHeader=false);
                  }
                  if ($abortonerror) {
                          return 1;
                  } else {
                          return 0;
                  }
          }
          // CHECK: Is PDF already created?
          if (!file_exists('uploads/tx_caddy'.'/'.$filename)) {
                  $fpdi = new FPDI();
                  $fpdi->AddPage();

                  if ($this->conf['include_file']) {
                          $fpdi->setSourceFile($this->conf['include_file']);
                          $tplIdx = $fpdi->importPage(1);
                          $fpdi->useTemplate($tplIdx, 0, 0, 210);
                  }
//http://www.tcpdf.org/doc/code/classTCPDF.html
                  $fpdi->SetFont( 'Helvetica', '' , $this->conf['font-size'] );

                  $this->renderInvoiceAddress($fpdi);
                  $this->deliveryorderAddress($fpdi);
                  $this->renderInvoiceNumber($fpdi);
                  $this->renderAdditionalTextblocks($fpdi);

                  $subpartArray = $this->caddyTablehead( $subpartArray );
                  $outerMarkerArray = $outerMarkerArray . $subpartArray;
                  foreach ($session['products'] as $key => $product) {
                    $subpartArray['###CONTENT###'] .= $this->caddyProduct($product);
                  }
                  $this->renderCartSum($outerMarkerArray, $session);

                  $html = $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached($this->tmpl['all'], $outerMarkerArray, $subpartArray);

                  $fpdi->SetLineWidth(1);
                  $fpdi->writeHTMLCell($this->conf['body-width'], 0, $this->conf['body-position-x'], $this->conf['body-position-y'], $html, 0, 2);

                  $this->renderPaymentOption($fpdi, $session['payment']);

                  $fpdi->Output('uploads/tx_caddy'.'/'.$filename, 'F');
          }

          // CHECK: Was PDF not created, send E-Mail and exit with error.
          if (!file_exists('uploads/tx_caddy'.'/'.$filename)) {
                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('error.mailheader.invoice');
                          $mailbody = $this->pi_getLL('error.mailbody.cannotcreate');
                          $mailbody .= $this->pi_getLL('error.mailbody.cannotwrite');
                          if ($abortonerror) {
                                  $mailbody .= $this->pi_getLL('error.mailbody.abort');
                          }
                          t3lib_div::plainMailEncoded($erroremailaddress, $mailheader, $mailbody, $headers='', $enc='', $charset='', $dontEncodeHeader=false);
                  }
                  return 1;
          }
          $session['files'][$filename] = 'uploads/tx_caddy'.'/'.$filename;
  }

  private function renderInvoiceAddress( &$fpdi )
  {
    $invoiceaddress = $GLOBALS['TSFE']->cObj->cObjGetSingle
                    (
                      $this->conf['invoiceaddress'], $this->conf['address.']['invoice.']
                    );
    if( ! empty( $invoiceaddress ) )
    {
      $invoiceaddressheadline = $GLOBALS['TSFE']->cObj->cObjGetSingle
                              (
                                $this->conf['address.']['invoice.']['0'], $this->conf['address.']['invoice.']['0.']
                              );
      if( $invoiceaddressheadline )
      {
        $invoiceaddress = $invoiceaddressheadline . $invoiceaddress;
      }
      $fpdi->writeHtmlCell
      (
        160, 
        0, 
        $this->conf['invoiceaddress-position-x'], 
        $this->conf['invoiceaddress-position-y'], 
        $invoiceaddress
      );
    }
  }



  private function renderCartSum(&$subpartArray, $session) {
          global $TSFE;

          $outerArr = array(
                  'optionsGross'      => $session['optionsGross'],
                  'optionsNet'        => $session['optionsNet'], 
                  'ordernumber'       => $session['ordernumber'],
                  'numbers' => $session['numbers.']['deliveryorder'],
                  'productsGross'     => $session['productsGross'],
                  'productsNet'       => $session['productsNet'],
                  'sumGross'          => $session['sumGross'],
                  'sumNet'            => $session['sumNet'],
                  'sumTaxReduced'     => $session['sumTaxReduced'],
                  'sumTaxNormal'      => $session['sumTaxNormal']
          );

          $local_cObj = $GLOBALS['TSFE']->cObj;
          $local_cObj->start($outerArr, $this->conf['db.']['table']);

          
          foreach ((array) $this->confSettings['powermailCaddy.']['overall.'] as $key => $value)
          { // one loop for every param of the current product
                  if (!stristr($key, '.'))
                  { // no .
                          $out = $local_cObj->cObjGetSingle($this->confSettings['powermailCaddy.']['overall.'][$key], $this->confSettings['powermailCaddy.']['overall.'][$key . '.']); // write to marker
                          $out = str_replace('&euro;', '€', $out);
                          $out = str_replace('&nbsp;', ' ', $out);

                          $subpartArray['###' . strtoupper($key) . '###'] = $out;
                  }
          }

          $subpartArray['###CADDY_LL_SUMNET###'] = $this->pi_getLL('caddy_ll_cart_net');
          $subpartArray['###CADDY_LL_SERVICE_COST###'] = $this->pi_getLL('caddy_ll_service_cost');
          $subpartArray['###CADDY_LL_TAX###'] = $this->pi_getLL('caddy_ll_tax');
          $subpartArray['###CADDY_LL_SUMGROSS###'] = $this->pi_getLL('caddy_ll_gross_total');
          $subpartArray['###CADDY_LL_SHIPPING###'] = $this->pi_getLL('caddy_ll_shipping');
          $subpartArray['###CADDY_LL_PAYMENT###'] = $this->pi_getLL('caddy_ll_payment');
          $subpartArray['###CADDY_LL_SPECIAL###'] = $this->pi_getLL('caddy_ll_special');

          $subpartArray['###SHIPPING_OPTION###'] = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['shipping.']['options.'][$session['shipping'].'.']['title'];
          $subpartArray['###PAYMENT_OPTION###'] = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'][$session['payment'].'.']['title'];

          $subpartArray['###SPECIAL_OPTION###'] = '';
          if (isset($session['special'])) {
                  foreach ($session['special'] as $special_id) {
                          $subpartArray['###SPECIAL_OPTION###'] .= $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['special.']['options.'][$special_id.'.']['title'];
                  }
          }
  }

  private function renderInvoiceNumber(&$fpdi) {
          $fpdi->SetXY($this->conf['invoicenumber-position-x'], $this->conf['invoicenumber-position-y']);

          $fpdi->Cell('150', '6', $this->onumber);
  }



  /***********************************************
  *
  * Delivery Order
  *
  **********************************************/

 /**
  * deliveryorder( ) : 
  *
  * @return	string		$path : path to the rendered pdf file
  * @access     public
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
  * deliveryorderAdditionalTextblocks( ) : 
  *
  * @return	void
  * @access     private
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
  * deliveryorderAddress( ) : 
  *
  * @return	void
  * @access     public
  * @version    2.0.0
  * @since      1.4.6
  */
  private function deliveryorderAddress( $fallBackToInvoiceAddress=false )
  {
      // Get the body content
    $body         = $this->confPdf['deliveryorder.']['content.']['address.']['deliveryorder.']['body.'];
    $htmlContent  = $GLOBALS['TSFE']->cObj->cObjGetSingle( $body['content'], $body['content.'] );
    // Get the body content
      
    switch( true )
    {
      case( ! empty( $htmlContent ) ):
        $invoiceaddress = $this->confPdf['deliveryorder.']['content.']['address.']['deliveryorder.'];
        $this->writeTextblock( $invoiceaddress, 'deliveryorderaddress' );
        break;
      case( empty( $htmlContent ) ):
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
  * deliveryorderDate( ) : 
  *
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */

  private function deliveryorderDate( )
  {
    $date = $this->confPdf['deliveryorder.']['content.']['date.'];
    $this->writeTextblock( $date, 'deliveryorderDate' );
  }

 /**
  * deliveryorderInit( ) : 
  *
  * @return	boolean		false, if delivery order isn't needed
  * @access     private
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
  * deliveryorderNumbers( ) : 
  *
  * @return	void
  * @access     private
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
  * header
  *
  **********************************************/
  
 /**
  * header( ) : 
  *
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function header( $header )
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
  * init
  *
  **********************************************/

 /**
  * init( ) : 
  *
  * @return	void
  * @access     public
  * @version    2.0.0
  * @since      2.0.0
  */ 
  private function init( ) 
  {
    $this->conf         = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    $this->confSettings = $this->conf['settings.'];
    $this->confPdf      = $this->conf['pdf.'];

    $this->local_cObj   = $GLOBALS['TSFE']->cObj;


    $this->pi_loadLL();

      // DIE  : if there is an unproper directory
    $this->initCheckDir( );

      // DIE  : if there is an unproper directory
    $this->initCheckProducts( );

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
  * initCheckDir( ) : 
  *
  * @return	void
  * @access     private
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
  * initCheckProducts( ) : 
  *
  * @return	void
  * @access     private
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



  /***********************************************
  *
  * Invoice
  *
  **********************************************/

 /**
  * invoice( ) : 
  *
  * @return	string		$path : path to the rendered pdf file
  * @access     public
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
  * invoiceAdditionalTextblocks( ) : 
  *
  * @return	void
  * @access     private
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
  * invoiceAddress( ) : 
  *
  * @return	void
  * @access     public
  * @version    2.0.0
  * @since      1.4.6
  */
  private function invoiceAddress( )
  {
//    $invoiceaddress = $this->confPdf['invoice.']['content.']['address.']['invoice.'];
//    $this->writeTextblock( $invoiceaddress, 'invoiceaddress' );
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
  * invoiceDate( ) : 
  *
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */

  private function invoiceDate( )
  {
    $date = $this->confPdf['invoice.']['content.']['date.'];
    $this->writeTextblock( $date, 'invoiceDate' );
  }

 /**
  * invoiceInit( ) : 
  *
  * @return	boolean		false, if delivery order isn't needed
  * @access     private
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
  * invoiceNumbers( ) : 
  *
  * @return	void
  * @access     private
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





  private function renderAdditionalTextblocks(&$fpdi) {
          foreach ($this->confPdf['additionaltextblocks.'] as $key => $value) {
                  $html = $GLOBALS['TSFE']->cObj->cObjGetSingle($value['content'], $value['content.']);

                  $fpdi->writeHTMLCell($value['width'], $value['height'], $value['position-x'], $value['position-y'], $html, 0, 2, 0, true, $value['align'] ? $value['align'] : 'L', true);
          }
  }

  private function renderPaymentOption(&$fpdi, $payment_id) {
          $payment_option = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'][$payment_id . '.'];

          if ($payment_option['note']) {
                  $fpdi->SetY($fpdi->GetY()+20);
                  $fpdi->SetX($this->conf['body-position-x']);
                  $fpdi->Cell('150', '5', $payment_option['title'], 0, 1);
                  $fpdi->SetX($this->conf['body-position-x']);
                  $fpdi->Cell('150', '5', $payment_option['note'], 0, 1);
          }
  } 

 /**
  * concatFileName( ) : Wii prefix the given filename with the current date
  *
  * @param	string		$filename : current filename 
  * @return	string		$filename : prefixed filename
  * @access     private
  * @version    2.0.0
  * @since      1.4.6
  */
 private function concatFileName( $filename )
  {
    $date     = date( 'Ymd' );
    $filename = $date . '-' . $filename . '.pdf';

    return $filename;
  }
  
  

  /***********************************************
  *
  * TCPDF
  *
  **********************************************/

 /**
  * tcpdfInit( ) : 
  *
  * @param	string		$filename : current filename 
  * @param	string		$docTitle : Title of the pdf document
  * @return	string		$filename : prefixed filename
  * @access     private
  * @internal   http://www.tcpdf.org/doc/code/classTCPDF.html
  * @version    2.0.0
  * @since      2.0.0
  */
  private function tcpdfInit( $srceFile, $docTitle='TYPO3 Quick Shop' )
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
  * tcpdfOutput( ) : 
  *
  * @param      string    $destPath : path of the destination file
  * @return	void
  * @access     private
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
  * tcpdfSetFont( ) : 
  *
  * @return	void
  * @access     private
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
  * tcpdfSetTextColor( ) : 
  *
  * @return	void
  * @access     private
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
  *                 from the properties
  *
  * @param      array           $properties   : Array with the properties font, textColor and cell 
  * @param      string          $htmlContent  : Current HTML content
  * @param      string          $drsLabel     : Label for teh DRS prompt. Usually the name of the calling method
  * @return	void
  * @internal   Supported tags are: a, b, blockquote, br, dd, del, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6, 
  *                                 hr, i, img, li, ol, p, pre, small, span, strong, sub, sup, table, tcpdf, 
  *                                 td, th, thead, tr, tt, u, ul 
  *             NOTE: all the HTML attributes must be enclosed in double-quote
  * @access     private
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
    $x            = $this->zz_cObjGetSingle( $cell['x'], $cell['x.'] );$cell['x'];
    $y            = $this->zz_cObjGetSingle( $cell['y'], $cell['y.'] );$cell['y'];
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
  * terms( ) : 
  *
  * @return	string		$path : path to the rendered pdf file
  * @access     public
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
var_dump( __METHOD__, __LINE__, $destPath );
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
    $fallBackToInvoiceAddress = true;
    $this->termsAddress( $fallBackToInvoiceAddress );

      // Write the delivery order date
    $this->termsDate( );

      // Write the delivery order number
    $this->termsNumbers( );

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
  * termsAdditionalTextblocks( ) : 
  *
  * @return	void
  * @access     private
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
  * termsAddress( ) : 
  *
  * @return	void
  * @access     public
  * @version    2.0.0
  * @since      1.4.6
  */
  private function termsAddress( $fallBackToInvoiceAddress=false )
  {
      // Get the body content
    $body         = $this->confPdf['terms.']['content.']['address.']['deliveryorder.']['body.'];
    $htmlContent  = $GLOBALS['TSFE']->cObj->cObjGetSingle( $body['content'], $body['content.'] );
      // Get the body content
      
    switch( true )
    {
      case( ! empty( $htmlContent ) ):
        $invoiceaddress = $this->confPdf['terms.']['content.']['address.']['deliveryorder.'];
        $this->writeTextblock( $invoiceaddress, 'termsaddress' );
        break;
      case( empty( $htmlContent ) ):
      default:
          // FALLBACK : take the invoice address
        if( $fallBackToInvoiceAddress ) 
        {
          $invoiceaddress = $this->confPdf['terms.']['content.']['address.']['invoice.'];
          $this->writeTextblock( $invoiceaddress, 'invoiceAddress' );
        }
          // FALLBACK : take the invoice address
        break;
    }
    unset( $htmlContent );

    return;
  }

 /**
  * termsDate( ) : 
  *
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */

  private function termsDate( )
  {
    $date = $this->confPdf['terms.']['content.']['date.'];
    $this->writeTextblock( $date, 'termsDate' );
  }

 /**
  * termsInit( ) : 
  *
  * @return	boolean		false, if delivery order isn't needed
  * @access     private
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
  
 /**
  * termsNumbers( ) : 
  *
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function termsNumbers( )
  {
    $numbers = $this->confPdf['terms.']['content.']['numbers.'];

      // LOOP : fields, the elements of a product
    foreach( array_keys ( ( array ) $numbers ) as $key )
    { 
      if( ! stristr( $key, '.' ) )
      { 
        continue;
      }
      
      $this->writeTextblock( $numbers[$key], 'terms.numbers.' . $key );
    }
        
  }
  
  

  /***********************************************
  *
  * write
  *
  **********************************************/
  
 /**
  * writeTextblock( ) : 
  *
  * @param      array     $textBlock  : the additional text block
  * @param      string    $drsLabel   : label for the drs prompt. Usually name of the calling function.
  * @return	void
  * @access     private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function writeTextblock( $textBlock, $drsLabel )
  {
//var_dump( __METHOD__, __LINE__, $textBlock );      
    $body         = $textBlock['body.'];
    $htmlContent  = $GLOBALS['TSFE']->cObj->cObjGetSingle( $body['content'], $body['content.'] );

      // RETURN : HTML content is empty
    if( empty( $htmlContent ) )
    {
      return;
    }
      // RETURN : HTML content is empty

    $header       = $textBlock['header.'];
    $htmlContent  = $this->header( $header ) . $htmlContent;
//var_dump( __METHOD__, __LINE__, $body['properties.'] );      
    $this->tcpdfWriteHtmlCell( $body['properties.'], $htmlContent, $drsLabel );
  }




  /***********************************************
  *
  * ZZ
  *
  **********************************************/

 /**
  * zz_hexToRgb( )
  *
  * @param	string		$hex        : color in HTML notation like #FFF or #CC00CC or CSS color name
  * @return	string          $textColor  : color in RGB format like 255 255 255 or 144 0 144 
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
  * zz_cObjGetSingle( )
  *
  * @param	[type]		$$cObj_name: ...
  * @param	[type]		$cObj_conf: ...
  * @return	string
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