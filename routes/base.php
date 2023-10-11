<?php 

$router->add('/', function () {
    require("../api/main.php");
});

$router->add('/projects/defaultconfig', function () {
    require("../api/projects/config.php");

});

$router->add('/errors/404', function () {
    require("../api/errors/404.php");
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