<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0638ddff, #4073d1ff, #2981d4ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .verify-card {
            background: #ffffff; /* ✔ WHITE CARD */
            color: #000; /* ✔ BLACK TEXT */
            border-radius: 16px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
            font-weight: 600;
        }

        p {
            color: #333;
            font-size: 14px;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f1f1f1;
            font-size: 14px;
            color: #000;
        }

        input::placeholder {
            color: #777;
        }

        button {
            margin-top: 15px;
            padding: 12px 20px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s ease;
        }

        .verify-btn {
            background: #3498db;
            color: white;
        }

        .verify-btn:disabled {
            background: #6b7280;
            cursor: not-allowed;
        }

        .resend-btn {
            background: #2ecc71;
            color: white;
            margin-top: 10px;
        }

        .success { color: #15803d; }
        .error { color: #d00000; }

        #countdown {
            margin-top: 10px;
            color: #444;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="verify-card">
        <h2>Email Verification</h2>

        <p>We sent a 6-digit code to <strong>{{ request('email') }}</strong></p>

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        @if (session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('verify.code') }}">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <input type="text" name="code" placeholder="Enter 6-digit code" maxlength="6" required>

            <button type="submit" class="verify-btn" id="verifyBtn">Verify</button>
        </form>

        <form method="POST" action="{{ route('resend.code') }}" id="resendForm" style="display:none;">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <button type="submit" class="resend-btn">Send New Code</button>
        </form>

        <p id="countdown"></p>
    </div>

    <script>
        let duration = 60; // 1 minute
        const countdownEl = document.getElementById("countdown");
        const verifyBtn = document.getElementById("verifyBtn");
        const resendForm = document.getElementById("resendForm");

        function updateCountdown() {
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            countdownEl.textContent = `Code valid for ${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (duration <= 0) {
                verifyBtn.disabled = true;
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
