<?php

define('PAYMILL_API_HOST', 'https://api.paymill.de/v3/');
define('PAYMILL_API_KEY', '1b74c48cfb702d44bd5d3dab47534280');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(realpath(dirname(__FILE__)) . '/paymill-php-3.0.2/lib'),
    get_include_path(),

)));

$token = $_POST['paymillToken'];

if ($token) {
  require 'Paymill/API/CommunicationAbstract.php';
  require 'Paymill/API/Curl.php';
  require 'Paymill/Models/Request/Base.php';
  require 'Paymill/Models/Request/Client.php';
  require 'Paymill/Models/Request/Offer.php';
  require 'Paymill/Models/Request/Payment.php';
  require 'Paymill/Models/Request/Preauthorization.php';
  require 'Paymill/Models/Request/Refund.php';
  require 'Paymill/Models/Request/Subscription.php';
  require 'Paymill/Models/Request/Transaction.php';
  require 'Paymill/Models/Request/Webhook.php';
  require 'Paymill/Models/Response/Base.php';
  require 'Paymill/Models/Response/Client.php';
  require 'Paymill/Models/Response/Error.php';
  require 'Paymill/Models/Response/Offer.php';
  require 'Paymill/Models/Response/Payment.php';
  require 'Paymill/Models/Response/Preauthorization.php';
  require 'Paymill/Models/Response/Refund.php';
  require 'Paymill/Models/Response/Subscription.php';
  require 'Paymill/Models/Response/Transaction.php';
  require 'Paymill/Models/Response/Webhook.php';
  require 'Paymill/Services/PaymillException.php';
  require 'Paymill/Services/ResponseHandler.php';
  require 'Paymill/Request.php';
  $request = new Paymill\Request(PAYMILL_API_KEY);

//  $payment = new Paymill\Models\Request\Payment();
//  $payment->setToken($token);
  $transaction = new Paymill\Models\Request\Transaction();
  $transaction->setAmount(4200) // e.g. "4200" for 42.00 EUR
              ->setCurrency('EUR')
              ->setToken($token)
              ->setDescription('Test Transaction');

  try{
//    $response  = $request->create($payment);
//    $paymentId = $response->getId();
    

    $response = $request->create($transaction);    
    echo "Transaction: ";
    print_r( $response );
  }catch(PaymillException $e){
    //Do something with the error informations below
//    $e->getResponseCode();
//    $e->getStatusCode();
//    $e->getErrorMessage();
    var_export( $e->getResponseCode(), true );
  }

}

?>