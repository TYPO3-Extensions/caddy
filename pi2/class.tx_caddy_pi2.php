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

require_once( PATH_tslib . 'class.tslib_pibase.php' );

/**
* plugin 'Minicart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package	TYPO3
 * @subpackage	tx_caddy
 * @version	2.1.0
 * @since       2.1.0
 */
class tx_caddy_pi2 extends tslib_pibase
{

  public $prefixId      = 'tx_caddy_pi2';
  public $scriptRelPath = 'pi2/class.tx_caddy_pi2.php';
  public $extKey        = 'caddy';

 /**
  * main( ) : the main method of the PlugIn
  *
  * @param      string      $content  : plugin content. Usually empty.
  * @param      array       $conf     : plugin configuration.
  * @return     string      $content  : The content that is displayed on the website
  * @version  2.1.0
  * @since    2.1.0
  */	
  public function main( $content, $conf ) 
  {
    $this->conf = $conf;
    
    $this->init( );
    
    $this->products   = $this->session->productsGet( $this->pidCaddy );
    $numberOfProducts = count( $this->products );
    
    switch( true )
    {
      case( $numberOfProducts > 0 ):
        $caddy = $this->caddyWiProducts( );
        break;
      case( $numberOfProducts = 0 ):
      default:
        $caddy = $this->caddyWoProducts( );
        break;
    }
    
    unset( $numberOfProducts );
    
    $content = $caddy;

    return $this->pi_wrapInBaseClass( $content );
  }



  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * caddyWiProducts( ) : 
  *
  * @return     string      $content  : mini caddy in case of products
  * @access   private
  * @version  2.1.0
  * @since    2.1.0
  */	
  private function caddyWiProducts( ) 
  {
    $tmpl = $this->tmpl['caddysum'];
          
    $key                  = 'sum';
    $name                 = $this->conf['content.'][$key];
    $conf                 = $this->conf['content.'][$key . '.'];
    $value                = $this->local_cObj->cObjGetSingle( $name, $conf );
    $marker               = '###' . strtoupper( $key ) . '###';
    $markerArray[$marker] = $value;

    $content  = $this->local_cObj->substituteMarkerArrayCached( $tmpl, $markerArray);
    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
    $content  = $this->dynamicMarkers->main( $content, $this );
    
    return $content;
  }

 /**
  * caddyWoProducts( ) : 
  *
  * @return     string      $content  : mini caddy in case of no products
  * @access   private
  * @version  2.1.0
  * @since    2.1.0
  */	
  private function caddyWoProducts( ) 
  {
    $content = null;
    return $content;
    
//    $content  = $this->tmpl['caddysumempty'];
//    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;
//    $content  = $this->dynamicMarkers->main( $content, $this );
//    
//    return $content;
  }



  /***********************************************
  *
  * Init
  *
  **********************************************/

 /**
  * init( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function init( )
  {
    $this->initVars( );
    
    $this->initInstances( );
    $this->initFlexform( );
    $this->initDrs( );

    $this->initPid( );
    $this->initTemplate( );
    
    $this->initCobj( );
    
    //$this->session->setParentObject( $this );
  }
  
/**
 * initCobj( ) :
 *
 * @return	void
 * @access private
 * @version 2.1.0
 * @since 2.1.0
 */
  private function initCobj( )
  {
      // Get the current session array
    $sesArray = $GLOBALS['TSFE']->fe_user->getKey( 'ses', $this->extKey . '_' . $this->pidCaddy );

      // data: implode the array to a one dimensional array
    $data = t3lib_BEfunc::implodeTSParams( $sesArray );

      // cObject becomes current record
    $this->local_cObj->start( $data );

      // RETURN : no DRS
    if( ! $this->drs->drsCobj )
    {
      return;
    }
      // RETURN : no DRS

      // DRS
    $cObjData = var_export( $this->local_cObj->data, true );
    $prompt   = 'cObj->data: ' . $cObjData;
    t3lib_div::devlog( '[INFO/COBJ] ' . $prompt, $this->extKey, 0 );
      // DRS
  }
  
 /**
  * initDrs( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initDrs( )
  {
    $this->drs->init( );
  }
  
 /**
  * initFlexform( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initFlexform( )
  {
    $this->flexform->main( );
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
    
    require_once( $path2lib . 'drs/class.tx_caddy_drs.php' );
    $this->drs              = t3lib_div::makeInstance( 'tx_caddy_drs' );
    $this->drs->pObj        = $this;
    $this->drs->row         = $this->cObj->data;

    require_once( $path2lib . 'class.tx_caddy_dynamicmarkers.php' );
    $this->dynamicMarkers = t3lib_div::makeInstance( 'tx_caddy_dynamicmarkers' );
    $this->dynamicMarkers->scriptRelPath = $this->scriptRelPath;

    require_once( 'class.tx_caddy_pi2_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_caddy_pi2_flexform' );
    $this->flexform->pObj   = $this;
    $this->flexform->row    = $this->cObj->data;
//var_dump( __METHOD__, __LINE__, $this->cObj->data );
    require_once( $path2lib . 'class.tx_caddy_session.php'); // file for div functions
    $this->session = t3lib_div::makeInstance('tx_caddy_session'); // Create new instance for div functions
    $this->session->setParentObject( $this );
    
//    require_once( $path2lib . 'class.tx_caddy_template.php' );
//    $this->template         = t3lib_div::makeInstance( 'tx_caddy_template' );
//    $this->template->pObj   = $this;

  }

/**
 * initPid( ) :
 *
 * @return	void
 * @version 2.0.0
 * @since   2.0.0
 */
  private function initPid( )
  {
      // Take pid from TypoScript
    $this->pidCaddy = $this->conf['pid'];

      // IF : pid is empty
    if( empty( $this->pidCaddy ) )
    {
        // Take pid from the flexform
      $this->pidCaddy = $this->flexform->sdefPidCaddy;
    }
      // IF : pid is empty

      // DIE  : $row is empty
    if( empty( $this->pidCaddy ) )
    {
      $prompt = 'ERROR: uid of the page with the caddy is empty!<br />' . PHP_EOL 
              . '<br />' . PHP_EOL 
              . 'Please take care of a proper configuration.<br />' . PHP_EOL 
              . '<br />' . PHP_EOL 
              . 'Please maintain<br />' . PHP_EOL 
              . '* constant editor > category [CADDY - MAIN] > pid<br />' . PHP_EOL 
              . 'or<br />' . PHP_EOL 
              . '* plugin / flexform > [Plugin] > [Caddy] > Page with the caddy<br />' . PHP_EOL 
              . '<br />' . PHP_EOL 
              . 'Sorry for the trouble.<br />' . PHP_EOL 
              . 'TYPO3 Caddy > sum (plugin 2)<br />' . PHP_EOL 
              . '<br />' . PHP_EOL 
              . __METHOD__ . ' (' . __LINE__ . ')'
              ;
      die( $prompt );
    }
      // DIE  : $row is empty
  }

 /**
  * initPowermail( )
  *
  * @return	void
  * @access private
  * @internal   #45915
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initPowermail( )
  {
    $this->powermail->init( $this->cObj->data );
  }

 /**
  * initTemplate( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initTemplate( )
  {
    $fileRessource  = $this->conf['templates.']['html.']['caddysum.']['file'];
    $template       = $this->cObj->fileResource( $fileRessource );
    $markerAll      = $this->conf['templates.']['html.']['caddysum.']['marker.']['all'];
    
    $this->tmpl['caddysum']      = $this->cObj->getSubpart( $template, $markerAll );
    $this->tmpl['items']         = $this->cObj->getSubpart( $this->tmpl['caddysum'], $markerItem );
    $marker                      = '###CADDYSUMEMPTY###';
    $this->tmpl['caddysumempty'] = $this->cObj->getSubpart( $template, $marker );
  }

 /**
  * initVars( )
  *
  * @return	void
  * @access private
  * @version    2.0.0
  * @since      2.0.0
  */
  private function initVars( )
  {
    $this->local_cObj = $GLOBALS['TSFE']->cObj;

    $this->pi_setPiVarDefaults();
    $this->pi_loadLL();
    $this->pi_initPIflexForm( );
  }

}