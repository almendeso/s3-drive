<!DOCTYPE html>
<!-- © 2026 S3-Sites by Alvaro Mendes-->
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="author" content="Alvaro Mendes">

    @include('css.app')
    @include('css.dragdrop')
</head>

@yield('content')

</html>
