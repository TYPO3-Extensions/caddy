plugin.tx_caddy_pi1 {
    // Empty statement for proper comment only
  pdf {
  }
    // deliveryorder
  pdf =
  pdf {
      // delivery order: page, filename, content
    deliveryorder =
    deliveryorder {
        // 1, 2
      page =
      page {
          // First page: autopagebreak, margin
        1 =
        1 {
            //
          autopagebreak = 60
            // top, right, bottom, left
          margin =
          margin {
            top     = 20
            right   = 10
            bottom  = 40
            left    = 20
          }
        }
          // From second page: autopagebreak, margin
        2 =
        2 {
            //
          autopagebreak = 40
            // top, right, bottom, left
          margin =
          margin {
            top     = 20
            right   = 10
            bottom  = 20
            left    = 20
          }
        }
      }
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
  }
}