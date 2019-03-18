<?php

error_reporting(E_ERROR | E_PARSE);

if (!defined('PHP_MAJOR_VERSION')) {
  $version = explode('.', PHP_VERSION);
  define('PHP_MAJOR_VERSION',   $version[0]);
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
    if (PHP_MAJOR_VERSION == 5) {
      if (ereg('--([^=]+)=(.*)',$arg,$reg)) {
        $_ARG[$reg[1]] = $reg[2];
      } elseif(ereg('-([a-zA-Z0-9])',$arg,$reg)) {
        $_ARG[$reg[1]] = 'true';
      }
    } else {
      if (preg_match('--([^=]+)=(.*)',$arg,$reg)) {
        $_ARG[$reg[1]] = $reg[2];
      } elseif(preg_match('-([a-zA-Z0-9])',$arg,$reg)) {
        $_ARG[$reg[1]] = 'true';
      }
    }
  }
  return $_ARG;
}
?>
