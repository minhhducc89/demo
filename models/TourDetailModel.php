<?php
class TourDetailModel {
    public $conn;
    public function __construct(PDO $conn) { 
    $this->conn = $conn;
    }

    

    // Lấy chi tiết theo ID tour
    public function getDetailsByTourId($tour_id) {
        $sql = "SELECT * FROM tb_tour_chi_tiet WHERE tour_id = :tour_id ORDER BY ngay_thu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }
    
    // Thêm: Bổ sung $hinh_anh
    public function insertDetail($tour_id, $ngay_thu, $tieu_de, $mo_ta, $hinh_anh = null) {
        $sql = "INSERT INTO tb_tour_chi_tiet (tour_id, ngay_thu, tieu_de, mo_ta, hinh_anh) VALUES (:t, :n, :td, :mt, :ha)";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([
                ':t' => $tour_id,
                ':n' => $ngay_thu,
                ':td' => $tieu_de,
                ':mt' => $mo_ta,
                ':ha' => $hinh_anh
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (strpos($msg, "doesn't have a default value") !== false || strpos($msg, '1364') !== false) {
                // Table may not have AUTO_INCREMENT. Compute next id and retry with explicit id.
                $stmtNext = $this->conn->prepare("SELECT COALESCE(MAX(id),0)+1 AS next_id FROM tb_tour_chi_tiet");
                $stmtNext->execute();
                $nextId = $stmtNext->fetchColumn();

                $sql2 = "INSERT INTO tb_tour_chi_tiet (id, tour_id, ngay_thu, tieu_de, mo_ta, hinh_anh) VALUES (:id, :t, :n, :td, :mt, :ha)";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->execute([
                    ':id' => $nextId,
                    ':t' => $tour_id,
                    ':n' => $ngay_thu,
                    ':td' => $tieu_de,
                    ':mt' => $mo_ta,
                    ':ha' => $hinh_anh
                ]);
                return $nextId;
            }

            throw $e;
        }
    }
    
    // Sửa: Bổ sung $hinh_anh (cho phép null nếu không đổi)
    public function updateDetail($id, $ngay_thu, $tieu_de, $mo_ta, $hinh_anh = null) {
        $sql = "UPDATE tb_tour_chi_tiet SET ngay_thu=:n, tieu_de=:t, mo_ta=:m";
        $params = [':n'=>$ngay_thu, ':t'=>$tieu_de, ':m'=>$mo_ta, ':id'=>$id];
        
        // Nếu có ảnh mới, thêm vào câu lệnh UPDATE
        if ($hinh_anh) {
            $sql .= ", hinh_anh=:ha";
            $params[':ha'] = $hinh_anh;
        }

        $sql .= " WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
    }

    public function getDetailById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tb_tour_chi_tiet WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function deleteDetail($id) {
        $stmt = $this->conn->prepare("DELETE FROM tb_tour_chi_tiet WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
?>