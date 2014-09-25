.. include:: Images.txt

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


Disable audit proof
^^^^^^^^^^^^^^^^^^^

You candisable the audit proof mode by the extension manager.


Possible reasons
""""""""""""""""

There are some needs for disabling the audit proof:

- You have tested Caddy and you want to delete the logged data.

- Your database is growing an growing and you want to delete records and
  files.


Extension Manager
"""""""""""""""""

|img-55|

#. Module: Admin Tools Extension Manager

#. Edit Area Select the Caddy extension

#. Tab [Configuration]

#. Category: [ARCHIVE AUDIT PROOF]

#. Enabled: [ ]

#. [Update]

