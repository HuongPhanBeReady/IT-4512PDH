<?php

<<<<<<< HEAD
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$host = $_ENV['MYSQL_HOST'];
$dbname = $_ENV['MYSQL_DATABASE'];
$username = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];

return $db = [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$host};dbname={$dbname}",
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
];

=======
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=contact',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
>>>>>>> a9e4bfccef306d502a2d23e3c8f4234a33b5c0c7
