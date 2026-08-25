<?php
declare(strict_types=1);
session_start();
require_once __DIR__.'/../config/database.php';

$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $st=db()->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $st->execute([trim($_POST['username']??'')]);
    $admin=$st->fetch();

    if($admin && password_verify($_POST['password']??'',$admin['password_hash'])){
        session_regenerate_id(true);
        $_SESSION['admin_id']=$admin['id'];
        $_SESSION['admin_name']=$admin['name'];
        $_SESSION['csrf']=bin2hex(random_bytes(32));
        header('Location: index.php');
        exit;
    }
    $error='Identifiant ou mot de passe incorrect.';
}
?>
<!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin AUBEDE</title><style>body{font-family:Arial;background:#10213f;display:grid;place-items:center;min-height:100vh}.box{background:#fff;padding:30px;border-radius:18px;width:min(420px,92%)}input,button{width:100%;padding:13px;margin:7px 0;border-radius:9px;border:1px solid #ddd}button{background:#e9823b;color:white;font-weight:800}.err{color:#b42318}</style></head><body><div class="box"><h1>Administration</h1><p>ZOUNTCHEGBE AUBEDE</p><?php if($error):?><p class="err"><?=htmlspecialchars($error)?></p><?php endif;?><form method="post"><input name="username" placeholder="Identifiant" required><input name="password" type="password" placeholder="Mot de passe" required><button>Se connecter</button></form></div></body></html>
