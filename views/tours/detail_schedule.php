<div class="mb-6">
    <div class="bg-blue-600 text-white p-6 rounded-lg shadow-md mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Lịch Trình: <?= htmlspecialchars($tour['name']) ?></h1>
            <p class="opacity-90 mt-1">Quản lý các hoạt động chi tiết theo từng ngày</p>
        </div>
        <a href="?act=tours" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded transition">Quay lại</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500 sticky top-4">
                <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Thêm Hoạt Động Mới</h3>
                
                <form action="?act=tours-detail-store" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thứ</label>
                        <input type="number" name="ngay_thu" placeholder="VD: 1" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề hoạt động</label>
                        <input type="text" name="tieu_de" placeholder="VD: Tham quan Vịnh Hạ Long" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh minh họa</label>
                        <input type="file" name="hinh_anh" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition border border-gray-300 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả hoạt động</label>
                        <textarea name="mo_ta" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded shadow transition">Thêm Hoạt Động</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <?php if(empty($details)): ?>
                <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500 italic">
                    Chưa có lịch trình nào được tạo. Hãy thêm mới bên trái.
                </div>
            <?php else: ?>
                <?php foreach($details as $d): ?>
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex gap-5 group hover:shadow-md transition relative">
                    
                    <div class="w-32 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border">
                        <?php 
                            $imgUrl = asset('uploads/tour_details/' . ($d['hinh_anh'] ?? ''));
                            $filePath = upload_path('tour_details/' . ($d['hinh_anh'] ?? ''));
                        ?>
                        <?php if($d['hinh_anh'] && file_exists($filePath)): ?>
                            <img src="<?= htmlspecialchars($imgUrl) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Image</div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-200">
                                Ngày <?= $d['ngay_thu'] ?>
                            </span>
                            <h4 class="text-lg font-bold text-gray-800"><?= $d['tieu_de'] ?></h4>
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-2"><?= $d['mo_ta'] ?></p>
                    </div>

                    <div class="flex flex-col gap-2 justify-center border-l pl-4 ml-2">
                        <a href="?act=tours-detail-edit&id=<?= $d['id'] ?>" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-50 rounded hover:bg-blue-100" title="Sửa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <a href="?act=tours-detail-delete&id=<?= $d['id'] ?>&tour_id=<?= $tour['id'] ?>" onclick="return confirm('Xóa hoạt động này?')" class="text-red-500 hover:text-red-700 p-1 bg-red-50 rounded hover:bg-red-100" title="Xóa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>