<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/api.ts">

plugin.tx_caddy_pi1 {
    // Constants needed while runtime: getpost
  constant_editor =
  constant_editor {
      // qty
    getpost =
    getpost {
      qty = {$plugin.caddy.getpost.qty}
    }
  }

    // Never remove this property!
  pluginCheck = dummy
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/db.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/debug.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/pdf.ts">

plugin.tx_caddy_pi1 {
    // page id of the caddy
  pid = {$plugin.caddy.pages.caddy}

    // Never remove this property!
  pluginCheck = dummy
}
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/settings.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/Templates/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/css.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:caddy/Configuration/TypoScript/Basis/Plugin/TxCaddyPi1/htmlmarker.ts">

