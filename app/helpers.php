<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function e(mixed $v): string { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function go(string $url): never { header('Location: '.$url); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type,'message'=>$message]; }
function take_flash(): ?array { $f=$_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function check_csrf(): void {
    if (!hash_equals((string)($_SESSION['csrf'] ?? ''), (string)($_POST['csrf'] ?? ''))) {
        http_response_code(419); exit('Token keamanan tidak valid. Muat ulang halaman.');
    }
}
function log_action(PDO $pdo, string $action, string $desc, ?int $id=null): void {
    $s=$pdo->prepare('INSERT INTO activity_logs(action,description,product_id) VALUES(?,?,?)');
    $s->execute([$action,$desc,$id]);
}
