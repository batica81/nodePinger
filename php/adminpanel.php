<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

if (!isset($_SERVER['PHP_AUTH_PW']) || (($_SERVER['PHP_AUTH_PW'] != BASIC_PASSWORD))) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="ServerAlive"');
    exit('<h3>Server Alive Backend</h3>Sorry, you need proper credentials.');
}
echo "<link rel='stylesheet' type='text/css' href='styleadmin.css'>";

require_once 'credentials.php';

$host = DB_HOST;
$charset = DB_CHARSET;
$db = DB_NAME;
$user = DB_USER;
$pass = DB_PASSWORD;

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


$stmt4 = $pdo->prepare('select * from booth join booth_alive on booth.id=booth_alive.booth_id;');
$stmt4->execute();
$allBooths = $stmt4->fetchAll();

?>

    <head>
        <title>Server Alive Admin Panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>

    <h1 class="adminTitle">Server Alive Admin Panel</h1>


    <h3 class="booths">Available Booths:</h3>

    <div class="boothsWrapper">
        <!-- loop with php -->
        <?php foreach ($allBooths as $booth): ?>

        <div class="singleBooth">
            <div class="boothName">Booth: <span class="boothNumber"><?php echo $booth['id']; ?></span></div>
            <div class="boothStatus">Booth last seen alive: <span class="boothLiveDate"><?php if($booth['is_alive'] == 1) {echo date('Y-M-d H:i:s', strtotime($booth['timestamp'] . ' + 3 hours')); }; ?></span></div>

        </div>

        <?php endforeach; ?>

    </div>


    </body>

