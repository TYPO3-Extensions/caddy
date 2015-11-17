plugin.tx_caddy_pi1 {
    // deliveryorder, invoice, revocation, terms
  pdf =
  pdf {
      // delivery order PDF
    deliveryorder =
    deliveryorder {
        // label - delivery order number - date . pdf
      filename = COA
      filename {
          // label: invoice
        10 = TEXT
        10 {
          data = LLL:EXT:caddy/pi1/locallang.xml:deliveryorder
          wrap = |-
        }
          // deliver order number
        20 = TEXT
        20 {
          field = numberDeliveryorderCurrent
        }
          // date
        30 = TEXT
        30 {
          data      = date : U
          strftime  = %Y%m%d%H%M%S
          wrap      = -|
        }
          // extension pdf
        50 = TEXT
        50 {
          value = pdf
          wrap  = .|
        }
      }
        // address, caddy, date, numbers, termOfCredit, additionaltextblocks
      content =
      content {
          // deliveryorder, invoice
        address =
        address {
            // body
          deliveryorder =
          deliveryorder {
              // content
            header =
            header {
                // label for delivery order address
              content = TEXT
              content {
                value = Lieferaddresse
                lang {
                  de = Lieferaddresse
                  en = delivery address
                }
                wrap      = |<br>
              }
            }
            header >
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.deliveryorder.address.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.deliveryorder.address.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.deliveryorder.address.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.address}
              }
                // Address: company, first name and last name, street, zip and city, country
              content = COA
              content {
                  // Company
                10 = TEXT
                10 {
                  field     = deliveryorderCompany
                  required  = 1
                  wrap      = <b>|</b>
                }
                  // First name and last name
                20 = COA
                20 {
                  // First name
                  10 = TEXT
                  10 {
                    field     = deliveryorderFirstname
                  }
                    // Last name
                  20 = TEXT
                  20 {
                    field       = deliveryorderLastname
                    noTrimWrap  = | ||
                    required    = 1
                  }
                  wrap      = <br />|
                }
                  // Address
                30 = TEXT
                30 {
                  field     = deliveryorderAddress
                  wrap      = <br />|
                  required  = 1
                }
                  // ZIP and city
                40 = COA
                40 {
                    // ZIP
                  10 = TEXT
                  10 {
                    field     = deliveryorderZip
                  }
                    // City
                  20 = TEXT
                  20 {
                    field       = deliveryorderCity
                    noTrimWrap  = | ||
                    required    = 1
                  }
                  wrap      = <br />|
                }
                  // Country
                50 = TEXT
                50 {
                  field     = deliveryorderCountry
                  wrap      = <br />|
                  required  = 1
                }
              }
            }
          }
            // body
          invoice < .deliveryorder
          invoice {
            body {
              content = COA
              content {
                10 {
                  field = invoiceCompany
                }
                20 {
                  10 {
                    field = invoiceFirstname
                  }
                  20 {
                    field = invoiceLastname
                  }
                }
                30 {
                  field = invoiceAddress
                }
                40 {
                  10 {
                    field = invoiceZip
                  }
                  20 {
                    field = invoiceCity
                  }
                }
                50 {
                  field = invoiceCountry
                }
              }
            }
          }
        }

          // body
        caddy =
        caddy {
            // properties
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align =
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = {$plugin.caddy.pdf.caddy.width}
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.caddy.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.caddy.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size = {$plugin.caddy.pdf.caddy.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.caddy}
            }
          }
        }

          // body
        date =
        date {
            // properties, content
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align = {$plugin.caddy.pdf.date.align}
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = 0
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.date.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.date.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size  = {$plugin.caddy.pdf.date.fontsize}
                  // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                  // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.date}
            }
              // data, strftime
            content = TEXT
            content {
              data      = date : U
              strftime  = {$plugin.caddy.pdf.date.strftime}
            }
          }
        }

          // deliveryorder, invoice and order
        numbers =
        numbers {
            // body
          deliveryorder =
          deliveryorder {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.deliveryorder.number.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.deliveryorder.number.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.deliveryorder.number.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.numberDeliveryorder}
              }
                // label, prefix, deliveryordernumber
              content = COA
              content {
                  // label
                10 = TEXT
                10 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:deliveryordernumber
                  noTrimWrap = || |
                }
                  // prefix
                20 = TEXT
                20 {
                  value = {$plugin.caddy.pdf.deliveryorder.number.prefix}
                }
                  // deliveryordernumber
                30 = TEXT
                30 {
                  field = numberDeliveryorderCurrent
                }
              }
            }
          }
            // body
          orderAndInvoice =
          orderAndInvoice {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.deliveryorder.number.x}
                    // [INTEGER] Position from top in millimeter relative to delivery order number
                  y = TEXT
                  y {
                    value       = {$plugin.caddy.pdf.deliveryorder.number.y} + 10
                    prioriCalc  = intval
                  }
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points, taken from caddy.fontsize
                  size = {$plugin.caddy.pdf.caddy.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.numberLine}
              }
                // ordernumber devider invoicenumber
              content = COA
              content {
                  // label, prefix, ordernumber
                10 = COA
                10 {
                    // label
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:ordernumber
                    noTrimWrap = || |
                  }
                    // prefix
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.pdf.order.number.prefix}
                  }
                    // ordernumber
                  30 = TEXT
                  30 {
                    field = numberOrderCurrent
                  }
                }
                  // devider
                20 = TEXT
                20 {
                  value       = |
                  noTrimWrap  = | | |
                }
                  // label, prefix, invoicenumber
                30 = COA
                30 {
                    // label
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:invoicenumber
                    noTrimWrap = || |
                  }
                    // prefix
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.pdf.invoice.number.prefix}
                  }
                    // invoicenumber
                  30 = TEXT
                  30 {
                    field = numberInvoiceCurrent
                  }
                }
              }
            }
          }
        }
      }

    }
      // invoice PDF
    invoice =
    invoice {
        // label - invoicenumber - date . pdf
      filename = COA
      filename {
          // label: invoice
        10 = TEXT
        10 {
          data = LLL:EXT:caddy/pi1/locallang.xml:invoice
          wrap = |-
        }
          // invoicenumber
        20 = TEXT
        20 {
          field = numberInvoiceCurrent
        }
          // date
        30 = TEXT
        30 {
          data      = date : U
          strftime  = %Y%m%d%H%M%S
          wrap      = -|
        }
          // extension pdf
        50 = TEXT
        50 {
          value = pdf
          wrap  = .|
        }
      }
        // address, caddy, date, numbers, termOfCredit, additionaltextblocks
      content =
      content {
          // deliveryorder, invoice
        address =
        address {
            // header, body
          deliveryorder =
          deliveryorder {
              // content
            header =
            header {
                // label for delivery order address
              content = TEXT
              content {
                data = LLL:EXT:caddy/pi1/locallang.xml:deliveryaddress
                wrap      = |<br>
              }
            }
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = TEXT
                  x {
                    value       = {$plugin.caddy.pdf.invoice.address.x} + 100
                    prioriCalc  = 1
                  }
                    // [INTEGER] Position from top in millimeter
                  y = TEXT
                  y {
                    value       = {$plugin.caddy.pdf.invoice.address.y} - 6
                    prioriCalc  = 1
                  }
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.deliveryorder.address.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.address}
              }
                // Address: company, first name and last name, street, zip and city, country
              content = COA
              content {
                  // Company
                10 = TEXT
                10 {
                  field     = deliveryorderCompany
                  required  = 1
                  wrap      = <b>|</b>
                }
                  // First name and last name
                20 = COA
                20 {
                  // First name
                  10 = TEXT
                  10 {
                    field     = deliveryorderFirstname
                  }
                    // Last name
                  20 = TEXT
                  20 {
                    field       = deliveryorderLastname
                    noTrimWrap  = | ||
                    required    = 1
                  }
                  wrap      = <br />|
                }
                  // Address
                30 = TEXT
                30 {
                  field     = deliveryorderAddress
                  wrap      = <br />|
                  required  = 1
                }
                  // ZIP and city
                40 = COA
                40 {
                    // ZIP
                  10 = TEXT
                  10 {
                    field     = deliveryorderZip
                  }
                    // City
                  20 = TEXT
                  20 {
                    field       = deliveryorderCity
                    noTrimWrap  = | ||
                    required    = 1
                  }
                  wrap      = <br />|
                }
                  // Country
                50 = TEXT
                50 {
                  field     = deliveryorderCountry
                  wrap      = <br />|
                  required  = 1
                }
              }
            }
          }
            // body
          invoice =
          invoice {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.invoice.address.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.invoice.address.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.invoice.address.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.address}
              }
                // Address: company, first name and last name, street, zip and city, country
              content = COA
              content {
                  // Company
                10 = TEXT
                10 {
                  field     = invoiceCompany
                  required  = 1
                  wrap      = <b>|</b>
                }
                  // First name and last name
                20 = COA
                20 {
                  // First name
                  10 = TEXT
                  10 {
                    field     = invoiceFirstname
                    required  = 1
                    wrap      = <br />|
                  }
                    // Last name
                  20 = TEXT
                  20 {
                    field       = invoiceLastname
                    noTrimWrap  = | ||
                    required    = 1
                  }
                }
                  // Address
                30 = TEXT
                30 {
                  field     = invoiceAddress
                  wrap      = <br />|
                  required  = 1
                }
                  // ZIP and city
                40 = COA
                40 {
                    // ZIP
                  10 = TEXT
                  10 {
                    field     = invoiceZip
                    required  = 1
                    wrap      = <br />|
                  }
                    // City
                  20 = TEXT
                  20 {
                    field       = invoiceCity
                    noTrimWrap  = | ||
                    required    = 1
                  }
                }
                  // Country
                50 = TEXT
                50 {
                  field     = invoiceCountry
                  wrap      = <br />|
                  required  = 1
                }
              }
            }
          }
        }

          // body
        caddy =
        caddy {
            // properties
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align =
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = {$plugin.caddy.pdf.caddy.width}
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.caddy.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.caddy.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size = {$plugin.caddy.pdf.caddy.fontsize}
                  // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                  // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.caddy}
            }
          }
        }

          // body
        date =
        date {
            // properties, content
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align = {$plugin.caddy.pdf.date.align}
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = 0
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.date.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.date.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size  = {$plugin.caddy.pdf.date.fontsize}
                  // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                  // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.date}
            }
              // data, strftime
            content = TEXT
            content {
              data      = date : U
              strftime  = {$plugin.caddy.pdf.date.strftime}
            }
          }
        }

          // deliveryorder, invoice and order
        numbers =
        numbers {
            // body
          invoice =
          invoice {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.invoice.number.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.invoice.number.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.invoice.number.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.numberInvoice}
              }
                // label, prefix, invoicenumber
              content = COA
              content {
                  // label
                10 = TEXT
                10 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:invoicenumber
                  noTrimWrap = || |
                }
                  // prefix
                20 = TEXT
                20 {
                  value = {$plugin.caddy.pdf.invoice.number.prefix}
                }
                  // invoicenumber
                30 = TEXT
                30 {
                  field = numberInvoiceCurrent
                }
              }
            }
          }
            // body
          orderAndDeliveryorder =
          orderAndDeliveryorder {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.deliveryorder.number.x}
                    // [INTEGER] Position from top in millimeter relative to delivery order number
                  y = TEXT
                  y {
                    value       = {$plugin.caddy.pdf.deliveryorder.number.y} + 10
                    prioriCalc  = intval
                  }
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points, taken from caddy.fontsize
                  size = {$plugin.caddy.pdf.caddy.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.numberLine}
              }
                // ordernumber devider deliveryordernumber
              content = COA
              content {
                  // label, prefix, ordernumber
                10 = COA
                10 {
                    // label
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:ordernumber
                    noTrimWrap = || |
                  }
                    // prefix
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.pdf.order.number.prefix}
                  }
                    // ordernumber
                  30 = TEXT
                  30 {
                    field = numberOrderCurrent
                  }
                }
                  // devider
                20 = TEXT
                20 {
                  value       = |
                  noTrimWrap  = | | |
                }
                  // label, prefix, deliveryordernumber
                30 = COA
                30 {
                    // label
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:deliveryordernumber
                    noTrimWrap = || |
                  }
                    // prefix
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.pdf.deliveryorder.number.prefix}
                  }
                    // deliveryordernumber
                  30 = TEXT
                  30 {
                    field = numberDeliveryorderCurrent
                  }
                }
              }
            }
          }
        }
          // body
        termOfCredit =
        termOfCredit {
            // properties, content
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align = left
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = 0
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.invoice.termOfCredit.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.invoice.termOfCredit.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size  = {$plugin.caddy.pdf.invoice.termOfCredit.fontsize}
                  // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                  // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.termOfCredit}
            }
              // Line 1: Term of credit, Line 2: Recommend us
            content = COA
            content {
                // Line 1: Term of credit
              wrap = <p>|</p>
              10 = COA
              10 {
                if {
                  equals {
                    field = optionspaymentid
                  }
                  // 1: cash in advance
                  value = 1
                }
                  // Pay until
                10 = TEXT
                10 {
                  value = You have enabled cash in advance. Kindly remit the balance
                  lang {
                    de = Sie haben als Zahlungsmethode Vorkasse gewählt. Bitte überweisen Sie
                    en = You have enabled cash in advance. Kindly remit the balance
                  }
                  noTrimWrap = || |
                }
                  // amount
                20 = COA
                20 {
                    // sum gross
                  10 = USER
                  10 {
                    userFunc = tx_caddy_userfunc->numberformat
                    userFunc {
                      number = TEXT
                      number {
                        field = sumsumgross
                      }
                      decimal       = {$plugin.caddy.main.decimal}
                      dec_point     = {$plugin.caddy.main.dec_point}
                      thousands_sep = {$plugin.caddy.main.thousands_sep}
                      drs           = {$plugin.caddy.debug.userfunc}
                    }
                  }
                    // currency symbol
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.main.currencySymbol}
                    noTrimWrap = | ||
                  }
                  wrap = <b>|</b>
                }
                  // until
                21 = TEXT
                21 {
                  value = until
                  lang {
                    de = bis
                    en = until
                  }
                  noTrimWrap = || |
                }
                  // due date
                30 = USER
                30 {
                  userFunc = tx_caddy_userfunc->calcDueDate
                  userFunc {
                    strtotime = {$plugin.caddy.main.dueDate}
                    strftime  = {$plugin.caddy.main.dueDateFormat}
                  }
                }
                  // invoice number
                40 = COA
                40 {
                    // text
                  10 = TEXT
                  10 {
                    value = with the annotation
                    lang {
                      de = mit dem Vermerk
                      en = with the annotation
                    }
                    noTrimWrap  = | | |
                  }
                    // invoice number
                  20 = COA
                  20 {
                      // label
                    10 = TEXT
                    10 {
                      data = LLL:EXT:caddy/pi1/locallang.xml:invoicenumber
                      noTrimWrap = || |
                    }
                      // prefix
                    20 = TEXT
                    20 {
                      value = {$plugin.caddy.pdf.invoice.number.prefix}
                    }
                      // number
                    30 = TEXT
                    30 {
                      field = numberInvoiceCurrent
                    }
                    wrap = "<b>|</b>"
                  }
                }
                  // to account
                50 = TEXT
                50 {
                  value = to our account
                  lang {
                    de = auf unser Konto
                    en = to our account
                  }
                  noTrimWrap = | |<br />|
                }
                  // IBAN
                60 = COA
                60 {
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:IBAN
                    noTrimWrap = ||: |
                  }
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.bankaccount.iban}
                    lang {
                      de = {$plugin.caddy.bankaccount.iban}
                      en = {$plugin.caddy.bankaccount.iban}
                    }
                    noTrimWrap = ||, |
                  }
                }
                  // BIC
                61 = COA
                61 {
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:BIC
                    noTrimWrap = ||: |
                  }
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.bankaccount.bic}
                    lang {
                      de = {$plugin.caddy.bankaccount.bic}
                      en = {$plugin.caddy.bankaccount.bic}
                    }
                    noTrimWrap = ||, |
                  }
                }
                  // VAT Reg.No.
                62 = COA
                62 {
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:tax_reg_no
                    noTrimWrap = ||: |
                  }
                  20 = TEXT
                  20 {
                    value = {$plugin.caddy.tax.vatregno}
                    lang {
                      de = {$plugin.caddy.tax.vatregno}
                      en = {$plugin.caddy.tax.vatregno}
                    }
                  }
                }
                wrap  = |<br />
              }
                // Line 2: Recommend us
              20 = TEXT
              20 {
                value = We will be happy, if you recommend us.
                lang {
                  de = Wir w&uuml;rden uns freuen, wenn Sie uns weiterempfehlen.
                  en = We will be happy, if you recommend us.
                }
              }
            }
          }
        }
        termOfCredit >
      }
    }
      // revocation PDF
    revocation =
    revocation {
        // label - invoicenumber - date . pdf
      filename = COA
      filename {
          // label: revocation
        10 = TEXT
        10 {
          value = revocation
          lang {
            de = Widerrufsrecht
            en = revocation
          }
          wrap = |-
        }
          // invoicenumber
        20 = TEXT
        20 {
          field = numberInvoiceCurrent
        }
          // date
        30 = TEXT
        30 {
          data      = date : U
          strftime  = %Y%m%d%H%M%S
          wrap      = -|
        }
          // extension pdf
        50 = TEXT
        50 {
          value = pdf
          wrap  = .|
        }
      }
        // address, date, additionaltextblocks
      content =
      content {
          // invoice
        address =
        address {
            // body
          invoice = TEXT
          invoice {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.invoice.address.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.invoice.address.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.invoice.address.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.address}
              }
                // Address: company, first name and last name, street, zip and city, country
              content = COA
              content {
                  // Company
                10 = TEXT
                10 {
                  field     = invoiceCompany
                  required  = 1
                  wrap      = <b>|</b>
                }
                  // First name and last name
                20 = COA
                20 {
                  // First name
                  10 = TEXT
                  10 {
                    field     = invoiceFirstname
                    required  = 1
                    wrap      = <br />|
                  }
                    // Last name
                  20 = TEXT
                  20 {
                    field       = invoiceLastname
                    noTrimWrap  = | ||
                    required    = 1
                  }
                }
                  // Address
                30 = TEXT
                30 {
                  field     = invoiceAddress
                  wrap      = <br />|
                  required  = 1
                }
                  // ZIP and city
                40 = COA
                40 {
                    // ZIP
                  10 = TEXT
                  10 {
                    field     = invoiceZip
                    required  = 1
                    wrap      = <br />|
                  }
                    // City
                  20 = TEXT
                  20 {
                    field       = invoiceCity
                    noTrimWrap  = | ||
                    required    = 1
                  }
                }
                  // Country
                50 = TEXT
                50 {
                  field     = invoiceCountry
                  wrap      = <br />|
                  required  = 1
                }
              }
            }
          }
        }

          // body
        date =
        date {
            // properties, content
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align = {$plugin.caddy.pdf.date.align}
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = 0
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.date.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.date.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size  = {$plugin.caddy.pdf.date.fontsize}
                  // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                  // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.date}
            }
              // data, strftime
            content = TEXT
            content {
              data      = date : U
              strftime  = {$plugin.caddy.pdf.date.strftime}
            }
          }
        }

          // revocation
        additionaltextblocks =
        additionaltextblocks {
            // body
          revocation =
          revocation {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align = {$plugin.caddy.pdf.revocation.align}
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.revocation.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.revocation.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.revocation.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.revocation}
              }
                // content of page with id plugin.caddy.pages.revocation
              content < styles.content.get
              content {
                select {
                  pidInList = {$plugin.caddy.pages.revocation}
                }
              }
            }
          }
        }
      }
    }
      // terms PDF
    terms =
    terms {
        // label - invoicenumber - date . pdf
      filename = COA
      filename {
          // label: terms
        10 = TEXT
        10 {
          value = terms
          lang {
            de = AGB
            en = terms
          }
          wrap = |-
        }
          // invoicenumber
        20 = TEXT
        20 {
          field = numberInvoiceCurrent
        }
          // date
        30 = TEXT
        30 {
          data      = date : U
          strftime  = %Y%m%d%H%M%S
          wrap      = -|
        }
          // extension pdf
        50 = TEXT
        50 {
          value = pdf
          wrap  = .|
        }
      }
        // address, date, additionaltextblocks
      content =
      content {
          // invoice
        address =
        address {
            // body
          invoice = TEXT
          invoice {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align =
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.invoice.address.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.invoice.address.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.invoice.address.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.address}
              }
                // Address: company, first name and last name, street, zip and city, country
              content = COA
              content {
                  // Company
                10 = TEXT
                10 {
                  field     = invoiceCompany
                  required  = 1
                  wrap      = <b>|</b>
                }
                  // First name and last name
                20 = COA
                20 {
                  // First name
                  10 = TEXT
                  10 {
                    field     = invoiceFirstname
                    required  = 1
                    wrap      = <br />|
                  }
                    // Last name
                  20 = TEXT
                  20 {
                    field       = invoiceLastname
                    noTrimWrap  = | ||
                    required    = 1
                  }
                }
                  // Address
                30 = TEXT
                30 {
                  field     = invoiceAddress
                  wrap      = <br />|
                  required  = 1
                }
                  // ZIP and city
                40 = COA
                40 {
                    // ZIP
                  10 = TEXT
                  10 {
                    field     = invoiceZip
                    required  = 1
                    wrap      = <br />|
                  }
                    // City
                  20 = TEXT
                  20 {
                    field       = invoiceCity
                    noTrimWrap  = | ||
                    required    = 1
                  }
                }
                  // Country
                50 = TEXT
                50 {
                  field     = invoiceCountry
                  wrap      = <br />|
                  required  = 1
                }
              }
            }
          }
        }

          // body
        date =
        date {
            // properties, content
          body =
          body {
              // cell, font, textColor
            properties =
            properties {
                // align, height, width, x, y
              cell =
              cell {
                  // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                align = {$plugin.caddy.pdf.date.align}
                  // [INTEGER] Height of content in millimeter
                height = 0
                  // [INTEGER] Width of content in millimeter
                width = 0
                  // [INTEGER] Position from left in millimeter
                x = {$plugin.caddy.pdf.date.x}
                  // [INTEGER] Position from top in millimeter
                y = {$plugin.caddy.pdf.date.y}
              }
                // family, size, stretching, style
              font =
              font {
                  // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                family = {$plugin.caddy.pdfGeneral.fontfamily}
                  // [Integer] Font size in points
                size  = {$plugin.caddy.pdf.date.fontsize}
                  // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                  // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                style =
              }
                // [String] color in HTML notation like #CC00CC or #FFF
              textColor = {$plugin.caddy.pdf.textColor.date}
            }
              // data, strftime
            content = TEXT
            content {
              data      = date : U
              strftime  = {$plugin.caddy.pdf.date.strftime}
            }
          }
        }

          // terms
        additionaltextblocks =
        additionaltextblocks {
            // body
          terms =
          terms {
              // properties, content
            body =
            body {
                // cell, font, textColor
              properties =
              properties {
                  // align, height, width, x, y
                cell =
                cell {
                    // [STRING] L: left. C: center. R: right. empty: left for LTR or right for RTL
                  align = {$plugin.caddy.pdf.terms.align}
                    // [INTEGER] Height of content in millimeter
                  height = 0
                    // [INTEGER] Width of content in millimeter
                  width = 0
                    // [INTEGER] Position from left in millimeter
                  x = {$plugin.caddy.pdf.terms.x}
                    // [INTEGER] Position from top in millimeter
                  y = {$plugin.caddy.pdf.terms.y}
                }
                  // family, size, stretching, style
                font =
                font {
                    // [String] Arial, Courier, Helvetica, Symbol, Times, ZapfDingbats - or font name
                  family = {$plugin.caddy.pdfGeneral.fontfamily}
                    // [Integer] Font size in points
                  size  = {$plugin.caddy.pdf.terms.fontsize}
                    // [String] Strecth the font (in percent). 100: no effect, 90: smaller, 110: expanded
                  stretching = {$plugin.caddy.pdfGeneral.fontStretching}
                    // empty: regular, B: bold, I: italic, U: underline. Combinations are possible like: BI
                  style =
                }
                  // [String] color in HTML notation like #CC00CC or #FFF
                textColor = {$plugin.caddy.pdf.textColor.terms}
              }
                // content of page with id plugin.caddy.pages.terms
              content < styles.content.get
              content {
                select {
                  pidInList = {$plugin.caddy.pages.terms}
                }
              }
            }
          }
        }
      }
    }
  }
}