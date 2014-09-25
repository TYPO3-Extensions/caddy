

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


Setup
^^^^^


Include the e-payment template
""""""""""""""""""""""""""""""

Please add the e-payment template to the TypoScript of your page

Include static (from extensions):

- main Template (powermail)

- Foundation [2] 4x + Powermail 2.x (t3foundation)

- Caddy [5] Powermail 2.x Main (caddy)

- Caddy [6] + E-Payment Paymill (caddy)


Configure Paymill
"""""""""""""""""

After including the e-payment template, you have a lot of new options
for configuration. See

- TypoScript Constant Editor > Category: [CADDY – E-PAYMENT – PAYMILL\*]

Please add your private and public keys.


Test- and Live-Mode
~~~~~~~~~~~~~~~~~~~

Paymill is running in test mode by default. You can test transactions
without any real payment.

If you like to switch to the live mode, please configure

- TypoScript Constant Editor > Category: [CADDY – E-PAYMENT – PAYMILL\*]
  > Mode

See screenshots with test- and live-mode at "Screenshots" on page 18
above.


Constant Editor
"""""""""""""""

See "E-PAYMENT – PAYMILL" on page 34 below.

