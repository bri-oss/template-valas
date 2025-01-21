<?php

require 'utils.php';

header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('Content-Type: application/json'); // Ensure all responses are JSON

try {
  // Step 1: Load client credentials
  list($clientId, $clientSecret) = getCredentials();

  // Step 2: Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 3: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 4: Get timestamp
  $timestamp = getTimestamp();

  // Step 5: Sanitize and validate file upload inputs
  $path = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual file path eg: assets/image.png
  $partnerCode = filter_var('', FILTER_SANITIZE_STRING); // Replace with actual partner code

  list($fileName, $base64) = validateFileUploadInputs($path, $partnerCode);

  // Step 6: Create request body
  $body = ['fileData' => $base64, 'fileName' => $fileName];

  // Step 7: Perform file upload
  $response = fetchValasUploadUnderlying($clientSecret, $baseUrl, $accessToken, $timestamp, $partnerCode, $body);

  // Output response
  echo htmlspecialchars($response, ENT_QUOTES, 'UTF-8');
} catch (InvalidArgumentException $e) {
  // Return a generic error message to the client
  http_response_code(400); // Bad Request

  // Log the error for debugging
  error_log('InvalidArgumentException: ' . $e->getMessage());
} catch (RuntimeException $e) {
  // Return a generic error message to the client
  http_response_code(500); // Internal Server Error

  // Log the error for debugging
  error_log('RuntimeException: ' . $e->getMessage());
} catch (Exception $e) {
  // Return a generic error message to the client
  http_response_code(500); // Internal Server Error

  // Log any other unexpected errors for debugging
  error_log('UnexpectedException: ' . $e->getMessage());
}
