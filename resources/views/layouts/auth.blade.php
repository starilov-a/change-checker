<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Марк Отто, Джейкоб Торнтон и контрибьюторы Bootstrap">
    <meta name="generator" content="Hugo 0.80.0">
    <meta name="theme-color" content="#7952b3">

    <title>Вход · Monitor v0.2</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <link href="{{ url('assets/css/auth.css') }}" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body class="text-center">

<main class="form-signin">
    <form method="post" action="/auth">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Вход</h1>

        <div class="form-floating">
            <input name="login" type="text" class="form-control" id="floatingInput" placeholder="user1">
            <label for="floatingInput">Логин</label>
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Пароль</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Войти</button>
        @error('message')
            <div class="alert alert-danger" role="alert" style="margin-top: 10px">
                {{ $message }}
            </div>
        @enderror
        <p class="mt-5 mb-3 text-muted">© 2023</p>

    </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
