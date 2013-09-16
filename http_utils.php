<?php

/**
 * Checks for if-modified-since and returns 304 (not modified) if the header matches the given timestamp.
 */
function check_modified($timestamp) {
  header("Cache-Control: must-revalidate");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s", $timestamp) . " GMT"); 

  if ((@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $timestamp)) { 
    header("HTTP/1.1 304 Not Modified");
    exit;
  }
}
