<div class="max-w-6xl mx-auto">
    <div class="mb-4">
        <a href="?act=tours" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Quay lại
        </a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden border-t-4 border-blue-600 mb-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($tour['name']) ?></h1>
            <div class="text-sm text-gray-600 mb-4">Giá: <span class="text-red-600 font-bold"><?= number_format($tour['price']) ?> đ</span> &middot; Ngày khởi hành: <?= date('d/m/Y', strtotime($tour['start_date'])) ?></div>

            <?php if (!empty($images)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="md:col-span-2">
                        <?php
                            // Use first image as hero if exists
                            $hero = $images[0]['file_path'] ?? null;
                            $heroUrl = $hero ? asset('uploads/tour_images/' . $hero) : asset('uploads/tours/' . ($tour['image'] ?? ''));
                        ?>
                        <div class="h-64 w-full rounded overflow-hidden border mb-3">
                            <img src="<?= htmlspecialchars($heroUrl) ?>" class="w-full h-full object-cover">
                        </div>

                        <div class="flex gap-2 overflow-x-auto">
                            <?php foreach ($images as $img): ?>
                                <?php $u = asset('uploads/tour_images/' . $img['file_path']); ?>
                                <div class="w-28 h-20 flex-shrink-0 rounded overflow-hidden border">
                                    <img src="<?= htmlspecialchars($u) ?>" class="w-full h-full object-cover">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <div class="bg-gray-50 p-4 rounded border">
                            <h3 class="font-bold mb-2">Gói giá</h3>
                            <?php if (!empty($prices)): ?>
                                <ul class="space-y-2 text-sm">
                                    <?php foreach ($prices as $p): ?>
                                        <li>
                                            <div class="font-semibold"><?= htmlspecialchars($p['package_name']) ?></div>
                                            <div class="text-xs text-gray-600">Người lớn: <?= number_format($p['price_adult'], 0) ?> đ<?php if(!empty($p['price_child'])): ?> &middot; Trẻ em: <?= number_format($p['price_child'], 0) ?> đ<?php endif; ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-sm text-gray-500">Chưa có giá cụ thể.</div>
                            <?php endif; ?>

                            <hr class="my-3">

                            <h3 class="font-bold mb-2">Thông tin nhanh</h3>
                            <div class="text-sm text-gray-700">
                                <div><strong>Mã tour:</strong> <?= $tour['id'] ?></div>
                                <div><strong>HDV:</strong> <?= htmlspecialchars($tour['guide_name'] ?? 'Chưa gán') ?></div>
                                <div><strong>Danh mục:</strong> <?= htmlspecialchars($tour['category_name'] ?? 'Không rõ') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="h-64 w-full rounded overflow-hidden border mb-6">
                    <img src="<?= htmlspecialchars(asset('uploads/tours/' . ($tour['image'] ?? ''))) ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>

            <div class="prose max-w-none mb-6">
                <h2>Mô tả</h2>
                <p><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold mb-3">Lịch trình</h3>

                    <?php if (empty($itinerary)): ?>
                        <div class="bg-white p-6 rounded border text-gray-500">Chưa có lịch trình chi tiết.</div>
                    <?php else: ?>
                        <?php foreach ($itinerary as $day): ?>
                            <div class="bg-white p-4 rounded border mb-3">
                                <div class="font-bold mb-2">Ngày <?= htmlspecialchars($day['day_number']) ?></div>
                                <?php foreach ($day['activities'] as $act): ?>
                                    <div class="mb-3 pb-3 border-b last:border-b-0">
                                        <!-- Tiêu đề hoạt động -->
                                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($act['location_name'] ?? ($act['tieu_de'] ?? 'Hoạt động')) ?></div>
                                        
                                        <!-- Mô tả -->
                                        <?php if (!empty($act['mo_ta'] ?? ($act['activity_details'] ?? ''))): ?>
                                            <div class="text-sm text-gray-600 mt-1"><?= nl2br(htmlspecialchars($act['mo_ta'] ?? ($act['activity_details'] ?? ''))) ?></div>
                                        <?php endif; ?>
                                        
                                        <!-- Ảnh nếu có -->
                                        <?php if (!empty($act['hinh_anh'] ?? null)): ?>
                                            <?php 
                                                $detailImgUrl = asset('uploads/tour_details/' . $act['hinh_anh']);
                                                $detailImgPath = upload_path('tour_details/' . $act['hinh_anh']);
                                            ?>
                                            <div class="mt-2">
                                                <img src="<?= htmlspecialchars($detailImgUrl) ?>" alt="<?= htmlspecialchars($act['tieu_de'] ?? '') ?>" class="w-full h-auto rounded border">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Time nếu có -->
                                        <?php if (!empty($act['start_time'])): ?>
                                            <div class="text-xs text-gray-500 mt-2">Giờ: <?= htmlspecialchars($act['start_time']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <h3 class="text-lg font-bold mb-3 mt-6">Chính sách</h3>
                    <?php if (!empty($policies)): ?>
                        <?php foreach ($policies as $pol): ?>
                            <div class="bg-white p-3 rounded border mb-2 flex justify-between items-start">
                                <div>
                                    <div class="font-semibold"><?= htmlspecialchars($pol['policy_type'] ?? 'Chính sách') ?></div>
                                    <div class="text-sm text-gray-700"><?= nl2br(htmlspecialchars($pol['content'])) ?></div>
                                </div>
                                <div>
                                    <a href="?act=tours-policy-delete&tour_id=<?= $tour['id'] ?>&policy_id=<?= $pol['id'] ?>" onclick="return confirm('Xóa chính sách này?')" class="text-red-500 text-xs">Xóa</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-sm text-gray-500">Chưa có chính sách.</div>
                    <?php endif; ?>

                    <form action="?act=tours-policy-store" method="POST" class="mt-3 space-y-2">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <input type="text" name="policy_type" placeholder="Loại chính sách (Ví dụ: Hủy/Hoàn)" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <textarea name="content" placeholder="Nội dung chính sách" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Thêm chính sách</button>
                        </div>
                    </form>
                </div>

                <aside class="bg-white p-4 rounded border">
                    <h4 class="font-bold mb-2">Đối tác / Nhà cung cấp</h4>
                    <?php if (!empty($suppliers)): ?>
                        <ul class="text-sm space-y-2 mb-3">
                            <?php foreach ($suppliers as $s): ?>
                                <li class="flex justify-between items-start">
                                    <div>
                                        <div class="font-semibold"><?= htmlspecialchars($s['supplier_name']) ?></div>
                                        <div class="text-xs text-gray-600"><?= htmlspecialchars($s['service_type'] ?? '') ?> <?php if(!empty($s['phone'])): ?> &middot; <?= htmlspecialchars($s['phone']) ?><?php endif; ?></div>
                                    </div>
                                    <div>
                                        <a href="?act=tours-supplier-detach&tour_id=<?= $tour['id'] ?>&supplier_id=<?= $s['id'] ?>" onclick="return confirm('Gỡ liên kết nhà cung cấp này?')" class="text-red-500 text-xs">Gỡ</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-sm text-gray-500 mb-3">Chưa liên kết nhà cung cấp.</div>
                    <?php endif; ?>

                    <form action="?act=tours-supplier-store" method="POST" class="space-y-2">
                        <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                        <input type="text" name="supplier_name" placeholder="Tên nhà cung cấp" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <input type="text" name="service_type" placeholder="Loại dịch vụ (ví dụ: Khách sạn)" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <input type="text" name="phone" placeholder="SĐT" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <textarea name="address" placeholder="Địa chỉ" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">Thêm</button>
                        </div>
                    </form>
                </aside>
            </div>
        </div>
    </div>
</div>