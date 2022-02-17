@extends('frontend.main_master')
@section('content')

    <div class="body-content">
        <div class="container">
            <div class="sign-in-page">
                <div class="row">
                    <!-- Sign-in -->
                    <div class="col-md-6 col-sm-6 sign-in">
                        <h4 class="">Forgot password page</h4>
                        <p class="">Hello, reset password here</p>

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>

                        <form class="register-form outer-top-xs" role="form" method="post" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Email Address <span>*</span></label>
                                <input type="email" name="email" :value="old('email')" class="form-control unicase-form-control text-input" id="exampleInputEmail1" >
                            </div>

                            <button type="submit" class="btn-upper btn btn-primary checkout-page-button">
                                {{ __('Email Password Reset Link') }}
                            </button>
                        </form>
                    </div>
                    <!-- Sign-in -->
                </div>
            </div>
            <!-- ============================================== BRANDS CAROUSEL ============================================== -->
            @include('frontend.body.brands')
            <!-- /.logo-slider -->
            <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->	</div><!-- /.container -->
    </div><!-- /.body-content -->

    @include('frontend.body.brands')
@endsection




