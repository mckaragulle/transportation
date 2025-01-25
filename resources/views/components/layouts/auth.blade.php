<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Jobick : Job Admin Bootstrap 5 Template" />
    <meta name="format-detection" content="telephone=no">
    <title>TAŞIMACILIK PROGRAMI</title>
    {{-- <title>{{ \Illuminate\Support\Str::ucfirst(Spatie\Multitenancy\Models\Tenant::current()->name) }} - TAŞIMACILIK PROGRAMI</title> --}}

    {{-- <link rel="shortcut icon" type="image/png" href="{{ asset('xhtml/images/favicon.png') }}" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/themes@5.0.11/default/default.min.css" />
    <link href="{{ asset('xhtml/css/style.css') }}" rel="stylesheet">

</head>

<body>
    <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Mustafa KARAGÜLLE © {{ env('APP_VERSION') }} 2024</p>
        </div>
    </div>

</body>

</html>
