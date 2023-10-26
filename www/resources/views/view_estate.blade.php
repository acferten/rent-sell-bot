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
    <script src="{{env('NGROK_SERVER')}}/js/viewEstate.js" defer></script>
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <title>Просмотр объекта</title>
</head>
<body>
<div class="container mt-4 view-page">
    <h1 class="page-title">Просмотр объекта</h1>
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
                    <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                         viewBox="0 0 24 24" id="bill">
                        <path fill="#6563FF"
                              d="M9.5,10.5H12a1,1,0,0,0,0-2H11V8A1,1,0,0,0,9,8v.55a2.5,2.5,0,0,0,.5,4.95h1a.5.5,0,0,1,0,1H8a1,1,0,0,0,0,2H9V17a1,1,0,0,0,2,0v-.55a2.5,2.5,0,0,0-.5-4.95h-1a.5.5,0,0,1,0-1ZM21,12H18V3a1,1,0,0,0-.5-.87,1,1,0,0,0-1,0l-3,1.72-3-1.72a1,1,0,0,0-1,0l-3,1.72-3-1.72a1,1,0,0,0-1,0A1,1,0,0,0,2,3V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V13A1,1,0,0,0,21,12ZM5,20a1,1,0,0,1-1-1V4.73L6,5.87a1.08,1.08,0,0,0,1,0l3-1.72,3,1.72a1.08,1.08,0,0,0,1,0l2-1.14V19a3,3,0,0,0,.18,1Zm15-1a1,1,0,0,1-2,0V14h2Z"></path>
                    </svg>
                    <div class="entity__information">
                        <span class="entity__title">Стоимость</span>
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
                        <span class="entity__description">{{ $estate_rent->period }}</span>
                    </div>
                </div>
                <div class="entity">
                    <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                         viewBox="0 0 24 24" id="bill">
                        <path fill="#6563FF"
                              d="M9.5,10.5H12a1,1,0,0,0,0-2H11V8A1,1,0,0,0,9,8v.55a2.5,2.5,0,0,0,.5,4.95h1a.5.5,0,0,1,0,1H8a1,1,0,0,0,0,2H9V17a1,1,0,0,0,2,0v-.55a2.5,2.5,0,0,0-.5-4.95h-1a.5.5,0,0,1,0-1ZM21,12H18V3a1,1,0,0,0-.5-.87,1,1,0,0,0-1,0l-3,1.72-3-1.72a1,1,0,0,0-1,0l-3,1.72-3-1.72a1,1,0,0,0-1,0A1,1,0,0,0,2,3V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V13A1,1,0,0,0,21,12ZM5,20a1,1,0,0,1-1-1V4.73L6,5.87a1.08,1.08,0,0,0,1,0l3-1.72,3,1.72a1.08,1.08,0,0,0,1,0l2-1.14V19a3,3,0,0,0,.18,1Zm15-1a1,1,0,0,1-2,0V14h2Z"></path>
                    </svg>
                    <div class="entity__information">
                        <span class="entity__title">Стоимость</span>
                        <span class="entity__description">{{ $estate_rent->price }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Об объекте</label>
        <div class="view-page__amenities">
            <div class="entity">
                <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="estate">
                    <path fill="#6563FF"
                          d="M20,8h0L14,2.74a3,3,0,0,0-4,0L4,8a3,3,0,0,0-1,2.26V19a3,3,0,0,0,3,3H18a3,3,0,0,0,3-3V10.25A3,3,0,0,0,20,8ZM14,20H10V15a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H16V15a3,3,0,0,0-3-3H11a3,3,0,0,0-3,3v5H6a1,1,0,0,1-1-1V10.25a1,1,0,0,1,.34-.75l6-5.25a1,1,0,0,1,1.32,0l6,5.25a1,1,0,0,1,.34.75Z"></path>
                </svg>
                <div class="entity__information">
                    <span class="entity__title">Тип жилья</span>
                    <span class="entity__description">{{ $estate->type->title }}</span>
                </div>
            </div>
            <div class="entity">
                <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="bed">
                    <path fill="#6563FF"
                          d="M7,12.5a3,3,0,1,0-3-3A3,3,0,0,0,7,12.5Zm0-4a1,1,0,1,1-1,1A1,1,0,0,1,7,8.5Zm13-2H12a1,1,0,0,0-1,1v6H3v-8a1,1,0,0,0-2,0v13a1,1,0,0,0,2,0v-3H21v3a1,1,0,0,0,2,0v-9A3,3,0,0,0,20,6.5Zm1,7H13v-5h7a1,1,0,0,1,1,1Z"></path>
                </svg>
                <div class="entity__information">
                    <span class="entity__title">спален</span>
                    <span class="entity__description">{{ $estate->bedrooms }}</span>
                </div>
            </div>
            <div class="entity">
                <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="snowflake">
                    <path fill="#6563FF"
                          d="M21.16,16.13l-2-1.15.89-.24a1,1,0,1,0-.52-1.93l-2.82.76L14,12l2.71-1.57,2.82.76.26,0a1,1,0,0,0,.26-2L19.16,9l2-1.15a1,1,0,0,0-1-1.74L18,7.37l.3-1.11a1,1,0,1,0-1.93-.52l-.82,3L13,10.27V7.14l2.07-2.07a1,1,0,0,0,0-1.41,1,1,0,0,0-1.42,0L13,4.31V2a1,1,0,0,0-2,0V4.47l-.81-.81a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41L11,7.3v3L8.43,8.78l-.82-3a1,1,0,1,0-1.93.52L6,7.37,3.84,6.13a1,1,0,0,0-1,1.74L4.84,9,4,9.26a1,1,0,0,0,.26,2l.26,0,2.82-.76L10,12,7.29,13.57l-2.82-.76A1,1,0,1,0,4,14.74l.89.24-2,1.15a1,1,0,0,0,1,1.74L6,16.63l-.3,1.11A1,1,0,0,0,6.39,19a1.15,1.15,0,0,0,.26,0,1,1,0,0,0,1-.74l.82-3L11,13.73v3.13L8.93,18.93a1,1,0,0,0,0,1.41,1,1,0,0,0,.71.3,1,1,0,0,0,.71-.3l.65-.65V22a1,1,0,0,0,2,0V19.53l.81.81a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.41L13,16.7v-3l2.57,1.49.82,3a1,1,0,0,0,1,.74,1.15,1.15,0,0,0,.26,0,1,1,0,0,0,.71-1.23L18,16.63l2.14,1.24a1,1,0,1,0,1-1.74Z"></path>
                </svg>
                <div class="entity__information">
                    <span class="entity__title">кондиционеров</span>
                    <span class="entity__description">{{ $estate->conditioners }}</span>
                </div>
            </div>
            <div class="entity">
                <svg class="entity__image" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24"
                     id="bath">
                    <path fill="#6563FF"
                          d="M22 12H5V6.41a1.975 1.975 0 0 1 1.04-1.759 1.995 1.995 0 0 1 1.148-.23 3.491 3.491 0 0 0 .837 3.554l1.06 1.06a1 1 0 0 0 1.415 0L14.035 5.5a1 1 0 0 0 0-1.414l-1.06-1.06a3.494 3.494 0 0 0-4.53-.343A3.992 3.992 0 0 0 3 6.41V12H2a1 1 0 0 0 0 2h1v3a2.995 2.995 0 0 0 2 2.816V21a1 1 0 0 0 2 0v-1h10v1a1 1 0 0 0 2 0v-1.184A2.995 2.995 0 0 0 21 17v-3h1a1 1 0 0 0 0-2ZM9.44 4.44a1.502 1.502 0 0 1 2.12 0l.354.353-2.121 2.121-.354-.353a1.501 1.501 0 0 1 0-2.122ZM19 17a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-3h14Z"></path>
                </svg>
                <div class="entity__information">
                    <span class="entity__title">ванных комнат</span>
                    <span class="entity__description">{{ $estate->bathrooms }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Удобства проживания</label>
        <ul>
            @foreach($estate_includes as $include)
                <li>{{ $include }}</li>
            @endforeach
        </ul>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Расположение</label>
        <p>{{ $estate->geoposition  }}</p>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Описание</label>
        <p>{{ $estate->description  }}</p>
    </div>
</div>
</body>
</html>


