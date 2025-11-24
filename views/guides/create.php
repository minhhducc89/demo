<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Thêm Hướng Dẫn Viên Mới</h2>
    <p class="text-gray-500 text-sm mt-1">Tạo tài khoản và hồ sơ chi tiết cho HDV.</p>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 max-w-2xl mx-auto">
    <form action="?act=guides-store" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ Tên <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required class="w-full border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="Nguyễn Văn A">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" required class="w-full border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="nguyenvana@tour.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" required class="w-full border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Điện thoại</label>
                <input type="text" name="phone" id="phone" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="090...">
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Giới tính</label>
                <select name="gender" id="gender" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Chọn giới tính</option>
                    <option value="1">Nam</option>
                    <option value="0">Nữ</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                <textarea name="address" id="address" rows="3" class="w-full border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="Địa chỉ hiện tại..."></textarea>
            </div>
            
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end gap-3">
            <a href="?act=guides" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md shadow-sm transition font-medium">Hủy</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium">Thêm HDV</button>
        </div>
    </form>
</div>