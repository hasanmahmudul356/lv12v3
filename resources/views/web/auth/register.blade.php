@extends('web.layouts.master')
@section('content')
    <div class="section fix" data-bg-image="{{publicImage('frontend/images/bg/contact-1.jpg')}}" style="background-image: url({{publicImage('frontend/images/bg/contact-1.jpg')}});">
        <div class="col-lg-6 offset-lg-3 col-12 section-padding">

        <div class="section-title">
            <h2 class="title">LOGIN</h2>
        </div>

        <!--Comment Form Start-->
        <div class="contact-form col-lg-8 mx-auto">
            <form id="contact-form" action="https://htmldemo.net/edwards/edwards/assets/php/contact-mail.php" method="post">
                <div class="row mbn-10">
                    <div class="col-12 mb-10">
                        <input type="text" name="name" placeholder="Name">
                    </div>
                    <div class="col-12 mb-10">
                        <input type="email" name="email" placeholder="Email Address">
                    </div>
                    <div class="col-12 mb-10">
                        <input type="number" name="phone" placeholder="Phone Number">
                    </div>
                    <div class="col-12 mb-10">
                        <input type="password" name="password" placeholder="Password">
                    </div>
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