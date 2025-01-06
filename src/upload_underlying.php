<?php

require 'utils.php';

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
  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
