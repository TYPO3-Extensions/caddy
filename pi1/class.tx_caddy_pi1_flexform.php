<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2013 - Dirk Wildt http://wildt.at.die-netzmacher.de
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
 *   60: class tx_caddy_pi1_flexform
 *  136:     function main()
 *
 *              SECTION: Sheets
 *  165:     private function sheetDeliveryorder( )
 *  214:     private function sheetEmail( )
 *  273:     private function sheetInvoice( )
 *  322:     private function sheetOrigin( )
 *  361:     private function sheetPaths( )
 *  395:     private function sheetSdef( )
 *
 *              SECTION: Zz
 *  436:     public function zzFfValue( $sheet, $field, $drs=true )
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * The class tx_caddy_pi1_flexform bundles all methods for the flexform but any wizard.
 * See Wizards in the wizard class.
 *
 * @author    Dirk Wildt http://wildt.at.die-netzmacher.de
 * @package    TYPO3
 * @subpackage    caddy
 * @version 2.0.0
 * @since   2.0.0
 */
class tx_caddy_pi1_flexform
{
    // Parent object
  public $pObj = null;
    // Current row
  public $row = null;

    // [deliveryorder]
  public $deliveryorderCompany    = null;
  public $deliveryorderFirstname  = null;
  public $deliveryorderLastname   = null;
  public $deliveryorderAddress    = null;
  public $deliveryorderZip        = null;
  public $deliveryorderCity       = null;
  public $deliveryorderCountry    = null;
    // [deliveryorder]

    // [email]
  public $emailCustomerEmail      = null;
  public $emailDeliveryorderMode  = null;
  public $emailInvoiceMode        = null;
  public $emailRevocationMode     = null;
  public $emailTermsMode          = null;
    // [email]

    // [invoice]
  public $invoiceCompany    = null;
  public $invoiceFirstname  = null;
  public $invoiceLastname   = null;
  public $invoiceAddress    = null;
  public $invoiceZip        = null;
  public $invoiceCity       = null;
  public $invoiceCountry    = null;
    // [invoice]

    // [origin]
  public $originMin             = null;
  public $originMax             = null;
  public $originDeliveryorder   = null;
  public $originInvoice         = null;
  public $originOrder           = null;
    // [origin]

    // [paths]
  public $pathsDeliveryorder  = null;
  public $pathsInvoice        = null;
  public $pathsRevocation     = null;
  public $pathsTerms          = null;
    // [paths]

    // [sdef]
    // [boolean] enable DRS
  public $sdefDrs = null;
    // [boolean] enable update wizard
  public $sdefUpdatewizard = null;
    // [string] csv list of allowed IP
  public $sdefCsvallowedip;
    // [sdef]










  /**
 * main():  Process the values from the pi_flexform field.
 *          Process each sheet.
 *          Allocates values to TypoScript.
 *
 * @return	void
 * @version 4.1.10
 */
  function main()
  {

      // Sheets
    $this->sheetSdef( );
    $this->sheetEmail( );
    $this->sheetDeliveryorder ( );
    $this->sheetInvoice( );
    $this->sheetOrigin( );
    $this->sheetPaths( );
      // Sheets

  }



  /***********************************************
   *
   * Sheets
   *
   **********************************************/

/**
 * sheetDeliveryorder( )  :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sheetDeliveryorder( )
  {
    $sheet = 'deliveryorder';

      // deliveryorderCompany
    $field                        = 'company';
    $this->deliveryorderCompany   = $this->zzFfValue( $sheet, $field );
      // deliveryorderCompany

      // deliveryorderFirstname
    $field                        = 'firstName';
    $this->deliveryorderFirstname = $this->zzFfValue( $sheet, $field );
      // deliveryorderFirstname

      // deliveryorderLastname
    $field                        = 'lastName';
    $this->deliveryorderLastname  = $this->zzFfValue( $sheet, $field );
      // deliveryorderLastname

      // deliveryorderAddress
    $field                        = 'address';
    $this->deliveryorderAddress   = $this->zzFfValue( $sheet, $field );
      // deliveryorderAddress

      // deliveryorderZip
    $field                        = 'zip';
    $this->deliveryorderZip       = $this->zzFfValue( $sheet, $field );
      // deliveryorderZip

      // deliveryorderCity
    $field                        = 'city';
    $this->deliveryorderCity      = $this->zzFfValue( $sheet, $field );
      // deliveryorderCity

      // deliveryorderCountry
    $field                        = 'country';
    $this->deliveryorderCountry   = $this->zzFfValue( $sheet, $field );
      // deliveryorderCountry

    return;
  }

/**
 * sheetEmail( )  :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sheetEmail( )
  {
    $sheet = 'email';

      // emailCustomerEmail
    $field                        = 'customerEmail';
    $this->emailCustomerEmail     = $this->zzFfValue( $sheet, $field );
      // emailCustomerEmail

      // emailDeliveryorderMode
    $field                        = 'deliveryorderMode';
    $this->emailDeliveryorderMode = $this->zzFfValue( $sheet, $field );
      // emailDeliveryorderMode

      // pathsDeliveryorder
    $field                        = 'deliveryorderPath';
    $this->pathsDeliveryorder = $this->zzFfValue( $sheet, $field );
      // pathsDeliveryorder

      // emailInvoiceMode
    $field                        = 'invoiceMode';
    $this->emailInvoiceMode       = $this->zzFfValue( $sheet, $field );
      // emailInvoiceMode

      // pathsInvoice
    $field                        = 'invoicePath';
    $this->pathsInvoice           = $this->zzFfValue( $sheet, $field );
      // pathsInvoice

      // emailRevocationMode
    $field                        = 'revocationMode';
    $this->emailRevocationMode    = $this->zzFfValue( $sheet, $field );
      // emailRevocationMode

      // pathsRevocation
    $field                        = 'revocationPath';
    $this->pathsRevocation        = $this->zzFfValue( $sheet, $field );
      // pathsRevocation

      // emailTermsMode
    $field                        = 'termsMode';
    $this->emailTermsMode         = $this->zzFfValue( $sheet, $field );
      // emailTermsMode

      // pathsTerms
    $field                        = 'termsPath';
    $this->pathsTerms             = $this->zzFfValue( $sheet, $field );
      // pathsTerms

    return;
  }

/**
 * sheetInvoice( )  :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sheetInvoice( )
  {
    $sheet = 'invoice';

      // invoiceCompany
    $field                  = 'company';
    $this->invoiceCompany   = $this->zzFfValue( $sheet, $field );
      // invoiceCompany

      // invoiceFirstname
    $field                  = 'firstName';
    $this->invoiceFirstname = $this->zzFfValue( $sheet, $field );
      // invoiceFirstname

      // invoiceLastname
    $field                  = 'lastName';
    $this->invoiceLastname  = $this->zzFfValue( $sheet, $field );
      // invoiceLastname

      // invoiceAddress
    $field                  = 'address';
    $this->invoiceAddress   = $this->zzFfValue( $sheet, $field );
      // invoiceAddress

      // invoiceZip
    $field                  = 'zip';
    $this->invoiceZip       = $this->zzFfValue( $sheet, $field );
      // invoiceZip

      // invoiceCity
    $field                  = 'city';
    $this->invoiceCity      = $this->zzFfValue( $sheet, $field );
      // invoiceCity

      // invoiceCountry
    $field                  = 'country';
    $this->invoiceCountry   = $this->zzFfValue( $sheet, $field );
      // invoiceCountry

    return;
  }

/**
 * sheetOrigin( ) :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sheetOrigin( )
  {
    $sheet = 'origin';

      // originDeliveryorder
    $field                      = 'deliveryorder';
    $this->originDeliveryorder  = ( int ) $this->zzFfValue( $sheet, $field );
      // originDeliveryorder

      // originInvoice
    $field                      = 'invoice';
    $this->originInvoice        = ( int ) $this->zzFfValue( $sheet, $field );
      // originInvoice

      // originMin
    $field                      = 'min';
    $this->originMin          = ( int ) $this->zzFfValue( $sheet, $field );
      // originMin

      // originMax
    $field                      = 'max';
    $this->originMax          = ( int ) $this->zzFfValue( $sheet, $field );
      // originMax

      // originOrder
    $field                      = 'order';
    $this->originOrder          = ( int ) $this->zzFfValue( $sheet, $field );
      // originOrder

    return;
  }

/**
 * sheetPaths( )  :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sheetPaths( )
  {
    $sheet = 'paths';

      // pathsDeliveryorder
    $field                    = 'deliveryorder';
    $this->pathsDeliveryorder = $this->zzFfValue( $sheet, $field );
      // pathsDeliveryorder

      // pathsInvoice
    $field                    = 'invoice';
    $this->pathsInvoice       = $this->zzFfValue( $sheet, $field );
      // pathsInvoice

      // pathsRevocation
    $field                    = 'revocation';
    $this->pathsRevocation    = $this->zzFfValue( $sheet, $field );
      // pathsRevocation

      // pathsTerms
    $field                    = 'terms';
    $this->pathsTerms         = $this->zzFfValue( $sheet, $field );
      // pathsTerms

    return;
  }

/**
 * sheetSdef( ) :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function sheetSdef( )
  {
    $sheet = 'sDEF';

      // sdefCsvallowedip
    $field                  = 'sdefCsvallowedip';
    $this->sdefCsvallowedip = $this->zzFfValue( $sheet, $field );
      // sdefCsvallowedip

      // sdefDrs
// @see pObj->initByFlexform( )
//    $field          = 'sdefDrs';
//    $this->sdefDrs  = $this->zzFfValue( $sheet, $field, false );
      // sdefDrs

      // sdefUpdatewizard
    $field                  = 'sdefUpdatewizard';
    $this->sdefUpdatewizard = $this->zzFfValue( $sheet, $field );
      // sdefUpdatewizard

    return;
  }



  /***********************************************
   *
   * Zz
   *
   **********************************************/

/**
 * zzFfValue: Returns the value of the given flexform field
 *
 * @param	[type]		$$sheet: ...
 * @param	[type]		$field: ...
 * @param	[type]		$drs: ...
 * @return	mixed		$value  : Value from the flexform field
 * @version 2.0.0
 * @since   2.0.0
 */
  public function zzFfValue( $sheet, $field, $drs=true )
  {
    $pi_flexform = $this->row['pi_flexform'];
    $value = $this->pObj->pi_getFFvalue( $pi_flexform, $field, $sheet, 'lDEF', 'vDEF' );

      // RETURN : Don't prompt to DRS
    if( ! $drs )
    {
      return $value;
    }
      // RETURN : Don't prompt to DRS

      // RETURN : DRS is disabled
    if( ! $this->pObj->b_drs_flexform )
    {
      return $value;
    }
      // RETURN : DRS is disabled

      // DRS
    $prompt = $sheet . '.' . $field . ': "' . $value . '"';
    t3lib_div :: devlog('[INFO/FLEXFORM] ' . $prompt, $this->pObj->extKey, 0);
      // DRS

    return $value;
  }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_flexform.php']) {
  include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/pi1/class.tx_caddy_pi1_flexform.php']);
}
?>