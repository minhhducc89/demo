<?php
// Require các Model và helper chung
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../models/TourModel.php';
require_once __DIR__ . '/../models/TourDetailModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class TourController {
    public $tourModel;
    public $tourDetailModel;
    public $categoryModel;

    public function __construct() {
        $conn = get_connection();
        $this->tourModel = new TourModel($conn);
        $this->tourDetailModel = new TourDetailModel($conn);
        $this->categoryModel = new CategoryModel($conn);
    }

    // =========================================================================
    // PHẦN 1: QUẢN LÝ DASHBOARD (TỔNG QUAN)
    // =========================================================================
    public function dashboard() {
    // Khởi tạo model nếu chưa có (nên làm trong constructor)
    if (!isset($this->tourModel)) {
        $this->tourModel = new TourModel(get_connection());
    }

    // 1. Lấy dữ liệu thống kê cơ bản
    $totalTours = $this->tourModel->countTours();
    $totalGuides = $this->tourModel->countGuides();

    // 2. LẤY DỮ LIỆU MỚI CHO BOOKING VÀ REVENUE
    $totalBookings = $this->tourModel->countBookings(); // <--- MỚI
    $totalRevenue = $this->tourModel->calculateTotalRevenue(); // <--- MỚI
    
    // 3. Lấy dữ liệu danh sách tour mới nhất
    $recentTours = $this->tourModel->getRecentTours(5); // Lấy 5 tour gần nhất

    // 4. Chuẩn bị dữ liệu để truyền sang View
    $data = [
        'totalTours'    => $totalTours,
        'totalGuides'   => $totalGuides,
        'totalBookings' => $totalBookings, // <--- MỚI
        'totalRevenue'  => $totalRevenue, // <--- MỚI
        'recentTours'   => $recentTours,
    ];

    // 5. Load View và truyền dữ liệu
    include_once __DIR__ . '/../views/layouts/header.php';
    include_once __DIR__ . '/../views/dashboard.php';
    include_once __DIR__ . '/../views/layouts/footer.php';
}

    // =========================================================================
    // PHẦN 2: QUẢN LÝ TOUR CHÍNH (CRUD TOUR)
    // =========================================================================

    // [1] DANH SÁCH TOUR
    public function index() {
        $keyword = $_GET['keyword'] ?? null;
        $guide_id = $_GET['guide_id'] ?? null;
        $category_id = $_GET['category_id'] ?? null;

        $tours = $this->tourModel->getAllTours($keyword, $guide_id, $category_id);
        $guides = $this->tourModel->getAllGuides();
        $categories = $this->categoryModel->getAll();

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/tours/list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // [2] FORM THÊM TOUR
    public function create() {
        $guides = $this->tourModel->getAllGuides();
        $categories = $this->categoryModel->getAll();

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/tours/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // [3] LƯU TOUR MỚI
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $start_date = $_POST['start_date'];
            $guide_id = !empty($_POST['guide_id']) ? $_POST['guide_id'] : null;
            $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
            $desc = $_POST['description'];
            
            // Xử lý upload ảnh (Lưu vào thư mục uploads/)
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                // Save under uploads/tours/ (file_upload expects a folder relative to uploads/)
                $image = file_upload($_FILES['image'], 'tours/'); 
            }

            $this->tourModel->insertTour($name, $price, $start_date, $guide_id, $image, $desc, $category_id);
            header("Location: ?act=tours"); 
            exit();
        }
    }

    // [4] FORM SỬA TOUR
    public function edit() {
        $id = $_GET['id'];
        $tour = $this->tourModel->getTourById($id);
        $guides = $this->tourModel->getAllGuides();
        $categories = $this->categoryModel->getAll();

        if (!$tour) {
            echo "Tour không tồn tại!";
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/tours/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // [5] CẬP NHẬT TOUR
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $start_date = $_POST['start_date'];
            $guide_id = !empty($_POST['guide_id']) ? $_POST['guide_id'] : null;
            $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
            $desc = $_POST['description'];
            
            // Lấy ảnh cũ từ input hidden
            $old_image = $_POST['old_image']; 
            $image = $old_image; // Mặc định giữ ảnh cũ

            // Kiểm tra: Nếu có chọn ảnh mới thì Upload Mới -> Xóa Cũ
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                // Upload to uploads/tours/
                $new_image = file_upload($_FILES['image'], 'tours/');

                if ($new_image) {
                    $image = $new_image; // Cập nhật tên ảnh mới để lưu DB
                    // Xóa ảnh cũ trong uploads/tours/
                    file_delete($old_image, 'uploads/tours/');
                }
            }

            $this->tourModel->updateTour($id, $name, $price, $start_date, $guide_id, $image, $desc, $category_id);
            header("Location: ?act=tours");
            exit();
        }
    }

    // [6] XÓA TOUR
    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: ?act=tours'); exit(); }

        // Lấy thông tin tour để xóa ảnh
        $tour = $this->tourModel->getTourById($id);
        
        if ($tour) {
            // 1. Xóa ảnh tour chính (stored in uploads/tours/)
            file_delete($tour['image'], 'uploads/tours/');

            // 2. Xóa data trong DB
            $this->tourModel->delete($id);
        }

        header('Location: ?act=tours');
        exit();
    }


    // =========================================================================
    // PHẦN 3: QUẢN LÝ LỊCH TRÌNH CHI TIẾT (TOUR DETAILS)
    // =========================================================================
    
    // [7] HIỂN THỊ DANH SÁCH LỊCH TRÌNH CỦA 1 TOUR
    public function detail() {
        $id = $_GET['id']; // ID Tour
        $tour = $this->tourModel->getTourById($id);
        $details = $this->tourDetailModel->getDetailsByTourId($id);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/tours/detail_schedule.php'; 
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // [8] THÊM LỊCH TRÌNH CHI TIẾT
    public function storeDetail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = $_POST['tour_id'];
            $ngay_thu = $_POST['ngay_thu'];
            $tieu_de = $_POST['tieu_de'];
            $mo_ta = $_POST['mo_ta'];
            
            // Xử lý upload ảnh (Lưu vào uploads/tour_details/)
            $hinh_anh = null;
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['size'] > 0) {
                // Save under uploads/tour_details/
                $hinh_anh = file_upload($_FILES['hinh_anh'], 'tour_details/'); 
            }

            $this->tourDetailModel->insertDetail($tour_id, $ngay_thu, $tieu_de, $mo_ta, $hinh_anh);
            
            header("Location: ?act=tours-detail&id=" . $tour_id);
            exit();
        }
    }

    // [9] FORM SỬA LỊCH TRÌNH
    public function editDetail() {
        $id = $_GET['id']; // ID chi tiết
        $detail = $this->tourDetailModel->getDetailById($id);
        
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/tours/edit_detail.php'; 
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // [10] CẬP NHẬT LỊCH TRÌNH
    public function updateDetail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $tour_id = $_POST['tour_id']; 
            $ngay_thu = $_POST['ngay_thu'];
            $tieu_de = $_POST['tieu_de'];
            $mo_ta = $_POST['mo_ta'];
            
            $hinh_anh_cu = $_POST['hinh_anh_cu'] ?? null;
            $hinh_anh = $hinh_anh_cu; // Mặc định giữ ảnh cũ

            // Nếu user chọn ảnh mới
                if (isset($_FILES['hinh_anh_moi']) && $_FILES['hinh_anh_moi']['size'] > 0) {
                    // Upload ảnh mới vào uploads/tour_details/
                    $hinh_anh_moi_upload = file_upload($_FILES['hinh_anh_moi'], 'tour_details/'); 

                    if ($hinh_anh_moi_upload) {
                        $hinh_anh = $hinh_anh_moi_upload;
                        // Xóa ảnh cũ trong uploads/tour_details/
                        file_delete($hinh_anh_cu, 'uploads/tour_details/'); 
                    }
                }
            
            $this->tourDetailModel->updateDetail($id, $ngay_thu, $tieu_de, $mo_ta, $hinh_anh);
            header("Location: ?act=tours-detail&id=" . $tour_id);
            exit();
        }
    }
    
    // [11] XÓA LỊCH TRÌNH
    public function deleteDetail() {
        $id = $_GET['id'];
        $tour_id = $_GET['tour_id']; // Để redirect về đúng trang

        // Lấy thông tin để xóa file ảnh
        $detail = $this->tourDetailModel->getDetailById($id);
        
        if ($detail && $detail['hinh_anh']) {
            file_delete($detail['hinh_anh'], 'uploads/tour_details/');
        }
        
        $this->tourDetailModel->deleteDetail($id);
        header("Location: ?act=tours-detail&id=" . $tour_id);
        exit();
    }

} // End Class TourController


?>