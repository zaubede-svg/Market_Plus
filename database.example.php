<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_NAME = 'aubede';
const DB_USER = 'CHANGE_ME';
const DB_PASS = 'CHANGE_ME';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';

    return $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
