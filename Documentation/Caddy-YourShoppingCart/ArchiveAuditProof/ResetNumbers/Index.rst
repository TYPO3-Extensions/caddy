

.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. ==================================================
.. DEFINE SOME TEXTROLES
.. --------------------------------------------------
.. role::   underline
.. role::   typoscript(code)
.. role::   ts(typoscript)
   :class:  typoscript
.. role::   php(code)


Reset numbers
^^^^^^^^^^^^^

Caddy is storing the numbers in the registry of TYPO3 – table
sys\_registry.

If you like to reset the numbers, you need a SQL command like in the
examples below.


Get all keys
""""""""""""

SELECT \*

FROM sys\_registry

WHERE entry\_namespace = 'tx\_caddy'

The result will look like this:

"uid","entry\_namespace","entry\_key","entry\_value"

"561","tx\_caddy","lastOrder\_1279","i:1;"

"553","tx\_caddy","lastOrder\_15","i:4;"

"563","tx\_caddy","lastOrder\_7567","i:4;"

...

"626","tx\_caddy","page\_7843\_deliveryorder","i:1;"

"627","tx\_caddy","page\_7843\_invoice","i:1;"

"628","tx\_caddy","page\_7843\_order","i:1;"


Syntax of the entry\_key
~~~~~~~~~~~~~~~~~~~~~~~~

The entry\_key has the format:

- pageUid\_KindOfNumber

where

- pageUid is
  
  - the uid of the page, which contains the Caddy plugin
  
  - format is: page\_uid

- KindOfNumber is
  
  - deliveryorder
  
  - invoice
  
  - order


Reset (remove the keys)
"""""""""""""""""""""""


All keys
~~~~~~~~

DELETE

FROM sys\_registry

WHERE entry\_namespace = 'tx\_caddy'


Keys of a single Caddy plugin
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

DELETE

FROM sys\_registry

WHERE entry\_namespace = 'tx\_caddy'

AND entry\_key LIKE 'page\_7567\_%'

where 7567 is the page uid of your Caddy plugin

