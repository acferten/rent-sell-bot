<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="{{asset('css/estate_form.css')}}">
    {{--    <link rel="stylesheet" href="https://a625-79-136-237-88.ngrok-free.app/public/css/estate_form.css">--}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css"/>
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"
            defer></script>
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

        .form-label {
            font-size: 0.85rem;
        }

        .form-outline .form-control ~ .form-label {
            padding-top: 0.5rem;
        }
    </style>

</head>
<body>
<div class="container mt-5">
    <div>{{ session('status') }}</div>
    <form method="post" action="{{ route('estate.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <p>Сделка</p>
            <div class="btn-group">
                @foreach($deal_types as $deal_type)
                    <input type="radio" class="btn-check" name="deal_type" value="{{$deal_type->value}}"
                           id="{{$deal_type->value}}" autocomplete="off" checked/>
                    <label class="btn btn-secondary" for="{{$deal_type->value}}">{{$deal_type->value}}</label>
                @endforeach
            </div>
            @error('deal_type')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="number" id="typeNumber" name="price" class="form-control" min="0"/>
                <label class="form-label" for="typeNumber">Цена</label>
            </div>
            @error('price')
            <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
        </div>
        <div class="form-group">
            <select class="form-select" aria-label="Default select example">
                <option selected>Тип недвижимости</option>
                @foreach($estate_types as $estate_type)
                    <option name="house_type_id" value="{{$estate_type->id}}">{{$estate_type->title}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="number" id="typeNumber" name="bedrooms" class="form-control" min="0" max="10" step="1"/>
                <label class="form-label" for="typeNumber">Количество спален</label>
            </div>
            @error('bedrooms')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="number" id="typeNumber" name="bathrooms" class="form-control" min="0" max="10" step="1"/>
                <label class="form-label" for="typeNumber">Количество ванн</label>
            </div>
            @error('bathrooms')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="number" id="typeNumber" name="conditioners" class="form-control" min="0" max="10"
                       step="1"/>
                <label class="form-label" for="typeNumber">Количество кондиционеров</label>
            </div>
            @error('conditioners')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="mb-3">
                <label for="formFile" class="form-label">Фото</label>
                <input class="form-control" name="photo" type="file" id="formFile" multiple>
            </div>
            @error('photo')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="text" id="form12" name="country" class="form-control"/>
                <label class="form-label" for="form12">Страна</label>
            </div>
            @error('country')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="text" id="form12" name="town" class="form-control"/>
                <label class="form-label" for="form12">Город</label>
            </div>
            @error('town')
            <div>{{ $message }}</div>
            @enderror
        </div>


        <div class="form-group">
            <div class="form-outline">
                <input type="text" id="form12" name="district" class="form-control"/>
                <label class="form-label" for="form12">Район</label>
            </div>
            @error('district')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-outline">
                <input type="text" id="form12" name="street" class="form-control"/>
                <label class="form-label" for="form12">Улица</label>
            </div>
            @error('street')
            <div>{{ $message }}</div>
            @enderror
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
            @error('description')
            <div>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-dark btn-block">Сохранить</button>
    </form>
</div>
</body>
</html>
