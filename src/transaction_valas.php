<?php

use BRI\Util\GenerateRandomString;
use BRI\Util\GetAccessToken;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..')->load();
require __DIR__ . '/../../briapi-sdk/autoload.php';

/**
 * Get client credentials from environment variables
 */
function getClientCredentials(): array {
  $clientId = $_ENV['CONSUMER_KEY'] ?? null;
  $clientSecret = $_ENV['CONSUMER_SECRET'] ?? null;

  if (!$clientId || !$clientSecret) {
      throw new Exception('Missing client credentials in environment variables.');
  }

  return [$clientId, $clientSecret];
}

/**
 * Get access token from BRI API
 */
function getAccessToken(string $clientId, string $clientSecret, string $baseUrl): string {
  $getAccessToken = new GetAccessToken();
  $accessToken = $getAccessToken->getBRIAPI($clientId, $clientSecret, $baseUrl);

  if (!$accessToken) {
      throw new Exception('Failed to retrieve access token.');
  }

  return $accessToken;
}

/**
 * Generate current UTC timestamp
 */
function getCurrentTimestamp(): string {
  $date = new DateTime("now", new DateTimeZone("UTC"));
  return $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';
}

/**
 * Validate and sanitize input parameters
 */
function validateInput(array $inputs): array {
  $validatedInputs = [];
  foreach ($inputs as $key => $value) {
      $validatedInputs[$key] = filter_var($value, FILTER_SANITIZE_STRING);
      if (empty($validatedInputs[$key])) {
          throw new Exception("Invalid input parameter: $key");
      }
  }
  return $validatedInputs;
}

/**
 * Perform transaction Valas
 */
function performTransactionValas(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    array $body,
    string $partnerCode
): string {
  $valas = new Valas();
  return $valas->transactionValas($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

try {
  // Step 1: Load client credentials
  [$clientId, $clientSecret] = getClientCredentials();

  // Step 2: Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 3: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 4: Get timestamp
  $timestamp = getCurrentTimestamp();

  // Step 5: Sanitize and validate input parameters
  $inputs = [
      'debitAccount' => '', // Replace with actual input
      'creditAccount' => '',
      'dealCode' => '',
      'remark' => '',
      'partnerReferenceNo' => (new GenerateRandomString())->generate(13),
      'underlyingReference' => '', // optional
      'partnerCode' => '' // Replace with actual input
  ];

  $validatedInputs = validateInput($inputs);

  // Step 6: Create request body
  $body = [
      'debitAccount' => $validatedInputs['debitAccount'],
      'creditAccount' => $validatedInputs['creditAccount'],
      'dealCode' => $validatedInputs['dealCode'],
      'remark' => $validatedInputs['remark'],
      'partnerReferenceNo' => $validatedInputs['partnerReferenceNo'],
      'underlyingReference' => $validatedInputs['underlyingReference']
  ];

  // Step 7: Perform transaction Valas
  $response = performTransactionValas(
      $clientSecret,
      $baseUrl,
      $accessToken,
      $timestamp,
      $body,
      $validatedInputs['partnerCode']
  );

  // Output response
  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
