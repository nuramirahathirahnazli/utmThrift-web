<!DOCTYPE html>
<html>
<head>
    <title>Your UTMThrift OTP Code</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            background-color: #0369a1;
            padding: 20px;
            text-align: center;
        }
        .logo {
            color: white;
            font-weight: 600;
            font-size: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
        }
        .otp-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            font-weight: 600;
            color: #0369a1;
            letter-spacing: 2px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 16px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .button {
            display: inline-block;
            background-color: #0369a1;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/>
                </svg>
                UTMThrift
            </div>
        </div>
        
        <div class="content">
            <h2 style="margin-top: 0;">OTP Verification</h2>
            <p>Hello,</p>
            <p>We received a request to access your UTMThrift account. Please use the following One-Time Password (OTP) to verify your identity:</p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <p style="margin-bottom: 25px;">This OTP is valid for <strong>10 minutes</strong>. Please do not share this code with anyone.</p>
            
            <p>If you didn't request this OTP, please ignore this email or contact our support team immediately.</p>
            
            <p>Thank you,<br>The UTMThrift Team</p>
        </div>
        
        <div class="footer">
            <p>© 2025 UTMThrift. All rights reserved.</p>
            <p>Universiti Teknologi Malaysia</p>
        </div>
    </div>
</body>
</html>