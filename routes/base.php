<?php 

$router->add('/', function () {
    require("../api/main.php");
});

$router->add('/projects/defaultconfig', function () {
    require("../api/projects/config.php");

});

$router->add('/telemetry', function () {
    require("../api/telemetry.php");
});

$router->add('/telemetry', function () {
    require("../api/telemetry.php");
});

$router->add('/problem', function () {
    require("../api/problem.php");
});

$router->add('/errors/404', function () {
    require("../api/errors/404.php");
});

$router->add('/errors/405', function () {
    require("../api/errors/405.php");
});

$router->add('/errors/400', function () {
    require("../api/errors/400.php");
});

$router->add('/errors/401', function () {
    require("../api/errors/401.php");
});

$router->add('/errors/403', function () {
    require("../api/errors/403.php");
});

$router->add('/errors/500', function () {
    require("../api/errors/500.php");
});
?>