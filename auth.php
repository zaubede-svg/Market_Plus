<?php
declare(strict_types=1);

session_start();
require_once __DIR__.'/database.php';

function require_admin(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function check_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Jeton de sécurité invalide.');
    }
}

function h(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
