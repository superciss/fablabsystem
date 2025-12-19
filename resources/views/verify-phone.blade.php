<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background: #f2f2f2;
    }

    .center-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 15px;
    }

    .otp-card {
        width: 380px;
        background: #e0e0e0; /* Gray card */
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .otp-card h3 {
        text-align: center;
        margin-bottom: 10px;
    }

    .otp-card p {
        text-align: center;
        color: #555;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        color: #444;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        margin-bottom: 15px;
        font-size: 16px;
    }

    .btn {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .btn-dark {
        background: #333;
        color: white;
    }

    .btn-dark:hover {
        background: #111;
    }

    .btn-gray {
        background: #555;
        color: white;
    }

    .btn-gray:hover {
        background: #444;
    }

    .divider {
        text-align: center;
        color: #777;
        margin: 15px 0;
    }
</style>


<div class="center-container">
    <div class="otp-card">

        <h3>Phone Verification</h3>
        <p>Enter the 6-digit OTP sent to your phone.</p>

        <!-- OTP Verify Form -->
        <form method="POST" action="{{ route('phone.verify') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">

            <label class="form-label">Enter OTP Code:</label>
            <input type="text" name="code" maxlength="6" required class="form-control" placeholder="Enter 6-digit code">

            <button type="submit" class="btn btn-dark">Verify OTP</button>
        </form>

        <div class="divider">— or —</div>

        <!-- Resend Code Form -->
        <form method="POST" action="{{ route('phone.resend') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">

            <button type="submit" class="btn btn-gray">Resend Code</button>
        </form>

    </div>
</div>
