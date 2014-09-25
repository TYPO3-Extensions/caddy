

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


TYPO3 Foundation
""""""""""""""""

- Install and enable the extension TYPO3 Foundation (t3foundation)

- You don't have to configure nothing but to include static templates
  (see below).


Caddy
"""""

You have to include static templates (TypoScript) only.


Sample page tree
~~~~~~~~~~~~~~~~

- TYPO3
  
  - Quick Shop [1] <- or your Shop
    
    - Caddy [2]


Page with the Shop
~~~~~~~~~~~~~~~~~~

This sample is based on Quick Shop.

If you are using your own Shop, take the include-static-templates of
your own shop.

Include static (from extensions):

- CSS Styled Content (css\_styled\_content)

- Browser (browser)

- Foundation [1] 4x (t3foundation)

- Quick Shop – Basis Template (base\_quickshop) <- only, if you are
  using the Quick Shop Template

- Quick Shop [1] (quick\_shop)

- Caddy [1] (caddy)

- Caddy [1] + foundation 4.x (caddy)

- Caddy [2] + German (caddy) <- only, if you are using Caddy in German
  language

- Caddy [4] CSS foundation (caddy)

- Quick Shop [2] Caddy (quick\_shop)


Page with Caddy
~~~~~~~~~~~~~~~

Include static (from extensions):

- main Template (powermail)

- Foundation [2] 4x + Powermail 2.x (t3foundation)

- Caddy [5] Powermail 2.x Main (caddy)

