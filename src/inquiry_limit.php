<?php

include 'utils.php';

try {
  // Step 1: Load client credentials
  [$clientId, $clientSecret] = getCredentials();

  // Step 2: Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 3: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 4: Get timestamp
  $timestamp = getTimestamp();

  // Step 5: Sanitize and validate input
  $inputs = [
      'debitAccount' => '',  // Replace with actual input
      'partnerCode' => '',   // Replace with actual input
  ];

  $validatedInputs = sanitizeInput($inputs);

  // Step 6: Perform inquiry limit
  $response = fetchValasInquiryLimit(
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
