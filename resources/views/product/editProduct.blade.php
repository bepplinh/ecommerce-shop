@extends('layout.adminDashboard')
@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Các trường tên, mã --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="code" class="form-label">Mã sản phẩm</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                                value="{{ old('code', $product->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Giá và mô tả --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Giá</label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                                id="price" value="{{ old('price', number_format($product->price, 0, ',', '.')) }}"
                                required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                    Hoạt động</option>
                                <option value="inactive"
                                    {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Danh mục & thương hiệu --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" name="category_id"
                                required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="brand_id" class="form-label">Thương hiệu</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id" required>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Size và stock của từng size --}}
                    <div class="mb-3">
                        <label class="form-label">Kích thước & số lượng</label>
                        <div id="size-stock-container">
                            @foreach ($sizes as $size)
                                @php
                                    $existing = $product->sizes->firstWhere('id', $size->id);
                                @endphp

                                @if ($existing)
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-4">
                                            <select name="sizes[{{ $loop->index }}][id]" class="form-select">
                                                @foreach ($sizes as $option)
                                                    <option value="{{ $option->id }}"
                                                        {{ $option->id == $size->id ? 'selected' : '' }}>
                                                        {{ $option->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="sizes[{{ $loop->index }}][stock]"
                                                class="form-control" min="0"
                                                value="{{ old('sizes.' . $loop->index . '.stock', $existing ? $existing->pivot->stock : 0) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeSizeRow(this)">Xoá</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" onclick="addSizeRow()">+ Thêm size</button>
                    </div>

                    <h5>Ảnh sản phẩm</h5>
                    <table class="table table-bordered" id="image-table">
                        <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Ảnh thay thế</th>
                                <th>Ảnh chính</th>
                                <th>Xoá</th>
                            </tr>
                        </thead>
                        <tbody id="existing-images">
                            @foreach ($product->images as $image)
                                <tr>
                                    <td><img src="{{ asset($image->image_path) }}" width="80"></td>
                                    <td>
                                        <input type="file" name="replace_images[{{ $image->id }}]"
                                            class="form-control-file">
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="main_image_id" value="{{ $image->id }}"
                                            {{ $image->is_main ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="delete-image" value="{{ $image->id }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tbody id="new-images">
                            <!-- Dòng ảnh mới chọn sẽ được JS thêm vào đây -->
                        </tbody>
                    </table>

                    <!-- Input thêm ảnh mới -->
                    <div class="form-group">
                        <label>Thêm ảnh mới</label>
                        <input type="file" name="images[]" id="image-input" multiple class="form-control-file mb-3">
                    </div>

                    <!-- Hidden input để lưu ảnh bị xoá -->
                    <input type="hidden" name="deleted_images" id="deleted_images">

                    {{-- Nút submit --}}
                    <div class="text-end">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>

                <!-- Modal sửa ảnh -->
                @foreach ($product->images as $image)
                    <div class="modal fade" id="editImageModal{{ $image->id }}" tabindex="-1"
                        aria-labelledby="editImageModalLabel{{ $image->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editImageModalLabel{{ $image->id }}">Chỉnh sửa ảnh
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- <form action="{{ route('products.updateImage', $image->id) }}" method="POST" enctype="multipart/form-data"> --}}
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Chọn ảnh mới</label>
                                        <input type="file" class="form-control" name="image" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="is_main" class="form-label">Ảnh chính</label>
                                        <input type="checkbox" name="is_main" {{ $image->is_main ? 'checked' : '' }}>
                                        Chọn làm ảnh chính
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                    {{-- </form> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatPriceInput(selector) {
            const input = document.querySelector(selector);
            input.addEventListener('input', function() {
                let rawValue = input.value.replace(/\D/g, '');
                if (rawValue === '') return input.value = '';
                input.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        }
        formatPriceInput('#price');


        function removeSizeRow(button) {
            button.closest('.row').remove();
        }

        function addSizeRow() {
            const index = document.querySelectorAll('#size-stock-container .row').length;
            const container = document.getElementById('size-stock-container');

            const row = document.createElement('div');
            row.classList.add('row', 'mb-2', 'align-items-center');
            row.innerHTML = `
            <div class="col-md-4">
                <select name="sizes[${index}][id]" class="form-select">
                    @foreach ($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="sizes[${index}][stock]" class="form-control" min="0" value="0">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeSizeRow(this)">Xoá</button>
            </div>
        `;
            container.appendChild(row);
        }

        document.addEventListener('DOMContentLoaded', function () {
    // Lấy tất cả các checkbox xóa ảnh
    const deleteCheckboxes = document.querySelectorAll('.delete-image');
    // Lấy trường input để lưu danh sách các ảnh cần xóa
    const deletedImagesInput = document.getElementById('deleted_images');
    
    // Dùng mảng để chứa các imageId cần xoá
    let deletedImages = [];

    deleteCheckboxes.forEach(checkbox => {
        // Lắng nghe sự kiện thay đổi của checkbox
        checkbox.addEventListener('change', function () {
            const imageId = this.value;

            if (this.checked) {
                // Nếu checkbox được chọn, thêm imageId vào mảng deletedImages
                if (!deletedImages.includes(imageId)) {
                    deletedImages.push(imageId);
                }
            } else {
                // Nếu checkbox không được chọn, xoá imageId khỏi mảng deletedImages
                deletedImages = deletedImages.filter(id => id !== imageId);
            }

            // Cập nhật giá trị của trường deleted_images với danh sách imageId cần xóa
            deletedImagesInput.value = deletedImages.join(',');
        });
    });

    // Xử lý thêm ảnh mới vào bảng
    const imageInput = document.getElementById('image-input');
    const newImagesTable = document.getElementById('new-images');
    let newImageCounter = 0;  // Đếm ảnh mới để tạo ID riêng

    imageInput.addEventListener('change', function () {
        // Duyệt qua các file ảnh đã chọn
        Array.from(this.files).forEach((file) => {
            const reader = new FileReader();
            const imageId = `new_${newImageCounter++}`;

            reader.onload = function (e) {
                // Tạo bảng mới hiển thị ảnh đã chọn
                const html = `
                    <tr class="new-image" data-image-id="${imageId}">
                        <td><img src="${e.target.result}" width="80"></td>
                        <td class="text-muted text-center">--</td>
                        <td class="text-center">
                            <input type="radio" name="main_image_id" value="${imageId}">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-new-image">Xoá</button>
                        </td>
                    </tr>
                `;
                newImagesTable.insertAdjacentHTML('beforeend', html);
            };
            reader.readAsDataURL(file);
        });
    });

    // Xử lý xoá ảnh mới trong bảng
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-new-image')) {
            const row = e.target.closest('tr');
            if (row) row.remove();
        }
    });
});

    </script>
@endsection
