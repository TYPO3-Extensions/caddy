plugin.tx_caddy_pi3 {
  content {
    item {
      10 {
        wrap = <div class="{$plugin.caddy.html.minicaddy.classes.column.0}">|</div>
      }
      20 {
        wrap = <div class="{$plugin.caddy.html.minicaddy.classes.column.1}">|</div>
      }
      30 {
        wrap = <div class="{$plugin.caddy.html.minicaddy.classes.column.2}">|</div>
      }
      wrap = <div class="row collapse item minicaddyitem">|</div>
    }
    sum {
      10 {
        wrap = <div class="{$plugin.caddy.html.minicaddy.classes.column.0}">|</div>
      }
      20 {
        wrap = <div class="{$plugin.caddy.html.minicaddy.classes.column.1}">|</div>
      }
      30 {
        wrap = <div class="{$plugin.caddy.html.minicaddy.classes.column.2}">|</div>
      }
      wrap = <div class="row collapse sum minicaddysum">|</div>
    }
  }
    // linktocaddy, linktoshop
  _HTMLMARKER =
  _HTMLMARKER {
      // label, icon. Replaces _HTMLMARKER_LINTOCADDY
    linktocaddy = COA
    linktocaddy {
      wrap = <div class="row"><div class="columns">|</div></div>
    }
    linktoshop {
      wrap = <div class="row"><div class="columns">|</div></div>
    }
  }
}


XXXplugin.tx_caddy_pi3 {
    // linktoshop
  _HTMLMARKER {
    linktoshop >
      // <button ...>label icon</button>
    linktoshop < plugin.tx_caddy_pi1._HTMLMARKER.linktoshop
  }
}
