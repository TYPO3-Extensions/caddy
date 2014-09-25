

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


TCPDF-Error
^^^^^^^^^^^


Prompt
""""""

TCPDF ERROR: This document (fileadmin/files/my.pdf) probably uses a
compression technique which is not supported by the free parser
shipped with FPDI.


Reason and Solution
"""""""""""""""""""

Probably the version of your PDF file is the reason: Version must be
lower than 1.5.

