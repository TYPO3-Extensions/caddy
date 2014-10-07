plugin.tx_caddy_pi1 {
    // gross, max, min, service attributes_[1-3], sku, stockmanagement, stockquantity, table, tax, title
  db =
  db {
    gross                 = {$plugin.caddy.db.price}
    min                   = {$plugin.caddy.db.min}
    max                   = {$plugin.caddy.db.max}
    service_attribute_1   = {$plugin.caddy.db.service_attribute_1}
    service_attribute_2   = {$plugin.caddy.db.service_attribute_2}
    service_attribute_3   = {$plugin.caddy.db.service_attribute_3}
    sku                   = {$plugin.caddy.db.sku}
    stockquantity         = {$plugin.caddy.db.stockquantity}
    stockmanagement       = {$plugin.caddy.db.stockmanagement}
    table                 = {$plugin.caddy.db.table}
    tax                   = {$plugin.caddy.db.tax}
    title                 = {$plugin.caddy.db.title}
    uid                   = uid
  }
}