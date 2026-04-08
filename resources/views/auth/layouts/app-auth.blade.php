<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') | visitMerauke.com</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.jpeg') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 1rem;
        }
    </style>

</head>

<body class="d-flex align-items-center justify-content-center">
    @yield('content')

</body>

</html>