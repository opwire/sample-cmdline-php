<?php

function bootstrap($args) {
  $store = array();

  foreach (array("OPWIRE_EDITION", "OPWIRE_REQUEST", "OPWIRE_SETTING") as $envName) {
    if (array_key_exists($envName, $_SERVER)) {
      $data = $_SERVER[$envName];
      if (is_string($data) && strlen($data) > 0) {
        $store[$envName] = json_decode($data, true);
      }
    }
  }

  $input = "";
  while($line = fgets(STDIN)) {
    $input .= $line;
  }

  if (is_string($input) && strlen($input) > 0) {
    $store["input"] = $input;
    if (array_key_exists("input-format", $args) && $args["input-format"] == "json") {
      $inputJSON = json_decode($input, true);
      $error = null;
      switch (json_last_error()) {
        case JSON_ERROR_NONE:
          $store["input"] = $inputJSON;
        break;
        case JSON_ERROR_DEPTH:
          $error = "Maximum stack depth exceeded";
        break;
        case JSON_ERROR_STATE_MISMATCH:
          $error = "Underflow or the modes mismatch";
        break;
        case JSON_ERROR_CTRL_CHAR:
          $error = "Unexpected control character found";
        break;
        case JSON_ERROR_SYNTAX:
          $error = "Syntax error, malformed JSON";
        break;
        case JSON_ERROR_UTF8:
          $error = "Malformed UTF-8 characters";
        break;
        default:
          $error = "Unknown error";
        break;
      }
      if ($error != null) {
        throw new Exception($error);
      }
    }
  }

  return $store;
}
?>
