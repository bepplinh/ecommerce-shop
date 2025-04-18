@extends('layout.adminDashboard')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="code" class="form-label">Mã sản phẩm</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                            id="code" value="{{ old('code') }}" required>
                        @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Size và số lượng --}}
                <div class="mb-3">
                    <label class="form-label">Size và số lượng</label>
                    <div id="size-stock-list">
                        @if(old('sizes'))
                            @foreach(old('sizes') as $i => $size)
                                <div class="row mb-2 align-items-end size-stock-item">
                                    <div class="col-md-5">
                                        <select name="sizes[{{ $i }}][size_id]" class="form-select" required>
                                            <option value="">-- Chọn size --</option>
                                            @foreach($sizes as $s)
                                                <option value="{{ $s->id }}" {{ $size['size_id'] == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" name="sizes[{{ $i }}][stock]" class="form-control" placeholder="Số lượng" value="{{ $size['stock'] }}" required>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="button" class="btn btn-danger btn-remove-size"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-2 align-items-end size-stock-item">
                                <div class="col-md-5">
                                    <select name="sizes[0][size_id]" class="form-select" required>
                                        <option value="">-- Chọn size --</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" name="sizes[0][stock]" class="form-control" placeholder="Số lượng" required>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-danger btn-remove-size"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-outline-primary" id="btn-add-size">
                        <i class="fas fa-plus-circle me-1"></i> Thêm size
                    </button>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Giá</label>
                        <div class="input-group">
                            <span class="input-group-text">VNĐ</span>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                                id="price" value="{{ old('price') }}" required>
                        </div>
                        @error('price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                        id="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select select2-custom @error('category_id') is-invalid @enderror"
                            name="category_id" id="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
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
                        <select class="form-select select2-custom @error('brand_id') is-invalid @enderror"
                            name="brand_id" id="brand_id" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh sản phẩm</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image"
                        accept="image/*" onchange="previewImage(this)">
                    @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="mt-2 position-relative" id="imagePreviewContainer" style="display: none;">
                        <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        <button type="button" class="btn-close position-absolute top-0 end-0 bg-light rounded-circle"
                            onclick="clearImage()" style="margin: 5px;"></button>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('products.create') }}" class="btn btn-secondary me-2"
                        onclick="return confirmCancel(event)">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Tạo sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmCancel(event) {
        event.preventDefault();
        if (confirm('Bạn có chắc chắn muốn hủy?')) {
            window.location.href = event.currentTarget.href;
        }
    }

    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', function (e) {
        const rawValue = this.value.replace(/\D/g, '');
        this.value = Number(rawValue).toLocaleString('vi-VN');
        this.setSelectionRange(this.value.length, this.value.length);
    });

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                container.style.display = 'inline-block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            clearImage();
        }
    }

    function clearImage() {
        const input = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        input.value = '';
        preview.src = '#';
        container.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        let index = {{ old('sizes') ? count(old('sizes')) : 1 }};

        document.getElementById('btn-add-size').addEventListener('click', function () {
            const container = document.getElementById('size-stock-list');

            const newItem = document.createElement('div');
            newItem.classList.add('row', 'mb-2', 'align-items-end', 'size-stock-item');
            newItem.innerHTML = `
                <div class="col-md-5">
                    <select name="sizes[${index}][size_id]" class="form-select" required>
                        <option value="">-- Chọn size --</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="number" name="sizes[${index}][stock]" class="form-control" placeholder="Số lượng" required>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-remove-size"><i class="fas fa-trash-alt"></i></button>
                </div>
            `;
            container.appendChild(newItem);
            index++;
        });

        document.getElementById('size-stock-list').addEventListener('click', function (e) {
            if (e.target.closest('.btn-remove-size')) {
                e.target.closest('.size-stock-item').remove();
            }
        });
    });
</script>
@endsection
