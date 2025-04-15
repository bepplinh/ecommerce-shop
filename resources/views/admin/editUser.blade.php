@extends('layout.adminDashboard')
@section('content')
<div class="container mt-5" id="edit-user-form">
    <h2 class="text-center mb-4">Edit User</h2>
    <form action="{{ route('updateUser', ['id' => $user->id]) }}" method="POST" class="shadow p-4 rounded bg-light" onsubmit="return confirmUpdate()">
        @csrf
        @method('POST')

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (Leave blank to keep current password):</label>
            <input type="password" class="form-control" id="password" name="password">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password:</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <div class="mb-3">
            <label class="form-label">Is Admin:</label>
            <select class="form-control" name="is_admin" required>
                <option value="0" {{ old('is_admin', $user->is_admin) == '0' ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('is_admin', $user->is_admin) == '1' ? 'selected' : '' }}>Yes</option>
            </select>
            @error('is_admin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-20">Update</button>
    </form>
</div>

<script>
    function confirmUpdate() {
        return confirm("Are you sure you want to update this user?");
    }
</script>

@endsection