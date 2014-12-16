$.noConflict();

jQuery(document).ready(function($) {
  var formlang = 'de';
  var doc = document;
  var body = $(doc.body);
  function translateForm(language) {
    formlang = language;
    var lang;
    if (translation[language] === undefined) {
      lang = translation['en'];
    } else {
      lang = translation[language];
    }
  }

  $('.card-number').keyup(function() {
    var brand = paymill.cardType($('.card-number').val());
    brand = brand.toLowerCase();
    $(".card-number")[0].className = $(".card-number")[0].className.replace(/paymill-card-number-.*/g, '');
    if (brand !== 'unknown') {
      $('.card-number').addClass("paymill-card-number-" + brand);
    }

    if (brand !== 'maestro') {
      VALIDATE_CVC = true;
    } else {
      VALIDATE_CVC = false;
    }
  });

  $('.card-expiry').keyup(function() {
    if (/^\d\d$/.test($('.card-expiry').val())) {
      text = $('.card-expiry').val();
      $('.card-expiry').val(text += "/");
    }
  });


  function PaymillResponseHandler(error, result) {
    if (error) {
      // Zeigt den Fehler überhalb des Formulars an
      switch (error.apierror)
      {
        case("field_invalid_account_holder"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-holder"]);
          break;
        case("field_invalid_amount"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-amount"]);
          break;
        case("field_invalid_amount_int"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-amount-int"] + ": " + ($('#amount').val() * 100));
          break;
        case("field_invalid_bic"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-bic"]);
          break;
        case("field_invalid_country"):
        case("field_invalid_iban"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-iban"]);
          break;
        case("field_invalid_currency"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-currency"]);
          break;
        case("invalid_payment_data"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid_payment_data"]);
          break;
        case("invalid_public_key"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid_public_key"]);
          break;
        case("unknown_error"):
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["unknown_error"]);
          break;
        default:
          $(".paymill_alert .prompt").text(error.apierror);
          break;
      }
      $(".paymill_alert").css("display", "block");
      $(".submit-button").removeAttr("disabled");
    }
    if (!error) {
      var token = result.token;
      submit(token);
//      $(".paymill_alert").css("display", "none");
//      $(".paymill_alert .prompt").text("");
//      var form = $(".paymill-form");
//      // Token
//      var token = result.token;
//      // Token in das Formular einfügen damit es an den Server übergeben wird
//      form.append("<input type='hidden' name='tx_caddy_pi1[e-payment][paymill][token]' value='" + token + "'/>");
//      // Workflow: Take form by index. 1-
//      //form.get(0).submit();
//      // Workflow: Take form by index. 1+
//      form.get(formIndex).submit();
    }
  }

  function submit(token) {
    $(".paymill_alert").css("display", "none");
    $(".paymill_alert .prompt").text("");
    var form = $(".paymill-form");

    if (token)
    {
      // Token in das Formular einfügen damit es an den Server übergeben wird
      form.append("<input type='hidden' name='tx_caddy_pi1[e-payment][paymill][token]' value='" + token + "'/>");
    }
    form.get(formIndex).submit();
  }

  var formIndex;

  $(".paymill-form").submit(function(event) {
    // Workflow for PaymillResponseHandler(): get the index of the current form. 2+
    var formId = $(this).attr("id");
    //console.debug(formId);
    var formItem = document.getElementById(formId);
    formIndex = $(".paymill-form").index(formItem);
    // Absenden Button deaktivieren um weitere Klicks zu vermeiden
    $('.submit-button').attr("disabled", "disabled");

    // 140301, dwildt, 1-
    //paymenttype = $('.payment-type-active').length ? $('.payment-type-active').attr( 'href' ) : '#payment-type-cc';
    // 140301, dwildt, 1+
    var paymenttype = $('.payment-type-active').attr('href');
    //console.debug(paymenttype);
    var defaultToken = null;
    switch (paymenttype) {
      case "#payment-type-cashinadvance":
      case "#payment-type-cashondelivery":
      case "#payment-type-cashonpickup":
      case "#payment-type-invoice":
        $(".submit-button").removeAttr("disabled");
        submit(defaultToken);
        return false;
      case "#payment-type-cc":
        $('.submit-button').attr("disabled", "disabled");
        if (false == paymill.validateHolder($('#card-holdername').val())) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-card-holdername"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          //console.debug('paymill-cc-elv-iban.js#96');
          return false;
        }
        if (false == paymill.validateCardNumber($('#card-number').val())) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-card-number"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        expiry = $('#card-expiry').val();
        expiry = expiry.split("/");
        if (expiry[1] && (expiry[1].length <= 2)) {
          expiry[1] = '20' + expiry[1];
        }
        if (false == paymill.validateExpiry(expiry[0], expiry[1])) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-card-expiry-date"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if ((false == paymill.validateCvc($('#card-cvc').val()))) {
          if (VALIDATE_CVC) {
            $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-card-cvc"]);
            $(".paymill_alert").css("display", "block");
            $(".submit-button").removeAttr("disabled");
            return false;
          } else {
            $('#card-cvc').val("000");
          }
        }
//console.debug("#amount: " + Math.round($('#amount').text() * 100));
//console.debug("#card-currency: " + $('#card-currency').val());
        params = {
          amount_int: Math.round($('#amount').text() * 100), // E.g. "15" for 0.15 Eur
          currency: $('#card-currency').val(), // ISO 4217 e.g. "EUR"
          number: $('#card-number').val(),
          exp_month: expiry[0],
          exp_year: expiry[1],
          cvc: $('#card-cvc').val(),
          cardholder: $('#card-holdername').val()
        };
        break;
      case "#payment-type-elv":
        $('.submit-button').attr("disabled", "disabled");
        if (false == $('#elv-holder').val()) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-holder"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if (false == paymill.validateAccountNumber($('#elv-account').val())) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-accountnumber"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if (false == paymill.validateBankCode($('#elv-bankcode').val())) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-bankcode"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        params = {
          // dwildt: next 3 lines without any effect
          //amount_int:     $('#amount').val() * 100,  // E.g. "15" for 0.15 Eur
          //amount_int:     Math.round( $('#amount').val() * 100 ),  // E.g. "15" for 0.15 Eur
          //currency:       $('#currency').val(),    // ISO 4217 e.g. "EUR"
          number: $('#elv-account').val(),
          bank: $('#elv-bankcode').val(),
          accountholder: $('#elv-holder').val()
        };
        break;
      case "#payment-type-iban":
        $('.submit-button').attr("disabled", "disabled");
        if (false == $('#elv-iban-holder').val()) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-holder"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if (!validateIBAN($('#elv-iban').val())) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-iban"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if ("" === $('#elv-bic').val()) {
          $(".paymill_alert .prompt").text(translation[formlang]["error"]["invalid-elv-bic"]);
          $(".paymill_alert").css("display", "block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        params = {
          // dwildt: next 3 lines without any effect
          //amount_int:     $('#amount').val() * 100,  // E.g. "15" for 0.15 Eur
          //amount_int:     Math.round( $('#amount').val() * 100 ),  // E.g. "15" for 0.15 Eur
          //currency:       $('#currency').val(),    // ISO 4217 e.g. "EUR"
          iban: $('#elv-iban').val(),
          bic: $('#elv-bic').val(),
          accountholder: $('#elv-iban-holder').val()
        };
        break;
      default:
        $(".payment_errors").text(translation[formlang]["error"]["unknown_payment-method"]);
        $(".payment_errors").css("display", "block");
        $(".submit-button").removeAttr("disabled");
        return false;
        //break;
    }

    try
    {
      paymill.createToken(params, PaymillResponseHandler);
    }
    catch (err)
    {
      $(".paymill_alert .prompt").text(err.message);
      $(".paymill_alert").css("display", "block");
      $(".submit-button").removeAttr("disabled");
      event.preventDefault( ); // Don't execute the click
    }
    return false;
  });

  // Toggle buttons and forms
  $(".payment-type").click(function(event) {
    if ($(this).hasClass('payment-type-active')) {
      return false;
    }
    $(".paymill_alert").css("display", "none");
    $(".paymill_alert .prompt").text("");
    $('.payment-type-active').removeClass('payment-type-active');
    $($(this).attr('href')).addClass('payment-type-active');
  });

  $("#elv-bankcode").bind("paste cut keydown", function(e) {
    var that = this;
    setTimeout(function() {
      paymill.getBankName($(that).val(), function(error, result) {
        error ? logResponse(error.apierror) : $("#elv-bankname").val(result);
      });
    }, 200);
  });

  translateForm(formlang);
  $(".paymill_alert").css("display", "none");

});

function logResponse(res) {
  // create console.log to avoid errors in old IE browsers
  if (!window.console)
    console = {
      log: function() {
      }
    };
  console.log(res);
}

function validateIBAN(iban) {
  return true;
}