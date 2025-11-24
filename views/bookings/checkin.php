<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-xl">
    <div class="flex items-center justify-between mb-6 border-b pb-2">
        <h2 class="text-2xl font-bold text-gray-700">Điểm Danh Khách Hàng</h2>
        <a href="?act=bookings" class="text-sm text-blue-600 hover:underline">
            &larr; Quay lại danh sách Booking
        </a>
    </div>

    <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
        <p class="font-semibold text-purple-800">Booking #<?= $booking['id'] ?>:</p>
        <p class="text-lg font-bold text-gray-800"><?= $booking['tour_name'] ?></p>
        <p class="text-sm text-gray-600">Khách hàng: <?= $booking['customer_name'] ?> | SL Khách: <?= $booking['so_luong_khach'] ?></p>
    </div>

    <form action="?act=bookings-process-checkin" method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

        <table class="min-w-full leading-normal border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase w-1/4">Khách hàng</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase w-1/4">Điện thoại</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase w-1/4">Trạng thái hiện tại</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase w-1/4">Cập nhật điểm danh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($checkins as $c): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 text-sm text-gray-900 font-medium"><?= $c['customer_name'] ?> (Đại diện)</td>
                    <td class="px-5 py-4 text-sm text-gray-600"><?= $c['customer_phone'] ?></td>
                    <td class="px-5 py-4 text-center">
                        <?php
                            $status = $c['trang_thai'];
                            $bg_class = 'bg-yellow-100';
                            $text_class = 'text-yellow-800';
                            if ($status == 'Đã điểm danh') {
                                $bg_class = 'bg-green-100';
                                $text_class = 'text-green-800';
                            } elseif ($status == 'Vắng mặt') {
                                $bg_class = 'bg-red-100';
                                $text_class = 'text-red-800';
                            }
                        ?>
                        <span class="<?= $bg_class ?> <?= $text_class ?> px-3 py-1 rounded-full text-xs font-semibold">
                            <?= $status ?>
                        </span>
                        <?php if ($c['thoi_gian_diem_danh']): ?>
                            <br><small class="text-gray-500 italic text-xs"> lúc <?= date('H:i', strtotime($c['thoi_gian_diem_danh'])) ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        <select name="checkin_status[<?= $c['id'] ?>]" class="border rounded p-2 w-full">
                            <option value="Chưa điểm danh" <?= $c['trang_thai'] == 'Chưa điểm danh' ? 'selected' : '' ?>>Chưa điểm danh</option>
                            <option value="Đã điểm danh" <?= $c['trang_thai'] == 'Đã điểm danh' ? 'selected' : '' ?>>Đã điểm danh</option>
                            <option value="Vắng mặt" <?= $c['trang_thai'] == 'Vắng mặt' ? 'selected' : '' ?>>Vắng mặt</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition">
                Cập Nhật Trạng Thái Điểm Danh
            </button>
        </div>
    </form>
</div>