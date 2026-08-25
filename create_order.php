<?php
declare(strict_types=1);
require_once __DIR__.'/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $data=json_decode(file_get_contents('php://input'),true,512,JSON_THROW_ON_ERROR);
    foreach(['name','phone','location','payment','items'] as $key){
        if(empty($data[$key])) throw new RuntimeException('Champ manquant: '.$key);
    }

    $pdo=db();
    $pdo->beginTransaction();

    $total=0;
    $items=[];

    foreach($data['items'] as $item){
        $st=$pdo->prepare("SELECT id,name,price,stock FROM products WHERE id=? AND active=1 FOR UPDATE");
        $st->execute([(int)$item['id']]);
        $product=$st->fetch();

        if(!$product) continue;

        $qty=max(1,(int)$item['qty']);
        if($qty > (int)$product['stock']) throw new RuntimeException('Stock insuffisant pour '.$product['name']);

        $total += (float)$product['price']*$qty;
        $items[]=['id'=>$product['id'],'name'=>$product['name'],'price'=>$product['price'],'qty'=>$qty];

        $up=$pdo->prepare("UPDATE products SET stock=stock-? WHERE id=?");
        $up->execute([$qty,$product['id']]);
    }

    if(!$items) throw new RuntimeException('Panier vide.');

    $number='CMD-'.date('Ymd-His').'-'.random_int(100,999);

    $st=$pdo->prepare("INSERT INTO orders(order_number,customer_name,phone,location,address,payment_method,total) VALUES(?,?,?,?,?,?,?)");
    $st->execute([$number,$data['name'],$data['phone'],$data['location'],$data['address']??null,$data['payment'],$total]);

    $orderId=(int)$pdo->lastInsertId();
    $st=$pdo->prepare("INSERT INTO order_items(order_id,product_id,product_name,quantity,unit_price) VALUES(?,?,?,?,?)");

    foreach($items as $item){
        $st->execute([$orderId,$item['id'],$item['name'],$item['qty'],$item['price']]);
    }

    $pdo->commit();

    echo json_encode(['ok'=>true,'order_number'=>$number,'total'=>$total],JSON_UNESCAPED_UNICODE);
} catch(Throwable $e) {
    if(isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);
}
