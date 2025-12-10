<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="bg-white shadow p-10 rounded-xl text-center max-w-md mx-auto">
        <h1 class="text-3xl font-bold text-amber-600">{{ $title }}</h1>

        <p class="text-6xl font-bold mt-6 text-gray-800">{{ $total }}</p>

        <a href="/" class="mt-10 inline-block bg-amber-500 text-white px-6 py-3 rounded-lg">
            Kembali ke Landing Page
        </a>
    </div>

</body>
</html>
