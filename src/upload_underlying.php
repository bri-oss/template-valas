<?php

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

  $path = filter_var('', FILTER_SANITIZE_STRING); // assets/image.png
  $fileName = filter_var(pathinfo($path, PATHINFO_FILENAME), FILTER_SANITIZE_STRING);
  $data = filter_var(file_get_contents($path), FILTER_SANITIZE_STRING);
  $base64 = filter_var(base64_encode($data), FILTER_SANITIZE_STRING);
  $partnerCode = filter_var('', FILTER_SANITIZE_STRING);

  if (
    empty($path) || 
    empty($fileName) || 
    empty($data) || 
    empty($base64) ||
    empty($partnerCode)) {
    throw new Exception('Invalid input parameter variables');
  }

  $body = [
    'fileData' => $base64,
    'fileName' => $fileName
  ];

  $valas = new Valas();

  $response = $valas->uploadUnderlying(
    $clientSecret,
    $baseUrl,
    $accessToken,
    $timestamp,
    $partnerCode,
    $body
  );

  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}

