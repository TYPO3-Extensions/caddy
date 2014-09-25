

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


Options
^^^^^^^


OPTIONS PAYMENT
"""""""""""""""

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
         options.payment.default
   
   Type
         integer
   
   Description
         Default payment option:0: no preset. 1: 1st payment option. 2: 2nd
         payment option. 3rd payment option.
   
   Default
         0


.. container:: table-row

   Property
         options.payment.1.lll
   
   Type
         string
   
   Description
         1. label:Label for 1st payment option. Example: cash in advance
         (German: Vorkasse)
         
         Default: EXT:caddy/pi1/locallang.xml:paymentoption\_cashinadvance
   
   Default
         see description


.. container:: table-row

   Property
         options.payment.1.enabled
   
   Type
         boolean
   
   Description
         1. enabled:Disable it, if you don't want to offer the 1st payment
         option.
   
   Default
         1


.. container:: table-row

   Property
         options.payment.1.costs
   
   Type
         double
   
   Description
         1. costs:Extra costs for 1st payment option. Example: 9.99
   
   Default
         0.00


.. container:: table-row

   Property
         options.payment.1.taxrate
   
   Type
         string
   
   Description
         1. tax rate:Tax rate for extra costs for 1st payment option.Values
         are: reduced, normal
   
   Default
         normal


.. container:: table-row

   Property
         options.payment.[2-3]
   
   Type
   
   
   Description
         Same options for options payment 2 to options.payment 3
   
   Default


.. ###### END~OF~TABLE ######


OPTIONS SHIPPING
""""""""""""""""

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
         options.shipping.default
   
   Type
         integer
   
   Description
         Default shipping option:0: no preset. 1: mail. 2: mail express.
   
   Default
         0


.. container:: table-row

   Property
         TODOoptions.shipping.1.lll
   
   Type
         TODOstring
   
   Description
         TODO1. label:Label for 1st shipping option. Example: cash in advance
         (German: Vorkasse)
         
         Default: EXT:caddy/pi1/locallang.xml:shippingoption\_cashinadvance
   
   Default
         see description


.. container:: table-row

   Property
         options.shipping.1.enabled
   
   Type
         boolean
   
   Description
         1. enabled:Disable it, if you don't want to offer the 1st shipping
         option.
   
   Default
         1


.. container:: table-row

   Property
         options.shipping.1.costs
   
   Type
         double
   
   Description
         1. costs:Extra costs for 1st shipping option. Example: 9.99
   
   Default
         0.00


.. container:: table-row

   Property
         options.shipping.1.taxrate
   
   Type
         string
   
   Description
         1. tax rate:Tax rate for extra costs for 1st shipping option.Values
         are: reduced, normal
   
   Default
         normal


.. container:: table-row

   Property
         options.shipping.[2]
   
   Type
   
   
   Description
         Same options for option shipping 2
   
   Default


.. ###### END~OF~TABLE ######


OPTIONS SPECIALS
""""""""""""""""

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
         options.specials.default
   
   Type
         integer
   
   Description
         Default specials option:0: no preset. 1: neutral package. 2: fee for
         islands (German: Versandkosten Deutsche Inseln).
   
   Default
         0


.. container:: table-row

   Property
         TODOoptions.specials.1.lll
   
   Type
         TODOstring
   
   Description
         TODO1. label:Label for 1st specials option. Example: cash in advance
         (German: Vorkasse)
         
         Default: EXT:caddy/pi1/locallang.xml:specialsoption\_cashinadvance
   
   Default
         see description


.. container:: table-row

   Property
         options.specials.1.enabled
   
   Type
         boolean
   
   Description
         1. enabled:Disable it, if you don't want to offer the 1st specials
         option.
   
   Default
         1


.. container:: table-row

   Property
         options.specials.1.costs
   
   Type
         double
   
   Description
         1. costs:Extra costs for 1st specials option. Example: 9.99
   
   Default
         0.00


.. container:: table-row

   Property
         options.specials.1.taxrate
   
   Type
         string
   
   Description
         1. tax rate:Tax rate for extra costs for 1st specials option.Values
         are: reduced, normal
   
   Default
         normal


.. container:: table-row

   Property
         options.specials.[2]
   
   Type
   
   
   Description
         Same options for option specials 2
   
   Default


.. ###### END~OF~TABLE ######

