

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


TypoScript Snippet
^^^^^^^^^^^^^^^^^^

plugin.tx\_caddy\_pi1 {

db {

sql >

sql = TEXT

sql {

value (

SELECT

CAST('{GP:tx\_org\_cal\|uid}' AS SIGNED) AS puid,

CONCAT('<strong>', tx\_org\_cal.title, '</strong><br />',

'###GP:TX\_ORG\_CAL.DATETIME###<br />',

'Karte ', tx\_org\_calentrance.title, ' ', tx\_org\_calentrance.value,
' &euro;')

AS title,

tx\_org\_calentrance.value AS gross,

tx\_org\_tax.value AS tax,

CAST('{GP:tx\_org\_cal\|uid}' AS SIGNED) AS sku

FROM \`tx\_org\_cal\`

LEFT JOIN tx\_org\_cal\_mm\_tx\_org\_calentrance

ON (

tx\_org\_cal\_mm\_tx\_org\_calentrance.uid\_local = tx\_org\_cal.uid)

LEFT JOIN tx\_org\_calentrance

ON (

tx\_org\_cal\_mm\_tx\_org\_calentrance.uid\_foreign =
tx\_org\_calentrance.uid

AND tx\_org\_calentrance.uid = '###GP:TX\_ORG\_CALENTRANCE.UID###'

###ENABLE\_FIELDS:TX\_ORG\_CALENTRANCE###)

LEFT JOIN tx\_org\_tax

ON (

tx\_org\_calentrance.tx\_org\_tax = tx\_org\_tax.uid

###ENABLE\_FIELDS:TX\_ORG\_TAX###)

WHERE tx\_org\_cal.uid = '###GP:TX\_ORG\_CAL.UID###'

###ENABLE\_FIELDS:TX\_ORG\_CAL###

)

insertData = 1

}

}

settings {

variant {

10 = tx\_org\_calentrance.uid

}

}

}

