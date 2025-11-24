<?php
class BookingModel {
    public $conn;
    public function __construct(PDO $conn) { 
        // Lưu PDO connection được truyền vào từ controller
        $this->conn = $conn; 
    }

    // =========================================================================
    // QUẢN LÝ BOOKING (CRUD)
    // =========================================================================

    /**
     * Lấy tất cả Booking, join với Tour và Khách hàng
     */
    public function getAllBookings() {
        $sql = "SELECT 
                    b.id,                      
                    b.ngay_dat,
                    b.so_luong_khach,
                    b.tong_tien,
                    b.trang_thai,
                    b.ghi_chu,
                    b.customer_id,
                    t.name as tour_name,
                    IFNULL(kh.ho_ten, 'Khách vãng lai') as customer_name,
                    -- Tổng số record checkin và số đã điểm danh
                    (SELECT COUNT(*) FROM tb_checkin c WHERE c.booking_id = b.id) AS total_checkin_records,
                    (SELECT COUNT(*) FROM tb_checkin c WHERE c.booking_id = b.id AND c.trang_thai = 'Đã điểm danh') AS checked_in_count
                FROM tb_booking b
                JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tb_khach_hang kh ON b.customer_id = kh.id
                ORDER BY b.id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết Booking theo ID
     */
    public function getById($id) {
        $sql = "SELECT 
                    b.*, 
                    t.name as tour_name, 
                    kh.ho_ten as customer_name,
                    kh.sdt as customer_phone,
                    kh.email as customer_email
                FROM tb_booking b
                JOIN tours t ON b.tour_id = t.id
                -- Sử dụng LEFT JOIN để vẫn trả về booking ngay cả khi thông tin khách hàng chưa có trong tb_khach_hang
                LEFT JOIN tb_khach_hang kh ON b.customer_id = kh.id
                WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Thêm Booking mới và tự động tạo 1 record Check-in
     */
    public function insert($tour_id, $customer_id, $ngay_dat, $so_luong_khach, $tong_tien, $trang_thai, $ghi_chu) {
        $this->conn->beginTransaction(); // Bắt đầu Transaction
        try {
            $sql = "INSERT INTO tb_booking (tour_id, customer_id, ngay_dat, so_luong_khach, tong_tien, trang_thai, ghi_chu) 
                    VALUES (:t_id, :c_id, :nd, :slk, :tt, :ts, :gc)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':t_id' => $tour_id, ':c_id' => $customer_id, ':nd' => $ngay_dat,
                ':slk' => $so_luong_khach, ':tt' => $tong_tien, ':ts' => $trang_thai, ':gc' => $ghi_chu
            ]);
            
            $booking_id = $this->conn->lastInsertId();
        
            // TỰ ĐỘNG TẠO RECORD CHECK-IN CHO KHÁCH HÀNG (tạo theo số lượng khách)
            $this->insertCheckin($booking_id, $customer_id, $so_luong_khach ?? 1);
            
            $this->conn->commit();
            return $booking_id;
        } catch (PDOException $e) {
            // Nếu lỗi do table không có giá trị default cho id (không AUTO_INCREMENT), thử chèn với id tạo tay
            $msg = $e->getMessage();
            if (strpos($msg, "doesn't have a default value") !== false || strpos($msg, '1364') !== false) {
                try {
                    // Lấy id tiếp theo
                    $stmtNext = $this->conn->prepare("SELECT COALESCE(MAX(id),0)+1 AS next_id FROM tb_booking");
                    $stmtNext->execute();
                    $nextId = $stmtNext->fetchColumn();

                    $sql2 = "INSERT INTO tb_booking (id, tour_id, customer_id, ngay_dat, so_luong_khach, tong_tien, trang_thai, ghi_chu) 
                             VALUES (:id, :t_id, :c_id, :nd, :slk, :tt, :ts, :gc)";
                    $stmt2 = $this->conn->prepare($sql2);
                    $stmt2->execute([
                        ':id' => $nextId,
                        ':t_id' => $tour_id, ':c_id' => $customer_id, ':nd' => $ngay_dat,
                        ':slk' => $so_luong_khach, ':tt' => $tong_tien, ':ts' => $trang_thai, ':gc' => $ghi_chu
                    ]);

                    $booking_id = $nextId;
                    $this->insertCheckin($booking_id, $customer_id, $so_luong_khach ?? 1);
                    $this->conn->commit();
                    return $booking_id;
                } catch (PDOException $e2) {
                    $this->conn->rollBack();
                    throw $e2;
                }
            }

            $this->conn->rollBack(); // Rollback nếu có lỗi
            throw $e;
    }
    }

    /**
     * Cập nhật thông tin Booking
     */
    public function update($id, $ngay_dat, $so_luong_khach, $tong_tien, $trang_thai, $ghi_chu) {
        $sql = "UPDATE tb_booking SET ngay_dat=:nd, so_luong_khach=:slk, tong_tien=:tt, trang_thai=:ts, ghi_chu=:gc WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':nd' => $ngay_dat, ':slk' => $so_luong_khach, ':tt' => $tong_tien, 
            ':ts' => $trang_thai, ':gc' => $ghi_chu, ':id' => $id
        ]);
    }

    /**
     * Xóa Booking
     */
    public function delete($id) {
        // Tự động xóa các record Check-in liên quan nhờ ON DELETE CASCADE trong SQL
        $stmt = $this->conn->prepare("DELETE FROM tb_booking WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }


    // =============================================================================
    // CHỨC NĂNG CHECK-IN
    // =============================================================================

    /**
     * Lấy danh sách các record Check-in liên quan đến một Booking
     */
    public function getCheckinsByBookingId($booking_id) {
        $sql = "SELECT 
                    c.*, 
                    kh.ho_ten as customer_name,
                    kh.sdt as customer_phone
                FROM tb_checkin c
                LEFT JOIN tb_khach_hang kh ON c.customer_id = kh.id
                WHERE c.booking_id = :booking_id
                ORDER BY c.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':booking_id' => $booking_id]);
        return $stmt->fetchAll();
}

    /**
     * Tạo record check-in cho một booking và khách hàng
     */
    public function insertCheckin($booking_id, $customer_id, $count = 1, $trang_thai = 'Chưa điểm danh') {
        // Tạo nhiều record checkin tương ứng với số lượng khách
        $sql = "INSERT INTO tb_checkin (booking_id, customer_id, trang_thai) VALUES (:b, :c, :ts)";
        $stmt = $this->conn->prepare($sql);
        $success = true;
        for ($i = 0; $i < max(1, intval($count)); $i++) {
            $ok = $stmt->execute([
                ':b' => $booking_id,
                ':c' => $customer_id,
                ':ts' => $trang_thai
            ]);
            if (!$ok) $success = false;
        }
        return $success;
    }

    /**
     * Cập nhật trạng thái cho một record check-in
     */
    public function updateCheckinStatus($checkin_id, $trang_thai) {
        $sql = "UPDATE tb_checkin SET trang_thai = :ts WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':ts' => $trang_thai, ':id' => $checkin_id]);
    }
}
?>