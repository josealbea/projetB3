<?php
function send_status($codeHttp) {
  $codes = array(
    200 => "OK",
    201 => "Created",
    204 => "No content",
    400 => "Bad request",
    401 => "Unauthorized",
    404 => "Not found",
    405 => "Method not allowed",
    409 => "Conflict",
    500 => "Internal server error");
  header("HTTP/1.1 $codeHttp ".$codes[$codeHttp]);
}

function exit_error($codeHttp, $message = "") {
  send_status($codeHttp);
  die($message);
}
?>