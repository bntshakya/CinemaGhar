@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Chat</h1>
@stop

@section('content')
    <!-- Construct the card with style you want. Here we are using card-danger -->
    <!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
    <!-- The contextual class should match the card, so we are using direct-chat-danger -->

    <div class="{{$userSelectedFlag?'card card-success direct-chat direct-chat-primary':'card card-success direct-chat direct-chat-primary direct-chat-contacts-open'}}">
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
                    @if ($msg['type'] === 'admin')
                            <div class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">Admin</span>
                                </div>
                                <!-- /.direct-chat-infos -->
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    <p id="admin-msg">{{ $msg['msg'] }}</p>
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                        @elseif($msg['type'] === 'user')
                            <!-- /.direct-chat-msg -->
                            <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right" id="username" value="{{$username}}">{{$username}}</span>
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
            <div class="direct-chat-contacts">
                <ul class="contacts-list">
                  @foreach ($users as $user)
                    <li>
                        <a href="{{route('chat.selectuser', ['id' => ($user->id)])}}">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    {{$user->username}}
                                </span>
                                <span class="contacts-list-msg">How have you been? I was...</span>
                            </div>
                            <!-- /.contacts-list-info -->
                        </a>
                    </li>
                  @endforeach
                    <!-- End Contact Item -->
                </ul>
                <!-- /.contacts-list -->
            </div>
            <!-- /.direct-chat-pane -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <form action="{{route('chat.add')}}" method="get">
                <input type="hidden" name="userid" value="{{$user_id}}" id="userid">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control" id="message">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="send-btn">Send</button>
                    </span>
                </div>
            </form>
        </div>
        <!-- /.card-footer-->
    </div>
    <!--/.direct-chat -->
@stop
@section('css')
{{-- Add here extra stylesheets --}}
{{--
<link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop
@section('meta_tags')
    @vite('resources/js/app.js')
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
@endsection
@section('js')
    <script>
        $('#send-btn').click(function(event){
            event.preventDefault();
            $.get("{{route('chat.add')}}",{
                userid: $('#userid').val(),
                message: $('#message').val(),
            },function(response){
                $('#message').val('');
            })
        });

        $('#chat-pane-toggle').DirectChat('toggle');
    </script>
@endsection

