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
          // default, bootstrap, foundation
        order = CASE
        order {
          key = default
          key = bootstrap
          key = foundation
            // empty: not configured
          default = TEXT
          default {
            value = You are here: plugin.tx_caddy_pi1.templates.html.form.order.default
          }
            // 3x
          bootstrap = COA
          bootstrap {
              // wiSelect, woSelect
            10 = COA
            10 {
              wiSelect = TEXT
              wiSelect {
                value = BOOTSTRAP 3X
              }
              10 < .wiSelect
            }
          }
            // 5x
          foundation = COA
          foundation {
              // wiSelect, woSelect
            5x = CASE
            5x {
              key = wiSelect
                // <form ...>, <fieldset>...</fieldset>, </form>
              wiSelect = COA
              wiSelect {
                  // <form ...>
                10 = COA
                10 {
                    // <form class="{$plugin.caddy.html.intothecaddy.form.class}"
                  10 = TEXT
                  10 {
                    value = <form class="{$plugin.caddy.html.intothecaddy.form.class}"
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
                  stdWrap {
                    wrap {
                      cObject = COA
                      cObject {
                        10 = TEXT
                        10 {
                          if {
                            isTrue = {$plugin.caddy.html.intothecaddy.form.fieldset}
                          }
                          value = <fieldset>|</fieldset>
                        }
                        20 = TEXT
                        20 {
                          if {
                            isTrue  = {$plugin.caddy.html.intothecaddy.form.fieldset}
                            negate  = 1
                          }
                          value = |
                        }
                      }
                    }
                  }
                    // <legend>...</legend>
                  10 = TEXT
                  10 {
                    if {
                      isTrue = {$plugin.caddy.html.intothecaddy.form.legend}
                    }
                    data = LLL:EXT:caddy/pi1/locallang.xml:formLegend
                    wrap = <legend>|</legend>
                  }
                    // <input type="hidden" ... />
                  20 = COA
                  20 {
                      // uid of the item
                    10 = TEXT
                    10 {
                      field = {$plugin.caddy.db.table}.uid // uid
                      wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[{$plugin.caddy.url.showUid}]" value="|" />
                    }
                      // quantity of the item
                    11 = TEXT
                    11 {
                      value = {$plugin.caddy.getpost.qty}
                      wrap  = <input type="hidden" name="|" value="1" />
                    }
                      // service_attribute
                    20 = COA
                    20 {
                        // local table ...
                      10 = COA
                      10 {
                          // service_attribute_1
                        10 = TEXT
                        10 {
                          if {
                            isTrue {
                              field = {$plugin.caddy.db.table}.{$plugin.caddy.db.service_attribute_1}
                            }
                          }
                          field = {$plugin.caddy.db.table}.{$plugin.caddy.db.service_attribute_1}
                          wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[service_attribute_1]" value="|" />
                        }
                          // service_attribute_2
                        20 = TEXT
                        20 {
                          if {
                            isTrue {
                              field = {$plugin.caddy.db.table}.{$plugin.caddy.db.service_attribute_2}
                            }
                          }
                          field = {$plugin.caddy.db.table}.{$plugin.caddy.db.service_attribute_2}
                          wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[service_attribute_2]" value="|" />
                        }
                          // service_attribute_3
                        30 = TEXT
                        30 {
                          if {
                            isTrue {
                              field = {$plugin.caddy.db.table}.{$plugin.caddy.db.service_attribute_3}
                            }
                          }
                          field = {$plugin.caddy.db.table}.{$plugin.caddy.db.service_attribute_3}
                          wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[service_attribute_3]" value="|" />
                        }
                      }
                        // ... or foreign table
                      20 = COA
                      20 {
                          // service_attribute_1
                        10 = TEXT
                        10 {
                          if {
                            isTrue {
                              field = {$plugin.caddy.db.service_attribute_1}
                            }
                          }
                          field = {$plugin.caddy.db.service_attribute_1}
                          wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[service_attribute_1]" value="|" />
                        }
                          // service_attribute_2
                        20 = TEXT
                        20 {
                          if {
                            isTrue {
                              field = {$plugin.caddy.db.service_attribute_2}
                            }
                          }
                          field = {$plugin.caddy.db.service_attribute_2}
                          wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[service_attribute_2]" value="|" />
                        }
                          // service_attribute_3
                        30 = TEXT
                        30 {
                          if {
                            isTrue {
                              field = {$plugin.caddy.db.service_attribute_3}
                            }
                          }
                          field = {$plugin.caddy.db.service_attribute_3}
                          wrap  = <input type="hidden" name="{$plugin.caddy.url.extension}[service_attribute_3]" value="|" />
                        }
                      }
                    }
                  }
                    // select and button
                  30 = COA
                  30 {
                      // foundation row
                    wrap = {$plugin.caddy.html.intothecaddy.form.wrap.selectbutton}
                      // <select>...</select>
                    10 = TEXT
                    10 {
                      if {
                        isTrue = {$plugin.caddy.html.intothecaddy.form.select}
                      }
                      value (
                        <select name="{$plugin.caddy.getpost.qty}" class="{$plugin.caddy.html.intothecaddy.form.select.class}" size="1">
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
                      wrap = {$plugin.caddy.html.intothecaddy.form.wrap.select}
                    }
                      // <button>...</button>
                    90 = COA
                    90 {
                        // <button>...</button>
                      wrap = {$plugin.caddy.html.intothecaddy.form.wrap.button}
                      10 = TEXT
                      10 {
                        data  = LLL:EXT:caddy/pi1/locallang.xml:formButton
                        wrap  = <button class="{$plugin.caddy.html.intothecaddy.button.classes.wiselect}" role="button ">|</button>
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
                    90 {
                      wrap >
                      10 {
                        wrap  = <button class="{$plugin.caddy.html.intothecaddy.button.classes.woselect}" role="button ">|</button>
                      }
                    }
                    wrap >
                  }
                }
              }
            }
            10 < .5x
          }
        }
      }
    }
  }
}