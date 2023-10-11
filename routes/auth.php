<?php 

$router->add('/ui/auth/login', function () {
    require("../views/auth/login.php");
});

$router->add('/ui/auth/register', function () {
    require("../views/auth/register.php");
});

$router->add('/ui/auth/logout', function () {
    require("../views/auth/logout.php");
});
?>