<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <!-- Incluindo Tailwind CSS para facilitar a estilização -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-xl shadow-lg text-center max-w-md w-full m-4">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">403</h1>
        <h2 class="text-3xl font-semibold text-gray-700 mb-2">Acesso Negado</h2>
        <p class="text-gray-500 mb-6">
            Você não tem permissão para acessar esta página.
        </p>
        <a href="{{ route('dashboard.index') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
            Voltar para a Página Inicial
        </a>
    </div>
</body>

</html>
