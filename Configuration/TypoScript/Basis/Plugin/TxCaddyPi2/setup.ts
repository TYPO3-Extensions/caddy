plugin.tx_caddy_pi2 {
    // item, marker
  content =
  content {
      // label, sumsumgross
    sum = COA
    sum {
        // label
      20 = TEXT
      20 {
        data        = LLL:EXT:caddy/pi2/locallang.xml:phrasesum
        noTrimWrap  = |<span class="label sumlabel">|</span> |
      }
        // sumsumgross
      30 = COA
      30 {
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
        wrap = <span class="gross sumgross">|</span>
      }
    }
  }
    // page id of the caddy
  pid = {$plugin.caddy.pages.caddy}

    // e-mail, html, pdf
  templates =
  templates < plugin.tx_caddy_pi1.templates
}