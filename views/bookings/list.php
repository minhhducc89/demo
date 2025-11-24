<div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
    
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-extrabold text-gray-900 border-l-4 border-teal-500 pl-4">
            <i class="fas fa-calendar-check text-teal-500 mr-2"></i>
            Booking & Check-in
        </h2>
        
        <a href="?act=bookings-create" 
           class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-full flex items-center gap-2 transition duration-300 shadow-lg shadow-teal-500/50 transform hover:scale-[1.02]">
            <i class="fas fa-plus h-4 w-1"></i>Tạo Booking Mới
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-xl">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">Thông tin Khách & Tour</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">Ngày đặt</th>
                    <th class="px-3 py-4 text-center text-xs font-extrabold text-gray-600 uppercase tracking-wider">SL Khách</th>
                    <th class="px-6 py-4 text-right text-xs font-extrabold text-gray-600 uppercase tracking-wider">Tổng tiền</th>
                    
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">Trạng thái Booking</th>
                    <th class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">Điểm danh</th>
                    <th class="px-6 py-4 text-center text-xs font-extrabold text-gray-600 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($bookings as $booking): 
                    // Logic Tính toán tỷ lệ điểm danh (Giữ nguyên)
                    $checkin_status_count = $booking['checked_in_count'] ?? 0;
                    $checkin_total_count = $booking['total_checkin_records'] ?? 0;
                    
                    if ($checkin_total_count == 0) {
                           $checkin_status_text = 'Chưa tạo Check-in';
                           $checkin_class = 'bg-red-100 text-red-800 border border-red-300'; 
                    } else {
                        $checkin_status_text = $checkin_status_count . ' / ' . $checkin_total_count . ' Khách';
                        $checkin_class = ($checkin_status_count == $checkin_total_count) ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-yellow-100 text-yellow-800 border border-yellow-300';
                    }

                    // Logic Trạng thái Booking (Giữ nguyên)
                    $status_text = $booking['trang_thai'] ?? 'Mới';
                    $status_class = 'bg-blue-100 text-blue-800 border border-blue-300'; 
                    if ($status_text == 'Đã xác nhận' || $status_text == 'Đã hoàn thành') {
                        $status_class = 'bg-teal-100 text-teal-800 border border-teal-300'; 
                    } else if ($status_text == 'Đã hủy') {
                        $status_class = 'bg-red-100 text-red-800 border border-red-300';
                    }

                    // START: LOGIC PHÂN LOẠI KHÁCH LẺ/KHÁCH ĐOÀN
                    $so_luong_khach = $booking['so_luong_khach'] ?? 0;
                    if ($so_luong_khach <= 2) {
                        $pax_type_text = 'Khách Lẻ';
                        $pax_type_icon = '<i class="fas fa-user text-teal-500 mr-1"></i>'; // Icon khách lẻ
                        $pax_count_class = 'text-teal-600 bg-teal-50'; // Màu Teal cho khách lẻ
                    } else {
                        $pax_type_text = 'Khách Đoàn';
                        $pax_type_icon = '<i class="fas fa-users text-orange-500 mr-1"></i>'; // Icon khách đoàn
                        $pax_count_class = 'text-orange-600 bg-orange-50'; // Màu Cam/Vàng cho khách đoàn
                    }
                    // END: LOGIC PHÂN LOẠI KHÁCH LẺ/KHÁCH ĐOÀN
                ?>
                <tr class="hover:bg-teal-50 transition duration-150">
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-bold text-gray-900">#<?php echo $booking['id']; ?></td>
                    
                    <td class="px-6 py-4">
                        <div class="text-base font-semibold text-gray-900">
                            <i class="fas fa-user-circle text-gray-400 mr-1"></i>
                            <?php echo $booking['customer_name']; ?>
                        </div>
                        <div class="text-xs text-teal-600 mt-0.5">
                             <i class="fas fa-route text-teal-400 mr-1"></i>
                            <?php echo $booking['tour_name']; ?>
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo date('d/m/Y', strtotime($booking['ngay_dat'])); ?>
                    </td>
                    
                    <td class="px-3 py-4 whitespace-nowrap text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-lg font-extrabold px-2 py-1 rounded <?= $pax_count_class; ?>">
                                <?php echo $so_luong_khach; ?>
                            </span>
                            <div class="text-xs font-semibold mt-1 text-gray-700">
                                <?= $pax_type_icon; ?><?php echo $pax_type_text; ?>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-base font-extrabold text-red-600">
                            <?php echo number_format($booking['tong_tien']); ?> đ
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?= $status_class; ?>">
                            <?php echo $status_text; ?>
                        </span>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?= $checkin_class; ?>">
                            <?php echo $checkin_status_text; ?>
                        </span>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            
                            <a href="?act=bookings-checkin&id=<?php echo $booking['id']; ?>" 
                               title="Quản lý điểm danh"
                               class="text-teal-600 hover:text-teal-800 p-2 rounded-full hover:bg-teal-50 transition duration-150 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.001 12.001 0 002 15c0 3.012 1.953 5.733 4.887 7.042A12.004 12.004 0 0112 22c3.21 0 6.136-1.546 8-3.958a11.955 11.955 0 01-2.382-6.048z"></path></svg>
                            </a>
                            
                            <a href="?act=bookings-edit&id=<?php echo $booking['id']; ?>" 
                               title="Sửa thông tin"
                               class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-50 transition duration-150 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-9-1.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM19.5 9l-4.5 4.5L10.5 9l4.5-4.5L19.5 9z"></path></svg>
                            </a>
                            
                            <a href="?act=bookings-delete&id=<?php echo $booking['id']; ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa Booking #<?php echo $booking['id']; ?> không?');" 
                               title="Xóa"
                               class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition duration-150 transform hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                            
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>