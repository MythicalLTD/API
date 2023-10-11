<?php
namespace MythicalSystems\Session;
use MythicalSystems\Database\Connect;
use MythicalSystems\Encryption;

$encryption = new Encryption();
class SessionManager
{
    private $db;
    private $encryption; 

    public function __construct()
    {
        $connect = new Connect();
        $this->db = $connect->connectToDatabase();
        $this->encryption = new Encryption(); 
    }
    public function authenticateUser()
    {
        if (isset($_COOKIE['token'])) {
            $session_id = $_COOKIE['token'];
            $query = "SELECT * FROM users WHERE token='" . $session_id . "'";
            $result = mysqli_query($this->db, $query);

            if (mysqli_num_rows($result) > 0) {
                session_start();
                $userdbd = $this->db->query("SELECT * FROM users WHERE token='$session_id'")->fetch_array();
                $_SESSION["token"] = $session_id;
                $_SESSION['loggedin'] = true;
            } else {
                $this->redirectToLogin();
            }
        } else {
            $this->redirectToLogin();
        }
    }

    public function getUserInfo($info)
    {
        $session_id = $_COOKIE["token"];
        $safeInfo = $this->db->real_escape_string($info);
        $query = "SELECT `$safeInfo` FROM users WHERE token='$session_id' LIMIT 1";
        $result = $this->db->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$info];
        } else {
            return null; // User or data not found
        }
    }

    private function redirectToLogin()
    {
        $this->deleteCookies();
        header('location: /ui/auth/login');
        die();
    }

    private function deleteCookies()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
    }
    public function createUser($username, $password, $token)
    {
        $query = "INSERT INTO users (username, password, token) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "sss",
            $username,
            $password,
            $token,
        );
        return $stmt->execute();
    }

    public function generatePassword($length = 12) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = "";
        
        $charArrayLength = strlen($chars) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, $charArrayLength)];
        }
        
        return $password;
    }

    public function generate_key($username, $password) {
        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);
        $encoded_email = base64_encode($username);
        $encoded_password = password_hash($password, PASSWORD_DEFAULT);
        $generated_password = $this->generatePassword(12);

        $key = "mythicalsystems_" . base64_encode($formatted_timestamp . $encoded_email . $encoded_password . $generated_password);
        return $key;
    }
    
    public function generate_keynoinfo() {
        $timestamp = time();
        $formatted_timestamp = date("HisdmY", $timestamp);
        $generated_password = $this->generatePassword(12);
        $key = "mythicalsystems_" . base64_encode($formatted_timestamp . $generated_password);
        return $key;
    }

    public function __destruct()
    {
        $this->db->close();
    }
    function getIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
    
}
?>