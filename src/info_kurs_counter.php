<?php

include 'utils.php';

header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('Content-Type: application/json'); // Ensure all responses are JSON

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

  echo htmlspecialchars($response, ENT_QUOTES, 'UTF-8');
}  catch (InvalidArgumentException $e) {
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
