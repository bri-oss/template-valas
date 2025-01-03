<?php

use BRI\Util\GetAccessToken;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..')->load();

require __DIR__ . '/../../briapi-sdk/autoload.php';

// Load environment variables and validate credentials
function getCredentials(): array {
  $clientId = $_ENV['CONSUMER_KEY'] ?? null;
  $clientSecret = $_ENV['CONSUMER_SECRET'] ?? null;

  if (!$clientId || !$clientSecret) {
      throw new Exception('Missing client credentials in environment variables.');
  }

  return [$clientId, $clientSecret];
}

// Get Access Token
function getAccessToken(string $clientId, string $clientSecret, string $baseUrl): string {
  $getAccessToken = new GetAccessToken();
  $accessToken = $getAccessToken->getBRIAPI($clientId, $clientSecret, $baseUrl);

  if (!$accessToken) {
      throw new Exception('Failed to retrieve access token.');
  }

  return $accessToken;
}

// Get current timestamp in UTC
function getTimestamp(): string {
  $date = new DateTime("now", new DateTimeZone("UTC"));
  return $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';
}

// Sanitize input parameters
function sanitizeInput(array $inputs): array {
  $sanitized = [];
  foreach ($inputs as $key => $value) {
      $sanitized[$key] = filter_var($value, FILTER_SANITIZE_STRING);
      if (empty($sanitized[$key])) {
          throw new Exception("Invalid input parameter for $key");
      }
  }
  return $sanitized;
}

// Fetch Valas Info
function fetchValasInfo(string $clientSecret, string $baseUrl, string $accessToken, string $timestamp, array $body, string $partnerCode): string {
  $valas = new Valas();
  return $valas->infoKursCounter($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

try {
  // Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 1: Load credentials
  [$clientId, $clientSecret] = getCredentials();

  // Step 2: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 3: Get timestamp
  $timestamp = getTimestamp();

  // Step 4: Sanitize inputs
  $inputs = [
      'dealtCurrency' => '',  // Replace with actual input
      'counterCurrency' => '',  // Replace with actual input
      'partnerCode' => '',  // Replace with actual input
  ];

  $sanitizedInputs = sanitizeInput($inputs);

  $body = [
      'dealtCurrency' => $sanitizedInputs['dealtCurrency'],
      'counterCurrency' => $sanitizedInputs['counterCurrency'],
  ];

  // Step 5: Fetch Valas info
  $response = fetchValasInfo(
      $clientSecret,
      $baseUrl,
      $accessToken,
      $timestamp,
      $body,
      $sanitizedInputs['partnerCode']
  );

  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
