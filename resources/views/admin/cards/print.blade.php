<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print ID Card — {{ $member->membership_number }}</title>
    <style>
        @page {
            size: 85.6mm 53.98mm; /* ISO/IEC 7810 ID-1 CR80 dimensions */
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #0f172a;
            color: #ffffff;
        }
        .no-print {
            padding: 12px 24px;
            background: #1e293b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            border-bottom: 1px solid #334155;
        }
        .btn {
            background: #10b981;
            color: #022c22;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #34d399;
        }
        .btn-secondary {
            background: #334155;
            color: #f8fafc;
            margin-right: 8px;
        }
        .btn-secondary:hover {
            background: #475569;
        }
        .print-canvas {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
            padding: 30px;
        }

        /* CR80 Card Dimensions */
        .card-frame {
            width: 85.6mm;
            height: 53.98mm;
            border-radius: 3.5mm;
            overflow: hidden;
            position: relative;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.6);
            border: 1px solid #10b981;
            background: #042f2e;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Themes */
        .theme-emerald { background: #042f2e; border-color: #10b981; color: #ffffff; }
        .theme-midnight { background: #0b0f19; border-color: #f59e0b; color: #ffffff; }
        .theme-cyan { background: #052033; border-color: #06b6d4; color: #ffffff; }
        .theme-clean { background: #ffffff; border-color: #059669; color: #0f172a; }

        /* Tanzania National Flag Ribbon */
        .tz-banner {
            height: 1mm;
            width: 100%;
            display: flex;
        }
        .tz-green { width: 33.33%; height: 1mm; background: #1eb53a; }
        .tz-yellow { width: 33.34%; height: 1mm; background: #fcd116; }
        .tz-blue { width: 33.33%; height: 1mm; background: #00a3dd; }

        /* Front Elements */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5mm 3mm 1mm 3mm;
            border-bottom: 0.5px solid rgba(255,255,255,0.15);
        }
        .theme-clean .card-header {
            border-bottom: 0.5px solid #e2e8f0;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 2mm;
        }
        .logo-img {
            height: 5.5mm;
            max-width: 12mm;
            object-fit: contain;
        }
        .logo-placeholder {
            width: 5.5mm;
            height: 5.5mm;
            background: #10b981;
            color: #ffffff;
            border-radius: 1mm;
            font-weight: 900;
            font-size: 2.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-title {
            font-size: 3.4mm;
            font-weight: 900;
            line-height: 1;
            letter-spacing: 0.2mm;
            color: #ffffff;
        }
        .theme-clean .brand-title { color: #064e3b; }
        .brand-sub {
            font-size: 1.7mm;
            color: #34d399;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .theme-midnight .brand-sub { color: #fbbf24; }
        .theme-cyan .brand-sub { color: #38bdf8; }
        .theme-clean .brand-sub { color: #047857; }

        .tier-badge {
            font-size: 1.8mm;
            background: #065f46;
            color: #a7f3d0;
            padding: 0.6mm 1.6mm;
            border-radius: 1mm;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2mm;
            border: 0.3mm solid #10b981;
            white-space: nowrap;
        }
        .theme-midnight .tier-badge { background: #451a03; color: #fde68a; border-color: #f59e0b; }
        .theme-cyan .tier-badge { background: #164e63; color: #cffafe; border-color: #06b6d4; }
        .theme-clean .tier-badge { background: #d1fae5; color: #065f46; border-color: #059669; }

        .card-body {
            display: flex;
            align-items: center;
            gap: 2.5mm;
            padding: 1.5mm 3mm;
            flex-grow: 1;
        }
        .photo-box {
            width: 15.5mm;
            height: 19.5mm;
            border-radius: 1.2mm;
            border: 0.5mm solid #10b981;
            overflow: hidden;
            background: #0f172a;
            flex-shrink: 0;
        }
        .theme-midnight .photo-box { border-color: #f59e0b; }
        .theme-cyan .photo-box { border-color: #06b6d4; }
        .theme-clean .photo-box { border-color: #059669; background: #e2e8f0; }

        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2mm;
            color: #94a3b8;
            font-weight: bold;
        }

        .info-col {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.4mm;
        }
        .info-lbl {
            font-size: 1.4mm;
            color: #94a3b8;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: 0.1mm;
        }
        .theme-clean .info-lbl { color: #64748b; }

        .info-name {
            font-size: 2.8mm;
            font-weight: 900;
            line-height: 1.1;
            color: #ffffff;
            text-transform: uppercase;
        }
        .theme-clean .info-name { color: #0f172a; }

        .info-num {
            font-size: 2.3mm;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #f59e0b;
            line-height: 1;
            background: #022c22;
            padding: 0.4mm 1.2mm;
            border-radius: 0.8mm;
            border: 0.3mm solid #f59e0b;
            display: inline-block;
            width: fit-content;
        }
        .theme-midnight .info-num { background: #030712; color: #fbbf24; border-color: #d97706; }
        .theme-cyan .info-num { background: #031926; color: #38bdf8; border-color: #0284c7; }
        .theme-clean .info-num { background: #f0fdf4; color: #047857; border-color: #059669; }

        .info-sub {
            font-size: 1.9mm;
            color: #cbd5e1;
            line-height: 1.2;
        }
        .theme-clean .info-sub { color: #334155; }

        .qr-box {
            width: 13.5mm;
            height: 13.5mm;
            background: #ffffff;
            padding: 0.6mm;
            border-radius: 1mm;
            flex-shrink: 0;
            border: 0.3mm solid #cbd5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .qr-img {
            width: 100%;
            height: 100%;
        }

        .micro-security-strip {
            height: 2mm;
            background: rgba(0,0,0,0.35);
            font-size: 1.1mm;
            line-height: 2mm;
            text-transform: uppercase;
            letter-spacing: 0.2mm;
            text-align: center;
            color: #6ee7b7;
            overflow: hidden;
            white-space: nowrap;
        }
        .theme-clean .micro-security-strip { background: #e2e8f0; color: #047857; }

        .card-footer {
            border-top: 0.5px solid rgba(255,255,255,0.15);
            padding: 0.8mm 2mm;
            font-size: 1.6mm;
            font-weight: bold;
            color: #f59e0b;
            text-align: center;
            letter-spacing: 0.2mm;
            text-transform: uppercase;
            background: rgba(0,0,0,0.5);
        }
        .theme-clean .card-footer {
            border-top: 0.5px solid #cbd5e1;
            background: #f1f5f9;
            color: #047857;
        }

        /* Back Elements */
        .magnetic-bar {
            height: 5.5mm;
            background: #020617;
            border-bottom: 0.3mm solid #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 3mm;
            font-family: 'Courier New', monospace;
            font-size: 1.7mm;
            font-weight: bold;
            color: #cbd5e1;
        }
        .back-body {
            padding: 1.5mm 3mm;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .back-terms {
            font-size: 1.45mm;
            line-height: 1.25;
            color: #94a3b8;
            text-align: justify;
        }
        .theme-clean .back-terms { color: #475569; }

        .back-grid {
            display: flex;
            justify-content: space-between;
            gap: 2mm;
            margin-top: 1mm;
        }
        .sig-col {
            width: 48%;
        }
        .sig-box {
            border-bottom: 0.3mm solid #475569;
            padding-bottom: 0.3mm;
            margin-bottom: 0.3mm;
            font-family: 'Brush Script MT', cursive, sans-serif;
            font-size: 2.8mm;
            color: #34d399;
            font-style: italic;
        }
        .theme-midnight .sig-box { color: #fbbf24; }
        .theme-cyan .sig-box { color: #38bdf8; }
        .theme-clean .sig-box { color: #047857; }

        .sig-name {
            font-size: 1.7mm;
            font-weight: bold;
            color: #ffffff;
        }
        .theme-clean .sig-name { color: #0f172a; }

        .sig-title {
            font-size: 1.3mm;
            color: #94a3b8;
        }
        .theme-clean .sig-title { color: #64748b; }

        .contact-box {
            width: 50%;
            background: rgba(0,0,0,0.3);
            border-radius: 1mm;
            padding: 1mm 1.5mm;
            font-size: 1.45mm;
            line-height: 1.25;
            color: #cbd5e1;
            border: 0.3mm solid rgba(255,255,255,0.08);
        }
        .theme-clean .contact-box {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .barcode-box {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 2mm;
            font-weight: bold;
            color: #94a3b8;
            letter-spacing: 0.3mm;
            margin-top: 0.5mm;
        }

        .return-notice {
            font-size: 1.25mm;
            color: #94a3b8;
            text-align: center;
            text-transform: uppercase;
            padding: 0.8mm 2mm;
            background: rgba(0,0,0,0.4);
            border-top: 0.3mm solid rgba(255,255,255,0.08);
        }
        .theme-clean .return-notice {
            background: #e2e8f0;
            border-top: 0.3mm solid #cbd5e1;
            color: #475569;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: none;
            }
            .print-canvas {
                padding: 0;
                gap: 0;
            }
            .card-frame {
                box-shadow: none;
                page-break-after: always;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>TEVDA Official ID Card Print Preview</strong> — {{ $member->full_name }} ({{ $member->membership_number }})
        </div>
        <div>
            <a href="{{ route('admin.cards.show', $card->id) }}" class="btn btn-secondary">&larr; Back to Details</a>
            <button onclick="window.print()" class="btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Plastic ID Card
            </button>
        </div>
    </div>

    <div class="print-canvas">
        <!-- FRONT -->
        <div class="card-frame theme-{{ $theme ?? 'emerald' }}">
            <div class="tz-banner">
                <span class="tz-green"></span><span class="tz-yellow"></span><span class="tz-blue"></span>
            </div>

            <div class="card-header">
                <div class="brand-box">
                    @if(\App\Models\Setting::hasCustomLogo())
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" class="logo-img" alt="Logo">
                    @else
                        <div class="logo-placeholder">TEV</div>
                    @endif
                    <div>
                        <div class="brand-title">TEVDA</div>
                        <div class="brand-sub">Tanzania Electric Vehicles Drivers Association</div>
                    </div>
                </div>
                <div class="tier-badge">{{ $member->category->name }}</div>
            </div>

            <div class="card-body">
                <div class="photo-box">
                    @if($member->passport_photo_path)
                        <img src="{{ asset('storage/' . $member->passport_photo_path) }}" class="photo-img" alt="{{ $member->full_name }}">
                    @else
                        <div class="photo-placeholder">PHOTO</div>
                    @endif
                </div>

                <div class="info-col">
                    <span class="info-lbl">Member Name / Jina</span>
                    <span class="info-name">{{ $member->full_name }}</span>

                    <span class="info-lbl">Member ID / Namba</span>
                    <div>
                        <span class="info-num">{{ $member->membership_number }}</span>
                    </div>

                    <span class="info-lbl">Region & Territory</span>
                    <span class="info-sub">
                        <strong>{{ $member->region?->name ?? 'Tanzania' }}</strong>
                        @if($member->district) • {{ $member->district->name }} @endif
                    </span>

                    <span class="info-lbl">Validity / Hali</span>
                    <span class="info-sub">
                        <strong style="color: #f59e0b;">{{ $card->expiry_date ? 'EXP: ' . $card->expiry_date->format('m/Y') : 'ACTIVE' }}</strong>
                        <strong style="color: #34d399; margin-left: 2px;">• VERIFIED</strong>
                    </span>
                </div>

                <div class="qr-box">
                    <img src="{{ $qrCodeUri }}" class="qr-img" alt="QR">
                </div>
            </div>

            <div class="micro-security-strip">
                • TANZANIA ELECTRIC VEHICLES DRIVERS ASSOCIATION • OFFICIAL SECURE SMART ID • TEVDA CERTIFIED •
            </div>

            <div class="card-footer">
                SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ
            </div>
        </div>

        <!-- BACK -->
        <div class="card-frame theme-{{ $theme ?? 'emerald' }}">
            <div class="magnetic-bar">
                <span>CARD ID: {{ $card->card_number }}</span>
                <span style="color: #10b981; font-size: 1.4mm;">OFFICIAL SMART BADGE</span>
            </div>

            <div class="back-body">
                <div class="back-terms">
                    This official smart ID card certifies that the cardholder is a registered and compliant member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations.
                </div>

                <div class="back-grid">
                    <div class="sig-col">
                        <span class="info-lbl" style="margin-bottom: 0.5mm;">Authorized Signatory</span>
                        <div class="sig-box">{{ \App\Models\Setting::get('chairman_name', 'Charles Mwansasu') }}</div>
                        <div class="sig-name">{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}</div>
                        <div class="sig-title">{{ \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }} • TEVDA</div>
                    </div>

                    <div class="contact-box">
                        <strong style="color: #34d399; font-size: 1.6mm; display: block; margin-bottom: 0.5mm;">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                        {{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}<br>
                        <strong>Helpline:</strong> {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}<br>
                        <strong>Support:</strong> {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }} • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}
                    </div>
                </div>

                <div class="barcode-box">
                    ||| | |||| | ||| |||| | || ||| |||| | || | |||
                </div>

                <div class="micro-security-strip" style="margin: 0.5mm 0;">
                    • PROPERTY OF TEVDA • ENCRYPTED SMART ID • ISO/IEC 7810 ID-1 •
                </div>
            </div>

            <div class="return-notice">
                IF FOUND PLEASE RETURN TO ANY TEVDA OFFICE OR POLICE STATION
            </div>
        </div>
    </div>

</body>
</html>
