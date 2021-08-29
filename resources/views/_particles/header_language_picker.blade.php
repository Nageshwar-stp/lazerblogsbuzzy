@if($languages = get_active_languages())
<div class="language-links hor">
    <a class="button button-white" href="javascript:">
        <i class="material-icons">&#xE8E2;</i>
        <b>{{ get_language_list(get_buzzy_locale()) }}</b>
    </a>
    <ul class="sub-nav ">
        @foreach($languages as $key => $lang)
        <li>
            <a href="{{ url('/selectlanguge/'.$key) }}" class="sub-item">{{ trans($lang) }}</a>
        </li>
        @endforeach
    </ul>
</div>
@endif
