<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Two-Factor Authentication</h2>

        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
            <p class="font-bold">Verification Code</p>
            <p>Please enter the following code to complete your signup:</p>
        </div>

        <div class="text-center">
            <span class="text-4xl font-mono font-bold tracking-wider text-gray-700">{{ $code }}</span>
        </div>

        <p class="mt-6 text-sm text-gray-600 text-center">
            This code will expire in 10 minutes. If you didn't request this code, please ignore this message.
        </p>

        <form action="{{ route('verify.two.factor.code') }}" method="POST" class="mt-8">
            @csrf

            <div class="flex items-center justify-between">
                <a href="{{ route('resend.2fa') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Resend Code
                </a>
            </div>
        </form>
    </div>
</body>
</html>
