<!doctype html>
<html ⚡>
  <head>
    <meta charset="utf-8">
    <title>{{ $story['title'] }}</title>

    <link rel="canonical" href="{{ $story['slug'] }}">
    @if(isset($story['date']))
    <script type=application/ld+json>{
        "@context": "http://schema.org",
        "@type": "NewsArticle",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ $story['slug'] }}"
        },
        "headline": "{{ $story['title'] }}",
        "description": "{{ $story['title'] }}",
        "image": [
            "{{ $story['poster-portrait-src'] }}"
        ],
        "datePublished": "{{ $story['date'] }}",
        "dateModified": "{{ $story['date'] }}",
        "publisher": {
            "@type": "Organization",
            "name": "Publisher name",
            "logo": {
                "@type": "ImageObject",
		            "url": "{{ URL::to('/') }}/assets/logo.png",
		            "width": 210,
		            "height": 210
            }
        },
        "author": {
            "@type": "Organization",
            "name": "Publisher name",
            "logo": {
                "@type": "ImageObject",
		            "url": "{{ URL::to('/') }}/assets/logo.png",
		            "width": 210,
		            "height": 210
            }
        }
    }</script>
    @endif
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-video"
        src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
    <script async custom-element="amp-story"
        src="https://cdn.ampproject.org/v0/amp-story-1.0.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400" rel="stylesheet">
    <style amp-custom="">
      @if(isset($story['css'])) {{ $story['css'] }} @endif
    </style>
  </head>
  <body>
    <!-- Cover page -->
    <amp-story standalone
        title="{{ $story['title'] }}"
        publisher="{{ $story['publisher'] }}"
        publisher-logo-src="{{ $story['publisher-logo-src'] }}"
        poster-portrait-src="{{ $story['poster-portrait-src'] }}">
        @foreach($story['pages'] as $page)
          @if(isset($page_id))
            @include('story-page', ['page' => $page, 'page_id' => $page_id])
          @else
            @include('story-page', ['page' => $page])
          @endif
        @endforeach
        @if(isset($story['bookend']))
          <amp-story-bookend layout=nodisplay>
            <script type="application/json">
              {!! json_encode($story['bookend']) !!}
            </script>
          </amp-story-bookend>
        @endif
    </amp-story>
  </body>
</html>
