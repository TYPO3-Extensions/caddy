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
 * @version	2.0.2
 * @since       2.0.2
 */
class tx_caddy_pi3 extends tslib_pibase
{

  public $prefixId = 'tx_caddy_pi3';
  public $scriptRelPath = 'pi3/class.tx_caddy_pi3.php';
  public $extKey = 'caddy';

 /**
  * main( ) : the main method of the PlugIn
  *
  * @param      string      $content  : plugin content. Usually empty.
  * @param      array       $conf     : plugin configuration.
  * @return     string      $content  : The content that is displayed on the website
  * @version  2.0.2
  * @since    2.0.2
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
  * @version  2.0.2
  * @since    2.0.2
  */	
  public function caddyWiProducts( ) 
  {
    $numberOfItems  = $this->zz_numberOfItems( );
    
    $cObjData = array
    (
      'numberofitems' => $numberOfItems,
      'minisumgross'  => $this->session->productsGetGross( $this->pidCaddy )
    );

    $this->local_cObj->start( $cObjData, $this->conf['db.']['table'] );
var_dump( __METHOD__, __LINE__, $this->local_cObj->data );
    foreach( array_keys( ( array ) $this->conf['content.'] ) as $key )
    {
      if( stristr( $key, '.' ) )
      {
        continue;
      }
      $name                 = $this->conf['content.'][$key];
      $conf                 = $this->conf['content.'][$key . '.'];
      $value                = $this->local_cObj->cObjGetSingle( $name, $conf );
      $marker               = '###' . strtoupper( $key ) . '###';
      $markerArray[$marker] = $value;
    }
var_dump( __METHOD__, __LINE__, $this->products, count( $this->products ), $markerArray );

    $pid                  = $this->pidCaddy;

//    $value                = $numberOfProducts;
//    $marker               = '###MINICART_COUNT###';
//    $markerArray[$marker] = $value;
//
//    $value                = $this->session->productsGetGross( $this->pidCaddy );
//    $marker               = '###MINISUMGROSS###';
//    $markerArray[$marker] = $value;

    $label                = $this->pi_getLL('link');
    $typolinkConf         = array( );
    $value                = $this->pi_linkToPage( $label, $pid, null, $typolinkConf );
    $marker               = '###MINICART_LINK###';
    $markerArray[$marker] = $value;

    $marker               = '###MINICART_LINK_URL###';
    $markerArray[$marker] = $this->pi_getPageLink( $pid, null, $typolinkConf);

var_dump( __METHOD__, __LINE__, $this->products, count( $this->products ), $markerArray );
    $tmpl     = $this->tmpl['caddymini'];
    $content  = $this->local_cObj->substituteMarkerArrayCached( $tmpl, $markerArray);
    $content  = $this->dynamicMarkers->main( $content, $this );
    
    return $content;
  }

 /**
  * caddyWoProducts( ) : 
  *
  * @return     string      $content  : mini caddy in case of no products
  * @version  2.0.2
  * @since    2.0.2
  */	
  public function caddyWoProducts( ) 
  {
    $content  = $this->tmpl['caddyminiempty'];
    $content  = $this->dynamicMarkers->main( $content, $this );
    
    return $content;
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
    
    $this->session->setParentObject( $this );
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
    $this->dynamicMarkers->scriptRelPath = 'pi3/class.tx_caddy_pi3.php';

    require_once( 'class.tx_caddy_pi3_flexform.php' );
    $this->flexform         = t3lib_div::makeInstance( 'tx_caddy_pi3_flexform' );
    $this->flexform->pObj   = $this;
    $this->flexform->row    = $this->cObj->data;
//var_dump( __METHOD__, __LINE__, $this->cObj->data );
    require_once( $path2lib . 'class.tx_caddy_session.php'); // file for div functions
    $this->session = t3lib_div::makeInstance('tx_caddy_session'); // Create new instance for div functions
    $this->session->setParentObject( $this );
    
    require_once( $path2lib . 'class.tx_caddy_template.php' );
    $this->template         = t3lib_div::makeInstance( 'tx_caddy_template' );
    $this->template->pObj   = $this;

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
    $this->pidCaddy = $this->flexform->sdefPidCaddy;

      // DIE  : $row is empty
    if( empty( $this->pidCaddy ) )
    {
      $prompt = 'ERROR: uid of the page with the caddy is empty!' . PHP_EOL 
              . 'Please take care of a proper configuration of the plaugin/flexform.<br />' . PHP_EOL 
              . 'Sorry for the trouble.<br />' . PHP_EOL 
              . 'TYPO3 Caddy<br />' . PHP_EOL 
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
    $this->tmpl = $this->template->main( );

    $fileRessource  = $this->cObj->fileResource($this->conf['main.']['template'] );
    
    $marker                   = '###CADDYMINI###';
    $this->tmpl['caddymini']  = $this->cObj->getSubpart( $fileRessource, $marker );
    
    $marker               = '###ITEMS###';
    $this->tmpl['items']  = $this->cObj->getSubpart( $this->tmpl['caddymini'], $marker );

    $marker                       = '###CADDYMINIEMPTY###';
    $this->tmpl['caddyminiempty'] = $this->cObj->getSubpart( $fileRessource, $marker );
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



  /***********************************************
  *
  * Caddy
  *
  **********************************************/

 /**
  * zz_numberOfItems( ) : 
  *
  * @return     string      $content  : mini caddy in case of products
  * @version  2.0.2
  * @since    2.0.2
  */	
  public function zz_numberOfItems( ) 
  {
    $numberOfItems = 0;
    
    foreach( ( array ) $this->products as $product )
    {
      $numberOfItems  = $numberOfItems
                      + $product['qty']
                      ;
    }

    return $numberOfItems;
  }
}