

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


MAIN
^^^^

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
         main.pid
   
   Type
         text
   
   Description
         Uid of storage folder: Uid of the folder with the caddy records.
         
         Leave it empty (recommended!), to store records at the same page like
         the caddy plugin.
   
   Default


.. container:: table-row

   Property
         main.dueDate
   
   Type
         options
   
   Description
         Due date for payment.
         
         Options
         
         - now
         
         - +1day
         
         - +2 days
         
         - +3 days
         
         - +4 days
         
         - +5 days
         
         - +6 days
         
         - +1 week
         
         - +2 weeks
         
         - +3 weeks
         
         - +1 month
   
   Default
         +2 weeks


.. container:: table-row

   Property
         main.dueDateFormat
   
   Type
         string
   
   Description
         Format of the due date.
         
         **Examples:**
         
         - %Y-%m-%d
         
         - %d. %b. %Y
   
   Default
         %Y-%m-%d


.. ###### END~OF~TABLE ######


MAIN NUMBERFORMAT
"""""""""""""""""

For German rules there isn't any need to use the Constant Editor. See

- "Static Templates>Error: Reference source not found" on pageError:
  Reference source not foundError: Reference source not found.

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
         main.decimal
   
   Type
         text
   
   Description
         Decimal. This setting will be used for formatting prices.
   
   Default
         2


.. container:: table-row

   Property
         main.dec\_point
   
   Type
         text
   
   Description
         Decimal symbol: Dot or comma. This setting will be used for formatting
         prices.
   
   Default
         .


.. container:: table-row

   Property
         main.thousands\_sep
   
   Type
         text
   
   Description
         Thousands separator. Dot or comma. This setting will be used for
         formatting prices.
   
   Default
         ,


.. container:: table-row

   Property
         main.currencySymbol
   
   Type
         text
   
   Description
         Currency symbol. Example: &euro, $
   
   Default
         $


.. container:: table-row

   Property
         main.currencySymbolBeforePrice
   
   Type
         boolean
   
   Description
         Show currency symbol before price.
   
   Default
         true


.. container:: table-row

   Property
         main.percentSymbol
   
   Type
         string
   
   Description
         Percent symbol
   
   Default
         %


.. ###### END~OF~TABLE ######

