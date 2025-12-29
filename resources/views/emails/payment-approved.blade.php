<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }

        .info-box {
            background-color: white;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>✅ Payment Approved!</h1>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $name }}</strong>,</p>

        <p>Congratulations! Your payment has been verified and approved. You are now officially registered for the
            <strong>WebRunning 5K Event</strong>!</p>

        <div class="info-box">
            <h3>Registration Details:</h3>
            <p><strong>Registration Code:</strong> {{ $registrationCode }}</p>
            <p><strong>T-Shirt Size:</strong> {{ $shirtSize }}</p>
            <p><strong>Payment Verified:</strong> {{ $verifiedAt }}</p>
        </div>

        <p><strong>What's Next?</strong></p>
        <ul>
            <li>Save your registration code for reference</li>
            <li>Wait for further event details via email</li>
            <li>Prepare for the event day!</li>
        </ul>

        <p>We're excited to see you at the event! If you have any questions, please don't hesitate to contact us.</p>

        <p>Best regards,<br>
            <strong>WebRunning Team</strong>
        </p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} WebRunning. All rights reserved.</p>
    </div>
</body>

</html>