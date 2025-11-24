<?php
class CustomerModel {
    public $conn;
    public function __construct(PDO $conn) { 
    $this->conn = $conn;
     }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM tb_khach_hang ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tb_khach_hang WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function insert($ho_ten, $email, $sdt, $dia_chi) {
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO tb_khach_hang (ho_ten, email, sdt, dia_chi, ngay_tao) VALUES (:n, :e, :p, :a, :nt)";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([':n' => $ho_ten, ':e' => $email, ':p' => $sdt, ':a' => $dia_chi, ':nt' => $now]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (strpos($msg, "doesn't have a default value") !== false || strpos($msg, '1364') !== false) {
                // Try inserting with explicit id if table lacks AUTO_INCREMENT
                $stmtNext = $this->conn->prepare("SELECT COALESCE(MAX(id),0)+1 AS next_id FROM tb_khach_hang");
                $stmtNext->execute();
                $nextId = $stmtNext->fetchColumn();

                $sql2 = "INSERT INTO tb_khach_hang (id, ho_ten, email, sdt, dia_chi, ngay_tao) VALUES (:id, :n, :e, :p, :a, :nt)";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->execute([':id' => $nextId, ':n' => $ho_ten, ':e' => $email, ':p' => $sdt, ':a' => $dia_chi, ':nt' => $now]);
                return $nextId;
            }

            // Re-throw other exceptions
            throw $e;
        }
    }

    public function update($id, $ho_ten, $email, $sdt, $dia_chi) {
        $sql = "UPDATE tb_khach_hang SET ho_ten=:n, email=:e, sdt=:p, dia_chi=:a WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':n' => $ho_ten, ':e' => $email, ':p' => $sdt, ':a' => $dia_chi, ':id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM tb_khach_hang WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
?>