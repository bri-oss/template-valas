<?php

use BRI\Util\GenerateRandomString;
use BRI\Util\GetAccessToken;
use BRI\Util\VarNumber;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..' . '')->load();

require __DIR__ . '/../../briapi-sdk/autoload.php';

$clientId = $_ENV['CONSUMER_KEY']; // customer key
$clientSecret = $_ENV['CONSUMER_SECRET']; // customer secret

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

$getAccessToken = new GetAccessToken();

$accessToken = $getAccessToken->getBRIAPI(
  $clientId,
  $clientSecret,
  $baseUrl
);

$valas = new Valas();

$date = new DateTime("now", new DateTimeZone("UTC"));

$timestamp = $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';

$debitAccount = '030702000141509';
$creditAccount = '034401083104504';
$debitCurrency = 'USD';
$creditCurrency = 'IDR';
$remark = (new GenerateRandomString())->generate(9);
$partnerReferenceNo = (string) (new VarNumber())->generateVar(13); //'7278163827131';
$debitAmount = '3.00'; // optional
$partnerCode = 'rxEG1EMYHQZMgb3';

$body = [
  'debitAccount' => $debitAccount,
  'creditAccount' => $creditAccount,
  'debitCurrency' => $debitCurrency,
  'creditCurrency' => $creditCurrency,
  'debitAmount' => $debitAmount,
  'remark' => $remark,
  'partnerReferenceNo' => $partnerReferenceNo
];

$response = $valas->transactionValasNonNego(
  $clientSecret,
  $baseUrl,
  $accessToken,
  $timestamp,
  $body,
  $partnerCode
);

echo $response;
