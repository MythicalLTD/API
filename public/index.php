<?php

try {
    if (file_exists('../vendor/autoload.php')) {
        require("../vendor/autoload.php");
    } else {
        die('Hello, it looks like you did not run:  "<code>composer install --no-dev --optimize-autoloader</code>". Please run that and refresh the page');
    }
} catch (Exception $e) {
    die('Hello, it looks like our panel does not like some composer packages. Please report this error on our Discord.:  <code>' . $e . '</code>');
}
$router = new \Router\Router();

include(__DIR__."/../routes/base.php");
include(__DIR__."/../routes/auth.php");
include(__DIR__."/../routes/projects.php");
include(__DIR__."/../routes/ui.php");

$router->add("/(.*)", function () {
    require("../api/errors/404.php");
});

try {
    $router->route();  
} catch (Exception $ex) {
    $rsp = array(
        "code" => 500,
        "error" => "The server encountered a situation it doesn't know how to handle.",
        "message" => $ex
    );
    http_response_code(500);
    die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
?>