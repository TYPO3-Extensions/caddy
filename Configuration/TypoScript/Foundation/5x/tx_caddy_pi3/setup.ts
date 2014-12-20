plugin.tx_caddy_pi3 {
  content {
    item {
      10 {
        wrap = <div class="columns large-2"><span class="quantity itemquantity">|</span></div>
      }
      20 {
        wrap  = <div class="columns large-6"><span class="label itemlabel">|</span></div>
      }
      30 {
        wrap = <div class="columns large-2 caddy-right"><span class="gross itemgross">|</span></div>
      }
      wrap = <div class="row"><div class="item minicaddyitem">|</div></div>
    }
      // quantity, label, gross
    sum = COA
    sum {
      10 {
        wrap = <div class="columns large-2"><span class="quantity sumquantity">|</span></div>
      }
      20 {
        wrap  = <div class="columns large-6"><span class="label sumlabel">|</span></div>
      }
      30 {
        wrap = <div class="columns large-2 caddy-right"><span class="gross sumgross">|</span></div>
      }
      wrap = <div class="row"><div class="sum minicaddysum">|</div></div>
    }
  }
    // linktocaddy, linktoshop
  _HTMLMARKER =
  _HTMLMARKER {
      // label, icon. Replaces _HTMLMARKER_LINTOCADDY
    linktocaddy = COA
    linktocaddy {
      wrap = <div class="linktocaddy minicaddylinktocaddy">|</div>
    }
    linktoshop {
      wrap = <div class="linktoshop minicaddylinktoshop">|</div>
    }
  }
}
  // plugin.tx_caddy_pi3
