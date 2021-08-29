@if (isset($thread))
<div class="thread-participants-list">
    @foreach ($thread->participants()->take(5)->get() as $participant)
    @if(!empty($participant->user))
    <a class="thread-user-icon" href="{{ action('UsersController@index', [$participant->user->username_slug]) }}"
        title="{{$participant->user->username}}">
        <img src="{{ makepreview($participant->user->icon, 's', 'members/avatar') }}"
            alt="{{ $participant->user->username }}" class="img-circle">
    </a>
    @endif
    @endforeach
</div>
@endif
