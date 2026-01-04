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
            background-color: #f5f5f5;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background-color: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .bib-number {
            font-size: 64px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 10px;
            margin: 20px 0;
            letter-spacing: 8px;
        }

        .info-box {
            background-color: #f9f9f9;
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
            float: right;
        }

        /* Ticket-style barcode container */
        .ticket-container {
            background: white;
            border: 2px dashed #4CAF50;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .ticket-header {
            background: #000;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .ticket-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .barcode-wrapper {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            display: inline-block;
        }

        .barcode-wrapper img {
            max-width: 300px;
            height: auto;
            display: block;
        }

        .ticket-id {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            color: #666;
            margin: 15px 0;
            font-weight: bold;
        }

        .ticket-details {
            text-align: left;
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .ticket-details p {
            margin: 8px 0;
            font-size: 14px;
        }

        .ticket-footer {
            margin-top: 20px;
            padding: 15px;
            background: #fff3cd;
            border-radius: 8px;
            font-size: 13px;
            color: #856404;
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
            <strong>WebRunning 5K Event</strong>!
        </p>

        <!-- Registration Details -->
        <div class="info-box">
            <h3>Registration Details</h3>
            <div class="info-row">
                <span class="label">Registration Code:</span>
                <span class="value">{{ $registrationCode }}</span>
                <div style="clear: both;"></div>
            </div>
            <div class="info-row">
                <span class="label">T-Shirt Size:</span>
                <span class="value">{{ $shirtSize }}</span>
                <div style="clear: both;"></div>
            </div>
            <div class="info-row">
                <span class="label">Payment Verified:</span>
                <span class="value">{{ $verifiedAt }}</span>
                <div style="clear: both;"></div>
            </div>
        </div>

        <!-- Ticket-style QR Code -->
        @if($qrcodeImage)
            <div class="ticket-container">
                <div class="ticket-header">
                    <p class="ticket-title">WEBRUNNING 5K EVENT 2026</p>
                </div>

                <div class="barcode-wrapper">
                    <img src="data:image/png;base64,{{ $qrcodeImage }}" alt="QR Code">
                </div>

                <div class="ticket-id">
                    Ticket ID: {{ $registrationCode }}
                </div>

                <div class="ticket-details">
                    <p><strong>• Name:</strong> {{ $name }}</p>
                    <p><strong>• Registration Code:</strong> {{ $registrationCode }}</p>
                    <p><strong>• Size Jersey:</strong> {{ $shirtSize }}</p>
                </div>

                <div class="ticket-footer">
                    <strong>SHOW THIS TICKET</strong><br>
                    TO GET RACE PACK COLLECTION
                </div>
            </div>
        @else
            <div style="background: #ffebee; padding: 15px; border-radius: 8px; text-align: center; color: #c62828;">
                <p><strong>⚠️ QR code generation failed</strong></p>
                <p>Please contact support with your registration code: <strong>{{ $registrationCode }}</strong></p>
            </div>
        @endif

        <!-- Important Instructions -->
        <div class="instructions">
            <h3 style="margin-top: 0; color: #856404;">📋 Important Instructions</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>Save this email</strong> - You'll need it for race pack pickup</li>
                <li><strong>Bring your ID</strong> - Bring your ID card (NIK) when collecting race pack</li>
                <li><strong>Show the QR code</strong> - Present the QR code above at the pickup counter</li>
            </ul>
        </div>

        <!-- What's Next -->
        <p><strong>What's Next?</strong></p>
        <ul>
            <li>Wait for race pack pickup schedule announcement</li>
            <li>Bring this email (printed or on your phone)</li>
            <li>Show your QR code to collect race pack</li>
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