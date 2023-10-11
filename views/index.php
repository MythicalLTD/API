<?php
use MythicalSystems\AppConfig;

$appConfig = new AppConfig();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $appConfig->get('app')['name'] ?> - Index
    </title>
    <link rel="icon" type="image/png" href="<?= $appConfig->get('app')['logo'] ?>">
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add custom dark theme styles -->
    <style>
        body {
            background-color: #121212;
            color: #fff;
        }

        .container {
            margin-top: 50px;
        }

        a {
            color: #fff;
        }

        a:hover {
            text-decoration: none;
        }

        /* Modern design styles */
        .jumbotron {
            background-color: #343a40;
            color: #fff;
            padding: 2rem;
            border-radius: 10px;
        }

        .list-group-item {
            background-color: #212529;
            border: none;
        }

        .list-group-item a {
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- Add a Bootstrap navbar with dark theme -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <?= $appConfig->get('app')['name'] ?>
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to
                <?= $appConfig->get('app')['name'] ?>!
            </h1>
            <p class="lead">Please select the UI page you want to view:</p>
            <ul class="list-group">
                <li class="list-group-item"><a href="/ui/auth/login">Login</a></li>
                <?php if ($appConfig->get('app')['registration'] == true) {
                    ?>
                    <li class="list-group-item"><a href="/ui/auth/register">Register</a></li>
                    <?php
                } ?>
                <li class="list-group-item"><a href="/ui/telemetry">Telemetry</a></li>
                <li class="list-group-item"><a href="/ui/problems">Problems</a></li>
            </ul>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>