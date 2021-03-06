var translation = new Object();

//German
translation["de"] = new Object();
//Error
translation["de"]["error"] = new Object();
translation["de"]["error"]["invalid-amount"]              = 'Systemfehler: Es fehlt das Feld mit der ID #amount.';
translation["de"]["error"]["invalid-amount-int"]          = 'Der Wert ist kein Integer';
translation["de"]["error"]["invalid-card-number"]         = 'Ungültige Kartennummer';
translation["de"]["error"]["invalid-card-cvc"]            = 'Ungültige CVC.';
translation["de"]["error"]["invalid-card-expiry-date"]    = 'Ungültiges Gültigkeitsdatum';
translation["de"]["error"]["invalid-card-holdername"]     = 'Bitte geben Sie den korrekten Karteninhaber an.';
translation["de"]["error"]["invalid-currency"]            = 'Bitte geben Sie eine korrekte Währung an.';
translation["de"]["error"]["invalid-elv-accountnumber"]   = 'Bitte geben Sie eine gültige Kontonummer an.';
translation["de"]["error"]["invalid-elv-bankcode"]        = 'Bitte geben Sie eine gültige BLZ ein.';
translation["de"]["error"]["invalid-elv-bic"]             = 'Bitte geben Sie eine gültige BIC ein.';
translation["de"]["error"]["invalid-elv-holder"]          = 'Bitte geben Sie den korrekten Kontoinhaber an.';
translation["de"]["error"]["invalid-elv-iban"]            = 'Bitte geben Sie eine gültige IBAN ein.';
translation["de"]["error"]["invalid-elv-iban-holder"]     = 'Bitte geben Sie den korrekten Kontoinhaber an.';
translation["de"]["error"]["invalid_payment_data"]        = 'Zahlmethode wird nicht akzeptiert. Möglicherweise ist der Betrag zu klein.';
translation["de"]["error"]["invalid_public_key"]          = 'Dem Formular fehlt der PAYMILL_PUBLIC_KEY. Der PAYMILL_PUBLIC_KEY wird hier eingerichtet: TYPO3 Constant Editor > Kategorie [CADDY - E-PAYMENT - PAYMILL]. Berichte diesen Fehler bitte Deinem TYPO3-Integrator.';
translation["de"]["error"]["payment_not_testdata"]        = 'Bei den Daten handelt es sich leider nicht um die definierten Testdaten. Bitte geben Sie die richtigen Testdaten an oder wählen Sie eine andere Zahlungsmethode.';
translation["de"]["error"]["unknown_error"]               = 'Unbekannter Fehler: Möglicherweise sind Ihre Kontodaten falsch. Oder es fehlt im Formular der Paymill-Public-API-Key.';
translation["de"]["error"]["unknown_payment-method"]      = 'System-Fehler: Die gewählte Zahlungsmethode ist nicht definiert. ';

//English
translation["en"] = new Object();
//Error
translation["en"]["error"] = new Object();
translation["en"]["error"]["invalid-amount"]              = 'System error: A field with the id #amount is missing.';
translation["en"]["error"]["invalid-amount-int"]          = 'Value isn\'t an integer';
translation["en"]["error"]["invalid-card-number"]         = 'Invalid card number.';
translation["en"]["error"]["invalid-card-cvc"]            = 'Invalid CVC.';
translation["en"]["error"]["invalid-card-expiry-date"]    = 'Invalid expire date.';
translation["en"]["error"]["invalid-card-holdername"]     = 'Please enter the proper name of the card holder.';
translation["en"]["error"]["invalid-currency"]            = 'Please enter a proper currency.';
translation["en"]["error"]["invalid-elv-accountnumber"]   = 'Please enter a valid account number.';
translation["en"]["error"]["invalid-elv-bankcode"]        = 'Please enter a valid bank code.';
translation["en"]["error"]["invalid-elv-bic"]             = 'Please enter a valid BIC.';
translation["en"]["error"]["invalid-elv-holder"]          = 'Please enter the proper name of the account holder.';
translation["en"]["error"]["invalid-elv-iban"]            = 'Please enter a valid IBAN.';
translation["en"]["error"]["invalid-elv-iban-holder"]     = 'Please enter the proper name of the account holder.';
translation["en"]["error"]["invalid_payment_data"]        = 'Payment method won\'t accepted. Maybe the amount is to less.';
translation["en"]["error"]["invalid_public_key"]          = 'The form is without the PAYMILL_PUBLIC_KEY. You can configure the PAYMILL_PUBLIC_KEY at the TYPO3 Constant Editor > Kategorie [CADDY - E-PAYMENT - PAYMILL]. Please report this bug to your TYPO3 integrator.';
translation["en"]["error"]["payment_not_testdata"]        = 'The given data aren\'t the determined test data. Please enter the proper data or choose a different payment method.';
translation["en"]["error"]["unknown_error"]               = 'Undefined error: Maybe your account data aren\'t proper. Maybe the Paymill public API key is missing in the form.';
translation["en"]["error"]["unknown_payment-method"]      = 'Fatal error: The selected payment method isn\'t defined.';
