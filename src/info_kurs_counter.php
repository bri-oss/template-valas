<?php

include 'utils.php';

try {
  // Define base URL
  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  // Step 1: Load credentials
  [$clientId, $clientSecret] = getCredentials();

  // Step 2: Get access token
  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  // Step 3: Get timestamp
  $timestamp = getTimestamp();

  // Step 4: Sanitize inputs
  $inputs = [
      'dealtCurrency' => '',  // Replace with actual input
      'counterCurrency' => '',  // Replace with actual input
      'partnerCode' => '',  // Replace with actual input
  ];

  $sanitizedInputs = sanitizeInput($inputs);

  $body = [
      'dealtCurrency' => $sanitizedInputs['dealtCurrency'],
      'counterCurrency' => $sanitizedInputs['counterCurrency'],
  ];

  // Step 5: Fetch Valas info kurs counter
  $response = fetchValasInfoKursCounter(
      $clientSecret,
      $baseUrl,
      $accessToken,
      $timestamp,
      $body,
      $sanitizedInputs['partnerCode']
  );

  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
