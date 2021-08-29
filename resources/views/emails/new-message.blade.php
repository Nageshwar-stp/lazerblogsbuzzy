<html>
<head>
    <style>
        p {
            font-size: 16px;
            color: #000;
        }
    </style>
</head>
<body>
<p>
    {!! trans('v4.new_message_received_for') !!} <a href="{{$link}}" target="_blank">{{$subject}}</a>
</p>
<p>
    <b>{{ trans('v4.message_from')}} {{$from}}</b><br/>
    <b>{{ trans('v4.message_body')}}</b><br/>

    {!! nl2br($body) !!}
</p>
</body>
</html>
