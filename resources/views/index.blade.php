<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        @vite('resources/js/app.js')
    </head>
    <body class="antialiased">
        <div id="app">
            <Videos></Videos>
        </div>

        <script>
            const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster : 'eu'});
                        
            var channel = pusher.subscribe('public');
            channel.bind('video', function(data) {                
                alert(data.message);
            });
        </script>
    </body>
</html>
