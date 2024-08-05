<!DOCTYPE html>
<html class="h-full bg-gray-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="https://www.evertecinc.com/wp-content/uploads/2020/07/cropped-favicon-evertec-32x32.png">
    <title>{{ config('app.title', 'Laravel') }}</title>

    @filamentStyles
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body class="h-full">
    @inertia
</body>

</html>