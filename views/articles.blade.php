@extends('layouts.app')

@section('content')

        <section class="body_menu_links" data-aos-delay="200" data-aos="fade-in">
            <div class="hscontainer">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Blogs</li>
                </ul>
            </div>
        </section>

        <section class="hscontainer">
            @if(!empty($recent_articles))
                <div class="featured-blogs">
                    <div class="row fblogs-slider">
                        @foreach($recent_articles as $key=>$recent)
                            <div class="col-md-6">
                                <a href="{{ url('blog').'/'.$recent->slug }}">
                                    <div class="fblog-box" style="background:url('@if (file_exists(public_path($recent->featured_image)) && $recent->featured_image!=''){{URL::asset($recent->featured_image) }} @else {{ asset('assets/uploads/no_img.gif') }} @endif') no-repeat; background-size: cover; background-position: top center; ">
                                        <span></span>
                                        <div class="fblog-title">{{ $recent->title }}</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="clearfix"></div>

            <div class="">
                <div class="row">
                    <div class="col-md-9">
                        <div class="blog-grid row">
                        @if(!empty($articles))
                            @foreach($articles as $index=>$article)
                            <div class="col-lg-6" data-aos="fade-in" data-aos-delay="{{ ($index+1)*100 }}">
                                <div class="blog-post">
                                    <div class="blog-image" style="background-image:url('@if (file_exists(public_path($article->featured_image)) && $article->featured_image!=''){{URL::asset($article->featured_image) }} @else {{ asset('assets/uploads/no_img.gif') }} @endif');">
                                    </div>
                                    <div class="blog-box">
                                        <div class="blog-title"><a href="{{ url('blog').'/'.$article->slug }}">{{ $article->title }}</a></div>
                                        <div class="blog-info">
                                            <span>{{ date('F j, Y',strtotime($article->created_at)) }}</span>   |  <span>
                                                In:
                                                <i>
                                                    @php
                                                        $articlesCategories = articles_categories();
                                                        $post_cat = explode(",", $article->categories);
                                                        $comma = count($post_cat)-1;

                                                        foreach($post_cat as $c){
                                                            foreach ($articlesCategories as $key=>$cat){
                                                                if($c == $cat->id){
                                                                    echo ' <a href="'.  url('blog/category').'/'.$cat->slug .'">'. $cat->name . '</a>';
                                                                }
                                                            }
                                                            while($comma-- > 0){
                                                                echo ',';
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                </i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="blog-description">
                                        <p>{!! words($article->description, 10, '....') !!}</p>
                                    </div>
                                    <a href="{{ url('blog').'/'.$article->slug }}" class="btn btn-blog-kr btn-green hvr-wobble-horizontal">Keep Reading</a>
                                </div>
                            </div>
                            @endforeach
                        @endif

                        </div>
                        @if(count($articles) > 12 )
                            <nav aria-label="Page navigation example">
                                @include('pagination', ['paginator' => $articles])
                            </nav>
                        @endif
                    </div>
                    <div class="col-md-3 wow fadeInRight">
                        @include('mobile-sidebar')
                    </div>
                </div>
            </div>
        </section>

        <div class="flowerStrip"></div>
    </div>
@endsection