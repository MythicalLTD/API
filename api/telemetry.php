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
            if (isset($_GET['action']) && !empty($_GET['action'])) {
                $osName = isset($_GET['osName']) ? mysqli_real_escape_string($conn, $_GET['osName']) : "Unknown";
                $kernelName = isset($_GET['kernelName']) ? mysqli_real_escape_string($conn, $_GET['kernelName']) : "kernelName";
                $cpuArchitecture = isset($_GET['cpuArchitecture']) ? mysqli_real_escape_string($conn, $_GET['cpuArchitecture']) : "cpuArchitecture";
                $osArchitecture = isset($_GET['osArchitecture']) ? mysqli_real_escape_string($conn, $_GET['osArchitecture']) : "osArchitecture";

                $project = mysqli_real_escape_string($conn, $_GET['project']);
                $action = mysqli_real_escape_string($conn, $_GET['action']);
                $authKey = mysqli_real_escape_string($conn, $_GET['authKey']);

                if ($authKey == $appConfig->get('data')['encryptionkey']) {
                    $validProjects = ["mythicaldash", "pterodactyl-desktop", "kosmapanel", "kosmapanel-daemon", "mythicalpics"];

                    if (in_array($project, $validProjects)) {
                        function addtodb($conn, $project, $action, $osName, $kernelName, $cpuArchitecture, $osArchitecture) {
                            $query = "INSERT INTO telemetry (project, action, osName, kernelName, cpuArchitecture, osArchitecture) VALUES (?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssssss", $project, $action, $osName, $kernelName, $cpuArchitecture, $osArchitecture);
                            return $stmt->execute();
                        }

                        if (addtodb($conn, $project, $action, $osName, $kernelName, $cpuArchitecture, $osArchitecture)) {
                            $response = [
                                "code" => 200,
                                "error" => null,
                                "message" => "The telemetry request was added to the database successfully!"
                            ];
                            http_response_code(200);
                        } else {
                            $response = [
                                "code" => 500,
                                "error" => "Server error",
                                "message" => "We apologize, but our server cannot handle this request."
                            ];
                            http_response_code(500);
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
                        "error" => "Unauthorized",
                        "message" => "Please make sure your API key is valid."
                    ];
                    http_response_code(403);
                }
            } else {
                $response = [
                    "code" => 400,
                    "error" => "Bad request syntax",
                    "message" => "Please tell us what action the user performed."
                ];
                http_response_code(400);
            }
        } else {
            $response = [
                "code" => 400,
                "error" => "Bad request syntax",
                "message" => "Please specify the project you want to send data to."
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
