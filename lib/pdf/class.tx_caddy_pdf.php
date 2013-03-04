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
  
  public $drsUserfunc   = null;



  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content  : The PlugIn content
  * @param	array		$conf     : The PlugIn configuration
  * @return	string		$errorcnt : Error prompt
  * @version    2.0.0
  * @since      1.4.6
  */
  public function createPdf( &$session=null ) 
  {
    $this->conf         = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    $this->confSettings = $this->conf['settings.'];
    $this->confPdf      = $this->conf['pdf.'];

    $this->pi_loadLL();
    $errorcnt = 0;
    // CHECK: Should PDF attached to mail?
    unset($session['files']);

//    $this->ofilename =  $GLOBALS['TSFE']->cObj->cObjGetSingle
//                        (
//                          $this->confPdf['orderpdf.']['filename'], 
//                          $this->confPdf['orderpdf.']['filename.']
//                        );
    $this->pfilename =  $GLOBALS['TSFE']->cObj->cObjGetSingle
                        (
                          $this->confPdf['deliveryorder.']['filename'], 
                          $this->confPdf['deliveryorder.']['filename.']
                        );

//    $this->onumber =  $GLOBALS['TSFE']->cObj->cObjGetSingle
//                      (
//                        $this->confPdf['orderpdf.']['ordernumber'], 
//                        $this->confPdf['orderpdf.']['ordernumber.']
//                      );
    $this->pnumber =  $GLOBALS['TSFE']->cObj->cObjGetSingle
                      (
                        $this->confPdf['deliveryorder.']['packinglistnumber'], 
                        $this->confPdf['deliveryorder.']['packinglistnumber.']
                      );

//    $this->conf = $this->confPdf['orderpdf.'];
//    $errorcnt += $this->renderOrderPdf($session);
    
    //$this->conf = $this->confPdf['deliveryorder.'];
    $errorcnt += $this->renderPackinglistPdf( );

    return $errorcnt;
  }

  private function renderOrderPdf( &$session ) {
          $filename = $this->concatFileName($this->ofilename);

          $this->tmpl['all'] = $GLOBALS['TSFE']->cObj->getSubpart($GLOBALS['TSFE']->cObj->fileResource($this->conf['template']), '###CADDY_ORDERPDF###'); // Load HTML Template
          $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart($this->tmpl['all'], '###ITEM###'); // work on subpart 2

          // CHECK: Is directory for PDF available?
          if (!is_dir('uploads/tx_caddy')) {
                  $session['error'][] = 'directory for order pdf is not valid';

                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('error.mailheader.orderpdf');
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

                  $fpdi->SetFont('Helvetica','',$this->conf['font-size']);

                  $this->renderOrderAddress($fpdi);
                  $this->renderShippingAddress($fpdi);
                  $this->renderOrderNumber($fpdi);
                  $this->renderAdditionalTextblocks($fpdi);

                  $subpartArray = $this->renderCartHeadline( $subpartArray );
                  $outerMarkerArray = $outerMarkerArray . $subpartArray;
                  foreach ($session['products'] as $key => $product) {
                    $subpartArray['###CONTENT###'] .= $this->renderCartProduct($product);
                  }
                  $this->renderCartSum($outerMarkerArray, $session);

                  $html = $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached($this->tmpl['all'], $outerMarkerArray, $subpartArray);

                  $fpdi->SetLineWidth(1);
                  $fpdi->writeHTMLCell($this->conf['cart-width'], 0, $this->conf['cart-position-x'], $this->conf['cart-position-y'], $html, 0, 2);

                  $this->renderPaymentOption($fpdi, $session['payment']);

                  $fpdi->Output('uploads/tx_caddy'.'/'.$filename, 'F');
          }

          // CHECK: Was PDF not created, send E-Mail and exit with error.
          if (!file_exists('uploads/tx_caddy'.'/'.$filename)) {
                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('error.mailheader.orderpdf');
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

 /**
  * renderPackinglistPdf( ) : 
  *
  * @return	boolean		true, in case of en arror. false, if all is proper
  * @version    2.0.0
  * @since      1.4.6
  */
  private function renderPackinglistPdf( )
  {
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_caddy_' . $GLOBALS["TSFE"]->id );
var_dump( __METHOD__, __LINE__, $sesArray );
    if( empty( $sesArray['products'] ) )
    {
      return;
    }
    $this->conf = $this->conf['pdf.']['deliveryorder.'];

    $filename = time( ) . $this->concatFileName($this->pfilename);

    $this->tmpl['all']  = $GLOBALS['TSFE']->cObj->getSubpart
                          (
                            $GLOBALS['TSFE']->cObj->fileResource
                            (
                              $this->conf['template']
                            ), 
                            '###CADDY_PACKINGLISTPDF###'
                          );
    
    $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart( $this->tmpl['all'], '###ITEM###' );

    // CHECK: Is directory for PDF available?
    if( ! is_dir( 'uploads/tx_caddy' ) )
    {
      $prompt = 'uploads/tx_caddy/ not found<br />
        ' .__METHOD__. ' (' . __LINE__ . ')';
      die( $prompt );
    }

      // CHECK: Is PDF already created?
    if( file_exists( 'uploads/tx_caddy' . '/' . $filename ) ) 
    {
      $prompt = 'uploads/tx_caddy' . '/' . $filename . ' exists!<br />' .  PHP_EOL .
        __METHOD__. ' (' . __LINE__ . ')<br />' . PHP_EOL;
//      die( $prompt );
      echo $prompt;
      return;
    }

    $fpdi = new FPDI( );
    $fpdi->AddPage( );

    if( $this->conf['include_file'] ) 
    {
      $fpdi->setSourceFile($this->conf['include_file']);
      $tplIdx = $fpdi->importPage( 1 );
      $fpdi->useTemplate( $tplIdx, 0, 0, 210 );
    }

    $fpdi->SetFont( 'Helvetica','',$this->conf['font-size'] );
    $fpdi->SetTextColor( 255, 255, 255 );

    $this->renderShippingAddress( $fpdi, true );
    $this->renderPackinglistNumber( $fpdi );
    $this->renderAdditionalTextblocks( $fpdi );

    $fpdi->SetY( $this->conf['cart-position-y'] );

    $subpartArray = null;
    $subpartArray = $this->renderCartHeadline( $subpartArray );

    foreach( $sesArray['products'] as $product ) 
    {
      $subpartArray['###CONTENT###'] = $subpartArray['###CONTENT###'] . $this->renderCartProduct( $product );
    }
    
    $html = $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached
            (
              $this->tmpl['all'], $this->outerMarkerArray, $subpartArray
            );

    $fpdi->writeHTMLCell
    (
      $this->conf['cart-width'], 
      0, 
      $this->conf['cart-position-x'], 
      $this->conf['cart-position-y'], 
      $html, 
      0, 
      2
    );

    $fpdi->Output( 'uploads/tx_caddy' . '/' . $filename, 'F' );


    // CHECK: Was PDF not created, send E-Mail and exit with error.
    if( ! file_exists( 'uploads/tx_caddy' . '/' . $filename ) ) 
    {
      $prompt = 'uploads/tx_caddy' . '/' . $filename . ' could not written!<br />
        ' .__METHOD__. ' (' . __LINE__ . ')';
      die( $prompt );
    }
    // CHECK: Was PDF not created, send E-Mail and exit with error.
    if( file_exists( 'uploads/tx_caddy' . '/' . $filename ) ) 
    {
      $prompt = 'uploads/tx_caddy' . '/' . $filename . ' is written!<br />' . PHP_EOL .
        __METHOD__. ' (' . __LINE__ . ')<br />' . PHP_EOL;
      echo $prompt;
    }
    $sesArray['files'][$filename] = 'uploads/tx_caddy'.'/'.$filename;
  }

  private function renderOrderAddress( &$fpdi )
  {
    $orderaddress = $GLOBALS['TSFE']->cObj->cObjGetSingle
                    (
                      $this->conf['orderaddress'], $this->conf['orderaddress.']
                    );
    if( ! empty( $orderaddress ) )
    {
      $orderaddressheadline = $GLOBALS['TSFE']->cObj->cObjGetSingle
                              (
                                $this->conf['orderaddress.']['0'], $this->conf['orderaddress.']['0.']
                              );
      if( $orderaddressheadline )
      {
        $orderaddress = $orderaddressheadline . $orderaddress;
      }
      $fpdi->writeHtmlCell
      (
        160, 
        0, 
        $this->conf['orderaddress-position-x'], 
        $this->conf['orderaddress-position-y'], 
        $orderaddress
      );
    }
  }

  private function renderShippingAddress( &$fpdi, $fallback=false )
  {
    $shippingaddress =  $GLOBALS['TSFE']->cObj->cObjGetSingle
                        (
                          $this->conf['shippingaddress'], $this->conf['shippingaddress.']
                        );

    if ( ! empty( $shippingaddress ) )
    {
      $shippingaddressheadline =  $GLOBALS['TSFE']->cObj->cObjGetSingle
                                  (
                                    $this->conf['shippingaddress.']['0'], $this->conf['shippingaddress.']['0.']
                                  );
      if( $shippingaddressheadline )
      {
        $shippingaddress = $shippingaddressheadline . $shippingaddress;
      }
      $fpdi->writeHtmlCell
      (
        160, 
        0, 
        $this->conf['shippingaddress-position-x'],
        $this->conf['shippingaddress-position-y'], 
        $shippingaddress
      );
    }
    elseif ( $fallback ) 
    {
      $this->renderOrderAddress( $fpdi );
    }
  }

  private function renderCartHeadline( $subpartArray )
  {
    foreach( array_keys ( ( array ) $this->confSettings['powermailCart.']['fields.'] ) as $key )
    { 
      if (!stristr($key, '.'))
      { 
        $subpartArray['###CADDY_LL_' . strtoupper($key) . '###'] = $this->pi_getLL('caddy_ll_' . $key);
      }
    }
    
    return $subpartArray;

  }

  private function renderCartProduct($product) {
          $local_cObj = $GLOBALS['TSFE']->cObj; // cObject
          $product['price_total'] = $product['price'] * $product['qty']; // price total
          $local_cObj->start($product, $this->conf['db.']['table']); // enable .field in typoscript

          foreach ((array) $this->confSettings['powermailCart.']['fields.'] as $key => $value)
          { 
                  if (!stristr($key, '.'))
                  { 
                          $productOut[$key] = $local_cObj->cObjGetSingle($this->confSettings['powermailCart.']['fields.'][$key], $this->confSettings['powermailCart.']['fields.'][$key . '.']); // write to marker
                          $productOut[$key] = str_replace('&euro;', '€', $productOut[$key]);
                          $productOut[$key] = str_replace('&nbsp;', ' ', $productOut[$key]);

                          $this->markerArray['###' . strtoupper($key) . '###'] = $productOut[$key]; // write to marker
                  }
          }

          return $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached($this->tmpl['item'], $this->markerArray); // add inner html to variable
  }

  private function renderCartSum(&$subpartArray, $session) {
          global $TSFE;

          $outerArr = array(
                  'optionsGross'      => $session['optionsGross'],
                  'optionsNet'        => $session['optionsNet'], 
                  'ordernumber'       => $session['ordernumber'],
                  'packinglistnumber' => $session['packinglistnumber'],
                  'productsGross'     => $session['productsGross'],
                  'productsNet'       => $session['productsNet'],
                  'sumGross'          => $session['sumGross'],
                  'sumNet'            => $session['sumNet'],
                  'sumTaxReduced'     => $session['sumTaxReduced'],
                  'sumTaxNormal'      => $session['sumTaxNormal']
          );

          $local_cObj = $GLOBALS['TSFE']->cObj;
          $local_cObj->start($outerArr, $this->conf['db.']['table']);

          foreach ((array) $this->confSettings['powermailCart.']['overall.'] as $key => $value)
          { // one loop for every param of the current product
                  if (!stristr($key, '.'))
                  { // no .
                          $out = $local_cObj->cObjGetSingle($this->confSettings['powermailCart.']['overall.'][$key], $this->confSettings['powermailCart.']['overall.'][$key . '.']); // write to marker
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

  private function renderOrderNumber(&$fpdi) {
          $fpdi->SetXY($this->conf['ordernumber-position-x'], $this->conf['ordernumber-position-y']);

          $fpdi->Cell('150', '6', $this->onumber);
  }

  private function renderPackinglistNumber(&$fpdi) {
          $fpdi->SetXY($this->conf['packinglistnumber-position-x'], $this->conf['packinglistnumber-position-y']);

          $fpdi->Cell('150', '6', $this->pnumber);
  }

  private function renderAdditionalTextblocks(&$fpdi) {
          foreach ($this->conf['additionaltextblocks.'] as $key => $value) {
                  $html = $GLOBALS['TSFE']->cObj->cObjGetSingle($value['content'], $value['content.']);

                  $fpdi->writeHTMLCell($value['width'], $value['height'], $value['position-x'], $value['position-y'], $html, 0, 2, 0, true, $value['align'] ? $value['align'] : 'L', true);
          }
  }

  private function renderPaymentOption(&$fpdi, $payment_id) {
          $payment_option = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'][$payment_id . '.'];

          if ($payment_option['note']) {
                  $fpdi->SetY($fpdi->GetY()+20);
                  $fpdi->SetX($this->conf['cart-position-x']);
                  $fpdi->Cell('150', '5', $payment_option['title'], 0, 1);
                  $fpdi->SetX($this->conf['cart-position-x']);
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

}

?>