<?php

use BRI\Util\GetAccessToken;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..')->load();
require __DIR__ . '/../../briapi-sdk/autoload.php';

/**
 * Get environment variables for client credentials
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
 * Get the current timestamp in UTC format
 */
function getCurrentTimestamp(): string {
  $date = new DateTime("now", new DateTimeZone("UTC"));
  return $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';
}

/**
 * Sanitize and validate input variables
 */
function validateInput(array $inputs): array {
  $sanitizedInputs = [];
  foreach ($inputs as $key => $value) {
      $sanitizedInputs[$key] = filter_var($value, FILTER_SANITIZE_STRING);
      if (empty($sanitizedInputs[$key])) {
          throw new Exception("Invalid input parameter: $key");
      }
  }
  return $sanitizedInputs;
}

/**
 * Perform inquiry limit using Valas service
 */
function performInquiryLimit(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    string $debitAccount,
    string $partnerCode
): string {
  $valas = new Valas();
  return $valas->inquiryLimit($clientSecret, $baseUrl, $accessToken, $timestamp, $debitAccount, $partnerCode);
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

  // Step 5: Sanitize and validate input
  $inputs = [
      'debitAccount' => '',  // Replace with actual input
      'partnerCode' => '',   // Replace with actual input
  ];
  $validatedInputs = validateInput($inputs);

  // Step 6: Perform inquiry limit
  $response = performInquiryLimit(
      $clientSecret,
      $baseUrl,
      $accessToken,
      $timestamp,
      $validatedInputs['debitAccount'],
      $validatedInputs['partnerCode']
  );

  // Output response
  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
