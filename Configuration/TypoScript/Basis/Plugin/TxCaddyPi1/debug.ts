plugin.tx_caddy_pi1 {
    // dontReplaceEmptyMarker, paramsAndTs, userfunc
  debug  =
  debug {
      // [Boolean] Debug params/typoscript (frontend): Get a report in the frontend with received GET-/POST-parameters and typoscript configuration
    dontReplaceEmptyMarker  = {$plugin.caddy.debug.dontReplaceEmptyMarker}
      // [Boolean] Debug user functions (backend): Enable the DRS for user functions
    paramsAndTs             = {$plugin.caddy.debug.paramsAndTs}
      // [Boolean] Don't replace empty marker: Enable it, if empty HTML markers should not removed.
    userfunc                = {$plugin.caddy.debug.userfunc}
  }
}