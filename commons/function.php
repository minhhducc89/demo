<?php
// FILE: D:\laragon\www\main\commons\function.php

// Hàm kết nối CSDL (get_connection)
function get_connection() {
    // Các hằng số DB_HOST, DB_NAME, DB_USER, DB_PASS PHẢI được định nghĩa trong env.php
    $host = DB_HOST; 
    $db = DB_NAME;
    $user = DB_USER;
    $pass = DB_PASS;

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Lỗi kết nối CSDL: " . $e->getMessage()); 
    }
}

// Hàm upload file (sử dụng cho các Controller)
function uploadFile($file, $targetDir) {
    // 1. Chuẩn hóa đường dẫn: Thêm dấu '/' vào cuối nếu chưa có
    $targetDir = rtrim($targetDir, '/') . '/';
    
    // 2. Định nghĩa đường dẫn chứa file
    // Dùng __DIR__ để lấy đường dẫn tuyệt đối của file function.php, sau đó ra ngoài 1 cấp
    $uploadPath = __DIR__ . '/../uploads/' . $targetDir;

    // 3. Kiểm tra và tạo thư mục nếu chưa tồn tại
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // 4. Kiểm tra lỗi file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        // Có thể log lỗi ở đây nếu cần
        return null;
    }

    // 5. Tạo tên file mới để tránh trùng lặp
    // Lấy đuôi file (jpg, png...)
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    // Tên file = Thời gian + Số ngẫu nhiên + Đuôi file
    $fileName = time() . '-' . mt_rand(1000, 9999) . '.' . $extension;
    
    // Đường dẫn đích cuối cùng
    $targetFile = $uploadPath . $fileName;

    // 6. Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // Trả về tên file kèm thư mục con (ví dụ: tours/abc.jpg) 
        // hoặc chỉ tên file tùy vào cách bạn lưu trong DB. 
        // Dựa vào code view của bạn ($imgUrl = asset('uploads/tours/' . $imgFile)), 
        // bạn chỉ đang lưu TÊN FILE trong DB.
        return $fileName; 
    }
    
    return null;
}

// Hàm bổ trợ/tương thích (Cho TourController)
if (!function_exists('file_upload')) {
    function file_upload($file, $folder = 'uploads/') {
        // Dùng lại hàm uploadFile
        return uploadFile($file, $folder);
    }
}

// Hàm xóa file (sử dụng cho các Controller)
function delete_file($fileName, $targetDir) {
    // Đường dẫn tương đối từ commons/
    $fullPath = __DIR__ . '/../uploads/' . $targetDir . $fileName; 
    
    if (file_exists($fullPath) && is_file($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

// Hàm bổ trợ/tương thích (Cho TourController)
if (!function_exists('file_delete')) {
    function file_delete($fileName, $folder = 'uploads/') {
        // Đường dẫn tương đối từ commons/
        $fullPath = __DIR__ . '/../' . $folder . $fileName;
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}

// Asset URL helper: build absolute URL for assets (images, css, js)
if (!function_exists('asset')) {
    if (!function_exists('asset')) {
    function asset($path) {
        // Lấy BASE_URL đã được định nghĩa, loại bỏ dấu / thừa ở cuối
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        
        // Loại bỏ dấu / thừa ở đầu $path
        $p = ltrim($path, '/');
        
        // Kết hợp và đảm bảo chỉ có 1 dấu /
        return $base . '/' . $p; 
    }
}
}

// Server filesystem path helper for uploads
if (!function_exists('upload_path')) {
    if (!function_exists('upload_path')) {
    function upload_path($relativePath) {
        // 1. Lấy đường dẫn gốc của dự án (Ví dụ: D:/laragon/www/main/)
        $rootPath = __DIR__ . '/../'; 
        
        // 2. Xây dựng đường dẫn đầy đủ
        $fullPath = $rootPath . 'uploads/' . ltrim($relativePath, '/');
        
        // 3. SỬ DỤNG realpath: Hàm này chuyển đổi đường dẫn sang định dạng chuẩn của OS
        // Nếu file tồn tại, realpath sẽ trả về đường dẫn chuẩn (ví dụ: dùng dấu \), 
        // giúp file_exists() hoạt động chính xác.
        // Dùng ?: để đảm bảo trả về đường dẫn gốc nếu file chưa tồn tại (ví dụ: khi upload lần đầu)
        return realpath($fullPath) ?: $fullPath; 
    }
}
}
