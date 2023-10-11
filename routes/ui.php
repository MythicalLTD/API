<?php 

$router->add('/ui', function () {
    require("../views/index.php");
});

$router->add('/ui/telemetry', function () {
    require("../views/telemetry.php");
});

$router->add('/ui/problems', function () {
    require("../views/problems.php");
});
?>