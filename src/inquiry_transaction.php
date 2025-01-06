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

  // Step 5: Sanitize and validate input parameters
  $inputs = [
      'originalPartnerReferenceNo' => '', // Replace with actual input
      'originalReferenceNo' => '',       // Replace with actual input
      'partnerCode' => ''                // Replace with actual input
  ];

  $validatedInputs = sanitizeInput($inputs);

  // Step 6: Create request body
  $body = [
      'originalPartnerReferenceNo' => $validatedInputs['originalPartnerReferenceNo'],
      'originalReferenceNo' => $validatedInputs['originalReferenceNo']
  ];

  // Step 7: Perform inquiry transaction
  $response = fetchValasInquiryTransaction(
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
