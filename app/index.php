<?php
include 'includes/authenticate.class.php';

header("Content-Type:application/json");

$auth = new AuthenticateDevice();

if (isset($_GET['uuid']) && !isset($_GET['cognito_uuid']) && !isset($_GET['deauth_all'])) {
  $response = $auth->authenticateDevice();
  http_response_code(200);
  echo json_encode($response);
}
elseif (isset($_GET['cognito_uuid'])) {
  $response = $auth->deauthDevice();
  if ($response) {
    http_response_code(204);
  }
}
elseif (isset($_GET['deauth_all'])) {
  $response = $auth->deauthAllDevices();
  if ($response) {
    http_response_code(204);
  }
}
else {
  http_response_code(404);
  echo '{ "error":"Endpoint not found."}';
}

?>
