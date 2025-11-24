<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md border-t-4 border-yellow-500">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Cập Nhật Hoạt Động</h2>
        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded text-sm">Ngày <?= $detail['ngay_thu'] ?></span>
    </div>

    <form action="?act=tours-detail-update" method="POST" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="id" value="<?= $detail['id'] ?>">
        <input type="hidden" name="tour_id" value="<?= $detail['tour_id'] ?>">
        <input type="hidden" name="hinh_anh_cu" value="<?= $detail['hinh_anh'] ?>">

        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thứ</label>
                <input type="number" name="ngay_thu" value="<?= $detail['ngay_thu'] ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-yellow-500 outline-none">
            </div>
            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề</label>
                <input type="text" name="tieu_de" value="<?= htmlspecialchars($detail['tieu_de']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-yellow-500 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh</label>
            <div class="flex gap-4 items-center p-4 border border-gray-200 rounded bg-gray-50">
                <?php 
                    $imgUrl = asset('uploads/tour_details/' . ($detail['hinh_anh'] ?? ''));
                    $filePath = upload_path('tour_details/' . ($detail['hinh_anh'] ?? ''));
                ?>
                <?php if($detail['hinh_anh'] && file_exists($filePath)): ?>
                    <div class="w-24 h-24 rounded overflow-hidden border bg-white flex-shrink-0">
                        <img src="<?= htmlspecialchars($imgUrl) ?>" class="w-full h-full object-cover">
                    </div>
                <?php else: ?>
                    <div class="w-24 h-24 rounded border bg-white flex items-center justify-center text-xs text-gray-400">Trống</div>
                <?php endif; ?>
                
                <div class="flex-1">
                    <input type="file" name="hinh_anh_moi" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-100 file:text-yellow-700 hover:file:bg-yellow-200 transition">
                    <p class="text-xs text-gray-500 mt-2">Upload ảnh mới sẽ thay thế ảnh cũ.</p>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
            <textarea name="mo_ta" rows="5" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-yellow-500 outline-none"><?= htmlspecialchars($detail['mo_ta']) ?></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="?act=tours-detail&id=<?= $detail['tour_id'] ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded shadow hover:bg-yellow-600 transition">Lưu Thay Đổi</button>
        </div>
    </form>
</div>