<?php 
include('base.php');
$rsp = array(
    "code" => 200,
    "error" => null,
    "message" => "Hi, and welcome to MythicalSystems main api this is the main path of our API. Make sure to check our docs for the requests you can make!"
);
http_response_code(200);
die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>