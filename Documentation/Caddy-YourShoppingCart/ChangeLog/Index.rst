

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


Change Log
----------

4.0.12 **Bugfix** \* #58646: Currency symbol in email is crippled

4.0.11 **Feature** \* #i0055: Tax info in the simple caddy HTML

4.0.10 **Feature** \* #i0054: Bank account in case of cash in advance
for email and pdfBugfix\* #i0053: Fatal error: Class 'Paymill\Request'
not found
inlib/e-payment/paymill/class.tx\_caddy\_paymill\_transaction.php on
line 765\* #i0052: Paymill exception while transaction: '50647.056'
must contain only digits

4.0.9 **Bugfix** \* #i0051: condition is placed outside the label\*
#i0050: Non e-payment-methods are enable only, if an e-payment-method
is enabled before.

4.0.8 **Feature** \* #i0049: Tax is removed from HTML template\*
#i0048: Billing-, service- and shipping costs are displayed only, if
sum is greater than 0.00\* Rule is set for HTML template only\* The
seller and the client need the information for shipping, payment and
specials inthe e-mail template in every case.\* new fields
sumcashdiscountsumnet and sumcashdiscountsumgross among others\*
#i0047: cash discount\* new fields: sumcashdiscountnet,
sumcashdiscountgross\* #i0046: new payment option cash on pick up
(german: Bar bei Abholung)\* #i0045: e-payment\* enhance payment
options from 3 to 6

4.0.7 **Feature** \* #55726: Stock management: decrease item quantity
after ordering\* New db.fields: stockquantity and stockmanagement\*
New api.getpost: stockquantity and stockmanagement\* New
api.marker.item: stockquantity and stockmanagement

4.0.6 **Bugfix** \* #i0044: Adding a product removes the session\*
#i0043: e-payment\* payment id is removed after transaction\* payment
options are missing in e-mail and pdf

4.0.5 **Major Feature** \* #53678: e-payment step 2\* #53742: Fancybox
is removed **Feature** \* #55333: Configurable options\* #54999: Link
to an item (to the single view) **Improvement** \* #55322: Clean up
TypoScript structure\* plugin.tx\_caddy\_pi1\* conditions -> moved to:
api.conditions\* getpost -> moved to: api.getpost\* marker -> moved
to: api.marker\* options -> moved to: api.options\* symbols -> moved
to: api.symbols\* tax -> moved to: api.tax **Bugfixing** \* #i0047:
Unproper form in the extension manager (TYPO3 6.x)

4.0.4 **Features** \* #54968: Powermail improvement\* #54967:
Responsive framework foundation\* #54858: Split the form in different
parts\* #54832: Another label wrap for radiobuttons and checkboxes\*
#i0047: Foundation\* #i0046: Validator

4.0.3 **Features** \* #i0042: ###UID\_POWERMAIL\_FORM###\* #i0040:
jQuery for powermail\* #i0039: Sum for shipping and specials (without
payment)\* #54628: Calculated Caddy available for dynamicMarkers\*
#54634: Enable controlling of the caddy (pi1) by pid **Improvement**
\* #i0038: Caddy HTML **Bugfix** \* #i0045: unproper replace of an
empty pid in session -> getPid( )\* #i0044: Unsupported operand types
in lib/caddy/class.tx\_caddy.php on line 898\* #i0043:
getPaymentOptionLabelBySessionId seem's to be unproper\* #i0041:
###TARGET### wasn't handled

4.0.2 **Major Feature** \* #53678: e-payment step 1 **Features** \*
#i0037: Replace marker by cObjData\* #i0036: Debugging: Don't replace
empty marker **Improvement** \* #i0034: Disable jquery.caddy-3.0.0 /
fancybox\* #i0033: Move version from 4.0.1 to 3.0.1 **Bugfix** \*
#i0039: Caddy included TypoScript template for Caddy 4.x. Proper is
template for Caddy 2.x

4.0.1 **Major Feature** \* #53742: Fancybox **Improvement** \* #i0032:
plugin.tx\_powermail.settings.misc.showOnlyFilledValues = true

4.0.0 **Major Feature** \* #53679: Workflow in AJAX

3.0.0 **Major Feature** \* #53360: Fit it into TYPO3 6.x

2.1.5 **Improvement** \* #i0032: Searchfields tca.php\* #i0031: Update
the manual. Here: wt\_cart

2.1.4 **Improvement** \* #52313: Try to externalise tcpdf

2.1.3 **Manual**

2.1.2 **Improvement** \* #i0029: Setup: minimum groos sum for items\*
#i0028: CSS\* #i0027: pdf filename %H%M -> %H%M%S\* #52165: Update
tcpdf: 5.9.149 -> 6.0.031

2.1.1 **Improvement** \* #i0026: additional template
caddy\_wo\_options.html\* #i0025: DRS

2.1.0 **Features** \* #51916: Plugin for amount only

2.0.11 **Improvement** \* #i0024: db.sql **Bugfixing** \* #i0023:
$this->extKey -> $\_EXTKEY

2.0.10 **Bugfixing** \* #50045: rounding error

2.0.9 **Bugfixing** \* #i0019: UTF8-Bug in Powermail 2

2.0.8 **Features** \* #i0022: Unique file names\* #i0021: Term of
Credit **Improvement** \* #i0020: Constant Editor Labelling
**Bugfixing** \* #49431: misspelling nachnahme\* #49427: misspelling
constants\* #49428: Localisation dependency (isn't tested in not
localised context)\* #49430: Check TypoScript Case

2.0.7 **Features** \* #i0018: \* New user functions\* calcDueDate\*
calcMultiply\* #i0016: \* Improved caddy table structur
**Improvement** \* #i0017: \* Shipping tax\* Moved from normal to
reduced

2.0.6 **Improvement** \* #i0015: \* Overwrite existing files!

2.0.5 **Bugfix** \* #i0014: \* Unproper TypoScript path to HTML table
properties for PDF files\* #i0013: \* If customer doesn't get any
invoice and any deliveryorder, HTML template for caddy isn't
loaded.This causes a bug, while sending the confirmation
e-mail.Workflow:\* Respect HTML template in case of all PDF files.

2.0.4 **Bugfix** \* #i0012: \* foreach( ) : Invalid argument in line
607\* foreach( ) : Invalid argument in line 171

2.0.3 **Improvements** \* #i0006: HTML mini caddy\* #i0005: TypoScript
is cleaned up

2.0.2 **Improvement** \* #i0004: Rendering of option lists\* #i0003:
Calculation of options costs

2.0.1 **Feature** \* #i0002: Power of Revocation **Bugfix** \* #i0001:
unproper locallang.xml value in userfunc

2.0.0 **Features** \* #45954: Database\* #45915: Powermail
Controller\* #45863: Icons\* #45808: PDF attachments: Bill and
Shipping\* #45967: Attachment Invoice\* #45968: Attachment Delivery
Order\* #45969: Attachment Terms & Conditions **FORK** \* #45792: DRS
- Development Reporting System\* #45775: Fork from wt\_cart 1.4.6\*
#45797: Consolidating code\* #45783: Proper Flexforms\* #45781: Proper
extension manager\* #45776: Move all labels from wt\_cart to caddy

`1 <#sdfootnote1anc>`_ If you are using TYPO3 6.x, powermail version
must be 2.x because 1.x version doesn't work with different languages
with 6.1. Powermail 2.x seems to be compulsory if the site is not in
English.

`1 <#sdfootnote2anc>`_ The one-click-installation is obligated only,
because there isn't any manual for a manual installation.

`2 <#sdfootnote3anc>`_ See "Organiser Installer" on page 14 below

`3 <#sdfootnote4anc>`_ See "Organiser Installer" on page 14 below

`4 <#sdfootnote5anc>`_ See "Quick Shop Installer" on page 14 below

`1 <#sdfootnote6anc>`_ You need the Organiser Installer from version
3.3. I purpose to publish it in September of 2013.

`2 <#sdfootnote7anc>`_ You need the Quick Shop Installer from version
3.3. I purpose to publish it in September of 2013.

`1 <#sdfootnote8anc>`_ Caddy is using the PAYMILL Javascript Bridge.
The clients' sensitive data is sent directly to PAYMILL and is never
touches your servers.

`2 <#sdfootnote9anc>`_ dito

`1 <#sdfootnote10anc>`_ Caddy supports both versions: Powermail 1.x
and 2.x

`2 <#sdfootnote11anc>`_ Depends on version 1.x and 2.x

`1 <#sdfootnote12anc>`_ You are controlling a lot of CSS properties by
the Constant Editor.

`2 <#sdfootnote13anc>`_ Colour is blue

`3 <#sdfootnote14anc>`_ There isn't any CSS for Powermail 1.x

`1 <#sdfootnote15anc>`_ Attachments are: invoice, delivery order,
power of revocation and terms & conditions

`1 <#sdfootnote16anc>`_ German: revisionssicher

`1 <#sdfootnote17anc>`_ Currently these templates are available in
2.0.0 only

`2 <#sdfootnote18anc>`_ If you are using TYPO3 6.x, powermail version
must be 2.x because 1.x version doesn't work with different languages
with 6.1. Powermail 2.x seems to be compulsory if the site is not in
English.

`1 <#sdfootnote19anc>`_ It is realised with wt\_cart 1.4.x in 2011 but
never published :(

`1 <#sdfootnote20anc>`_ Delivery order, invoice, terms & conditions

`2 <#sdfootnote21anc>`_ One dimension

`3 <#sdfootnote22anc>`_ Plugin 1

`4 <#sdfootnote23anc>`_ Audit proof in German: revisionssicher

`5 <#sdfootnote24anc>`_ supported by the DRS – Development Reporting
System

94


