<?php

if( ! defined ( 'TYPO3_MODE' ) )
{
  die ( 'Access denied.' );
}

  // 0: non cached
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_caddy_pi1.php', '_pi1', 'list_type', 0 );
  // 1: cached
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi2/class.tx_caddy_pi2.php', '_pi2', 'list_type', 1 );
  // 1: cached
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi3/class.tx_caddy_pi3.php', '_pi3', 'list_type', 1 );

# Hook: clear powermail output if session is not filled
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MainContentHookAfter'][]  = 'EXT:caddy/lib/class.tx_caddy_powermail.php:tx_caddy_powermail';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitEmailHook'][]       = 'EXT:caddy/lib/class.tx_caddy_powermail.php:tx_caddy_powermail';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitEmailHook2'][]      = 'EXT:caddy/lib/class.tx_caddy_powermail.php:tx_caddy_powermail';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MandatoryHookBefore'][]   = 'EXT:caddy/lib/class.tx_caddy_powermail.php:tx_caddy_powermail';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MandatoryHook'][]         = 'EXT:caddy/lib/class.tx_caddy_powermail.php:tx_caddy_powermail';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitLastOne'][]         = 'EXT:caddy/lib/class.tx_caddy_powermail.php:tx_caddy_powermail';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['tx_caddy_evalprice'] = 'EXT:caddy/pi2/class.tx_caddy_evalprice.php';
?>