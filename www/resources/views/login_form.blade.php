<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"
            defer></script>
    <title>Фильтрация объектов</title>
</head>
<body>
<form method="post">
    @csrf
    <div class="form-group" id="price-container">
        <label class="form-group__title" for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username">
        @error('username')
        <div>{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" id="price-container">
        <label class="form-group__title" for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="d-grid gap-2 main-buttons">
        <button type="submit" id="btn-submit" class="btn">Войти</button>
    </div>
</form>
</body>
</html>

