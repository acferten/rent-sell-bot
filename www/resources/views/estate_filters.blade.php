<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/filtersEstate/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"
            defer></script>
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/globalVariables.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/applyEstateFilters.js" defer></script>
    <title>Фильтрация объектов</title>
</head>
<body>
<div class="container mt-3">
    <h1 class="page-title">Фильтрация объектов</h1>
    <form method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" id="username" name="username" value=""/>
        <input type="hidden" id="user_id" name="user_id" value=""/>
        <input type="hidden" id="first_name" name="first_name" value=""/>
        <input type="hidden" id="last_name" name="last_name" value=""/>
        <input type="hidden" id="initData" name="initData" value=""/>

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
            <div class="invalid-field" id="deal_type-error"></div>
        </div>

        <div class="form-group d-none" id="period-container">
            <label class="form-group__title" for="period">Период аренды</label>
            <div class="type_announcement">
                @foreach($price_periods as $price_period)
                    <div class="type_announcement__item">
                        <input class="type_announcement__field" type="checkbox" name="periods[]"
                               value="{{$price_period->value}}"
                               id="{{$price_period->value}}"/>
                        <label for="{{$price_period->value}}">
                            <span class="radio-label">{{$price_period->value}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="invalid-field" id="period-error"></div>
        </div>

        <div class="form-group" id="price-container">
            <label class="form-group__title" for="price-start">Цена от</label>
            <input type="number" class="form-control" id="price-start" name="price_start" placeholder="5000" min="0">
        </div>
        <div class="form-group" id="price-container">
            <label class="form-group__title" for="price-end">Цена до</label>
            <input type="number" class="form-control" id="price-end" name="price_end" placeholder="5000" min="0">
        </div>

        <div class="form-group">
            <p class="form-group__title">Вид недвижимости</p>
            <div class="estate_types">
                @foreach($estate_types as $estate_type)
                    <div class="estate_types__item">
                        <input type="checkbox" name="house_type_ids[]" value="{{$estate_type->id}}"
                               id="{{$estate_type->id}}"/>
                        <label for="{{$estate_type->id}}">
                            <span class="radio-label">{{$estate_type->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{--        TODO: УДАЛИТЬ КОГДА НУЖНО БУДЕТ ДОБАВИТЬ ПОИСК ПО РАЗНЫМ СТРАНАМ--}}
        <div class="form-group">
            <label class="form-group__title" for="country">📍 Выберите район</label>
            <select id="custom_district" name="custom_district" class="form-select form-control"
                    aria-label="Default select example">
                <option value="" selected>Выберите район</option>
                @foreach($custom_districts as $custom_district)
                    <option value="{{$custom_district}}">{{$custom_district}}</option>
                @endforeach
            </select>
        </div>

        {{--        TODO: УДАЛИТЬ d-none КОГДА НУЖНО БУДЕТ ДОБАВИТЬ ПОИСК ПО РАЗНЫМ СТРАНАМ--}}
        <div class="form-group d-none">
            <label class="form-group__title" for="country">Страна</label>
            <select id="country" name="country" class="form-select form-control"
                    aria-label="Default select example">
                <option value="" selected>Выберите страну</option>
            </select>
        </div>
        <div class="form-group state-group state-group--hidden">
            <label class="form-group__title" for="state">Регион</label>
            <select id="state" name="state" class="form-select form-control"
                    aria-label="Default select example">
            </select>
        </div>
        <div class="form-group county-group county-group--hidden">
            <label class="form-group__title" for="county">Округ</label>
            <select id="county" name="county" class="form-select form-control"
                    aria-label="Default select example">
            </select>
        </div>
        <div class="form-group town-group town-group--hidden">
            <label class="form-group__title" for="town">Город</label>
            <select id="town" name="town" class="form-select form-control"
                    aria-label="Default select example">
            </select>
        </div>

        <div class="form-group">
            <label class="form-group__title">🛎 Удобства на объекте</label>
            <div class="estate_includes">
                @foreach($amenities as $amenity)
                    <div class="estate_includes__item">
                        <input type="checkbox" name="amenity_ids[]" value="{{$amenity->id}}"
                               id="{{$amenity->title}}-{{$amenity->id}}"/>
                        <label for="{{$amenity->title}}-{{$amenity->id}}">
                            <span class="radio-label">{{$amenity->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label class="form-group__title">🤝 Включено в стоимость аренды</label>
            <div class="estate_includes">
                @foreach($services as $service)
                    <div class="estate_includes__item">
                        <input type="checkbox" name="service_ids[]" value="{{$service->id}}"
                               id="{{$service->title}}-{{$service->id}}"/>
                        <label for="{{$service->title}}-{{$service->id}}">
                            <span class="radio-label">{{$service->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-grid gap-2 main-buttons">
            <button type="submit" id="btn-submit" class="btn">Показать объявления</button>
        </div>
    </form>
</div>
</body>
</html>


