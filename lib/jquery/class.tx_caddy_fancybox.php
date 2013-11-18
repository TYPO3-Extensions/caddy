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
* Class provides methods for the extension manager.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    caddy
* @internal   #53742 
* @version  4.0.1
* @since    4.0.1
*/


  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_caddy_fancybox
 *   67:     function promptCheckUpdate()
 *  102:     function promptCurrIP()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_caddy_fancybox
{
  
 /**
  * Extension key
  *
  * @var string
  */
  private $extKey = 'caddy';

 /**
  * Prefix id
  *
  * @var string
  */
  public $prefixId = 'tx_caddy_fancybox';
 
 /**
  * Path to this script
  *
  * @var string
  */
  public $scriptRelPath = 'lib/jquery/class.tx_caddy_fancybox.php';
  
 /**
  * Current TypoScript configuration
  *
  * @var array
  */
  private $conf;
  
 /**
  * Current TypoScript configuration of fancybox
  *
  * @var array
  */
  private $confFancybox;
  
 /**
  * Extension configuration
  *
  * @var array
  */
  private $arr_extConf;

 /**
  * Parant object
  *
  * @var object
  */
  public $pObj = null;

 /**
  * Current row
  *
  * @var array
  */
  public $row;
  
  
 /**
  * main( ):
  *
  * @return    mixed        HTML output.
  * @access public
  * @version 4.0.1
  * @since 4.0.1
  */
  public function main( )
  {
    unset( $content );
    
      // Init
    if( ! $this->init( ) )
    {
      return;
    }

      // ...
    $content = $this->jquery( );

    return $content;
  }

  
  
 /**
  * init( ):
  *
  * @return    boolean        
  * @access private
  * @version 4.0.1
  * @since 4.0.1
  */
  private function  init( )
  {
      // Current TypoScript configuration
    $this->conf = $this->pObj->conf;
    $this->confFancybox = $this->conf['javascript.']['jquery.']['plugins.']['fancybox.'];

    $success = $this->initRequirements( );
    return $success;
  }

  
  
 /**
  * initRequirements( ):
  *
  * @return    boolean        
  * @access private
  * @version 4.0.1
  * @since 4.0.1
  */
  private function  initRequirements( )
  {
    $success = false;
    
    switch( $this->confFancybox['enabled'] )
    {
      case( 'yes' ):
          // Follow the workflow: include library and css
        $success = true;
        break;
      case( 'no' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'The caddy fancybox should not used.';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        $success = false;
        break;
      default:
        $enabled = $this->confFancybox['enabled'];
        $prompt = '
          <p>
            Undefined value in  plugin.tx_caddy_pi1.javascript.jquery.plugins.fancybox.enabled: "' . $enabled . '"<br />
            Value has to be yes or no.<br />
            Please fix the bug!<br />
            TYPO3 extension Caddy<br />
            Method: ' . __METHOD__ . ' <br />
            Line: ' . __LINE__ . '
          </p>
';
        die( $prompt );
        break;
    }

    return $success;
  }

  
 
 /**
  * jquery( ):
  *
  * @return    string     $content  : HTML content
  * @access   private
  * @version  4.0.1
  * @since    4.0.1
  */
  private function jquery( )
  {
    $this->jquerySource( );   
    $this->jqueryFancybox( );   
    $content = $this->jqueryFancyboxScript( );
    
    return $content;
  }

  
  
 /**
  * jqueryFancybox( ):
  *
  * @return    void
  * @access   private
  * @version  4.0.1
  * @since    4.0.1
  */
  private function jqueryFancybox( )
  {
    $coa_name = $this->confFancybox['includes.']['fancybox'];
    $coa_conf = $this->confFancybox['includes.']['fancybox.'];
    $fancybox = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    
    switch( $fancybox )
    {
      case( 'enabled' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'Fancybox is enabled.';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        $this->jqueryFancyboxCSS( );
        $this->jqueryFancyboxLibrary( );
        break;
      case( 'disabled' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'Fancybox is disabled.';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        break;
      default:
        $prompt = '
          <p>
            Undefined value: $fancybox = ' . $fancybox . '<br />
            Please fix the bug!<br />
            TYPO3 extension Caddy<br />
            Method: ' . __METHOD__ . ' <br />
            Line: ' . __LINE__ . '
          </p>
';
        die( $prompt );
        break;
    }

    return;
  }

  
  
 /**
  * jqueryFancyboxCSS( ):
  *
  * @return    void
  * @access   private
  * @version  4.0.1
  * @since    4.0.1
  */
  private function jqueryFancyboxCSS( )
  {
    $coa_name = $this->confFancybox['includes.']['fancyboxCSS'];
    $coa_conf = $this->confFancybox['includes.']['fancyboxCSS.'];
    $fancyboxCss = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    if( $this->pObj->drs->drsJavascript )
    {
      $prompt = 'Fancybox CSS is included at the top of the page (HTML head).';
      t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
    }
    $GLOBALS['TSFE']->additionalHeaderData['caddy.fancybox.css'] = $fancyboxCss;
    
    return;
  }

  
  
 /**
  * jqueryFancyboxLibrary( ):
  *
  * @return    void
  * @access   private
  * @version  4.0.1
  * @since    4.0.1
  */
  private function jqueryFancyboxLibrary( )
  {
    $coa_name = $this->confFancybox['includes.']['fancyboxPosition'];
    $coa_conf = $this->confFancybox['includes.']['fancyboxPosition.'];
    $fancyboxPosition = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    
    $coa_name = $this->confFancybox['includes.']['fancyboxLibrary'];
    $coa_conf = $this->confFancybox['includes.']['fancyboxLibrary.'];
    $fancyboxLibrary = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    
    switch( $fancyboxPosition )
    {
      case( 'top' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'Fancybox is included at the top of the page (HTML head).';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        $GLOBALS['TSFE']->additionalHeaderData['caddy.fancybox.lib'] = $fancyboxLibrary;
        break;
      case( 'bottom' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'Fancybox is included at the bottom of the page.';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        $GLOBALS['TSFE']->additionalFooterData['caddy.fancybox.lib'] = $fancyboxLibrary;
        break;
      default:
        $prompt = '
          <p>
            Undefined value: fancyboxPosition = ' . fancyboxPosition . '<br />
            Please fix the bug!<br />
            TYPO3 extension Caddy<br />
            Method: ' . __METHOD__ . ' <br />
            Line: ' . __LINE__ . '
          </p>
';
        die( $prompt );
        break;
    }

    return;
  }
  
  
  
 /**
  * jqueryFancyboxScript( ):
  *
  * @return    mixed        HTML output.
  * @access private
  * @version 4.0.1
  * @since 4.0.1
  */
  private function jqueryFancyboxScript( )
  {
      // Parameter from typoscript
    $params = $this->jqueryFancyboxScriptParams( );
      // Variables from typoscript
    $vars   = $this->jqueryFancyboxScriptVars( );
       
    $script = $this->confFancybox['javascript.']['script'];
    $script = str_replace( '%params%', $params, $script );
    $script = str_replace( array_keys( $vars ), $vars, $script );
    
    return $script;    
  }
  
  
  
 /**
  * jqueryFancyboxScriptParams( ):
  *
  * @return    string        list of parameter
  * @access private
  * @version 4.0.1
  * @since 4.0.1
  */
  private function jqueryFancyboxScriptParams( )
  {
    $spaceLeft  = $this->confFancybox['javascript.']['paramsSpaceLeft'];
    $params     = array( );
    $strParams  = null;   
    
      // params
    foreach( array_keys ( ( array ) $this->confFancybox['javascript.']['params.'] ) as $param )
    {
        // CONTINUE : param has an dot
      if( rtrim( $param, '.' ) != $param )
      {
        continue;
      }
        // CONTINUE : param has an dot

      $cObj_name  = $this->confFancybox['javascript.']['params.'][$param];
      $cObj_conf  = $this->confFancybox['javascript.']['params.'][$param . '.'];
      $value      = $this->zz_cObjGetSingle( $cObj_name, $cObj_conf );
      switch( true )
      {
          // Don't process default values
          // See
          //  * http://fancybox.net/api
          //  * constant editor
        case( $param == 'autoDimensions'      && $value == 'true' ):
        case( $param == 'autoScale'           && $value == 'true' ):
        case( $param == 'centerOnScroll'      && $value == 'false' ):
        case( $param == 'cyclic'              && $value == 'false' ):
        case( $param == 'enableEscapeButton'  && $value == 'true' ):
        case( $param == 'height'              && $value == '340px' ):
        case( $param == 'hideOnContentClick'  && $value == 'false' ):
        case( $param == 'hideOnOverlayClick'  && $value == 'true' ):
        case( $param == 'margin'              && $value == 20 ):
        case( $param == 'modal'               && $value == 'false' ):
        case( $param == 'opacity'             && $value == 'false' ):
        case( $param == 'overlayColor'        && $value == "'#666'" ):
        case( $param == 'overlayShow'         && $value == 'true' ):
        case( $param == 'overlayOpacity'      && $value == '0.3' ):
        case( $param == 'padding'             && $value == 10 ):
        case( $param == 'scrolling'           && $value == "'auto'" ):
        case( $param == 'showCloseButton'     && $value == 'true' ):
        case( $param == 'showNavArrows'       && $value == 'true' ):
        case( $param == 'speedIn'             && $value == 300 ):
        case( $param == 'speedOut'            && $value == 300 ):
        case( $param == 'titleShow'           && $value == 'true' ):
        case( $param == 'transitionIn'        && $value == "'fade'" ):
        case( $param == 'transitionOut'       && $value == "'fade'" ):
        case( $param == 'width'               && $value == '560px' ):
          if( $this->pObj->drs->drsJavascript )
          {
            $prompt = 'Fancybox parameter ' . $param . ' = ' . $value . '. This is the default. Parameter won\'t processed.';
            t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
          }
          continue 2;
          break;
            // Don't process empty values
        case( $value === null ):
        case( $value == "''" ):
          if( $this->pObj->drs->drsJavascript )
          {
            $prompt = 'Fancybox parameter ' . $param . ' is empty. Parameter won\'t processed.';
            t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
          }
          continue 2;
          break;
        default:
            // Follow the workflow
          break;
      }
      $params[] = "'" . $param . "' : " . $value;
    }
    $strParams = implode( ',' . PHP_EOL . str_repeat( ' ', $spaceLeft ), ( array ) $params );
      // params
       
    return $strParams;    
  }
  
  
  
 /**
  * jqueryFancyboxScriptVars( ):
  *
  * @return    array        $variables
  * @access private
  * @version 4.0.1
  * @since 4.0.1
  */
  private function jqueryFancyboxScriptVars( )
  {
    $variables  = array( );

      // variables
    foreach( array_keys ( ( array ) $this->confFancybox['javascript.']['variables.'] ) as $variable )
    {
        // CONTINUE : param has an dot
      if( rtrim( $variable, '.' ) != $variable )
      {
        continue;
      }
        // CONTINUE : param has an dot

      $cObj_name  = $this->confFancybox['javascript.']['variables.'][$variable];
      $cObj_conf  = $this->confFancybox['javascript.']['variables.'][$variable . '.'];
      $variables['%' . $variable . '%'] = $this->zz_cObjGetSingle( $cObj_name, $cObj_conf );
    }
      // variables

    return $variables;
  }

  
  
 /**
  * jquerySource( ):
  *
  * @return    void
  * @access   private
  * @version  4.0.1
  * @since    4.0.1
  */
  private function jquerySource( )
  {
    $coa_name = $this->confFancybox['includes.']['source'];
    $coa_conf = $this->confFancybox['includes.']['source.'];
    $source = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    
    switch( $source )
    {
      case( 'enabled' ):
          // Follow the workflow: include library and css
        break;
      case( 'disabled' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'jQuery library should not included.';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        return;
        break;
      default:
        $prompt = '
          <p>
            Undefined value: $source = ' . $source . '<br />
            Please fix the bug!<br />
            TYPO3 extension Caddy<br />
            Method: ' . __METHOD__ . ' <br />
            Line: ' . __LINE__ . '
          </p>
';
        die( $prompt );
        break;
    }

    $coa_name = $this->confFancybox['includes.']['sourcePosition'];
    $coa_conf = $this->confFancybox['includes.']['sourcePosition.'];
    $sourcePosition = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    
    $coa_name = $this->confFancybox['includes.']['sourceLibrary'];
    $coa_conf = $this->confFancybox['includes.']['sourceLibrary.'];
    $sourceLibrary = $this->zz_cObjGetSingle( $coa_name, $coa_conf );
    
    switch( $sourcePosition )
    {
      case( 'top' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'jQuery is included at the top of the page (HTML head).';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        $GLOBALS['TSFE']->additionalHeaderData['caddy.source.lib'] = $sourceLibrary;
        break;
      case( 'bottom' ):
        if( $this->pObj->drs->drsJavascript )
        {
          $prompt = 'jQuery is included at the bottom of the page.';
          t3lib_div::devlog( '[INFO/JAVASCRIPT] ' . $prompt, $this->extKey, 0 );
        }
        $GLOBALS['TSFE']->additionalFooterData['caddy.source.lib'] = $sourceLibrary;
        break;
      default:
        $prompt = '
          <p>
            Undefined value: sourcePosition = ' . sourcePosition . '<br />
            Please fix the bug!<br />
            TYPO3 extension Caddy<br />
            Method: ' . __METHOD__ . ' <br />
            Line: ' . __LINE__ . '
          </p>
';
        die( $prompt );
        break;
    }

    return;
  }

  
  
 /**
  * zz_cObjGetSingle( ):
  *
  * @return    string        $value  : ....
  * @access   private
  * @version  4.0.1
  * @since    4.0.1
  */
  private function zz_cObjGetSingle( $cObj_name, $cObj_conf )
  {
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }
      
    return $value;
  }

  
  
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/jquery/class.tx_caddy_fancybox.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/jquery/class.tx_caddy_fancybox.php']);
}

?>