<?php 
include(__DIR__.'/../base.php');
$rsp = array(
    "code" => 405,
    "error" => "A request was made of a page using a request method not supported by that page",
    "message" => "Please take a look at the api docs"
);
http_response_code(405);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>