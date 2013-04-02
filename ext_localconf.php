<?php

if( ! defined ( 'TYPO3_MODE' ) )
{
  die ( 'Access denied.' );
}

$cached = false;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_caddy_pi1.php', '_pi1', 'list_type', $cached );

$cached = false;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi3/class.tx_caddy_pi3.php', '_pi3', 'list_type', $cached );

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['tx_caddy_evalprice'] = 'EXT:caddy/pi2/class.tx_caddy_evalprice.php';

?>