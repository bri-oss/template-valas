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
 * Validate and sanitize input for file upload
 */
function validateFileUploadInputs(string $path, string $partnerCode): array {
  $fileName = filter_var(pathinfo($path, PATHINFO_FILENAME), FILTER_SANITIZE_STRING);
  $data = filter_var(file_get_contents($path), FILTER_SANITIZE_STRING);
  $base64 = filter_var(base64_encode($data), FILTER_SANITIZE_STRING);

  if (empty($path) || empty($fileName) || empty($data) || empty($base64) || empty($partnerCode)) {
      throw new Exception('Invalid input parameter variables');
  }

  return [$fileName, $base64];
}

/**
 * Perform file upload
 */
function performFileUpload(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    string $partnerCode,
    array $body
): string {
  $valas = new Valas();
  return $valas->uploadUnderlying($clientSecret, $baseUrl, $accessToken, $timestamp, $partnerCode, $body);
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

  // Step 5: Sanitize and validate file upload inputs
  $path = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual file path
  $partnerCode = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual partner code

  list($fileName, $base64) = validateFileUploadInputs($path, $partnerCode);

  // Step 6: Create request body
  $body = ['fileData' => $base64, 'fileName' => $fileName];

  // Step 7: Perform file upload
  $response = performFileUpload($clientSecret, $baseUrl, $accessToken, $timestamp, $partnerCode, $body);

  // Output response
  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
