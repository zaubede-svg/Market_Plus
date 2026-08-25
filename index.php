<?php
declare(strict_types=1);
require_once __DIR__.'/config/database.php';
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Marché AUBEDE</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wrap">
  <section class="hero">
    <h1>Marché AUBEDE</h1>
    <p>Votre boutique — ZOUNTCHEGBE AUBEDE</p>
  </section>
  <input id="search" class="search" placeholder="Rechercher un produit...">
  <section id="products" class="products"></section>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
