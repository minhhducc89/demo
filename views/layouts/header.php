<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiFAdmin - Tour Management</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f3f5f9; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }
    </style>
</head>
<body class="text-gray-800 antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-white flex flex-col shadow-lg z-20 hidden md:flex">
            <div class="h-20 flex items-center px-8 border-b border-gray-100">
                <a href="?act=/" class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-lg">Hi</div>
                    F<span class="text-blue-500">ADMIN</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                <a href="?act=/" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= (!isset($_GET['act']) || $_GET['act']=='/') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <a href="?act=tours" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= (isset($_GET['act']) && strpos($_GET['act'], 'tours') !== false) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Quản lý Tour
                </a>
                <a href="?act=bookings" class="flex items-center px-4 py-3 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Booking & Check-in
                

                
                <?php if($_SESSION['user']['role'] == 'admin'): ?>
                <a href="?act=guides" class="flex items-center px-4 py-3 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Hướng dẫn viên
                </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                <!-- <a href="?act=tours-create" class="flex items-center px-4 py-3 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Thêm Tour Mới
                </a> -->
                </a>
                                <a href="?act=customers" class="flex items-center px-4 py-3 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Khách hàng
                </a>
                <?php endif; ?>
                
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Hệ thống</p>
                    <a href="?act=logout" class="flex items-center px-4 py-3 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Đăng xuất
                    </a>
                </div>
            </nav>
        </aside>
        
        <div class="flex-1 flex flex-col overflow-hidden bg-[#f3f5f9]">
            <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10">
                
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-gray-700">
                        <?php 
                            // Hiển thị tiêu đề động tùy theo trang
                            if(!isset($_GET['act']) || $_GET['act']=='/') echo "Dashboard Overview";
                            elseif(strpos($_GET['act'], 'tours') !== false) echo "Quản lý Tours";
                            else echo "Hệ thống";
                        ?>
                    </h2>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4 text-gray-400">
                        <button class="hover:text-blue-600 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>

                    <?php if(isset($_SESSION['user'])): ?>
                    <div class="flex items-center gap-3 cursor-pointer">
                        <div class="text-right hidden md:block">
                            <div class="text-sm font-semibold text-gray-700"><?= $_SESSION['user']['name'] ?></div>
                            <div class="text-xs text-gray-400"><?= $_SESSION['user']['role'] == 'admin' ? 'Administrator' : 'Guide' ?></div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border-2 border-white shadow">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['name']) ?>&background=random" alt="User" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">