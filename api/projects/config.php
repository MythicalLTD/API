<?php 
include(__DIR__.'/../base.php');
use MythicalSystems\AppConfig;
$appConfig = new AppConfig();

if ($_SERVER['REQUEST_METHOD'] === 'GET') { 
    $rsp = array(
        "code" => 200,
        "error" => null,
        "data" => array(
            "name" => $appConfig->get('app')['name'],
            "logo" => $appConfig->get('app')['logo'],
            "banner" => $appConfig->get('app')['banner'],
            "description" => $appConfig->get('app')['description'],
            "links" => array(
                "website" => $appConfig->get('links')['website'],
                "status" => $appConfig->get('links')['status'],
                "phpmyadmin" => $appConfig->get('links')['phpmyadmin'],
                "discord" => $appConfig->get('links')['discord'],
                "github" => $appConfig->get('links')['github'],
                "paypal" => $appConfig->get('links')['paypal'],
                "privacy_policy" => $appConfig->get('links')['privacy_policy'],
                "terms_of_service" => $appConfig->get('links')['terms_of_service'],
                "documentations" => $appConfig->get('links')['docs'],
                "company_houses" => $appConfig->get('links')['company_houses'],
            )
        )
    );
    http_response_code(200);
    die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
} else {
    $rsp = array(
        "code" => 405,
        "error" => "A request was made of a page using a request method not supported by that page",
        "message" => "Please use a get request"
    );
    http_response_code(405);
    die(json_encode($rsp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}


?>