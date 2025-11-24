<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-700">Thêm Khách Hàng Mới</h2>
        <a href="?act=customers" class="text-sm text-blue-600 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Quay lại danh sách
        </a>
    </div>

    <form action="?act=customers-store" method="POST">
        <div class="space-y-4">
            <div>
                <label for="ho_ten" class="block text-sm font-medium text-gray-700">Họ tên khách hàng</label>
                <input type="text" name="ho_ten" id="ho_ten" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="sdt" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                <input type="text" name="sdt" id="sdt" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="dia_chi" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                <textarea name="dia_chi" id="dia_chi" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition">
                Lưu Khách Hàng
            </button>
        </div>
    </form>
</div>