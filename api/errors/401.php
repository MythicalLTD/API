<?php 
include(__DIR__.'/../base.php');
$rsp = array(
    "code" => 401,
    "error" => "The request requires user authentication or the provided credentials are invalid.",
    "message" => "Please make sure to provide your API key."
);
http_response_code(401);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>