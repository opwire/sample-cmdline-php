<?php

$args = extractArgs();

// import environment varialbes & input data
$map = bootstrap($args);
if ($map["error"] != null) {
  $err_text = $map["error"];
  if (isFormatType($args, "output-format", "json")) {
    $err_json = array("name" => "SyntaxError", "message" => $err_text);
    $err_text = json_encode($err_json, JSON_PRETTY_PRINT);
  }
  fwrite(STDERR, $err_text);
  exit(1);
}
$store = $map["store"];

// body of program: processing something here
// .....

// supposes the store is output...
if (isFormatType($args, "output-format", "json")) {
  echo json_encode($store, JSON_PRETTY_PRINT);
} else {
  foreach(array_keys($store) as $key) {
    printf("%s:\n", $key);
    if (is_string($store[$key])) {
      echo $store[$key];
    } else {
      echo json_encode($store[$key], JSON_PRETTY_PRINT);
    }
    printf("\n\n");
  }
}

// -------------------------------------------------------- local functions

function bootstrap($args) {
  $store = array();

  foreach (array("OPWIRE_REQUEST", "OPWIRE_SETTING") as $envName) {
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
    if (isFormatType($args, "input-format", "json")) {
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
    }
  }

  return array("error" => $error, "store" => $store);
}

function isFormatType($args, $format, $type) {
  return array_key_exists($format, $args) && $args[$format] == $type;
}

function extractArgs() {
  $raw_args = parseArgs();
  $args = array();
  $format = "json";
  if (array_key_exists("format", $raw_args)) {
    $format = $raw_args["format"];
  }
  foreach (array("input-format", "output-format") as $arg_key) {
    $args[$arg_key] = $format;
    if (array_key_exists($arg_key, $raw_args)) {
      $args[$arg_key] = $raw_args[$arg_key];
    }
  }
  return $args;
}

function parseArgs(&$default_args = null) {
  if (is_string($default_args)) {
    $argv = explode(' ', $default_args);
  } else if (is_array($default_args)) {
    $argv = $default_args;
  } else {
    global $argv;
    if (isset($argv) && count($argv) > 1) {
      array_shift($argv);
    }
  }
  $_ARG = array();
  foreach ($argv as $arg) {
    if (ereg('--([^=]+)=(.*)',$arg,$reg)) {
      $_ARG[$reg[1]] = $reg[2];
    } elseif(ereg('-([a-zA-Z0-9])',$arg,$reg)) {
      $_ARG[$reg[1]] = 'true';
    }
  }
  return $_ARG;
}
?>
