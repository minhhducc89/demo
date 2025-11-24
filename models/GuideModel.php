<?php
// FILE: D:\laragon\www\main\models\GuideModel.php

// Đã loại bỏ: require_once __DIR__ . '/../commons/Db.php';

class GuideModel { 
    public $conn; // Thêm thuộc tính để lưu đối tượng kết nối PDO
    private $table = 'users'; // Sử dụng bảng users để lưu HDV

    // FIX LỖI: Sử dụng Dependency Injection (DI)
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    /**
     * Lấy danh sách tất cả Hướng dẫn viên (role = 'guide')
     */
    public function getAllGuides() {
        $sql = "SELECT id, name, email, phone, gender, address FROM {$this->table} WHERE role = 'guide'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thông tin HDV theo ID
     */
    public function getGuideById($id) {
        $sql = "SELECT id, name, email, phone, gender, address FROM {$this->table} WHERE id = ? AND role = 'guide'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm HDV mới (mặc định role = 'guide' và mã hóa mật khẩu)
     */
    public function insert($name, $email, $password, $phone, $gender, $address) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO {$this->table} (name, email, password, role, phone, gender, address) 
                 VALUES (?, ?, ?, 'guide', ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        try {
            return $stmt->execute([
                $name,
                $email,
                $hashedPassword,
                $phone,
                $gender,
                $address
            ]);
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (strpos($msg, "doesn't have a default value") !== false || strpos($msg, '1364') !== false) {
                // Table 'users' may require explicit id. Compute next id and retry insert with explicit id.
                $stmtNext = $this->conn->prepare("SELECT COALESCE(MAX(id),0)+1 AS next_id FROM {$this->table}");
                $stmtNext->execute();
                $nextId = $stmtNext->fetchColumn();

                $sql2 = "INSERT INTO {$this->table} (id, name, email, password, role, phone, gender, address) 
                         VALUES (?, ?, ?, ?, 'guide', ?, ?, ?)";
                $stmt2 = $this->conn->prepare($sql2);
                return $stmt2->execute([
                    $nextId,
                    $name,
                    $email,
                    $hashedPassword,
                    $phone,
                    $gender,
                    $address
                ]);
            }
            throw $e;
        }
    }

    /**
     * Cập nhật thông tin HDV
     */
    public function update($id, $name, $email, $phone, $gender, $address, $password = null) {
        
        $params = [$name, $email, $phone, $gender, $address];
        
        $sql = "UPDATE {$this->table} SET name = ?, email = ?, phone = ?, gender = ?, address = ?";
        
        // Nếu có nhập mật khẩu mới, thì mã hóa và cập nhật
        if ($password !== null && $password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[] = $hashedPassword;
        }

        $sql .= " WHERE id = ? AND role = 'guide'";
        $params[] = $id;

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Xóa HDV
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ? AND role = 'guide'";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Kiểm tra email tồn tại
     */
    public function checkEmailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
}
?>