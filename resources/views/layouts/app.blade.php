{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel Auth') }}</title>
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    @yield('content')
</body>

</html>
