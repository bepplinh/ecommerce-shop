{{-- <h1>đây là trang admin</h1>
<a href={{ route('logout') }}>Đăng xuất</a>
<a href={{ route('products.create') }}>Thêm sản phẩm</a> --}}

@extends('layout.adminDashboard')

@section('content')
<i class=""></i>
<script>
    @if(session('success'))
        toastr.success("{{ session()->pull('success') }}");
    @endif
  
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
  
    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
  
    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif
  </script>
@endsection