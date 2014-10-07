plugin.tx_caddy_pi1 {
    // linktoshop
  _HTMLMARKER =
  _HTMLMARKER {
    caddy_bankaccount = CASE
    caddy_bankaccount {
        // optionspaymentid
      key = TEXT
      key {
        field = optionspaymentid
      }
        // default error message
      default = COA
      default {
        10 = TEXT
        10 {
          value =
          lang {
            de = TYPOSCRIPT FEHLER: key für CASE ist nicht definiert. Siehe: plugin.tx_caddy_pi1._HTMLMARKER.caddy_bankaccount. key =
            en = TYPOSCRIPT ERROR: key for CASE is undefined. See: plugin.tx_caddy_pi1._HTMLMARKER.caddy_bankaccount. key =
          }
          noTrimWrap = || |
        }
        20 = TEXT
        20 {
          field = optionspaymentid
        }
        wrap = <p>|</p>
      }
        // default invoice: term of credit, recommend us
      draftInvoice = COA
      draftInvoice {
        wrap = <p>|</p>
          // Line 1: Term of credit
        10 = COA
        10 {
            // Pay
          10 = TEXT
          10 {
            value = Kindly remit the balance
            lang {
              de = Bitte überweisen Sie
              en = Kindly remit the balance
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
            noTrimWrap = | | |
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
            noTrimWrap = | |:<br />|
          }
            // IBAN
          60 = TEXT
          60 {
            value = IBAN: {$plugin.caddy.bankaccount.iban}
            lang {
              de = IBAN: {$plugin.caddy.bankaccount.iban}
              en = IBAN: {$plugin.caddy.bankaccount.iban}
            }
            noTrimWrap = ||, |
          }
            // BIC
          61 = TEXT
          61 {
            value = BIC: {$plugin.caddy.bankaccount.bic}
            lang {
              de = BIC: {$plugin.caddy.bankaccount.bic}
              en = BIC: {$plugin.caddy.bankaccount.bic}
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
        // cash in advance: term of credit, recommend us
      1 < .draftInvoice
      1 {
        10 {
          21 >
          30 >
        }
      }
        // invoice (de: Rechnung): term of credit, recommend us
      2 < .draftInvoice
        // cash on delivery (de: Nachnahme): term of credit, recommend us
      3 = TEXT
      3 {
        value = Please pay the cash on delivery.
        lang {
          de = Bitte bezahlen Sie die Ware bei der Lieferung.
          en = Please pay the cash on delivery.
        }
      }
        // cash on pick up (de: Bar bei Abholung): term of credit, recommend us
      4 = TEXT
      4 {
        value = Please pay the cash on pick up.
        lang {
          de = Bitte bezahlen Sie die Ware bei Abholung.
          en = Please pay the cash on pick up.
        }
      }
    }
    caddy_buttonsubmit = COA
    caddy_buttonsubmit {
        // label
      10 = TEXT
      10 {
        value = Order with costs
        lang {
          de = Kostenpflichtig bestellen
          en = Order with costs
        }
        noTrimWrap = |<span class="label">|</span> |
      }
        // value
      30 = COA
      30 {
          // content
        10 = TEXT
        10 {
          value = Amount
          lang {
            de = Summe
            en = Amount
          }
          noTrimWrap = |(||
        }
          // value
        20 = USER
        20.userFunc = tx_caddy_userfunc->numberformat
        20.userFunc {
          drs           = {$plugin.caddy.debug.userfunc}
          number        = TEXT
          number {
            field = sumsumgross
          }
          decimal       = 2
          dec_point     = {$plugin.caddy.main.dec_point}
          thousands_sep = {$plugin.caddy.main.thousands_sep}
        }
          // currency
        30 = TEXT
        30 {
          value = {$plugin.caddy.main.currencySymbol}
          noTrimWrap = ||)</span>|
        }
        wrap = <span class="value">|</span>
      }
    }
      // Currency like EUR or USD
    caddy_currency = TEXT
    caddy_currency {
      value = {$plugin.caddy.e-payment.currency}
    }
      // item || items
    caddy_item_label = COA
    caddy_item_label {
        // in case of one item
      10 = TEXT
      10 {
        if {
          value = 2
          isLessThan {
            field = sumsumqty
          }
        }
        data = LLL:EXT:caddy/pi3/locallang.xml:item
      }
        // in case of more than one item
      20 = TEXT
      20 {
        if {
          value = 2
          isLessThan {
            field = sumsumqty
          }
          negate  = 1
        }
        data = LLL:EXT:caddy/pi3/locallang.xml:items
      }
    }
    caddy_prompt_cashdiscount = COA
    caddy_prompt_cashdiscount {
        // label
      10 = TEXT
      10 {
        value = Amount of your cash discount
        lang {
          de = Gesamtsumme Ihres Skontos
          en = Amount of your cash discount
        }
        noTrimWrap = ||: |
      }
        // value, currency
      20 = COA
      20 {
          // value
        10 = USER
        10.userFunc = tx_caddy_userfunc->numberformat
        10.userFunc {
          drs           = {$plugin.caddy.debug.userfunc}
          number        = TEXT
          number {
            field = sumcashdiscountsumgross
          }
          decimal       = 2
          dec_point     = {$plugin.caddy.main.dec_point}
          thousands_sep = {$plugin.caddy.main.thousands_sep}
        }
          // currency
        20 = TEXT
        20 {
          value = {$plugin.caddy.main.currencySymbol}
          noTrimWrap = | ||
        }
      }
    }
    caddy_prompt_order = COA
    caddy_prompt_order {
        // sumsumnet
      10 = COA
      10 {
        wrap = <div class="row">|</div>
          // label
        10 = COA
        10 {
          wrap = <span class="small-7 large-7 columns">|</span>
            // net
          10 = TEXT
          10 {
            data = LLL:EXT:caddy/pi1/locallang.xml:sumnet
          }
        }
          // &nbsp;
        30 = TEXT
        30 {
          value = &nbsp;
          wrap  = <span class="small-2 large-2 columns">|</span>
        }
          // value, currency
        40 = COA
        40 {
            // value
          10 = USER
          10.userFunc = tx_caddy_userfunc->numberformat
          10.userFunc {
            drs           = {$plugin.caddy.debug.userfunc}
            number        = TEXT
            number {
              field = sumsumnet
            }
            decimal       = 2
            dec_point     = {$plugin.caddy.main.dec_point}
            thousands_sep = {$plugin.caddy.main.thousands_sep}
          }
            // currency
          20 = TEXT
          20 {
            value = {$plugin.caddy.main.currencySymbol}
            noTrimWrap = | ||
          }
          wrap = <span class="small-2 large-2 columns caddy-right">|</span>
        }
          // &nbsp;
        50 = TEXT
        50 {
          value = &nbsp;
          wrap  = <span class="small-1 large-1 columns">|</span>
        }
      }
        // tax normal
      20 < .10
      20 {
        10 {
          10 >
          10 = TEXT
          10 {
            value = plus {$plugin.caddy.tax.normal}% VAT
            lang {
              de = zzgl. {$plugin.caddy.tax.normal}% MWSt.
              en = plus {$plugin.caddy.tax.normal}% VAT
            }
          }
        }
        40 {
          10.userFunc {
            number {
              field = sumsumtaxnormal
            }
          }
        }
      }
        // tax reduced
      30 < .10
      30 {
        10 {
          10 >
          10 = TEXT
          10 {
            value = plus {$plugin.caddy.tax.reduced}% VAT
            lang {
              de = zzgl. {$plugin.caddy.tax.reduced}% MWSt.
              en = plus {$plugin.caddy.tax.reduced}% VAT
            }
          }
        }
        40 {
          10.userFunc {
            number {
              field = sumsumtaxreduced
            }
          }
        }
      }
        // hr
      40 = TEXT
      40 {
        value = <hr />
        wrap = <div class="row">|</div>
      }
        // sumsumgross
      50 < .10
      50 {
        10 {
          10 >
          10 = TEXT
          10 {
            data = LLL:EXT:caddy/pi1/locallang.xml:sumgross
          }
        }
        40 {
          10.userFunc {
            number {
              field = sumsumgross
            }
          }
        }
      }
    }
      // label, icon. Replaces _HTMLMARKER_LINTOSHOP
    linktoshop = COA
    linktoshop {
        // Label
      10 = TEXT
      10 {
        data = LLL:EXT:caddy/pi1/locallang.xml:linktoshop
        typolink {
          parameter = {$plugin.caddy.pages.shop}
          title {
            data = LLL:EXT:caddy/pi1/locallang.xml:linktoshop
          }
        }
        noTrimWrap  = || |
      }
        // Icon
      20 = IMAGE
      20 {
        file = {$plugin.caddy.html.color.icon.caddy}
        altText {
          data = LLL:EXT:caddy/pi1/locallang.xml:shop
        }
        titleText {
          data = LLL:EXT:caddy/pi1/locallang.xml:shop
        }
        params = class="linktoshop"
        imageLinkWrap = 1
        imageLinkWrap {
          enable = 1
          typolink {
            parameter = {$plugin.caddy.pages.shop}
          }
        }
      }
      wrap = <div class="linktoshopwofloat linktoshop caddylinktoshop">|</div>
    }
  }
}