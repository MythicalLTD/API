<?php

namespace MythicalSystems;

use MythicalSystems\Api\ResponseHandler as rh;
use MythicalSystems\Api\Api as api;
use MythicalSystems\Database\Connect;
use MythicalSystems\Utils\EncryptionHandler as eh;


use MythicalSystems\Main;
use Exception;
api::init();
$conn = new Connect();
$conn = $conn->connectToDatabase();
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = file_get_contents('php://input');
    if (isset($input) && !$input == null && !$input == "") {
        $str_key = mysqli_real_escape_string($conn, generateKey(64));
        $str_input = mysqli_real_escape_string($conn,eh::encrypt($input, $str_key));
        try {
            $url = Main::getUrl();
            mysqli_query($conn, "INSERT INTO `logs` (`log`, `mkey`) VALUES ('" . $str_input . "', '" . $str_key . "');");
            rh::sendManualResponse(200, null, "Log has been successfully added to the database!", true, [
                "management_key" => $str_key,
                "api_url" => $url."/log?mkey=" . $str_key,
                "user_url" => $url."/log?mkey=" . $str_key . "&plain"
            ]);
        } catch (Exception $e) {
            rh::InternalServerError("An error occurred while processing your request!");
        }
    } else {
        rh::BadRequest("Please provide a request body!");
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['mkey']) && !empty($_GET['mkey'])) {
        $mkey = mysqli_real_escape_string($conn, $_GET['mkey']);
        $query = "SELECT * FROM logs WHERE mkey = '" . $mkey . "'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if (isset($_GET['plain'])) {
                    echo eh::decrypt($row['log'], $row['mkey']);
                } else {
                    rh::sendManualResponse(200, null, "Log has been successfully retrieved from the database!", true, [
                        "log" => eh::decrypt($row['log'], $row['mkey']),
                        "mkey" => $row['mkey'],
                    ]);
                }
            } else {
                rh::NotFound("Log not found in the database!");
            }
        } else {
            rh::InternalServerError("An error occurred while processing your request!");
        }
    } else {
        rh::BadRequest("Please provide a management key!");
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['mkey']) && !empty($_GET['mkey'])) {
        $mkey = mysqli_real_escape_string($conn, $_GET['mkey']);
        $query = "DELETE FROM logs WHERE mkey = '" . $mkey . "'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_affected_rows($conn) > 0) {
                rh::sendManualResponse(200, null, "Log has been successfully deleted from the database!", true, [
                    "mkey" => $mkey,
                ]);
            } else {
                rh::NotFound("Log not found in the database!");
            }
        } else {
            rh::InternalServerError("An error occurred while processing your request!");
        }
    } else {
        rh::BadRequest("Please provide a management key!");
    }
} else {
    rh::MethodNotAllowed("Please use a PUT/GET/DELETE request to access this endpoint!");
}


function generateKey(int $length = 12): string
{
    if ($length <= 8) {
        throw new \Exception('The length has to be bigger then 8!');
    }
    
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';

    $password = '';

    $password .= substr(str_shuffle($uppercase), 0, 1);
    $password .= substr(str_shuffle($lowercase), 0, 1);
    $password .= substr(str_shuffle($numbers), 0, 1);
    $password .= substr(str_shuffle($uppercase . $lowercase . $numbers), 0, $length - 4);

    $password = str_shuffle($password);
    
    return (string)$password;
}