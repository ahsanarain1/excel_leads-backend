<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? '' }}</title>
</head>

<body>
    <div style="background-color: #f8f9fa; padding: 20px;">
        <div style="background-color: #ffffff; border-radius: 5px; padding: 20px;">
            <h1>{{ $title ?? '' }}</h1>

            {{ $slot }}

            <p style="font-size: 14px;">Regards,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>

</html>