<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Caddy - responsive Shopping Cart',
	'description' => ''
  . 'http://typo3-caddy.de. '
  . 'Caddy is the TYPO3 responsive Shopping Cart. '
  . 'It is optimised for desktops, tablets and smartphones. '
  . 'Caddy is e-commerce for small budget and small-scale enterprises. '
  . 'You are welcome to install Caddy with one mouse-click - ready-to-use. See: TYPO3 Quick Shop. '
  . 'Caddy provides ready-to-use templates for both responsive frameworks: bootstrap and foundation. '
  . 'You can use caddy for your own needs with your own database and without Quick Shop. '
  . 'Manual at http://typo3-caddy.de/typo3conf/ext/caddy/doc/manual.pdf. '
  ,
	'category' => 'plugin',
	'shy' => 0,
	'version' => '6.3.2',
	'dependencies' => 'powermail',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Dirk Wildt (Die Netzmacher)',
	'author_email' => 'http://wildt.at.die-netzmacher.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'powermail' => '',
			't3_tcpdf' => '',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);
