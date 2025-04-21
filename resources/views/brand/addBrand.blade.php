@extends('layout.adminDashboard')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="mb-0">Add New Brand</h5>
                        <a href="{{ route('brands.index') }}" class="btn btn-primary ms-auto">
                            <i class="fas fa-list me-1"></i> List Brand
                        </a>

                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <!-- Brand Name -->
                            <div class="form-group row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Brand Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group row mb-3">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                                <div class="col-md-6">
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description"
                                        rows="4">{{ old('description') }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Brand Image Upload + Preview -->
                            <div class="form-group row mb-4">
                                <label for="image" class="col-md-4 col-form-label text-md-right">Brand Image</label>
                                <div class="col-md-6">
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror" accept="image/*"
                                        onchange="previewImage(event)">

                                    @error('image')
                                        <span class="invalid-feedback d-block mt-1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <!-- Preview Area: now below the input -->
                                    <div class="mt-3">
                                        <img id="imagePreview" src="#" alt="Logo Brand"
                                            style="max-width: 100%; height: auto; display: none; border-radius: 8px; border: 1px solid #ddd;">
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Buttons -->
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Add Brand
                                    </button>
                                    <a href="#" class="btn btn-secondary" onclick="resetForm()">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetForm() {
            if (confirm('Bạn có muốn hủy không ?')) {
                window.location.reload();
            }
        }
    </script>
@endsection
