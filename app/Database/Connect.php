<?php
namespace MythicalSystems\Database;
use mysqli;
use MythicalSystems\AppConfig;

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

    $conn = new mysqli($dbHost . ':' . $dbPort, $dbUsername, $dbPassword, $dbName);;

    return $conn;
  }
}

?>