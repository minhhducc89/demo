<?php
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    public $userModel;

    public function __construct() {
        $conn = get_connection();
        $this->userModel = new UserModel($conn);
    }

    public function showLoginForm() {
        // Nếu đã đăng nhập rồi thì đẩy về trang chủ, không cho vào form login nữa
        if (isset($_SESSION['user'])) {
            header("Location: index.php");
            exit();
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->getUserByEmail($email);

            // Kiểm tra user có tồn tại và mật khẩu có khớp không
            if ($user && password_verify($password, $user['password'])) {
                // Đăng nhập thành công
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role'] // admin hoặc guide
                ];
                header("Location: index.php");
            } else {
                // Đăng nhập thất bại
                $_SESSION['error'] = "Email hoặc mật khẩu không đúng!";
                header("Location: index.php?act=login");
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?act=login");
        exit();
    }
}
?>