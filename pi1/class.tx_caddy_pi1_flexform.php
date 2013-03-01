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
 *   69: class tx_caddy_pi1_flexform
 *  195:     function __construct($parentObj)
 *  217:     function main()
 *
 *              SECTION: piVars
 *  337:     function prepare_piVars()
 *  521:     function prepare_mode()
 *
 *              SECTION: Fields with Priority
 *  573:     function sheet_sDEF_views()
 *
 *              SECTION: Sheets
 *  903:     function sheet_advanced()
 * 1058:     function sheet_evaluate( )
 * 1117:     function sheet_extend( )
 * 1198:     function sheet_javascript()
 * 1500:     function sheet_sDEF( )
 * 2141:     function sheet_socialmedia()
 * 2205:     function sheet_tca()
 * 2266:     function sheet_templating()
 * 2531:     function sheet_viewList( )
 * 3352:     function sheet_viewSingle()
 *
 * TOTAL FUNCTIONS: 15
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

    // [sdef]
    // [boolean] enable DRS
  public $sdefDrs = null;
    // [boolean] enable update wizard
  public $sdefUpdatewizard = null;
    // [string] csv list of allowed IP
  public $sdefCsvallowedip;

    // Parent object
  public $pObj = null;
    // Current row
  public $row = null;









  /**
 * main():  Process the values from the pi_flexform field.
 *          Process each sheet.
 *          Allocates values to TypoScript.
 *
 * @return    void
 * @version 4.1.10
 */
  function main()
  {

      // Sheets
    $this->sheetSdef( );
      // Sheets

  }

  
  
  /***********************************************
   *
   * Sheets
   *
   **********************************************/

/**
 * sheetSdef: Configuration for evaluation
 *
 * @return    void
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

// @see pObj->initByFlexform( )
//      // sdefDrs
//    $field          = 'sdefDrs';
//    $this->sdefDrs  = $this->zzFfValue( $sheet, $field, false );
//      // sdefDrs

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
 * @return    mixed   $value  : Value from the flexform field
 * @version 2.0.0
 * @since   2.0.0
 */
  public function zzFfValue( $sheet, $field, $drs=true )
  {
    $pi_flexform = $this->row['pi_flexform'];
var_dump( __METHOD__, __LINE__, $pi_flexform );      

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