plugin.tx_caddy_pi1 {
    // e-mail, html, pdf
  templates =
  templates {
      // form
    html =
    html {
        // order
      form =
      form {
          // default, foundation
        order =
        order {
            // empty: not configured
          default =
          default {
          }
            // 5x
          foundation =
          foundation {
              // wiSelect, woSelect
            5x =
            5x {
                // <form ...>, <fieldset>...</fieldset>, </form>
              wiSelect = COA
              wiSelect {
                  // <form ...>
                10 = COA
                10 {
                    // <form class="caddy"
                  10 = TEXT
                  10 {
                    value = <form class="caddy"
                    noTrimWrap = || |
                  }
                    // action="..."
                  20 = TEXT
                  20 {
                    noTrimWrap = |action="|" |
                    typolink {
                      parameter = {$plugin.caddy.pages.caddy}
                      returnLast = url
                    }
                  }
                    // method="post">
                  30 = TEXT
                  30 {
                    value = method="post">
                    noTrimWrap = |||
                  }
                }
                  // <fieldset>...</fieldset>
                20 = COA
                20 {
                  wrap = <fieldset>|</fieldset>
                    // <legend>...</legend>
                  10 = TEXT
                  10 {
                    data = LLL:EXT:caddy/pi1/locallang.xml:formLegend
                    wrap = <legend>|</legend>
                  }
                    // <input type="hidden" ... />
                  20 = COA
                  20 {
                      // uid of the item
                    10 = TEXT
                    10 {
                      field = {$plugin.caddy.db.table}.uid
                      wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[{$plugin.caddy.url.showUid}]" value="|" />
                    }
                  }
                    // select and button
                  30 = COA
                  30 {
                      // foundation row
                    wrap = <div class="row collapse">|</div>
                      // <select>...</select>
                    10 = TEXT
                    10 {
                      value (
                        <select name="tx_quickshop_qty" size="1">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                        </select>
)
                        // foundation column
                      wrap = <div class="small-12 large-3 columns">|</div>
                    }
                      // <button>...</button>
                    90 = COA
                    90 {
                        // <button>...</button>
                      wrap = <div class="small-12 large-9 columns">|</div>
                      10 = TEXT
                      10 {
                        data  = LLL:EXT:caddy/pi1/locallang.xml:formButton
                        wrap  = <button class="button postfix" role="button ">|</button>
                      }
                    }
                  }
                }
                  // </form>
                30 = TEXT
                30 {
                  value = </form>
                }
              }
              woSelect < .wiSelect
              woSelect {
                20 {
                  30 {
                    10 >
                    90.wrap >
                    wrap >
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}