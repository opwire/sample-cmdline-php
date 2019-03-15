<?php

include(dirname(__FILE__) . "/bootstrap.php");
include(dirname(__FILE__) . "/cmdargs.php");

$args = extractArgs();

try {
  // import environment varialbes & input data
  $store = bootstrap($args);

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
} catch (Exception $e) {
  $err_text = $e->getMessage();
  if (isFormatType($args, "output-format", "json")) {
    $err_json = array("name" => "SyntaxError", "message" => $err_text);
    $err_text = json_encode($err_json, JSON_PRETTY_PRINT);
  }
  fwrite(STDERR, $err_text);
  exit(1);
}
?>
