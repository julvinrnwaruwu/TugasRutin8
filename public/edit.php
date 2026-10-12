<?php
require_once __DIR__.'/../config/database.php'; require_once __DIR__.'/../app/helpers.php'; require_once __DIR__.'/../app/ProductRepository.php';
$pdo=Database::connection(); $repo=new ProductRepository($pdo); $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id||$id<1){flash('error','ID produk tidak valid.');go('index.php');}
$p=$repo->find((int)$id); if(!$p){flash('error','Produk tidak ditemukan.');go('index.php');}
$cats=$repo->categories();$sup=$repo->suppliers();$errors=[];$d=$p;
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf(); foreach(['category_id','supplier_id','sku','name','description','stock','price'] as $k)$d[$k]=trim((string)($_POST[$k]??''));
 if(!ctype_digit($d['category_id'])||!in_array((int)$d['category_id'],array_column($cats,'id'))) $errors[]='Pilih kategori yang valid.';
 if(!ctype_digit($d['supplier_id'])||!in_array((int)$d['supplier_id'],array_column($sup,'id'))) $errors[]='Pilih supplier yang valid.';
 if($d['sku']===''||mb_strlen($d['sku'])>40)$errors[]='SKU wajib diisi (maksimal 40 karakter).';
 if($d['name']===''||mb_strlen($d['name'])>150)$errors[]='Nama produk wajib diisi (maksimal 150 karakter).';
 if(!ctype_digit($d['stock']))$errors[]='Stok harus bilangan bulat nol atau lebih.';
 if(!is_numeric($d['price'])||(float)$d['price']<0)$errors[]='Harga harus angka nol atau lebih.';
 if(!$errors){try{$repo->update((int)$id,['category_id'=>(int)$d['category_id'],'supplier_id'=>(int)$d['supplier_id'],'sku'=>$d['sku'],'name'=>$d['name'],'description'=>$d['description']?:null,'stock'=>(int)$d['stock'],'price'=>number_format((float)$d['price'],2,'.','')]);log_action($pdo,'UPDATE','Mengubah produk: '.$d['name'],(int)$id);flash('success','Produk berhasil diperbarui.');go('index.php');}catch(PDOException $e){$errors[]=$e->getCode()==='23000'?'SKU sudah digunakan produk lain.':'Produk gagal diperbarui.';}}
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Produk</title><link rel="stylesheet" href="assets/style.css"></head><body><header class="top"><div class="wrap topin"><a class="brand" href="index.php">◆ Inventaris<span>.</span></a><a href="index.php">← Kembali</a></div></header><main class="wrap page narrow"><section class="hero"><p class="eyebrow">FORM INVENTARIS</p><h1>Edit <em>Produk</em></h1></section><?php if($errors):?><div class="alert error"><ul><?php foreach($errors as $e1):?><li><?=e($e1)?></li><?php endforeach;?></ul></div><?php endif;?>
<form class="panel form" method="post"><input type="hidden" name="csrf" value="<?=e(csrf())?>"><div class="grid">
<div class="field"><label>Nama produk *</label><input name="name" maxlength="150" required value="<?=e($d['name'])?>"></div><div class="field"><label>SKU *</label><input name="sku" maxlength="40" required value="<?=e($d['sku'])?>"></div>
<div class="field"><label>Kategori *</label><select name="category_id" required><option value="">Pilih kategori</option><?php foreach($cats as $c):?><option value="<?=(int)$c['id']?>" <?=((string)$d['category_id']===(string)$c['id'])?'selected':''?>><?=e($c['name'])?></option><?php endforeach;?></select></div>
<div class="field"><label>Supplier *</label><select name="supplier_id" required><option value="">Pilih supplier</option><?php foreach($sup as $s):?><option value="<?=(int)$s['id']?>" <?=((string)$d['supplier_id']===(string)$s['id'])?'selected':''?>><?=e($s['name'])?></option><?php endforeach;?></select></div>
<div class="field"><label>Stok *</label><input type="number" name="stock" min="0" step="1" required value="<?=e($d['stock'])?>"></div><div class="field"><label>Harga (Rp) *</label><input type="number" name="price" min="0" step="0.01" required value="<?=e($d['price'])?>"></div>
<div class="field full"><label>Deskripsi</label><textarea name="description" rows="4"><?=e($d['description'])?></textarea></div></div><div class="formactions"><a class="btn ghost" href="index.php">Batal</a><button class="btn primary">Simpan Perubahan</button></div></form></main></body></html>
