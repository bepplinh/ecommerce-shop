@extends('layout.adminDashboard')
@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center text-center">
            <h5 class="mb-0">Danh sách sản phẩm</h5>
            <a href="{{ route('products.create') }}" class="btn btn-primary ms-auto">
                <i class="fas fa-plus me-1"></i> Thêm sản phẩm
            </a>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="w-50">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control text-center" name="search"
                                placeholder="Tìm kiếm theo tên hoặc mã sản phẩm..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request('search'))
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div>
                    <form action="{{ route('products.index') }}" method="GET">
                        <select name="sort" class="form-select" onchange="this.form.submit()" style="min-width: 200px;">
                            <option value="">Sắp xếp theo</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá (Thấp-Cao)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá (Cao-Thấp)</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr class="align-middle">
                            <th class="text-center align-middle">Mã SP</th>
                            <th class="text-center align-middle">Hình ảnh</th>
                            <th class="text-center align-middle">Tên sản phẩm</th>
                            <th class="text-center align-middle">Giá (VNĐ)</th>
                            <th class="text-center align-middle">Size</th>
                            <th class="text-center align-middle">Số lượng</th>
                            <th class="text-center align-middle">Thương hiệu</th>
                            <th class="text-center align-middle">Danh mục</th>
                            <th class="text-center align-middle">Trạng thái</th>
                            <th class="text-center align-middle">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr class="align-middle">
                            <td class="align-middle">{{ $product->code }}</td>
                            <td class="align-middle">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail mx-auto d-block"
                                    style="max-width: 100px;">
                            </td>
                            <td class="align-middle">{{ $product->name }}</td>
                            <td class="align-middle">{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="align-middle">
                                @forelse ($product->sizes as $size)
                                <div class="text-center">{{ $size->name }}</div>
                                @empty
                                <div class="text-center">Không có kích thước</div>
                                @endforelse
                            </td>
                            <td class="align-middle">
                                @forelse ($product->sizes as $size)
                                <div class="text-center">{{ $size->pivot->stock }}</div>
                                @empty
                                <div class="text-center">-</div>
                                @endforelse
                            </td>
                            <td class="align-middle">
                                {{ $product->brand ? $product->brand->name : 'Chưa có thương hiệu' }}
                            </td>
                            <td class="align-middle">
                                {{ $product->category ? $product->category->name : 'Chưa có thương hiệu' }}
                            </td>
                            <td class="align-middle">
                                <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="btn-group d-flex justify-content-center" role="group">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-sm btn-warning w-50">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        class="d-inline w-50">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có sản phẩm nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <li class="page-item {{ $products->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection