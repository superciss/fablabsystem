<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Reset Code</title>
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
            color: #000; /* ✔ TEXT BLACK */
            border-radius: 16px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #000; /* ✔ BLACK */
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #000; /* ✔ BLACK LABEL */
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f1f1f1; /* ✔ light grey input */
            color: #000; /* ✔ BLACK TEXT */
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 3px;
            font-weight: bold;
        }

        input::placeholder {
            color: #777;
        }

        button {
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s ease;
            width: 100%;
        }

        .verify-btn {
            background: #00b4d8;
            margin-bottom: 12px;
        }

        .verify-btn:hover {
            background: #0096c7;
        }

        .resend-btn {
            background: #38bdf8;
        }

        .resend-btn:hover {
            background: #0ea5e9;
        }

        p {
            font-size: 14px;
            margin-top: 15px;
            color: #000; /* ✔ BLACK TEXT */
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
        <h2>Verify Reset Code</h2>

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('verify.reset.code.submit') }}" id="verifyForm">
            @csrf
            <label>Enter 6-digit Code</label>
            <input type="text" name="code" maxlength="6" placeholder="______" required>
            <button type="submit" class="verify-btn" id="verifyBtn">Verify</button>
        </form>

        <form method="POST" action="{{ route('resend.reset.code') }}" id="resendForm" style="display:none;">
            @csrf
            <button type="submit" class="resend-btn">Send New Code</button>
        </form>

        <p id="countdown"></p>
    </div>

    <script>
        let duration = 1 * 60;
        const countdownEl = document.getElementById("countdown");
        const verifyBtn = document.getElementById("verifyBtn");
        const resendForm = document.getElementById("resendForm");

        function updateCountdown() {
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            countdownEl.textContent = `Code valid for ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (duration <= 0) {
                verifyBtn.disabled = true;
                verifyBtn.style.background = '#6b7280';
                countdownEl.textContent = "Code expired. Please send a new one.";
                resendForm.style.display = "block";
                return;
            }

            duration--;
            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
    </script>
</body>
</html>
