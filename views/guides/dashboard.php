<?php 
// Đảm bảo $upcomingTours, $totalTours, $totalGuests đã được truyền từ Controller
$guideName = $_SESSION['user']['name'] ?? 'Hướng Dẫn Viên';
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Xin Chào, <?= htmlspecialchars($guideName) ?>!</h2>
    <p class="text-gray-500 text-sm mt-1">Tổng quan về công việc của bạn.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <h3 class="text-3xl font-bold"><?= $totalTours ?? 0 ?></h3>
            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>
        </div>
        <p class="text-sm uppercase tracking-wide opacity-90 mt-2">Tổng số Tour đã/đang phụ trách</p>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <h3 class="text-3xl font-bold"><?= $totalGuests ?? 0 ?></h3>
            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <p class="text-sm uppercase tracking-wide opacity-90 mt-2">Tổng số Khách đã/đang phục vụ</p>
    </div>
    
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold">
                <?php if (!empty($upcomingTours)): ?>
                    <?= date('d/m/Y', strtotime($upcomingTours[0]['start_date'])) ?>
                <?php else: ?>
                    Không có
                <?php endif; ?>
            </h3>
            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <p class="text-sm uppercase tracking-wide opacity-90 mt-2">Ngày đi Tour gần nhất</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <h3 class="text-xl font-semibold text-gray-700 mb-4">Lịch làm việc (Tour sắp tới và Đang chạy)</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-5 py-3">Tên Tour</th>
                    <th class="px-5 py-3">Ngày đi</th>
                    <th class="px-5 py-3">Trạng thái</th>
                    <th class="px-5 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($upcomingTours)): ?>
                    <?php foreach($upcomingTours as $tour): ?>
                    <tr class="hover:bg-gray-50 border-b border-gray-100">
                        <td class="px-5 py-4 text-sm text-gray-900 font-medium"><?= $tour['name'] ?></td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                            (<?= $tour['duration_days'] ?> ngày)
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $tour['status'] == 'running' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>">
                                <?= $tour['status'] == 'running' ? 'Đang chạy' : 'Sắp tới' ?>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-right">
                            <a href="?act=bookings-checkin&tour_id=<?= $tour['id'] ?>" class="text-purple-600 hover:text-purple-900">
                                Chi tiết & Check-in
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-5 py-4 text-sm text-center text-gray-500">Hiện tại không có Tour sắp tới nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>