@extends('layouts.app')
@section('style')
<style>
    .alertify-notifier.ajs-right {
        right: 10px;
        cursor: pointer;
    }
</style>
@stop
@section('content')

    <div class="wrapper">
        @if (!empty($page->header_image))
            <section class="pageheader" style="background: url('{{ URL::asset($page->header_image) }}')">
                <div class="hscontainer">
                    <div class="pageheader-content">
                        <h1>Contact Us</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </section>
        @endif

        <section class="contactMidleContent">
            @if ($page->map)
                <div class="mapSection">
                    <iframe src="{{ $page->map }}" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            @endif


            <div class="contactFormAddress">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <h2>Enquire Now</h2>
                            <form action="{{ url('contact/send') }}" method="post" id="contact_us">
                                {{ csrf_field() }}
                                <div class="form-group"> <input type="text" name="name" id="name" placeholder="Name" class="form-control"> </div>
                                <div class="form-group"> <input type="text" name="email" id="email" placeholder="Email Id"  class="form-control"> </div>
                                <div class="form-group"> <input type="number" name="phone" id="phone" placeholder="Phone No."  class="form-control"> </div>
                                <div class="form-group"> <textarea placeholder="Your Query" name="query" id="query" class="form-control"></textarea></div>
                                <div class="form-group"> <input type="submit" value="Send Us" class="hvr-wobble-horizontal" > </div>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <h2>Our Offices</h2>
                            <div class="ourAddress">
                                <div class="row">

                                    @if (!empty($contactDetails))

                                        @foreach($contactDetails as $key=>$details)
                                        <div class="col-md-6 mb-5 mt-4">
                                            <div class="addressBox @if($key == 1) float-right @else @endif ">
                                                <div class="addressIcon"><img src="{{ config('constants.img_path') }}/contactBuildingIcon.png"></div>
                                                <h5>{{ $details->name }}</h5>
                                                <p>{{ $details->address }}</p>
                                            </div>
                                        </div>
                                        @endforeach

                                    @endif

                                    <div class="col-md-12 ">
                                        <div class="addressBox w-100">
                                            <h4><small>Telephone :</small> +91 8448449480</h4>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contactFooter">
            <div class="contactFooterContent">
                <h2>CAN'T FIND WHAT YOU ARE LOOKING FOR?</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="footerContentBox writeUsBG">
                            <h5>We’re here to help you</h5>
                            <h5>8 <small>am</small> - 12 <small>AM</small></h5>
                            <button class="writeUsBtn hvr-wobble-horizontal">Write to us</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footerContentBox callUsBG">
                            <h5>Feel free to call us</h5>
                            <h5>8 <small>am</small> - 12 <small>AM</small></h5>
                            <button class="writeUsBtn hvr-wobble-horizontal">Call Us</button>
                        </div>

                    </div>
                </div>
            </div>
        </section>


        <div class="flowerStrip"></div>

    </div>

@endsection
@section('scripts')

<script>
	$(document).ready(function () {
		var form = $('#contact_us');
		form.submit(function(e) {
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var query = $('#query').val();
            if((name == '') || (email == '') || (phone == '') || (query == '')){
                alertify.error('Please Fill all the fields !!!');
            }
            if(email != ''){
                var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

                if(!pattern.test(email))
                {
                    alertify.error('Enter a valid E-mail address');
                    return false;
                }
            }
            if(phone != ''){
                var len = phone.length;
                if(len != 10){
                    alertify.error('Enter Valid Phone Number');
                    return false;
                }
            }
			e.preventDefault();
			$.ajax({
				url     : form.attr('action'),
				type    : form.attr('method'),
				data    : form.serialize(),
				dataType: 'json',
				success : function ( data )
				{
					if(data.errors) {
						$.each(data.errors, function (key, value) {
							//$('.'+key+'-error').html(value);
							$('input[name="'+key+'"],textarea[name="'+key+'"]').closest('div.form-group').addClass('has-error');
						});
                	}
					if(data.success == 1){
					    alertify.success('Query Submitted Successfully');
						form.prepend('<div class="alert alert-success alert-dismissable"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong> Your query has been sent successfully. We will be get back to you soon.</div>')
						form[0].reset();
					}
				},
				error: function( json )
				{
					if(json.status === 422) {
						$.each(json.responseJSON, function (key, value) {
							//$('.'+key+'-error').html(value);
							$('input[name="'+key+'"],textarea[name="'+key+'"]').closest('div.form-group').addClass('has-error');
						});
					} else {
						// Error
                    	
                    	// alert('Incorrect credentials. Please try again.')
					}
				}
			});
		});
	 
	});
</script>

@stop