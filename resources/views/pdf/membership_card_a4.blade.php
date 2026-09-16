<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TEVDA Member ID Cards — Printable Sheet</title>
    <style>
        @page {
            margin: 10mm 10mm;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            background: #ffffff;
            color: #0f172a;
            font-size: 7.5px;
        }

        .header-doc {
            text-align: center;
            margin-bottom: 8pt;
            border-bottom: 1.5pt solid #064e3b;
            padding-bottom: 4pt;
        }
        .doc-title {
            font-size: 11pt;
            font-weight: 900;
            color: #064e3b;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
        }
        .doc-sub {
            font-size: 6.5pt;
            color: #64748b;
            margin-top: 1pt;
        }

        .pair-row {
            margin-bottom: 12pt;
            page-break-inside: avoid;
        }

        .cut-guide-container {
            border: 0.8pt dashed #94a3b8;
            padding: 5pt;
            background: #f8fafc;
            border-radius: 5pt;
        }

        .guide-label {
            font-size: 5.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3pt;
            letter-spacing: 0.4pt;
        }

        /* Exact CR80 dimensions in points: 242.64pt x 153.07pt (85.6mm x 54mm) */
        .card-box {
            width: 242.64pt;
            height: 153.07pt;
            position: relative;
            border-radius: 6pt;
            overflow: hidden;
            background: #022c22;
            color: #ffffff;
            border: 1.2pt solid #10b981;
            display: inline-block;
            vertical-align: top;
            text-align: left;
        }

        /* Themes */
        .theme-emerald .card-box { background: #042f2e; border: 1.2pt solid #10b981; color: #ffffff; }
        .theme-midnight .card-box { background: #0b0f19; border: 1.2pt solid #f59e0b; color: #ffffff; }
        .theme-cyan .card-box { background: #052033; border: 1.2pt solid #06b6d4; color: #ffffff; }
        .theme-clean .card-box { background: #ffffff; border: 1.2pt solid #059669; color: #0f172a; }

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
            height: 17pt;
            max-width: 30pt;
            display: block;
        }
        .org-badge {
            width: 17pt;
            height: 17pt;
            line-height: 17pt;
            background: #10b981;
            color: #ffffff;
            text-align: center;
            font-weight: 900;
            font-size: 7.5pt;
            border-radius: 3pt;
        }
        .org-name-primary {
            font-size: 9pt;
            font-weight: 900;
            line-height: 1;
            letter-spacing: 0.5pt;
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
            color: #34d399;
        }
        .theme-midnight .org-name-sub { color: #fbbf24; }
        .theme-cyan .org-name-sub { color: #38bdf8; }
        .theme-clean .org-name-sub { color: #047857; }

        .member-tier-badge {
            display: inline-block;
            padding: 1.2pt 4pt;
            border-radius: 2.5pt;
            font-size: 5pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.3pt;
            background: #065f46;
            color: #a7f3d0;
            border: 0.5pt solid #10b981;
            white-space: nowrap;
        }
        .theme-midnight .member-tier-badge { background: #451a03; color: #fde68a; border-color: #f59e0b; }
        .theme-cyan .member-tier-badge { background: #164e63; color: #cffafe; border-color: #06b6d4; }
        .theme-clean .member-tier-badge { background: #d1fae5; color: #065f46; border-color: #059669; }

        /* Body Section */
        .card-body-table {
            width: 100%;
            padding: 3.5pt 6pt 2pt 6pt;
        }

        .photo-column {
            width: 46pt;
            vertical-align: top;
        }
        .photo-box {
            width: 44pt;
            height: 54pt;
            border-radius: 3pt;
            border: 1pt solid #10b981;
            overflow: hidden;
            background: #0f172a;
        }
        .theme-midnight .photo-box { border-color: #f59e0b; }
        .theme-cyan .photo-box { border-color: #06b6d4; }
        .theme-clean .photo-box { border-color: #059669; background: #e2e8f0; }

        .photo-image {
            width: 44pt;
            height: 54pt;
            display: block;
        }
        .photo-placeholder-box {
            width: 44pt;
            height: 54pt;
            text-align: center;
            line-height: 54pt;
            font-size: 6pt;
            font-weight: bold;
            color: #94a3b8;
            background: #1e293b;
        }

        .info-column {
            padding-left: 5pt;
            padding-right: 3pt;
            vertical-align: top;
        }
        .label-micro {
            font-size: 4pt;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.3pt;
            line-height: 1;
            margin-bottom: 0.5pt;
        }
        .theme-clean .label-micro { color: #64748b; }

        .value-name {
            font-size: 8pt;
            font-weight: 900;
            line-height: 1.1;
            color: #ffffff;
            margin-bottom: 2pt;
            text-transform: uppercase;
        }
        .theme-clean .value-name { color: #0f172a; }

        .value-number-badge {
            display: inline-block;
            font-family: 'Courier', monospace;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.3pt;
            padding: 1pt 3pt;
            border-radius: 2pt;
            margin-bottom: 2pt;
            background: #022c22;
            color: #f59e0b;
            border: 0.5pt solid #f59e0b;
        }
        .theme-midnight .value-number-badge { background: #030712; color: #fbbf24; border-color: #d97706; }
        .theme-cyan .value-number-badge { background: #031926; color: #38bdf8; border-color: #0284c7; }
        .theme-clean .value-number-badge { background: #f0fdf4; color: #047857; border-color: #059669; }

        .value-meta {
            font-size: 5.2pt;
            line-height: 1.2;
            color: #cbd5e1;
            margin-bottom: 1pt;
        }
        .theme-clean .value-meta { color: #334155; }

        .qr-column {
            width: 40pt;
            vertical-align: top;
            text-align: right;
        }
        .qr-wrapper {
            background: #ffffff;
            padding: 1.5pt;
            border-radius: 2.5pt;
            display: inline-block;
            border: 0.5pt solid #cbd5e1;
        }
        .qr-image {
            width: 36pt;
            height: 36pt;
            display: block;
        }
        .qr-caption {
            font-size: 3.8pt;
            font-weight: bold;
            color: #94a3b8;
            text-align: center;
            margin-top: 1pt;
            text-transform: uppercase;
        }

        .micro-security-strip {
            position: absolute;
            bottom: 13pt;
            left: 0;
            right: 0;
            height: 5pt;
            line-height: 5pt;
            background: rgba(0,0,0,0.35);
            font-size: 3pt;
            text-transform: uppercase;
            letter-spacing: 0.5pt;
            text-align: center;
            color: #6ee7b7;
            overflow: hidden;
            white-space: nowrap;
        }
        .theme-midnight .micro-security-strip { color: #fde68a; }
        .theme-cyan .micro-security-strip { color: #7dd3fc; }
        .theme-clean .micro-security-strip { background: #e2e8f0; color: #047857; }

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
            font-size: 4.5pt;
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

        /* Back side specifics */
        .magnetic-stripe {
            height: 15pt;
            background: #020617;
            border-bottom: 1pt solid #1e293b;
        }
        .stripe-content-table {
            width: 100%;
            height: 15pt;
            padding: 0 6pt;
        }
        .stripe-serial {
            font-family: 'Courier', monospace;
            font-size: 5pt;
            font-weight: bold;
            color: #cbd5e1;
        }
        .stripe-tag {
            font-size: 4.5pt;
            font-weight: bold;
            color: #10b981;
            text-transform: uppercase;
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
            margin-bottom: 4pt;
            text-align: left;
            word-wrap: break-word;
        }
        .theme-clean .terms-paragraph { color: #334155; }

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

        .barcode-box {
            text-align: center;
            margin-top: 3pt;
            margin-bottom: 2pt;
        }
        .barcode-bars {
            font-family: 'Courier', monospace;
            font-size: 6.8pt;
            font-weight: bold;
            color: #94a3b8;
            letter-spacing: 0.8pt;
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

    <div class="header-doc">
        <div class="doc-title">Tanzania Electric Vehicles Drivers Association (TEVDA)</div>
        <div class="doc-sub">Official Member ID Cards • ISO/IEC 7810 ID-1 CR80 Format (85.6mm × 54mm) • Standard Double-Sided Print Sheet</div>
    </div>

    @foreach($preparedMembers as $index => $item)
        @php
            $m = $item['member'];
            $qrCodeUri = $item['qrCodeUri'];
            $photoDataUri = $item['photoDataUri'];
        @endphp

        <div class="pair-row">
            <div class="cut-guide-container">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <!-- FRONT SIDE -->
                        <td style="width: 50%; vertical-align: top; text-align: center; padding-right: 6pt;">
                            <div class="guide-label">FRONT SIDE (CARD #{{ $index + 1 }})</div>
                            
                            <div class="card-box">
                                <div class="tz-banner">
                                    <span class="tz-green"></span><span class="tz-yellow"></span><span class="tz-blue"></span>
                                </div>

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
                                                        <div class="org-name-sub">Tanzania Electric Vehicles Drivers Association</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="text-align: right; vertical-align: middle; width: 30%;">
                                            <span class="member-tier-badge">
                                                {{ $m->category->name }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>

                                <table class="card-body-table" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="photo-column">
                                            <div class="photo-box">
                                                @if(!empty($photoDataUri))
                                                    <img src="{{ $photoDataUri }}" class="photo-image" alt="{{ $m->full_name }}">
                                                @else
                                                    <div class="photo-placeholder-box">PHOTO</div>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="info-column">
                                            <div class="label-micro">Member Name / Jina</div>
                                            <div class="value-name">{{ $m->full_name }}</div>

                                            <div class="label-micro">Member ID / Namba</div>
                                            <div>
                                                <span class="value-number-badge">{{ $m->membership_number }}</span>
                                            </div>

                                            <div class="label-micro">Region & Territory</div>
                                            <div class="value-meta">
                                                <strong>{{ $m->region?->name ?? 'Tanzania' }}</strong>
                                                @if($m->district) • {{ $m->district->name }} @endif
                                            </div>

                                            <div class="label-micro">Validity / Hali</div>
                                            <div class="value-meta">
                                                <span style="color: #f59e0b; font-weight: bold;">
                                                    {{ $m->expiry_date ? 'EXP: ' . $m->expiry_date->format('m/Y') : 'ACTIVE' }}
                                                </span>
                                                <span style="color: #34d399; font-weight: bold; margin-left: 2pt;">• VERIFIED</span>
                                            </div>
                                        </td>

                                        <td class="qr-column">
                                            <div class="qr-wrapper">
                                                <img src="{{ $qrCodeUri }}" class="qr-image" alt="QR">
                                            </div>
                                            <div class="qr-caption">Scan to Verify</div>
                                        </td>
                                    </tr>
                                </table>

                                <div class="micro-security-strip">
                                    • TANZANIA ELECTRIC VEHICLES DRIVERS ASSOCIATION • OFFICIAL SECURE SMART ID • TEVDA CERTIFIED •
                                </div>

                                <div class="card-footer-bar">
                                    SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ
                                </div>
                            </div>
                        </td>

                        <!-- FOLD/CUT GUIDE SEPARATOR -->
                        <td style="width: 1px; border-left: 1pt dashed #94a3b8;"></td>

                        <!-- BACK SIDE -->
                        <td style="width: 50%; vertical-align: top; text-align: center; padding-left: 6pt;">
                            <div class="guide-label">BACK SIDE (CARD #{{ $index + 1 }})</div>

                            <div class="card-box">
                                <div class="magnetic-stripe">
                                    <table class="stripe-content-table" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="stripe-serial" style="vertical-align: middle;">
                                                CARD ID: {{ $m->card?->card_number ?? ('CARD-' . $m->membership_number) }}
                                            </td>
                                            <td class="stripe-tag" style="vertical-align: middle;">
                                                OFFICIAL BADGE
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <table class="back-body-table" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="back-body-cell">
                                            <div class="terms-paragraph">
                                                This official smart ID card certifies that the cardholder is a registered and compliant member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations.
                                            </div>

                                            <table class="back-details-table" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="width: 48%; vertical-align: top; padding-right: 4pt;">
                                                        <div class="label-micro" style="text-align: center; margin-bottom: 1.5pt;">Authorized Signatory</div>
                                                        <div class="signatory-box">
                                                            <span class="signature-font">{{ \App\Models\Setting::get('chairman_name', 'Charles Mwansasu') }}</span>
                                                        </div>
                                                        <div class="signatory-name">{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}</div>
                                                        <div class="signatory-title">{{ \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }} • TEVDA</div>
                                                    </td>

                                                    <td style="width: 52%; vertical-align: top; padding-left: 2pt;">
                                                        <div class="helpline-container">
                                                            <strong style="color: #34d399; font-size: 5pt; display: block; margin-bottom: 1pt;">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                                                            {{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}<br>
                                                            <strong>Helpline:</strong> {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}<br>
                                                            <strong>Support:</strong> {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }} • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <div class="barcode-box">
                                                <div class="barcode-bars">||| | |||| | ||| |||| | || ||| |||| | || | |||</div>
                                                <div class="barcode-number">
                                                    *{{ $m->membership_number }}*
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
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if(($index + 1) % 3 == 0 && ($index + 1) < count($preparedMembers))
            <div style="page-break-before: always;"></div>
        @endif
    @endforeach

</body>
</html>
