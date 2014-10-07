plugin.tx_caddy_pi1 {
    // e-mail, html, pdf
  templates =
  templates {
      // file, marker, table
    e-mail =
    e-mail {
      file = {$plugin.caddy.templates.e-mail}
        // all, item
      marker =
      marker {
        all   = ###CADDY_EMAIL###
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
  }
}