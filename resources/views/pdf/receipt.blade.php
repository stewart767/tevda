<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #0f172a;
            padding: 30px;
            font-size: 12px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #065f46;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 22px;
            font-weight: 900;
            color: #065f46;
        }
        .receipt-no {
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
            color: #d97706;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-table td {
            vertical-align: top;
            padding: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #065f46;
            color: #ffffff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .total-box {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #065f46;
            margin-top: 15px;
        }
        .footer-note {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                @if(!empty($logoDataUri))
                    <img src="{{ $logoDataUri }}" style="max-height: 40px; max-width: 140px; object-fit: contain; margin-bottom: 4px; display: block;" alt="Logo">
                @else
                    <div class="title">TEVDA</div>
                @endif
                <div style="font-size: 11px; font-weight: bold; color: #1e293b;">Tanzania Electric Vehicle Drivers Association</div>
                <div style="font-size: 10px; color: #64748b;">Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania</div>
                <div style="font-size: 10px; color: #64748b;">Telephone: +255 757 700 401 • info@tevda.or.tz</div>
            </td>
            <td style="text-align: right;">
                <div style="font-size: 18px; font-weight: bold; color: #065f46;">OFFICIAL RECEIPT</div>
                <div class="receipt-no">{{ $receipt->receipt_number }}</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 4px;">Date: {{ $receipt->issued_at->format('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong style="color: #64748b; font-size: 10px; text-transform: uppercase;">Received From:</strong><br/>
                <strong style="font-size: 13px;">{{ $receipt->payment->member->full_name ?? 'Valued Member' }}</strong><br/>
                <span>Member No: {{ $receipt->payment->member->membership_number ?? 'Pending' }}</span><br/>
                <span>Phone: {{ $receipt->payment->member->phone ?? 'N/A' }}</span>
            </td>
            <td style="width: 50%; text-align: right;">
                <strong style="color: #64748b; font-size: 10px; text-transform: uppercase;">Payment Details:</strong><br/>
                <span>Reference: <strong>{{ $receipt->payment->payment_reference }}</strong></span><br/>
                <span>Method: {{ ucwords(str_replace('_', ' ', $receipt->payment->payment_method)) }}</span><br/>
                <span>Transaction Ref: {{ $receipt->payment->transaction_reference }}</span>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description / Purpose</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $receipt->invoice->purpose ?? 'Official TEVDA Service Fee' }}</td>
                <td style="text-align: right;">{{ number_format($receipt->amount, 2) }} {{ $receipt->currency }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        Total Paid: {{ number_format($receipt->amount, 2) }} {{ $receipt->currency }} (PAID IN FULL)
    </div>

    <div class="footer-note">
        Thank you for your partnership in building clean mobility across Tanzania.<br/>
        Official Motto: <strong>SMART DRIVERS SMART MOBILITY</strong> • www.tevda.or.tz
    </div>
</body>
</html>
