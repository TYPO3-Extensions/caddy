<?php

if( ! defined ( 'TYPO3_MODE' ) )
{
  die ( 'Access denied.' );
}

$cached = false;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_caddy_pi1.php', '_pi1', 'list_type', $cached );

$cached = false;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi3/class.tx_caddy_pi2.php', '_pi2', 'list_type', $cached );

$cached = false;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi3/class.tx_caddy_pi3.php', '_pi3', 'list_type', $cached );

?>