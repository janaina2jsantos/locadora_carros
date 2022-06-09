<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>LocadoraCarros</title>
    </head>
    <body>    
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <ul>
                    @foreach($dados as $dado)
                        <li>{{ $dado->nome }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>