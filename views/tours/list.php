<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-blue-600 pl-4">Danh Sách Tour</h2>
        
        <a href="?act=tours-create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            Thêm Tour Mới
        </a>
    </div>

    <div class="bg-gray-50 p-4 rounded-md mb-6 border border-gray-200">
        <form action="" method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="act" value="tours">
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Từ khóa</label>
                <input type="text" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Nhập tên tour cần tìm..." class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Hướng dẫn viên</label>
                <select name="guide_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Tất cả --</option>
                    <?php foreach ($guides as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= (isset($guide_id) && $guide_id == $g['id']) ? 'selected' : '' ?>>
                            <?= $g['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục Tour</label>
                <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Tất cả --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($category_id) && $category_id == $cat['id']) ? 'selected' : '' ?>>
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2 rounded transition">Lọc</button>
                <a href="?act=tours" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded transition">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tên Tour</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Giá / Ngày đi</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">HDV</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($tours as $tour): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                                $imgName = $tour['image'] ?? null;
                                $folder = 'tours/';
                                
                                $pathCheck = upload_path($folder . $imgName);
                                // Do file đã tồn tại, lỗi nằm ở pathCheck. Thử dùng hàm realpath
                                // $hasImage = !empty($imgName) && file_exists(realpath($pathCheck)); 
                                // Hoặc đơn giản hơn: Bỏ qua file_exists nếu bạn chắc chắn file đã có
                                
                                $hasImage = !empty($imgName) && file_exists($pathCheck); // Giữ nguyên check cũ
                                $imgUrl = asset('uploads/' . $folder . $imgName);
                            ?>

                            <?php if ($hasImage): ?>
                                <div class="h-16 w-24 rounded overflow-hidden shadow-sm border border-gray-200">
                                    <img src="<?= htmlspecialchars($imgUrl) ?>" 
                                        alt="Tour Image" 
                                        class="h-full w-full object-cover">
                                </div>
                            <?php else: ?>
                                <div class="h-16 w-24 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs border border-gray-200">
                                    No Image
                                </div>
                            <?php endif; ?>
                        </td>

                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= $tour['name'] ?></div>
                        <div class="text-sm text-gray-500 line-clamp-1" title="<?= $tour['description'] ?>"><?= $tour['description'] ?></div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-red-600"><?= number_format($tour['price']) ?> đ</div>
                        <div class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($tour['start_date'])) ?></div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?= $tour['category_name'] ?? 'Không rõ' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if(isset($tour['guide_name']) && $tour['guide_name']): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <?= $tour['guide_name'] ?>
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Chưa gán</span>
                        <?php endif; ?>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            <a href="?act=tours-detail-full&id=<?= $tour['id'] ?>" 
                                class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg inline-block transition duration-150" 
                                title="Xem Chi tiết Tour Tổng thể">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                            </a>
                            <a href="?act=tours-detail&id=<?= $tour['id'] ?>" class="text-purple-600 hover:text-purple-900 bg-purple-50 p-2 rounded-lg inline-block transition duration-150" title="Quản lý lịch trình">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            </a>
                            
                            <a href="?act=tours-edit&id=<?= $tour['id'] ?>" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg inline-block transition duration-150" title="Sửa thông tin">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            
                            <a href="?act=tours-delete&id=<?= $tour['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa tour này?')" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg inline-block transition duration-150" title="Xóa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>