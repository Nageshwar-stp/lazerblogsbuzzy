@php($class = $thread->isUnread(Auth::id()) ? 'unreaded' : '')
<tr class="thread {{ $class }}">
    <td class="thread-body">
        <div class="thread-heading">
            <a href="{{ action('UserMessageController@show', [$userinfo->username_slug, $thread->id]) }}">
                {{ $thread->subject }}
            </a>
        </div>
        <p>{{ $thread->latestMessage ? Str::limit($thread->latestMessage->body, 120) : '-' }}</p>
    </td>
    <td class="thread-message-count">
        @if ($message_count = $thread->userUnreadMessagesCount(Auth::id()))
        <span>{{ $message_count }}</span>
        @endif
    </td>
    <td class="thread-date">{{ $thread->latestMessage ? $thread->latestMessage->created_at->diffForHumans() : '' }}</td>
    <td class="thread-participants">
        @include('_particles.users.messages.partials._participants', ['thread' => $thread])
    </td>
    <td class="thread-actions">
        <a href="javacript:void(0);" class="thread-actions-button has-dropdown ripple"
            data-target="thread-dropdown{{ $thread->id }}" data-align="right-bottom">
            <i class="material-icons">more_vert</i>
        </a>
        <div class="thread-dropdown{{ $thread->id }} dropdown-container" style="width: 150px;">
            <ul>
                <li class="dropdown-container__item ripple">
                    <a
                        href="{{ action('UserMessageController@show', [$userinfo->username_slug, $thread->id]) }}">{{ trans('v4.view_messages') }}</a>
                </li>
                @if ($thread->isUnread(Auth::id()))
                <li class="dropdown-container__item ripple">
                    <a
                        href="{{ action('UserMessageController@read', [$userinfo->username_slug, $thread->id]) }}">{{ trans('v4.mark_as_read') }}</a>
                </li>
                @else
                <li class="dropdown-container__item ripple"> <a
                        href="{{ action('UserMessageController@unread', [$userinfo->username_slug, $thread->id]) }}">{{ trans('v4.mark_as_unread') }}</a>
                </li>
                @endif
            </ul>
        </div>
    </td>
</tr>
