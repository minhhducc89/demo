<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded shadow overflow-hidden border-t-4 border-blue-500">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Thêm Tour Mới</h2>
        </div>
        
        <form action="?act=tours-store" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên Tour <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giá (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày khởi hành <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục Tour</label>
                    <div class="relative">
                        <select name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Chọn danh mục --</option>
                            <?php 
                            // Giả định biến $categories được truyền từ Controller
                            foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
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
                            <?php foreach ($guides as $guide): ?>
                                <option value="<?= $guide['id'] ?>"><?= $guide['name'] ?> (<?= $guide['email'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh cover</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                </div>
                    </div>
                </div>

                <aside class="bg-white p-4 rounded border">
                    <h3 class="font-bold mb-2">Xem trước</h3>
                    <div class="h-48 w-full rounded overflow-hidden border mb-3 bg-gray-100 flex items-center justify-center text-gray-400">
                        <span class="text-sm">Chưa có ảnh</span>
                    </div>

                    <h3 class="font-bold mb-2">Thông tin nhanh</h3>
                    <div class="text-sm text-gray-700">
                        <div><strong>Giá:</strong> -</div>
                        <div><strong>Ngày khởi hành:</strong> -</div>
                        <div><strong>HDV:</strong> -</div>
                    </div>
                </aside>

            </div>

            <!-- --- Gallery: multiple images --- -->
            <div class="bg-white p-4 rounded border">
                <h4 class="font-bold mb-2">Ảnh Gallery</h4>
                <input type="file" name="images[]" multiple class="w-full text-sm text-gray-500">
                <p class="text-xs text-gray-500 mt-1">Bạn có thể chọn nhiều ảnh (Gallery)</p>
            </div>

            <!-- --- Prices dynamic --- -->
            <div class="bg-white p-4 rounded border">
                <h4 class="font-bold mb-2">Gói giá</h4>
                <div id="prices-wrapper">
                    <div class="price-row grid grid-cols-3 gap-2 mb-2">
                        <input type="text" name="price_package_name[]" placeholder="Tên gói" class="border p-2 rounded" />
                        <input type="number" name="price_adult[]" placeholder="Giá người lớn" class="border p-2 rounded" />
                        <input type="number" name="price_child[]" placeholder="Giá trẻ em (tuỳ chọn)" class="border p-2 rounded" />
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="add-price" class="px-3 py-1 bg-gray-200 rounded">Thêm gói</button>
                </div>
            </div>

            <!-- --- Policies dynamic --- -->
            <div class="bg-white p-4 rounded border">
                <h4 class="font-bold mb-2">Chính sách</h4>
                <div id="policies-wrapper">
                    <div class="policy-row mb-2">
                        <input type="text" name="policy_type[]" placeholder="Loại chính sách" class="w-full border p-2 rounded mb-1" />
                        <textarea name="policy_content[]" placeholder="Nội dung" class="w-full border p-2 rounded"></textarea>
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="add-policy" class="px-3 py-1 bg-gray-200 rounded">Thêm chính sách</button>
                </div>
            </div>

            <!-- --- Suppliers dynamic --- -->
            <div class="bg-white p-4 rounded border">
                <h4 class="font-bold mb-2">Đối tác / Nhà cung cấp</h4>
                <div id="suppliers-wrapper">
                    <div class="supplier-row grid grid-cols-2 gap-2 mb-2">
                        <input type="text" name="supplier_name[]" placeholder="Tên nhà cung cấp" class="border p-2 rounded" />
                        <input type="text" name="supplier_service_type[]" placeholder="Loại dịch vụ" class="border p-2 rounded" />
                        <input type="text" name="supplier_phone[]" placeholder="SĐT" class="border p-2 rounded" />
                        <input type="text" name="supplier_address[]" placeholder="Địa chỉ" class="border p-2 rounded" />
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="add-supplier" class="px-3 py-1 bg-gray-200 rounded">Thêm đối tác</button>
                </div>
            </div>

            <!-- --- Itinerary dynamic (legacy tour_details) --- -->
            <div class="bg-white p-4 rounded border">
                <h4 class="font-bold mb-2">Lịch trình (Ngày / Tiêu đề / Mô tả / Ảnh)</h4>
                <div id="itinerary-wrapper">
                    <div class="it-row grid grid-cols-4 gap-2 mb-2">
                        <input type="number" name="it_ngay_thu[]" placeholder="Ngày thứ" class="border p-2 rounded" />
                        <input type="text" name="it_tieu_de[]" placeholder="Tiêu đề" class="border p-2 rounded" />
                        <input type="text" name="it_mo_ta[]" placeholder="Mô tả ngắn" class="border p-2 rounded" />
                        <input type="file" name="it_hinh_anh[]" class="border p-2 rounded" />
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="add-it" class="px-3 py-1 bg-gray-200 rounded">Thêm mục lịch trình</button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="?act=tours" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Hủy bỏ</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">Lưu Tour</button>
            </div>
        </form>
    </div>
</div>

<script>
// Simple dynamic rows for create form
document.getElementById('add-price')?.addEventListener('click', function(){
    const wrapper = document.getElementById('prices-wrapper');
    const div = document.createElement('div');
    div.className = 'price-row grid grid-cols-3 gap-2 mb-2';
    div.innerHTML = '<input type="text" name="price_package_name[]" placeholder="Tên gói" class="border p-2 rounded" />\n        <input type="number" name="price_adult[]" placeholder="Giá người lớn" class="border p-2 rounded" />\n        <input type="number" name="price_child[]" placeholder="Giá trẻ em (tuỳ chọn)" class="border p-2 rounded" />';
    wrapper.appendChild(div);
});

document.getElementById('add-policy')?.addEventListener('click', function(){
    const wrapper = document.getElementById('policies-wrapper');
    const div = document.createElement('div');
    div.className = 'policy-row mb-2';
    div.innerHTML = '<input type="text" name="policy_type[]" placeholder="Loại chính sách" class="w-full border p-2 rounded mb-1" />\n        <textarea name="policy_content[]" placeholder="Nội dung" class="w-full border p-2 rounded"></textarea>';
    wrapper.appendChild(div);
});

document.getElementById('add-supplier')?.addEventListener('click', function(){
    const wrapper = document.getElementById('suppliers-wrapper');
    const div = document.createElement('div');
    div.className = 'supplier-row grid grid-cols-2 gap-2 mb-2';
    div.innerHTML = '<input type="text" name="supplier_name[]" placeholder="Tên nhà cung cấp" class="border p-2 rounded" />\n        <input type="text" name="supplier_service_type[]" placeholder="Loại dịch vụ" class="border p-2 rounded" />\n        <input type="text" name="supplier_phone[]" placeholder="SĐT" class="border p-2 rounded" />\n        <input type="text" name="supplier_address[]" placeholder="Địa chỉ" class="border p-2 rounded" />';
    wrapper.appendChild(div);
});

document.getElementById('add-it')?.addEventListener('click', function(){
    const wrapper = document.getElementById('itinerary-wrapper');
    const div = document.createElement('div');
    div.className = 'it-row grid grid-cols-4 gap-2 mb-2';
    div.innerHTML = '<input type="number" name="it_ngay_thu[]" placeholder="Ngày thứ" class="border p-2 rounded" />\n        <input type="text" name="it_tieu_de[]" placeholder="Tiêu đề" class="border p-2 rounded" />\n        <input type="text" name="it_mo_ta[]" placeholder="Mô tả ngắn" class="border p-2 rounded" />\n        <input type="file" name="it_hinh_anh[]" class="border p-2 rounded" />';
    wrapper.appendChild(div);
});
</script>