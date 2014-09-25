

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


DATABASE
^^^^^^^^

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
         db.table
   
   Type
         text
   
   Description
         Table\*: \*Obligate! Name of the table with the items (products).
         
         **Examples:**
         
         - tx\_myextension\_products
         
         - tx\_quickshop\_products
         
         - tx\_cars\_model
   
   Default


.. container:: table-row

   Property
         db.title
   
   Type
         text
   
   Description
         Title\*: \*Obligate! Field with the title of the item (product).
   
   Default
         title


.. container:: table-row

   Property
         db.price
   
   Type
         text
   
   Description
         Price\*: \*Obligate! Field with the gross price of the item (product).
   
   Default
         price


.. container:: table-row

   Property
         db.tax
   
   Type
         text
   
   Description
         Tax\*: \*Obligate! Field with the tax category of the item (product).
   
   Default
         tax


.. container:: table-row

   Property
         db.sku
   
   Type
         text
   
   Description
         SKU: field with the sku (unique id) of the item (product).
   
   Default


.. container:: table-row

   Property
         db.min
   
   Type
         text
   
   Description
         MIN: field with the minimum order quantity of the item (product).
   
   Default


.. container:: table-row

   Property
         db.max
   
   Type
         text
   
   Description
         MAX: field with the minimum order quantity of the item (product).
   
   Default


.. container:: table-row

   Property
         db.service\_attribute\_1
   
   Type
         text
   
   Description
         Service Attribute 1: the field with the service attribute 1 of the
         item (product).
   
   Default


.. container:: table-row

   Property
         db.service\_attribute\_2
   
   Type
         text
   
   Description
         Service Attribute 2: the field with the service attribute 1 of the
         item (product).
   
   Default


.. container:: table-row

   Property
         db.service\_attribute\_3
   
   Type
         text
   
   Description
         Service Attribute 3: the field with the service attribute 1 of the
         item (product).
   
   Default


.. ###### END~OF~TABLE ######

