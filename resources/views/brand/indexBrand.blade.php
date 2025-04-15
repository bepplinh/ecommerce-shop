@extends('layout.adminDashboard')
@section('content')
<table class="table table-striped table-bordered table-hover table-sm text-center align-middle" style="max-width: 80%; margin: 0 auto;">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($brands as $brand)
        <tr>
            <td class="align-middle">{{ $brand->id }}</td>
            <td class="align-middle">{{ $brand->name }}</td>
            <td class="align-middle">
                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-width: 100px; max-height: 100px;">
            </td>
            <td class="align-middle">
                <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection