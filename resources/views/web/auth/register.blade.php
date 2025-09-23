@extends('web.layouts.master')
@section('content')
    <div class="section fix" data-bg-image="{{publicImage('frontend/images/bg/contact-1.jpg')}}" style="background-image: url({{publicImage('frontend/images/bg/contact-1.jpg')}});">
        <div class="col-lg-6 offset-lg-3 col-12 section-padding">

        <div class="section-title">
            <h2 class="title">LOGIN</h2>
        </div>

        <!--Comment Form Start-->
        <div class="contact-form col-lg-8 mx-auto">
            <form id="contact-form" action="{{url("register")}}" method="post">
                @csrf
                <div class="row mbn-10">
                    <div class="col-12 mb-10">
                        <input type="text" name="name" placeholder="Name" required>
                        <span class="text-danger">{{$errors->has('name') ? $errors->first('name') : ''}}</span>
                    </div>
                    <div class="col-12 mb-10">
                        <input type="email" name="email" placeholder="Email Address" required>
                        <span class="text-danger">{{$errors->has('email') ? $errors->first('email') : ''}}</span>
                    </div>
                    <div class="col-12 mb-10">
                        <input type="number" name="phone" minlength="11" maxlength="11" placeholder="Phone Number" required>
                        <span class="text-danger">{{$errors->has('phone') ? $errors->first('phone') : ''}}</span>
                    </div>
                    <div class="col-12 mb-10">
                        <input type="password" name="password" placeholder="Password" required>
                        <span class="text-danger">{{$errors->has('password') ? $errors->first('password') : ''}}</span>
                    </div>
                    <div class="col-12 mb-10">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                        <span class="text-danger">{{$errors->has('password_confirmation') ? $errors->first('password_confirmation') : ''}}</span>
                    </div>
                    <input type="hidden" name="remember_token" value="some_random_token_here">
                    <div class="col-12 mb-10">
                        <button class="btn btn-primary w-100" type="submit">REGISTER</button>
                    </div>
                    <div class="col-10 offset-1 text-end">
                        <h2 class="text-primary">Already user <a class="text-success" href="{{url('login')}}">Login</a></h2>
                    </div>
                </div>
            </form>
            <p class="form-message mt-15 d-none"></p>
        </div>
        <!--Comment Form End-->

    </div>
</div>
@endsection
