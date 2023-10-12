<?php
use MythicalSystems\Database\Connect;
use MythicalSystems\AppConfig;

$appConfig = new AppConfig();
$conn = new Connect();
$conn = $conn->connectToDatabase();

include('base.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['authKey']) && !empty($_GET['authKey'])) {
        if (isset($_GET['project']) && !empty($_GET['project'])) {
            if (isset($_GET['type']) && !empty($_GET['type'])) {
                if (isset($_GET['title']) && !empty($_GET['title'])) {
                    if (isset($_GET['message']) && !empty($_GET['message'])) {
                        $authKey = mysqli_real_escape_string($conn, $_GET['authKey']);
                        $project = mysqli_real_escape_string($conn, $_GET['project']);
                        $type = mysqli_real_escape_string($conn, $_GET['type']);
                        $title = mysqli_real_escape_string($conn, $_GET['title']);
                        $message = mysqli_real_escape_string($conn, $_GET['message']);

                        if ($authKey == $appConfig->get('data')['encryptionkey']) {
                            $validProjects = ["mythicaldash", "pterodactyl-desktop", "kosmapanel", "kosmapanel-daemon", "mythicalpics"];
                            $validTypes = ["warning", "error", "critical"];

                            if (in_array($project, $validProjects)) {
                                if (in_array($type, $validTypes)) {
                                    $query = "INSERT INTO problems (project, type, title, message) VALUES (?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssss", $project, $type, $title, $message);

                                    if ($stmt->execute()) {
                                        $response = [
                                            "code" => 200,
                                            "error" => null,
                                            "message" => "The problem request was added to the database successfully!"
                                        ];
                                        http_response_code(200);
                                    } else {
                                        $response = [
                                            "code" => 500,
                                            "error" => "The server encountered an error while processing your request.",
                                            "message" => "We apologize, but our server is currently unable to handle this request."
                                        ];
                                        http_response_code(500);
                                    }
                                } else {
                                    $response = [
                                        "code" => 400,
                                        "error" => "Bad request syntax",
                                        "message" => "Please use a valid type: warning, error, or critical."
                                    ];
                                    http_response_code(400);
                                }
                            } else {
                                $response = [
                                    "code" => 400,
                                    "error" => "Bad request syntax",
                                    "message" => "Please specify a valid project."
                                ];
                                http_response_code(400);
                            }
                        } else {
                            $response = [
                                "code" => 403,
                                "error" => "Authorization failed",
                                "message" => "Please ensure your API key is valid."
                            ];
                            http_response_code(403);
                        }
                    } else {
                        $response = [
                            "code" => 400,
                            "error" => "Bad request syntax",
                            "message" => "Please provide a message describing the problem."
                        ];
                        http_response_code(400);
                    }
                } else {
                    $response = [
                        "code" => 400,
                        "error" => "Bad request syntax",
                        "message" => "Please provide a title for the problem."
                    ];
                    http_response_code(400);
                }
            } else {
                $response = [
                    "code" => 400,
                    "error" => "Bad request syntax",
                    "message" => "Please specify the type of problem that occurred."
                ];
                http_response_code(400);
            }
        } else {
            $response = [
                "code" => 400,
                "error" => "Bad request syntax",
                "message" => "Please specify the project to send data to."
            ];
            http_response_code(400);
        }
    } else {
        $response = [
            "code" => 401,
            "error" => "Authentication required",
            "message" => "Please provide your API key."
        ];
        http_response_code(401);
    }
} else {
    $response = [
        "code" => 405,
        "error" => "Method not supported",
        "message" => "Please use a GET request."
    ];
    http_response_code(405);
}

die(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
?>
