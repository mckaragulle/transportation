
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
@if ($errors->any())
<div class="alert alert-danger alert-dismissible alert-alt solid fade show">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
    </button>
    @foreach ($errors->all() as $key => $error)
        {{ $error }}<br />
    @endforeach
</div>
@endif
<script src="{{asset('xhtml/js/jquery.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('xhtml/js/powergrid.js')}}"></script>
@stack('scripts')
@livewireScripts
</body>
</html>
