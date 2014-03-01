$.noConflict();

jQuery(document).ready(function ($) {
  var formlang = 'de';
  var doc = document;
  var body = $( doc.body );
  function translateForm(language){
    formlang = language;
    var lang;
    if(translation[language] === undefined){
      lang = translation['en'];
    }else{
      lang = translation[language];
    }

    $(".amount-label").text(lang["form"]["amount"]);
    $(".card-number-label").text(lang["form"]["card-number"]);
    $(".card-cvc-label").text(lang["form"]["card-cvc"]);
    $(".card-holdername-label").text(lang["form"]["card-holdername"]);
    $(".card-expiry-label").text(lang["form"]["card-expiry"]);
    $(".currency-label").text(lang["form"]["currency"]);
    $(".elv-account-label").text(lang["form"]["elv-account"]);
    $(".elv-bankcode-label").text(lang["form"]["elv-bankcode"]);
    $(".elv-bankname-label").text(lang["form"]["elv-bankname"]);
    $(".elv-bic-label").text(lang["form"]["elv-bic"]);
    $(".elv-holdername-label").text(lang["form"]["elv-holdername"]);
    $(".elv-iban-label").text(lang["form"]["elv-iban"]);
    $(".elv-iban-holdername-label").text(lang["form"]["elv-iban-holdername"]);
    $("#payment-type-cc").text(lang["form"]["card-paymentname"]);
    $("#payment-type-elv").text(lang["form"]["elv-paymentname"]);
    $("#payment-type-iban").text(lang["form"]["elv-paymentname-iban"]);
    $(".submit-button").text(lang["form"]["submit-button"]);
    $("#tooltip").attr('title', lang["form"]["tooltip"]);
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
    if ( /^\d\d$/.test( $('.card-expiry').val() ) ) {
      text = $('.card-expiry').val();
      $('.card-expiry').val(text += "/");
    }
  });


  function PaymillResponseHandler(error, result) {
    if (error) {
      // Zeigt den Fehler überhalb des Formulars an
      switch( error.apierror )
      {
        case( "field_invalid_account_holder"):
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-holdername"]);
          break;
        case( "field_invalid_amount_int"):
          $(".payment_errors").text(translation[formlang]["error"]["invalid-amount-int"] + ": " + ( $('#amount').val() * 100 ) );
          break;
        case( "field_invalid_bic"):
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-bic"]);
          break;
        case( "field_invalid_country"):
        case( "field_invalid_iban"):
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-iban"]);
          break;
        case( "field_invalid_currency"):
          $(".payment_errors").text(translation[formlang]["error"]["invalid-currency"]);
          break;
        case( "unknown_error"):
          $(".payment_errors").text(translation[formlang]["error"]["unknown_error"]);
          break;
        default:
          $(".payment_errors").text(error.apierror);
          break;
      }
      $(".payment_errors").css("display","block !important");
      console.debug( 'all.html #75' );
    } else {
      $(".payment_errors").css("display","none");
      $(".payment_errors").text("");
      var form = $("#payment-form");
      // Token
      var token = result.token;
      // Token in das Formular einfügen damit es an den Server übergeben wird
      form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
      form.get(0).submit();
    }
    $(".submit-button").removeAttr("disabled");
  }

  $("#payment-form").submit(function (event) {
    // Absenden Button deaktivieren um weitere Klicks zu vermeiden
    $('.submit-button').attr("disabled", "disabled");

    // 140301, dwildt, 1-
    //paymenttype = $('.payment-type-active').length ? $('.payment-type-active').attr( 'href' ) : '#payment-type-cc';
    // 140301, dwildt, 1+
    paymenttype = $('.payment-type-active').attr( 'href' );
    //console.debug(paymenttype);
    switch (paymenttype) {
      case "#payment-type-cashinadvance":
        $('.submit-button').attr("disabled", "disabled");
        //paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      case "#payment-type-cashondelivery":
        $('.submit-button').attr("disabled", "disabled");
        //paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      case "#payment-type-cashonpickup":
        $('.submit-button').attr("disabled", "disabled");
        //paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      case "#payment-type-cc":
        $('.submit-button').attr("disabled", "disabled");
        if (false == paymill.validateHolder($('#card-holdername').val())) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-card-holdername"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if (false == paymill.validateCardNumber($('#card-number').val())) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-card-number"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        expiry = $('#card-expiry').val();
        expiry = expiry.split("/");
        if(expiry[1] && (expiry[1].length <= 2)){
          expiry[1] = '20'+expiry[1];
        }
        if (false == paymill.validateExpiry(expiry[0], expiry[1])) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-card-expiry-date"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if ((false == paymill.validateCvc($('#card-cvc').val()))) {
          if(VALIDATE_CVC){
            $(".payment_errors").text(translation[formlang]["error"]["invalid-card-cvc"]);
            $(".payment_errors").css("display","block");
            $(".submit-button").removeAttr("disabled");
            return false;
          } else {
            $('#card-cvc').val("000");
          }
        }
        params = {
          //amount_int:     $('#amount').val() * 100,  // E.g. "15" for 0.15 Eur
          amount_int:     Math.round( $('#amount').val() * 100 ),  // E.g. "15" for 0.15 Eur
          currency:       $('#currency').val(),    // ISO 4217 e.g. "EUR"
          number:         $('#card-number').val(),
          exp_month:      expiry[0],
          exp_year:       expiry[1],
          cvc:            $('#card-cvc').val(),
          cardholder:     $('#card-holdername').val()
        };
        paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      case "#payment-type-invoice":
        $('.submit-button').attr("disabled", "disabled");
        //paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      case "#payment-type-elv":
        $('.submit-button').attr("disabled", "disabled");
        if (false == $('#elv-holdername').val()) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-holdername"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if (false == paymill.validateAccountNumber($('#elv-account').val())) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-accountnumber"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if (false == paymill.validateBankCode($('#elv-bankcode').val())) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-bankcode"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        params = {
          // dwildt: next 3 lines without any effect
          //amount_int:     $('#amount').val() * 100,  // E.g. "15" for 0.15 Eur
          //amount_int:     Math.round( $('#amount').val() * 100 ),  // E.g. "15" for 0.15 Eur
          //currency:       $('#currency').val(),    // ISO 4217 e.g. "EUR"
          number:         $('#elv-account').val(),
          bank:           $('#elv-bankcode').val(),
          accountholder:  $('#elv-holdername').val()
        };
        paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      case "#payment-type-iban":
        $('.submit-button').attr("disabled", "disabled");
        if (false == $('#elv-iban-holdername').val()) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-holdername"]);
          $(".payment_errors").css("display","block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if ( ! validateIBAN($('#elv-iban').val())) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-iban"]);
          $(".payment_errors").css("display", "inline-block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        if ("" === $('#elv-bic').val()) {
          $(".payment_errors").text(translation[formlang]["error"]["invalid-elv-bic"]);
          $(".payment_errors").css("display", "inline-block");
          $(".submit-button").removeAttr("disabled");
          return false;
        }
        params = {
          // dwildt: next 3 lines without any effect
          //amount_int:     $('#amount').val() * 100,  // E.g. "15" for 0.15 Eur
          //amount_int:     Math.round( $('#amount').val() * 100 ),  // E.g. "15" for 0.15 Eur
          //currency:       $('#currency').val(),    // ISO 4217 e.g. "EUR"
          iban:           $('#elv-iban').val(),
          bic:            $('#elv-bic').val(),
          accountholder:  $('#elv-iban-holdername').val()
        };
        paymill.createToken(params, PaymillResponseHandler);
        return false;
        //break;
      default:
        $(".payment_errors").text(translation[formlang]["error"]["unknown_payment-method"]);
        $(".payment_errors").css("display","block");
        $(".submit-button").removeAttr("disabled");
        return false;
        //break;
    }

//    paymill.createToken(params, PaymillResponseHandler);
//    return false;
  });

  // Toggle buttons and forms
  $(".payment-type").click(function (event) {
    if ($(this).hasClass('payment-type-active')){
      return false;
    }
    $(".payment_errors").text("");
    $('.payment-type-active').removeClass('payment-type-active');
    $($(this).attr( 'href' )).addClass('payment-type-active');
  });
  translateForm(formlang);

  $("#elv-bankcode").bind("paste cut keydown",function(e) {
    var that = this;
    setTimeout(function() {
      paymill.getBankName($(that).val(), function(error, result) {
        error ? logResponse(error.apierror) : $("#elv-bankname").val(result);
      });
    }, 200);
  });

});

function logResponse(res) {
  // create console.log to avoid errors in old IE browsers
  if (!window.console) console = {
    log:function(){}
  };
  console.log(res);
}

function validateIBAN( iban ) {
  return true;
}