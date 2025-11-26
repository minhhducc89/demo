<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="?act=tours" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Quay lại
        </a>
    </div>
    <div class="bg-white rounded shadow overflow-hidden border-t-4 border-blue-500">

        <!-- Tiêu đề -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">
                Cập nhật Tour: <?= htmlspecialchars($tour['name']) ?>
            </h2>
        </div>

        <!-- Form chính: Cập nhật Tour -->
        <form action="?act=tours-update" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">

            <input type="hidden" name="id" value="<?= $tour['id'] ?>">
            <input type="hidden" name="old_image" value="<?= $tour['image'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Tên Tour -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên Tour <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($tour['name']) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Giá -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giá (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required value="<?= htmlspecialchars($tour['price']) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Ngày khởi hành -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày khởi hành <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required value="<?= htmlspecialchars($tour['start_date']) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Danh mục Tour -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục Tour</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $tour['category_id'] == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Hướng dẫn viên -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn viên</label>
                    <select name="guide_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">-- Chưa phân công --</option>
                        <?php foreach ($guides as $g): ?>
                            <option value="<?= $g['id'] ?>" <?= $tour['guide_id'] == $g['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['name']) ?> (<?= htmlspecialchars($g['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Ảnh cover -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh cover</label>
                    <?php if (!empty($tour['image'])): ?>
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-1">Ảnh hiện tại:</p>
                            <img src="uploads/tours/<?= htmlspecialchars($tour['image']) ?>" alt="Ảnh cover hiện tại" class="w-24 h-20 object-cover rounded shadow border">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">* Chỉ chọn nếu muốn thay đổi ảnh.</p>
                </div>

                <!-- Mô tả -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                    <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($tour['description']) ?></textarea>
                </div>

            </div>

            <!-- Nút Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="?act=tours" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Hủy bỏ</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Cập nhật Tour
                </button>
            </div>
        </form>

       

        <!-- ======================== -->
        <!-- CHÍNH SÁCH (Form riêng) -->
        <!-- ======================== -->
        <div class="bg-gray-50 p-4 rounded border mt-6">
            <h3 class="font-bold mb-3">Chính sách</h3>

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

            <!-- Thêm chính sách -->
            <form action="?act=tours-policy-store" method="POST" class="mt-3 space-y-2">
                <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                <input type="text" name="policy_type" placeholder="Loại chính sách (Ví dụ: Hủy/Hoàn)" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                <textarea name="content" placeholder="Nội dung chính sách" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Thêm chính sách</button>
                </div>
            </form>
        </div>

        <!-- ======================== -->
        <!-- NHÀ CUNG CẤP (Form riêng) -->
        <!-- ======================== -->
        <div class="bg-gray-50 p-4 rounded border mt-6">
            <h3 class="font-bold mb-3">Đối tác / Nhà cung cấp</h3>

            <?php if (!empty($suppliers)): ?>
                <ul class="text-sm space-y-2 mb-3">
                    <?php foreach ($suppliers as $s): ?>
                        <li class="flex justify-between items-start bg-white p-3 rounded border">
                            <div>
                                <div class="font-semibold"><?= htmlspecialchars($s['supplier_name']) ?></div>
                                <div class="text-xs text-gray-600"><?= htmlspecialchars($s['service_type'] ?? '') ?><?php if(!empty($s['phone'])): ?> &middot; <?= htmlspecialchars($s['phone']) ?><?php endif; ?></div>
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

            <!-- Thêm nhà cung cấp -->
            <form action="?act=tours-supplier-store" method="POST" class="space-y-2">
                <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                <input type="text" name="supplier_name" placeholder="Tên nhà cung cấp" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                <input type="text" name="service_type" placeholder="Loại dịch vụ (ví dụ: Khách sạn)" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                <input type="text" name="phone" placeholder="SĐT" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                <textarea name="address" placeholder="Địa chỉ" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">Thêm nhà cung cấp</button>
                </div>
            </form>
        </div>

    </div>
</div>
