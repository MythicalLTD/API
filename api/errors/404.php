<?php 
include(__DIR__.'/../base.php');
$rsp = array(
    "code" => 404,
    "error" => "The requested resource could not be found on the server.",
    "message" => "We can't find the API request you pointed at. Maybe take a look at our API docs?"
);
http_response_code(404);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>