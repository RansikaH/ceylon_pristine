<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $messageData['subject'] }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            background: white;
            padding: 40px 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .message-type {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .subject {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message-content {
            font-size: 16px;
            line-height: 1.8;
            color: #495057;
            white-space: pre-wrap;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .brand {
            font-weight: 700;
            color: #667eea;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header, .content {
                padding: 20px;
            }
            .subject {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ceylon Moms</h1>
        <p>Your Trusted Shopping Partner</p>
    </div>
    
    <div class="content">
        <div class="message-type">
            {{ ucfirst(str_replace('_', ' ', $messageData['message_type'])) }}
        </div>
        
        <div class="subject">
            {{ $messageData['subject'] }}
        </div>
        
        <div class="message-content">
            {{ $messageData['message'] }}
        </div>
        
        <div class="footer">
            <p>Thank you for choosing <span class="brand">Ceylon Moms</span></p>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>&copy; {{ date('Y') }} Ceylon Moms. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
