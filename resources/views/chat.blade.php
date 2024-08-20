<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    @vite('resources/js/app.js')
</head>
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>

<body>
    <x-navbar></x-navbar>
    <br>
    <div class="card card-success direct-chat direct-chat-primary">
        <div class="card-header">
            <h3 class="card-title">Direct Chat</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
                    data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-widget="remove"><i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages">
                <!-- Message. Default to the left -->

                @foreach ($tracklist as $msg)
                    @if ($msg['type'] == 'admin')
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">Admin</span>
                            </div>
                            <!-- /.direct-chat-infos -->
                            <!-- /.direct-chat-img -->
                            <div class="direct-chat-text">
                                <p>{{ $msg['msg'] }}</p>
                            </div>
                            <!-- /.direct-chat-text -->
                        </div>
                    @elseif($msg['type'] == 'user')
                        <!-- /.direct-chat-msg -->
                        <!-- Message to the right -->
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right" id="username">{{session('username')}}</span>
                            </div>
                            <!-- /.direct-chat-infos -->
                            <!-- /.direct-chat-img -->
                            <div class="direct-chat-text">
                                <p>{{ $msg['msg'] }}</p>
                            </div>
                            <!-- /.direct-chat-text -->
                        </div>`
                    @endif
                @endforeach
                <!-- /.direct-chat-msg -->
            </div>
            <!--/.direct-chat-messages-->
            <!-- Contacts are loaded here -->
            <!-- /.direct-chat-pane -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <form action="{{route('chat.users.add')}}" method="get">
                <input type="hidden" name="userid" value="{{$user_id}}" id="userid">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control"
                        id="message-user">
                    <span class="input-group-append">
                        <button type="submit" class="btn" id="send-btn-user">Send</button>
                    </span>
                </div>
            </form>
        </div>
        <!-- /.card-footer-->
    </div>
</body>
<script>
    $('#send-btn-user').click(function (event) {
        event.preventDefault();
        $.get("{{route('chat.users.add')}}", {
            userid: $('#userid-user').val(),
            message: $('#message-user').val(),
        }, function (response) {
            $('#message-user').val('');
        })
    });
    
</script>

</html>