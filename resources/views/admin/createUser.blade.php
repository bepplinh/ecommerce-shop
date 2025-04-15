@extends('layout.adminDashboard')
@section('head')
<style>

    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');


    #create-user-form {
        font-family: 'Roboto', sans-serif;
    }
    
    .form-control {
        border-radius: 0.5rem;
    }

    .btn-primary {
        border-radius: 0.5rem;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
@section('content')
    <div class="container mt-5" id="create-user-form">
        <h2 class="text-center mb-4">Create User</h2>
        <form action="{{ route('createUser') }}" method="POST" class="shadow p-4 rounded bg-light">
            @csrf
    
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
    
            <div class="mb-3">
                <label class="form-label">Is Admin:</label>
                <select class="form-control" name="is_admin" required>
                    <option value="0" {{ old('is_admin') == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('is_admin') == '1' ? 'selected' : '' }}>Yes</option>
                </select>
                @error('is_admin')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <button type="submit" class="btn btn-primary w-20">Create User</button>
        </form>
    </div>

@endsection