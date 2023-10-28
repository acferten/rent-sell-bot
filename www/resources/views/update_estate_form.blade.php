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
    <script src="{{env('NGROK_SERVER')}}/js/updateFirstStepEstate.js" defer></script>
    <title>Обновление данных объекта</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="page-title">Обновление данных объекта</h1>
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
            <input type="number" class="form-control" id="price" name="price" placeholder="5000" min="0"
                   value="{{$estate->price}}">
            <div class="invalid-field" id="price-error"></div>
        </div>

        <div class="form-group d-none" id="period-container">
            <label class="form-group__title" for="period">Период аренды</label>
            <div class="type_announcement">
                @foreach($price_periods as $price_period)
                    <div class="type_announcement__item">
                        <input type="radio" name="period" value="{{$price_period->value}}"
                               id="{{$price_period->value}}"
                               @if($price_period->value == $estate_rent->period)
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
                   id="period_price" value="{{$estate_rent->price}}">
            <div id="period_price-error"></div>
        </div>

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
            <label class="form-group__title" for="bedrooms">Количество спален</label>
            <select id="bedrooms" name="bedrooms" class="form-select form-control" aria-label="Default select example"
                    required>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}" @if($estate->bedrooms == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="bedrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="bathrooms">Количество ванн</label>
            <select id="bathrooms" name="bathrooms" class="form-select form-control" aria-label="Default select example"
                    required>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{$i}}" @if($estate->bathrooms == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="bathrooms-error"></div>
        </div>
        <div class="form-group">
            <label class="form-group__title" for="conditioners">Количество кондиционеров</label>
            <select id="conditioners" name="conditioners" class="form-select form-control"
                    aria-label="Default select example" required>
                @for($i = 0; $i <= 10; $i++)
                    <option value="{{$i}}" @if($estate->conditioners == $i) selected @endif>{{$i}}</option>
                @endfor
            </select>
            <div class="invalid-field" id="conditioners-error"></div>
        </div>


        <div class="form-group">
            <label class="form-group__title" for="main_photo">Главная фотография</label>
            <div class="form-outline">
                <input type="file" id="main_photo" accept="image/jpg, image/jpeg, image/png, image/tif,
  image/tiff, .tif" name="main_photo" class="form-control"/>
            </div>
            <div class="invalid-field" id="main-photo-error"></div>
            <div id="collage-main">
                <p class="collage__title">Выбранная ранее</p>
                <div class="collage__images">
                    <img class="collage__image" src="/photos/{{$estate_main_photo}}" alt="estate-photo">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-group__title" for="extra_photos">Дополнительные фотографии</label>
            <div class="form-outline">
                <input type="file" id="extra_photos" accept="image/jpg, image/jpeg, image/png, image/tif,
  image/tiff, .tif" name="extra_photos[]" class="form-control" multiple/>
            </div>
            <div class="invalid-field" id="photos-error"></div>
            <div id="collage-extra">
                <p class="collage__title">Выбранные ранее</p>
                <div class="collage__images">
                    @foreach($estate_extra_photos as $estate_extra_photo)
                        <img class="collage__image" src="/photos/{{$estate_extra_photo}}" alt="estate-photo">
                    @endforeach
                </div>
            </div>
        </div>

        {{--        TODO: Добавить вывод выбранного ранее видео --}}
        <div class="form-group">
            <label class="form-group__title" for="video">Видео объекта (не обязательно)</label>
            <div class="form-outline">
                <input type="file" id="video" accept="video/mp4,video/x-m4v,video/*" name="video"
                       class="form-control"/>
            </div>
            <div class="invalid-field" id="video-error"></div>
            @if($estate->video)
                <div>
                    <p class="collage__title">Выбранные ранее</p>
                    <video>
                        <source src="/photos/{{$estate->video}}">
                    </video>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-group__title">Включено в стоимость</label>
            <div class="estate_includes">
                @foreach($includes as $include)
                    <div class="estate_includes__item">
                        <input type="checkbox" name="include_ids[]" value="{{$include->id}}"
                               id="{{$include->title}}-{{$include->id}}"
                               @if($estate_includes->contains("$include->title")) checked @endif
                        />
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
                      placeholder="Подробное описание вашего объекта">{{$estate->description}}</textarea>
            <div class="invalid-field" id="description-error"></div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" id="btn-submit" class="btn">Сохранить</button>
        </div>
    </form>
</div>
</body>
</html>


