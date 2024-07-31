<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="https://www.evertecinc.com/wp-content/uploads/2020/07/cropped-favicon-evertec-32x32.png">
    <title>{{ config('app.title', 'Laravel') }}</title>

    @vite('resources/js/app.js')
    @inertiaHead
    @vite('resources/css/app.css')
</head>

<body>
    @inertia
</body>

</html>