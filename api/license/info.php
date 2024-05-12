<?php
use MythicalSystems\Database\Connect;

include(__DIR__ . '/../base.php');
$conn = new Connect();
$conn = $conn->connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($license_key) && !$license_key == null) {
        define("LICENSE_KEY", mysqli_real_escape_string($conn, $license_key));
        $query = "SELECT * FROM licenses WHERE licensekey = '" . LICENSE_KEY . "'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $expireDate = strtotime($row['registered_date_expire']);
                $currentDate = strtotime(date('Y-m-d H:i:s'));

                if ($currentDate < $expireDate) {
                    http_response_code(200);
                    $response = array(
                        'code' => 200,
                        'error' => null,
                        'status' => 'success',
                        'message' => 'License is valid',
                        'license_info' => array(
                            'license_id' => $row['id'],
                            'key' => $row['licensekey'],
                            'name' => $row['registered_name'],
                            'url' => $row['url'],
                            'email' => $row['registered_support_email'],
                            'date_registered' => $row['registered_date_registered'],
                            'date_expire' => $row['registered_date_expire'],
                        )
                    );
                    $conn->close();
                    die(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                } else {
                    http_response_code(403);
                    $response = array(
                        'code' => 403,
                        "error" => "The server understood the request, but it refuses to authorize it.",
                        'message' => 'License has expired'
                    );
                    $conn->close();
                    die(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }
            } else {
                http_response_code(404);
                $response = array(
                    'code' => 404,
                    "error" => "The requested resource could not be found on the server.",
                    'message' => 'Invalid license key'
                );
                $conn->close();
                die(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        } else {
            http_response_code(500);
            $response = array(
                'code' => 500,
                "error" => "The server encountered a situation it doesn't know how to handle.",
                'message' => 'Error executing the query'
            );
            $conn->close();
            die(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    } else {
        $rsp = array(
            "code" => 401,
            "error" => "The request requires user authentication or the provided credentials are invalid.",
            "message" => "Please provide an license key."
        );
        http_response_code(401);
        die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
} else {
    $rsp = array(
        "code" => 405,
        "error" => "A request was made of a page using a request method not supported by that page",
        "message" => "Please use a GET request!"
    );
    http_response_code(405);
    die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
?>