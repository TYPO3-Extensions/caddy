plugin.tx_caddy_pi1 {
    // Empty statement for proper comment only
  pdf {
  }
    // terms
  pdf =
  pdf {
      // terms: page, filename, content
    terms =
    terms {
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