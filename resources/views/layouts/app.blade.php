
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{asset('xhtml/css/style.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('xhtml/vendor/select2/css/select2.min.css')}}">
    @stack('styles')
    @livewireStyles
</head>
<body>
{{ $slot }}

<script src="{{asset('xhtml/js/jquery.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('xhtml/js/powergrid.js')}}"></script>
@stack('scripts')
@livewireScripts
</body>
</html>
