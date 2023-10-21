<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/bundle.css">
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <title>Размещение объекта</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="page-title">Размещение объекта</h1>
    <div id="test">{{ session('status') }}</div>
    <form method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" id="username" name="username" value=""/>
        <input type="hidden" id="user_id" name="user_id" value=""/>
        <input type="hidden" id="first_name" name="first_name" value=""/>
        <input type="hidden" id="last_name" name="last_name" value=""/>
        <input type="hidden" id="initData" name="initData" value=""/>
        <input type="hidden" id="chat_id" name="chat_id" value=""/>

        <div class="form-group">
            <label class="form-group__title">Тип услуги</label>
            <div class="type_announcement">
                @foreach($deal_types as $deal_type)
                    <div class="type_announcement__item">
                        <input type="radio" name="deal_type"
                               value="{{$deal_type->value}}"
                               id="{{$deal_type->value}}"
                               @if($estate->deal_type == $deal_type->value)
                                   checked
                            @endif
                        />
                        <label for="{{$deal_type->value}}">
                            <span class="radio-label">{{$deal_type->value}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="invalid-field" id="deal_type-error"></div>
        </div>

        <div class="form-group d-none" id="price-container">
            <label class="form-group__title" for="price">Цена</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="5000" min="0">
            <div class="invalid-field" id="price-error"></div>
        </div>

        <div class="form-group d-none" id="period-container">
            <label class="form-group__title" for="period">Период аренды</label>
            <div class="type_announcement">
                @foreach($price_periods as $price_period)
                    <div class="type_announcement__item">
                        <input type="radio" name="period" value="{{$price_period->value}}"
                               id="{{$price_period->value}}"
                               @if($estate->price == $price_period->value)
                                   checked
                            @endif
                        />
                        <label for="{{$price_period->value}}">
                            <span class="radio-label">{{$price_period->value}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div id="period-error"></div>
        </div>

        <div class="form-group d-none" id="period_price-container">
            <label class="form-group__title" for="period_price">Цена за весь период</label>
            <input type="number" class="form-control" placeholder="5000" min="10" max="100000000" name="period_price"
                   id="period_price">
            <div id="period_price-error"></div>
        </div>

        <div class="form-group">
            <p class="form-group__title">Вид недвижимости</p>
            <div class="estate_types">
                @foreach($estate_types as $estate_type)
                    <div class="estate_types__item">
                        <input type="radio" name="house_type_id" value="{{$estate_type->id}}"
                               id="{{$estate_type->id}}"/>
                        <label for="{{$estate_type->id}}">
                            <span class="radio-label">{{$estate_type->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="invalid-field" id="house_type_id-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bedrooms">Количество спален</label>
            <div class="form-outline">
                <input type="number" id="bedrooms" name="bedrooms" class="form-control" min="0" max="10" step="1"
                       placeholder="2"/>
            </div>
            <div class="invalid-field" id="bedrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bathrooms">Количество ванн</label>
            <div class="form-outline">
                <input type="number" id="bathrooms" name="bathrooms" class="form-control" min="0" max="10" step="1"
                       placeholder="1"/>
            </div>
            <div class="invalid-field" id="bathrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="conditioners">Количество кондиционеров</label>
            <div class="form-outline">
                <input type="number" id="conditioners" name="conditioners" class="form-control" min="0" max="10"
                       step="1" placeholder="1"/>
            </div>
            <div class="invalid-field" id="conditioners-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="photo">Фото</label>
            <div class="form-outline">
                <input type="file" id="photo" accept="image/jpg, image/jpeg, image/png, image/tif,
  image/tiff, .tif" name="photo[]" class="form-control" multiple/>
            </div>
            <div class="invalid-field" id="photo-error"></div>
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
            <div class="invalid-field" id="description-error"></div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" id="btn-submit" class="btn">Сохранить</button>
        </div>
    </form>
</div>
<script>
    let tg = window.Telegram.WebApp;
    tg.expand();

    document.getElementById('username').value = tg.initDataUnsafe.user.username;
    document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
    document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
    document.getElementById('initData').value = tg.initData;
    document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;
    console.log(tg.initDataUnsafe);

    tg.enableClosingConfirmation();

    let form = document.getElementById('form');

    form.addEventListener('submit', (e) => {
        const elems = [
            'photo-error',
            'description-error',
            'deal_type-error',
            'bathrooms-error',
            'bedrooms-error',
            'conditioners-error',
            'house_type_id-error'
        ];
        elems.forEach((elem) => {
            document.getElementById(elem).innerText = "";
        })

        document.getElementById('btn-submit').disabled = true;

        e.preventDefault();
        const formData = new FormData(e.currentTarget);

        fetch(`https://a811-37-21-168-91.ngrok-free.app/estate/`, {
            headers: {
                Accept: "application/json"
            },
            method: "POST",
            body: formData,

        })
            .then((response) => response.json())
            .then((json) => {
                document.getElementById('btn-submit').disabled = false;
                if (json?.errors?.photo) document.getElementById('photo-error').innerText = json.errors.photo[0];
                if (json?.errors?.description) document.getElementById('description-error').innerText = json.errors.description[0];
                if (json?.errors?.deal_type) document.getElementById('deal_type-error').innerText = json.errors.deal_type[0];
                if (json?.errors?.bathrooms) document.getElementById('bathrooms-error').innerText = json.errors.bathrooms[0];
                if (json?.errors?.bedrooms) document.getElementById('bedrooms-error').innerText = json.errors.bedrooms[0];
                if (json?.errors?.conditioners) document.getElementById('conditioners-error').innerText = json.errors.conditioners[0];
                if (json?.errors?.house_type_id) document.getElementById('house_type_id-error').innerText = json.errors.house_type_id[0];
            })
            .finally();
    })

    document.getElementById('Продажа').addEventListener("click", () => {
        document.getElementById('price-container').classList.remove('d-none');
        document.getElementById('period-container').classList.add('d-none');
        document.getElementById('period_price-container').classList.add('d-none');
    })
    document.getElementById('Аренда').addEventListener("click", () => {
        document.getElementById('price-container').classList.add('d-none');
        document.getElementById('period-container').classList.remove('d-none');
        document.getElementById('period_price-container').classList.remove('d-none');
    })


</script>
</body>
</html>


