<?php
namespace MythicalSystems\Database;
use MythicalSystems\AppConfig;
use PDO;

class Connect
{
  public function connectToDatabase()
  {
    $config = new AppConfig();
    $dbHost = $config->get('database')['host'];
    $dbPort = $config->get('database')['port'];
    $dbUsername = $config->get('database')['username'];
    $dbPassword = $config->get('database')['password'];
    $dbName = $config->get('database')['name'];

    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";

    $conn = new PDO($dsn, $dbUsername, $dbPassword);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
  }
}

?>