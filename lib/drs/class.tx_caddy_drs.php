<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2014 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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
 *   51: class tx_caddy_drs
 *  102:     public function init( )
 *  124:     private function initByExtmngr( )
 *  165:     private function initByFlexform( )
 *  205:     public function zzDrsPromptsTrue( )
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * plugin 'Cart to powermail' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	6.0.3
 * @since       1.4.6
 */
class tx_caddy_drs
{

  // path to this script relative to the extension dir.
  private $extKey = 'caddy';
  // Parent object
  private $pObj = null;
  // Current row
  private $row = null;
  private $extConf;
  private $flexform;
  public $drsError = false;
  public $drsWarn = false;
  public $drsInfo = false;
  public $drsOk = false;
  public $drsCalc = false;
  public $drsClean = false;
  public $drsCobj = false;
  public $drsEpayment = false;
  public $drsFlexform = false;
  public $drsFormula = false;
  public $drsInit = false;
  public $drsJavascript = false;
  public $drsMarker = false;
  public $drsOptions = false;
  public $drsPowermail = false;
  public $drsSession = false;
  public $drsSql = false;
  public $drsStockmanager = false;
  public $drsTodo = false;
  public $drsVariants = false;

  /**
   * init( ): Init the DRS - Development Reportinmg System
   *
   * @return	void
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function init()
  {
    $this->initByExtmngr();

    // RETURN : DRS is enabled by the extension manager
    if ( $this->drsOk )
    {
      return;
    }
    // RETURN : DRS is enabled by the extension manager

    $this->initByFlexform();
  }

  /**
   * initByExtmngr( ): Init the DRS - Development Reportinmg System
   *
   * @return	void
   * @access private
   * @version    2.0.0
   * @since      2.0.0
   */
  private function initByExtmngr()
  {

    switch ( $this->pObj->extConf[ 'debuggingDrs' ] )
    {
      case( 'Disabled' ):
      case( null ):
        return;
        break;
      case( 'Enabled (for debugging only!)' ):
        // Follow the workflow
        break;
      default:
        $prompt = 'Error: debuggingDrs is undefined.<br />
          value is ' . $this->pObj->extConf[ 'debuggingDrs' ] . '<br />
          <br />
          ' . __METHOD__ . ' line(' . __LINE__ . ')';
        die( $prompt );
    }

    $this->zzDrsPromptsTrue();

    $prompt = 'The DRS - Development Reporting System is enabled: ' . $this->pObj->extConf[ 'debuggingDrs' ];
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    $prompt = 'The DRS is enabled by the extension manager.';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    $str_header = $this->row[ 'header' ];
    $int_uid = $this->row[ 'uid' ];
    $int_pid = $this->row[ 'pid' ];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
  }

  /**
   * initByFlexform( ): Init the DRS - Development Reportinmg System
   *
   * @return	void
   * @access private
   * @version    4.0.5
   * @since      2.0.0
   */
  private function initByFlexform()
  {

    // RETURN : parent object doesn't have any flexform
    if ( !is_object( $this->pObj->flexform ) )
    {
      return;
    }
    // sdefDrs
    $sheet = 'sDEF';
    $field = 'sdefDrs';
    $this->pObj->flexform->sdefDrs = $this->pObj->flexform->zzFfValue( $sheet, $field, false );
    // sdefDrs
    // Enable the DRS by TypoScript
    if ( empty( $this->pObj->flexform->sdefDrs ) )
    {
      return;
    }

    $this->zzDrsPromptsTrue();

    $prompt = 'The DRS - Development Reporting System is enabled by the flexform (frontend mode).';
    t3lib_div::devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
    $str_header = $this->row[ 'header' ];
    $int_uid = $this->row[ 'uid' ];
    $int_pid = $this->row[ 'pid' ];
    $prompt = '"' . $str_header . '" (pid: ' . $int_pid . ', uid: ' . $int_uid . ')';
    t3lib_div :: devlog( '[INFO/DRS] ' . $prompt, $this->extKey, 0 );
  }

  /**
   * setExtConf( ):
   *
   * @param array $extConf: Extension Configuration array
   * @return	void
   * @access public
   * @version    6.0.3
   * @since      6.0.3
   */
    public function setExtConf( $extConf )
    {
      $this->extConf = $extConf;
    }

  /**
   * setFlexform( ):
   *
   * @param object $flexform
   * @return	void
   * @access public
   * @version    6.0.3
   * @since      6.0.3
   */
    public function setFlexform( $flexform )
    {
      $this->flexform = $flexform;
    }

  /**
   * setRow( ):
   *
   * @param array $row: Current row or cObj->data
   * @return	void
   * @access public
   * @version    6.0.3
   * @since      6.0.3
   */
    public function setRow( $row )
    {
      $this->row = $row;
    }

  /**
   * zzDrsPromptsTrue( ): Init the DRS - Development Reportinmg System
   *
   * @return	void
   * @access public
   * @version    2.0.0
   * @since      2.0.0
   */
  public function zzDrsPromptsTrue()
  {
    $this->drsError = true;
    $this->drsWarn = true;
    $this->drsInfo = true;
    $this->drsOk = true;
    $this->drsCalc = true;
    $this->drsClean = true;
    $this->drsCobj = true;
    $this->drsEpayment = true;
    $this->drsFlexform = true;
    $this->drsFormula = true;
    $this->drsInit = true;
    $this->drsJavascript = true;
    $this->drsMarker = true;
    $this->drsOptions = true;
    $this->drsPowermail = true;
    $this->drsSession = true;
    $this->drsSql = true;
    $this->drsStockmanager = true;
    $this->drsTodo = true;
    $this->drsVariants = true;
  }

}

if ( defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/drs/class.tx_caddy_drs.php' ] )
{
  include_once($TYPO3_CONF_VARS[ TYPO3_MODE ][ 'XCLASS' ][ 'ext/caddy/lib/drs/class.tx_caddy_drs.php' ]);
}
?>
