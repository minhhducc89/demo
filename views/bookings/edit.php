<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Chỉnh Sửa Booking #<?php echo $booking['id']; ?></h2>

    <form action="?act=bookings-update" method="POST">
        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
        
        <div class="flex flex-wrap -mx-3 mb-4">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Tên Tour
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight" 
                       value="<?php echo $booking['tour_name']; ?>" disabled>
                <input type="hidden" name="tour_id" value="<?php echo $booking['tour_id']; ?>">
            </div>

            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Khách hàng đại diện
                </label>
                <input type="text" class="appearance-none block w-full bg-gray-100 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight" 
                       value="<?php echo $booking['customer_name']; ?> (SĐT: <?php echo $booking['customer_phone']; ?>)" disabled>
                <input type="hidden" name="customer_id" value="<?php echo $booking['customer_id']; ?>">
            </div>
        </div>
        
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label for="so_luong_khach" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Số lượng khách (*)
                </label>
                <input type="number" name="so_luong_khach" id="so_luong_khach" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" min="1" 
                       value="<?php echo $booking['so_luong_khach']; ?>" required>
                <p class="text-gray-600 text-xs italic mt-1">Lưu ý: Thay đổi SL khách cần thêm/xóa record check-in.</p>
            </div>
            
            <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                <label for="ngay_dat" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Ngày đặt (*)
                </label>
                <input type="date" name="ngay_dat" id="ngay_dat" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" 
                       value="<?php echo $booking['ngay_dat']; ?>" required>
            </div>
            
            <div class="w-full md:w-1/3 px-3">
                <label for="tong_tien" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Tổng tiền (*)
                </label>
                <input type="number" name="tong_tien" id="tong_tien" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" 
                       value="<?php echo $booking['tong_tien']; ?>" required>
            </div>
        </div>

        <div class="mb-6">
            <label for="trang_thai" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                Trạng thái Booking (*)
            </label>
            <select name="trang_thai" id="trang_thai" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" required>
                <?php 
                    $statuses = ['Mới', 'Đã xác nhận', 'Đang đi', 'Đã hoàn thành', 'Đã hủy'];
                    foreach ($statuses as $status):
                ?>
                    <option value="<?php echo $status; ?>" <?php echo ($booking['trang_thai'] == $status) ? 'selected' : ''; ?>>
                        <?php echo $status; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-6">
            <label for="ghi_chu" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                Ghi chú
            </label>
            <textarea name="ghi_chu" id="ghi_chu" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" rows="3"><?php echo $booking['ghi_chu']; ?></textarea>
        </div>

        <div class="flex items-center justify-start mt-6 space-x-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Cập nhật Booking
            </button>
            <a href="?act=bookings" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Hủy
            </a>
        </div>
    </form>
</div>