@extends('frontend.main_master')
@section('content')

    <div class="body-content">
        <div class="container">
            <div class="sign-in-page">
                <div class="row">

                    <!-- create a new account -->
                    <div class="col-md-12 col-sm-12 create-new-account">
                        <h4 class="checkout-subtitle">Create a new account</h4>
                        <p class="text title-tag-line">Create your new account.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="register-form outer-top-xs" role="form" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail2">Email Address <span>*</span></label>
                                <input type="email" name="email"  value="{{ old('email') }}" class="form-control unicase-form-control text-input" id="exampleInputEmail2" >
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Name <span>*</span></label>
                                <input type="text" name="name"  value="{{ old('name') }}" class="form-control unicase-form-control text-input" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Phone Number <span>*</span></label>
                                <input type="text" name="phone"  value="{{ old('phone') }}" class="form-control unicase-form-control text-input" id="exampleInputEmail1" >
                                <input type="hidden" name="user_role"  value="user" class="form-control unicase-form-control text-input" >
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Password <span>*</span></label>
                                <input type="password" name="password" class="form-control unicase-form-control text-input" id="exampleInputEmail1" >
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Confirm Password <span>*</span></label>
                                <input type="password" name="password_confirmation" class="form-control unicase-form-control text-input" id="exampleInputEmail1" >
                            </div>
                            <button type="submit" class="btn-upper btn btn-primary checkout-page-button">Sign Up</button>
                        </form>


                    </div>
                    <!-- create a new account -->			</div><!-- /.row -->
            </div><!-- /.sigin-in-->
            <!-- ============================================== BRANDS CAROUSEL ============================================== -->
        @include('frontend.body.brands')
        <!-- /.logo-slider -->
            <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->	</div><!-- /.container -->
    </div><!-- /.body-content -->

    @include('frontend.body.brands')
@endsection


