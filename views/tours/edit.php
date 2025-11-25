<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded shadow overflow-hidden border-t-4 border-blue-500">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Cập nhật Tour: <?= htmlspecialchars($tour['name']) ?></h2>
        </div>
        
        <form action="?act=tours-update" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            
            <input type="hidden" name="id" value="<?= htmlspecialchars($tour['id']) ?>">
            <input type="hidden" name="old_image" value="<?= htmlspecialchars($tour['image']) ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên Tour <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required 
                            value="<?= htmlspecialchars($tour['name']) ?>" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giá (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required 
                            value="<?= htmlspecialchars($tour['price']) ?>" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày khởi hành <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required 
                            value="<?= htmlspecialchars($tour['start_date']) ?>" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục Tour</label>
                    <div class="relative">
                        <select name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Chọn danh mục --</option>
                            <?php 
                            $current_category_id = $tour['category_id'] ?? null;
                            // Giả định biến $categories được truyền từ Controller
                            foreach ($categories as $category): 
                            ?>
                                <option value="<?= htmlspecialchars($category['id']) ?>" 
                                        <?= ($current_category_id == $category['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn viên</label>
                    <div class="relative">
                        <select name="guide_id" class="w-full border border-gray-300 rounded-md px-3 py-2 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Chưa phân công --</option>
                            <?php 
                            $current_guide_id = $tour['guide_id'] ?? null;
                            foreach ($guides as $guide): 
                            ?>
                                <option value="<?= htmlspecialchars($guide['id']) ?>" 
                                        <?= ($current_guide_id == $guide['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($guide['name']) ?> (<?= htmlspecialchars($guide['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh cover</label>
                    
                    <?php if (!empty($tour['image'])): ?>
                        <?php 
                            // Use consistent uploads/tours/ path
                            $imgUrl = asset('uploads/tours/' . ($tour['image'] ?? ''));
                            $filePath = upload_path('tours/' . ($tour['image'] ?? ''));
                        ?>
                        <?php if (file_exists($filePath)): ?>
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-1">Ảnh hiện tại:</p>
                            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Ảnh cover hiện tại" class="w-24 h-20 object-cover rounded shadow border">
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                    <p class="mt-1 text-xs text-gray-500">
                        * Chỉ chọn nếu muốn thay đổi ảnh.
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"><?= htmlspecialchars($tour['description']) ?></textarea>
            </div>

            <!-- Lịch trình (Itinerary) -->
            <div class="bg-gray-50 p-4 rounded border">
                <h3 class="font-bold mb-3">Lịch trình</h3>
                <?php if (!empty($itinerary)): ?>
                    <?php foreach ($itinerary as $it): ?>
                        <div class="mb-2 flex justify-between items-start bg-white p-3 rounded border">
                            <div>
                                <div class="font-semibold">Ngày <?= htmlspecialchars($it['day_number'] ?? ($it['ngay_thu'] ?? '')) ?> - <?= htmlspecialchars($it['location_name'] ?? ($it['tieu_de'] ?? '')) ?></div>
                                <div class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($it['activity_details'] ?? ($it['mo_ta'] ?? ''))) ?></div>
                            </div>
                            <div class="text-right">
                                <a href="?act=tours-edit-detail&detail_id=<?= $it['id'] ?? $it['detail_id'] ?>&tour_id=<?= $tour['id'] ?>" class="text-indigo-600 text-sm mr-2">Sửa</a>
                                <a href="?act=tours-delete-detail&detail_id=<?= $it['id'] ?? $it['detail_id'] ?>&tour_id=<?= $tour['id'] ?>" onclick="return confirm('Xóa mục lịch trình này?')" class="text-red-600 text-sm">Xóa</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-sm text-gray-500">Chưa có lịch trình chi tiết.</div>
                <?php endif; ?>

                <form action="?act=tours-store-detail" method="POST" enctype="multipart/form-data" class="mt-3 space-y-2">
                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                    <div class="grid grid-cols-4 gap-2">
                        <input type="number" name="it_ngay_thu" placeholder="Ngày thứ" class="border p-2 rounded" required>
                        <input type="text" name="it_tieu_de" placeholder="Tiêu đề" class="border p-2 rounded" required>
                        <input type="text" name="it_mo_ta" placeholder="Mô tả ngắn" class="border p-2 rounded">
                        <input type="file" name="it_hinh_anh" class="border p-2 rounded">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">Thêm lịch trình</button>
                    </div>
                </form>
            </div>

            <!-- Chính sách (Policies) -->
            <div class="bg-gray-50 p-4 rounded border">
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

                <form action="?act=tours-policy-store" method="POST" class="mt-3 space-y-2">
                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                    <input type="text" name="policy_type" placeholder="Loại chính sách (Ví dụ: Hủy/Hoàn)" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                    <textarea name="content" placeholder="Nội dung chính sách" required class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Thêm chính sách</button>
                    </div>
                </form>
            </div>

            <!-- Đối tác / Nhà cung cấp (Suppliers) -->
            <div class="bg-gray-50 p-4 rounded border">
                <h3 class="font-bold mb-3">Đối tác / Nhà cung cấp</h3>
                <?php if (!empty($suppliers)): ?>
                    <ul class="text-sm space-y-2 mb-3">
                        <?php foreach ($suppliers as $s): ?>
                            <li class="flex justify-between items-start bg-white p-3 rounded border">
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
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">Thêm nhà cung cấp</button>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="?act=tours" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Hủy bỏ</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">Cập nhật Tour</button>
            </div>
        </form>
    </div>
</div>