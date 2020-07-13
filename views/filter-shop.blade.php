@php $catOrder = 1000; @endphp
@foreach($all_products as $productKey => $product)
    {{--    @if($product->categoryOrder < $catOrder)--}}
    {{--        @php echo $catOrder = $product->categoryOrder @endphp--}}
    <div class="item col-sm-6 col-lg-3">
        <div class="thumbnail card">
            @if($product->product_type == 1)
                <div class="img-event">
                    @if(file_exists(public_path($product->featured_image)) && $product->featured_image!='')
                        <a href="{{ URL::to('shop').'/'.$product->slug }}"><img
                                    alt="{{ $product->name }}"
                                    src="{{URL::asset($product->featured_image)}}"
                                    class="img-fluid"></a>
                    @elseif(count($product->productImages) > 0)
                        @foreach ($product->productImages as $image)
                            <a href="{{ URL::to('shop').'/'.$product->slug }}"><img
                                        alt="{{ $product->name }}" src="{{ asset($image->image) }}"
                                        class="img-fluid"></a>
                            @break;
                        @endforeach
                    @else
                        <a href="{{ URL::to('shop').'/'.$product->slug }}"><img
                                    alt="{{ $product->name }}"
                                    src="{{ asset('assets/uploads/no_img.gif') }}"
                                    class="img-fluid"></a>
                    @endif
                </div>
            @else
                @foreach($product->productOptions as $option)
                    @if($option->gallery)
                        @php $gallery = explode(", ", $option->gallery->gallery_images) @endphp
                        <div id="carouselExampleIndicators{{$productKey}}" class="carousel slide" data-ride="carousel">
                            @if(count($gallery) > 1 )
                                <ol class="carousel-indicators">
                                    @foreach($gallery as $key => $image)
                                        <li data-target="#carouselExampleIndicators{{ $productKey }}" data-slide-to="{{$key}}" class="@if($key == 0) active @else @endif"></li>
                                    @endforeach
                                </ol>
                            @endif
                            <div class="carousel-inner">
                                @foreach($gallery as $key => $image)
                                    <div class="carousel-item @if($key == 0) active @else @endif">
                                        <a href="{{ URL::to('shop').'/'.$product->slug }}">
                                            <img class="d-block w-100" alt="{{ $product->name }}" src="{{ URL::asset($image) }}" width="100%">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ URL::to('shop').'/'.$product->slug }}">
                            <img alt="{{ $product->name }}" src="{{ asset('assets/uploads/no_img.gif') }}" class="img-fluid">
                        </a>
                    @endif
                    @break
                @endforeach
            @endif
            <div class="caption card-body">
                @php
                    $reviews =  $product->productReviews;
                @endphp
                @if(count($reviews) > 0)
                    <div class="rating">
                        @php $sum = 0; @endphp
                        @foreach ($reviews as $key=>$review)
                            @php $sum = $sum + $review['stars'] @endphp
                        @endforeach
                        @php $total = $sum/($key+1);
                                        $n = $total;
                        @endphp
                        @if($total > 0)
                            @for($i=1; $i<= 5; $i++)
                                <i class="@if($total >= $i) fas fa-star @else @if($i-$total <= 0.5)fas fa-star-half-alt @else far fa-star @endif @endif "></i>
                            @endfor
                        @endif
                    </div>
                @else
                    <div class="rating">
                        @for($i=1; $i<= 5; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                @endif
                <h6>
                    @if($product->product_type == 1)
                        @if($product->regular_price > 0 && $product->sale_price > 0)
                            <p class="c-price c-font-16 c-font-slim">
                                <i class="fas fa-rupee-sign"></i><span
                                        class="c-font-16 c-font-red">{{ $product->sale_price }} &nbsp;</span>
                                <i class="fas fa-rupee-sign"></i>
                                <del class="c-font-16 c-font-line-through c-font-red">{{ $product->regular_price }}</del>
                            </p>
                        @else
                            <p class="c-price c-font-16 c-font-slim">
                                <i class="fas fa-rupee-sign"></i>
                                <span class="c-font-16 c-font-red">
                                                    {{ $product->regular_price }}
                                                </span>
                            </p>
                        @endif
                    @else
                        @php
                            $options = $product->productOptions;
                            foreach ($options as $option){
                                echo '<i class="fas fa-rupee-sign"></i> '. $option['option_val'];
                                break;
                            }
                        @endphp
                    @endif
                </h6>
                <a href="{{ URL::to('shop').'/'.$product->slug }}">
                    <h4 class="group card-title inner list-group-item-heading">{{ $product->name }}</h4>
                </a>

                <a href="{{ URL::to('shop').'/'.$product->slug }}" class="btn btn-green ">Book
                    Now</a>
                <button class="lead wishlistadd-button" data-id="{{ $product->id }}"><i
                            class="fas fa-heart"></i></button>

            </div>
        </div>
    </div>
    {{--    @endif--}}
@endforeach