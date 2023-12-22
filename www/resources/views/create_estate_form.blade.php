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
    <script src="{{env('NGROK_SERVER')}}/js/globalVariables.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/compressorimages.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/createFirstStepEstate.js" defer></script>
    <title>Размещение объекта</title>
</head>
<body>

<div class="container mt-3">
    <h1 class="page-title" id="title">Размещение объекта</h1>
    <form method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" id="username" name="username" value=""/>
        <input type="hidden" id="user_id" name="user_id" value=""/>
        <input type="hidden" id="first_name" name="first_name" value=""/>
        <input type="hidden" id="last_name" name="last_name" value=""/>
        <input type="hidden" id="initData" name="initData" value=""/>

        <div class="form-group d-none">
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

        <div class="form-group d-none" id="price-container">
            <label class="form-group__title" for="price">Цена (указывать в млн.)</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="500" min="0">
            <div class="invalid-field" id="price-error"></div>
        </div>

        <div class="form-group d-none" id="period-container">
            <label class="form-group__title" for="period">Период аренды</label>
            <p class="form-group__description">Выберите и установите цену исходя из срока аренды: за месяц и/или за
                год.</p>
            <div class="type_announcement">
                @foreach($price_periods as $price_period)
                    <div class="type_announcement__item">
                        <input type="checkbox" name="periods[]" value="{{$price_period->value}}"
                               id="{{__("periods.{$price_period->value}")}}"/>
                        <label for="{{__("periods.{$price_period->value}")}}">
                            <span class="radio-label">{{$price_period->value}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="invalid-field" id="periods-error"></div>
        </div>


        @foreach($price_periods as $price_period)
            <div class="form-group d-none" id="{{__("periods.{$price_period->value}")}}_price-container">
                <label class="form-group__title" for="{{__("periods.{$price_period->value}")}}_price">Цена
                    за {{$price_period->value}} аренды (указывать в млн.)</label>
                <input type="number" class="form-control" placeholder="500" min="0"
                       name="{{__("periods.{$price_period->value}")}}_price"
                       id="{{__("periods.{$price_period->value}")}}_price">
                <div class="invalid-field" id="{{__("periods.{$price_period->value}")}}_price-error"></div>
            </div>
        @endforeach

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
            <label class="form-group__title" for="title">📜 Название</label>
            <p class="form-group__description">Напишите уникальное короткое привлекательное название вашего объекта.</p>
            <input class="form-control" name="title" id="title"
                   placeholder="Вилла с видом на море в Чангу">
            <div class="invalid-field" id="title-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bedrooms">🛏 Количество спален</label>
            <select id="bedrooms" name="bedrooms" class="form-select form-control" aria-label="Default select example">
                <option selected>Выберите количество</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="bedrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bathrooms">🛁 Количество ванных комнат</label>
            <select id="bathrooms" name="bathrooms" class="form-select form-control"
                    aria-label="Default select example">
                <option selected>Выберите количество</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="bathrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="conditioners">💨 Количество кондиционеров</label>
            <select id="conditioners" name="conditioners" class="form-select form-control"
                    aria-label="Default select example">
                <option selected>Выберите количество</option>
                @for($i = 0; $i <= 10; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="conditioners-error"></div>
        </div>

        <div class="form-group">
            <label class="form-group__title">📸 Главная фотография</label>
            <p class="form-group__description">Именно эту фотография клиенты будут видеть первой при просмотре вашего
                объявления.</p>
            <div class="photo-uploader">
                <div class="photo-uploader__selected-photos" id="main-photo-container">
                    <label for="main-photo-hidden" class="photo-uploader__add-button">
                        +
                    </label>
                </div>
                <input class="photo-uploader__input" type="file"
                       id="main-photo-hidden">
                <input class="photo-uploader__input" name="main_photo" id="main-photo" type="file" accept="image/jpg, image/jpeg, image/png, image/tif,
  image/tiff, .tif">
            </div>
            <div class="invalid-field" id="main_photo-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title">🎞 Дополнительные фотографии</label>
            <p class="form-group__description">Добавьте не менее 5 фотографий, показывающих ваш объект с выгодной
                стороны. Рекомендуем добавить: фото снаружи, ливинг рум, кухня, спальня, ванная комната. Это значительно
                увеличит заинтересованность к вашему объекту.</p>
            <div class="photo-uploader">
                <div class="photo-uploader__selected-photos" id="photos-container">
                    <label for="photos-hidden" class="photo-uploader__add-button">
                        +
                    </label>
                </div>
                <input class="photo-uploader__input" type="file" id="photos-hidden" multiple>
                <input class="photo-uploader__input" name="photo[]" id="photos" type="file" multiple accept="image/jpg, image/jpeg, image/png, image/tif,
  image/tiff, .tif">
            </div>
            <div class="invalid-field" id="photo-error"></div>
        </div>


        {{--        <div class="form-group">--}}
        {{--            <label class="form-group__title" for="video">📹 Видеоролик об объекте (необязательный пункт)</label>--}}
        {{--            <p class="form-group__description">По статистике объявления с видеороликом просматривают на 53% больше, чем--}}
        {{--                без видео. Видеоролики желательно добавлять в вертикальном формате.</p>--}}
        {{--            <div class="form-outline">--}}
        {{--                <input type="file" id="video" accept="video/mp4,video/x-m4v,video/*" name="video"--}}
        {{--                       class="form-control"/>--}}
        {{--            </div>--}}
        {{--            <div class="invalid-field" id="video-error"></div>--}}
        {{--        </div>--}}

        <div class="form-group">
            <label class="form-group__title" for="available_date">🗓 С какой даты объект свободен для заселения?</label>
            <div class="form-outline">
                <input type="date" id="available_date" name="available_date" value="{{date("Y-m-d")}}"
                       min="{{date("Y-m-d")}}" class="form-control"/>
            </div>
            <div class="invalid-field" id="available_date-error"></div>
        </div>

        <div class="form-group">
            <label class="form-group__title">🛎 Удобства на вашем объекте</label>
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
            <label class="form-group__title">🤝 Что включено в стоимость аренды?</label>
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

        <div class="form-group">
            <label class="form-group__title">📍 Выберите район, где располагается объект</label>
            <div class="estate_districts">
                <select id="custom_district" name="custom_district" class="form-select form-control"
                        aria-label="Default select example">
                    <option value="" selected>Выберите район</option>
                    @foreach($custom_districts as $custom_district)
                        <option value="{{$custom_district}}">{{$custom_district}}</option>
                    @endforeach
                </select>
            </div>
            <div class="invalid-field" id="custom_district-error"></div>
        </div>

        <div class="form-group">
            <label class="form-group__title" for="description">ℹ️ Описание</label>
            <p class="form-group__description">Напишите, что ещё хорошего есть на вашем объекте. Какие преимущества
                территориального расположения.</p>
            <textarea class="form-control" name="description" id="description" rows="3"
                      placeholder="Подробное описание вашего объекта"></textarea>
            <div class="invalid-field" id="description-error"></div>
        </div>
        <div class="d-grid gap-2 main-buttons">
            <button type="submit" id="btn-submit" class="btn">✅ Сохранить</button>
        </div>
    </form>
</div>
</body>
</html>
