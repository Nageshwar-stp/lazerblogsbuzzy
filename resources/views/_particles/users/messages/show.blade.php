@extends("pages.users.userapp")
@section('usercontent')
<div class="user_messages_header">
    <h2><a href="{{ action('UserMessageController@index', [$userinfo->username_slug]) }}">{{ trans('v4.messages') }}</a>
        > {{ $thread->subject }}</h2>
    <div class="user_messages_header_participants">
        @include('_particles.users.messages.partials._participants', ['thread' => $thread])
    </div>
</div>
<div class="user_messages">
    @each('_particles.users.messages.partials.messages', $messages, 'message')
    <div class="message-pagination">
        {!! $messages->links() !!}
    </div>
    <div class="message-reply-form clearfix">
        @include('_particles.users.messages.partials.reply-message-form')
    </div>
</div>
@endsection
