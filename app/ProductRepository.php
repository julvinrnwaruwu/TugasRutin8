<?php
declare(strict_types=1);
final class ProductRepository {
    public function __construct(private PDO $pdo) {}
    public function categories(): array { return $this->pdo->query('SELECT id,name FROM categories ORDER BY name')->fetchAll(); }
    public function suppliers(): array { return $this->pdo->query('SELECT id,name FROM suppliers ORDER BY name')->fetchAll(); }
    public function find(int $id): ?array {
        $s=$this->pdo->prepare('SELECT * FROM products WHERE id=?'); $s->execute([$id]); return $s->fetch() ?: null;
    }
    public function count(string $q=''): int {
        if ($q==='') return (int)$this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $s=$this->pdo->prepare('SELECT COUNT(*) FROM products p JOIN categories c ON c.id=p.category_id JOIN suppliers s ON s.id=p.supplier_id WHERE p.name LIKE ? OR p.sku LIKE ? OR c.name LIKE ? OR s.name LIKE ?');
        $like='%'.$q.'%'; $s->execute([$like,$like,$like,$like]); return (int)$s->fetchColumn();
    }
    public function page(string $q,int $limit,int $offset): array {
        $sql='SELECT p.*,c.name category_name,s.name supplier_name FROM products p JOIN categories c ON c.id=p.category_id JOIN suppliers s ON s.id=p.supplier_id';
        if ($q!=='') $sql.=' WHERE p.name LIKE :q OR p.sku LIKE :q OR c.name LIKE :q OR s.name LIKE :q';
        $sql.=' ORDER BY p.id DESC LIMIT :lim OFFSET :off';
        $s=$this->pdo->prepare($sql);
        if ($q!=='') $s->bindValue(':q','%'.$q.'%',PDO::PARAM_STR);
        $s->bindValue(':lim',$limit,PDO::PARAM_INT); $s->bindValue(':off',$offset,PDO::PARAM_INT); $s->execute();
        return $s->fetchAll();
    }
    public function create(array $d): int {
        $s=$this->pdo->prepare('INSERT INTO products(category_id,supplier_id,sku,name,description,stock,price) VALUES(:category_id,:supplier_id,:sku,:name,:description,:stock,:price)');
        $s->execute($d); return (int)$this->pdo->lastInsertId();
    }
    public function update(int $id,array $d): void {
        $d['id']=$id; $s=$this->pdo->prepare('UPDATE products SET category_id=:category_id,supplier_id=:supplier_id,sku=:sku,name=:name,description=:description,stock=:stock,price=:price WHERE id=:id'); $s->execute($d);
    }
    public function delete(int $id): ?string {
        $p=$this->find($id); if (!$p) return null;
        $s=$this->pdo->prepare('DELETE FROM products WHERE id=?'); $s->execute([$id]); return $p['name'];
    }
}
