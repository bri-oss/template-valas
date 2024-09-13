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

$getAccessToken = new GetAccessToken();

$accessToken = $getAccessToken->getBRIAPI(
  $clientId,
  $clientSecret,
  $baseUrl
);

$valas = new Valas();

$date = new DateTime("now", new DateTimeZone("UTC"));

$timestamp = $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';

$path = 'assets/image.png'; // assets/image.png
$fileName = pathinfo($path, PATHINFO_FILENAME);
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = base64_encode($data);

// echo new CURLFile($base64);
$body = [
  'fileData' => $base64,
  'fileName' => $fileName
];

$partnerCode = 'rxEG1EMYHQZMgb3';

$response = $valas->uploadUnderlying(
  $clientSecret,
  $baseUrl,
  $accessToken,
  $timestamp,
  $partnerCode,
  $body
);

echo $response;
