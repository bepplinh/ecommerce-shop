@extends('layout.adminDashboard')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="code" class="form-label">Mã sản phẩm</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                            id="code" value="{{ old('code', $product->code) }}" required>
                        @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="size" class="form-label">Size</label>
                        <select class="form-select @error('size_id') is-invalid @enderror" name="size_id" id="size_id" required>
                            <option value="">-- Chọn size --</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}" {{ old('size_id', $product->size_id) == $size->id ? 'selected' : '' }}>
                                    {{ $size->name }}
                                </option>
                            @endforeach
                        </select>                        
                        
                        
                        @error('size')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label">Giá</label>
                        <div class="input-group">
                            <span class="input-group-text">VNĐ</span>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                                id="price" value="{{ old('price', $product->price) }}" required>
                        </div>
                        @error('price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                        id="description" rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Số lượng</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock"
                            id="stock" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                Hoạt động
                            </option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                Không hoạt động
                            </option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id"
                            id="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id"
                            id="brand_id" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                        id="image" accept="image/*" onchange="previewImage(this)">
                    @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="mt-2">
                        <label class="form-label">Ảnh hiện tại:</label>
                        <div class="position-relative d-inline-block">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image"
                                class="img-thumbnail" style="max-width: 200px;">
                            @else
                            <p>Không có ảnh</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-2 position-relative" id="imagePreviewContainer" style="display: none;">
                        <label class="form-label">Ảnh mới:</label>
                        <div class="position-relative d-inline-block">
                            <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            <button type="button" class="btn-close position-absolute top-0 end-0 bg-light rounded-circle"
                                onclick="clearImage()" style="margin: 5px;"></button>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
        const input = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        
        input.value = '';
        preview.src = '#';
        container.style.display = 'none';
    }
</script>
@endsection