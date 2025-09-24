@extends('web.layouts.master')
@section('content')
    <div class="section fix">
        <div class="col-lg-12 col-12 mb-40">

            <div class="section-title bg-dark pt-40 pb-40 m-0">
                <h2 class="title text-white text-uppercase">Have a Complain ?</h2>
            </div>

            <!--Hot Questions Accordion Start-->
            <div id="hot-questions-accordion" class="accordion text-center">
                <div class="card">                                              profile
                    <div class="card-header">
                        <a data-bs-toggle="collapse" href="#application-process-one">Submit Complaint (with option to hide your information)</a>
                    </div>
                    <!-- Add .show class to this item to make it visible on page load -->
                    <div id="application-process-one" class="collapse show" data-bs-parent="#hot-questions-accordion">
                        <div class="card-body">
                            <p>You can submit your complaint easily. Choose whether to share your name or keep it hidden. Every complaint is recorded securely in our system.</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed" data-bs-toggle="collapse" href="#application-process-two">Track Complaint</a>
                    </div>
                    <div id="application-process-two" class="collapse" data-bs-parent="#hot-questions-accordion">
                        <div class="card-body">
                            <p>After submission, you’ll receive a tracking ID. You can check the current status of your complaint anytime.</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed" data-bs-toggle="collapse" href="#application-process-three">Complaint Categories</a>
                    </div>
                    <div id="application-process-three" class="collapse" data-bs-parent="#hot-questions-accordion">
                        <div class="card-body">
                            <p>Browse and select the right category for your issue (e.g., service, billing, employee behavior, technical support, etc.). This helps us route your complaint to the right department faster.</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a class="collapsed" data-bs-toggle="collapse" href="#application-process-four">Resolution & Feedback</a>
                    </div>
                    <div id="application-process-four" class="collapse" data-bs-parent="#hot-questions-accordion">
                        <div class="card-body">
                            <p>Once your complaint is resolved, you’ll be notified. You can provide feedback on how satisfied you are with the solution. Your feedback helps us improve our services.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--Hot Questions Accordion End-->

            <div class="text-center col-12 mt-20">
                <a href="{{url('login')}}" class="btn btn-primary">LOGIN AND SUBMIT COMPLAIN</a>
            </div>

        </div>
    </div>
@endsection