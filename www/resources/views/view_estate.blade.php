<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/viewEstate/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/viewEstate.js" defer></script>
    <script src="https://telegram.org/js/telegram-web-app.js" defer></script>
    <title>Просмотр объекта</title>
</head>
<body>
<div class="container mt-5 view-page">
    <h1 class="page-title">Просмотр объекта</h1>
    <div class="form-group">
        <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
            @for($i = 0; $i < count($estate_photos); $i++)
                @if($i == 0)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}" class="active" aria-current="true" aria-label="Slide {{$i + 1}}"></button>
                @else
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}" aria-label="Slide {{$i + 1}}"></button>
                @endif
            @endfor
        </div>
        <div class="carousel-inner" style="height: 200px">
            @php
                $counter = 0;
            @endphp
            @foreach($estate_photos as $estate_photo)
                @php
                    $counter++;
                @endphp
                <div class="carousel-item {{ $counter == 1 ? "active" : "" }}">
                    <img class="d-block w-100" src="/photos/{{$estate_photo}}" alt="estate-photo">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" style="background-color: rgba(0, 0, 0, 0.2)" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" style="background-color: rgba(0, 0, 0, 0.2)" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Сделка</label>
        <p>Тип сделки: <br>{{ $estate->deal_type  }}</p>
        <p>Цена за весь период: <br>{{ $estate_rent->price  }}</p>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Какие удобства вас ждут</label>
        <div class="view-page__amenities">
            <div class="amenity">
                <div class="amenity__image"></div>
                <div class="amenity__information">
                    <span class="amenity__title">спален</span>
                    <span class="amenity__description">{{ $estate->bedrooms }}</span>
                </div>
            </div>
            <div class="amenity">
                <div class="amenity__image"></div>
                <div class="amenity__information">
                    <span class="amenity__title">кондиционеров</span>
                    <span class="amenity__description">{{ $estate->conditioners }}</span>
                </div>
            </div>
            <div class="amenity">
                <div class="amenity__image"></div>
                <div class="amenity__information">
                    <span class="amenity__title">ванных комнат</span>
                    <span class="amenity__description">{{ $estate->bathrooms }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Включено в стоимость</label>
        <ul>
        @foreach($estate_includes as $include)
            <li>{{ $include }}</li>
        @endforeach
        </ul>
    </div>
    <div class="view-page__group">
        <hr>
        <label class="form-group__title">Описание</label>
        <p>{{ $estate->description  }}</p>
    </div>
</div>
</body>
</html>


