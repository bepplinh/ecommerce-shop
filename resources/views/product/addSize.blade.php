@extends('layout.adminDashboard')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Size</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('product.addSize') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Size:</label>
                            <input type="text" class="form-control w-50" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Add Size</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Sizes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        Size
                                        <a href="{{ route('product.size', ['sort' => 'asc']) }}" class="text-dark">
                                            <i class="fas fa-sort-alpha-down ms-1"></i>
                                        </a>
                                        <a href="{{ route('product.size', ['sort' => 'desc']) }}" class="text-dark">
                                            <i class="fas fa-sort-alpha-up ms-1"></i>
                                        </a>
                                    </th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sizes as $size)
                                <tr>
                                    <td class="text-center align-middle">{{ $size->name }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editSize{{ $size->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('product.deleteSize', $size->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Size Modals -->
    @foreach($sizes as $size)
    <div class="modal fade" id="editSize{{ $size->id }}" tabindex="-1" aria-labelledby="editSizeLabel{{ $size->id }}"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSizeLabel{{ $size->id }}">Edit Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('product.updateSize', $size->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName{{ $size->id }}">Size:</label>
                            <input type="text" class="form-control" id="editName{{ $size->id }}" name="name"
                                value="{{ $size->name }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection