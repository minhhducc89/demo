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
        return $this->conn->lastInsertId();
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
        $sql = "
            SELECT 
                t.*, 
                u.name AS guide_name,
                c.name AS category_name
            FROM 
                tours t
            LEFT JOIN 
                users u ON t.guide_id = u.id  -- THAY ĐỔI: JOIN với bảng users (đóng vai trò là guides)
            LEFT JOIN 
                categories c ON t.category_id = c.id
            WHERE 
                t.id = :id
        ";

        // Chuẩn bị và thực thi truy vấn
        $stmt = $this->conn->prepare($sql);
        
        // Bind tham số
        $stmt->execute([':id' => $id]);
        
        // Trả về kết quả dưới dạng mảng kết hợp
        // Lưu ý: Đảm bảo PDO::FETCH_ASSOC hoặc cấu hình fetch mặc định trả về mảng kết hợp.
        return $stmt->fetch(PDO::FETCH_ASSOC); 
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
        return $stmt->execute([
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

    // -----------------------
    // Full detail helpers
    // -----------------------
    public function getImagesByTourId($tourId) {
        $sql = "SELECT * FROM tour_images WHERE tour_id = :tour_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    public function insertTourImage($tourId, $filePath, $caption = null) {
        $sql = "INSERT INTO tour_images (tour_id, file_path, caption) VALUES (:tour_id, :file_path, :caption)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId, ':file_path' => $filePath, ':caption' => $caption]);
        return $this->conn->lastInsertId();
    }

    public function insertPrice($tourId, $package_name, $price_adult, $price_child = null, $applicability = null, $included_services = null) {
        $sql = "INSERT INTO tour_prices (tour_id, package_name, price_adult, price_child, applicability, included_services)
                VALUES (:tour_id, :package_name, :price_adult, :price_child, :applicability, :included_services)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':tour_id' => $tourId,
            ':package_name' => $package_name,
            ':price_adult' => $price_adult,
            ':price_child' => $price_child,
            ':applicability' => $applicability,
            ':included_services' => $included_services
        ]);
        return $this->conn->lastInsertId();
    }

    public function getPricesByTourId($tourId) {
        $sql = "SELECT * FROM tour_prices WHERE tour_id = :tour_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    public function getPoliciesByTourId($tourId) {
        $sql = "SELECT * FROM tour_policies WHERE tour_id = :tour_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    public function getSuppliersByTourId($tourId) {
        $sql = "SELECT s.* FROM tour_suppliers s
                JOIN tour_supplier_pivot p ON p.supplier_id = s.id
                WHERE p.tour_id = :tour_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tourId]);
        return $stmt->fetchAll();
    }

    // Supplier and policy management
    public function insertSupplier($name, $service_type = null, $address = null, $phone = null) {
        $sql = "INSERT INTO tour_suppliers (supplier_name, service_type, address, phone) VALUES (:name, :service_type, :address, :phone)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':name'=>$name, ':service_type'=>$service_type, ':address'=>$address, ':phone'=>$phone]);
        return $this->conn->lastInsertId();
    }

    public function attachSupplierToTour($tourId, $supplierId) {
        $sql = "INSERT IGNORE INTO tour_supplier_pivot (tour_id, supplier_id) VALUES (:tour_id, :supplier_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':tour_id'=>$tourId, ':supplier_id'=>$supplierId]);
    }

    public function detachSupplierFromTour($tourId, $supplierId) {
        $sql = "DELETE FROM tour_supplier_pivot WHERE tour_id = :tour_id AND supplier_id = :supplier_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':tour_id'=>$tourId, ':supplier_id'=>$supplierId]);
    }

    public function deleteSupplier($supplierId) {
        $sql = "DELETE FROM tour_suppliers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$supplierId]);
    }

    public function insertPolicy($tourId, $policy_type, $content) {
        $sql = "INSERT INTO tour_policies (tour_id, policy_type, content) VALUES (:tour_id, :policy_type, :content)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id'=>$tourId, ':policy_type'=>$policy_type, ':content'=>$content]);
        return $this->conn->lastInsertId();
    }

    public function deletePolicy($policyId) {
        $sql = "DELETE FROM tour_policies WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id'=>$policyId]);
    }

    public function getItineraryByTourId($tourId) {
        // Prefer new normalized tables if they exist (tour_itineraries + itinerary_activities)
        try {
            $sql = "SELECT iti.id as itinerary_id, iti.day_number, act.id as activity_id, act.start_time, act.location_name, act.activity_details
                    FROM tour_itineraries iti
                    JOIN itinerary_activities act ON act.itinerary_id = iti.id
                    WHERE iti.tour_id = :tour_id
                    ORDER BY iti.day_number ASC, act.start_time ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':tour_id' => $tourId]);
            $rows = $stmt->fetchAll();

            // Group by day (itinerary)
            $result = [];
            foreach ($rows as $r) {
                $day = $r['day_number'] ?? ($r['itinerary_id'] ?? 0);
                if (!isset($result[$day])) $result[$day] = ['day_number' => $day, 'activities' => []];
                $result[$day]['activities'][] = $r;
            }
            // Return indexed array
            return array_values($result);
        } catch (PDOException $e) {
            // Fallback: try to read from legacy `tb_tour_chi_tiet` (used elsewhere in the app)
            try {
                $sql2 = "SELECT id, ngay_thu as day_number, tieu_de as tieu_de, mo_ta as mo_ta, hinh_anh as hinh_anh
                         FROM tb_tour_chi_tiet WHERE tour_id = :tour_id ORDER BY ngay_thu ASC";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->execute([':tour_id' => $tourId]);
                $rows2 = $stmt2->fetchAll();
                // Convert to itinerary-like structure
                $result = [];
                foreach ($rows2 as $r) {
                    $day = $r['day_number'] ?? 0;
                    if (!isset($result[$day])) $result[$day] = ['day_number' => $day, 'activities' => []];
                    $result[$day]['activities'][] = [
                        'detail_id' => $r['id'],
                        'tieu_de' => $r['tieu_de'],
                        'mo_ta' => $r['mo_ta'],
                        'hinh_anh' => $r['hinh_anh'],
                    ];
                }
                return array_values($result);
            } catch (PDOException $ex) {
                return []; // give up, return empty
            }
        }
    }


    
}
?>