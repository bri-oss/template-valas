<?php

use BRI\Util\GenerateRandomString;
use BRI\Util\GetAccessToken;
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
  $dealCode = filter_var('', FILTER_SANITIZE_STRING);
  $remark = filter_var('', FILTER_SANITIZE_STRING);
  $partnerReferenceNo = filter_var((new GenerateRandomString())->generate(13), FILTER_SANITIZE_STRING);
  $underlyingReference = filter_var('', FILTER_SANITIZE_STRING); // optional
  $partnerCode = filter_var('', FILTER_SANITIZE_STRING);

  if (
    empty($debitAccount) || 
    empty($creditAccount) || 
    empty($dealCode) || 
    empty($remark) || 
    empty($partnerReferenceNo) || 
    empty($underlyingReference) || 
    empty($partnerCode)) {
    throw new Exception('Invalid input parameter variables');
  }

  $body = [
    'debitAccount' => $debitAccount,
    'creditAccount' => $creditAccount,
    'dealCode' => $dealCode,
    'remark' => $remark,
    'partnerReferenceNo' => $partnerReferenceNo,
    'underlyingReference' => $underlyingReference
  ];

  $valas = new Valas();

  $response = $valas->transactionValas(
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
