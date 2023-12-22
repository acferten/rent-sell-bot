<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/bundle.css">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/pages/UpdateEstateAdmin/style.css">
    <script src="{{env('NGROK_SERVER')}}/js/globalVariables.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/updateAdminEstate.js" defer></script>
    <title>Обновление данных объекта</title>
</head>
<body>
<div class="container mt-3 update-page">
    <h1 class="page-title">Обновление данных объекта</h1>
    <form method="post" enctype="multipart/form-data" id="form">
        @csrf
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

        <div class="form-group @if(!$estate->price) d-none @endif" id="price-container">
            <label class="form-group__title" for="price">Цена</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="5000" min="0"
                   value="{{$estate->price}}">
            <div class="invalid-field" id="price-error"></div>
        </div>

        <div class="form-group @if($estate->price) d-none @endif" id="period-container">
            <label class="form-group__title" for="period">Период аренды</label>
            <p class="form-group__description">Выберите и установите цену исходя из срока аренды: за месяц и/или за
                год.</p>
            <div class="type_announcement">
                @foreach($price_periods as $price_period)
                    <div class="type_announcement__item">
                        <input type="checkbox" name="periods[]" value="{{$price_period->value}}"
                               id="{{__("periods.{$price_period->value}")}}"
                               @foreach($estate_rent as $rent)
                                   @if($price_period->value == $rent->period->value)
                                       checked
                            @endif
                            @endforeach
                        />
                        <label for="{{__("periods.{$price_period->value}")}}">
                            <span class="radio-label">{{$price_period->value}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="invalid-field" id="periods-error"></div>
        </div>

        @foreach($price_periods as $price_period)
            <div class="form-group
            @if($estate_rent->where('period', $price_period)->isEmpty())d-none @endif"
                 id="{{$price_period->name}}_price-container">
                <label class="form-group__title" for="{{__("periods.{$price_period->value}")}}_price">Цена
                    за {{$price_period->value}} аренды</label>
                <input type="number" class="form-control" placeholder="5000" min="10" max="100000000"
                       name="{{__("periods.{$price_period->value}")}}_price"
                       id="{{__("periods.{$price_period->value}")}}_price"
                       @foreach($estate_rent as $rent)
                           @if($price_period->value == $rent->period->value)
                               value="{{$rent->price}}"
                    @endif
                    @endforeach
                >
                <div class="invalid-field" id="{{__("periods.{$price_period->value}")}}_price-error"></div>
            </div>
        @endforeach

        <div class="form-group">
            <label class="form-group__title">Вид недвижимости</label>
            <div class="estate_types">
                @foreach($estate_types as $estate_type)
                    <div class="estate_types__item">
                        <input type="radio" name="house_type_id" value="{{$estate_type->id}}"
                               id="{{$estate_type->id}}"
                               @if($estate_type == $estate_house_type) checked @endif
                        />
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
            <input class="form-control" name="title" id="title" value="{{$estate->title}}"
                   placeholder="Вилла с видом на море в Чангу">
            <div class="invalid-field" id="title-error"></div>
        </div>

        <div class="form-group">
            <label class="form-group__title" for="bedrooms">🛏 Количество спален</label>
            <select id="bedrooms" name="bedrooms" class="form-select form-control" aria-label="Default select example"
            >
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}" @if($estate->bedrooms == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="bedrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bathrooms">🛁 Количество ванных комнат</label>
            <select id="bathrooms" name="bathrooms" class="form-select form-control" aria-label="Default select example"
            >
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}" @if($estate->bathrooms == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="bathrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="conditioners">💨 Количество кондиционеров</label>
            <select id="conditioners" name="conditioners" class="form-select form-control"
                    aria-label="Default select example">
                @for($i = 0; $i <= 10; $i++)
                    <option value="{{$i}}" @if($estate->conditioners == $i) selected @endif>{{$i}}</option>
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
                    <div class="preview-container__photo"
                         style="background-image: url('/photos/{{$estate_main_photo}}');"></div>
                    <label for="main-photo-hidden" class="photo-uploader__add-button">
                        +
                    </label>
                </div>
                <input class="photo-uploader__input" type="file" id="main-photo-hidden">
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
                    @foreach($estate_photos as $photo)
                        <div class="preview-container__photo"
                             style="background-image: url('/photos/{{$photo}}');"></div>
                    @endforeach
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
        {{--            @if($estate->video)--}}
        {{--                <div>--}}
        {{--                    <p class="collage__title">Выбранные ранее</p>--}}
        {{--                    <video class="video" preload="metadata" controls>--}}
        {{--                        <source src="/photos/{{$estate->video}}">--}}
        {{--                    </video>--}}
        {{--                </div>--}}
        {{--            @endif--}}
        {{--        </div>--}}

        <div class="form-group">
            <label class="form-group__title" for="available_date">🗓 С какой даты объект свободен для заселения?</label>
            <div class="form-outline">
                <input type="date" id="available_date" name="available_date" value="{{$check_in_date}}"
                       class="form-control"/>
            </div>
            <div class="invalid-field" id="available_date-error"></div>
        </div>

        <div class="form-group">
            <label class="form-group__title">🛎 Удобства на вашем объекте</label>
            <div class="estate_includes">
                @foreach($amenities as $amenity)
                    <div class="estate_includes__item">
                        <input type="checkbox" name="amenity_ids[]" value="{{$amenity->id}}"
                               id="{{$amenity->title}}-{{$amenity->id}}"
                               @if($estate_amenities->contains("$amenity->title")) checked @endif
                        />
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
                               id="{{$service->title}}-{{$service->id}}"
                               @if($estate_services->contains("$service->title")) checked @endif
                        />
                        <label for="{{$service->title}}-{{$service->id}}">
                            <span class="radio-label">{{$service->title}}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{--        TODO: не подставляется выбранный район, всегда чангу показывает как селектед--}}
        <div class="form-group">
            <label class="form-group__title">📍 Выберите район, где располагается объект</label>
            <div class="estate_districts">
                <select id="custom_district" name="custom_district" class="form-select form-control"
                        aria-label="Default select example">
                    @foreach($custom_districts as $custom_district)
                        <option value="{{$custom_district}}"
                                @if($custom_district->value == $estate_custom_district) selected @endif>{{$custom_district}}</option>
                    @endforeach
                </select>
            </div>
            <div class="invalid-field" id="custom_district-error"></div>
        </div>

        <div class="form-group">
            <label class="form-group__title" for="description">ℹ️ Опишите ваш объект в свободной форме.</label>
            <p class="form-group__description">Напишите, что ещё хорошего есть на вашем объекте. Какие преимущества
                территориального расположения.</p>
            <textarea class="form-control" name="description" id="description" rows="3"
                      placeholder="Подробное описание вашего объекта">{{$estate->description}}</textarea>
            <div class="invalid-field" id="description-error"></div>
        </div>
        <div class="d-grid main-buttons container">
            <button type="submit" id="btn-submit" class="btn">Сохранить</button>
        </div>
    </form>
</div>
</body>
</html>


