@extends('layout.clientApp')
@section('head')
    <style>
        .register-form {
            width: 80%;
            margin: 0 auto;
        }

        button {
            background-color: #222222;
            color: white;
            height: 65px;
        }
    </style>
@endsection
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="login-register container">
            <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link nav-link_underscore active" id="register-tab" data-bs-toggle="tab"
                        href="#tab-item-register" role="tab" aria-controls="tab-item-register"
                        aria-selected="true">Register</a>
                </li>
            </ul>
            <div class="tab-content pt-2" id="login_register_tab_content">
                <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel"
                    aria-labelledby="register-tab">
                    <div class="register-form">
                        <form method="POST" action="{{ route('register') }}" name="register-form" class="needs-validation" novalidate="">
                            @csrf

                            <div class="form-floating mb-3">
                                <input class="form-control form-control_gray " name="username" value="" required=""
                                    autocomplete="username" autofocus="">
                                <label for="name">Username *</label>
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="pb-3"></div>

                            <div class="form-floating mb-3">
                                <input id="email" type="email" class="form-control form-control_gray " name="email"
                                    value="" autocomplete="email">
                                <label for="email">Email address *</label>
                    
                            </div>

                            <div class="pb-3"></div>

                            <div class="form-floating mb-3">
                                <input id="password" type="password" class="form-control form-control_gray "
                                    name="password" required="" autocomplete="new-password">
                                <label for="password">Password *</label>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="pb-3"></div>

                            <div class="form-floating mb-3">
                                <input id="password-confirm" type="password" class="form-control form-control_gray"
                                    name="password_confirmation" required="" autocomplete="new-password">
                                <label for="password">Confirm Password *</label>
                            </div>

                            <div class="d-flex align-items-center mb-3 pb-2">
                                <p class="m-0">Your personal data will be used to support your experience throughout this
                                    website, to
                                    manage access to your account, and for other purposes described in our privacy policy.
                                </p>
                            </div>

                            <button class="w-100 text-uppercase" type="submit">Register</button>

                            <div class="customer-option mt-4 text-center">
                                <span class="text-secondary">Have an account?</span>
                                <a href="{{ route('login') }}" class="btn-text js-show-register">Login to your Account</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
