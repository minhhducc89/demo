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

            // Insert main tour and get ID
            $tourId = $this->tourModel->insertTour($name, $price, $start_date, $guide_id, $image, $desc, $category_id);

            // --- Gallery images (multiple) ---
            if (isset($_FILES['images'])) {
                // Normalize multiple files
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if (empty($files['name'][$i]) || $files['error'][$i] !== UPLOAD_ERR_OK) continue;
                    $file = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ];
                    $saved = file_upload($file, 'tour_images/');
                    if ($saved) {
                        $this->tourModel->insertTourImage($tourId, $saved, null);
                    }
                }
            }

            // --- Prices ---
            if (!empty($_POST['price_package_name']) && is_array($_POST['price_package_name'])) {
                $names = $_POST['price_package_name'];
                $adult = $_POST['price_adult'] ?? [];
                $child = $_POST['price_child'] ?? [];
                $app = $_POST['price_applicability'] ?? [];
                $inc = $_POST['price_included'] ?? [];
                foreach ($names as $idx => $pkg) {
                    $pkg = trim($pkg);
                    if ($pkg === '') continue;
                    $pAdult = floatval($adult[$idx] ?? 0);
                    $pChild = isset($child[$idx]) ? floatval($child[$idx]) : null;
                    $pApp = $app[$idx] ?? null;
                    $pInc = $inc[$idx] ?? null;
                    $this->tourModel->insertPrice($tourId, $pkg, $pAdult, $pChild, $pApp, $pInc);
                }
            }

            // --- Policies ---
            if (!empty($_POST['policy_type']) && is_array($_POST['policy_type'])) {
                foreach ($_POST['policy_type'] as $i => $ptype) {
                    $ptype = trim($ptype);
                    $pcontent = trim($_POST['policy_content'][$i] ?? '');
                    if ($ptype && $pcontent) {
                        $this->tourModel->insertPolicy($tourId, $ptype, $pcontent);
                    }
                }
            }

            // --- Suppliers ---
            if (!empty($_POST['supplier_name']) && is_array($_POST['supplier_name'])) {
                foreach ($_POST['supplier_name'] as $i => $sname) {
                    $sname = trim($sname);
                    if ($sname === '') continue;
                    $stype = trim($_POST['supplier_service_type'][$i] ?? '');
                    $sphone = trim($_POST['supplier_phone'][$i] ?? '');
                    $saddr = trim($_POST['supplier_address'][$i] ?? '');
                    $sid = $this->tourModel->insertSupplier($sname, $stype, $saddr, $sphone);
                    if ($sid) $this->tourModel->attachSupplierToTour($tourId, $sid);
                }
            }

            // --- Itinerary (legacy tour_details) ---
            if (!empty($_POST['it_ngay_thu']) && is_array($_POST['it_ngay_thu'])) {
                $days = $_POST['it_ngay_thu'];
                $titles = $_POST['it_tieu_de'] ?? [];
                $descs = $_POST['it_mo_ta'] ?? [];
                // Files for itinerary images may be in $_FILES['it_hinh_anh']
                $itFiles = $_FILES['it_hinh_anh'] ?? null;
                for ($i = 0; $i < count($days); $i++) {
                    $d = intval($days[$i]);
                    $t = trim($titles[$i] ?? '');
                    $m = trim($descs[$i] ?? '');
                    $hinh = null;
                    if ($itFiles && !empty($itFiles['name'][$i]) && $itFiles['error'][$i] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $itFiles['name'][$i],
                            'type' => $itFiles['type'][$i],
                            'tmp_name' => $itFiles['tmp_name'][$i],
                            'error' => $itFiles['error'][$i],
                            'size' => $itFiles['size'][$i]
                        ];
                        $hinh = file_upload($file, 'tour_details/');
                    }
                    if ($t || $m) {
                        $this->tourDetailModel->insertDetail($tourId, $d, $t ?: 'Hoạt động', $m, $hinh);
                    }
                }
            }

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

        // Related data for edit view: itinerary, policies, suppliers
        $itinerary = $this->tourModel->getItineraryByTourId($id);
        $policies = $this->tourModel->getPoliciesByTourId($id);
        $suppliers = $this->tourModel->getSuppliersByTourId($id);

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

    // [7b] Full tour detail (Gallery, Prices, Policies, Suppliers, Itinerary)
    public function detailFull() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?act=tours');
            exit();
        }

        $tour = $this->tourModel->getTourById($id);
        if (!$tour) {
            echo "Tour không tồn tại";
            exit;
        }

        // Fetch related data using TourModel helper methods
        $images = $this->tourModel->getImagesByTourId($id);
        $prices = $this->tourModel->getPricesByTourId($id);
        $policies = $this->tourModel->getPoliciesByTourId($id);
        $suppliers = $this->tourModel->getSuppliersByTourId($id);
        $itinerary = $this->tourModel->getItineraryByTourId($id);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/tours/detail_full.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // Suppliers: create + attach
    public function supplierStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ?act=tours'); exit(); }
        $tour_id = $_POST['tour_id'] ?? null;
        $supplier_name = trim($_POST['supplier_name'] ?? '');
        $service_type = trim($_POST['service_type'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($tour_id && $supplier_name) {
            // Create supplier and attach
            $supplierId = $this->tourModel->insertSupplier($supplier_name, $service_type, $address, $phone);
            if ($supplierId) {
                $this->tourModel->attachSupplierToTour($tour_id, $supplierId);
            }
        }
        header('Location: ?act=tours-detail-full&id=' . $tour_id);
        exit();
    }

    public function supplierDetach() {
        $tour_id = $_GET['tour_id'] ?? null;
        $supplier_id = $_GET['supplier_id'] ?? null;
        if ($tour_id && $supplier_id) {
            $this->tourModel->detachSupplierFromTour($tour_id, $supplier_id);
        }
        header('Location: ?act=tours-detail-full&id=' . $tour_id);
        exit();
    }

    // Policies: create + delete
    public function policyStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ?act=tours'); exit(); }
        $tour_id = $_POST['tour_id'] ?? null;
        $policy_type = trim($_POST['policy_type'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($tour_id && $policy_type && $content) {
            $this->tourModel->insertPolicy($tour_id, $policy_type, $content);
        }
        header('Location: ?act=tours-detail-full&id=' . $tour_id);
        exit();
    }

    public function policyDelete() {
        $tour_id = $_GET['tour_id'] ?? null;
        $policy_id = $_GET['policy_id'] ?? null;
        if ($policy_id) {
            $this->tourModel->deletePolicy($policy_id);
        }
        header('Location: ?act=tours-detail-full&id=' . $tour_id);
        exit();
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