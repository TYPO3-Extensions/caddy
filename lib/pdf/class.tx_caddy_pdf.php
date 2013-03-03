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

  public $scriptRelPath = 'lib/pdf/class.tx_caddy_pdf.php';
  public $extKey        = 'caddy';



  /***********************************************
  *
  * Main
  *
  **********************************************/

 /**
  * the main method of the PlugIn
  *
  * @param	string		$content: The PlugIn content
  * @param	array		$conf: The PlugIn configuration
  * @return	The		content that is displayed on the website
  * @version    2.0.0
  * @since      1.4.6
  */
  public function createPdf( &$params, &$session ) 
  {
    $this->conf         = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.'];
    $this->confSettings = $this->conf['settings.'];
    $this->confPdf      = $this->conf['pdf.'];

    $this->pi_loadLL();
    $errorcnt = 0;
    // CHECK: Should PDF attached to mail?
    unset($session['files']);

    $this->ofilename = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->confPdf['orderpdf.']['filename'], $this->confPdf['orderpdf.']['filename.']);
    $this->pfilename = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->confPdf['packinglistpdf.']['filename'], $this->confPdf['packinglistpdf.']['filename.']);

    $this->onumber = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->confPdf['orderpdf.']['ordernumber'], $this->confPdf['orderpdf.']['ordernumber.']);
    $this->pnumber = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->confPdf['packinglistpdf.']['packinglistnumber'], $this->confPdf['packinglistpdf.']['packinglistnumber.']);

    if (($params['subpart']=='recipient_mail') && ($this->confPdf['orderpdf.']['attach_recipient_mail'] == 1)) {
      $this->conf = $this->confPdf['orderpdf.'];
      $errorcnt += $this->renderOrderPdf($session);
    }
    if (($params['subpart']=='sender_mail') && ($this->confPdf['orderpdf.']['attach_sender_mail'] == 1)) {
      $this->conf = $this->confPdf['orderpdf.'];
      $errorcnt += $this->renderOrderPdf($session);
    }
    if (($params['subpart']=='recipient_mail') && ($this->confPdf['packinglistpdf.']['attach_recipient_mail'] == 1)) {
      $this->conf = $this->confPdf['packinglistpdf.'];
      $errorcnt += $this->renderPackinglistPdf($session);
    }
    if (($params['subpart']=='sender_mail') && ($this->confPdf['packinglistpdf.']['attach_sender_mail'] == 1)) {
      $this->conf = $this->confPdf['packinglistpdf.'];
      $errorcnt += $this->renderPackinglistPdf($session);
    }
    return $errorcnt;
  }

  private function renderOrderPdf(&$session) {
          $filename = $this->concatFileName($this->ofilename);

          $this->tmpl['all'] = $GLOBALS['TSFE']->cObj->getSubpart($GLOBALS['TSFE']->cObj->fileResource($this->conf['template']), '###WTCART_ORDERPDF###'); // Load HTML Template
          $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart($this->tmpl['all'], '###ITEM###'); // work on subpart 2

          // CHECK: Is directory for PDF available?
          if (!is_dir($this->conf['dir'])) {
                  $session['error'][] = 'directory for order pdf is not valid';

                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('wtcartorderpdf.error.mailheader.orderpdf');
                          $mailbody = $this->pi_getLL('wtcartorderpdf.error.mailbody.cannotcreate');
                          $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.dirnotfound');
                          if ($abortonerror) {
                                  $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.abort');
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
          if (!file_exists($this->conf['dir'].'/'.$filename)) {
                  $pdf = new FPDI();
                  $pdf->AddPage();

                  if ($this->conf['include_file']) {
                          $pdf->setSourceFile($this->conf['include_file']);
                          $tplIdx = $pdf->importPage(1);
                          $pdf->useTemplate($tplIdx, 0, 0, 210);
                  }

                  $pdf->SetFont('Helvetica','',$this->conf['font-size']);

                  $this->renderOrderAddress($pdf);
                  $this->renderShippingAddress($pdf);
                  $this->renderOrderNumber($pdf);
                  $this->renderAdditionalTextblocks($pdf);

                  $cartitems = '';

                  $outerMarkerArray .= $this->renderCartHeadline($subpartArray);
                  foreach ($session['products'] as $key => $product) {
                          $subpartArray['###CONTENT###'] .= $this->renderCartProduct($product);
                  }
                  $this->renderCartSum($outerMarkerArray, $session);

                  $html = $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached($this->tmpl['all'], $outerMarkerArray, $subpartArray);

                  $pdf->SetLineWidth(1);
                  $pdf->writeHTMLCell($this->conf['cart-width'], 0, $this->conf['cart-position-x'], $this->conf['cart-position-y'], $html, 0, 2);

                  $this->renderPaymentOption($pdf, $session['payment']);

                  $pdf->Output($this->conf['dir'].'/'.$filename, 'F');
          }

          // CHECK: Was PDF not created, send E-Mail and exit with error.
          if (!file_exists($this->conf['dir'].'/'.$filename)) {
                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('wtcartorderpdf.error.mailheader.orderpdf');
                          $mailbody = $this->pi_getLL('wtcartorderpdf.error.mailbody.cannotcreate');
                          $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.cannotwrite');
                          if ($abortonerror) {
                                  $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.abort');
                          }
                          t3lib_div::plainMailEncoded($erroremailaddress, $mailheader, $mailbody, $headers='', $enc='', $charset='', $dontEncodeHeader=false);
                  }
                  return 1;
          }
          $session['files'][$filename] = $this->conf['dir'].'/'.$filename;
  }

  private function renderPackinglistPdf(&$session) {
          $filename = $this->concatFileName($this->pfilename);

          $this->tmpl['all'] = $GLOBALS['TSFE']->cObj->getSubpart($GLOBALS['TSFE']->cObj->fileResource($this->conf['template']), '###WTCART_PACKINGLISTPDF###'); // Load HTML Template
          $this->tmpl['item'] = $GLOBALS['TSFE']->cObj->getSubpart($this->tmpl['all'], '###ITEM###'); // work on subpart 2

          // CHECK: Is directory for PDF available?
          if (!is_dir($this->conf['dir'])) {
                  $session['error'][] = 'directory for packinglist pdf is not valid';

                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('wtcartorderpdf.error.mailheader.packinglistpdf');
                          $mailbody = $this->pi_getLL('wtcartorderpdf.error.mailbody.cannotcreate');
                          $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.dirnotfound');
                          if ($abortonerror) {
                                  $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.abort');
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
          if (!file_exists($this->conf['dir'].'/'.$filename)) {
                  $pdf = new FPDI();
                  $pdf->AddPage();

                  if ($this->conf['include_file']) {
                          $pdf->setSourceFile($this->conf['include_file']);
                          $tplIdx = $pdf->importPage(1);
                          $pdf->useTemplate($tplIdx, 0, 0, 210);
                  }

                  $pdf->SetFont('Helvetica','',$this->conf['font-size']);

                  $this->renderShippingAddress($pdf, true);
                  $this->renderPackinglistNumber($pdf);
                  $this->renderAdditionalTextblocks($pdf);

                  $pdf->SetY($this->conf['cart-position-y']);

                  $cartitems = '';

                  $outerMarkerArray .= $this->renderCartHeadline($subpartArray);
                  foreach ($session['products'] as $key => $product) {
                          $subpartArray['###CONTENT###'] .= $this->renderCartProduct($product);
                  }
                  $html = $GLOBALS['TSFE']->cObj->substituteMarkerArrayCached($this->tmpl['all'], $this->outerMarkerArray, $subpartArray);

                  $pdf->writeHTMLCell($this->conf['cart-width'], 0, $this->conf['cart-position-x'], $this->conf['cart-position-y'], $html, 0, 2);

                  $pdf->Output($this->conf['dir'].'/'.$filename, 'F');


          }

          // CHECK: Was PDF not created, send E-Mail and exit with error.
          if (!file_exists($this->conf['dir'].'/'.$filename)) {
                  $erroremailaddress = $this->conf['erroremailaddress'];
                  if ($erroremailaddress) {
                          $mailheader = $this->pi_getLL('wtcartorderpdf.error.mailheader.packinglistpdf');
                          $mailbody = $this->pi_getLL('wtcartorderpdf.error.mailbody.cannotcreate');
                          $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.cannotwrite');
                          if ($abortonerror) {
                                  $mailbody .= $this->pi_getLL('wtcartorderpdf.error.mailbody.abort');
                          }
                          t3lib_div::plainMailEncoded($erroremailaddress, $mailheader, $mailbody, $headers='', $enc='', $charset='', $dontEncodeHeader=false);
                  }
                  if ($abortonerror) {
                          return 1;
                  }
          }
          $session['files'][$filename] = $this->conf['dir'].'/'.$filename;
  }

  private function renderOrderAddress(&$pdf) {
          $orderaddress = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->conf['orderaddress'], $this->conf['orderaddress.']);

          if (!$orderaddress == "") {
                  $orderaddressheadline = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->conf['orderaddress.']['0'], $this->conf['orderaddress.']['0.']);
                  if ($orderaddressheadline)
                  {
                          $orderaddress = $orderaddressheadline . $orderaddress;
                  }
                  $pdf->writeHtmlCell(160, 0, $this->conf['orderaddress-position-x'], $this->conf['orderaddress-position-y'], $orderaddress);
          }
  }

  private function renderShippingAddress(&$pdf, $fallback=false) {
          $shippingaddress = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->conf['shippingaddress'], $this->conf['shippingaddress.']);

          if (!$shippingaddress == "") {
                  $shippingaddressheadline = $GLOBALS['TSFE']->cObj->cObjGetSingle($this->conf['shippingaddress.']['0'], $this->conf['shippingaddress.']['0.']);
                  if ($shippingaddressheadline)
                  {
                          $shippingaddress = $shippingaddressheadline . $shippingaddress;
                  }
                  $pdf->writeHtmlCell(160, 0, $this->conf['shippingaddress-position-x'], $this->conf['shippingaddress-position-y'], $shippingaddress);
          } elseif ($fallback) {
                  $this->renderOrderAddress($pdf);
          }
  }

  private function renderCartHeadline(&$subpartArray) {

          foreach ((array) $this->confSettings['powermailCart.']['fields.'] as $key => $value)
          { 
                  if (!stristr($key, '.'))
                  { 
                          $subpartArray['###WTCART_LL_' . strtoupper($key) . '###'] = $this->pi_getLL('wtcartorderpdf_ll_' . $key);
                  }
          }

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
                  'optionsNet' => $session['optionsNet'], 
                  'optionsGross' => $session['optionsGross'],
                  'sumGross' => $session['sumGross'],
                  'productsGross' => $session['productsGross'],
                  'sumNet' => $session['sumNet'],
                  'productsNet' => $session['productsNet'],
                  'sumTaxReduced' => $session['sumTaxReduced'],
                  'sumTaxNormal' => $session['sumTaxNormal'],
                  'ordernumber' => $session['ordernumber'],
                  'packinglistnumber' => $session['packinglistnumber']
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

          $subpartArray['###WTCART_LL_SUMNET###'] = $this->pi_getLL('wtcartorderpdf_ll_cart_net');
          $subpartArray['###WTCART_LL_SERVICE_COST###'] = $this->pi_getLL('wtcartorderpdf_ll_service_cost');
          $subpartArray['###WTCART_LL_TAX###'] = $this->pi_getLL('wtcartorderpdf_ll_tax');
          $subpartArray['###WTCART_LL_GROSS_TOTAL###'] = $this->pi_getLL('wtcartorderpdf_ll_gross_total');
          $subpartArray['###WTCART_LL_SHIPPING###'] = $this->pi_getLL('wtcartorderpdf_ll_shipping');
          $subpartArray['###WTCART_LL_PAYMENT###'] = $this->pi_getLL('wtcartorderpdf_ll_payment');
          $subpartArray['###WTCART_LL_SPECIAL###'] = $this->pi_getLL('wtcartorderpdf_ll_special');

          $subpartArray['###SHIPPING_OPTION###'] = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['shipping.']['options.'][$session['shipping'].'.']['title'];
          $subpartArray['###PAYMENT_OPTION###'] = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'][$session['payment'].'.']['title'];

          $subpartArray['###SPECIAL_OPTION###'] = '';
          if (isset($session['special'])) {
                  foreach ($session['special'] as $special_id) {
                          $subpartArray['###SPECIAL_OPTION###'] .= $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['special.']['options.'][$special_id.'.']['title'];
                  }
          }
  }

  private function renderOrderNumber(&$pdf) {
          $pdf->SetXY($this->conf['ordernumber-position-x'], $this->conf['ordernumber-position-y']);

          $pdf->Cell('150', '6', $this->onumber);
  }

  private function renderPackinglistNumber(&$pdf) {
          $pdf->SetXY($this->conf['packinglistnumber-position-x'], $this->conf['packinglistnumber-position-y']);

          $pdf->Cell('150', '6', $this->pnumber);
  }

  private function renderAdditionalTextblocks(&$pdf) {
          foreach ($this->conf['additionaltextblocks.'] as $key => $value) {
                  $html = $GLOBALS['TSFE']->cObj->cObjGetSingle($value['content'], $value['content.']);

                  $pdf->writeHTMLCell($value['width'], $value['height'], $value['position-x'], $value['position-y'], $html, 0, 2, 0, true, $value['align'] ? $value['align'] : 'L', true);
          }
  }

  private function renderPaymentOption(&$pdf, $payment_id) {
          $payment_option = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_caddy_pi1.']['payment.']['options.'][$payment_id . '.'];

          if ($payment_option['note']) {
                  $pdf->SetY($pdf->GetY()+20);
                  $pdf->SetX($this->conf['cart-position-x']);
                  $pdf->Cell('150', '5', $payment_option['title'], 0, 1);
                  $pdf->SetX($this->conf['cart-position-x']);
                  $pdf->Cell('150', '5', $payment_option['note'], 0, 1);
          }
  } 

  private function concatFileName($filename) {
          $date = date("Ymd");

          $pathfilename = $date.'-'.$filename.'.pdf';

          return $pathfilename;
  }

}

?>