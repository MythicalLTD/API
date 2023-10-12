<?php
use MythicalSystems\Database\Connect;
use MythicalSystems\Session\SessionManager;
use MythicalSystems\AppConfig;

$appConfig = new AppConfig();
$conn = new Connect();
$conn = $conn->connectToDatabase();
$session = new SessionManager;
$session->authenticateUser();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $pdb = $conn->query("SELECT * FROM problems WHERE id = '".$id. "'")->fetch_array();
        $filename = $appConfig->get('app')['name']."_".$pdb['type']."_Report_".$pdb['id'].".txt";
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: text/plain');
        ob_start();
        echo $appConfig->get('app')['name']." Report - ".$pdb['id']."\r\n";
        echo "Project: ".$pdb['project']."\r\n";
        echo "Type: ".$pdb['type']."\r\n";
        echo "Title: ".$pdb['title']."\r\n";
        echo "Created Date: ".$pdb['date']."\r\n";
        echo "-----------------------------\r\n";
        echo $pdb['message']."\r\n";
        $exportData = ob_get_clean();
        echo $exportData;
        $conn->close();
        die();
    } else {
        header('location: /ui/problems');
    }
} else {
    header('location: /ui/problems');
}

?>