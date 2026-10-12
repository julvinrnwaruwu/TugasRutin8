<?php
require_once __DIR__.'/../config/database.php'; require_once __DIR__.'/../app/helpers.php'; require_once __DIR__.'/../app/ProductRepository.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') go('index.php');
check_csrf(); $id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id || $id<1) { flash('error','ID produk tidak valid.'); go('index.php'); }
$pdo=Database::connection(); $repo=new ProductRepository($pdo);
try {
    $name=$repo->delete((int)$id);
    if ($name===null) { flash('error','Produk tidak ditemukan.'); go('index.php'); }
    log_action($pdo,'DELETE','Menghapus produk: '.$name,(int)$id); flash('success','Produk berhasil dihapus.');
} catch (PDOException $e) { flash('error','Produk gagal dihapus.'); }
go('index.php');
