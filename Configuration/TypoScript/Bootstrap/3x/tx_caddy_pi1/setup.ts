
  ///////////////////////////////////////////////////////////////
  //
  // plugin.tx_caddy_pi1._HTMLMARKER

plugin.tx_caddy_pi1 {
    // linktoshop
  _HTMLMARKER {
    caddy_header_cashdiscount = COA
    caddy_header_cashdiscount {
      //wrap = <a href="#">|</a>
        // label
      10 = TEXT
      10 {
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span>
        value = Cash discount
        lang {
          de = Skonto
          en = Cash discount
        }
      }
        // &nbsp;
      30 = TEXT
      30 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.1}">|</span>
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
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.2}">|</span>
      }
        // &nbsp;
      50 = TEXT
      50 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.3}">|</span>
      }
    }
      // label, content, value
    caddy_header_items = COA
    caddy_header_items {
      //wrap = <a href="#">|</a>
        // label
      10 = COA
      10 {
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span>
        10 = TEXT
        10 {
          value = Caddy
          lang {
            de = Warenkorb
            en = Caddy
          }
          noTrimWrap = ||: |
        }
          // quantity, item || items
        20 = COA
        20 {
            // quantity
          10 = TEXT
          10 {
            field       = sumsumqty
            noTrimWrap  = || |
          }
            // item || items
          20 = COA
          20 {
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
        }
      }
        // &nbsp;
      30 = TEXT
      30 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.1}">|</span>
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
            field = sumitemsgross
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
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.2}">|</span>
      }
        // &nbsp;
      50 = TEXT
      50 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.3}">|</span>
      }
    }
    caddy_header_optionswopayment = COA
    caddy_header_optionswopayment {
      //wrap = <a href="#">|</a>
        // label
      10 = TEXT
      10 {
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span>
        value = Shipping and service
        lang {
          de = Service- und Versand
          en = Shipping and service
        }
      }
        // &nbsp;
      30 = TEXT
      30 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.1}">|</span>
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
            field = sumoptionswopaymentgross
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
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.2}">|</span>
      }
        // &nbsp;
      50 = TEXT
      50 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.3}">|</span>
      }
    }
    caddy_header_order = COA
    caddy_header_order {
      //wrap = <a href="#">|</a>
        // label
      10 = COA
      10 {
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span>
          // Amount
        10 = TEXT
        10 {
          value = Amount
          lang {
            de = Summe
            en = Amount
          }
          noTrimWrap = || |
        }
          // TAX
        20 = COA
        20 {
            // incl.
          10 = TEXT
          10 {
            value = incl.
            lang {
              de = inkl.
              en = incl.
            }
            noTrimWrap = || |
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
                field = sumsumtaxsum
              }
              decimal       = 2
              dec_point     = {$plugin.caddy.main.dec_point}
              thousands_sep = {$plugin.caddy.main.thousands_sep}
            }
              // currency
            20 = TEXT
            20 {
              value = {$plugin.caddy.main.currencySymbol}
              noTrimWrap = | | |
            }
          }
            // VAT
          30 = TEXT
          30 {
            value = VAT
            lang {
              de = MWSt.
              en = VAT
            }
          }
          wrap = (|)
        }
      }
        // &nbsp;
      30 = TEXT
      30 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.1}">|</span>
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
            field = sumsumgross
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
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.2}">|</span>
      }
        // &nbsp;
      50 = TEXT
      50 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.3}">|</span>
      }
    }
    caddy_header_paymentmethod = COA
    caddy_header_paymentmethod {
      //wrap = <a href="#">|</a>
        // label
      10 = COA
      10 {
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span>
          // label
        10 = TEXT
        10 {
          value = Payment method
          lang {
            de = Zahlungsmethode
            en = Payment method
          }
          noTrimWrap = ||: |
        }
          // content
        20 = TEXT
        20 {
          field = optionspaymentlabel
        }
      }
        // &nbsp;
      30 = TEXT
      30 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.1}">|</span>
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
            field = optionspaymentsumgross
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
        wrap = <span class="{$plugin.caddy.html.accordion.classes.column.2}">|</span>
      }
        // &nbsp;
      50 = TEXT
      50 {
        value = &nbsp;
        wrap  = <span class="{$plugin.caddy.html.accordion.classes.column.3}">|</span>
      }
    }
    caddy_header_paymentmethod_cashinadvance  < plugin.tx_caddy_pi1.api.options.payment.options.1.title
    caddy_header_paymentmethod_cashondelivery < plugin.tx_caddy_pi1.api.options.payment.options.3.title
    caddy_header_paymentmethod_cashonpickup   < plugin.tx_caddy_pi1.api.options.payment.options.4.title
    caddy_header_paymentmethod_invoice        < plugin.tx_caddy_pi1.api.options.payment.options.2.title
    caddy_header_powermail = TEXT
    caddy_header_powermail {
      //wrap = <a href="#"><span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span></a>
      wrap = <span class="{$plugin.caddy.html.accordion.classes.column.0}">|</span>
      value = Your data, power of revocation
      lang {
        de = Ihre Daten, Widerrufsbelehrung
        en = Your data, power of revocation
      }
    }
    caddy_prompt_order >
    caddy_prompt_order = COA
    caddy_prompt_order {
        // sumsumnet
      10 = COA
      10 {
        wrap = <div class="row">|</div>
          // &nbsp;
        10 = TEXT
        10 {
          value = &nbsp;
          wrap  = <span class="hidden-xs hidden-sm hidden-md col-lg-6">|</span>
        }
          // label
        20 = COA
        20 {
          wrap = <span class="col-xs-8 col-sm-8 col-md-10 col-lg-3">|</span>
            // net
          10 = TEXT
          10 {
            data = LLL:EXT:caddy/pi1/locallang.xml:sumnet
          }
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
          wrap = <span class="col-xs-4 col-sm-4 col-md-2 col-lg-2 text-right">|</span>
        }
          // &nbsp;
        50 = TEXT
        50 {
          value = &nbsp;
          wrap  = <span class="hidden-xs hidden-sm hidden-md col-lg-1">|</span>
        }
      }
        // tax normal
      20 < .10
      20 {
        20 {
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
        20 {
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
      40 = COA
      40 {
          // &nbsp;
        10 = TEXT
        10 {
          value = &nbsp;
          wrap  = <span class="hidden-xs hidden-sm hidden-md col-lg-6">|</span>
        }
          // hr
        20 = TEXT
        20 {
          value = <hr />
          wrap  = <span class="col-xs-12 col-sm-12 col-md-12 col-lg-6">|</span>
        }
        wrap = <div class="row">|</div>
      }
        // sumsumgross
      50 < .10
      50 {
        20 {
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
    cssAccordionActive = TEXT
    cssAccordionActive {
      value = {$plugin.caddy.html.accordion.classes.active}
    }
    linktoshop >
      // <button ...>label icon</button>
    linktoshop = COA
    linktoshop {
      10 = COA
      10 {
          // <button ...>
        10 = TEXT
        10 {
          typolink {
            parameter {
              cObject = COA
              cObject {
                  // url
                10 = TEXT
                10 {
                  value = {$plugin.caddy.pages.shop}
                }
                  // target
                20 = TEXT
                20 {
                  value       = -
                  noTrimWrap  = | "|"|
                }
                  // class
                30 = TEXT
                30 {
                  value       = button
                  noTrimWrap  = | "|"|
                }
                  // title
                40 = TEXT
                40 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:linktoshop
                  noTrimWrap  = | "|"|
                }
              }
            }
          }
        }
          // label, icon
        XX20 = COA
        XX20 {
            // Label
          10 = TEXT
          10 {
            data = LLL:EXT:caddy/pi1/locallang.xml:linktoshop
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
          }
        }
          // </button>
        30 = Text
        30 {
          value = </button>
        }
      }
    }
  }
}
  // plugin.tx_caddy_pi1._HTMLMARKER