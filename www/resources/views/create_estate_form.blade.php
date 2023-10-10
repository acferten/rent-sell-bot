<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://0b80-176-65-56-39.ngrok-free.app/public/css/bundle.css">
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <script src="https://0b80-176-65-56-39.ngrok-free.app/public/js/script.js" defer></script>
    <title>Размещение объекта</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="page-title">Размещение объекта</h1>

    <div>{{ session('status') }}</div>
    <form method="post" action="{{ route('estate.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="username" name="username" value=""/>
        <input type="hidden" id="user_id" name="user_id" value=""/>
        <input type="hidden" id="first_name" name="first_name" value=""/>
        <input type="hidden" id="last_name" name="last_name" value=""/>

        <div class="form-group">
            <label class="form-group__title">Тип услуги</label>
            <div class="type_announcement">
                @foreach($deal_types as $deal_type)
                    <div class="type_announcement__item">
                        <input type="radio" name="deal_type" value="{{$deal_type->value}}"
                               id="{{$deal_type->value}}"/>
                        <label for="{{$deal_type->value}}">
                            <span class="radio-label">{{$deal_type->value}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            @error('deal_type')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="price">Цена</label>
            <input type="number" class="form-control" id="price" placeholder="5000" min="0">
            @error('price')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <p class="form-group__title">Вид недвижимости</p>
            <div class="estate_types">
                @foreach($estate_types as $estate_type)
                    <div class="estate_types__item">
                        <input type="radio" name="estate_type" value="{{$estate_type->id}}"
                               id="{{$estate_type->id}}"/>
                        <label for="{{$estate_type->id}}">
                            <span class="radio-label">{{$estate_type->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            @error('estate_type')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bedrooms">Количество спален</label>
            <div class="form-outline">
                <input type="number" id="bedrooms" name="bedrooms" class="form-control" min="0" max="10" step="1"
                       placeholder="2"/>
            </div>
            @error('bedrooms')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bathrooms">Количество ванн</label>
            <div class="form-outline">
                <input type="number" id="bathrooms" name="bathrooms" class="form-control" min="0" max="10" step="1"
                       placeholder="1"/>
            </div>
            @error('bathrooms')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="conditioners">Количество кондиционеров</label>
            <div class="form-outline">
                <input type="number" id="conditioners" name="conditioners" class="form-control" min="0" max="10"
                       step="1" placeholder="1"/>
            </div>
            @error('conditioners')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="photo">Фото</label>
            <div class="form-outline">
                <input type="file" id="photo" accept=" image/jpg, image/jpeg, image/png, image/tif,
  image/tiff, .tif" name="photo" class="form-control" multiple/>
            </div>
            @error('photo')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="country">Страна</label>
            <div class="form-outline">
                <input type="text" id="country" name="country" class="form-control" placeholder="Россия"/>
            </div>
            @error('country')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="town">Город</label>
            <div class="form-outline">
                <input type="text" id="town" name="town" class="form-control" placeholder="Москва"/>
            </div>
            @error('town')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="district">Район</label>
            <div class="form-outline">
                <input type="text" id="district" name="district" class="form-control" placeholder="Марьино"/>
            </div>
            @error('district')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title" for="street">Улица</label>
            <div class="form-outline">
                <input type="text" id="street" name="street" class="form-control" placeholder="Иловайская улица"/>
            </div>
            @error('street')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-group__title">Включено в стоимость</label>
            <div class="estate_includes">
                @foreach($includes as $include)
                    <div class="estate_includes__item">
                        <input type="checkbox" name="include_ids[]" value="{{$include->id}}"
                               id="{{$include->title}}-{{$include->id}}"/>
                        <label for="{{$include->title}}-{{$include->id}}">
                            <span class="radio-label">{{$include->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="description">Описание</label>
            <textarea class="form-control" name="description" id="description" rows="3"
                      placeholder="Подробное описание вашего объекта"></textarea>
            @error('description')
            <div class="invalid-field">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn">Сохранить</button>
        </div>
    </form>
</div>
</body>
</html>
