<!DOCTYPE html>
<html>
<head>
    <title>Payment Status | UTMThrift</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #f9f9f9;
        }

        .status-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        .status-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .success-icon {
            color: #4CAF50;
        }

        .fail-icon {
            color: #F44336;
        }

        h1 {
            margin: 0;
            font-size: 2.2rem;
        }

        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .detail-item {
            margin-bottom: 0.8rem;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            margin: 0.5rem 0;
        }

        .success-badge {
            background-color: #E8F5E9;
            color: #2E7D32;
        }

        .fail-badge {
            background-color: #FFEBEE;
            color: #C62828;
        }

        .thank-you {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            font-style: italic;
            color: #666;
        }

        @media (max-width: 600px) {
            .order-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="status-card">
        <div class="status-header">
            @if ($statusId == 1)
                <div class="status-icon success-icon">✓</div>
                <h1>Payment Successful</h1>
            @else
                <div class="status-icon fail-icon">✗</div>
                <h1>Payment Failed</h1>
            @endif
        </div>

        <!-- TRANSACTION DETAILS -->
        <h2>Transaction Details</h2>
        <div class="order-details">
            <div class="detail-item">
                <span class="detail-label">Transaction ID:</span><br>
                {{ $transactionId ?? 'N/A' }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Order ID:</span><br>
                {{ $orderId ?? 'N/A' }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Bill Code:</span><br>
                {{ $billCode ?? 'N/A' }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Payment Method:</span><br>
                {{ $paymentMethod ?? 'Online Banking' }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Paid At:</span><br>
                {{ \Carbon\Carbon::parse($timestamp)->format('d M Y, h:i A') }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Status Message:</span><br>
                @switch($msg)
                    @case('ok')
                        <span style="color: #4CAF50;">✓ Payment completed successfully</span>
                        @break
                    @case('cancelled')
                        <span style="color: #FF9800;">⚠️ Payment was cancelled by the user</span>
                        @break
                    @case('declined')
                        <span style="color: #F44336;">✗ Payment was declined by the bank</span>
                        @break
                    @default
                        <span style="color: #9E9E9E;">Unknown status</span>
                @endswitch
            </div>
        </div>

        <!-- ORDER DETAILS -->
        <h2 style="margin-top: 2rem;">Order Details</h2>
        <div class="order-details">
            <div class="detail-item">
                <span class="detail-label">Item:</span><br>
                {{ $itemName ?? '-' }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Quantity:</span><br>
                {{ $quantity ?? 1 }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Order Status:</span><br>
                @if ($statusId == 1)
                    <span class="status-badge success-badge">Paid</span>
                @else
                    <span class="status-badge fail-badge">Failed</span>
                @endif
            </div>
        </div>

        <!-- THANK YOU MESSAGE -->
        <div class="thank-you">
            @if ($statusId == 1)
                <p>Your order will be processed soon. Thank you for shopping with UTMThrift!</p>
            @else
                <p>Unfortunately, your payment failed. Please try again or contact our support team.</p>
            @endif
            <p>Need help? Contact support@utmthrift.edu.my</p>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="utmthrift://orders/history?token={{ $access_token }}" ...>Go to Order History</a>

        </div>

    </div>

</body>
</html>
