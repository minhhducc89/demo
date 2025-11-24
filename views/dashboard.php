<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Tổng quan</h2>
        <p class="text-gray-500 text-sm mt-1">Chào mừng quay lại hệ thống quản lý Tour</p>
    </div>
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
    <a href="?act=tours-create" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded shadow-lg transition flex items-center font-medium text-sm">
        <span class="text-xl mr-2">+</span> Thêm Tour Mới
    </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    
    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h3 class="text-3xl font-bold"><?= $totalGuides ?? 0 ?></h3>
            </div>
            <p class="text-sm uppercase tracking-wide opacity-90">Hướng dẫn viên</p>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-16 opacity-30">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full"><path d="M0,49 C150,150 300,0 500,80 L500,150 L0,150 Z" fill="white"></path></svg>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-400 to-teal-500 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h3 class="text-3xl font-bold"><?= $totalTours ?? 0 ?></h3>
            </div>
            <p class="text-sm uppercase tracking-wide opacity-90">Tổng số Tour</p>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-16 opacity-30">
             <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full"><path d="M0,100 C150,200 350,0 500,100 L500,150 L0,150 Z" fill="white"></path></svg>
        </div>
    </div>

    <div class="bg-gradient-to-r from-orange-400 to-red-500 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="text-3xl font-bold"><?= $totalBookings ?? 0 ?></h3>
            </div>
            <p class="text-sm uppercase tracking-wide opacity-90">Tổng Booking</p>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-16 opacity-30">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full"><path d="M0,80 C150,0 350,150 500,50 L500,150 L0,150 Z" fill="white"></path></svg>
        </div>
    </div>

    <div class="bg-gradient-to-r from-lime-500 to-green-500 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <h3 class="text-2xl font-bold"><?= number_format($totalRevenue ?? 0) ?> <span class="text-lg font-normal">VNĐ</span></h3>
            </div>
            <p class="text-sm uppercase tracking-wide opacity-90">Tổng Doanh Thu</p>
        </div>
         <div class="absolute bottom-0 left-0 w-full h-12 flex items-end justify-between px-2 opacity-30 gap-1">
            <div class="w-1/12 bg-white h-4 rounded-t"></div>
            <div class="w-1/12 bg-white h-8 rounded-t"></div>
            <div class="w-1/12 bg-white h-6 rounded-t"></div>
            <div class="w-1/12 bg-white h-10 rounded-t"></div>
            <div class="w-1/12 bg-white h-5 rounded-t"></div>
            <div class="w-1/12 bg-white h-9 rounded-t"></div>
            <div class="w-1/12 bg-white h-7 rounded-t"></div>
            <div class="w-1/12 bg-white h-11 rounded-t"></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tour Mới Cập Nhật
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-5 py-3">Tên Tour</th>
                        <th class="px-5 py-3">Giá</th>
                        <th class="px-5 py-3">Ngày đi</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentTours)): ?>
                        <?php foreach($recentTours as $tour): ?>
                        <tr class="hover:bg-gray-50 border-b border-gray-100">
                            <td class="px-5 py-4 text-sm">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap font-medium"><?= $tour['name'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-900 font-bold">
                                <?= number_format($tour['price']) ?> đ
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                <?= date('d/m/Y', strtotime($tour['start_date'])) ?>
                            </td>
                            <td class="px-5 py-4 text-sm text-right">
                                <a href="?act=tours-detail&id=<?= $tour['id'] ?>" class="text-blue-600 hover:text-blue-900">Chi tiết</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-5 py-4 text-sm text-center text-gray-500">Chưa có dữ liệu tour.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-6">Tỷ lệ Tour/Guide</h3>
        <div class="relative h-64 w-full flex justify-center">
             <canvas id="pieChart"></canvas>
        </div>
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Tỷ lệ số lượng Tour so với Hướng dẫn viên</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Truyền dữ liệu từ PHP sang JS an toàn
    const totalTours = <?= $totalTours ?? 0 ?>;
    const totalGuides = <?= $totalGuides ?? 0 ?>;

    // Biểu đồ tròn (Doughnut) - Dùng dữ liệu thật
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Tổng Tour', 'Tổng HDV'],
            datasets: [{
                data: [totalTours, totalGuides],
                backgroundColor: ['#10b981', '#3b82f6'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'bottom' } 
            }
        }
    });
</script>