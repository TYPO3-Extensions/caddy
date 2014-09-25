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


Plugin / Flexform
^^^^^^^^^^^^^^^^^


Check it!
"""""""""

|img-32|

Enable the Check-it!-Report

- If the report is enabled, Caddy will check your Cady configuration and
  your Powermail configuration.

- If there is an unproper property, you will get a prompt probably with
  helpful information.

DRS: You will get a report in the Developer Log

- If the DRS is enabled, Caddy will prompt the work flow of the plugin
  to devlog.The DRS supports TYPO3 integrators for a proper
  configuration and for bugfixing.The DRS supports TYPO3 developers.

- You need the extension devlog, if you want to use this feature.

Update Wizard

- Without any effect in the current version.


Notes
"""""

|img-33|

Notes

- For your internal notes


Caddy
"""""

|img-34|

The minimum order quantity

- Minimum order quantity over all (for all items in the caddy)

The maximum order quantity

- Maximum order quantity over all (for all items in the caddy)

Increase order number with

- Caddy starts counting from 0. If you need a higher start number like
  1.000, please adapt the starting number to your needs.

Increase invoice number with

- See above.

Increase delivery order number with

- See above.


Reset numbers
~~~~~~~~~~~~~

If there is a need, to reset the counter of the numbers, please refer
to

- "Reset numbers" on page56below.


Email
"""""

|img-35|

Customer E-mail (field from powermail)

- Caddy must know the email from the customer.

- You have to link this field with the corresponding field of the
  powermail form.

PDF Terms & Conditions

- Attach the PDF to
  
  - nobody,
  
  - the customer,
  
  - the vendor or
  
  - both

PDF: Power of Revocation

- See above

PDF Invoice

- See above

PDF Delivery Order

- See above


Design of the attachments
~~~~~~~~~~~~~~~~~~~~~~~~~

See

- "Plugin / Flexform > PDF" on page 29 below

- "Corporate Design > Attachments" on page 46 below


Billing Address
"""""""""""""""

|img-36|

This is the Powermail connector.

Caddy needs this values for the billing address in PDF attachments.

Each field should linked with the corresponding field of the powermail
form.

It is recommended, to have all Powermail fields configured. And to
hide a field or fieldset, if you don't need the field or fieldset.

It isn't recommended, to left needed Powermail fields, because Caddy
will prompt a warning in the Check-it!-Report.


Delivery Address
""""""""""""""""

|img-37|

See "Billing Address" above.


PDF
"""

|img-38|

Background PDF for your attachments.

Please upload a PDF with your corporate design to the fileadmin
directory.

Update the values in the fields of this tab.


Further informations about attachments
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

See

- "Plugin / Flexform > Email" on page 27 above

- "Corporate Design > Attachments" on page 46 below


|img-39| Sponsors
"""""""""""""""""

|img-40|

List of sponsors.

Nothing to do but sponsoring. See

- "Packages for Sponsors" on page80below.


Help!
"""""

|img-41|

Helpful links to

- the manual, the caddy forum and some other caddy websites,

- the one-click-installer and

- the developer.

Nothing to do.

