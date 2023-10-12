<?php 
include(__DIR__.'/../base.php');
$rsp = array(
    "code" => 400,
    "error" => "The request cannot be fulfilled due to bad syntax",
    "message" => "Please take a look at the api docs"
);
http_response_code(400);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>