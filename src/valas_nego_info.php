<?php

require 'utils.php';

header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('Content-Type: application/json'); // Ensure all responses are JSON

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
