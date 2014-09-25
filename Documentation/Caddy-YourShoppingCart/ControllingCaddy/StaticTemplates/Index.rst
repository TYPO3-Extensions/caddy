

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


Static Templates
^^^^^^^^^^^^^^^^


Syntax – Order of the Templates
"""""""""""""""""""""""""""""""

The labels of the static templates have order numbers.

For example: you have to include Caddy (2+) before Caddy (4+).


Main (obligated)
""""""""""""""""

If you like to use Caddy, you have to include the main template. This
is obligate!

- Caddy[1] <- obligate!

- Caddy [1] + Foundation 4.x <- in case of an responsive design


TypoScript
~~~~~~~~~~

- "Setup" from page57below.


Localization
""""""""""""

Some formats are localized like anamount.

- 1,234.56 is the default notation of an amount.

If you like a German format like 1.234,56, please include this static
template:

- Caddy [2] + German


CSS
"""

CSStemplates are needed, if you like to use the CSS of the Caddy
extension. `:sup:`1`  <#sdfootnote12sym>`_

CSS in general (blue):

- Caddy [3] CSS (blue) `:sup:`2`  <#sdfootnote13sym>`_

Colours

- Caddy [3.1] + CSS orange

- Caddy [3.1] + CSS green

- Caddy [3.1] + CSS red

Foundation

- Caddy [4] CSS Foundation 4.x

CSS for Powermail 2.x `:sup:`3`  <#sdfootnote14sym>`_

- Caddy [5.1] + Powermail 2.x CSS fancy


Example
~~~~~~~

- "Colours" on page43below.


TypoScript
~~~~~~~~~~

- "tx\_caddy\_pi1.\_CSS\_DEFAULT\_STYLE" on page66below.


Powermail (obligated)
"""""""""""""""""""""

One of the both Powermailtemplates is obligate. The proper template
depends on your Powermail version.

- Caddy [5] Powermail 1.x

- Caddy [5] Powermail 2.x Main


TypoScript
~~~~~~~~~~

- "tx\_caddy\_pi1.powermail-" on page 67 below.


E-Payment
"""""""""

If you like to use the e-payment interface with the e-payment provider
Paymill, please include this static template:

- Caddy [6] + E-Payment Paymill


Reset
"""""

If you like to reset all Caddy options, please include this static
template at the top position:

- Caddy [99] Reset

