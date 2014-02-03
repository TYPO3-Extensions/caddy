<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type"
          content="text/html; charset=utf-8"/>
          <?php
          //
          // Please download the Paymill PHP Wrapper using composer.
          // If you don't already use Composer,
          // then you probably should read the installation guide http://getcomposer.org/download/.
          //

        //Change the following constants
          //define('PAYMILL_API_KEY', 'YOUR_API_KEY');
          define('PAYMILL_API_KEY', '1b74c48cfb702d44bd5d3dab47534280');
          //define('CUSTOMER_EMAIL', 'SOME_TEST_EMAIL');
          define('CUSTOMER_EMAIL', 'wildt@die-netzmacher.de');
          require 'vendor/autoload.php';

          if (isset($_POST['paymillToken'])) {
            $service = new Paymill\Request(PAYMILL_API_KEY);
            $client = new Paymill\Models\Request\Client();
            $payment = new Paymill\Models\Request\Payment();
            $transaction = new \Paymill\Models\Request\Transaction();

            try {
              $client->setEmail(CUSTOMER_EMAIL);
              $client->setDescription('This is a Testuser.');
              $clientResponse = $service->create($client);
//var_dump( __FILE__, __LINE__, $client, $clientResponse );

              $payment->setToken($_POST['paymillToken']);
              $payment->setClient($clientResponse->getId());
              $paymentResponse = $service->create($payment);
//var_dump( __FILE__, __LINE__, $payment, $paymentResponse );
              $transaction->setPayment($paymentResponse->getId());
              $transaction->setAmount($_POST['amount'] * 100);
              $transaction->setCurrency($_POST['currency']);
              $transaction->setDescription('Test Transaction');
//var_dump( __FILE__, __LINE__, $transaction );
              $transactionResponse = $service->create($transaction);
//var_dump( __FILE__, __LINE__, $transactionResponse );

              $title = "<h1>We appreciate your purchase!</h1>";
              $result = print_r($transactionResponse, true);
            } catch (\Paymill\Services\PaymillException $e) {
              $title = "<h1>An error has occoured!</h1>";
              $result = print_r($e->getResponseCode(), true) . " <br />" . print_r($e->getResponseCode(), true) . " <br />" . print_r($e->getErrorMessage(), true);
            }
          }
          if (!isset($_POST['paymillToken'])) {
            $title = "<h1>An error has occoured!</h1>";
            $result = "Paymill token is missing!";
          }
          ?>
  </head>
  <body>
    <div>
<?php echo $title ?>

      <h4>Transaction:</h4>
      <pre>
<?php echo $result ?>
      </pre>
    </div>
  </body>
</html>