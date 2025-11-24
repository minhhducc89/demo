<?php

// Yêu cầu load file env.php để lấy các hằng số DB_HOST, DB_NAME, ...
// Lưu ý: Trong index.php đã load file env.php rồi, nhưng ta require lại
// để đảm bảo lớp Database có thể tự hoạt động.
require_once __DIR__ . '/env.php'; 

class Database {
    protected $conn; // Biến kết nối PDO

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        // Xây dựng chuỗi DSN
        $dsn = "mysql:host=" . DB_HOST 
             . ";port=" . DB_PORT 
             . ";dbname=" . DB_NAME 
             . ";charset=utf8";

        try {
            // Thiết lập kết nối PDO
            $this->conn = new PDO(
                $dsn, 
                DB_USER, 
                DB_PASS
            );
            
            // Thiết lập chế độ báo lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Thiết lập chế độ fetch mặc định là ASSOC
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Xử lý lỗi kết nối
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }
}

