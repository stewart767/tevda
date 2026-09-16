<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Member ID Card — {{ $member->membership_number }}</title>
    <style>
        @page {
            margin: 0;
            size: 242.64pt 153.07pt; /* CR80 Standard ISO/IEC 7810 ID-1 Dimensions: 85.6mm x 53.98mm */
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #042f2e;
            color: #ffffff;
            font-size: 8px;
        }
        .page {
            width: 242.64pt;
            height: 153.07pt;
            position: relative;
            padding: 6px;
            overflow: hidden;
            box-sizing: border-box;
        }
        .page-break {
            page-break-before: always;
        }

        /* Card container styles */
        .card-box {
            width: 100%;
            height: 100%;
            border-radius: 7px;
            padding: 6px 7px 5px 7px;
            position: relative;
            background: #0f172a;
            border: 1px solid #10b981;
        }

        /* Themes */
        .theme-emerald .card-box {
            background: #091a18;
            border: 1px solid #10b981;
        }
        .theme-midnight .card-box {
            background: #090d16;
            border: 1px solid #d97706;
        }
        .theme-cyan .card-box {
            background: #051923;
            border: 1px solid #06b6d4;
        }
        .theme-clean .card-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #0f172a;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            padding-bottom: 3px;
            margin-bottom: 4px;
        }
        .theme-clean .header-table {
            border-bottom: 1px solid #e2e8f0;
        }
        .brand-title {
            font-size: 10px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1;
        }
        .theme-clean .brand-title {
            color: #064e3b;
        }
        .brand-sub {
            font-size: 5px;
            color: #34d399;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.1;
            letter-spacing: 0.3px;
        }
        .theme-midnight .brand-sub {
            color: #fbbf24;
        }
        .theme-cyan .brand-sub {
            color: #38bdf8;
        }
        .theme-clean .brand-sub {
            color: #059669;
        }
        .tier-badge {
            font-size: 5.5px;
            background: #065f46;
            color: #a7f3d0;
            padding: 1.5px 4px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
            white-space: nowrap;
        }
        .theme-midnight .tier-badge {
            background: #78350f;
            color: #fde68a;
        }
        .theme-cyan .tier-badge {
            background: #155e75;
            color: #cffafe;
        }
        .theme-clean .tier-badge {
            background: #e0f2fe;
            color: #0369a1;
        }

        /* Body layout */
        .content-table {
            width: 100%;
        }
        .photo-cell {
            width: 44px;
            vertical-align: top;
        }
        .photo-frame {
            width: 42px;
            height: 52px;
            border-radius: 4px;
            border: 1.2px solid #10b981;
            overflow: hidden;
            background: #1e293b;
            text-align: center;
        }
        .theme-midnight .photo-frame {
            border-color: #f59e0b;
        }
        .theme-cyan .photo-frame {
            border-color: #06b6d4;
        }
        .theme-clean .photo-frame {
            border-color: #059669;
            background: #e2e8f0;
        }
        .photo-img {
            width: 42px;
            height: 52px;
            object-fit: cover;
            display: block;
        }
        .photo-placeholder {
            line-height: 52px;
            font-size: 6.5px;
            font-weight: bold;
            color: #94a3b8;
        }

        .info-cell {
            padding-left: 6px;
            padding-right: 2px;
            vertical-align: top;
        }
        .info-label {
            font-size: 4.8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.5px;
            line-height: 1;
        }
        .theme-clean .info-label {
            color: #64748b;
        }
        .info-name {
            font-size: 8px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 2px;
            line-height: 1.1;
        }
        .theme-clean .info-name {
            color: #0f172a;
        }
        .info-number {
            font-size: 7px;
            font-weight: bold;
            font-family: 'Courier', monospace;
            color: #34d399;
            margin-bottom: 2px;
            line-height: 1;
        }
        .theme-midnight .info-number {
            color: #fbbf24;
        }
        .theme-cyan .info-number {
            color: #38bdf8;
        }
        .theme-clean .info-number {
            color: #059669;
        }
        .info-sub {
            font-size: 5.5px;
            color: #cbd5e1;
            line-height: 1.1;
        }
        .theme-clean .info-sub {
            color: #334155;
        }

        .qr-cell {
            width: 38px;
            text-align: right;
            vertical-align: bottom;
        }
        .qr-frame {
            width: 36px;
            height: 36px;
            background: #ffffff;
            padding: 1.5px;
            border-radius: 3px;
            display: inline-block;
        }
        .qr-img {
            width: 33px;
            height: 33px;
            display: block;
        }

        /* Footer */
        .footer-bar {
            margin-top: 3px;
            padding-top: 2px;
            border-top: 1px solid rgba(255,255,255,0.15);
            font-size: 4.8px;
            font-weight: bold;
            color: #fbbf24;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            text-align: center;
        }
        .theme-clean .footer-bar {
            border-top: 1px solid #e2e8f0;
            color: #047857;
        }

        /* Back side specific */
        .magnetic-stripe {
            height: 14px;
            background: #020617;
            margin: -6px -7px 5px -7px;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
            position: relative;
        }
        .stripe-text {
            color: #94a3b8;
            font-size: 4.5px;
            line-height: 14px;
            padding-left: 8px;
            font-family: 'Courier', monospace;
            letter-spacing: 0.5px;
        }
        .terms-box {
            font-size: 4.5px;
            line-height: 1.25;
            color: #94a3b8;
            margin-bottom: 4px;
            text-align: justify;
        }
        .theme-clean .terms-box {
            color: #475569;
        }
        .back-grid {
            width: 100%;
        }
        .signature-box {
            border-bottom: 1px solid #64748b;
            padding-bottom: 2px;
            margin-bottom: 2px;
            text-align: center;
        }
        .sig-name {
            font-size: 5px;
            font-weight: bold;
            color: #ffffff;
        }
        .theme-clean .sig-name {
            color: #0f172a;
        }
        .sig-title {
            font-size: 4.2px;
            color: #94a3b8;
        }
        .helpline-box {
            background: rgba(255,255,255,0.06);
            border-radius: 3px;
            padding: 3px 4px;
            font-size: 4.5px;
            color: #e2e8f0;
            line-height: 1.2;
        }
        .theme-clean .helpline-box {
            background: #e2e8f0;
            color: #1e293b;
        }
        .property-notice {
            font-size: 4px;
            color: #64748b;
            text-align: center;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
    </style>
</head>
<body class="theme-{{ $theme ?? 'emerald' }}">
    
    <!-- ==================== FRONT SIDE ==================== -->
    <div class="page">
        <div class="card-box">
            <!-- Header -->
            <table class="header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: middle;">
                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                            <tr>
                                @if(!empty($logoDataUri))
                                    <td style="vertical-align: middle; padding-right: 4px;">
                                        <img src="{{ $logoDataUri }}" style="height: 15px; max-width: 32px; object-fit: contain; display: block;" alt="Logo">
                                    </td>
                                @endif
                                <td style="vertical-align: middle;">
                                    <div class="brand-title">TEVDA</div>
                                    <div class="brand-sub">Tanzania EV Drivers Association</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <span class="tier-badge">{{ $member->category->name }}</span>
                    </td>
                </tr>
            </table>

            <!-- Content -->
            <table class="content-table" cellpadding="0" cellspacing="0">
                <tr>
                    <!-- Photo -->
                    <td class="photo-cell">
                        <div class="photo-frame">
                            @if(!empty($photoDataUri))
                                <img src="{{ $photoDataUri }}" class="photo-img" alt="{{ $member->full_name }}">
                            @else
                                <div class="photo-placeholder">PHOTO</div>
                            @endif
                        </div>
                    </td>

                    <!-- Member Details -->
                    <td class="info-cell">
                        <div class="info-label">Member Name</div>
                        <div class="info-name">{{ $member->full_name }}</div>

                        <div class="info-label">Membership Number</div>
                        <div class="info-number">{{ $member->membership_number }}</div>

                        <div class="info-label">Region / Territory</div>
                        <div class="info-sub">
                            {{ $member->region?->name ?? 'Tanzania' }}
                            @if($member->district) • {{ $member->district->name }} @endif
                        </div>

                        <div style="margin-top: 2px;">
                            <span class="info-label" style="display: inline;">Valid Thru: </span>
                            <span class="info-sub" style="font-weight: bold; color: #fbbf24;">
                                {{ $member->expiry_date ? $member->expiry_date->format('m/Y') : 'ACTIVE' }}
                            </span>
                        </div>
                    </td>

                    <!-- QR Code -->
                    <td class="qr-cell">
                        <div class="qr-frame">
                            <img src="{{ $qrCodeUri }}" class="qr-img" alt="QR">
                        </div>
                        <div style="font-size: 4px; color: #94a3b8; text-align: center; margin-top: 1px;">SCAN TO VERIFY</div>
                    </td>
                </tr>
            </table>

            <!-- Footer Motto -->
            <div class="footer-bar">
                SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ
            </div>
        </div>
    </div>

    <!-- ==================== BACK SIDE ==================== -->
    @if(!empty($showBack))
    <div class="page page-break">
        <div class="card-box">
            <!-- Magnetic Stripe Header -->
            <div class="magnetic-stripe">
                <div class="stripe-text">CARD ID: {{ $member->card?->card_number ?? ('CARD-' . $member->membership_number) }}</div>
            </div>

            <!-- Terms & Conditions -->
            <div class="terms-box">
                This official identification card certifies that the cardholder is a registered and certified member of TEVDA. 
                This card is non-transferable and must be presented upon request during official operations, inspections, or association events.
            </div>

            <!-- Back Info Grid -->
            <table class="back-grid" cellpadding="0" cellspacing="0">
                <tr>
                    <!-- Left: Signatory -->
                    <td style="width: 48%; vertical-align: top; padding-right: 4px;">
                        <div class="info-label" style="margin-bottom: 2px;">Authorized Signature</div>
                        <div class="signature-box">
                            <div style="font-family: 'Brush Script MT', 'Dancing Script', cursive, sans-serif; font-size: 9px; color: #34d399; line-height: 1;">
                                Charles Mwansasu
                            </div>
                        </div>
                        <div class="sig-name">Dr. Charles Mwansasu</div>
                        <div class="sig-title">Founding Chairperson • TEVDA</div>
                    </td>

                    <!-- Right: Emergency & Contact -->
                    <td style="width: 52%; vertical-align: top;">
                        <div class="helpline-box">
                            <strong style="color: #34d399; font-size: 4.8px;">HEADQUARTERS & HELPLINE</strong><br>
                            Dar es Salaam, United Republic of Tanzania<br>
                            Helpline: +255 700 000 000 / 022 200 0000<br>
                            Email: info@tevda.or.tz • portal.tevda.or.tz
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Return Notice -->
            <div class="property-notice">
                Property of TEVDA. If found, please return to any TEVDA Regional Office or Police Station.
            </div>
        </div>
    </div>
    @endif

</body>
</html>
