<!DOCTYPE html>
<html>
<head>
   <meta name="viewport" content="width=device-width">
   <title>POSS</title>
   <meta name="description" content="POSS: Publisher Open Source Stories">
   <link rel="icon" type="image/png" href="/assets/logo.png">
   <link rel="stylesheet" href="{{asset('css/app.css')}}">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Material+Icons">
   <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/vue-material.min.css">
   <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/theme/default.css">
   <link href="https://unpkg.com/cloudinary-video-player/dist/cld-video-player.min.css" rel="stylesheet">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
   <script src="https://unpkg.com/cloudinary-core/cloudinary-core-shrinkwrap.min.js" type="text/javascript"></script>
   <script src="https://unpkg.com/cloudinary-video-player/dist/cld-video-player.min.js" type="text/javascript"></script>
   <script src="https://media-library.cloudinary.com/global/all.js" type="text/javascript"></script>
   <script async src="https://cdn.ampproject.org/shadow-v0.js"></script>
</head>
<body>
  <div id="app"></div>
  @yield('content')
  @if ($app_url)
      <script type="text/javascript">
          window.app_url = "{{ $app_url }}";
      </script>
  @endif
  <script src="{{asset('js/app.js')}}" type="text/javascript"></script>
</body>
</html>
