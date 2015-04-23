<?php

if ( !defined( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}



////////////////////////////////////////////////////////////////////////////
//
// INDEX
// Set TYPO3 version
// Set powermail version
// Configuration by the extension manager
//    Database read only
//    Localization support
// Enables the Include Static Templates
// Add pagetree icons
// Methods for backend workflows
// Plugin Configuration
// TCA for tables
// Allow tables on pages
////////////////////////////////////////////////////////////////////////////
//
// Set TYPO3 version
// Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)
list( $main, $sub, $bugfix ) = explode( '.', TYPO3_version );
$version = ( ( int ) $main ) * 1000000;
$version = $version + ( ( int ) $sub ) * 1000;
$version = $version + ( ( int ) $bugfix ) * 1;
$typo3Version = $version;
// Set TYPO3 version as integer (sample: 4.7.7 -> 4007007)

if ( $typo3Version < 4005000 )
{
  $prompt = '<h1>ERROR</h1>
    <h2>Unproper TYPO3 version</h2>
    <ul>
      <li>
        TYPO3 version is smaller than 4.5.0
      </li>
      <li>
        constant TYPO3_version: ' . TYPO3_version . '
      </li>
      <li>
        integer $this->typo3Version: ' . ( int ) $this->typo3Version . '
      </li>
    </ul>
      ';
  die( $prompt );
}
// Set TYPO3 version
////////////////////////////////////////////////////////////////////////////
//
// Set powermail version

$path2lib = t3lib_extMgm::extPath( 'caddy' ) . 'Resources/Private/Lib/';
require_once( $path2lib . 'userfunc/class.tx_caddy_userfunc.php' );
$userfunc = t3lib_div::makeInstance( 'tx_caddy_userfunc' );

$arrResult = $userfunc->extMgmVersion( 'powermail' );
$versionInt = $arrResult[ 'int' ];
//$versionStr = $arrResult['str'];
switch ( true )
{
  case( $versionInt < 1000000 ):
    $prompt = 'ERROR: unexpected result<br />
      powermail version is below 1.0.0: ' . $versionInt . '<br />
      ext_tables.php method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
      TYPO3 extension: ' . $_EXTKEY;
    // 130720, dwildt, 1-: die( ) blocks the whole TYPO3 system
    //die( $prompt );
    // 130720, dwildt, 1+
    $pmVers = '0x';
    break;
  case( $versionInt < 2000000 ):
    $pmVers = '1x';
    break;
  case( $versionInt < 3000000 ):
    $pmVers = '2x';
    break;
  case( $versionInt >= 3000000 ):
  default:
    $prompt = 'ERROR: unexpected result<br />
      powermail version is 3.x: ' . $versionInt . '<br />
      ext_tables.php method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
      TYPO3 extension: ' . $_EXTKEY;
    // 130720, dwildt, 1-: die( ) blocks the whole TYPO3 system
    //die( $prompt );
    // 130720, dwildt, 1+
    $pmVers = '3x';
    break;
}
// Set powermail version
////////////////////////////////////////////////////////////////////////////
//
// Configuration by the extension manager

$confArr = unserialize( $GLOBALS[ 'TYPO3_CONF_VARS' ][ 'EXT' ][ 'extConf' ][ 'caddy' ] );

// Language for labels of static templates and page tsConfig
$beLanguage = $confArr[ 'beLanguage' ];
switch ( $beLanguage )
{
  case( 'German'):
    $beLanguage = 'de';
    break;
  default:
    $beLanguage = 'default';
}
// Language for labels of static templates and page tsConfig
// Configuration by the extension manager
////////////////////////////////////////////////////////////////////////////
//
// Enables the Include Static Templates
// Case $beLanguage
$path2Ts = 'Configuration/TypoScript/';
switch ( true )
{
  case( $beLanguage == 'de' ):
    // German
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Basis/', 'Caddy [1] Basis' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/', 'Caddy [2] + CSS (blau)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/orange/', 'Caddy [2.1] + orange' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/green/', 'Caddy [2.1] + gruen' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/red/', 'Caddy [2.1] + rot' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/', 'Caddy [3] + Foundation' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/4x/', 'Caddy [3] + Foundation 4.x (veraltet!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/Css/', 'Caddy [3.1] + Foundation CSS' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Ffoundation/4x/Css/', 'Caddy [3.1] + Foundation 4.x CSS (veraltet!' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/Mini/Dropdown/', 'Caddy [3.2] + Foundation Minicaddy Dropdown' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/Mini/Reveal/', 'Caddy [3.2] + Foundation Minicaddy Reveal' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/2x/', 'Caddy [5] Powermail 2.x' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/1x/', 'Caddy [5] Powermail 1.x (veraltet!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/2x/css/', 'Caddy [5.1] + Powermail 2.x CSS fancy' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/2x/Foundation_5x/', 'Caddy [6] + Powermail 2.x Foundation' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Properties/de/', 'Caddy [7] + Sprache: Deutsch' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'EPayment/paymill/5x/', 'Caddy [8] + E-Payment: Paymill Foundation' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'EPayment/paymill/4x/', 'Caddy [8] + E-Payment: Paymill Foundation 4.x' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'JQuery/', 'Caddy [90] + v4.x jQuery (nur Dev!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Reset/', 'Caddy [99] Reset' );
    break;
  default:
    // English
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Basis/', 'Caddy [1] Basis' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/', 'Caddy [2] + CSS (blue)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/orange/', 'Caddy [2.1] + orange' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/green/', 'Caddy [2.1] + green' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Css/red/', 'Caddy [2.1] + red' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/', 'Caddy [3] + Foundation' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/4x/', 'Caddy [3] + Foundation 4.x (deprecated!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/Css/', 'Caddy [3.1] + Foundation CSS' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/4x/Css/', 'Caddy [3.1] + Foundation 4.x CSS (deprecated!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/Mini/Dropdown/', 'Caddy [3.2] + Foundation Minicaddy Dropdown' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Foundation/5x/Mini/Reveal/', 'Caddy [3.2] + Foundation Minicaddy Reveal' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/2x/', 'Caddy [5] Powermail 2.x' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/1x/', 'Caddy [5] Powermail 1.x (deprecated!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/2x/css/', 'Caddy [5.1] + Powermail 2.x CSS fancy' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Powermail/2x/Foundation_5x/', 'Caddy [6] + Powermail 2.x Foundation' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Properties/de/', 'Caddy [7] + Language: German' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'EPayment/paymill/5x/', 'Caddy [8] + E-Payment: Paymill Foundation' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'EPayment/paymill/4x/', 'Caddy [8] + E-Payment: Paymill Foundation 4.x' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'JQuery/', 'Caddy [90] + v4.x jQuery (dev only!)' );
    t3lib_extMgm::addStaticFile( $_EXTKEY, $path2Ts . 'Reset/', 'Caddy [99] Reset' );
    break;
}
// Case $beLanguage
// Enables the Include Static Templates
////////////////////////////////////////////////////////////////////////////
//
// Add pagetree icons
// Case $beLanguage
switch ( true )
{
  case( $beLanguage == 'de' ):
    // German
    $TCA[ 'pages' ][ 'columns' ][ 'module' ][ 'config' ][ 'items' ][] = array( 'Caddy', 'caddy', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
    $TCA[ 'pages' ][ 'columns' ][ 'module' ][ 'config' ][ 'items' ][] = array( 'Caddy Mini', 'caddymini', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
    break;
  default:
    // English
    $TCA[ 'pages' ][ 'columns' ][ 'module' ][ 'config' ][ 'items' ][] = array( 'Caddy', 'caddy', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
    $TCA[ 'pages' ][ 'columns' ][ 'module' ][ 'config' ][ 'items' ][] = array( 'Caddy mini', 'caddymini', t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif' );
}
// Case $beLanguage

t3lib_SpriteManager::addTcaTypeIcon( 'pages', 'contains-caddy', '../typo3conf/ext/caddy/ext_icon.gif' );
t3lib_SpriteManager::addTcaTypeIcon( 'pages', 'contains-caddymini', '../typo3conf/ext/caddy/ext_icon.gif' );
// Add pagetree icons
///////////////////////////////////////////////////////////
//
// Methods for backend workflows
//require_once(t3lib_extMgm::extPath($_EXTKEY).'Resources/Private/Lib/flexform/class.tx_caddy_flexform.php');
require_once(t3lib_extMgm::extPath( $_EXTKEY ) . 'Resources/Private/Lib/userfunc/class.tx_caddy_userfunc.php');
// Methods for backend workflows
////////////////////////////////////////////////////////////////////////////
//
// Plugin Configuration

t3lib_div::loadTCA( 'tt_content' );

$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $_EXTKEY . '_pi1' ] = 'layout,select_key,recursive,pages';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $_EXTKEY . '_pi1' ] = 'pi_flexform';
t3lib_extMgm::addPlugin( array(
  'LLL:EXT:caddy/locallang_db.xml:tt_content.list_type_pi1',
  $_EXTKEY . '_pi1',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
        ), 'list_type' );
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/pi1/flexform' . $pmVers . '.xml' );

$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $_EXTKEY . '_pi2' ] = 'layout,select_key,recursive,pages';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $_EXTKEY . '_pi2' ] = 'pi_flexform';
t3lib_extMgm::addPlugin( array(
  'LLL:EXT:caddy/locallang_db.xml:tt_content.list_type_pi2',
  $_EXTKEY . '_pi2',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'Resources/Public/Images/caddy_000_13.png'
        ), 'list_type' );
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi2', 'FILE:EXT:' . $_EXTKEY . '/pi2/flexform.xml' );

$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_excludelist' ][ $_EXTKEY . '_pi3' ] = 'layout,select_key,recursive,pages';
$TCA[ 'tt_content' ][ 'types' ][ 'list' ][ 'subtypes_addlist' ][ $_EXTKEY . '_pi3' ] = 'pi_flexform';
t3lib_extMgm::addPlugin( array(
  'LLL:EXT:caddy/locallang_db.xml:tt_content.list_type_pi3',
  $_EXTKEY . '_pi3',
  t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif'
        ), 'list_type' );
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi3', 'FILE:EXT:' . $_EXTKEY . '/pi3/flexform.xml' );
// Plugin Configuration
////////////////////////////////////
//
// TCA for tables
// Orders
$TCA[ 'tx_caddy_order' ] = array(
  'ctrl' => array(
    'title' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_order',
    'label' => 'numberOrder',
    'label_alt' => 'numberDeliveryorder,numberInvoice,customerEmail',
    'label_alt_force' => true,
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
    'delete' => 'deleted',
    'default_sortby' => 'ORDER BY numberOrder DESC, tstamp DESC',
    'readOnly' => $confArr[ 'databaseReadonly' ],
    'hideAtCopy' => true,
    'dividers2tabs' => true,
    'dynamicConfigFile' => t3lib_extMgm::extPath( $_EXTKEY ) . 'tca.php',
    'iconfile' => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif',
    'searchFields' => 'customerEmail,' .
    'numberDeliveryorder,numberInvoice,numberOrder,' .
    'sumGross,sumNet,sumTaxReduced,sumTaxNormal,'
  ),
);
// Orders
// Items
$TCA[ 'tx_caddy_item' ] = array(
  'ctrl' => array(
    'title' => 'LLL:EXT:caddy/locallang_db.xml:tx_caddy_item',
    'label' => 'uid',
    'label_alt' => 'title',
    'label_alt_force' => true,
    'tstamp' => 'tstamp',
    'crdate' => 'crdate',
    'cruser_id' => 'cruser_id',
    'delete' => 'deleted',
    'default_sortby' => 'ORDER BY uid DESC',
    'readOnly' => $confArr[ 'databaseReadonly' ],
    'dividers2tabs' => true,
    'dynamicConfigFile' => t3lib_extMgm::extPath( $_EXTKEY ) . 'tca.php',
    'iconfile' => t3lib_extMgm::extRelPath( $_EXTKEY ) . 'ext_icon.gif',
    'searchFields' => 'title'
  ),
);
// Items
// TCA for tables
////////////////////////////////////
//
// Allow tables on pages

t3lib_extMgm::allowTableOnStandardPages( 'tx_caddy_item ' );
t3lib_extMgm::allowTableOnStandardPages( 'tx_caddy_order ' );
t3lib_extMgm::addToInsertRecords( 'tx_caddy_item ' );
t3lib_extMgm::addToInsertRecords( 'tx_caddy_order ' );
// Allow tables on pages
?>