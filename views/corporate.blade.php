@extends('layouts.app')
@section('content')
    <div class="wrapper">
        <section class="body_menu_links">
            <div class="hscontainer">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Corporate Subscription</li>
                </ul>
            </div>
        </section>

        <section class="corporate_panel fadeInRight wow">
            <div class="hscontainer">
                <h2>ORGANIZATIONS NEED FLOWERS</h2>
                <h3>Flowers constitute 80% when its comes to corporate gifting.</h3>
                <a class="btn btn-green hvr-wobble-horizontal" href="#enquire-now">Book Now</a>
            </div>
        </section>

        <section class="corporate_content_panel fadeInLeft wow">
            <div class="hscontainer">
                <div class="row">
                    <p><strong>With over a decade of experience in the industry of floral gifting</strong> floral décor and just about everything in flowers,<br>
                        we are happy to assist you in taking your stakeholder relationship to a higher level.</p>
                    <h4>OUR CORPORATE SERVICES INCLUDE:</h4>
                    <p>Delivering flowers, cakes, chocolates to your esteemed clients, employees vendors on their special occasions.<br>
                        Floral arrangements to enhance your Office Reception and other meeting areas.<br>
                        Exquisite floral Decor for events such as Seminars, Product launches and other Office Functions.<br>
                        To explore possibilities, write to us at <a href="mailto:contact@happystems.com">contact@happystems.com.</a></p>
                </div>
            </div>
        </section>

        <section class="corporate_service_panel">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="inner_div fadeInLeft wow">
                            <i class="fal fa-briefcase"></i>
                            <h3>Professionalism</h3>
                            <p>Our trained Call Center executives will ensure efficient order taking and processing. We provide complete convenience of ordering over phone, net or email. Delivery confirmation is given via mail/SMS.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="inner_div2 fadeInRight wow">
                            <i class="fal fa-tools"></i>
                            <h3>Services</h3>
                            <p>Speed of delivery, courteousness of shop supervisor and call
                                center executive will ensure smooth execution of all your
                                orders. Most important, our uniformed and pleasant
                                looking delivery team will add flavor to your flower gifting
                                experience.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="seamless_panel fadeInRight wow">
            <div class="container">
                <div class="row">
                    <div class="inner_div">
                        <h3>Seamless Billing</h3>
                        <p>We provide Monthly / Fortnightly billing facility.<br>
                            All bills at the end of billing cycle are accompanied with POD's</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact_panel_for_seamless fadeInLeft wow">
            <div class="hscontainer">
                <div class="row">
                    <h3>Contact us at:</h3>
                    <h3>+91 8448449480</h3>
                    <h3>contact@happystems.com</h3>
                </div>
            </div>

        </section>

        <section class="enquiry_form_panel bounceIn wow" id="enquire-now">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <h3>Enquire now</h3>
                        <div class="enquire_form">
                            <form id="corporate-sub-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="subscription_type" value="corporate">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Name<required>*</required></label>
                                                <input type="text" name="name" placeholder="Name" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Email Address<required>*</required></label>
                                                <input type="text" name="email_address" placeholder="Email Address" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Reason for Contact</label>
                                                <input type="text" name="reason_for_contact" placeholder="Reason for Contact">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Mobile No.</label>
                                                <input type="text" name="mobile" placeholder="Mobile No.">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Say Something</label>
                                                <textarea placeholder="Say Something" name="saysomething"></textarea>

                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group text-center">
                                                <button class="btn btn-green hvr-wobble-horizontal">Send Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if(count($reviews) > 0 )
            <section class="feedbackTestiMonial">
                <h2>What People are saying</h2>
                <div class=" feedbackTestiContent mx-auto mt-3 mb-3">
                    <div id="carouselTestimonial" class="carousel carousel-testimonial slide" data-ride="carousel">
                    <!-- <ol class="carousel-indicators">
                    @foreach($reviews as $key=>$review)
                        <li data-target="#carouselTestimonial" data-slide-to="{{ $key }}" class="@if($key==0){{'active'}}@endif"></li>
                    @endforeach
                            </ol> -->
                        <div class="carousel-inner">
                            @foreach($reviews as $key=>$review)
                                <div class="carousel-item text-center @if($key==0){{'active'}}@endif">
                                    <div class="carousel-testimonial-img p-1 border rounded-circle m-auto">
                                        <img class="d-block w-100 rounded-circle" src="{{ config('constants.img_path') }}/grid_2/testiIcon.png" alt="First slide">
                                    </div>
                                    <h5 class="mt-2 mb-2"><strong class="text-warning text-uppercase">{{ $review->name }}</strong></h5>
                                    <h6 class="text-dark m-0">{{ $review->location }}</h6>
                                    <p class="m-0 pt-3">{{ $review->review }}</p>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselTestimonial" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselTestimonial" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <div class="flowerStrip"></div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#corporate-sub-form').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: base_url+'/subscription/save',
                data: $('#corporate-sub-form').serialize(),
                success: function(response){
                    if(response.status == 'failed'){
                        swal('Error', response.msg, 'error');
                    }
                    if(response.status == 'success'){
                        swal('Success', response.msg, 'success');
                        $('#corporate-sub-form')[0].reset();
                    }
                    else{
                        if(response.errors){
                            $.each(response.errors, function(index, value){
                                alertify.error(value);
                            });
                        }
                        else{
                            swal('Error', 'Something went Wrong ! Please try again Later !');
                        }
                    }
                }
            })
        });
    </script>
@stop
