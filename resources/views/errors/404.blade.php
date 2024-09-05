<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link type="text/css" href="{{ asset('argon/css/argon.css?v=1.0.1') }}" rel="stylesheet">
    <link href="{{ asset('argon/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            /* Full height to make container flex stretch full screen */
            margin: 0;
            /* Remove default margin */
        }

        .vh-100 {
            height: 100vh;
            /* Make div full viewport height */
        }

        .flex-center {
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
            text-align: center;
        }

        .content {
            flex-direction: column;
            /* Stack flex items vertically */
        }
    </style>
</head>

<body>
    <div class="container vh-100 flex-center">
        <div class="content">
            <h1>Oops! Page not found.</h1>
            <p>We can't seem to find the page you're looking for.</p>
            <a href="{{ url('/') }}" class="btn btn-primary"><i class="fas fa-home"></i> Go Home</a>
        </div>
    </div>
</body>

</html>
