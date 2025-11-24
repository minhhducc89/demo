<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - HiF Tour Management</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-white h-screen w-full overflow-hidden">

    <div class="flex h-full flex-wrap">
        
        <div class="hidden lg:flex w-1/2 bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=2021&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
            
            <div class="relative z-10 flex flex-col justify-end h-full p-16 text-white">
                <div class="mb-6">
                    <span class="px-3 py-1 border border-white/30 rounded-full text-xs font-medium uppercase tracking-wider backdrop-blur-sm bg-white/10">
                        Tour Management System v2.0
                    </span>
                </div>
                <h2 class="text-5xl font-bold leading-tight mb-4">Khám phá thế giới,<br>Quản lý dễ dàng.</h2>
                <p class="text-gray-300 text-lg max-w-md">Hệ thống quản lý tour du lịch chuyên nghiệp dành cho Admin và Hướng dẫn viên.</p>
                
                <div class="flex gap-8 mt-10 border-t border-white/20 pt-8">
                    <div>
                        <p class="text-3xl font-bold">10k+</p>
                        <p class="text-sm text-gray-400">Tours Hoàn thành</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">99%</p>
                        <p class="text-sm text-gray-400">Khách hài lòng</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 relative">
            
            <div class="absolute top-10 right-10 w-20 h-20 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
            <div class="absolute bottom-10 left-10 w-20 h-20 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>

            <div class="w-full max-w-md p-8">
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-600/30">
                            <i class="fa-solid fa-plane-departure"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">HiF Tour</h1>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Chào mừng trở lại! 👋</h2>
                    <p class="text-gray-500">Vui lòng nhập thông tin đăng nhập của bạn.</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-100 flex items-start gap-3 animate-pulse">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-red-800">Đăng nhập thất bại</h4>
                            <p class="text-xs text-red-600 mt-1"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="?act=check-login" method="POST" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm" 
                                   id="email" type="email" name="email" placeholder="admin@tour.com" required>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700" for="password">Mật khẩu</label>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Quên mật khẩu?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm" 
                                   id="password" type="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-600 cursor-pointer">Ghi nhớ đăng nhập 30 ngày</label>
                    </div>
                    
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-600/30 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0" type="submit">
                        Đăng nhập
                    </button>
                </form>
                
                
            </div>
        </div>
    </div>
</body>
</html>