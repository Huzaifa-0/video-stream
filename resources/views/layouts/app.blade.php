<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://vjs.zencdn.net/7.21.1/video-js.css" rel="stylesheet" />

<!-- Fonts -->
<link rel="dns-prefetch" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

<!-- Styles -->
{{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.3/plyr.css" />

    @vite(['resources/js/app.js'])
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('uploader') }}">{{ __('Uploader') }}</a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
<script src="https://cdn.plyr.io/3.7.3/plyr.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        var video = document.getElementById('my-video')
        var source = "/storage/1HuGcjVdyJY9P8Cr.m3u8"
        const defaultOptions = {}
        if(Hls.isSupported()){
            const hls = new Hls()
            hls.loadSource(source)
            hls.on(Hls.Events.MANIFEST_PARSED, function (event, data){
                const availableQualities = hls.levels.map((l)=> l.height);
                defaultOptions.controls = [
                    'play-large', // The large play button in the center
                    // 'restart', // Restart playback
                    'rewind', // Rewind by the seek time (default 10 seconds)
                    'play', // Play/pause playback
                    'fast-forward', // Fast forward by the seek time (default 10 seconds)
                    'progress', // The progress bar and scrubber for playback and buffering
                    'current-time', // The current time of playback
                    'duration', // The full duration of the media
                    'mute', // Toggle mute
                    'volume', // Volume control
                    'captions', // Toggle captions
                    'settings', // Settings menu
                    'pip', // Picture-in-picture (currently Safari only)
                    'airplay', // Airplay (currently Safari only)
                    // 'download', // Show a download button with a link to either the current source or a custom URL you specify in your options
                    'fullscreen', // Toggle fullscreen
                ];
                defaultOptions.quality = {
                    default: availableQualities[0],
                    options: availableQualities,
                    forced: true,
                    onChange: (e) => updateQuality(e)
                }
                new Plyr(video, defaultOptions)
            });
            hls.attachMedia(video);
            window.hls = hls
        }
        function updateQuality(e){
            window.hls.levels.forEach((level, levelIndex)=>{
                if(level.height === e){
                    window.hls.currentLevelIndex  = levelIndex;
                }
            })
        }
    })
</script>
{{--<script src="https://vjs.zencdn.net/7.21.1/video.min.js"></script>--}}
{{--<script src="C://xampp//htdocs//vide-streaming//node_modules//videojs-contrib-quality-levels//dist//videojs-contrib-quality-levels.min.js"></script>--}}
{{--<script src="C://xampp/htdocs//vide-streaming//node_modules//videojs-hls-quality-selector//dist//videojs-hls-quality-selector.min.js"></script>--}}
{{--<script>--}}
{{--    var player = videojs('my-video');--}}

{{--    player.hlsQualitySelector();--}}
{{--</script>--}}

</body>
</html>
