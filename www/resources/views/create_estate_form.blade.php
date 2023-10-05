<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{--   <link rel="stylesheet" type="text/css" href="{{asset('public/css/estate_form.css')}}">--}}
    <link rel="stylesheet" href="https://1ab5-79-136-237-88.ngrok-free.app/public/css/estate_form.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <title>Document</title>

    <style>
        body {
            color: var(--tg-theme-text-color);
            background: var(--tg-theme-bg-color);
        }

        button {
            color: var(--tg-theme-button-color);
            background: var(--tg-theme-button-text-color);

        }
    </style>

</head>
<body>
<div class="container mt-5">
    <!-- Success message -->
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif
    <form method="post" action="{{ route('estate.create') }}">
        <!-- CROSS Site Request Forgery Protection -->
        @csrf
        <div class="form-group">
            <p>Включено в стоимость</p>
            @foreach($includes as $include)
                <label>
                    <input type="checkbox" name="include_ids[]" value="{{$include->id}}">
                    {{$include->title}}
                </label>
            @endforeach
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="email">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" name="phone" id="phone">
        </div>
        <div class="form-group">
            <label>Subject</label>
            <input type="text" class="form-control" name="subject" id="subject">
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" name="message" id="message" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-dark btn-block"> Create</button>
    </form>
</div>
</body>
</html>
