@extends('layouts.app')

@section('content')

    <section class="body_menu_links" data-aos-delay="200" data-aos="fade-in">
        <div class="hscontainer">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/blogs') }}">Blogs</a></li>
                <li>Single Blog Title will be here</li>
            </ul>
        </div>
    </section>


    <section class="hscontainer">
        <div class="blog-detail">
			<div class="row">
				<div class="col-md-9" data-aos-delay="300" data-aos="fade-right">
					<div class="blog-title">{{$articleDetail->title}}</div>
					<div class="blog-info">
						<ul>
							<li><span>{{ date('F j, Y',strtotime($articleDetail->created_at)) }}</span></li>
							<li><span>In:
									@foreach($articleCategoryDetail as $cat)
										<a href="{{ url('blog/category').'/'.$cat->slug }}">{{ $cat->name }}</a>{{ $loop->last ? '' : ',' }}
									@endforeach
							<li><span>By: <a href="#">HappySTEMS</a></span></li>
						</ul>
					</div>
					<div class="blog-image" style="background-image:url('@if (file_exists(public_path($articleDetail->featured_image)) && $articleDetail->featured_image!=''){{URL::asset($articleDetail->featured_image) }} @else {{ asset('assets/uploads/no_img.gif') }} @endif'); background-position: top center;"></div>
                                        <div class="blog-description">
                                            {!! $articleDetail->description !!}
                                        </div>
                                        <div class="blog-task">
                                            @if(!empty($getTagName))
                                                <div class="blog-tags">
                                                    <h4>Tags:</h4>
                                                    <ul>
                                                        @foreach ($getTagName as $tag)
                                                                @if(!empty($tag->name))<li><a href="{{ url('tag').'/'.$tag->slug }}">{{ $tag->name }}</a></li>@endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="blog-share">
                                                <ul>
                                                    <li><a  class="btn btn-fb" href="https://www.facebook.com/sharer/sharer.php?u=#{{ url()->current() }}" target="_blank"><i class="fab fa-facebook-f pr-1"></i> Facebook</a></li>
                                                    <li><a  class="btn btn-tw" href="http://twitter.com/share?text={{ $articleDetail->name }}&url={{ url()->current() }}" target="_blank"><i class="fab fa-twitter pr-1"></i> Twitter</a></li>
                                                    <li><a  class="btn btn-g" href="https://plus.google.com/share?url={{ url()->current() }}" target="_blank"><i class="fab fa-google-plus-g pr-1"></i> Google</a></li>
                                                    <li><a  class="btn btn-p" href="http://pinterest.com/pin/create/link/?url={{ url()->current() }}" target="_blank"><i class="fab fa-pinterest-p pr-1"></i> Pinterest</a></li>
                                                </ul>
                                            </div>
                                            <div class="blog-navigation">
                                                <a href="{{ url('blog').'/'.$previous_post['slug'] }}" class="btn btn-green prev hvr-wobble-horizontal @if(empty($previous_post['slug'])) disabled @endif"><i class="fal fa-arrow-left"></i> Previous Post</a>
                                                <a href="{{ url('blog').'/'.$next_post['slug'] }}" class="btn btn-green next hvr-wobble-horizontal @if(empty($next_post['slug'])) disabled @endif">Next Post <i class="fal fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        @if(!empty($related_posts))
                                            <div class="related-blog-posts">
                                                <div class="divider-title">Related Posts</div>
                                                <div class="row related-blogs-slider">
                                                    @foreach($related_posts as $post)
                                                        @if($articleDetail->id != $post->id)
                                                            <div class="col">
                                                                <div class="blog-post">
                                                                    <a href="{{ url('blog').'/'.$post->slug }}">
                                                                        <div class="blog-image" style="background-image:url('@if (file_exists(public_path($post->featured_image)) && $post->featured_image!=''){{URL::asset($post->featured_image) }} @else {{ asset('assets/uploads/no_img.gif') }} @endif'); background-position: center center;"></div>
                                                                        <div class="blog-title">{{ $post->title }}</div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <div class="blog-comments">
                                            @if(count($articlesComments) > 0)
                                                <div class="divider-title">Comments on this Post</div>
{{--                                                {{ var_dump($articlesComments) }}--}}
                                            @foreach ($articlesComments as $comments)
                                                <div class="media bcomment">
                                                    <img src="{{ config('constants.img_path') }}/avatar.jpg" class="mr-3 img-thumbnail" alt="...">
                                                    <div class="media-body">
                                                        <h5 class="mt-0">{{ $comments->name }}</h5>
                                                        <small>{{ date('M d, Y', strtotime($comments->created_at)) }}</small>
                                                        <p>{{ $comments->comment }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif

                                            <div class="divider-title">Leave a Comment</div>
                                            <small>Your email address will not be published. Required fields are marked *</small>

                                            <form action="{{ url('articles/leave-reply') }}" method="post" id="leave_reply">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="article_id" value="{{ $articleDetail->id }}"/>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="name">Name<span>*</span></label>
                                                            <input name="name" class="form-control" type="text" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email<span>*</span></label>
                                                            <input name="email" class="form-control" type="email" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mobile">Mobile Number</label>
                                                            <input type="number" class="form-control" name="mobile" id="mobile">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="comment">Comment<span>*</span></label>
                                                            <textarea name="message" class="form-control" rows="5" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-green hvr-wobble-horizontal">Post Comments</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                <div class="col-md-3" data-aos-delay="300" data-aos="fade-left">
                    @include('mobile-sidebar')
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <div class="flowerStrip"></div>
</div>
@endsection
@section('scripts')

<script>
	$(document).ready(function () {
 
		var form = $('#leave_reply');
		form.submit(function(e) {
			e.preventDefault();
			$('div.page-loader').show();
			$.ajax({
				url     : form.attr('action'),
				type    : form.attr('method'),
				data    : form.serialize(),
				dataType: 'json',
				success : function ( data )
				{
					$('div.page-loader').hide();
					if(data.errors) {
						$.each(data.errors, function (key, value) {
							//$('.'+key+'-error').html(value);
							$('input[name="'+key+'"],textarea[name="'+key+'"]').closest('div.form-group').addClass('has-error');
						});
                	}
					if(data.success == 1){
						form.prepend('<div class="alert alert-success alert-dismissable"> <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Success!</strong> Your reply has been successfully submitted. After review, this will be publish on website soon.</div>')
						form[0].reset();
					}
				},
				error: function( json )
				{
					$('div.page-loader').hide();
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