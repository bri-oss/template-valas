<?php

include 'utils.php';

try {
  // Step 1: Load credentials
  [$clientId, $clientSecret] = getCredentials();

  // Step 2: Get access token
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 3: Get current timestamp
  $timestamp = getTimestamp();

  // Step 4: Sanitize and validate input
  $inputs = [
      'dealCode' => '',  // Replace with actual input
      'partnerCode' => '',  // Replace with actual input
  ];
  $sanitizedInputs = sanitizeInput($inputs);

  // Step 5: Fetch deal code information
  $response = fetchValasCheckDealCode(
      $clientSecret,
      $baseUrl,
      $accessToken,
      $timestamp,
      $sanitizedInputs['dealCode'],
      $sanitizedInputs['partnerCode']
  );

  echo $response;

} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
