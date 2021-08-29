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
        {!! trans('v4.new_post_received') !!} <a href="{{$link}}" target="_blank">{{$title}}</a>
    </p>
    <p>
        {{ trans('v4.posted_by')}} <b>{{$from}}</b><br />
    </p>
</body>

</html>
