<?php

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
 * Validate and sanitize input for VALAS Nego Info
 */
function validateValasNegoInputs(string $dealtCurrency, string $counterCurrency, string $partnerCode): array {
  $dealtCurrency = filter_var($dealtCurrency, FILTER_SANITIZE_STRING);
  $counterCurrency = filter_var($counterCurrency, FILTER_SANITIZE_STRING);
  $partnerCode = filter_var($partnerCode, FILTER_SANITIZE_STRING);

  if (empty($dealtCurrency) || empty($counterCurrency) || empty($partnerCode)) {
      throw new Exception('Invalid input parameter variables');
  }

  return [$dealtCurrency, $counterCurrency, $partnerCode];
}

/**
 * Perform VALAS Nego Info operation
 */
function performValasNegoInfo(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    array $body,
    string $partnerCode
): string {
  $valas = new Valas();
  return $valas->valasNegoInfo($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

try {
  // Step 1: Load client credentials
  list($clientId, $clientSecret) = getClientCredentials();

  // Step 2: Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 3: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 4: Get timestamp
  $timestamp = getCurrentTimestamp();

  // Step 5: Sanitize and validate input for VALAS Nego Info
  $dealtCurrency = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual input
  $counterCurrency = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual input
  $partnerCode = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual input

  list($dealtCurrency, $counterCurrency, $partnerCode) = validateValasNegoInputs($dealtCurrency, $counterCurrency, $partnerCode);

  // Step 6: Create request body
  $body = [
      'dealtCurrency' => $dealtCurrency,
      'counterCurrency' => $counterCurrency
  ];

  // Step 7: Perform VALAS Nego Info operation
  $response = performValasNegoInfo($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);

  // Output response
  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
