<?php
session_start();
$csrf = new MythicalSystems\CSRF;
use MythicalSystems\AppConfig;
use MythicalSystems\CloudFlare\Captcha;
use MythicalSystems\Database\Connect;
use MythicalSystems\Session\SessionManager;

$session = new SessionManager;
$conn = new Connect();
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
                                header('location: /ui/dashboard');
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
<html>

<head>
    <title>
        <?= $appConfig->get('app')['name'] ?> - Login
    </title>
    <link rel="icon" type="image/png" href="<?= $appConfig->get('app')['logo'] ?>">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>

<body>
    <p>Welcome to
        <?= $appConfig->get('app')['name'] ?>
        !
    </p>
    <h3>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['e'])) {
                echo $_GET['e'];
            }
        }
        ?>
    </h3>
    <form action="/ui/auth/login" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username"><br>
        <label for="password">Password:</label>
        <input type="password" name="password"><br>
        <?= $csrf->input('login-form'); ?>
        <?php
        if ($appConfig->get('cloudflare')['enable'] == true) {
            ?>
            <div class="cf-turnstile" data-sitekey="<?= $appConfig->get('cloudflare')['key'] ?>"></div>
            <?php
        }
        ?>
        <button type="submit" name="submit">Login</button>
    </form>
</body>

</html>