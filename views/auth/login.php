<?php
session_start();
$csrf = new MythicalSystems\CSRF;
use MythicalSystems\AppConfig;
use MythicalSystems\CloudFlare\Captcha;
use MythicalSystems\Session\SessionManager;
use MythicalSystems\Database\Connect;
$conn = new Connect();
$session = new SessionManager;
$captcha = new Captcha();
$appConfig = new AppConfig();
$conn = $conn->connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        if ($csrf->validate('login-form')) {
            $ip_address = $session->getIP();
            $cf_turnstile_response = $_POST["cf-turnstile-response"];
            $cf_connecting_ip = $ip_address;
            if ($appConfig->get('cloudflare')['enable'] == false) {
                $captcha_success = 1;
            } else {
                $captcha_success = $captcha->validate_captcha($cf_turnstile_response, $cf_connecting_ip, $appConfig->get('cloudflare')['secret']);
            }
            if ($captcha_success) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                if (!$username == "" || !$password == "") {
                    $query = "SELECT * FROM users WHERE username = '" . $username . "'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_assoc($result);
                            $hashedPassword = $row['password'];
                            if (password_verify($password, $hashedPassword)) {
                                $token = $row['token'];
                                $cookie_name = 'token';
                                $cookie_value = $token;
                                setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60), '/');
                                $conn->close();
                                header('location: /ui');
                                die();
                            } else {
                                header("location: /ui/auth/login?e=Sorry, but the password is wrong.");
                                die();
                            }
                        } else {
                            header("location: /ui/auth/login?e=Sorry, but we can't find this username in the database.");
                            die();
                        }
                    } else {
                        header("location: /ui/auth/login?e=Sorry, but we can't find this username in the database.");
                        die();
                    }
                } else {
                    header("location: /ui/auth/login?e=Please fill in all the required information!");
                    die();
                }
            } else {
                header("location: /ui/auth/login?e=Captcha verification failed; please refresh!");
                die();
            }
        } else {
            header("location: /ui/auth/login?e=CSRF verification failed; please refresh!");
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appConfig->get('app')['name'] ?> - Login</title>
    <link rel="icon" type="image/png" href="<?= $appConfig->get('app')['logo'] ?>">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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
            max-width: 400px;
            background-color: #343a40;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        label {
            font-weight: bold;
            color: #fff;
        }
        form {
            margin-top: 20px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .cf-turnstile {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center"><?= $appConfig->get('app')['name'] ?> Login</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['e'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_GET['e'] . '</div>';
        } 
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
            echo '<div class="alert alert-success" role="alert">' . $_GET['s'] . '</div>';
        }
        ?>
        <form action="/ui/auth/login" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input required type="text" name="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input required type="password" name="password" class="form-control">
            </div>
            <center>
            <?= $csrf->input('login-form'); ?>
            <?php
            if ($appConfig->get('cloudflare')['enable'] == true) {
                ?>
                <div class="cf-turnstile" data-sitekey="<?= $appConfig->get('cloudflare')['key']?>"></div>
                <?php
            }
            ?>
            <button type="submit" name="submit">Login</button></center>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>