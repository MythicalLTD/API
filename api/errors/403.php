<?php 
include(__DIR__.'/../base.php');
$rsp = array(
    "code" => 403,
    "error" => "The server understood the request, but it refuses to authorize it.",
    "message" => "Please make sure that this API key works and is valid.."
);
http_response_code(403);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>