@extends('layouts.app')

@section('content')

    <ul class="breadcrumb bg-grey">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li>Archive for category: {{ $articlesCategory->name }}</li>
    </ul>

    <section>
        <div class="clearfix"></div>
        <div class="container">
            <h3 style=" margin-top: 10px; ">Archive for category: {{ $articlesCategory->name }}</h3>
            <div class="row">
                <div class="col-md-9 wow fadeInLeft">
                    <div class="blog-grid row">
                        @if(!empty($articles))
                            @foreach($articles as $index=>$article)
                                <div class="col-lg-6 wow fadeIn" style=" animation-delay: 0.{{$index+5}}s;">
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
                    <nav aria-label="Page navigation example">
                        @include('pagination', ['paginator' => $articles])
                    </nav>
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