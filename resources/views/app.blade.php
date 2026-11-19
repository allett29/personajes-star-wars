<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name') }}</title>

    <script>
        // Aplica el tema guardado antes de pintar la página para evitar un parpadeo.
        (function () {
            var guardado = localStorage.getItem('tema-oscuro');
            var oscuro = guardado === '1' || (guardado === null && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', oscuro);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@600;700&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>
