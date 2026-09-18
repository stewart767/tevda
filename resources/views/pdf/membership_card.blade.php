<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TEVDA Member ID Card — {{ $member->membership_number }}</title>
    <style>
        @page {
            margin: 0;
            size: 242.64pt 153.07pt; /* CR80 Standard ISO/IEC 7810 ID-1: 85.6mm x 53.98mm */
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }
        html, body {
            width: 242.64pt;
            height: 153.07pt;
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            background: #022c22;
            color: #ffffff;
            font-size: 7.5px;
        }

        .card-page {
            width: 242.64pt;
            height: 153.07pt;
            position: relative;
            page-break-after: always;
            overflow: hidden;
            background: #022c22;
        }
        .card-page.last-page {
            page-break-after: avoid;
        }

        /* Card Frame Container */
        .card-inner {
            position: absolute;
            top: 4pt;
            left: 4pt;
            width: 234.64pt;
            height: 145.07pt;
            border-radius: 6pt;
            overflow: hidden;
            border: 1.2pt solid #10b981;
            background: #064e3b;
        }

        /* Themes */
        .theme-emerald .card-page { background: #022c22; }
        .theme-emerald .card-inner {
            background: #042f2e;
            border: 1.2pt solid #10b981;
        }
        .theme-emerald .accent-text { color: #34d399; }
        .theme-emerald .highlight-pill { background: #065f46; color: #a7f3d0; border: 0.5pt solid #10b981; }
        .theme-emerald .number-pill { background: #022c22; color: #f59e0b; border: 0.5pt solid #f59e0b; }

        .theme-midnight .card-page { background: #030712; }
        .theme-midnight .card-inner {
            background: #0b0f19;
            border: 1.2pt solid #f59e0b;
        }
        .theme-midnight .accent-text { color: #fbbf24; }
        .theme-midnight .highlight-pill { background: #451a03; color: #fde68a; border: 0.5pt solid #f59e0b; }
        .theme-midnight .number-pill { background: #030712; color: #fbbf24; border: 0.5pt solid #d97706; }

        .theme-cyan .card-page { background: #031926; }
        .theme-cyan .card-inner {
            background: #052033;
            border: 1.2pt solid #06b6d4;
        }
        .theme-cyan .accent-text { color: #38bdf8; }
        .theme-cyan .highlight-pill { background: #164e63; color: #cffafe; border: 0.5pt solid #06b6d4; }
        .theme-cyan .number-pill { background: #031926; color: #38bdf8; border: 0.5pt solid #0284c7; }

        .theme-clean .card-page { background: #e2e8f0; }
        .theme-clean .card-inner {
            background: #ffffff;
            border: 1.2pt solid #059669;
            color: #0f172a;
        }
        .theme-clean .accent-text { color: #047857; }
        .theme-clean .highlight-pill { background: #d1fae5; color: #065f46; border: 0.5pt solid #059669; }
        .theme-clean .number-pill { background: #f0fdf4; color: #047857; border: 0.5pt solid #059669; }

        /* Security Guilloche Watermark Lines */
        .watermark-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.08;
            pointer-events: none;
        }

        /* Tanzania Flag Mini Banner */
        .tz-banner {
            height: 2.5pt;
            width: 100%;
            overflow: hidden;
            font-size: 0;
            line-height: 0;
        }
        .tz-green { display: inline-block; width: 33.3%; height: 2.5pt; background: #1eb53a; }
        .tz-yellow { display: inline-block; width: 33.4%; height: 2.5pt; background: #fcd116; }
        .tz-blue { display: inline-block; width: 33.3%; height: 2.5pt; background: #00a3dd; }

        /* Header Bar */
        .card-header-table {
            width: 100%;
            padding: 3pt 6pt 2pt 6pt;
            border-bottom: 0.6pt solid rgba(255,255,255,0.15);
        }
        .theme-clean .card-header-table {
            border-bottom: 0.6pt solid #e2e8f0;
        }
        .org-logo {
            height: 18pt;
            max-width: 32pt;
            display: block;
        }
        .org-badge {
            width: 18pt;
            height: 18pt;
            line-height: 18pt;
            background: #10b981;
            color: #ffffff;
            text-align: center;
            font-weight: 900;
            font-size: 8pt;
            border-radius: 3pt;
        }
        .org-name-primary {
            font-size: 9.5pt;
            font-weight: 900;
            line-height: 1;
            letter-spacing: 0.6pt;
            color: #ffffff;
        }
        .theme-clean .org-name-primary {
            color: #064e3b;
        }
        .org-name-sub {
            font-size: 4.1pt;
            font-weight: bold;
            line-height: 1.15;
            text-transform: uppercase;
            letter-spacing: 0.2pt;
        }
        .member-tier-badge {
            display: inline-block;
            padding: 1.5pt 4.5pt;
            border-radius: 2.5pt;
            font-size: 5.5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.4pt;
            white-space: nowrap;
        }

        /* Body Section */
        .card-body-table {
            width: 100%;
            padding: 3.5pt 6pt 2pt 6pt;
        }

        /* Passport Photo Column */
        .photo-column {
            width: 48pt;
            vertical-align: top;
        }
        .photo-box {
            width: 46pt;
            height: 56pt;
            border-radius: 3pt;
            border: 1pt solid #10b981;
            overflow: hidden;
            background: #0f172a;
            position: relative;
        }
        .theme-midnight .photo-box { border-color: #f59e0b; }
        .theme-cyan .photo-box { border-color: #06b6d4; }
        .theme-clean .photo-box { border-color: #059669; background: #e2e8f0; }

        .photo-image {
            width: 46pt;
            height: 56pt;
            display: block;
        }
        .photo-placeholder-box {
            width: 46pt;
            height: 56pt;
            text-align: center;
            line-height: 56pt;
            font-size: 6pt;
            font-weight: bold;
            color: #94a3b8;
            background: #1e293b;
        }

        /* Member Info Column */
        .info-column {
            padding-left: 6pt;
            padding-right: 4pt;
            vertical-align: top;
        }
        .label-micro {
            font-size: 4.2pt;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.3pt;
            line-height: 1;
            margin-bottom: 0.5pt;
        }
        .theme-clean .label-micro {
            color: #64748b;
        }
        .value-name {
            font-size: 8.5pt;
            font-weight: 900;
            line-height: 1.1;
            color: #ffffff;
            margin-bottom: 2pt;
            text-transform: uppercase;
            letter-spacing: 0.2pt;
        }
        .theme-clean .value-name {
            color: #0f172a;
        }
        .value-number-badge {
            display: inline-block;
            font-family: 'Courier', monospace;
            font-size: 7.2pt;
            font-weight: bold;
            letter-spacing: 0.4pt;
            padding: 1pt 3pt;
            border-radius: 2pt;
            margin-bottom: 2.5pt;
        }
        .value-meta {
            font-size: 5.5pt;
            line-height: 1.2;
            color: #cbd5e1;
            margin-bottom: 1.5pt;
        }
        .theme-clean .value-meta {
            color: #334155;
        }

        /* QR Column */
        .qr-column {
            width: 42pt;
            vertical-align: top;
            text-align: right;
        }
        .qr-wrapper {
            background: #ffffff;
            padding: 2pt;
            border-radius: 3pt;
            display: inline-block;
            border: 0.5pt solid #cbd5e1;
        }
        .qr-image {
            width: 38pt;
            height: 38pt;
            display: block;
        }
        .qr-caption {
            font-size: 4.2pt;
            font-weight: bold;
            color: #94a3b8;
            text-align: center;
            margin-top: 1.5pt;
            letter-spacing: 0.3pt;
            text-transform: uppercase;
        }
        .theme-clean .qr-caption {
            color: #64748b;
        }

        /* Micro Security Strip */
        .micro-security-strip {
            position: absolute;
            bottom: 13pt;
            left: 0;
            right: 0;
            height: 5pt;
            line-height: 5pt;
            background: rgba(0,0,0,0.35);
            font-size: 3.2pt;
            text-transform: uppercase;
            letter-spacing: 0.6pt;
            text-align: center;
            color: #6ee7b7;
            overflow: hidden;
            white-space: nowrap;
        }
        .theme-midnight .micro-security-strip { color: #fde68a; }
        .theme-cyan .micro-security-strip { color: #7dd3fc; }
        .theme-clean .micro-security-strip { background: #e2e8f0; color: #047857; }

        /* Card Footer */
        .card-footer-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12pt;
            line-height: 12pt;
            background: rgba(0,0,0,0.6);
            border-top: 0.5pt solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 4.8pt;
            font-weight: bold;
            color: #f59e0b;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .theme-clean .card-footer-bar {
            background: #f1f5f9;
            border-top: 0.5pt solid #cbd5e1;
            color: #047857;
        }

        /* ==================== BACK SIDE STYLES ==================== */
        .magnetic-stripe {
            height: 14pt;
            background: #020617;
            border-bottom: 0.6pt solid rgba(255,255,255,0.12);
            position: relative;
        }
        .stripe-content-table {
            width: 100%;
            height: 14pt;
            border-collapse: collapse;
        }
        .stripe-content-table td {
            padding: 0 6pt;
        }
        .stripe-serial {
            font-family: 'Courier', monospace;
            font-size: 5.5pt;
            font-weight: bold;
            color: #cbd5e1;
            letter-spacing: 0.4pt;
        }
        .stripe-tag {
            font-size: 4.8pt;
            font-weight: bold;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 0.4pt;
            text-align: right;
        }

        .back-body-table {
            width: 100%;
            border-collapse: collapse;
        }
        .back-body-cell {
            padding: 4pt 6pt 2pt 6pt;
            vertical-align: top;
        }
        .terms-paragraph {
            font-size: 4.5pt;
            line-height: 1.3;
            color: #cbd5e1;
            margin-bottom: 4.5pt;
            text-align: left;
            word-wrap: break-word;
        }
        .theme-clean .terms-paragraph {
            color: #334155;
        }

        .back-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3.5pt;
        }
        .signatory-box {
            border-bottom: 0.5pt solid #475569;
            padding-bottom: 1pt;
            margin-bottom: 1.5pt;
            text-align: center;
        }
        .signature-font {
            font-family: 'DejaVu Sans', cursive, sans-serif;
            font-style: italic;
            font-size: 9pt;
            font-weight: bold;
            line-height: 1;
            color: #34d399;
        }
        .theme-midnight .signature-font { color: #fbbf24; }
        .theme-cyan .signature-font { color: #38bdf8; }
        .theme-clean .signature-font { color: #047857; }

        .signatory-name {
            font-size: 5.5pt;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            line-height: 1.1;
        }
        .theme-clean .signatory-name { color: #0f172a; }

        .signatory-title {
            font-size: 4.2pt;
            color: #94a3b8;
            text-align: center;
            line-height: 1;
        }
        .theme-clean .signatory-title { color: #64748b; }

        .helpline-container {
            background: rgba(0,0,0,0.35);
            border-radius: 3pt;
            padding: 3.5pt 4.5pt;
            border: 0.5pt solid rgba(255,255,255,0.1);
            font-size: 4.5pt;
            line-height: 1.25;
            color: #e2e8f0;
        }
        .theme-clean .helpline-container {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        /* Barcode Representation */
        .barcode-box {
            text-align: center;
            margin-top: 3pt;
            margin-bottom: 2pt;
        }
        .barcode-bars {
            height: 8.5pt;
            letter-spacing: 0.8pt;
            font-family: 'Courier', monospace;
            font-size: 6.8pt;
            font-weight: bold;
            color: #94a3b8;
            line-height: 1;
        }
        .barcode-number {
            font-family: 'Courier', monospace;
            font-size: 4.2pt;
            color: #cbd5e1;
            letter-spacing: 0.6pt;
            line-height: 1;
            margin-top: 1pt;
        }
        .theme-clean .barcode-number { color: #64748b; }

        .back-micro-strip {
            position: absolute;
            bottom: 12pt;
            left: 0;
            right: 0;
            height: 5.5pt;
            line-height: 5.5pt;
            background: rgba(0,0,0,0.35);
            font-size: 3.2pt;
            text-transform: uppercase;
            letter-spacing: 0.6pt;
            text-align: center;
            color: #6ee7b7;
            overflow: hidden;
            white-space: nowrap;
        }
        .theme-midnight .back-micro-strip { color: #fde68a; }
        .theme-cyan .back-micro-strip { color: #7dd3fc; }
        .theme-clean .back-micro-strip { background: #e2e8f0; color: #047857; }

        .return-notice-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12pt;
            line-height: 12pt;
            background: rgba(0,0,0,0.6);
            border-top: 0.5pt solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 4.2pt;
            font-weight: bold;
            color: #f59e0b;
            letter-spacing: 0.4pt;
            text-transform: uppercase;
        }
        .theme-clean .return-notice-bar {
            background: #f1f5f9;
            border-top: 0.5pt solid #cbd5e1;
            color: #047857;
        }
    </style>
</head>
<body class="theme-{{ $theme ?? 'emerald' }}">

    <!-- ==================== FRONT SIDE (PAGE 1) ==================== -->
    <div class="card-page {{ empty($showBack) ? 'last-page' : '' }}">
        <div class="card-inner">
            <!-- Tanzanian National Flag Stripe -->
            <div class="tz-banner">
                <span class="tz-green"></span><span class="tz-yellow"></span><span class="tz-blue"></span>
            </div>

            <!-- Header -->
            <table class="card-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align: middle; width: 70%;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                @if(!empty($logoDataUri))
                                    <td style="vertical-align: middle; padding-right: 4pt;">
                                        <img src="{{ $logoDataUri }}" class="org-logo" alt="Logo">
                                    </td>
                                @else
                                    <td style="vertical-align: middle; padding-right: 4pt;">
                                        <div class="org-badge">TEV</div>
                                    </td>
                                @endif
                                <td style="vertical-align: middle;">
                                    <div class="org-name-primary">TEVDA</div>
                                    <div class="org-name-sub accent-text">Tanzania Electric Vehicles Drivers Association</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="text-align: right; vertical-align: middle; width: 30%;">
                        <span class="member-tier-badge highlight-pill">
                            {{ $member->category->name }}
                        </span>
                    </td>
                </tr>
            </table>

            <!-- Body Details -->
            <table class="card-body-table" cellpadding="0" cellspacing="0">
                <tr>
                    <!-- Member Passport Photo -->
                    <td class="photo-column">
                        <div class="photo-box">
                            @if(!empty($photoDataUri))
                                <img src="{{ $photoDataUri }}" class="photo-image" alt="{{ $member->full_name }}">
                            @else
                                <div class="photo-placeholder-box">PHOTO</div>
                            @endif
                        </div>
                    </td>

                    <!-- Member Info -->
                    <td class="info-column">
                        <div class="label-micro">Member Name / Jina</div>
                        <div class="value-name">{{ $member->full_name }}</div>

                        <div class="label-micro">Member ID / Namba ya Utambulisho</div>
                        <div>
                            <span class="value-number-badge number-pill">{{ $member->membership_number }}</span>
                        </div>

                        <div class="label-micro">Region & Territory</div>
                        <div class="value-meta">
                            <strong>{{ $member->region?->name ?? 'Tanzania' }}</strong>
                            @if($member->district) • {{ $member->district->name }} @endif
                        </div>

                        <div class="label-micro">Validity / Hali</div>
                        <div class="value-meta">
                            <span style="color: #f59e0b; font-weight: bold;">
                                {{ $member->expiry_date ? 'EXP: ' . $member->expiry_date->format('m/Y') : 'ACTIVE' }}
                            </span>
                            <span class="accent-text" style="font-weight: bold; margin-left: 3pt;">• VERIFIED</span>
                        </div>
                    </td>

                    <!-- Verification QR Code -->
                    <td class="qr-column">
                        <div class="qr-wrapper">
                            <img src="{{ $qrCodeUri }}" class="qr-image" alt="QR">
                        </div>
                        <div class="qr-caption">Scan to Verify</div>
                    </td>
                </tr>
            </table>

            <!-- Micro Security Strip -->
            <div class="micro-security-strip">
                • TANZANIA ELECTRIC VEHICLES DRIVERS ASSOCIATION • OFFICIAL SECURE SMART ID • TEVDA CERTIFIED •
            </div>

            <!-- Footer Motto -->
            <div class="card-footer-bar">
                SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ
            </div>
        </div>
    </div>

    <!-- ==================== BACK SIDE (PAGE 2) ==================== -->
    @if(!empty($showBack))
    <div class="card-page last-page">
        <div class="card-inner">
            <!-- Magnetic Stripe -->
            <div class="magnetic-stripe">
                <table class="stripe-content-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="stripe-serial" style="vertical-align: middle; width: 62%;">
                            CARD ID: {{ $member->card?->card_number ?? ('CARD-' . $member->membership_number) }}
                        </td>
                        <td class="stripe-tag" style="vertical-align: middle; width: 38%; text-align: right;">
                            OFFICIAL SMART BADGE
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Back Body Content -->
            <table class="back-body-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="back-body-cell">
                        <!-- Legal & Terms -->
                        <div class="terms-paragraph">
                            This smart ID card certifies that the cardholder is a registered and compliant member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations, inspections, or association activities.
                        </div>

                        <!-- 2-Column: Signatory on Left, Helpline on Right -->
                        <table class="back-details-table" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width: 48%; vertical-align: top; padding-right: 4pt;">
                                    <div class="label-micro" style="text-align: center; margin-bottom: 1.5pt;">Authorized Signatory</div>
                                    <div class="signatory-box">
                                        @if(!empty($signatureDataUri))
                                            <img src="{{ $signatureDataUri }}" style="max-height: 13pt; max-width: 65pt; object-fit: contain; display: inline-block;" alt="Signature">
                                        @else
                                            <span class="signature-font">{{ \App\Models\Setting::get('chairman_name', 'Charles Mwansasu') }}</span>
                                        @endif
                                    </div>
                                    <div class="signatory-name">{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}</div>
                                    <div class="signatory-title">{{ \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }} • TEVDA</div>
                                </td>

                                <td style="width: 52%; vertical-align: top; padding-left: 2pt;">
                                    <div class="helpline-container">
                                        <strong class="accent-text" style="font-size: 5pt; display: block; margin-bottom: 1pt;">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                                        {{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}<br>
                                        <strong>Helpline:</strong> {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}<br>
                                        <strong>Support:</strong> {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }} • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Barcode Representation -->
                        <div class="barcode-box">
                            <div class="barcode-bars">||| | |||| | ||| |||| | || ||| |||| | || | ||| |||</div>
                            <div class="barcode-number">
                                *{{ $member->membership_number }}*
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Micro Security Strip -->
            <div class="back-micro-strip">
                • PROPERTY OF TEVDA • ENCRYPTED SMART ID • ISO/IEC 7810 ID-1 •
            </div>

            <!-- Return Notice Footer Bar -->
            <div class="return-notice-bar">
                IF FOUND PLEASE RETURN TO ANY TEVDA OFFICE OR POLICE STATION
            </div>
        </div>
    </div>
    @endif

</body>
</html>
