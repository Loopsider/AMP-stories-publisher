<!doctype html>

<html ⚡="">

<head>
	<meta charset="utf-8">
	<title>{{$video->title}}</title>
	<meta name="description" content="{{$video->description}}">

	<meta name="robots" content="noindex, nofollow">

	<link rel="canonical" href="{{ url()->full() }}">
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<script async="" src="https://cdn.ampproject.org/v0.js"></script>
	<script custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js" async=""></script>
	<script custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js" async=""></script>
	<script async custom-element="amp-user-notification" src="https://cdn.ampproject.org/v0/amp-user-notification-0.1.js"></script>
	<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
	<script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
	<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
	<script async custom-element="amp-timeago" src="https://cdn.ampproject.org/v0/amp-timeago-0.1.js"></script>

	<script type="application/ld+json">
	{
	  "@context": "https://schema.org",
	  "@type": "VideoObject",
	  "name": "{{$video->title}}",
	  "description": "{{$video->description}}",
	  "thumbnailUrl": "{{$video->cover}}",
	  "uploadDate": "{{$video->ISODate}}",
	  "publisher": {
		"@type": "Organization",
		"name": "Publisher Name",
		"logo": {
		  "@type": "ImageObject",
		  "url": "{{ URL::to('/') }}/assets/logo.png",
		  "width": 210,
		  "height": 210
		}
	  },
	  "contentUrl": "{{$video->video_url}}"
	}
	</script>

	<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

	<style amp-boilerplate="">body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate="">body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

    <style amp-custom="">
        body{
            background-color:black; 
            color:white;
        }
        article.slide{
            display:inline-block;
            width:300px;
            height:300px;
        }

		#menu {
			background:gray;
			width: 100%;
			color: white;
			padding: 0;
			margin: 0
		}

		#menu .close {
			cursor: pointer;
			font-size: 25px;
			position: absolute;
			top: 30px;
			right: 30px
		}

        @media only screen and (min-width: 800px) {
            .poss-amp-carousel__lg {
                display: block;
            }
            .poss-amp-carousel__sm {
                display: none;
            }
        }

        @media only screen and (max-width:800px) {
            .poss-amp-carousel__lg {
                display: none;
            }
            .poss-amp-carousel__sm {
                display: block;
            }
        }

        ::-webkit-scrollbar{width: 0;background: none;}

    </style>
</head>

<body>

	@php
		if($video->video_language=='english'){
			Lang::setLocale('en');
		}else if($video->video_language=='spanish'){
			Lang::setLocale('es');
		}else{
			Lang::setLocale('fr');
		}
	@endphp
	
	<amp-analytics type="gtag" data-credentials="include">
		<script type="application/json">
		{
		  "vars" : {
			"gtag_id": "UA-131939849-1",
			"config" : {
			  "UA-131939849-1": { "groups": "default" }
			}
		  }
		}
		</script>
	</amp-analytics>

	<!-- Navbar -->
	<header class="ampstart-headerbar fixed flex justify-start items-center top-0 right-0 left-0 pl2 pr2 ">

		<a  style="text-decoration:none;font-size:25px;color:white;display:inline-block;margin:10px;"
			href="{{'/'.Lang::getLocale()}}" @if(Route::current()->getName()!='home') rel="nofollow" @endif>
			Home
		</a>

		<div role="button" aria-label="open menu" on="tap:menu" tabindex="0" style="font-size:25px;color:white;display:inline-block;margin:10px;">
			{{__('menu')}}
		</div>

	</header>

	<!-- Sidebar -->
	<amp-lightbox id="menu" layout="nodisplay" scrollable>
		
		<div role="button" aria-label="close menu" on="tap:menu.close" tabindex="0" class="close">
			<span>{{ __('close') }}</span>
		</div>

		<nav style="margin-top:100px;">
			<h1 style="font-size:25px;margin:10px;" class="md-hide lg-hide">Catégories</h1>
			<ul style="list-style: none;">
				<a style="text-decoration: none;display: inline;font-size: 25px;color:white;" href="{{'/environment/'. Lang::getLocale() }}" @if(Route::current()->getName()!='home') rel="nofollow" @endif><li><span>{!! __('category_one') !!}</span></li>
				<a style="text-decoration: none;display: inline;font-size: 25px;color:white;" href="{{'/world/'. Lang::getLocale() }}" @if(Route::current()->getName()!='home') rel="nofollow" @endif><li><span>{!! __('category_two') !!}</span></li></a>
				<a style="text-decoration: none;display: inline;font-size: 25px;color:white;" href="{{'/society/'. Lang::getLocale() }}" @if(Route::current()->getName()!='home') rel="nofollow" @endif><li><span>{!! __('category_three') !!}</span></li></a>
			</ul>
		</nav>

	</amp-lightbox>
	<!-- End Sidebar -->
	<!-- End Navbar -->
    

	<main style="margin-top:0;"> 

        <section class="poss-amp-carousel__lg" style="width:500px;height:500px;margin:auto;">
            
			<amp-video id="large-screen-video" class="hoverzone" height="500"
				poster="{{$video->cover}}"
				layout="fixed-height"
				controls>
				<div fallback>
					<p>Your browser doesn't support HTML5 video.</p>
				</div>
				<!-- <source type="video/webm" src="{{$video->webm}}"> -->
				<!-- <source type="video/mp4" src="{{$video->mp4}}"> -->
				<source type="video/mp4" src="{{$video->video_url}}">
			</amp-video>
			<div id="large-screen-overlay" class="click-to-play-overlay">
				<div class="play-icon" role="button" tabindex="0" on="tap:large-screen-overlay.hide, large-screen-video.play"></div>
			</div>
			<div class="overlay">
				<h1>{{$video->title}}</h1>
				<span class="description">{{$video->description}}</span>
			</div>
            
        </section>

        <section class="poss-amp-carousel__sm" style="width:300px;height:300px;margin:auto;">
            
            <amp-video id="large-screen-video" class="hoverzone" height="500"
                poster="{{$video->cover}}"
                layout="fixed-height"
                controls>
                <div fallback>
                    <p>Your browser doesn't support HTML5 video.</p>
                </div>
                <!-- <source type="video/webm" src="{{$video->webm}}"> -->
                <!-- <source type="video/mp4" src="{{$video->mp4}}"> -->
                <source type="video/mp4" src="{{$video->video_url}}">
            </amp-video>
            <div id="large-screen-overlay" class="click-to-play-overlay">
                <div class="play-icon" role="button" tabindex="0" on="tap:large-screen-overlay.hide, large-screen-video.play"></div>
            </div>
            <div class="overlay">
                <h1>{{$video->title}}</h1>
                <span class="description">{{$video->description}}</span>
            </div>
            
        </section>

		<section class="poss-amp-carousel__lg" style="margin-top:200px;text-align:center;">
			<amp-carousel height="400" layout="fixed-height" type="carousel" autoplay="" loop="">
                
                @php ($i = 0)
                
                @for ($k = 0; $k < 5; $k++)
                
				@if ($suggestedVideos->count()>=($i+1))
					@if($suggestedVideos[$i]->video_language=='english') 
						<a href="{{ route('videoplayer', ['locale'=>'en','slug'=> $suggestedVideos[$i]->slug]) }}" class="slide-video">
					@endif
					@if($suggestedVideos[$i]->video_language=='french') 
						<a href="{{ route('videoplayer', ['locale'=>'fr','slug'=> $suggestedVideos[$i]->slug]) }}" class="slide-video">
					@endif
					@if($suggestedVideos[$i]->video_language=='spanish') 
						<a href="{{ route('videoplayer', ['locale'=>'es','slug'=> $suggestedVideos[$i]->slug]) }}" class="slide-video">
					@endif
                    <article style="display:inline-block;width:300px;height:300px;">
                        <amp-img src="{{$suggestedVideos[$i]->cover}}" width="300" height="300" layout="fixed" >
                            <div style="position:absolute;z-index:5;text-align:start">
                                <amp-timeago 
                                style="font-size:20px;margin:10px;color:#ffff00;"
                                layout="fixed" 
                                width="300" 
                                class="time"
                                height="25"
                                datetime="{{$suggestedVideos[$i]->ISODate}}"
                                locale="{{lang::getLocale()}}">{{$suggestedVideos[$i]->ISODate}}</amp-timeago>
                                <h3 style="font-size:10px;margin:10px;color:white">
                                    {{$suggestedVideos[$i]->title}}
                                </h3>
                            </div>
                        </amp-img>
                    </article>
                    </a>
				@endif
				@php ($i++)
                @endfor
                
			</amp-carousel>
        </section>
        
		<section class="poss-amp-carousel__sm" style="margin-top:500px;text-align:center;">
					@php ($i = 0)
					@for ($k = 0; $k < 5; $k++)
					@if ($suggestedVideos->count()>=($i+1))

                        @if($suggestedVideos[$i]->video_language=='english') 
                            <a href="{{ route('videoplayer', ['locale'=>'en','slug'=> $suggestedVideos[$i]->slug]) }}">
                        @endif
                        @if($suggestedVideos[$i]->video_language=='french') 
                            <a href="{{ route('videoplayer', ['locale'=>'fr','slug'=> $suggestedVideos[$i]->slug]) }}">
                        @endif
                        @if($suggestedVideos[$i]->video_language=='spanish') 
                            <a href="{{ route('videoplayer', ['locale'=>'es','slug'=> $suggestedVideos[$i]->slug]) }}">
                        @endif
                            
                                <article style="display:inline-block;width:300px;height:300px;">
                                    <amp-img src="{{$suggestedVideos[$i]->cover}}" width="400" height="300" layout="responsive" class="fixed">
                                        <div style="position:relative;z-index:10;text-align:start">
                                            <amp-timeago layout="fixed" width="300" class="time"
                                                style="font-size:20px;margin:10px;color:#ffff00;"
                                                height="25"
                                                datetime="{{$suggestedVideos[$i]->ISODate}}"
                                                locale="{{lang::getLocale()}}">{{$suggestedVideos[$i]->ISODate}}
                                            </amp-timeago>
                                            <h2 style="font-size:16px;margin:10px;color:white">{{$suggestedVideos[$i]->title}}</h2>
                                        </div>
                                    </amp-img>
                                </article>
                            
                            </a>
					@endif
					@php ($i++)
					@endfor
			</div>
        </section>
        
	</main>
</body>
