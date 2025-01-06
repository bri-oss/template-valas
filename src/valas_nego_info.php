<?php

require 'utils.php';

try {
  list($clientId, $clientSecret) = getCredentials();

  $baseUrl = 'https://sandbox.partner.api.bri.co.id';

  $accessToken = getAccessToken($clientId, $clientSecret, $baseUrl);

  $timestamp = getTimestamp();

  $inputs = [
    'dealtCurrency' => '', // Replace with actual input
    'counterCurrency' => '', // Replace with actual input
    'partnerCode' => '', // Replace with actual input
  ];

  $validatedInputs = sanitizeInput($inputs);

  $body = [
      'dealtCurrency' => $validatedInputs['dealtCurrency'],
      'counterCurrency' => $validatedInputs['counterCurrency']
  ];

  $response = fetchValasNegoInfo($clientSecret, $baseUrl, $accessToken, $timestamp, $body, $validatedInputs['partnerCode']);

  // Output response
  echo $response;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
  exit(1);
}
