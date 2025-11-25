<?php
// FILE: D:\laragon\www\main\controllers\GuideController.php

// Đường dẫn tuyệt đối an toàn để gọi GuideModel
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../models/GuideModel.php';

class GuideController {
    private $guideModel;

    public function __construct() {
        $conn = get_connection();
        $this->guideModel = new GuideModel($conn);
    }

    // Hiển thị danh sách HDV
    public function index() {
        $guides = $this->guideModel->getAllGuides();
        include_once __DIR__ . '/../views/layouts/header.php';
        include_once __DIR__ . '/../views/guides/list.php';
        include_once __DIR__ . '/../views/layouts/footer.php';
    }

    // Hiển thị form thêm mới
    public function create() {
        include_once __DIR__ . '/../views/layouts/header.php';
        include_once __DIR__ . '/../views/guides/create.php';
        include_once __DIR__ . '/../views/layouts/footer.php';
    }

    // Xử lý thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $phone = trim($_POST['phone']) ?? null;
            $gender = $_POST['gender'] ?? null;
            $address = trim($_POST['address']) ?? null;

            // Kiểm tra Validation: email không được trùng
            if ($this->guideModel->checkEmailExists($email)) {
                echo "<script>alert('LỖI: Email này đã được sử dụng.'); window.history.back();</script>";
                return;
            }
            
            // Xử lý chèn vào DB
            if ($this->guideModel->insert($name, $email, $password, $phone, $gender, $address)) {
                echo "<script>alert('Thêm Hướng dẫn viên thành công!');</script>";
            } else {
                echo "<script>alert('Lỗi khi thêm Hướng dẫn viên.');</script>";
            }

            header("Location: ?act=guides");
            exit();
        }
    }

    // Hiển thị form sửa
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: ?act=guides");
            return;
        }

        $guide = $this->guideModel->getGuideById($id);
        
        if (!$guide) {
            echo "<script>alert('Không tìm thấy HDV.'); window.location.href='?act=guides';</script>";
            return;
        }

        // Load View và truyền biến $guide
        include_once __DIR__ . '/../views/layouts/header.php';
        include_once __DIR__ . '/../views/guides/edit.php';
        include_once __DIR__ . '/../views/layouts/footer.php';
    }

    // Xử lý cập nhật
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']) ?? null;
            $gender = $_POST['gender'] ?? null;
            $address = trim($_POST['address']) ?? null;
            $password = $_POST['password']; // Mật khẩu có thể null

            // Kiểm tra trùng email (loại trừ chính HDV đang sửa)
            if ($this->guideModel->checkEmailExists($email, $id)) {
                 echo "<script>alert('LỖI: Email này đã được sử dụng bởi người khác.'); window.history.back();</script>";
                return;
            }

            if ($this->guideModel->update($id, $name, $email, $phone, $gender, $address, $password)) {
                echo "<script>alert('Cập nhật Hướng dẫn viên thành công!');</script>";
            } else {
                 echo "<script>alert('Lỗi khi cập nhật Hướng dẫn viên hoặc không có gì thay đổi.');</script>";
            }

            header("Location: ?act=guides");
            exit();
        }
    }

    // Xóa HDV
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        // Cần bảo vệ: không cho phép xóa tài khoản đang đăng nhập
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $id) {
            echo "<script>alert('Không thể xóa tài khoản của chính bạn!'); window.location.href='?act=guides';</script>";
            return;
        }

        // Lỗi Foreign Key sẽ xảy ra nếu HDV còn Tour phụ trách
        if ($id && $this->guideModel->delete($id)) {
            echo "<script>alert('Xóa Hướng dẫn viên thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa Hướng dẫn viên. (Có thể do HDV này đang phụ trách Tour)');</script>";
        }

        header("Location: ?act=guides");
        exit();
    }

    // Dashboard cho Guide role
    public function dashboard() {
        // Lấy ID guide từ session
        $guideId = $_SESSION['user']['id'] ?? null;
        if (!$guideId) {
            header('Location: ?act=login');
            exit();
        }

        // Lấy dữ liệu cho guide
        $totalTours = $this->guideModel->countToursByGuideId($guideId);
        $totalGuests = $this->guideModel->countTotalGuestsByGuideId($guideId);
        $upcomingTours = $this->guideModel->getUpcomingToursByGuideId($guideId);

        include_once __DIR__ . '/../views/layouts/header.php';
        include_once __DIR__ . '/../views/guides/dashboard.php';
        include_once __DIR__ . '/../views/layouts/footer.php';
    }
}