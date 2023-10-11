<?php
use MythicalSystems\AppConfig;

$appConfig = new AppConfig();

?>
<html>

<head>
    <title>
        <?= $appConfig->get('app')['name'] ?> - Index
    </title>
    <link rel="icon" type="image/png" href="<?= $appConfig->get('app')['logo'] ?>">
</head>
<body>
    <div>
        <h1>Welcome to <?= $appConfig->get('app')['name'] ?>!</h1>
        <h2>Please select the UI page you want to view.</h2>
        <p>-> <a href="/ui/dashboard">Dashboard</a></p>
        <p>-> <a href="/ui/auth/login">Login</a></p>
        <p>-> <a href="/ui/auth/register">Register</a></p>
    </div>
</body>
</html>