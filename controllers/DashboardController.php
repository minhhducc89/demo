<?php
class DashboardController {
    public $tourModel;
    public $userModel;

    public function __construct() {
        require_once __DIR__ . '/../models/TourModel.php';
        require_once __DIR__ . '/../commons/function.php';
        $conn = get_connection();
        $this->tourModel = new TourModel($conn);
        // Bạn có thể cần thêm method đếm user trong UserModel nếu muốn đếm nhân viên
    }

    public function index() {
        // Lấy thống kê
        $totalTours = count($this->tourModel->getAllTours());
        
        // Tính tổng doanh thu (Giả sử query lấy hết rồi cộng lại, hoặc viết hàm SQL SUM riêng sẽ tốt hơn)
        $allTours = $this->tourModel->getAllTours();
        $totalRevenue = 0;
        foreach ($allTours as $t) {
            $totalRevenue += $t['price'];
        }

        $guides = $this->tourModel->getAllGuides();
        $totalGuides = count($guides);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/dashboard/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
?>