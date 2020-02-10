<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <div class="container" id="app">
            <div class="row">
                <div class="col s12 m6">
                    <recipe-component></recipe-component>
                </div>
            </div>

        </div>
    </body>

    <script src="{{ asset('js/app.js') }}"></script>
</html>
