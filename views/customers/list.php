<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 border-l-4 border-blue-500 pl-3">Quản Lý Khách Hàng</h1>
    <a href="?act=customers-create" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
        + Thêm Khách
    </a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Họ tên</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Liên hệ</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Địa chỉ</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($customers as $c): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-900"><?= $c['ho_ten'] ?></td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-900"><?= $c['email'] ?></p>
                    <p class="text-xs text-gray-500"><?= $c['sdt'] ?></p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500"><?= $c['dia_chi'] ?></td>
                <td class="px-6 py-4 text-center whitespace-nowrap">
                    <a href="?act=customers-edit&id=<?= $c['id'] ?>" 
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 bg-blue-50 hover:bg-blue-100 transition duration-150 shadow-md tooltip"
                    title="Sửa Khách hàng">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    
                    <a href="?act=customers-delete&id=<?= $c['id'] ?>" 
                    onclick="return confirm('Xóa khách này?')" 
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 bg-red-50 hover:bg-red-100 transition duration-150 shadow-md ml-2 tooltip"
                    title="Xóa Khách hàng">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>