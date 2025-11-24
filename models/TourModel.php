<?php
class TourModel {
    public $conn;

    public function __construct(PDO $conn) { 
        $this->conn = $conn; // Lưu đối tượng kết nối vào thuộc tính
    }

    public function getAllTours($keyword = null, $guide_id = null, $category_id = null) {
        $sql = "SELECT tours.*, users.name as guide_name, categories.name as category_name
                FROM tours
                LEFT JOIN users ON tours.guide_id = users.id
                LEFT JOIN categories ON tours.category_id = categories.id
                WHERE 1=1"; // Mẹo 1=1 để dễ nối chuỗi AND

        $params = [];

        // Nếu có từ khóa tìm kiếm
        if ($keyword) {
            $sql .= " AND tours.name LIKE :keyword";
            $params[':keyword'] = "%$keyword%";
        }

        // Nếu có lọc theo guide
        if ($guide_id) {
            $sql .= " AND tours.guide_id = :guide_id";
            $params[':guide_id'] = $guide_id;
        }

        // Nếu có lọc theo danh mục
        if ($category_id) {
            $sql .= " AND tours.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Lấy tour theo ID hướng dẫn viên (cho role guide)
    public function getToursByGuide($guide_id) {
        $sql = "SELECT * FROM tours WHERE guide_id = :guide_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':guide_id' => $guide_id]);
        return $stmt->fetchAll();
    }

    public function insertTour($name, $price, $start_date, $guide_id, $image, $desc, $category_id = null) {
        $sql = "INSERT INTO tours (name, price, start_date, guide_id, image, description, category_id) 
                VALUES (:name, :price, :start_date, :guide_id, :image, :desc, :category_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':name' => $name, ':price' => $price, ':start_date' => $start_date,
            ':guide_id' => $guide_id, ':image' => $image, ':desc' => $desc, ':category_id' => $category_id
        ]);
    }
    
    // Lấy danh sách User là Guide để chọn trong form
    public function getAllGuides() {
        $sql = "SELECT * FROM users WHERE role = 'guide'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id) {
        // Xóa Tour (giả định liên kết khóa ngoại đã có ON DELETE CASCADE)
        $stmt = $this->conn->prepare("DELETE FROM tours WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    // 1. Lấy thông tin chi tiết 1 tour để sửa
    public function getTourById($id) {
        $sql = "SELECT * FROM tours WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // 2. Cập nhật thông tin tour
    public function updateTour($id, $name, $price, $start_date, $guide_id, $image, $desc, $category_id = null) {
        // Nếu user không up ảnh mới ($image = null) thì giữ nguyên ảnh cũ trong logic Controller
        // Nhưng ở SQL, ta sẽ viết câu lệnh update đầy đủ
        $sql = "UPDATE tours 
                SET name = :name, price = :price, start_date = :start_date, 
                    guide_id = :guide_id, image = :image, description = :desc, category_id = :category_id 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':start_date' => $start_date,
            ':guide_id' => $guide_id,
            ':image' => $image,
            ':desc' => $desc,
            ':category_id' => $category_id,
            ':id' => $id
        ]);
    }

    // 3. Xóa tour
    public function deleteTour($id) {
        $sql = "DELETE FROM tours WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
    }



    // Trong class TourModel
    public function countTours() {
        $sql = "SELECT COUNT(*) as total FROM tours";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

        public function countGuides() {
    // Đảm bảo đếm từ bảng 'users' và chỉ đếm role là 'guide'
            $sql = "SELECT COUNT(*) FROM users WHERE role = 'guide'"; 
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            // fetchColumn(0) là cách tối ưu để lấy kết quả COUNT(*)
            return $stmt->fetchColumn(); 
        }
    public function getTotalRevenue() {
        // Lưu ý: Thực tế bạn cần bảng 'bookings' hoặc 'orders'. 
        // Ở đây tôi tạm tính tổng giá trị của tất cả các tour đang có để demo.
        $sql = "SELECT SUM(price) as total FROM tours";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Lấy 5 tour mới nhất để hiển thị bảng 'Recent'
    public function getRecentTours($limit = 5) {
        $limit = intval($limit);
        $sql = "SELECT * FROM tours ORDER BY id DESC LIMIT " . $limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countBookings() {
    // Đếm tất cả Booking ngoại trừ Booking 'Đã hủy'
    $sql = "SELECT COUNT(id) FROM tb_booking 
            WHERE trang_thai != 'Đã hủy'";
            
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}
    /**
     * Tính tổng giá trị (doanh thu) từ các Tour
     */
    public function calculateTotalRevenue() {
    // TÍNH TỔNG TỪ CẢ BOOKING 'MỚI' VÀ 'ĐÃ XÁC NHẬN'
    $sql = "SELECT SUM(tong_tien) FROM tb_booking 
            WHERE trang_thai IN ('Mới', 'Đã xác nhận')"; // <- Sửa TẠI ĐÂY
            
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn() ?? 0;
}
    


/**
 * Đếm tổng số Tour mà HDV đang phụ trách
 */
public function countToursByGuideId($guideId) {
    $sql = "SELECT COUNT(*) FROM tours WHERE guide_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$guideId]);
    return $stmt->fetchColumn();
}

/**
 * Đếm tổng số Khách hàng (Booking) cho các Tour của HDV (có thể đếm cả quá khứ)
 * Hoặc bạn có thể lọc chỉ đếm booking của các tour đang chạy/sắp tới
 */
      public function getUpcomingToursByGuideId($guideId) {
    $sql = "SELECT 
                t.id, t.name, t.start_date
            FROM tours t
            JOIN users u ON t.guide_id = u.id
            WHERE t.guide_id = ? AND t.start_date >= CURDATE()
            ORDER BY t.start_date ASC
            LIMIT 5"; // Lấy danh sách tour sắp tới
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$guideId]);
    return $stmt->fetchAll();
}

public function countTotalGuestsByGuideId($guideId) {
    $sql = "SELECT SUM(b.so_luong_khach) AS total_guests 
            FROM tb_booking b 
            JOIN tours t ON b.tour_id = t.id
            WHERE t.guide_id = ? AND b.trang_thai != 'Đã hủy'"; 
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$guideId]);
    return (int) ($stmt->fetchColumn() ?? 0);
}


    
}
?>