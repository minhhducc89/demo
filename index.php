<?php 
session_start(); // BẮT BUỘC phải có ở đầu file

// Require files Common
require_once './commons/env.php'; 
require_once './commons/function.php'; 

// Require Controllers
require_once './controllers/TourController.php';
require_once './controllers/AuthController.php'; 
require_once './controllers/BookingController.php'; 
require_once './controllers/CustomerController.php';
require_once './controllers/GuideController.php'; 

// Require Models
require_once './models/TourModel.php';
require_once './models/UserModel.php'; 
require_once './models/BookingModel.php';
require_once './models/TourDetailModel.php';
require_once './models/CustomerModel.php'; // Cần thiết cho CustomerController và BookingController
require_once './models/GuideModel.php'; 

// Route
$act = $_GET['act'] ?? '/';

// -------------------------------------------------------------------------
// *** KHẮC PHỤC LỖI ARGUMENTCOUNT VÀ DI ***
// -------------------------------------------------------------------------

// Khởi tạo Controllers (Controllers tự khởi tạo Model bên trong)
$authController = new AuthController();
$tourController = new TourController();
$bookingController = new BookingController();
$customerController = new CustomerController();
$guideController = new GuideController();

// -------------------------------------------------------------------------
// --- MIDDLEWARE: KIỂM TRA ĐĂNG NHẬP ---
if (!isset($_SESSION['user']) && $act !== 'login' && $act !== 'check-login') {
    header("Location: ?act=login");
    exit();
}

// --- MIDDLEWARE: KIỂM TRA QUYỀN ADMIN ---
// Danh sách các hành động chỉ Admin mới được làm
$adminActions = [
    'tours-create', 'tours-store', 'tours-edit', 'tours-update', 'tours-delete',
    'guides-create', 'guides-store', 'guides-edit', 'guides-update', 'guides-delete'
];

if (in_array($act, $adminActions) && $_SESSION['user']['role'] !== 'admin') {
    die("BẠN KHÔNG CÓ QUYỀN THỰC HIỆN CHỨC NĂNG NÀY! <a href='?act=/'>Quay lại</a>");
}

// --- MIDDLEWARE: ROUTE DASHBOARD THEO ROLE ---
if ($act === '/' || $act === 'dashboard') {
    if ($_SESSION['user']['role'] === 'guide') {
        $act = 'guide-dashboard';
    }
}

// --- ROUTING (SỬ DỤNG BIẾN CONTROLLER ĐÃ KHỞI TẠO) ---
match ($act) {

    // Auth Routes
    'login'         => $authController->showLoginForm(),
    'check-login'   => $authController->login(),
    'logout'        => $authController->logout(),

    // Dashboard & Home
    '/'             => $tourController->dashboard(),
    'dashboard'     => $tourController->dashboard(),
    'guide-dashboard' => $guideController->dashboard(),
    
    // Quản lý Tours
    'tours'         => $tourController->index(),
    'tours-create'  => $tourController->create(),
    'tours-store'   => $tourController->store(),
    'tours-edit'    => $tourController->edit(),
    'tours-update'  => $tourController->update(),
    'tours-delete'  => $tourController->delete(),

    // Quản lý Hướng Dẫn Viên (Guides Management)
    'guides'        => $guideController->index(),
    'guides-create' => $guideController->create(),
    'guides-store'  => $guideController->store(),
    'guides-edit'   => $guideController->edit(), 
    'guides-update' => $guideController->update(), 
    'guides-delete' => $guideController->delete(),
    
    // Quản lý Bookings (FIXED: Sử dụng $bookingController)
    'bookings'              => $bookingController->index(),
    'bookings-create'       => $bookingController->create(),
    'bookings-store'        => $bookingController->store(),
    'bookings-edit'         => $bookingController->edit(),
    'bookings-update'       => $bookingController->update(),
    'bookings-delete'       => $bookingController->delete(),
    'bookings-checkin'      => $bookingController->checkin(),        
    'bookings-process-checkin' => $bookingController->processCheckin(),

    // Quản lý Customers
    'customers'         => $customerController->index(),
    'customers-create'  => $customerController->create(),
    'customers-store'   => $customerController->store(),
    'customers-edit'    => $customerController->edit(),
    'customers-update'  => $customerController->update(),
    'customers-delete'  => $customerController->delete(),

    // TOUR CHI TIẾT (Tour Details)
    'tours-detail'          => $tourController->detail(),
    'tours-detail-full'     => $tourController->detailFull(),
    'tours-supplier-store'  => $tourController->supplierStore(),
    'tours-supplier-detach' => $tourController->supplierDetach(),
    'tours-policy-store'    => $tourController->policyStore(),
    'tours-policy-delete'   => $tourController->policyDelete(),
    'tours-detail-store'    => $tourController->storeDetail(),
    'tours-detail-edit'     => $tourController->editDetail(),
    'tours-detail-update'   => $tourController->updateDetail(),
    'tours-detail-delete'   => $tourController->deleteDetail(),
    
    // Trang 404 mặc định nếu không khớp case nào
    default => $tourController->dashboard(), 
};