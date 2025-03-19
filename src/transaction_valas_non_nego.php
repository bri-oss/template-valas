<?php

require 'utils.php';

use BRI\Util\GenerateRandomString;
use BRI\Util\VarNumber;

header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('Content-Type: application/json'); // Ensure all responses are JSON

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
    'creditAccount' => '', // Replace with actual input
    'debitCurrency' => '', // Replace with actual input
    'creditCurrency' => '', // Replace with actual input
    'debitAmount' => '', // Replace with actual input
    'partnerCode' => '', // Replace with actual input
    'remark' => (new GenerateRandomString())->generate(9),
    'partnerReferenceNo' => ''//(string) (new VarNumber())->generateVar(13)
  ];

  $validatedInputs = sanitizeInput($inputs);

  // Step 6: Create request body
  $body = [
    'debitAccount' => $validatedInputs['debitAccount'],
    'creditAccount' => $validatedInputs['creditAccount'],
    'debitCurrency' => $validatedInputs['debitCurrency'],
    'creditCurrency' => $validatedInputs['creditCurrency'],
    'debitAmount' => $validatedInputs['debitAmount'],
    'remark' => $validatedInputs['remark'],
    'partnerReferenceNo' => $validatedInputs['partnerReferenceNo']
  ];

  // Step 7: Perform transaction Valas Non-Nego
  $response = fetchValasTransactionValasNonNego(
    $clientSecret,
    $baseUrl,
    $accessToken,
    $timestamp,
    $body,
    $validatedInputs['partnerCode']
  );

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
