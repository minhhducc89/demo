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
                            <div class="text-sm text-gray-700 space-y-2">
                                <div class="flex items-center gap-2">
                                    <strong class="text-gray-600">Mã tour:</strong>
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">
                                        <?= $tour['id'] ?>
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <strong class="text-gray-600">HDV:</strong>
                                    <?php if(isset($tour['guide_name']) && $tour['guide_name']): ?>
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <?= htmlspecialchars($tour['guide_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Chưa gán</span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center gap-2">
                                    <strong class="text-gray-600">Danh mục:</strong>
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($tour['category_name'] ?? 'Không rõ') ?>
                                    </span>
                                </div>
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

                    <h3 class="text-lg font-bold mb-3 mt-6">Chính sách</h3>

                                <?php if (!empty($policies)): ?>
                                    <?php foreach ($policies as $pol): ?>
                                        <div class="bg-white p-3 rounded border mb-2">
                                            <div class="font-semibold"><?= htmlspecialchars($pol['policy_type'] ?? 'Chính sách') ?></div>
                                            <div class="text-sm text-gray-700"><?= nl2br(htmlspecialchars($pol['content'])) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-sm text-gray-500">Chưa có chính sách.</div>
                                <?php endif; ?>


                                <aside class="bg-white p-4 rounded border mt-6">
                                    <h4 class="font-bold mb-2">Đối tác / Nhà cung cấp</h4>

                                    <?php if (!empty($suppliers)): ?>
                                        <ul class="text-sm space-y-2 mb-3">
                                            <?php foreach ($suppliers as $s): ?>
                                                <li class="flex justify-between items-start">
                                                    <div>
                                                        <div class="font-semibold"><?= htmlspecialchars($s['supplier_name']) ?></div>

                                                        <div class="text-xs text-gray-600">
                                                            <?= htmlspecialchars($s['service_type'] ?? '') ?>
                                                            <?php if (!empty($s['phone'])): ?>
                                                                &middot; <?= htmlspecialchars($s['phone']) ?>
                                                            <?php endif; ?>
                                                        </div>

                                                        <?php if (!empty($s['address'])): ?>
                                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($s['address']) ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="text-sm text-gray-500 mb-3">Chưa liên kết nhà cung cấp.</div>
                                    <?php endif; ?>
                                </aside>

                   
            </div>
        </div>
    </div>
</div>