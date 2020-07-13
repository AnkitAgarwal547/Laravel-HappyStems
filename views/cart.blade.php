@extends('layouts.app')

@section('content')
	@if( Cart::count() >0 )
		<div class="wrapper">
			<section class="checkOutWrapper" id="cart_page">
				<div class="container">
					<ul class="cart-data" style="text-align:center;"></ul>
				</div>

				@if (count($errors) > 0)
					<div class="container">
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				<div class="hscontainer">
					<ul id="tabs" class="nav nav-tabs fadeInUp wow" role="tablist">
						<div class="liner"></div>
						<li class="nav-item">
							<a id="tab-a" href="#cart-a" class="nav-link active" data-toggle="tab" role="tab">
								<div class="rountTab"> 1</div><span> Shopping Cart</span>
							</a>
						</li>
						<li class="nav-item">
							<a id="tab-b" href="#cart-b" class="nav-link" data-toggle="tab" role="tab" disabled>
								<div class="rountTab"> 2</div><span> Checkout</span>
							</a>
						</li>
						<li class="nav-item">
							<a id="tab-c" href="#cart-c" class="nav-link" data-toggle="tab" role="tab">
								<div class="rountTab"> 3</div><span> Order Complete</span>
							</a>
						</li>
					</ul>
					<div id="content" class="tab-content" role="tablist">
						<div id="cart-a" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-a">
							<div class="card-header" role="tab" id="heading-A">
								<h5 class="mb-0">
									<a data-toggle="collapse" href="#collapse-A" aria-expanded="true" aria-controls="collapse-A">
										Shopping Cart
									</a>
								</h5>
							</div>

							<div id="collapse-A" class="collapse show" data-parent="#content" role="tabpanel" aria-labelledby="heading-A">
								<div class="card-body">
									<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
											<div class="col-md-8" data-aos="fade-right" data-aos-delay="200">
												<div class="table-responsive checkOutTbl">
													<table class="table ">
														<thead>
														<tr>
															<th colspan="2">Product</th>
															<th>Qty</th>
															<th class="text-right">Total</th>
														</tr>
														</thead>
														<tbody>
														@php $subtotal = 0; @endphp
														@foreach(Cart::items() as $index => $items)
															@php $subtotal += $items->quantity * $items->price; @endphp
															<tr>
																<td>
																	@if (file_exists(public_path($items->img_src)) && $items->img_src!='')
																		<img src="{{URL::asset($items->img_src)}}" style="width:82px;">
																	@else
																		<img src="{{ asset('assets/uploads/no_img.gif') }}" style="width:82px;">
																	@endif
																</td>
																<td>
																	<a href="{!! url('shop').'/'.$items->slug !!}"><h2>{!! $items->name !!}</h2></a>
																	<p>- Delivery Date : {{ $items->product_delivery_date }}</p>
																	<p>- Delivery City : {{ $items->delivery_city }}</p>
																	<h6 class="cehckOutPrice"><i class="fal fa-rupee-sign"></i>{{ $items->price }}</h6>
																</td>
																<td>
																	<div class="quantityIncrmnt">
																		<div class="numbers-row">
																			<input type="text" name="cart_quantity[{!! $items->id !!}]" id="digit1" value="{{ $items->quantity > 1 ? $items->quantity : 1}}">
																		</div>
																	</div>
																</td>
																<td class="text-right">
																	<h6 class="cehckOutPrice"> <i class="fal fa-rupee-sign"></i>{{ $items->quantity * $items->price }}</h6>
																	<a href="{{ url('cart/remove_item').'/'.$items->id }}" class="dltButton hvr-wobble-horizontal"><i class="fal fa-trash-alt"></i></a>
																</td>
															</tr>

														@endforeach
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-md-4 fadeInRight wow">
												<div class="checkOutPayment">
													<h2>Payment Summary</h2>
													<div class="checkOutProcess">
														{{-- <div class="form-group row">--}}
														{{-- <div class="col-md-6">--}}
														{{-- <label>Sub-Total :</label>--}}
														{{-- </div>--}}
														{{-- <div class="col-md-6">--}}
														{{-- <h6><i class="fal fa-rupee-sign"></i>{{ $subtotal }}</h6>--}}
														{{-- </div>--}}
														{{-- </div>--}}
														{{-- <hr/>--}}
														<div class="anchor-mob-hide-details">Price Details <i class="far fa-angle-down"></i></div>
														<div class="mob-hide-details">
															@foreach(Cart::items() as $index => $items)
															<h5 class="product-name-cart-page">{{ $items->name }} x {{ $items->quantity }}</h5>

															<div class="form-group row">
																<div class="col-md-6">
																	<label><span>Price x {{ $items->quantity }}:</span></label>
																</div>
																<div class="col-md-6">
																	<h6><i class="fal fa-rupee-sign"></i> {{ $items->price * $items->quantity }}</h6>
																</div>
															</div>

															<div class="form-group row">
																<div class="col-md-6">
																	<label>{{ $items->product_delivery_name }}: <span>({{ Carbon\Carbon::parse($items->product_delivery_time_from)->format('g:i A') }} - {{ Carbon\Carbon::parse($items->product_delivery_time_to)->format('g:i A') }})</span></label>
																</div>
																<div class="col-md-6">
																	<h6><i class="fal fa-rupee-sign"></i> {{ $items->product_delivery_charge }}</h6>
																</div>
															</div>
															@if($items->extra_double_flower_price || $items->extra_vase_price)
																<div class="form-group row">
																	<div class="col-md-6">
																		<label>Extras:
																			@if($items->extra_double_flower_price)
																				<span>(Double Flower x {{ $items->quantity }} ):</span><br>
																			@endif
																			@if($items->extra_vase_price)
																				<span>(Vase x {{ $items->quantity }} ):</span>
																			@endif
																		</label>
																	</div>
																	<div class="col-md-6">
																		@if($items->extra_double_flower_price)
																			<h6><i class="fal fa-rupee-sign"></i> {{ $items->extra_double_flower_price }}</h6>
																		@endif

																		@if($items->extra_vase_price)
																			<h6><i class="fal fa-rupee-sign"></i> {{ $items->extra_vase_price }}</h6>
																		@endif
																	</div>
																</div>
															@endif

															@if($items->add_on_total_quantity > 0)
																<div class="form-group row">
																	<div class="col-md-6">
																		<label>{{ $items->add_on_total_quantity }} Add-Ons:
																			@if(count($items->add_on_price) > 0)
																				@foreach($items->add_on_price as $key=>$addOnPrice)
																					@if($items->add_on_quantity[$key] > 0)
																						<span>
																			{{ '(' . $items->add_on_name[$key] . ' x ' . $items->quantity.'):'}}
																		</span>
																					@endif
																				@endforeach
																			@endif
																		</label>
																	</div>
																	<div class="col-md-6">
																		@if(count($items->add_on_price) > 0)
																			@foreach($items->add_on_price as $key=>$addOnPrice)
																				@if($items->add_on_quantity[$key] > 0)
																					<h6><i class="fal fa-rupee-sign"></i>
																						{{ $items->add_on_price[$key] }}
																					</h6>
																				@endif
																			@endforeach
																		@endif
																	</div>
																</div>
															@endif
															<hr>
															<div class="form-group row">
																<div class="col-md-6">
																	<label><strong>SubTotal:</strong></label>
																</div>
																<div class="col-md-6">
																	<h6><i class="fal fa-rupee-sign"></i>
																		<strong>{{ $items->cartTotalPrice }}</strong>
																	</h6>
																</div>
															</div>
															<hr/>
															@endforeach
														</div>

														@if(Session::has('applyed_coupon_code'))
															<div class="form-group row cart-coupon">
																<div class="col-md-6">
																	<label>Discount : </label>
																</div>
																<div class="col-md-6">
																	<h6><i class="fal fa-rupee-sign"></i>{!! Session::get('applyed_coupon_price') !!}</h6>
																	<button class="remove-coupon btn c-btn btn-lg c-theme-btn c-btn-square c-font-white c-font-bold c-font-uppercase c-cart-float-r cart-btn checkOutBtn hvr-wobble-horizontal" type="button"><i class="fal fa-trash-alt"></i>Remove coupon</button>
																</div>
															</div>
															<hr>
														@endif

														<div class="form-group row cart-grand-total">
															<div class="col-md-6">
																<label>Grand Total :</label>
															</div>
															<div class="col-md-6 c-cart-subtotal-border">
																{{-- <h3 class="value"><i class="fal fa-rupee-sign"></i>{!! getCartTotal() !!}</h3>--}}
																<h3 class="value"><i class="fal fa-rupee-sign"></i>
																	@php $total = 0; @endphp
																	@foreach(Cart::items() as $index => $items)
																		@php
																			$total = $total + $items->cartTotalPrice;
																		@endphp
																	@endforeach
																	{{ $total }}
																</h3>
															</div>
														</div>
														<div class="form-group row btn-cart-action">
															<div class="col-md-5">
																<button type="submit" class="updateCart hvr-wobble-horizontal" name="update_cart" value="update_cart">Update Cart</button>
															</div>
															<div class="col-md-7">
																<a href="javascript:void(0)" id="gotocheckouttab" class="btn c-btn btn-lg c-theme-btn c-btn-square c-font-white c-font-bold c-font-uppercase c-cart-float-r cart-btn checkOutBtn hvr-wobble-horizontal">Proceed to Checkout</a>
															</div>
														</div>
													</div>
													<div class="couponBox">
														<h3>Do you Have a Coupon or Voucher?</h3>
														<div class="form-group apply-coupon">
															<input class="form-control " id="apply_coupon_code" name="apply_coupon" placeholder="Coupon Code" type="text" value="{!! Session::has('applyed_coupon_code') ? Session::get('applyed_coupon_code') : '' !!}">
															<button class="applyCouponBtn" name="apply_coupon_post" id="apply_coupon_post">Apply Coupon</button>
														</div>

													</div>
												</div>

											</div>
										</div>
									</form>
								</div>
							</div>
						</div>

						{{--Second tab Checkout--}}

						<div id="cart-b" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-b">
							<div class="anchor-gobacktocart">Go Back</div>
							@php $points = 0; @endphp

							@foreach(Cart::items() as $index => $items)
								@php $points += $items->quantity * $items->price; @endphp
							@endforeach
							<form class="c-shop-form-1 wow fadeInLeft" method="post" action="{{ route('checkout') }}" id="checkout">
								{{ csrf_field() }}
								<div class="card-header" role="tab" id="heading-B">
									<h5 class="mb-0">
										<a class="collapsed" data-toggle="collapse" href="#collapse-B" aria-expanded="false" aria-controls="collapse-B">
											Checkout
										</a>
									</h5>
								</div>
								<div id="collapse-B" class="collapse" data-parent="content" role="tabpanel" aria-labelledby="heading-B">
									<div class="card-body">
										<div class="row">
											<div class="col-md-8" data-aos="fade-right" data-aos-delay="200">
												@foreach(Cart::items() as $index => $items)
													@if (count($savedAdd) > 0)
														<div class="checkOutTitle"><h2>Delivery Address</h2></div>
														<div class="row">
															@foreach($savedAdd as $key=>$add)
																@if($items->delivery_pincode == $add->postcode)
																	<div class="col-md-6">
																		<div class="chooseAddress">
																			<div class="form-group checkOutRadio">
																				<input rel="{{ $add->name }}, {{ $add->phone }}, {{ $add->address }}, {{ $add->city }}, {{ $add->state }}, {{ $add->postcode }}, {{ $add->landmark }}, {{ $add->address_type }}, {{ $add->id }}" type="radio" id="address{{$items->id}}{{$key}}" name="address{{ $index }}" class="selectadd" >

																				<label for="address{{$items->id}}{{$key}}"><strong>Address </strong>
																					{{ $add->name }}, {{ $add->address }}, {{ $add->city }}, {{ $add->state }}, {{ $add->postcode }}, {{ $add->landmark }}
																				</label>
																			</div>
																		</div>
																	</div>
																@endif
															@endforeach
														</div>
													@endif
													<div class="checkoutForm">
														<div class="checkOutTitle"><h2>add new Address</h2></div>
														<div class="checkOutTitle"><h4>{{ $items->name }}</h4></div>
														<div class="row">
															<div class="form-group col-md-6">
																<label>Name*</label>
																<input type="text" name="billing_first_name[{{ $items->id }}]" id="billing_first_name" class="form-control billing_first_name" value="{{ old('billing_first_name.'.$index) }}" required="">
															</div>
															<div class="form-group col-md-6">
																<label>Mobile Number*</label>
																<input type="tel" name="phone[{{ $items->id }}]" id="phone" class="form-control phone" placeholder="Phone" value="{{ old('phone.'.$index) }}" required="">
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-6">
																<label>Pin Code*</label>
																@if($items->delivery_pincode)
																	<input name="billing_postcode[{{ $items->id }}]" id="billing_postcode" type="text" class="form-control input_text numeric billing_postcode" placeholder="Postcode / Zip" value="{{$items->delivery_pincode}}" required="" disabled>
																@else
																	<input name="billing_postcode[{{ $items->id }}]" id="billing_postcode" type="text" class="form-control input_text numeric billing_postcode" placeholder="Postcode / Zip" value="" required="">
																@endif
															</div>
															<div class="form-group col-md-6">
																<label>Landmark*</label>
																<input type="text" name="landmark[{{ $items->id }}]" class="form-control landmark" value="{{ old('landmark.'.$index) }}" required="">
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-12">
																<label>Recipient's Address*</label>
																<textarea class="form-control billing_address" name="billing_address[{{ $items->id }}]" id="billing_address" placeholder="Apartment, street, area etc." required="">{{ old('billing_address.'.$index) }}</textarea>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-6">
																<label>Town / City*</label>
																@if($items->delivery_city)
																	<input name="billing_town[{{ $items->id }}]" id="billing_town" type="text" class="billing_town form-control c-square c-theme" placeholder="Town / City" value="{{ $items->delivery_city }}" required="" disabled>
																@else
																	<input name="billing_town[{{ $items->id }}]" id="billing_town" type="text" class="billing_town form-control c-square c-theme" placeholder="Town / City" value="" required="">
																@endif

															</div>
															<div class="form-group col-md-6">
																<label>State*</label>
																@if($items->delivery_state)
																	<input name="billing_state[{{ $items->id }}]" id="billing_state" class="form-control billing_state c-square c-theme" value="{{ $items->delivery_state }}" required="" disabled>
																@else
																	<input name="billing_state[{{ $items->id }}]" id="billing_state" class="form-control billing_state c-square c-theme" required="">
																@endif
															</div>
														</div>
														<div class="cntr-radio location_type">
															<label class="btn-radio">
																<input type="radio" name="location[{{ $items->id }}]" class="address_type" value="home">
																<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																<span>Home</span>
															</label>
															<label class="btn-radio">
																<input type="radio" name="location[{{ $items->id }}]" class="islocationoffice address_type" value="office">
																<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																<span>Office</span>
															</label>
															<label class="btn-radio">
																<input type="radio" name="location[{{ $items->id }}]" value="other" class="address_type">
																<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																<span>Other</span>
															</label>
														</div>
														<input type="hidden" name="address_id[{{ $items->id }}]" class="address_id">
														<div class="clearfix"></div>
														<div class="alert alert-primary alert-islocationoffice" role="alert"><i class="fas fa-info"></i> Gift Will Be Delivered In General Office Hours.</div>
														<div class="checkbox_for_message">
															<div class="form-group for_desktop">
																<div class="check-envelope">
																	<label for="check-envelope">
																		<input type="checkbox" name="greeting[{{$items->id}}]" id="check-envelope" data-id="{{ $items->id }}" class="toggle-switch check-envelope-button">
																		<span class="check-toggle"></span>
																		<div class="click-envelope">
																			<div class="envelope active">
																				<div class="top">
																					<div class="outer"><div class="inner"></div></div>
																				</div>
																				<div class="bottom"></div>
																				<div class="left"></div>
																				<div class="right"></div>
																				<div class="cover"></div>
																				<div class="paper">
																					<i class="fas fa-heart"></i>
																				</div>
																			</div>
																		</div>
																		<h2>Free Message Card</h2>
																	</label>
																</div>
															</div>
														</div>
													</div>
												@endforeach
											</div>
											<div class="col-md-4">
												<div class="checkOutTitle"><h2>order details</h2></div>
												<div class="orderDetails">
													<div class="orderDetailHeading">
														<h6>Products</h6>
														<button class="hvr-wobble-horizontal" id="gotofirsttabcart">Change</button>
													</div>
													<div class="checkoutOrderSection">
														@php $subtotal = 0 @endphp
														@foreach(Cart::items() as $index => $items)
															@php $subtotal += $items->cartTotalPrice; @endphp
															<div class="orderProductDetails">
																<div class="productText">
																	<h3><small>{{ $items->quantity }}x</small> {!! $items->name !!}</h3>
																	<p>- Delivery Date : {{ $items->product_delivery_date }}</p>
																	<p>- Delivery City : <strong>{{ $items->delivery_city }}</strong></p>
																</div>
																<div class="productPrice">
																	<h6><i class="fal fa-rupee-sign"></i>{{ $items->cartTotalPrice }}</h6>
																</div>
															</div>
														@endforeach

													</div>
													<ul>
														<li>
															<label>Order Total </label>
															<h4><i class="fal fa-rupee-sign"></i>
																@php $total = 0; @endphp
																@foreach(Cart::items() as $index => $items)
																	@php
																		$total = $total + $items->cartTotalPrice;
																	@endphp
																@endforeach
																{{ $total }}
															</h4>
														</li>
													</ul>

													<div class="slectWallet">
														<div class="form-group checkOutRadio">
															<div class="cntr-radio payment-buttons">
																<label class="btn-radio">
																	<input type="radio" id="radio2" value="instamojo" name="payment">
																	<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																	<span> <img src="{{ asset('/assets/uploads/instamojo.png') }}" style=" width: 100px; "></span>
																</label>
																<label class="btn-radio">
																	<input type="radio" name="payment" value="paytm" id="paytm" >
																	<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																	<span> <img src="{{ asset('/assets/uploads/paytm.png') }}" style=" width: 60px;"> </span>
																</label>
																<label class="btn-radio">
																	<input type="radio" name="payment" value="ccavenue" id="ccavenue" >
																	<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																	<span> <img src="{{ asset('/assets/uploads/ccavenue.png') }}" style=" width: 110px;"> </span>
																</label>
																<label class="btn-radio">
																	<input type="radio" name="payment" value="paypal" id="paypal" >
																	<svg width="20px" height="20px" viewBox="0 0 20 20"><circle cx="10" cy="10" r="9"></circle><path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path><path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path></svg>
																	<span> <img src="{{ asset('/assets/uploads/paypal-logo.png') }}" style=" width: 110px;"> </span>
																</label>
															</div>
														</div>
													</div>

													<div class="termSection">
														<div class="form-group customCheckBox">
															<input type="checkbox" name="accept_terms" type="checkbox" id="checkbox1-11">
															<label for="checkbox1-11">I agree to the </label>
															<a href="{{ url('/terms-conditions') }}" target="_blank">Terms & Conditions / Disclaimer</a>
														</div>
													</div>

													<div class="placeOrder"><button class="placeOrderBtn hvr-wobble-horizontal" > place order</button></div>
{{-- <a href="https://www.instamojo.com/@happystems/" rel="im-checkout" data-text="Pay with Instamojo" data-css-style="color:#ffffff; background:#75c26a; width:300px; border-radius:4px" data-layout="vertical"></a>--}}
{{-- <script src="https://js.instamojo.com/v1/button.js"></script>--}}
												</div>

											</div>
										</div>

									</div>
								</div>

								{{-----------------------------}}
								@foreach(Cart::items() as $index => $items)
									<div class="modal modal-envelope open-modal{{ $items->id }}">
										<div class="front"><img src="{{ config('constants.img_path') }}/card-cover.jpg" alt="matryoshka"></div>
										<div class="back">
											<div class="content">
												<img src="{{ config('constants.img_path') }}/card-inner.jpg" alt="matryoshka">
											</div>
										</div>
										<div class="opened">
											<div class="content">
												<input type="hidden" name="occassion[{{ $items->id }}]" class="occassion-hidden-field{{$items->id}}" value="">
												<input type="text" id="tomessage" name="message_to[{{$items->id}}]" class="form-control" placeholder="Dear"/>
												<textarea rows="6" id="inputmessage" name="card_message[{{$items->id}}]" class="form-control inputmessage" placeholder="Message" maxlength="250"></textarea>
												<div id="charNum" class="charNum"></div>
												<div class="form-group customCheckBox">
													<input type="checkbox" id="checkanonymously2" name="message_anonymously[{{ $items->id }}]" class="checkanonymously">
													<label for="checkanonymously2">Send Anonymously</label>
												</div>

												<input type="text" id="frommessage" name="message_from[{{ $items->id }}]" class="form-control frommessage" placeholder="from" value="">
											</div>
										</div>
									</div>

								@endforeach
								<div class="wrapper-envelope"></div>
								{{-----------------------------}}


							</form>
						</div>


						{{-- <div id="cart-c" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-c">--}}
						{{-- <div class="card-header" role="tab" id="heading-C">--}}
						{{-- <h5 class="mb-0">--}}
						{{-- <a class="collapsed" data-toggle="collapse" href="#collapse-C" aria-expanded="false" aria-controls="collapse-C">--}}
						{{-- Order Complete--}}
						{{-- </a>--}}
						{{-- </h5>--}}
						{{-- </div>--}}
						{{-- <div id="collapse-C" class="collapse" role="tabpanel" data-parent="404.htmlcontent" aria-labelledby="heading-C">--}}
						{{-- <div class="card-body">--}}
						{{-- <div class="row">--}}
						{{-- <div class="col-md-12 fadeInUp wow">--}}
						{{-- <div class="completeOrderSection">--}}
						{{-- <div class="completeOrderIcon"><img src="{{ config('constants.img_path') }}/completeOrderIcon.png"></div>--}}
						{{-- <div class="orderCompleteText">--}}
						{{-- <p>Thank you for ordering in HappySTEMS. You will receive a confirmation email shortly. Now check progress with your order.</p>--}}

						{{-- <button class="trackOrderBtn hvr-wobble-horizontal">Back To Home</button>--}}

						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
						{{-- </div>--}}
					</div>
				</div>
			</section>
			<div class="flowerStrip"></div>
		</div>
		@foreach(Cart::items() as $index => $items)
			<div class="predefined-templates pre-temp{{$index}}" data-id="{{$index}}">
				<div class="container-fluid">
					<div class="row align-items-center justify-content-center">
						<div class="col-sm-3">
							<select class="occassion selectric occasion{{$index}}" data-id="{{$index}}" name="occassion" required>
								<option value="" selected>Select Occassion</option>
								<option value="birthday">Birthday</option>
								<option value="anniversary">Anniversary</option>
								<option value="lovenromance">Love n Romance</option>
							</select>
						</div>
						<div class="col-sm-3">
							<select class="message selectric message{{$index}}" data-id="{{$index}}" name="message">
								<option value="" selected>Select Message on Card</option>
							</select>
						</div>
						<div class="col-sm-3">
							<div class="buttons row align-items-center justify-content-center">
								<button class="btn btn-green close-envelope save-greeting">Save</button>
								<a href="javascript:void(0)" class="btn btn-green-outline close-envelope" id="close-envelope">Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	@else
		@include('cart-empty')
	@endif
@endsection

@section('style')
	<style>
		.cntr-radio.location_type label.btn-radio {
			float: left;
		}
	</style>
@stop
@section('scripts')
	<script>
		$('.selectadd').click(function(){
			var id = $(this).attr('rel');
			var splittedData = id.split(', ');
			let parentNode = $(this).parent().parent().parent().parent().next();
			parentNode.find('.billing_first_name').val(splittedData[0]);
			parentNode.find('.phone').val(splittedData[1]);
			parentNode.find('.billing_address').html(splittedData[2]);
			parentNode.find('.billing_town').val(splittedData[3]);
			parentNode.find('.billing_state').val(splittedData[4]);
			parentNode.find('.billing_postcode').val(splittedData[5]);
			parentNode.find('.landmark').val(splittedData[6]);

			$.each(parentNode.find('.location_type').children().children('input'), function(key, value){
				if($(this).val() === splittedData[7]){
					$(this).prop('checked', true);
				}
			});
			parentNode.find('.address_id').val(splittedData[8]);
		});


		$('.address_type').click(function(){
			$('.alert-islocationoffice').hide('slow');
		});
		$('.islocationoffice').click(function(){
			var locID = $(this).attr('data-id');
			$(this).parent().parent().parent().children('.alert-islocationoffice').show('slow');
		})
	</script>
@stop