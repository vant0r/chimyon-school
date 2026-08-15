<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

function adminSecret(): array {
    $candidates = [
        dirname(__DIR__) . '/config/admin_secret.php',
        dirname(__DIR__) . '/../config/admin_secret.php',
    ];
    foreach ($candidates as $path) {
        if (is_file($path)) {
            $secret = require $path;
            if (is_array($secret) && !empty($secret['admin_key_hash'])) return $secret;
        }
    }
    return [];
}

function adminIsAuthenticated(): bool {
    return !empty($_SESSION['chimyon_admin_authenticated']);
}

function adminRequireAuth(): void {
    if (!adminIsAuthenticated()) {
        header('Location: login.php', true, 302);
        exit;
    }
}

function adminVerifyKey(string $key): bool {
    $secret = adminSecret();
    return !empty($secret['admin_key_hash']) && password_verify($key, (string)$secret['admin_key_hash']);
}

function adminLogin(string $key): bool {
    if (!adminVerifyKey($key)) return false;
    session_regenerate_id(true);
    $_SESSION['chimyon_admin_authenticated'] = true;
    $_SESSION['chimyon_admin_login_at'] = time();
    $_SESSION['chimyon_admin_csrf'] = bin2hex(random_bytes(32));
    return true;
}

function adminLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool)$params['secure'], (bool)$params['httponly']);
    }
    session_destroy();
}

function adminCsrfToken(): string {
    if (empty($_SESSION['chimyon_admin_csrf'])) $_SESSION['chimyon_admin_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['chimyon_admin_csrf'];
}

function adminVerifyCsrf(string $token): bool {
    return !empty($_SESSION['chimyon_admin_csrf']) && hash_equals((string)$_SESSION['chimyon_admin_csrf'], $token);
}
