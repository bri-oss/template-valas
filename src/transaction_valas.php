<?php

require 'utils.php';

use BRI\Util\GenerateRandomString;

try {
  // Step 1: Load client credentials
  [$clientId, $clientSecret] = getCredentials();

  // Step 2: Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 3: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 4: Get timestamp
  $timestamp = getTimestamp();

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

  $validatedInputs = sanitizeInput($inputs);

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
  $response = fetchValasTransactionValas(
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
