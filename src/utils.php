<?php

use BRI\Util\ExecuteCurlRequest;
use BRI\Util\GetAccessToken;
use BRI\Util\PrepareRequest;
use BRI\Valas\Valas;

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..')->load();
require __DIR__ . '/../../briapi-sdk/autoload.php';

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

// Fetch Valas Info
function fetchValasInfoKursCounter(
  string $clientSecret,
  string $baseUrl,
  string $accessToken,
  string $timestamp,
  array $body,
  string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->infoKursCounter($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

// Fetch deal code information
function fetchValasCheckDealCode(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    string $dealCode,
    string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );
  return $valas->checkDealCode($clientSecret, $baseUrl, $accessToken, $timestamp, $dealCode, $partnerCode);
}

/**
 * fetch inquiry limit using Valas service
 */
function fetchValasInquiryLimit(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    string $debitAccount,
    string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->inquiryLimit($clientSecret, $baseUrl, $accessToken, $timestamp, $debitAccount, $partnerCode);
}

/**
 * fetch inquiry transaction using Valas service
 */
function fetchValasInquiryTransaction(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    array $body,
    string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->inquiryTransaction($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

/**
 * fetch transaction Valas Non-Nego
 */
function fetchValasTransactionValasNonNego(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    array $body,
    string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->transactionValasNonNego($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

/**
 * fetch transaction Valas
 */
function fetchValasTransactionValas(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    array $body,
    string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->transactionValas($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}

/**
 * fetch file upload
 */
function fetchValasUploadUnderlying(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    string $partnerCode,
    array $body
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->uploadUnderlying($clientSecret, $baseUrl, $accessToken, $timestamp, $partnerCode, $body);
}

/**
 * fetch VALAS Nego Info operation
 */
function fetchValasNegoInfo(
    string $clientSecret,
    string $baseUrl,
    string $accessToken,
    string $timestamp,
    array $body,
    string $partnerCode
): string {
  $executeCurlRequest = new ExecuteCurlRequest();
  $prepareRequest = new PrepareRequest();

  $valas = new Valas(
    $executeCurlRequest,
    $prepareRequest
  );

  return $valas->valasNegoInfo($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $partnerCode);
}
