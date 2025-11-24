<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tạo Booking Tour Mới</h3>
    
    <form action="?act=bookings-store" method="POST">
        
        <div class="flex flex-wrap -mx-3 mb-4">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="tour_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Tên Tour (*)
                </label>
                <select name="tour_id" id="tour_id" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" required>
                    <option value="">-- Chọn Tour --</option>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?php echo $tour['id']; ?>">
                            <?php echo $tour['name']; ?> (Giá: <?php echo number_format($tour['price']); ?> VNĐ)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:w-1/2 px-3">
                <label for="customer_id" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Khách hàng đại diện (*)
                </label>
                <select name="customer_id" id="customer_id" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" required>
                    <option value="">-- Chọn Khách hàng --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>">
                            <?php echo $customer['ho_ten']; ?> (SĐT: <?php echo $customer['sdt']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label for="so_luong_khach" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Số lượng khách (*)
                </label>
                <input type="number" name="so_luong_khach" id="so_luong_khach" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" min="1" required>
                <p class="text-gray-600 text-xs italic mt-1">Nhập số lượng khách (1-2 người là Khách Lẻ, >2 là Đoàn).</p>
            </div>
            
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label for="ngay_dat" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Ngày đặt (*)
                </label>
                <input type="date" name="ngay_dat" id="ngay_dat" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="w-full md:w-1/3 px-3">
                <label for="tong_tien" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Tổng tiền (*)
                </label>
                <input type="number" name="tong_tien" id="tong_tien" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" required>
                <p class="text-gray-600 text-xs italic mt-1">Nhập tổng số tiền Booking.</p>
            </div>
        </div>

        <div class="mb-6">
            <label for="trang_thai" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                Trạng thái Booking (*)
            </label>
            <select name="trang_thai" id="trang_thai" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" required>
                <option value="Mới">Mới</option>
                <option value="Đã xác nhận">Đã xác nhận</option>
                <option value="Đã hủy">Đã hủy</option>
            </select>
        </div>
        
        <div class="mb-6">
            <label for="ghi_chu" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                Ghi chú
            </label>
            <textarea name="ghi_chu" id="ghi_chu" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" rows="3"></textarea>
        </div>

        <div class="flex items-center justify-start mt-6 space-x-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Tạo Booking
            </button>
            <a href="?act=bookings" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Quay lại
            </a>
        </div>
    </form>
</div>