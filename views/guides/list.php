<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Quản lý Hướng Dẫn Viên</h2>
        <p class="text-gray-500 text-sm mt-1">Tổng cộng <?= count($guides) ?> Hướng dẫn viên</p>
    </div>
    <a href="?act=guides-create" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded shadow-lg transition flex items-center font-medium text-sm">
        <span class="text-xl mr-2">+</span> Thêm HDV
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-5 py-3">ID</th>
                    <th class="px-5 py-3">Tên HDV</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Điện thoại</th>
                    <th class="px-5 py-3">Giới tính</th>
                    <th class="px-5 py-3 text-right">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($guides)): ?>
                    <?php foreach($guides as $guide): ?>
                    <tr class="hover:bg-gray-50 border-b border-gray-100">
                        <td class="px-5 py-4 text-sm text-gray-600"><?= $guide['id'] ?></td>
                        <td class="px-5 py-4 text-sm text-gray-900 font-medium"><?= $guide['name'] ?></td>
                        <td class="px-5 py-4 text-sm text-gray-600"><?= $guide['email'] ?></td>
                        <td class="px-5 py-4 text-sm text-gray-600"><?= $guide['phone'] ?? 'N/A' ?></td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            <?= $guide['gender'] == 1 ? 'Nam' : ($guide['gender'] == 0 ? 'Nữ' : 'N/A') ?>
                        </td>
                        <td class="px-5 py-4 text-sm text-right whitespace-nowrap">
                            <a href="?act=guides-edit&id=<?= $guide['id'] ?>" 
                            class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 bg-blue-50 hover:bg-blue-100 transition duration-150 shadow-md tooltip"
                            title="Sửa HDV">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] != $guide['id']): ?>
                            <a href="?act=guides-delete&id=<?= $guide['id'] ?>" 
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 bg-red-50 hover:bg-red-100 transition duration-150 shadow-md ml-2 tooltip"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa HDV này không?');"
                                title="Xóa HDV">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-5 py-4 text-sm text-center text-gray-500">Chưa có dữ liệu Hướng dẫn viên.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>