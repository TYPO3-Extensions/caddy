plugin.tx_caddy_pi1 {
    // e-mail, html, pdf
  templates =
  templates {
      // caddy, caddymini, caddysum
    html =
    html {
        // pi1: file, marker, table
      caddy =
      caddy {
        file = {$plugin.caddy.templates.html.caddy}
          // all, item
        marker =
        marker {
          all   = ###{$plugin.caddy.html.marker.caddy}###
          item  = ###ITEM###
        }
          // border, cellpadding, cellspacing
        table =
        table {
          bordercolor             = {$plugin.caddy.html.color.border}
          width_td_qty            = {$plugin.caddy.html.width.table.td.qty}
          width_td_sku            = {$plugin.caddy.html.width.table.td.sku}
          width_td_item           = {$plugin.caddy.html.width.table.td.item}
          width_td_tax            = {$plugin.caddy.html.width.table.td.tax}
          width_td_net            = {$plugin.caddy.html.width.table.td.net}
          width_td_sum            = {$plugin.caddy.html.width.table.td.sum}
          width_td_skuitemtax     = {$plugin.caddy.html.width.table.td.skuitemtax}
          width_td_skuitemtaxnet  = {$plugin.caddy.html.width.table.td.skuitemtaxnet}
        }
      }
        // pi3: file, marker
      caddymini =
      caddymini {
        file = {$plugin.caddy.templates.html.caddymini}
          // all, item
        marker =
        marker {
          all   = ###CADDYMINI###
          item  = ###ITEMS###
        }
      }
        // pi2: file, marker
      caddysum =
      caddysum {
        file = {$plugin.caddy.templates.html.caddysum}
          // all
        marker =
        marker {
          all   = ###CADDYSUM###
        }
      }
      powermail {
        classes {
          fieldsWrap = TEXT
          fieldsWrap {
            value = {$plugin.caddy.html.powermail.classes.fieldsWrap}
          }
          form = TEXT
          form {
            value = {$plugin.caddy.html.powermail.classes.form}
          }
          tab = TEXT
          tab {
            value = {$plugin.caddy.html.powermail.classes.tab}
          }
        }
      }
    }
  }
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/Templates/Html/form.ts">