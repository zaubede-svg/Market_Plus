<?php
declare(strict_types=1);
require_once __DIR__.'/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$products=db()->query("SELECT id,name,category,price,old_price,image,rating,reviews,description,tag,stock FROM products WHERE active=1 ORDER BY id DESC")->fetchAll();

echo json_encode($products, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
