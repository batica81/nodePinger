<?php

header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once 'credentials.php';

if (!isset($_SERVER['PHP_AUTH_PW']) || (($_SERVER['PHP_AUTH_PW'] != BASIC_PASSWORD))) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="ServerAlive"');
    exit('<h3>Server Alive Backend</h3>Sorry, you need proper credentials.');
}


$host = DB_HOST;
$charset = DB_CHARSET;
$db = DB_NAME;
$user = DB_USER;
$pass = DB_PASSWORD;

$allowedClients = ['torrente'];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if( isset($_POST['client_ip']) ) {

        $client_ip = $_POST['client_ip'];
        $stmt = $pdo->prepare('INSERT INTO liveclient (client_ip, is_alive, rtt) VALUES (?, ?, ?);');
        $stmt->execute([$client_ip ,1 ,0]);

        echo 'Update Sucessful!';
        die();

    }

}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if( isset($_GET['clientname']) && in_array($_GET['clientname'], $allowedClients )) {

        $client_ip = $_GET['clientname'];
        $stmt = $pdo->prepare("select * from liveclient where client_ip = ?;");
        $stmt->execute([$client_ip]);
        $allData = $stmt->fetchAll();

        $jsonstring = json_encode($allData);
        echo $jsonstring;

    } else {
        echo "Go away!";
        die();
    }
}