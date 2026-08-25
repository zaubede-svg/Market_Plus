<?php
declare(strict_types=1);
require_once __DIR__.'/../config/database.php';

$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $username=trim($_POST['username']??'admin');
    $name=trim($_POST['name']??'ZOUNTCHEGBE AUBEDE');
    $password=$_POST['password']??'Marina2026';

    if(strlen($password)<8){
        $message='Le mot de passe doit contenir au moins 8 caractères.';
    }else{
        try{
            $st=db()->prepare("INSERT INTO admins(username,name,password_hash) VALUES(?,?,?)");
            $st->execute([$username,$name,password_hash($password,PASSWORD_DEFAULT)]);
            $message='Administrateur créé. Supprimez maintenant admin/setup.php.';
        }catch(Throwable $e){$message='Création impossible: le compte existe peut-être déjà.';}
    }
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Setup AUBEDE</title><style>body{font-family:Arial;background:#10213f;padding:30px}.box{max-width:450px;margin:auto;background:white;padding:25px;border-radius:18px}input,button{width:100%;padding:12px;margin:7px 0;border-radius:9px;border:1px solid #ddd}button{background:#e9823b;color:white;font-weight:800}</style></head><body><div class="box"><h2>Configuration Marché AUBEDE</h2><p><?=htmlspecialchars($message)?></p><form method="post"><input name="name" value="ZOUNTCHEGBE AUBEDE" required><input name="username" value="admin" required><input name="password" type="password" value="Marina2026" required><button>Créer l'administrateur</button></form></div></body></html>
