<?php

use BRI\Util\GetAccessToken;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..' . '')->load();

require __DIR__ . '/../../briapi-sdk/autoload.php';

$clientId = $_ENV['CONSUMER_KEY']; // customer key
$clientSecret = $_ENV['CONSUMER_SECRET']; // customer secret

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

// change variables accordingly
$partnerId = 'feedloop'; //partner id
$channelId = '12345'; // channel id

$getAccessToken = new GetAccessToken();

$accessToken = $getAccessToken->getBRIAPI(
  $clientId,
  $clientSecret,
  $baseUrl
);

$valas = new Valas();

$date = new DateTime("now", new DateTimeZone("UTC"));

$timestamp = $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';

$debitAccount = '';
$creditAccount = '';
$debitCurrency = '';
$creditCurrency = '';
$remark = '';
$partnerReferenceNo = '';
$debitAmount = ''; // optional
$partnerCode = '';

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
