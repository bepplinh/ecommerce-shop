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
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="code" class="form-label">Mã sản phẩm</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $product->code) }}"
                            required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Giá và mô tả --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Giá</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                            value="{{ old('price', number_format($product->price, 0, ',', '.')) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : ''
                                }}>Hoạt động</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : ''
                                }}>Không hoạt động</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                        name="description">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Danh mục & thương hiệu --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) ==
                                $category->id ? 'selected' : '' }}>
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
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ?
                                'selected' : '' }}>
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
                        @foreach($sizes as $size)
                        @php
                        $existing = $product->sizes->firstWhere('id', $size->id);
                        @endphp

                        @if($existing)
                        <div class="row mb-2 align-items-center">
                            <div class="col-md-4">
                                <select name="sizes[{{ $loop->index }}][id]" class="form-select">
                                    @foreach($sizes as $option)
                                    <option value="{{ $option->id }}" {{ $option->id == $size->id ? 'selected' : '' }}>
                                        {{ $option->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="sizes[{{ $loop->index }}][stock]" class="form-control"
                                    min="0"
                                    value="{{ old('sizes.'.$loop->index.'.stock', $existing ? $existing->pivot->stock : 0) }}">
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

                {{-- Ảnh sản phẩm --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh sản phẩm</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*"
                        onchange="previewImage(this)">
                    <div class="mt-2">
                        <label class="form-label">Ảnh hiện tại:</label><br>
                        @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="Current Image" class="img-thumbnail"
                            style="max-width: 200px;">
                        @else
                        <p>Không có ảnh</p>
                        @endif
                    </div>

                    <div class="mt-2 position-relative" id="imagePreviewContainer" style="display: none;">
                        <label class="form-label">Ảnh mới:</label>
                        <div class="position-relative d-inline-block">
                            <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail"
                                style="max-width: 200px;">
                            <button type="button"
                                class="btn-close position-absolute top-0 end-0 bg-light rounded-circle"
                                onclick="clearImage()" style="margin: 5px;"></button>
                        </div>
                    </div>
                </div>

                {{-- Nút submit --}}
                <div class="text-end">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function formatPriceInput(selector) {
        const input = document.querySelector(selector);
        input.addEventListener('input', function () {
            let rawValue = input.value.replace(/\D/g, ''); 
            if (rawValue === '') return input.value = '';
            input.value = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
        });
    }
    formatPriceInput('#price');

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            clearImage();
        }
    }

    function clearImage() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').src = '#';
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }

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
                    @foreach($sizes as $size)
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
</script>
@endsection