<?php

use BRI\Util\GenerateRandomString;
use BRI\Util\GetAccessToken;
use BRI\Util\VarNumber;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..' . '')->load();

require __DIR__ . '/../../briapi-sdk/autoload.php';

$clientId = $_ENV['CONSUMER_KEY'] ?? null; // customer key
$clientSecret = $_ENV['CONSUMER_SECRET'] ?? null; // customer secret

if (!$clientId || !$clientSecret) {
  die('Missing client credentials in environment variables.');
}

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

try {
  $getAccessToken = new GetAccessToken();

  $accessToken = $getAccessToken->getBRIAPI(
    $clientId,
    $clientSecret,
    $baseUrl
  );

  if (!$accessToken) {
    throw new Exception('Failed to retrieve access token.');
  }

  $date = new DateTime("now", new DateTimeZone("UTC"));

  $timestamp = $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';

  $debitAccount = filter_var('', FILTER_SANITIZE_STRING);
  $creditAccount = filter_var('', FILTER_SANITIZE_STRING);
  $debitCurrency = filter_var('', FILTER_SANITIZE_STRING);
  $creditCurrency = filter_var('', FILTER_SANITIZE_STRING);
  $remark = filter_var((new GenerateRandomString())->generate(9), FILTER_SANITIZE_STRING);
  $partnerReferenceNo = filter_var((string) (new VarNumber())->generateVar(13), FILTER_SANITIZE_STRING); //'7278163827131';
  $debitAmount = filter_var('', FILTER_SANITIZE_STRING); // optional
  $partnerCode = filter_var('', FILTER_SANITIZE_STRING);

  if (
    empty($debitAccount) || 
    empty($creditAccount) || 
    empty($debitCurrency) || 
    empty($creditCurrency) || 
    empty($remark) || 
    empty($partnerReferenceNo) || 
    empty($debitAmount) || 
    empty($partnerCode)) {
    throw new Exception('Invalid input parameter variables');
  }

  $body = [
    'debitAccount' => $debitAccount,
    'creditAccount' => $creditAccount,
    'debitCurrency' => $debitCurrency,
    'creditCurrency' => $creditCurrency,
    'debitAmount' => $debitAmount,
    'remark' => $remark,
    'partnerReferenceNo' => $partnerReferenceNo
  ];

  $valas = new Valas();

  $response = $valas->transactionValasNonNego(
    $clientSecret,
    $baseUrl,
    $accessToken,
    $timestamp,
    $body,
    $partnerCode
  );

  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
