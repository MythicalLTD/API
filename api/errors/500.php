<?php
include(__DIR__.'/../base.php');
$rsp = array(
    "code" => 500,
    "error" => "The server encountered a situation it doesn't know how to handle.",
    "message" => "We are sorry, but our server can't handle this request. Please do not try again!"
);
http_response_code(500);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>