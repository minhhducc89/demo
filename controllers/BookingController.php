<?php
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/TourModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';

class BookingController {
    public $model;
    public $tourModel;
    public $customerModel;

    public function __construct() { 
        $conn = get_connection();
        $this->model = new BookingModel($conn);
        $this->tourModel = new TourModel($conn); // Dùng để lấy danh sách Tour
        $this->customerModel = new CustomerModel($conn); // Dùng để lấy danh sách Khách hàng
    }

    public function index() {
        $bookings = $this->model->getAllBookings();
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/bookings/list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function create() {
        // Lấy danh sách cần thiết cho form dropdown
        $tours = $this->tourModel->getAllTours(); 
        $customers = $this->customerModel->getAll(); 

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/bookings/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = $_POST['tour_id'];
            $customer_id = $_POST['customer_id'];
            $ngay_dat = $_POST['ngay_dat'];
            $so_luong_khach = $_POST['so_luong_khach'];
            $tong_tien = $_POST['tong_tien'];
            $trang_thai = $_POST['trang_thai'];
            $ghi_chu = $_POST['ghi_chu'] ?? '';

            $this->model->insert($tour_id, $customer_id, $ngay_dat, $so_luong_khach, $tong_tien, $trang_thai, $ghi_chu);
            header("Location: ?act=bookings");
        }
    }

    public function edit() {
        $id = $_GET['id'];
        $booking = $this->model->getById($id);
        
        // Lấy danh sách để hiển thị tên (tour/khách hàng)
        $tours = $this->tourModel->getAllTours(); 
        $customers = $this->customerModel->getAll(); 

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/bookings/edit.php'; // <<< DÒNG NÀY ĐÃ ĐƯỢC THỰC HIỆN
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $ngay_dat = $_POST['ngay_dat'];
            $so_luong_khach = $_POST['so_luong_khach'];
            $tong_tien = $_POST['tong_tien'];
            $trang_thai = $_POST['trang_thai'];
            $ghi_chu = $_POST['ghi_chu'] ?? '';

            $this->model->update($id, $ngay_dat, $so_luong_khach, $tong_tien, $trang_thai, $ghi_chu);
            header("Location: ?act=bookings");
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            $this->model->delete($_GET['id']);
        }
        header("Location: ?act=bookings");
    }

    


    public function checkin() {
    $booking_id = $_GET['id'];
    
    // Lấy thông tin Booking
    $booking = $this->model->getById($booking_id);
    
    // Lấy danh sách điểm danh
    $checkins = $this->model->getCheckinsByBookingId($booking_id);

    // Xử lý nếu chưa có record Check-in (Phòng trường hợp BookingModel chưa chèn tự động)
    if (empty($checkins)) {
        // Nếu chưa có record check-in, tạo 1 record đại diện từ customer_id của booking
        if ($booking && isset($booking['customer_id'])) {
            $count = isset($booking['so_luong_khach']) ? intval($booking['so_luong_khach']) : 1;
            $this->model->insertCheckin($booking_id, $booking['customer_id'], $count);
            // Lấy lại danh sách sau khi chèn
            $checkins = $this->model->getCheckinsByBookingId($booking_id);
        }
    }

    require_once __DIR__ . '/../views/layouts/header.php';
    require_once __DIR__ . '/../views/bookings/checkin.php'; // View điểm danh mới
    require_once __DIR__ . '/../views/layouts/footer.php';
}

// XỬ LÝ ĐIỂM DANH (Cập nhật trạng thái)
public function processCheckin() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $booking_id = $_POST['booking_id'];
        
        // Lặp qua các khách hàng và cập nhật trạng thái
        if (isset($_POST['checkin_status']) && is_array($_POST['checkin_status'])) {
            $checkin_statuses = $_POST['checkin_status']; // Mảng [checkin_id => trạng_thai]
            
            foreach ($checkin_statuses as $checkin_id => $trang_thai) {
                $this->model->updateCheckinStatus($checkin_id, $trang_thai);
            }
        }
        
        // Cập nhật trạng thái Booking nếu cần thiết (ví dụ: chuyển từ "Mới" sang "Đang đi")
        // ... (Logic cập nhật trạng thái Booking nếu tất cả đã điểm danh)
        
        // Chuyển hướng về trang điểm danh
        header("Location: ?act=bookings-checkin&id=" . $booking_id);
    }
}

    
}
?>