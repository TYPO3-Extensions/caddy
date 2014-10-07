plugin.tx_caddy_pi3 {
    // item, marker
  content =
  content {
      // quantity, label, gross
    item = COA
    item {
        // quantity
      10 = TEXT
      10 {
        field = quantity
        wrap = <div class="quantity itemquantity">|</div>
      }
        // label
      20 = TEXT
      20 {
        field = label
        wrap  = <div class="label itemlabel">|</div>
      }
        // gross
      30 = COA
      30 {
          // value
        10 = USER
        10.userFunc = tx_caddy_userfunc->numberformat
        10.userFunc {
          number = TEXT
          number.field = gross

          decimal       = 2
          dec_point     = {$plugin.caddy.main.dec_point}
          thousands_sep = {$plugin.caddy.main.thousands_sep}

          drs = {$plugin.caddy.debug.userfunc}
        }
          // currency
        20 = TEXT
        20 {
          value = {$plugin.caddy.main.currencySymbol}
          noTrimWrap = | ||
        }
        wrap = <div class="gross itemgross">|</div>
      }
      wrap = <div class="item minicaddyitem">|</div><div class="caddy_cleaner"></div>
    }
      // quantity, label, gross
    sum = COA
    sum {
        // quantity
      10 = TEXT
      10 {
        field = quantity
        wrap = <div class="quantity sumquantity">|</div>
      }
        // label
      20 = COA
      20 {
          // in case of one item
        10 = TEXT
        10 {
          if {
            value = 2
            isLessThan {
              field = quantity
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
              field = quantity
            }
            negate  = 1
          }
          data = LLL:EXT:caddy/pi3/locallang.xml:items
        }
        wrap = <div class="label sumlabel">|</div>
      }
        // gross
      30 = COA
      30 {
        10 = USER
        10.userFunc = tx_caddy_userfunc->numberformat
        10.userFunc {
          number = TEXT
          number.field = gross

          decimal       = 2
          dec_point     = {$plugin.caddy.main.dec_point}
          thousands_sep = {$plugin.caddy.main.thousands_sep}

          drs = {$plugin.caddy.debug.userfunc}
        }
        20 = TEXT
        20 {
          value = {$plugin.caddy.main.currencySymbol}
          noTrimWrap = | ||
        }
        wrap = <div class="gross sumgross">|</div><div class="caddy_cleaner"></div>
      }
      wrap = <div class="sum minicaddysum">|</div>
    }
  }
    // page id of the caddy
  pid = {$plugin.caddy.pages.caddy}

    // e-mail, html, pdf
  templates =
  templates < plugin.tx_caddy_pi1.templates

    // linktocaddy, linktoshop
  _HTMLMARKER =
  _HTMLMARKER {
      // label, icon. Replaces _HTMLMARKER_LINTOCADDY
    linktocaddy = COA
    linktocaddy {
        // Label
      10 = TEXT
      10 {
        data = LLL:EXT:caddy/pi3/locallang.xml:linktocaddy
        typolink {
          parameter = {$plugin.caddy.pages.caddy}
          title {
            data = LLL:EXT:caddy/pi3/locallang.xml:linktocaddy
          }
        }
        noTrimWrap  = || |
      }
        // Icon
      20 = IMAGE
      20 {
        file = {$plugin.caddy.html.color.icon.caddy}
        altText {
          data = LLL:EXT:caddy/pi3/locallang.xml:caddy
        }
        titleText {
          data = LLL:EXT:caddy/pi3/locallang.xml:caddy
        }
        params = class="linktocaddy"
        imageLinkWrap = 1
        imageLinkWrap {
          enable = 1
          typolink {
            parameter = {$plugin.caddy.pages.caddy}
          }
        }
      }
      wrap = <div class="linktocaddy minicaddylinktocaddy">|</div>
    }
      // label, icon. Replaces _HTMLMARKER_LINTOSHOP
    linktoshop = COA
    linktoshop {
        // Label
      10 = TEXT
      10 {
        data = LLL:EXT:caddy/pi3/locallang.xml:linktoshop
        typolink {
          parameter = {$plugin.caddy.pages.shop}
          title {
            data = LLL:EXT:caddy/pi3/locallang.xml:linktoshop
          }
        }
        noTrimWrap  = || |
      }
        // Icon
      20 = IMAGE
      20 {
        file = {$plugin.caddy.html.color.icon.caddy}
        altText {
          data = LLL:EXT:caddy/pi3/locallang.xml:shop
        }
        titleText {
          data = LLL:EXT:caddy/pi3/locallang.xml:shop
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
      wrap = <div class="linktoshop minicaddylinktoshop">|</div>
    }
  }
}
  // plugin.tx_caddy_pi3
