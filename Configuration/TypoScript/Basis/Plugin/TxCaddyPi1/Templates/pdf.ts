plugin.tx_caddy_pi1 {
    // e-mail, html, pdf
  templates =
  templates {
      // delivery order, invoice, revocation, terms
    pdf =
    pdf {
        // file, marker, table
      deliveryorder =
      deliveryorder {
        file = {$plugin.caddy.templates.pdf.deliveryorder}
          // all, item
        marker =
        marker {
          all   = ###CADDY_EMAILDELIVERY###
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
        // file, marker, table
      invoice =
      invoice {
        file = {$plugin.caddy.templates.pdf.invoice}
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
        // #i0013, 130604, dwildt, + // file, marker, table
      revocation =
      revocation {
        file = {$plugin.caddy.templates.pdf.invoice}
          // all, item
        marker =
        marker {
          all   = ###CADDY_REVOCATION###
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
        // #i0013, 130604, dwildt, + // file, marker, table
      terms =
      terms {
        file = {$plugin.caddy.templates.pdf.invoice}
          // all, item
        marker =
        marker {
          all   = ###CADDY_TERMS###
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
}