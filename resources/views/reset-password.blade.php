<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            background: #ffffff; /* ✔ WHITE CARD */
            color: #000; /* ✔ BLACK TEXT */
            border-radius: 16px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #000; /* ✔ BLACK */
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc; /* ✔ CLEAN INPUT BORDER */
            border-radius: 8px;
            background: #f1f1f1; /* ✔ light grey input background */
            color: #000; /* ✔ BLACK TEXT */
            font-size: 14px;
            margin-top: 8px;
        }

        input::placeholder {
            color: #777; /* ✔ Neutral placeholder */
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-size: 14px;
            color: #000; /* ✔ BLACK LABEL */
        }

        button {
            margin-top: 25px;
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
            color: #000; /* ✔ BLACK */
        }

        .error {
            color: #d00000;
        }

        .success {
            color: #15803d;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Reset Password</h2>

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('reset.password.submit') }}">
            @csrf
            <label>New Password</label>
            <input type="password" name="password" placeholder="Enter new password" required>

            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
