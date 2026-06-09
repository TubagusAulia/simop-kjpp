<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMOP KJPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#86c381] min-h-screen flex items-center justify-center">
    <div class="text-center text-white">
        <img src="/images/kjpp_logo.png" alt="KJPP Logo" class="w-48 mx-auto mb-8 rounded-[20px]">
        <h1 class="text-4xl font-bold mb-4">SIMOP KJPP</h1>
        <p class="text-lg mb-8 text-green-100">Sistem Manajemen Operasional KJPP Yanuar Rosye dan Rekan</p>
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="bg-white text-[#86c381] px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">Log In</a>
            <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-[#86c381] transition">Sign Up</a>
        </div>
    </div>
</body>
</html>
