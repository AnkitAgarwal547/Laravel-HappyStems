<div class="blog-search-box">
	<form action="{{ url('blogs/search') }}" method="post">
		{{ csrf_field() }}
		<input type="search" id="search" placeholder="Search..." name="s" value="" required>
		<button type="submit" class="icon" style="position: absolute;top: 0px;right: 15px;"><i class="fal fa-search"></i></button>
	</form>
</div>
<div class="clearfix"></div>

@php $articlesCategories = articles_categories(); @endphp
@if(!empty($articlesCategories))
<div class="blog-sidebar-widget">
	<div class="widget-title">Categories</div>
	<div class="widget-blog-scroll">
		<ul class="widget-blog-links">
			@foreach($articlesCategories as $acat)
				<li><a href="{{ url('blog/category').'/'.$acat->slug }}">{{ $acat->name }}</a></li>
			@endforeach
		</ul>
	</div>
</div>
@endif

@php $articles = latestArticles(); @endphp
@if(!empty($articles))
<div class="blog-sidebar-widget">
	<div class="widget-title">Latest Posts</div>
	<div class="widget-blog-scroll">
		<ul class="blog-posts">
			@foreach($articles as $article)
				<li class="clearfix">
					<a href="{{ url('blog').'/'.$article->slug }}">
						<img src="@if (file_exists(public_path($article->featured_image)) && $article->featured_image!=''){{URL::asset($article->featured_image) }} @else {{ asset('assets/uploads/no_img.gif') }} @endif" alt="{{ $article->name }}" width="60">
						<h5>{{ $article->title }}</h5>
					</a>
					<p class="meta">{{ date('j-m-Y',strtotime($article->created_at)) }}</p>
				</li>
			@endforeach
		</ul>
	</div>
</div>
@endif

@php $tags = blog_tags(); @endphp
@if(!empty($tags))
	<div class="blog-sidebar-widget">
		<div class="widget-title">Popular Tags</div>
		<div class="widget-blog-scroll">
			<ul class="tags">
				@foreach($tags as $key=>$tag)
					<li><a href="{{ url('tag').'/'.$tag->slug }}" class="@php echo randomChar(); @endphp" >{{ $tag->name }}</a></li>
				@endforeach
			</ul>
		</div>
	</div>
@endif