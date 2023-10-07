<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/estate_form.css')}}">
    {{--    <link rel="stylesheet" href="https://a625-79-136-237-88.ngrok-free.app/public/css/estate_form.css">--}}
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
        @csrf
        <div class="form-group">
            <label>Сделка</label>
            <p>
                @foreach($deal_types as $deal_type)
                    <input type="radio" name="deal_type" value="{{$deal_type->value}}" id="{{$deal_type->value}}">
                    <label for="{{$deal_type->value}}">{{$deal_type->value}}</label>
                @endforeach
            </p>
        </div>

        <div class="form-group">
            <label for="price">Цена</label>
            <input type="number" class="form-control" name="price" id="price">
        </div>

        <div class="form-group">
            <label>Тип недвижимости</label>
            <p>
                @foreach($estate_types as $estate_type)
                    <input type="radio" name="estate_type" value="{{$estate_type->id}}" id="{{$estate_type->title}}">
                    <label for="{{$estate_type->title}}">{{$estate_type->title}}</label>
                @endforeach
            </p>
        </div>

        <div class="form-group">
            <label for="bedrooms">Количество спален</label>
            <input type="number" class="form-control" name="bedrooms" id="bedrooms">
        </div>

        <div class="form-group">
            <label for="bathrooms">Количество ванных комнат</label>
            <input type="number" class="form-control" name="bathrooms" id="bathrooms">
        </div>

        <div class="form-group">
            <label for="conditioners">Количество кондиционеров</label>
            <input type="number" class="form-control" name="conditioners" id="conditioners">
        </div>

        <div class="form-group">
            <label for="photo">Фото</label>
            <input type="file" name="photo" id="photo" multiple>
        </div>

        <div class="form-group">
            <label for="country">Страна</label>
            <input type="text" class="form-control" name="country" id="country">
        </div>

        <div class="form-group">
            <label for="town">Город</label>
            <input type="text" class="form-control" name="town" id="town">
        </div>

        <div class="form-group">
            <label for="district">Район</label>
            <input type="text" class="form-control" name="district" id="district">
        </div>

        <div class="form-group">
            <label for="street">Улица</label>
            <input type="text" class="form-control" name="street" id="street">
        </div>

        <div class="form-group">
            <label>Включено в стоимость</label>
            @foreach($includes as $include)
                <p>
                    <label>
                        <input type="checkbox" name="include_ids[]" value="{{$include->id}}">
                        {{$include->title}}
                    </label>
                </p>
            @endforeach
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-dark btn-block">Сохранить</button>
    </form>
</div>
</body>
</html>
