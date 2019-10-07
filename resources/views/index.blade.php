@extends('layouts.front')

@section('content')

  <div class="poss-amp-carousel__lg">
    <header class="xs-hide large-screen">
      @php ($i = 0)
        @if ($videos->count()>=($i+1))
          <h1 style="font-size:25;margin:10px">{{__('videos')}}:</h1>
        @endif
        <amp-carousel height="819" layout="fixed-height" type="carousel" autoplay="" loop="">
          @for ($k = 0; $k < 5; $k++)
            @if ($videos->count()>=($i+1))
            <a style="text-decoration:none;" href="{{ route('videoplayer', ['locale'=>Lang::getLocale(),'slug'=> $videos[$i]->slug]) }}" class="btn">
            <article  class="slide">
              <amp-img src="{{$videos[$i]->cover}}" width="819" height="819" layout="fill" class="cover"></amp-img>
              <div style="position:relative;z-index:2;width:30%">
                <amp-timeago style="font-size:25px;font-weight:bold;margin:10px;color:#ffff00" layout="fixed" width="300" class="time" height="25" datetime="{{$videos[$i]->ISODate}}" locale="{{Lang::getLocale()}}">{{$videos[$i]->ISODate}}</amp-timeago>
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
    <header class="xs-hide large-screen">
      @php ($i = 0)
        @if ($videos->count()>=($i+1))
          <h1 style="font-size:25;margin:10px">{{__('videos')}}:</h1>
        @endif
        <amp-carousel height="497" layout="fixed-height" type="carousel" autoplay="" loop="">
          @for ($k = 0; $k < 5; $k++)
            @if ($videos->count()>=($i+1))
            <a style="text-decoration:none;" href="{{ route('videoplayer', ['locale'=>Lang::getLocale(),'slug'=> $videos[$i]->slug]) }}" class="btn">
            <article class="slide" style="width:325px;height:497px;">
              <amp-img src="{{$videos[$i]->cover}}" width="819" height="819" layout="fill" class="cover"></amp-img>
              <div style="position:absolute;z-index:2;width:90%">
                <amp-timeago style="font-size:25px;font-weight:bold;margin:10px;color:#ffff00" layout="fixed" width="300" class="time" height="25" datetime="{{$videos[$i]->ISODate}}" locale="{{Lang::getLocale()}}">{{$videos[$i]->ISODate}}</amp-timeago>
                <p style="color:white;margin:10px;font-size:10px;font-weight:bold;">
                  {{$videos[$i]->title}}
                </p>
              </div>
            </article>
          </a>
            @endif
            @php ($i++)
          @endfor
        </amp-carousel>
    </header>
  </div>
  
  @if (count($frontStories)>0)

    <h1 style="font-size:25px;margin:10px;">{{ __('stories')}}:</h1>

    <div class="poss-amp-carousel__lg">
      <amp-carousel height="717" layout="fixed-height" type="carousel" autoplay="" loop="" class="story-carousel">
        @foreach ($frontStories as $frontStorie)
          <article class="slide" style="margin-right:12px;overflow: scroll;">
            <a href="{{ "story/".$frontStorie->slug }}" target="_blank">
              <amp-img src="{{ $frontStorie->data->{'poster-portrait-src'} }}" width="431" height="717" style="position:relative;z-index:50">
                <div style="position:relative;z-index:2;width:60%">
                  <amp-timeago style="font-size:25px;font-weight:bold;margin:10px;color:#ffff00" layout="fixed" width="300" class="time" height="25" datetime="{{$frontStorie->ISODate}}" locale="{{Lang::getLocale()}}">
                    {{$frontStorie->ISODate}}
                  </amp-timeago>
                  <h2 style="color:white;margin:10px;font-size:25px;font-weight:bold;">{{$frontStorie->title}}</h2>
                </div>
              </amp-img>
            </a>
          </article>
        @endforeach
      </amp-carousel>
    </div>

    <div class="poss-amp-carousel__sm">
      <amp-carousel height="249" layout="fixed-height" type="carousel" autoplay="" loop="" class="story-carousel">
        @foreach ($frontStories as $frontStorie)
          <article class="slide" style="margin-right:2px">
            <a href="{{ "story/".$frontStorie->slug }}" target="_blank">
              <amp-img src="{{ $frontStorie->data->{'poster-portrait-src'} }}" width="149" height="249" style="position:relative;z-index:50">
                <div style="position:relative;z-index:2;width:90%">
                  <amp-timeago style="font-size:16px;font-weight:bold;margin:10px;color:#ffff00" layout="fixed" width="300" class="time" height="25" datetime="{{$frontStorie->ISODate}}" locale="{{Lang::getLocale()}}">
                    {{$frontStorie->ISODate}}
                  </amp-timeago>
                  <h2 style="color:white;margin:10px;font-size:14px;font-weight:bold;">{{$frontStorie->title}}</h2>
                </div>
              </amp-img>
            </a>
          </article>
        @endforeach
      </amp-carousel>
    </div>

  @endif

@endsection

