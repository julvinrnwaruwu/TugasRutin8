<?php
require_once __DIR__.'/../config/database.php'; require_once __DIR__.'/../app/helpers.php'; require_once __DIR__.'/../app/ProductRepository.php';
try { $pdo=Database::connection(); $repo=new ProductRepository($pdo); }
catch (Throwable $e) { http_response_code(500); exit('Koneksi gagal. Aktifkan MySQL dan import database/inventaris_db.sql.'); }
$f=take_flash(); $q=trim((string)($_GET['q']??'')); $page=max(1,(int)($_GET['page']??1)); $per=5;
$total=$repo->count($q); $pages=max(1,(int)ceil($total/$per)); $page=min($page,$pages); $rows=$repo->page($q,$per,($page-1)*$per);
$cats=$repo->categories(); $suppliers=$repo->suppliers();
function page_url(int $p,string $q): string { return '?'.http_build_query(['q'=>$q,'page'=>$p]); }
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>CRUD Inventaris</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><div class="wrap topin"><a class="brand" href="index.php">◆ Inventaris<span>.</span></a><small>TUGAS RUTIN 8</small></div></header>
<main class="wrap page"><section class="hero"><p class="eyebrow">MANAJEMEN BARANG</p><h1>CRUD Inventaris <em>— Dashboard</em></h1><p class="muted">Kelola produk, kategori, dan pemasok dalam satu tempat.</p></section>
<?php if($f): ?><div class="alert <?=e($f['type'])?>"><?=e($f['message'])?></div><?php endif; ?>
<section class="stats"><article><span>Total produk</span><b><?= $total ?></b><small>Data sesuai pencarian</small></article><article><span>Kategori</span><b><?=count($cats)?></b><small>Kategori tersedia</small></article><article><span>Supplier</span><b><?=count($suppliers)?></b><small>Pemasok tersedia</small></article></section>
<section class="panel"><div class="panelhead"><div><p class="eyebrow">DATA INVENTARIS</p><h2>Daftar Produk</h2></div><a class="btn primary" href="create.php">＋ Tambah Produk</a></div>
<form class="search" method="get"><input name="q" value="<?=e($q)?>" placeholder="Cari nama, SKU, kategori, supplier..."><button class="btn secondary">Cari</button><?php if($q!==''):?><a class="btn ghost" href="index.php">Reset</a><?php endif;?></form>
<div class="tablewrap"><table><thead><tr><th>Produk</th><th>Kategori</th><th>Supplier</th><th>Stok</th><th>Harga</th><th>Aksi</th></tr></thead><tbody>
<?php if(!$rows):?><tr><td colspan="6" class="empty">Tidak ada produk yang cocok.</td></tr><?php endif;?>
<?php foreach($rows as $p):?><tr><td><b><?=e($p['name'])?></b><small class="sku"><?=e($p['sku'])?></small></td><td><?=e($p['category_name'])?></td><td><?=e($p['supplier_name'])?></td><td><span class="pill"><?= (int)$p['stock']?></span></td><td>Rp <?=number_format((float)$p['price'],0,',','.')?></td><td><div class="actions"><a href="edit.php?id=<?=(int)$p['id']?>">Edit</a><form method="post" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus produk ini?')"><input type="hidden" name="csrf" value="<?=e(csrf())?>"><input type="hidden" name="id" value="<?=(int)$p['id']?>"><button class="link danger">Hapus</button></form></div></td></tr><?php endforeach;?>
</tbody></table></div><div class="foot"><span>Menampilkan <?=count($rows)?> dari <?=$total?> produk</span><nav class="pagination"><?php if($page>1):?><a href="<?=e(page_url($page-1,$q))?>">←</a><?php endif;?>Halaman <?=$page?> / <?=$pages?><?php if($page<$pages):?><a href="<?=e(page_url($page+1,$q))?>">→</a><?php endif;?></nav></div></section><p class="note">PHP · PDO Singleton · Prepared Statements · JOIN · Flash Message</p></main></body></html>
