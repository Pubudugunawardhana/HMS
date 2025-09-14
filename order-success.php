<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Hotel Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(140deg, #1a1a2e 0%, #16213e 35%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .success-container {
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 60px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: checkmarkAnimation 1s ease-in-out 0.5s both;
        }

        @keyframes checkmarkAnimation {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon i {
            font-size: 48px;
            color: white;
            animation: checkPulse 0.6s ease-in-out 1.1s both;
        }

        @keyframes checkPulse {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        .success-title {
            font-size: 32px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 15px;
            animation: fadeInUp 0.8s ease-out 0.8s both;
        }

        .success-subtitle {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 40px;
            line-height: 1.6;
            animation: fadeInUp 0.8s ease-out 1s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .confirmation-card {
            background: linear-gradient(135deg, #f0fff4, #e6fffa);
            border: 2px solid #48bb78;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out 1.2s both;
        }

        .confirmation-message {
            font-size: 16px;
            color: #2f855a;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .confirmation-details {
            font-size: 14px;
            color: #38a169;
            line-height: 1.5;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 1.4s both;
        }

        .btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: rgba(74, 85, 104, 0.1);
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: rgba(74, 85, 104, 0.2);
            border-color: #cbd5e0;
            transform: translateY(-1px);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 40px;
            animation: fadeInUp 0.8s ease-out 1.6s both;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon i {
            font-size: 16px;
            color: white;
        }

        .feature-text {
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
        }

        .footer-note {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #718096;
            animation: fadeInUp 0.8s ease-out 1.8s both;
        }

        @media (max-width: 768px) {
            .success-container {
                padding: 40px 25px;
                margin: 10px;
            }
            
            .success-title {
                font-size: 28px;
            }
            
            .success-subtitle {
                font-size: 16px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .success-icon {
                width: 100px;
                height: 100px;
            }
            
            .success-icon i {
                font-size: 40px;
            }
            
            .success-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="success-title">Booking Confirmed!</h1>
        <p class="success-subtitle">
            Thank you for your reservation. Your hotel booking has been successfully processed and confirmed.
        </p>
        
        
        <div class="action-buttons">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Back to Home
            </a>
            <a href="bookings.php" class="btn btn-secondary">
                <i class="fas fa-list"></i>
                View My Bookings
            </a>
        </div>
        
        <div class="footer-note">
            <p><strong>Need assistance?</strong> Our customer support team is available 24/7 to help you with any questions about your booking.</p>
            <p style="margin-top: 10px;">
                <i class="fas fa-phone" style="margin-right: 5px;"></i> +94 11 123 4567 |
                <i class="fas fa-envelope" style="margin-left: 15px; margin-right: 5px;"></i> support@hotel.com
            </p>
        </div>
    </div>
</body>
</html>