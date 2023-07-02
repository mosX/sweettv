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
    </head>
    <body class="antialiased">
        <div class="chat">
            <div class="top">
                <div></div>
            </div>

            <div class="messages">
                @include('receive',['message'=>'test'])
            </div>

            <div class="bottom">
                <form id="pusher">
                    <input type="text" id="message" placeholder="Enter message..." autocomplete="off">
                    {{csrf_token()}}
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>



<div class="container">
    <script>
        $('document').ready(function(){
            $("form#data").submit(function(event){                
                event.preventDefault;
                
                var formData = new FormData();
                formData.append('_token','{{csrf_token()}}');
                formData.append('video', $('#data input[name=video]')[0].files[0])
                
                
                $.ajax({
                    url:'/videos/save',
                    type: 'POST',
                    data: formData,
                    async: false,
                    success: function (data) {
                        alert(data)
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                return false;
            });
        })
    </script>
    <form id="data" method="post">
        <div class="form-group">
            <input type="file" name="video" value="Bob" />
        </div>        
        <button type="submit">Submit</button>
    </form>
</div>

        Pusher
        <script>
            const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster : 'eu'});
                        
            var channel = pusher.subscribe('public');
            channel.bind('video', function(data) {
                alert(JSON.stringify(data));
            });

            $('document').ready(function(){
                $('form#pusher').submit(function(event){
                    event.preventDefault();

                    $.ajax({
                        url:'/pusher/broadcast',
                        method:'POST',
                        headers:{
                            'X-Socket-Id': pusher.connection.socket_id
                        },
                        data:{
                            _token:'{{csrf_token()}}',
                            message: $("form#pusher #message").val(),
                        }
                    }).done((res)=>{
                        $('.messages > .message').last().after(res);
                        $('form#pusher #message').val('');
                        $(document).scrollTop($(document).height());
                    });
                })
            })
        </script>
    </body>
</html>
