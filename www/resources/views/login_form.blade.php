<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/bundle.css">
    <link rel="stylesheet" href="{{env('NGROK_SERVER')}}/css/pages/LoginPage/style.css">
    <script src="{{env('NGROK_SERVER')}}/js/globalVariables.js" defer></script>
    <script src="{{env('NGROK_SERVER')}}/js/authentication.js" defer></script>
    <title>Авторизация</title>
</head>
<body>
<div class="login-page">
    <h1 class="page-title">🔑GetKeysBot🔑</h1>
    <form method="post" class="form" id="form">
        @csrf
        <div class="form-group">
            <label class="form-group__title" for="username">Username</label>
            <input type="text" class="form-control form-group__input" id="username" placeholder="Your username" name="username">
            @error('username')
            <p class="error-field login-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-group__title" for="password">Password</label>
            <input type="password" class="form-control form-group__input" placeholder="Your password" id="password" name="password">
            @error('password')
            <p class="error-field password-error">{{ $message }}</p>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" id="btn-submit" class="btn login-btn">Войти</button>
        </div>
    </form>
</div>
</body>
</html>

