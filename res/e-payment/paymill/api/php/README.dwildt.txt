Composer installieren und starten

root@gera:/var/www/typo3.intern/paymill-payment-form-master# apt-get install curl
root@gera:/var/www/typo3.intern/paymill-payment-form-master# curl -sS https://getcomposer.org/installer | php
root@gera:/var/www/typo3.intern/paymill-payment-form-master# php composer.phar install

Tests und Ergebnisse

* 0 EUR: kann gezahlt werden, wird im Dash-Board gebucht
* 10.99 EUR ist OK und wird zu 10,99 EUR im Dashboard
* 10,99 EUR wird zu 10,00 EUR im Dashboard
* 10.03 * 100 wird zu 1002,9999: Workaround: Math.round( 1002,9999 )
* -1 EUR: führt zu einem PHP-Error bei ELV
* Phantasie-Währung führt zu einem PHP-Error
* Maximaler Betrag ist 21.474.836,47 EUR

PHP-ERROR
* caddy/res/e-payment/paymill/api/php/vendor/paymill/paymill/lib/Paymill/Services/PaymillException.php
  * $message wird als array übergeben, string wird erwartet: FATAL ERROR
  * PaymillException.php korrigiert
  * PaymillException.orig.php ist Original-Datei