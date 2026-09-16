<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $invoice->invoice_number }}</title>
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
        .invoice-no {
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
            color: #065f46;
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
        .payment-instructions {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 25px;
            font-size: 11px;
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
                <div style="font-size: 11px; font-weight: bold; color: #1e293b;">{{ \App\Models\Setting::get('site_name', 'Tanzania Electric Vehicle Drivers Association') }}</div>
                <div style="font-size: 10px; color: #64748b;">{{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}</div>
                <div style="font-size: 10px; color: #64748b;">Telephone: {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }} • {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }}</div>
            </td>
            <td style="text-align: right;">
                <div style="font-size: 18px; font-weight: bold; color: #065f46;">OFFICIAL INVOICE</div>
                <div class="invoice-no">{{ $invoice->invoice_number }}</div>
                @if(!empty($invoice->control_number))
                    <div style="margin-top: 4px; padding: 4px 8px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 4px; font-family: monospace; font-size: 12px; font-weight: bold; color: #065f46; display: inline-block;">
                        CONTROL NO: {{ $invoice->control_number }}
                    </div>
                @endif
                <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                    Issue Date: {{ $invoice->created_at ? $invoice->created_at->format('d F Y') : date('d F Y') }}<br/>
                    Due Date: {{ $invoice->due_date ? $invoice->due_date->format('d F Y') : 'Immediate' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <strong style="color: #64748b; font-size: 10px; text-transform: uppercase;">Billed To:</strong><br/>
                <strong style="font-size: 13px;">{{ $invoice->member->full_name ?? ($invoice->user->name ?? 'Valued Member') }}</strong><br/>
                <span>Member No: {{ $invoice->member->membership_number ?? 'Pending' }}</span><br/>
                <span>Phone: {{ $invoice->member->phone ?? ($invoice->user->phone ?? 'N/A') }}</span><br/>
                <span>Location: {{ $invoice->member->region->name ?? 'Tanzania' }}</span>
            </td>
            <td style="width: 50%; text-align: right;">
                <strong style="color: #64748b; font-size: 10px; text-transform: uppercase;">Status:</strong><br/>
                <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; background: {{ $invoice->status === 'paid' ? '#d1fae5; color: #065f46;' : '#fef3c7; color: #92400e;' }}">
                    {{ strtoupper($invoice->status) }}
                </span>
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
                <td>{{ $invoice->purpose ?? ($invoice->description ?? 'Official Association Tariff / Membership Registration Fee') }}</td>
                <td style="text-align: right;">{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        Total Amount: {{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}
    </div>

    <div class="payment-instructions">
        <strong>Bank & Mobile Money Payment Instructions:</strong><br/>
        Please pay using Control Number <strong>{{ $invoice->control_number }}</strong> via Vodacom M-Pesa (*150*00# -> Lipa Namba -> Paybill), Tigo Pesa, Airtel Money, or bank deposit (CRDB / NMB / NBC). Once paid, your official Certificate of Membership and Smart ID Card will be generated and issued.
    </div>

    <div class="footer-note">
        Official Motto: <strong>{{ \App\Models\Setting::get('site_motto', 'SMART DRIVERS SMART MOBILITY') }}</strong> • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}<br/>
        Official Address: {{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}
    </div>
</body>
</html>
