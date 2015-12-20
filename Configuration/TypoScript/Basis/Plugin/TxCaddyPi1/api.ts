plugin.tx_caddy_pi1 {
    // conditions, getpost, marker, options, symbols, tax
  api =
  api {
      // limits
    conditions =
    conditions {
        // items
      limits =
      limits {
          // gross
        items =
        items {
            // min
          gross =
          gross {
              // [double] minimal price. This value will compared with the gross sum of of the items (without service costs)
            min =
          }
        }
      }
    }
      // provider
    e-payment =
    e-payment {
        // currency
      currency = {$plugin.caddy.e-payment.currency}
        // paymill
      provider = {$plugin.caddy.e-payment.provider}
      provider {
        paymill =
      }
    }
      // gross, min, max, qty, sku, stockmanagement, stockquantity, tax, title, uid, volume, weight
    getpost =
    getpost {
      gross = TEXT
      gross {
        data = GP:{$plugin.caddy.getpost.price}
        htmlSpecialChars = 1
      }
      max = TEXT
      max {
        data = GP:{$plugin.caddy.getpost.max}
        htmlSpecialChars = 1
      }
      min = TEXT
      min {
        data = GP:{$plugin.caddy.getpost.min}
        htmlSpecialChars = 1
      }
      qty = TEXT
      qty {
        data = GP:{$plugin.caddy.getpost.qty}
        intval = 1
      }
      service_attribute_1 = TEXT
      service_attribute_1 {
        data = GP:{$plugin.caddy.getpost.service_attribute_1}
        htmlSpecialChars = 1
      }
      service_attribute_2 = TEXT
      service_attribute_2 {
        data = GP:{$plugin.caddy.getpost.service_attribute_2}
        htmlSpecialChars = 1
      }
      service_attribute_3 = TEXT
      service_attribute_3 {
        data = GP:{$plugin.caddy.getpost.service_attribute_3}
        htmlSpecialChars = 1
      }
      sku = TEXT
      sku {
        data = GP:{$plugin.caddy.getpost.sku}
        htmlSpecialChars = 1
      }
      stockmanagement = TEXT
      stockmanagement {
        data = GP:{$plugin.caddy.getpost.stockmanagement}
        htmlSpecialChars = 1
      }
      stockquantity = TEXT
      stockquantity {
        data = GP:{$plugin.caddy.getpost.stockquantity}
        htmlSpecialChars = 1
      }
      tax = TEXT
      tax {
        value = {$plugin.caddy.tax.normalCalc}
      }
      title = TEXT
      title {
        data = GP:{$plugin.caddy.getpost.title}
        htmlSpecialChars = 1
      }
      uid = TEXT
      uid {
        data = GP:{$plugin.caddy.getpost.uid}
        intval = 1
      }
      volume = TEXT
      volume {
        data = GP:{$plugin.caddy.getpost.volume}
        floatval = 1
      }
      weight = TEXT
      weight {
        data = GP:{$plugin.caddy.getpost.weight}
        floatval = 1
      }
    }

      // enabled
    inventorycontrol =
    inventorycontrol {
        // [Boolean]
      enabled = {$plugin.caddy.api.inventorycontrol.enabled}
    }
      // item, sum
    marker =
    marker {
        // delete, gross, min, max, uid, qty, service attributes (1-3), sku, stockmanagement, stockquantity, title, tax
      item =
      item {
          // delete icon: for removing the current item from the caddy
        delete = IMAGE
        delete {
          file = {$plugin.caddy.html.color.icon.delete}
          altText.data = LLL:EXT:caddy/pi1/locallang.xml:delete
          titleText.data = LLL:EXT:caddy/pi1/locallang.xml:delete
          params = class="caddy_delete"
          imageLinkWrap = 1
          imageLinkWrap {
            enable = 1
            typolink {
              parameter {
                cObject = COA
                cObject {
                    // URL
                  10 = TEXT
                  10 {
                    data = page:alias//TSFE:id
                  }
                    // target
                  20 = TEXT
                  20 {
                    value       = -
                    noTrimWrap  = | ||
                  }
                    // class
                  30 = TEXT
                  30 {
                    value       = onClickloadCaddyByAjax
                    noTrimWrap  = | ||
                  }
                }
              }
              additionalParams {
                wrap  = &tx_caddy_pi1[del]=|
                field = uid
              }
            }
          }
        }
        delete >
          // delete icon: for removing the current item from the caddy (#i0084)
        delete = TEXT
        delete {
          value = &times;
          //value = <i class="fi-trash"></i>
          typolink {
            parameter {
              cObject = COA
              cObject {
                  // URL
                10 = TEXT
                10 {
                  data = page:alias//TSFE:id
                }
                  // target
                20 = TEXT
                20 {
                  value       = -
                  noTrimWrap  = | ||
                }
                  // class
                30 = TEXT
                30 {
                  //value       = button delete mytiny alert onClickloadCaddyByAjax
                  value       = btn btn-primary button delete mytiny onClickloadCaddyByAjax
                  noTrimWrap  = | "|"|
                }
                  // title
                40 = TEXT
                40 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:delete
                  noTrimWrap  = | "|"|
                }
              }
            }
            additionalParams {
              wrap  = &tx_caddy_pi1[del]=|
              field = uid
            }
          }
        }
          // gross costs of the item
        gross = COA
        gross {
          10 = USER
          10.userFunc = tx_caddy_userfunc->numberformat
          10.userFunc {
            number = TEXT
            number.field = gross
            decimal = 2
            dec_point = {$plugin.caddy.main.dec_point}
            thousands_sep = {$plugin.caddy.main.thousands_sep}

            drs = {$plugin.caddy.debug.userfunc}
          }
          20 = TEXT
          20 {
            value = {$plugin.caddy.main.currencySymbol}
            noTrimWrap = | ||
          }
        }
          // minimum quantity for ordering
        min = TEXT
        min {
          field = min
        }
          // maximum quantity for ordering
        max = TEXT
        max {
          field = max
        }
          // net costs of the item
        net < .gross
        net.10.userFunc.number.field = net
          // quantity of the item
        qty = TEXT
        qty {
          field = qty
        }
          // service attribute 1
        service_attribute_1 = TEXT
        service_attribute_1 {
          field = service_attribute_1
        }
          // service attribute 2
        service_attribute_2 = TEXT
        service_attribute_2 {
          field = service_attribute_2
        }
          // service attribute 3
        service_attribute_3 = TEXT
        service_attribute_3 {
          field = service_attribute_3
        }
          // stock keeping unit
        sku = TEXT
        sku {
          field = sku
        }
          // [Boolean] stockmanagement
        stockmanagement = TEXT
        stockmanagement {
          field = stockmanagement
        }
          // [Integer] stock keeping unit
        stockquantity = TEXT
        stockquantity {
          field = stockquantity
        }
          // gross costs sum ( gross costs * quantity )
        sumgross < .gross
        sumgross.10.userFunc.number.field = sumgross
          // net costs sum ( net costs * quantity )
        sumnet < .gross
        sumnet.10.userFunc.number.field = sumnet
          // tax costs sum ( tax costs * quantity )
        sumtax < .gross
        sumtax.10.userFunc.number.field = sumtax
          // tax value of the item (0 , 1, 2)
        tax = TEXT
        tax {
          field = tax
        }
          // taxrate of the item
        taxrate = COA
        taxrate {
          10 = USER
          10 {
            userFunc = tx_caddy_userfunc->calcMultiply
            userFunc {
              number = TEXT
              number {
                field         = taxrate
              }
              multiplier    = 100
              decimal       = 0
              dec_point     = {$plugin.caddy.main.dec_point}
              thousands_sep = {$plugin.caddy.main.thousands_sep}
              drs           = {$plugin.caddy.debug.userfunc}
            }
          }
          20 = TEXT
          20 {
            value = {$plugin.caddy.main.percentSymbol}
          }
        }
          // label of the item
        title = TEXT
        title {
          field = title
          typolink {
            additionalParams {
              wrap  = &tx_browser_pi1[{$plugin.caddy.url.showUid}]=|
              field = uid
            }
            forceAbsoluteUrl = 1
            parameter = {$plugin.caddy.pages.shop} - linktosingle
            title {
              value = Item
              lang {
                de = Artikel
                en = Item
              }
            }
            useCacheHash = 1
          }
        }
          // uid of the item
        uid = TEXT
        uid {
          field = uid
        }
      }
        // labels, rates, values
      sum =
      sum {
          // payment, shipping, specials
        labels =
        labels {
            // label, gross
          optionspaymentlabel = TEXT
          optionspaymentlabel {
            field = optionspaymentlabel
          }
          optionsshippinglabel = TEXT
          optionsshippinglabel {
            field = optionsshippinglabel
          }
          optionsspecialslabels = TEXT
          optionsspecialslabels {
            field = optionsspecialslabels
          }
            // plus value, VAT
          taxrate_reduced_string = COA
          taxrate_reduced_string {
              // plus value
            10 = TEXT
            10 {
              value = plus
              lang {
                de = zuz&uuml;glich
                en = plus
              }
              noTrimWrap = || {$plugin.caddy.tax.reduced}{$plugin.caddy.main.percentSymbol} |
            }
              // VAT
            20 = TEXT
            20 {
              data = LLL:EXT:caddy/pi1/locallang.xml:tax_abbr
            }
          }
            // plus value, VAT
          taxrate_normal_string = COA
          taxrate_normal_string {
              // plus value
            10 = TEXT
            10 {
              value = plus
              lang {
                de = zuz&uuml;glich
                en = plus
              }
              noTrimWrap = || {$plugin.caddy.tax.normal}{$plugin.caddy.main.percentSymbol} |
            }
              // VAT
            20 = TEXT
            20 {
              data = LLL:EXT:caddy/pi1/locallang.xml:tax_abbr
            }
          }
        }
          // payment, shipping, specials
        rates =
        rates {
          optionspaymentsumrate = COA
          optionspaymentsumrate {
            10 = USER
            10 {
              userFunc = tx_caddy_userfunc->numberformat
              userFunc {
                number = TEXT
                number {
                  field = optionspaymentsumrate
                }
                decimal       = {$plugin.caddy.main.decimal}
                dec_point     = {$plugin.caddy.main.dec_point}
                thousands_sep = {$plugin.caddy.main.thousands_sep}
                drs           = {$plugin.caddy.debug.userfunc}
              }
            }
            20 = TEXT
            20 {
              value = {$plugin.caddy.main.percentSymbol}
              noTrimWrap = | ||
            }
          }

          optionsshippingsumrate                           < .optionspaymentsumrate
          optionsshippingsumrate.10.userFunc.number.field  = optionsshippingsumrate

          optionsspecialssumrate                           < .optionspaymentsumrate
          optionsspecialssumrate.10.userFunc.number.field  = optionsspecialssumrate
        }
          // payment, shipping, specials, items, sum, target
        values =
        values {
          optionspaymentsumgross = COA
          optionspaymentsumgross {
            10 = USER
            10 {
              userFunc = tx_caddy_userfunc->numberformat
              userFunc {
                drs           = {$plugin.caddy.debug.userfunc}
                number        = TEXT
                number {
                  field = optionspaymentsumgross
                }
                decimal       = {$plugin.caddy.main.decimal}
                dec_point     = {$plugin.caddy.main.dec_point}
                thousands_sep = {$plugin.caddy.main.thousands_sep}
              }
            }
            20 = TEXT
            20 {
              value = {$plugin.caddy.main.currencySymbol}
              noTrimWrap = | ||
            }
          }
          optionspaymentsumnet          < .optionspaymentsumgross
          optionspaymentsumnet.10.userFunc.number.field         = optionspaymentsumnet
          optionspaymentsumtaxnormal    < .optionspaymentsumgross
          optionspaymentsumtaxnormal.10.userFunc.number.field   = optionspaymentsumtaxnormal
          optionspaymentsumtaxreduced   < .optionspaymentsumgross
          optionspaymentsumtaxreduced.10.userFunc.number.field  = optionspaymentsumtaxreduced

          optionsshippingsumgross       < .optionspaymentsumgross
          optionsshippingsumgross.10.userFunc.number.field      = optionsshippingsumgross
          optionsshippingsumnet         < .optionspaymentsumgross
          optionsshippingsumnet.10.userFunc.number.field        = optionsshippingsumnet
          optionsshippingsumtaxnormal   < .optionspaymentsumgross
          optionsshippingsumtaxnormal.10.userFunc.number.field  = optionsshippingsumtaxnormal
          optionsshippingsumtaxreduced  < .optionspaymentsumgross
          optionsshippingsumtaxreduced.10.userFunc.number.field = optionsshippingsumtaxreduced

          optionsspecialssumgross       < .optionspaymentsumgross
          optionsspecialssumgross.10.userFunc.number.field      = optionsspecialssumgross
          optionsspecialssumnet         < .optionspaymentsumgross
          optionsspecialssumnet.10.userFunc.number.field        = optionsspecialssumnet
          optionsspecialssumtaxnormal   < .optionspaymentsumgross
          optionsspecialssumtaxnormal.10.userFunc.number.field  = optionsspecialssumtaxnormal
          optionsspecialssumtaxreduced  < .optionspaymentsumgross
          optionsspecialssumtaxreduced.10.userFunc.number.field = optionsspecialssumtaxreduced

            // Sum gross of cash discount
          sumcashdiscountsumgross  < .optionspaymentsumgross
          sumcashdiscountsumgross.10.userFunc.number.field = sumcashdiscountsumgross
            // Sum net of cash discount
          sumcashdiscountsumnet    < .optionspaymentsumgross
          sumcashdiscountsumnet.10.userFunc.number.field   = sumcashdiscountsumnet

            // Sum gross of items
          sumitemsgross         < .optionspaymentsumgross
          sumitemsgross.10.userFunc.number.field        = sumitemsgross
            // Sum net of items
          sumitemsnet           < .optionspaymentsumgross
          sumitemsnet.10.userFunc.number.field          = sumitemsnet
            // Sum tax normal of items
          sumitemstaxnormal     < .optionspaymentsumgross
          sumitemstaxnormal.10.userFunc.number.field    = sumitemstaxnormal
            // Sum reduced of items
          sumitemstaxreduced    < .optionspaymentsumgross
          sumitemstaxreduced.10.userFunc.number.field   = sumitemstaxreduced

            // Sum gross of payment, shipping and specials
          sumoptionsgross       < .optionspaymentsumgross
          sumoptionsgross.10.userFunc.number.field      = sumoptionsgross
            // Sum net of payment, shipping and specials
          sumoptionsnet         < .optionspaymentsumgross
          sumoptionsnet.10.userFunc.number.field        = sumoptionsnet
            // Sum tax normal of payment, shipping and specials
          sumoptionstaxnormal   < .optionspaymentsumgross
          sumoptionstaxnormal.10.userFunc.number.field  = sumoptionstaxnormal
            // Sum tax reduced of payment, shipping and specials
          sumoptionstaxreduced  < .optionspaymentsumgross
          sumoptionstaxreduced.10.userFunc.number.field = sumoptionstaxreduced

            // Sum gross of options without payment
          sumoptionswopaymentgross       < .optionspaymentsumgross
          sumoptionswopaymentgross.10.userFunc.number.field      = sumoptionswopaymentgross
            // Sum net of options without payment
          sumoptionswopaymentnet         < .optionspaymentsumgross
          sumoptionswopaymentnet.10.userFunc.number.field        = sumoptionswopaymentnet
            // Sum tax normal of options without payment
          sumoptionswopaymenttaxnormal   < .optionspaymentsumgross
          sumoptionswopaymenttaxnormal.10.userFunc.number.field  = sumoptionswopaymenttaxnormal
            // Sum tax reduced of options without payment
          sumoptionswopaymenttaxreduced  < .optionspaymentsumgross
          sumoptionswopaymenttaxreduced.10.userFunc.number.field = sumoptionswopaymenttaxreduced

            // Quantity over all items
          sumsumqty = TEXT
          sumsumqty {
            field = sumsumqty
          }

            // Sum gross over all
          sumsumgross           < .optionspaymentsumgross
          sumsumgross.10.userFunc.number.field          = sumsumgross
            // Sum gross without currency for e-payment
          sumsumgrossepayment = USER
          sumsumgrossepayment {
            userFunc = tx_caddy_userfunc->numberformat
            userFunc {
              drs           = {$plugin.caddy.debug.userfunc}
              number        = TEXT
              number {
                field = sumsumgross
              }
              decimal       = 2
              dec_point     = .
              thousands_sep =
            }
          }
            // Sum net over all
          sumsumnet             < .optionspaymentsumgross
          sumsumnet.10.userFunc.number.field            = sumsumnet
            // Sum tax normal over all
          sumsumtaxnormal       < .optionspaymentsumgross
          sumsumtaxnormal.10.userFunc.number.field      = sumsumtaxnormal
            // Sum tax reduced over all
          sumsumtaxreduced      < .optionspaymentsumgross
          sumsumtaxreduced.10.userFunc.number.field     = sumsumtaxreduced
            // Sum tax over all
          sumsumtaxsum          < .optionspaymentsumgross
          sumsumtaxsum.10.userFunc.number.field         = sumsumtaxsum
          target  = TEXT
          target {
            typolink {
              parameter {
                data = TSFE:id
              }
              returnLast = url
            }
          }
        }
      }
    }

      // payment, shipping, special
    options =
    options {
        // preset, options
      payment =
      payment {
          // default method
        preset = {$plugin.caddy.options.payment.default}
          // cash in advance, invoice, cash on delivery
        options =
        options {
            // enabled, title, extra, tax
          1 = cash in advance (de: Vorkasse)
          1 {
            enabled = {$plugin.caddy.options.payment.1.enabled}
              // label, gross, cash discount
            title = COA
            title {
                // label, gross
              10 = COA
              10 {
                10 = TEXT
                10 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:paymentoption_cashinadvance
                }
                  // amount
                20 = COA
                20 {
                  if {
                    value = 0
                    isGreaterThan = {$plugin.caddy.options.payment.1.costs}
                  }
                    // space
                  10 = TEXT
                  10 {
                    value =
                    noTrimWrap = | ||
                  }
                    // gross, currency symbol
                  20 = COA
                  20 {
                      // gross
                    10 = USER
                    10 {
                      userFunc = tx_caddy_userfunc->numberformat
                      userFunc {
                        number = TEXT
                        number {
                          value = {$plugin.caddy.options.payment.1.costs}
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
                    wrap = (+|)
                  }
                }
              }
                // cash discount
              20 = COA
              20 {
                if {
                  value = 0
                  isGreaterThan = {$plugin.caddy.options.payment.1.cashdiscount}
                }
                  // devider
                10 = TEXT
                10 {
                  data        = LLL:EXT:caddy/pi1/locallang.xml:cashdiscount
                  noTrimWrap  = |, | |
                }
                  // Cash discount
                20 = COA
                20 {
                    // value
                  10 = USER
                  10 {
                    userFunc = tx_caddy_userfunc->numberformat
                    userFunc {
                      number = TEXT
                      number {
                        value = {$plugin.caddy.options.payment.1.cashdiscount}
                      }
                      decimal       = {$plugin.caddy.main.decimal}
                      dec_point     = {$plugin.caddy.main.dec_point}
                      thousands_sep = {$plugin.caddy.main.thousands_sep}
                      drs           = {$plugin.caddy.debug.userfunc}
                    }
                  }
                    // symbol
                  20 = TEXT
                  20 {
                    value = %
                    noTrimWrap = | ||
                  }
                }
              }
            }
              // on, percent
            cash-discount =
            cash-discount {
                // items, payment, service, shipping
              on =
              on {
                  // [Boolean] true (default): items get the cash discount. false: items don't get the cash discount.
                items     = 1
                  // [Boolean] false (default): payment costs don't get the cash discount. true: payment costs get the cash discount.
                payment   = 0
                  // [Boolean] false (default): shipping costs don't get the cash discount. true: shipping costs get the cash discount.
                shipping  = 0
                  // [Boolean] false (default): specials costs don't get the cash discount. true: specials costs get the cash discount.
                specials  = 0
              }
                // [Double]
              percent = {$plugin.caddy.options.payment.1.cashdiscount}
            }
              // extra cost (gross price) of payment method
            extra = {$plugin.caddy.options.payment.1.costs}
              // tax rate which will be applied (reduced or normal)
            tax = {$plugin.caddy.options.payment.1.taxrate}
          }
          2 < .1
          2 = invoice (de: Rechnung)
          2 {
            enabled = {$plugin.caddy.options.payment.2.enabled}
            title {
              10 {
                10 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:paymentoption_invoice
                }
                20 {
                  if {
                    isGreaterThan = {$plugin.caddy.options.payment.2.costs}
                  }
                  20 {
                    10 {
                      userFunc {
                        number {
                          value = {$plugin.caddy.options.payment.2.costs}
                        }
                      }
                    }
                  }
                }
              }
              20 {
                if {
                  isGreaterThan = {$plugin.caddy.options.payment.2.cashdiscount}
                }
                20 {
                  10 {
                    userFunc {
                      number {
                        value = {$plugin.caddy.options.payment.2.cashdiscount}
                      }
                    }
                  }
                }
              }
            }
            cash-discount {
              percent = {$plugin.caddy.options.payment.2.cashdiscount}
            }
            extra = {$plugin.caddy.options.payment.2.costs}
            tax   = {$plugin.caddy.options.payment.2.taxrate}
          }
          3 < .1
          3 = cash on delivery (de: Nachnahme)
          3 {
            enabled = {$plugin.caddy.options.payment.3.enabled}
            title {
              10 {
                10 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:paymentoption_cashondelivery
                }
                20 {
                  if {
                    isGreaterThan = {$plugin.caddy.options.payment.3.costs}
                  }
                  20 {
                    10 {
                      userFunc {
                        number {
                          value = {$plugin.caddy.options.payment.3.costs}
                        }
                      }
                    }
                  }
                }
              }
              20 {
                if {
                  isGreaterThan = {$plugin.caddy.options.payment.3.cashdiscount}
                }
                20 {
                  10 {
                    userFunc {
                      number {
                        value = {$plugin.caddy.options.payment.3.cashdiscount}
                      }
                    }
                  }
                }
              }
            }
            cash-discount {
              percent = {$plugin.caddy.options.payment.3.cashdiscount}
            }
            extra = {$plugin.caddy.options.payment.3.costs}
            tax   = {$plugin.caddy.options.payment.3.taxrate}
          }
          4 < .1
          4 = cash on pick up (de: Bar bei Abholung)
          4 {
            enabled = {$plugin.caddy.options.payment.4.enabled}
            title {
              10 {
                10 {
                  data = LLL:EXT:caddy/pi1/locallang.xml:paymentoption_cashonpickup
                }
                20 {
                  if {
                    isGreaterThan = {$plugin.caddy.options.payment.4.costs}
                  }
                  20 {
                    10 {
                      userFunc {
                        number {
                          value = {$plugin.caddy.options.payment.4.costs}
                        }
                      }
                    }
                  }
                }
              }
              20 {
                if {
                  isGreaterThan = {$plugin.caddy.options.payment.4.cashdiscount}
                }
                20 {
                  10 {
                    userFunc {
                      number {
                        value = {$plugin.caddy.options.payment.4.cashdiscount}
                      }
                    }
                  }
                }
              }
            }
            cash-discount {
              percent = {$plugin.caddy.options.payment.4.cashdiscount}
            }
            extra = {$plugin.caddy.options.payment.4.costs}
            tax   = {$plugin.caddy.options.payment.4.taxrate}
          }
        }
      }
        // preset, options
      shipping =
      shipping {
          // default method
        preset = {$plugin.caddy.options.shipping.default}
          // mail, mailexpress
        options =
        options {
            // mail: enabled, title, extra, tax
          1 =
          1 {
            enabled = {$plugin.caddy.options.shipping.1.enabled}
              // label, gross
            title = COA
            title {
              10 = TEXT
              10 {
                data = LLL:EXT:caddy/pi1/locallang.xml:shippingoption_maildefault
                noTrimWrap  = || |
              }
                // amount
              20 = COA
              20 {
                if {
                  value = 0
                  isGreaterThan = {$plugin.caddy.options.shipping.1.costs}
                }
                  // gross
                10 = USER
                10 {
                  userFunc = tx_caddy_userfunc->numberformat
                  userFunc {
                    number = TEXT
                    number {
                      value = {$plugin.caddy.options.shipping.1.costs}
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
                wrap = (+|)
              }
            }
              // extra cost (gross price) of payment method
            extra = {$plugin.caddy.options.shipping.1.costs}
              // tax rate which will be applied (reduced or normal)
            tax = {$plugin.caddy.options.shipping.1.taxrate}
          }
            // mailexpress: enabled, title, extra, tax
          2 < .1
          2 =
          2 {
            enabled = {$plugin.caddy.options.shipping.2.enabled}
            title {
              10 {
                data = LLL:EXT:caddy/pi1/locallang.xml:shippingoption_mailexpress
              }
              20 {
                if {
                  isGreaterThan = {$plugin.caddy.options.shipping.2.costs}
                }
                10 {
                  userFunc {
                    number {
                      value = {$plugin.caddy.options.shipping.2.costs}
                    }
                  }
                }
              }
            }
            extra = {$plugin.caddy.options.shipping.2.costs}
            tax   = {$plugin.caddy.options.shipping.2.taxrate}
          }
            // pick up: enabled, title, extra, tax
          3 < .1
          3 =
          3 {
            enabled = {$plugin.caddy.options.shipping.3.enabled}
            title {
              10 {
                data = LLL:EXT:caddy/pi1/locallang.xml:shippingoption_pickup
              }
              20 {
                if {
                  isGreaterThan = {$plugin.caddy.options.shipping.3.costs}
                }
                10 {
                  userFunc {
                    number {
                      value = {$plugin.caddy.options.shipping.3.costs}
                    }
                  }
                }
              }
            }
            extra = {$plugin.caddy.options.shipping.3.costs}
            tax   = {$plugin.caddy.options.shipping.3.taxrate}
          }
        }
      }
        // preset, options
      specials =
      specials {
          // devider for list of options in e-mails und PDF attachments
        devider = TEXT
        devider {
          value       = ,
          noTrimWrap  = || |
        }
          // default method
        preset = {$plugin.caddy.options.specials.default}
          // neutralpackage, addfeeforislands
        options =
        options {
            // neutralpackage: enabled, title, extra, tax
          1 =
          1 {
            enabled = {$plugin.caddy.options.specials.1.enabled}
              // label, gross
            title = COA
            title {
              10 = TEXT
              10 {
                data = LLL:EXT:caddy/pi1/locallang.xml:specialoption_neutralpackage
                noTrimWrap  = || |
              }
                // amount
              20 = COA
              20 {
                if {
                  value = 0
                  isGreaterThan = {$plugin.caddy.options.specials.1.costs}
                }
                  // gross
                10 = USER
                10 {
                  userFunc = tx_caddy_userfunc->numberformat
                  userFunc {
                    number = TEXT
                    number {
                      value = {$plugin.caddy.options.specials.1.costs}
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
                wrap = (+|)
              }
            }
              // extra cost (gross price) of payment method
            extra = {$plugin.caddy.options.specials.1.costs}
              // tax rate which will be applied (reduced or normal)
            tax = {$plugin.caddy.options.specials.1.taxrate}
          }
           // addfeeforislands: enabled, title, extra, tax
          2 < .1
          2 =
          2 {
            title {
              10 {
                data = LLL:EXT:caddy/pi1/locallang.xml:specialoption_addfeeforislands
              }
              20 {
                if {
                  isGreaterThan = {$plugin.caddy.options.specials.2.costs}
                }
                10 {
                  userFunc {
                    number {
                      value = {$plugin.caddy.options.specials.2.costs}
                    }
                  }
                }
                20 {
                  value = {$plugin.caddy.main.currencySymbol}
                }
              }
            }
            extra = {$plugin.caddy.options.specials.2.costs}
            tax   = {$plugin.caddy.options.specials.2.taxrate}
          }
        }
      }
    }
      // decimal, dec_point, thousands_sep, currencySymbol, currencySymbolBeforePrice, quantitySymbol, service_attributes_X_symbol
    symbols =
    symbols {
      decimal         = {$plugin.caddy.main.decimal}
      dec_point       = {$plugin.caddy.main.dec_point}
      thousands_sep   = {$plugin.caddy.main.thousands_sep}
      currencySymbol  = {$plugin.caddy.main.currencySymbol}
      currencySymbolBeforePrice = {$plugin.caddy.main.currencySymbolBeforePrice}
      quantitySymbol  = {$plugin.caddy.symbol.quantitySymbol}
      service_attribute_1_symbol = {$plugin.caddy.symbol.service_attribute_1_symbol}
      service_attribute_2_symbol = {$plugin.caddy.symbol.service_attribute_2_symbol}
      service_attribute_3_symbol = {$plugin.caddy.symbol.service_attribute_3_symbol}
    }
      // reduced, normal
    tax =
    tax {
      reduced     = {$plugin.caddy.tax.reduced}
      reducedCalc = {$plugin.caddy.tax.reducedCalc}
      normal      = {$plugin.caddy.tax.normal}
      normalCalc  = {$plugin.caddy.tax.normalCalc}
    }
  }
}