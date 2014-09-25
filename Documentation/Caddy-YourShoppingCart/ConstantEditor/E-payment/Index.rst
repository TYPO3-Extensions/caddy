

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


E-PAYMENT
^^^^^^^^^

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         e-payment.provider
   
   Type
         string
   
   Description
         E-payment provider. Currently Paymill is possible only.
   
   Default


.. container:: table-row

   Property
         e-payment.currency
   
   Type
         string
   
   Description
         I.e: EUR, USD. Currency has an effect for transactions! Currency have
         to accord with the currency of your Shop (see: CADDY - MAIN
         NUMBERFORMAT).
   
   Default
         EUR


.. ###### END~OF~TABLE ######


E-PAYMENT – PAYMILL
"""""""""""""""""""

You have to include the static template " **Caddy [6] + E-Payment
Paymill (caddy)** "

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         e-payment.paymill.mode
   
   Type
         string
   
   Description
         live
         
         - Transaction are real transactions. Each transaction costs your money.
         
         test
         
         - Transactions hasn't any effect, but Paymill will display each
           transaction on the dashboard. BE AWARE that customers can order items
           without a real payment!
   
   Default
         test


.. container:: table-row

   Property
         e-payment.paymill.key.live.private
   
   Type
         string
   
   Description
         Key (live) - private\*:Your private key for the live mode.
   
   Default


.. container:: table-row

   Property
         e-payment.paymill.key.live.public
   
   Type
         string
   
   Description
         Key (live) - public\*:Your publickey for the live mode.
   
   Default


.. container:: table-row

   Property
         e-payment.paymill.key.test.private
   
   Type
         string
   
   Description
         Key (live) - private\*:Your private key for the test mode.
   
   Default


.. container:: table-row

   Property
         e-payment.paymill.key.test.public
   
   Type
         string
   
   Description
         Key (live) - public\*:Your publickey for the test mode.
   
   Default


.. ###### END~OF~TABLE ######


E-PAYMENT – PAYMILL FILES
"""""""""""""""""""""""""

You have to include the static template " **Caddy [6] + E-Payment
Paymill (caddy)** "

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         e-payment.paymill.files.\*
   
   Type
         string
   
   Description
         NeededPaymill files
   
   Default


.. ###### END~OF~TABLE ######


E-PAYMENT – PAYMILL PATHS
"""""""""""""""""""""""""

You have to include the static template " **Caddy [6] + E-Payment
Paymill (caddy)** "

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         e-payment.paymill.paths.api
   
   Type
         string
   
   Description
         API:Path to the APIDefault: res/e-payment/paymill/api/php/
   
   Default
         see description


.. ###### END~OF~TABLE ######


E-PAYMENT – POWERMAIL FILES
"""""""""""""""""""""""""""

You have to include the static template "Caddy [6] + E-Payment Paymill
(caddy)"

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         e-payment.powermail.files.html.error
   
   Type
         string
   
   Description
         Error template:Path to the html error templateDefault:
         EXT:caddy/res/e-payment/powermail/foundation/4x/html/error.html
   
   Default
         see description


.. ###### END~OF~TABLE ######


E-PAYMENT – POWERMAIL PATHS
"""""""""""""""""""""""""""

You have to include the static template "Caddy [6] + E-Payment Paymill
(caddy)"

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         Property
   
   Type
         Type
   
   Description
         Description
   
   Default
         Default


.. container:: table-row

   Property
         e-payment.powermail.paths.foundation
   
   Type
         string
   
   Description
         Path to foundationtypo3conf/ext/t3foundation/res/foundation-4.3.2/
   
   Default
         see description


.. ###### END~OF~TABLE ######

