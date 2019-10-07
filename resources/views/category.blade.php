@extends('layouts.front')

@section('content')

<section class="header cat">
  @if($category->slug=='category_one') <h1 style="margin:10px">{{ __('category_one') }}</h1> @endif
  @if($category->slug=='category_two') <h1 style="margin:10px">{{ __('category_two') }}</h1> @endif
  @if($category->slug=='category_three') <h1 style="margin:10px">{{ __('category_three') }}</h1> @endif
</section>

<div class="header-img">
	<div class="gradient"></div>
	<amp-img class="cover" layout="fill" src="/assets/banner_{{ $category->slug }}.jpg"></amp-img>
</div>


<div class="poss-amp-carousel__lg">
@php ($i = 0)
<header class="xs-hide large-screen">
  <amp-carousel height="819" layout="fixed-height" type="carousel" autoplay="" loop="">
	@for ($k = 0; $k < 5; $k++)
    @if ($videos->count()>=($i+1))
    <a style="text-decoration:none;" href="{{ route('videoplayer', ['locale'=>Lang::getLocale(),'slug'=> $videos[$i]->slug]) }}" class="btn">
    <article class="slide">
      <amp-img src="{{$videos[$i]->cover}}" width="819" height="819" layout="fill" class="cover"></amp-img>
      <div style="position:absolute;z-index:2;width:90%">
        <amp-timeago 
            style="font-size:25px;font-weight:bold;margin:10px;color:#ffff00" 
            layout="fixed" 
            width="300" 
            height="25"
            datetime="{{$videos[$i]->ISODate}}"
            locale="{{Lang::getLocale()}}">{{$videos[$i]->ISODate}}
        </amp-timeago>
        <h2 style="color:white;margin:10px;font-size:25px;font-weight:bold;">{{$videos[$i]->title}}</h2>
    </div>
</article>
</a>
    @endif
    @php ($i++)
    @endfor
  </amp-carousel>
</header>
</div>


<div class="poss-amp-carousel__sm"> 

    @php ($i = 0)
    <header class="xs-hide large-screen">
    <amp-carousel height="497" layout="fixed-height" type="carousel" autoplay="" loop="" class="mobile">
        @for ($k = 0; $k < 5; $k++)
        @if ($videos->count()>=($i+1))
        <a href="{{ route('videoplayer', ['locale'=>Lang::getLocale(),'slug'=> $videos[$i]->slug]) }}">
        <article class="slide" style="width:325px;height:497px;">
            <amp-img src="{{$videos[$i]->cover}}" width="300" layout="fill" class="fixed"></amp-img>
            <div style="position:absolute;z-index:2;width:90%">
                <amp-timeago 
                    style="font-size:25px;font-weight:bold;margin:10px;color:#ffff00" 
                    layout="fixed" 
                    width="300" 
                    height="25"
                    datetime="{{$videos[$i]->ISODate}}"
                    locale="{{Lang::getLocale()}}">{{$videos[$i]->ISODate}}
                </amp-timeago>
                <h2 style="color:white;margin:10px;font-size:25px;font-weight:bold;">{{$videos[$i]->title}}</h2>
            </div>
        </article>
        </a>
        @endif
        @php ($i++)
        @endfor
    </amp-carousel>
    </header>

</div>

<h2 class="section">
  {{__('videos')}}
</h2>

@endsection
