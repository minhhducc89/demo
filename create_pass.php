<?php
$pass = '123456';
$hash = password_hash($pass, PASSWORD_DEFAULT);
echo "Mật khẩu: " . $pass . "<br>";
echo "Mã hóa (Hash) cần lưu vào DB: " . $hash;
?>