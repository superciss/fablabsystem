<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0638ddff, #4073d1ff, #2981d4ff);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #ffffff; /* ✔ White card */
            color: #000; /* ✔ Black text */
            border-radius: 16px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f1f1f1;
            color: #000;
            font-size: 14px;
            margin-top: 8px;
        }

        input::placeholder {
            color: #777;
        }

        button {
            margin-top: 20px;
            background: #00b4d8;
            border: none;
            color: white;
            padding: 12px 20px;
            width: 100%;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: #0096c7;
        }

        p {
            font-size: 14px;
            margin-top: 10px;
        }

        .error {
            color: #d00000;
        }

        .success {
            color: #15803d;
        }

        label {
            text-align: left;
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Forgot Password</h2>

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('forgot.password.submit') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <button type="submit">Send Code</button>
        </form>
    </div>
</body>
</html>
