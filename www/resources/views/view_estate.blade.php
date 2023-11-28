<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/viewEstate/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"
            defer></script>
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/globalVariables.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/viewEstate.js" defer></script>
    <title>Просмотр объекта</title>
</head>
<body>
<div class="container mt-3 view-page">
    <h1 class="page-title">{{$estate->title}}</h1>
    <div class="form-group">
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                @for($i = 0; $i < count($estate_photos); $i++)
                    @if($i == 0)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}"
                                class="active" aria-current="true" aria-label="Slide {{$i + 1}}"></button>
                    @else
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}"
                                aria-label="Slide {{$i + 1}}"></button>
                    @endif
                @endfor
            </div>
            <div class="carousel-inner" style="height: 250px">
                @php
                    $counter = 0;
                @endphp
                @foreach($estate_photos as $estate_photo)
                    @php
                        $counter++;
                    @endphp
                    <div class="carousel-item {{ $counter == 1 ? "active" : "" }}">
                        <div class="d-block w-100 carousel-image"
                             style="background-image: url('/photos/{{$estate_photo}}')">
                        </div>
                    </div>
                @endforeach
                <button class="carousel-control-prev" style="background-color: rgba(0, 0, 0, 0.2)" type="button"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Предыдущий</span>
                </button>
                <button class="carousel-control-next" style="background-color: rgba(0, 0, 0, 0.2)" type="button"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Следующая</span>
                </button>
            </div>
        </div>
    </div>
    @if ($estate_video)
        <div class="view-page__group">
            <hr>
            <label class="form-group__title">Видео объекта</label>
            <video class="view-video" src="/photos/{{$estate_video}}" controls preload="metadata"></video>
        </div>
    @endif
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Условия сделки</label>
        <div class="сonditions">
            <div class="entity">
                <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="document-info">
                    <path fill="#6563FF"
                          d="M12,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,12,14Zm.38-2.92A1,1,0,0,0,11.8,11l-.18.06-.18.09-.15.12A1,1,0,0,0,11,12a1,1,0,0,0,.29.71,1,1,0,0,0,.33.21A.84.84,0,0,0,12,13a1,1,0,0,0,.71-.29A1,1,0,0,0,13,12a1,1,0,0,0-.29-.71A1.15,1.15,0,0,0,12.38,11.08ZM20,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19l-.1,0A1.1,1.1,0,0,0,13.06,2H7A3,3,0,0,0,4,5V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V9S20,9,20,8.94ZM14,5.41,16.59,8H15a1,1,0,0,1-1-1ZM18,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V5A1,1,0,0,1,7,4h5V7a3,3,0,0,0,3,3h3Z"></path>
                </svg>
                <div class="entity__information">
                    <span class="entity__title">Тип сделки</span>
                    <span class="entity__description">{{ $estate->deal_type }}</span>
                </div>
            </div>
            @if($estate->price)
                <div class="entity">
                    <span class="entity__icon">💰</span>
                    <div class="entity__information">
                        <span class="entity__title">Цена</span>
                        <span class="entity__description">{{ $estate->price }}</span>
                    </div>
                </div>
            @else
                <div class="entity">
                    <svg class="entity__image" id="clock" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24">
                        <path fill="#6563FF"
                              d="M15.09814,12.63379,13,11.42285V7a1,1,0,0,0-2,0v5a.99985.99985,0,0,0,.5.86621l2.59814,1.5a1.00016,1.00016,0,1,0,1-1.73242ZM12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Z"></path>
                    </svg>
                    <div class="entity__information">
                        <span class="entity__title">Срок аренды</span>
                        <span
                            class="entity__description">{{ $estate_rent[0]->period->value }} IDR @if(isset($estate_rent[1]))
                                / {{$estate_rent[1]->period->value}} IDR
                            @endif</span>
                    </div>
                </div>
                <div class="entity">
                    <span class="entity__icon">💰</span>
                    <div class="entity__information">
                        <span class="entity__title">Цена</span>
                        <span class="entity__description">{{ $estate_rent[0]->price }} @if(isset($estate_rent[1]))
                                / {{$estate_rent[1]->price}}
                            @endif</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="view-page__group">
        <hr>
        <div class="view-page__amenities">
            <div class="entity">
                <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="estate">
                    <path fill="#6563FF"
                          d="M20,8h0L14,2.74a3,3,0,0,0-4,0L4,8a3,3,0,0,0-1,2.26V19a3,3,0,0,0,3,3H18a3,3,0,0,0,3-3V10.25A3,3,0,0,0,20,8ZM14,20H10V15a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H16V15a3,3,0,0,0-3-3H11a3,3,0,0,0-3,3v5H6a1,1,0,0,1-1-1V10.25a1,1,0,0,1,.34-.75l6-5.25a1,1,0,0,1,1.32,0l6,5.25a1,1,0,0,1,.34.75Z"></path>
                </svg>
                <div class="entity__information">
                    <span class="entity__title">Вид недвижимости</span>
                    <span class="entity__description">{{ $estate->type->title }}</span>
                </div>
            </div>
            <div class="entity">
                <span class="entity__icon">🛏</span>
                <div class="entity__information">
                    <span class="entity__title">Спален</span>
                    <span class="entity__description">{{ $estate->bedrooms }}</span>
                </div>
            </div>
            <div class="entity">
                <span class="entity__icon">💨</span>
                <div class="entity__information">
                    <span class="entity__title">Кондиционеров</span>
                    <span class="entity__description">{{ $estate->conditioners }}</span>
                </div>
            </div>
            <div class="entity">
                <span class="entity__icon">🛁</span>
                <div class="entity__information">
                    <span class="entity__title">Ванных комнат</span>
                    <span class="entity__description">{{ $estate->bathrooms }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">🛎 Удобства</label>
        @if(count($estate_amenities))
            <ul>
                @foreach($estate_amenities as $amenity)
                    <li>{{ $amenity }}</li>
                @endforeach
            </ul>
        @else
            <p>Удобства отсутствуют</p>
        @endif
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">🤝 Включено в стоимость аренды</label>
        @if(count($estate_services))
            <ul>
                @foreach($estate_services as $service)
                    <li>{{ $service }}</li>
                @endforeach
            </ul>
        @else
            <p>Дополнительных услуг не предусмотрено</p>
        @endif
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">📍 Локация</label>
        <p>{{$estate->geoposition()}}</p>
        <p>Ссылка на Google maps:<br><a class="link"
                                        href="{{$estate->getGoogleLink()}}">{{$estate->getGoogleLink()}}</a></p>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">ℹ️ Описание</label>
        <p>
            {{ $estate->description  }}
            <br><br>
        </p>
    </div>
    <div class="form-group report-form-wrapper d-none">
        <hr>
        <form action="" id="report-form">
            <label class="form-group__title" for="conditioners">Причина жалобы</label>
            <select id="report_reason" name="report_reason" class="form-select form-control"
                    aria-label="Default select example">
                @foreach($report_reasons as $reason)
                    <option>{{$reason->value}}</option>
                @endforeach
            </select>
            <button type="submit" id="send-report" class="btn">Отправить жалобу</button>
        </form>
        <div class="invalid-field" id="report-error"></div>
        <hr>
    </div>
    <div class="actions main-buttons">
        <div class="col">
            <button id="btn-report" class="btn">Жалоба</button>
        </div>
        <div class="col">
            <a href="https://t.me/Silvery11" id="btn-write" class="btn">Написать</a>
        </div>
    </div>
</div>
</body>
</html>


