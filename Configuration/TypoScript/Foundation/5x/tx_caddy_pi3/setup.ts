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
  _HTMLMARKER {
    linktocaddy >
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
          ATagParams = class="minicaddy-button button tiny expand"
        }
        noTrimWrap  = || |
      }
      wrap = <div class="row"><div class="columns">|</div></div>
    }
    linktoshop >
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
          ATagParams = class="minicaddy-button button tiny expand"
        }
        noTrimWrap  = || |
      }
      wrap = <div class="row"><div class="columns">|</div></div>
    }
  }
}
