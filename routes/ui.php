<?php 

$router->add('/ui', function () {
    require("../views/index.php");
});

$router->add('/ui/dashboard', function () {
    require("../views/dashboard.php");
});
?>