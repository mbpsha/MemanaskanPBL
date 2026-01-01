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
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }

        .info-box h3 {
            margin-top: 0;
            color: #4CAF50;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #666;
        }

        .value {
            color: #333;
        }

        .bib-number {
            font-size: 48px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 10px;
            margin: 20px 0;
            letter-spacing: 5px;
        }

        .barcode-container {
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            margin: 20px 0;
        }

        .barcode-container img {
            max-width: 100%;
            height: auto;
        }

        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }

        .instructions {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
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

        <!-- BIB Number -->
        <div class="bib-number">
            {{ $bibNumber }}
        </div>
        <p style="text-align: center; color: #666; margin-top: -10px;">Your BIB Number</p>

        <!-- Registration Details -->
        <div class="info-box">
            <h3>Registration Details</h3>
            <div class="info-row">
                <span class="label">Registration Code:</span>
                <span class="value">{{ $registrationCode }}</span>
            </div>
            <div class="info-row">
                <span class="label">BIB Number:</span>
                <span class="value"><strong>{{ $bibNumber }}</strong></span>
            </div>
            <div class="info-row">
                <span class="label">T-Shirt Size:</span>
                <span class="value">{{ $shirtSize }}</span>
            </div>
            <div class="info-row">
                <span class="label">Payment Verified:</span>
                <span class="value">{{ $verifiedAt }}</span>
            </div>
        </div>

        <!-- Barcode for Race Pack Pickup -->
        @if($barcodeImage)
            <div class="barcode-container">
                <h3 style="margin-top: 0; color: #4CAF50;">Race Pack Pickup Barcode</h3>
                <p style="color: #666; font-size: 14px;">Show this barcode when collecting your race pack</p>
                <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode">
                <div class="barcode-text">{{ $registrationCode }}</div>
            </div>
        @endif

        <!-- Important Instructions -->
        <div class="instructions">
            <h3 style="margin-top: 0; color: #856404;">📋 Important Instructions</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>Save this email</strong> - You'll need it for race pack pickup</li>
                <li><strong>Bring your ID</strong> - Bring your ID card (NIK) when collecting race pack</li>
                <li><strong>Show the barcode</strong> - Present the barcode above at the pickup counter</li>
                <li><strong>Your BIB number is {{ $bibNumber }}</strong> - Remember this number!</li>
            </ul>
        </div>

        <!-- What's Next -->
        <p><strong>What's Next?</strong></p>
        <ul>
            <li>Wait for race pack pickup schedule announcement</li>
            <li>Bring this email (printed or on your phone)</li>
            <li>Collect your race pack with BIB number <strong>{{ $bibNumber }}</strong></li>
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