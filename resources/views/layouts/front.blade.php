<!doctype html>

<html ⚡="">
    <meta charset="utf-8">
    <title>POSS Front Office</title>
    <meta name="description" content="{{__('description')}}">
    <meta name="robots" content="noindex, nofollow">
  	<link rel="canonical" href="{{ url()->full() }}">
  	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
  	<script async="" src="https://cdn.ampproject.org/v0.js"></script>
	<script custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js" async=""></script>
	<script custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js" async=""></script>
	<script async custom-element="amp-user-notification" src="https://cdn.ampproject.org/v0/amp-user-notification-0.1.js"></script>
	<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
	<script async custom-element="amp-list" src="https://cdn.ampproject.org/v0/amp-list-0.1.js"></script>
	<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
	<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
	<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script>
	
	@if ($css=="index")
	<script async custom-element="amp-timeago" src="https://cdn.ampproject.org/v0/amp-timeago-0.1.js"></script>
	@endif
	
	<script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
	<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>

	<style amp-boilerplate="">body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate="">body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

	<style amp-custom="">
		



		@includeWhen($css=="index", "css.index")
	</style>

<head>


</head>

<body on="tap:AMP.setState({ hideLanguageOptions: true })" role tabindex>

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

	<amp-user-notification id="cookie-notification"
	  layout="nodisplay">
	  <div>
		{{__('notification')}}
	  </div>
	  <button on="tap:cookie-notification.dismiss">{{__('accept')}}</button>
	</amp-user-notification>

	<!-- Navbar -->
	<header class="ampstart-headerbar fixed flex justify-start items-center top-0 right-0 left-0 pl2 pr2 ">

		<a  style="text-decoration:none;font-size:25px;color:white;display:inline-block;margin:10px;"
			href="{{'/'.Lang::getLocale()}}" @if(Route::current()->getName()!='home') rel="nofollow" @endif>
			Home
		</a>

		<div role="button" aria-label="open menu" on="tap:menu" tabindex="0" style="font-size:25px;color:white;display:inline-block;margin:10px;">
			{{__('menu')}}
		</div>

		@if(Route::current()->getName()!='category' && Route::current()->getName()!='category-videos' && Route::current()->getName()!='videosperpage' && Route::current()->getName()!='category-videospages')
			<button 
				on="tap:AMP.setState({ hideLanguageOptions: false })" 
				style="width:60px;position:absolute;top:10px;right:130px;background-color:transparent;border-style:none;text-align:start"  
				class="select__lg--display ampstart-navbar-trigger"
			>
				@if(Lang::getLocale()=='fr') <a  style="padding-left:10px;font-size:25px;color:white;text-decoration:none;display:inline-block;">fr<span class="caret"></span></a> @endif
				@if(Lang::getLocale()=='en') <a  style="padding-left:10px;font-size:25px;color:white;text-decoration:none;display:inline-block;">en<span class="caret"></span></a> @endif
				@if(Lang::getLocale()=='es') <a  style="padding-left:10px;font-size:25px;color:white;text-decoration:none;display:inline-block;">es<span class="caret"></span></a> @endif
				<div hidden [hidden]="hideLanguageOptions">
					@if(Lang::getLocale()!='fr')<div class="btn__anchor"><a style="text-decoration: none;color:white;" href="{!! route(Route::current()->getName(), ['locale'=>'fr']) !!}">fr</a></div>@endif
					@if(Lang::getLocale()!='en')<div class="btn__anchor"><a style="text-decoration: none;color:white;" href="{!! route(Route::current()->getName(), ['locale'=>'en']) !!}">en</a></div>@endif
					@if(Lang::getLocale()!='es')<div class="btn__anchor"><a style="text-decoration: none;color:white;" href="{!! route(Route::current()->getName(), ['locale'=>'es']) !!}">es</a></div>@endif
				</div>
			</button>
		@endif

	</header>

	<!-- Sidebar -->
	<amp-lightbox id="menu" layout="nodisplay" scrollable>
		
		<div role="button" aria-label="close menu" on="tap:menu.close" tabindex="0" class="close">
			<span>{{ __('close') }}</span>
		</div>
	
		<nav style="margin-top:100px;">
			<h1 style="font-size:25px;margin:10px;" class="md-hide lg-hide">Catégories</h1>
			<ul style="list-style: none;">
				<a style="text-decoration: none;display: inline;font-size: 25px;color:white;" href="{{'/category_one/'. Lang::getLocale() }}" @if(Route::current()->getName()!='home') rel="nofollow" @endif><li><span>{!! __('category_one') !!}</span></li>
				<a style="text-decoration: none;display: inline;font-size: 25px;color:white;" href="{{'/category_two/'. Lang::getLocale() }}" @if(Route::current()->getName()!='home') rel="nofollow" @endif><li><span>{!! __('category_two') !!}</span></li></a>
				<a style="text-decoration: none;display: inline;font-size: 25px;color:white;" href="{{'/category_three/'. Lang::getLocale() }}" @if(Route::current()->getName()!='home') rel="nofollow" @endif><li><span>{!! __('category_three') !!}</span></li></a>
			</ul>
		</nav>

	</amp-lightbox>
	<!-- End Sidebar -->
	<!-- End Navbar -->

	<main style="margin-top:50px;">
		@yield('content')
	</main>

</body>