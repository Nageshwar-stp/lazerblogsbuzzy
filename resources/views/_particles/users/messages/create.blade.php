@extends("pages.users.userapp")
@section("usercontent")
<h2>{{ trans('v4.create_new_message') }}</h2>
<div class="setting-form">
    {!! Form::open(array('action' => array('UserMessageController@store', $userinfo->username_slug), 'method' => 'POST',
    'enctype' => 'multipart/form-data')) !!}
    <div class="form-group">
        {!! Form::label('recipients', trans('v4.sent_to')) !!}
        {!! Form::text('recipients', old('recipients'), ['class' => '','id' => 'recipients', 'placeholder' =>
        trans('v4.sent_to')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('subject', trans('v4.subject')) !!}
        {!! Form::text('subject', old('subject'), ['class' => 'cd-input','id' => 'subject', 'placeholder' =>
        trans('v4.subject')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('message', trans('v4.message')) !!}
        {!! Form::textarea('message', old('message'), ['id' => 'message', 'placeholder' => trans('v4.message')]) !!}
    </div>

    <div class="clear"></div>

    <div>
        <input class="button button-orange button-full" type="submit" value="{{ trans('v4.send_message') }}">
    </div>
    <br><br>
    {!! Form::close() !!}
</div>

@endsection
@section("footer")
<script src="{{ asset('assets/js/selectize/selectize.min.js') }}"></script>
<script>
    var xhr;
     $('#recipients').selectize({
            plugins: ['restore_on_backspace', 'remove_button'],
            delimiter: ',',
            valueField: 'id',
            labelField: 'username',
            searchField: ['username', 'email'],
            create: false,
            load: function(query, callback) {
                if (!query.length) return callback();

                xhr && xhr.abort();
                xhr = $.ajax({
                    url: '{{action("SearchController@searchUsers")}}',
                    type: 'GET',
                    data: {
                        q: query,
                    },
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(res);
                    }
                });
            },
            onInitialize: function() {
					var self = this;
                    @if($to_user)
						self.addOption({
							id: '{{$to_user->id}}',
							username: '{{$to_user->username}}'
						});
                        self.addItem({{$to_user->id}});
                    @endif
				},
        });
</script>
@endsection
