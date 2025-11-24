<?php
class UserModel {
    public $conn;

    public function __construct(PDO $conn) { 
        $this->conn = $conn; 
    }

    // Phương thức kiểm tra đăng nhập (đã chuẩn hóa tên bảng tb_users)
    public function getUserByUsername($username) {
        // Tìm user theo tên (cột `name`) — điều chỉnh nếu bảng dùng cột khác
        $sql = "SELECT id, name, email, password, role FROM users WHERE name = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }
    
    // Phương thức cũ dùng getUserByEmail (giữ lại và sửa lỗi tên bảng)
    public function getUserByEmail($email) {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}
?>