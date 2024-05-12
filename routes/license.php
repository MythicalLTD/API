<?php 

$router->add('/license/(.*)/info', function ($license_key) {
    require("../api/license/info.php");
});

?>